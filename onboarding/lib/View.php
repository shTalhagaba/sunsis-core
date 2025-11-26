<?php
class View extends CoreView
{

	public function getViewName()
	{
		return $this->name ? $this->name : get_class($this);
	}

	public function setViewName($name)
	{
		$this->name = $name;
	}

	public function getColumns($link)
	{
		$class = get_class($this);
		$columns = DAO::getResultSet($link, "select sequence,concat(ucase(left(colum,1)),lcase(right(colum,length(colum)-1))),colum from view_columns where view='$class' and user='master' order by sequence");
		return $columns;
	}


	public function getSelectedColumnsNumbers($link)
	{
		$class = get_class($this);
		$selected = DAO::getSingleColumn($link, "select sequence from view_columns where user='master' and view='$class' and visible=1 and concat(view,colum) not in (select concat(view,colum) from view_columns where user='{$_SESSION['user']->username}') order by sequence");
		$selected2 = implode(",",$selected);
		return $selected2;
	}

	public function getSelectedColumns($link)
	{
		$class = $this->name == '' ? get_class($this) : $this->name;
		$selected = DAO::getSingleColumn($link, "select colum from view_columns where user='master' and view='$class' and visible=1 and concat(view,colum) not in (select concat(view,colum) from view_columns where user='{$_SESSION['user']->username}') order by sequence");
		return $selected;
	}

	static public function getViewFromSession($key, $viewName = '')
	{
		$view = isset($_SESSION[$key]) ? $_SESSION[$key] : NULL; /* @var $view View */

		if($viewName == '')
		{
			return $view;
		}
		else
		{
			if(is_null($view) || !($view instanceof VoltView) || ($view->getViewName() != $viewName) )
			{
				return null;
			}
			else
			{
				return $view;
			}
		}
	}


	public function addFilter(IViewFilter $f)
	{
		$f->setParentView($this);
		$this->filters[$f->getName()] = $f;
	}


	public function hasFilter($filterName)
	{
		return array_key_exists($filterName, $this->filters);
	}

	public function getFilter($filterName)
	{
		if(array_key_exists($filterName, $this->filters))
		{
			return $this->filters[$filterName];
		}
		else
		{
			return null;
		}
	}

	public function getFilterHTML($filterName)
	{
		if(array_key_exists($filterName, $this->filters))
		{
			return $this->filters[$filterName]->toHTML();
		}
		else
		{
			return "";
		}
	}


	public function getFilterValue($filterName)
	{
		if(array_key_exists($filterName, $this->filters))
		{
			return $this->filters[$filterName]->getValue();
		}
		else
		{
			throw new Exception("Unknown filter '$filterName'");
		}
	}


	public function resetFilters()
	{
		$filters_have_changed = false;
		foreach($this->filters as $f)
		{
			$value_before_reset = $f->getValue();
			$f->reset();
			$filters_have_changed = $filters_have_changed || ($f->getValue() != $value_before_reset);
		}
		return $filters_have_changed;
	}



	public function refresh(PDO $link, array $form_submission)
	{
		try
		{
			// Update filter settings from user's form submission
			$filter_values_have_changed = false; // flag

			// check to see if we are applying a saved filter
			if(isset($_REQUEST['savedFilter']) AND $_REQUEST['savedFilter'] > 0)
			{
				// trim off the flag to indicate user or global
				$_REQUEST['savedFilter'] = substr($_REQUEST['savedFilter'], 1);

				$savedFilter = SavedFilters::getSavedFilter($link, $_REQUEST['savedFilter']);
				$savedFilter->filters = unserialize($savedFilter->filters);
				foreach($savedFilter->filters AS $key => $val)
				{
					if(isset($this->filters["$key"]))
					{
						$filter_values_have_changed = $filter_values_have_changed || $this->filters["$key"]->getValue() != $val;
						$this->filters["$key"]->setValue($val);
					}
				}
			}

			/*
					 * re 26/08/2011
					 * --------
					 * adding in a reset option like CLM
					 * requested by RTTG for part of the Recruitment Manager
					 * 	requirements
					 */
			if(array_key_exists("_reset", $form_submission)){
				$filter_values_have_changed = $this->resetFilters();
			}

			// Update selected values in filters
			foreach($form_submission as $key=>$value)
			{
				if(preg_match('/^'.get_class($this).'_(.+)/', $key, $matches))
				{
					if(array_key_exists($matches[1], $this->filters))
					{
						$filter_values_have_changed = ($this->filters[$matches[1]]->setValue($value)) || $filter_values_have_changed;
					}
					else
					{
						$this->setPreference($matches[1], $value);
					}
				}
			}

			// Refresh dropdown filter options
			$this->updateFilterOptions($link, $filter_values_have_changed, 20);

			// Check to see if we're saving a saved filter
			if(!empty($_REQUEST['filter_name']))
			{
				$filterData = array();
				foreach($this->filters AS $key => $val)
				{
					$filterData["$key"] = $val->getValue();
				}
				if(!isset($_REQUEST['filter_id']))
				{
					$_REQUEST['filter_id'] = 0;
				}
				SavedFilters::saveFilter($link, $_REQUEST['filter_id'], $_REQUEST['filter_name'], $this->getFilterURLBits(), $_SESSION['user']->username, $filterData);
			}

			$this->savedFilters = $filter = SavedFilters::getSavedFilters($link, $this->getFilterURLBits(), $_SESSION['user']->username);

			// Change the page number if the filters have changed or if the user
			// has specified a choice
			if($filter_values_have_changed)
			{
				// When the filters change, the view must always begin at page 1
				$this->setPreference(View::KEY_PAGE_NUMBER, 1);
				$this->pageNumber = 1;
			}
			elseif(!is_null($this->getPreference(View::KEY_PAGE_NUMBER)))
			{
				// Use the user's choice of page number
				$this->pageNumber = $this->getPreference(View::KEY_PAGE_NUMBER);
			}

			$this->updateRowCount($link, $filter_values_have_changed, 20);
		}
		catch(Exception $e)
		{
			// Clear all views from the user's session
			foreach ($_SESSION as $key=>$value) {
				if (preg_match('/^view_/', $key)) {
					unset($_SESSION[$key]);
				}
			}
			throw $e; // Rethrow
		}


		return $filter_values_have_changed;
	}


	public function setPreferences(array $form_submission)
	{
		foreach($form_submission as $key=>$value)
		{
			if(!array_key_exists($key, $this->filters))
			{
				$this->preferences[$key] = $value;
			}
		}
	}


	public function setPreference($key, $value)
	{
		if(!array_key_exists($key, $this->filters))
		{
			$this->preferences[$key] = $value;
		}
	}


	public function getPreference($key)
	{
		if(array_key_exists($key, $this->preferences))
		{
			return $this->preferences[$key];
		}
		else
		{
			return null;
		}
	}


	public function setSQL($sql)
	{
		// Remove commented SQL
		$sql = preg_replace('#/\\*.*?\\*/#ms', '', $sql);
		$sql = preg_replace('/^\\s*(#|--\\+).*?$/m', '', $sql);

		return $this->sql = $sql;
	}

	/**
	 * Returns the View's SQL after modification with filters and paging
	 * @return string
	 */
	public function getSQL()
	{
		return $this->getSQLStatement()->__toString();
	}

	/**
	 * Returns a new SQLStatement object, with SQL modified by filters and paging
	 * @return SQLStatement
	 */
	public function getSQLStatement()
	{
		// Start with the basic statement for this view
		$s = new SQLStatement($this->sql);

		// Paging filters treated separately (good argument for subclassing ViewFilter later)
		if(array_key_exists(View::KEY_PAGE_SIZE, $this->filters))
		{
			$records_per_page = (integer)$this->filters[View::KEY_PAGE_SIZE]->getValue();
			if($records_per_page > 0)
			{
				$skip = ($this->pageNumber - 1) * $records_per_page;
				$s->setClause('LIMIT ' . $skip . ',' . $this->filters[View::KEY_PAGE_SIZE]->getValue());
			}
			else
			{
				$s->removeClause('LIMIT');
			}
		}

		// Modify the basic statement with conditional clauses from view filters
		foreach($this->filters as $key=>$filter)
		{
			if(($key == View::KEY_PAGE_SIZE) || ($key == View::KEY_PAGE_NUMBER) )
			{
				continue; // These two filters have to be excluded -- they are a special case
			}

			$filter_statement = $filter->getSQLStatement();
			if(!is_null($filter_statement))
			{
				$s->appendStatement($filter_statement);
			}
		}

		// Remove any references to date constants which will stop the query result
		// from being stored in the MySQL query cache
		$s->replaceDateTimeConstants();

		return $s;
	}




	public function getRowCount()
	{
		return $this->rowCount;
	}


	/**
	 * Updates the row count
	 * @param PDO $link database connection
	 * @param boolean $force_update force update even if cache time is not exceeded
	 * @param integer cache_time_in_seconds time to cache row count
	 */
	private function updateRowCount(PDO $link, $force_update = false, $cache_time_in_seconds = 20)
	{
		if($force_update || (time() - $cache_time_in_seconds) > $this->last_row_count_update)
		{
			$s = $this->getSQLStatement();
			$s->removeClause("order by");
			$s->setClause("LIMIT 1");
			if(!$s->hasClause("having") && !$s->hasClause("group by") && !preg_match("/^SELECT.+?DISTINCT/si", $s->getClause("select")))
			{
				$s->setClause("SELECT SQL_CALC_FOUND_ROWS 1", true);
			}
			else
			{
				$s->setClause("SELECT SQL_CALC_FOUND_ROWS ".substr($s->getClause("select"), 7));
			}
			$query = $s->__toString();
			$rows = DAO::getResultset($link, $query);
			$this->rowCount = DAO::getSingleValue($link, "SELECT FOUND_ROWS()");
			$this->last_row_count_update = time();
		}
	}

	private function updateFilterOptions(PDO $link, $force_update = false, $cache_time_in_seconds = 20)
	{
		$null_options_found = false;
		foreach($this->filters as $filter)
		{
			if($null_options_found = ($filter instanceof DropDownViewFilter && is_null($filter->getOptions())) ){
				break;
			}
		}

		if($force_update || $null_options_found || (time() - $cache_time_in_seconds > $this->last_filter_option_update))
		{
			foreach($this->filters as $filter)
			{
				if($filter instanceof DropDownViewFilter OR $filter instanceof CheckboxViewFilter)
				{
					$filter->refresh($link);
				}
			}
			$this->last_filter_option_update = time();
		}
	}


	/**
	 * Enter description here...
	 *
	 */
	public function getViewNavigator($al = 'center')
	{
		if(!array_key_exists(View::KEY_PAGE_SIZE, $this->filters))
		{
			$records_per_page = 0;
		}
		else
		{
			$records_per_page = (integer) $this->filters[View::KEY_PAGE_SIZE]->getValue();
		}


		if($records_per_page > 0)
		{
			$numPages = ceil($this->rowCount / $records_per_page);
		}
		else
		{
			$numPages = 1;
		}

		// View objects keep their state, so the URL needs only to contain the action
		// and the page number.  The event (window.navigator_onclick()) can be used to
		// handle more complex page transitions that require the addition of further
		// querystring parameters e.g. when the a View object is used on the student enrollment form.
		if(preg_match('/[&]{0,1}_action=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0)
		{
			$qs = '_action='.$matches[1].'&'; // extract the action
		}
		else
		{
			$qs = '';
		}

		if(preg_match('/[&]{0,1}subview=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0)
		{
			$qs .= 'subview='.$matches[1].'&'; // extract the subview
		}

		// ick: id is now passed on with next prev option
		if(preg_match('/[&]{0,1}id=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0)
		{
			$qs .= 'id='.$matches[1].'&'; // extract the id
		}


		$pageNumberFieldName = get_class($this).'_'.View::KEY_PAGE_NUMBER;
		$qsFirst 	= $qs . $pageNumberFieldName . '=' . '1';
		$qsPrevious = $qs . $pageNumberFieldName . '=' . ($this->pageNumber - 1);
		$qsNext 	= $qs . $pageNumberFieldName . '=' . ($this->pageNumber + 1);
		$qsLast 	= $qs . $pageNumberFieldName . '=' . ($numPages);
		$viewName	= $this->getViewName();
		$pageNumber = $this->pageNumber;
		$pageNumberNext = $pageNumber + 1;
		$pageNumberPrev = $pageNumber - 1;

		// Page number dropdown
		$dropdown = "<select onchange=\"window.location.href='?{$qs}{$pageNumberFieldName}='+this.value;\">";
		$digits = strlen($numPages);
		for($i = 1; $i <= $numPages; $i++)
		{
			if($i != $this->pageNumber)
			{
				// done this way to add leading 0's where required
				$dropdown .= sprintf("<option value=\"%1\$d\">%1\${$digits}d</option>\n", $i);
			}
			else
			{
				$dropdown .= sprintf("<option value=\"%1\$d\" selected=\"selected\">%1\${$digits}d</option>\n", $i);
			}
		}
		$dropdown .= "</select>";

		if($al=='left')												// Khushnood
			$html  = '<div align="left" class="viewNavigator">'; 	// Khushnood
		else														// Khushnood
			$html  = '<div align="center" class="viewNavigator">';
		$html .= '<table width="450"><tr>';

		/*if($this->pageNumber <= 1)
		{
			$html .= '<td width="20%" align="left" style="color: silver">|&lt;&lt;&nbsp;&nbsp;&nbsp;&nbsp;&lt;</td>';
		}
		else
		{
			$html .= <<<HEREDOC
<td width="20%" align="left">
<a href="?$qsFirst" title="first page"
onclick="if(window.navigator__onclick){return window.navigator__onclick('$viewName', this, '$pageNumberFieldName', 1, arguments.length > 0 ? arguments[0] : window.event);} else {return true;}">|&lt;&lt;</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="?$qsPrevious" title="previous page"
onclick="if(window.navigator__onclick){return window.navigator__onclick('$viewName', this, '$pageNumberFieldName', $pageNumberPrev, arguments.length > 0 ? arguments[0] : window.event);} else {return true;}">&lt;</a>
</td>
HEREDOC;
		}*/

		if($this->pageNumber <= 1)
		{
			$html .= <<<HEREDOC
<td width="20%" align="right"><button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/first-grey.gif" width="10" height="16" border="0"/></button>
<button style="width:30px" disabled="disabled"><img src="/images/view-navigation/previous-grey.gif" width="8" height="16" border="0"/></button></td>
HEREDOC;
		}
		else
		{
			$html .= <<<HEREDOC
<td width="20%" align="right"><button onclick="this.disabled=true;window.location.href='?$qsFirst';return false;" style="width:30px;margin-right:12px;" title="First page"><img src="/images/view-navigation/first.gif" width="10" height="16" border="0"/></button>
<button onclick="this.disabled=true;window.location.href='?$qsPrevious';return false;" style="width:30px" title="Previous page"><img src="/images/view-navigation/previous.gif" width="8" height="16" border="0"/></button></td>
HEREDOC;
		}

		//$html .= '<td align="center" width="60%">page ' . $this->pageNumber . ' of ' . $numPages . ' (<span id="page-totalrows">' . ($this->rowCount == '' ? 0 : $this->rowCount) . '</span> records)</td>';
		$html .= '<td align="center" width="60%" valign="middle">page ' . $dropdown . ' of ' . $numPages . ' (' . $this->rowCount . ' records)</td>';
		/*if($this->pageNumber < $numPages)
		{
			//$qsNext .= '&id='. $_SERVER['QUERY_STRING'];
			$html .= <<<HEREDOC
<td width="20%" align="right"><a href="?$qsNext" title="next page"
onclick="if(window.navigator__onclick){return window.navigator__onclick('$viewName', this, '$pageNumberFieldName', $pageNumberNext, arguments.length > 0 ? arguments[0] : window.event);} else {return true;}">&gt;</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="?$qsLast" title="last page"
onclick="if(window.navigator__onclick){return window.navigator__onclick('$viewName', this, '$pageNumberFieldName', $numPages, arguments.length > 0 ? arguments[0] : window.event);} else {return true;}">&gt;&gt;|</a>
</td>
HEREDOC;
		}
		else
		{
			$html .= '<td width="20%" align="right" style="color: silver">&gt;&nbsp;&nbsp;&nbsp;&nbsp;&gt;&gt;|</td>';
		}
		*/
		if($this->pageNumber < $numPages)
		{
			$html .= <<<HEREDOC
<td width="20%" align="left"><button onclick="this.disabled=true;window.location.href='?$qsNext';return false;" style="width:30px;margin-right:12px;" title="Next page"><img src="/images/view-navigation/next.gif" width="8" height="16" border="0"/></button>
<button onclick="this.disabled=true;window.location.href='?$qsLast';return false;" style="width:30px" title="Final page"><img src="/images/view-navigation/last.gif" width="10" height="16" border="0"/></button></td>
HEREDOC;
		}
		else
		{
			$html .= <<<HEREDOC
<td width="20%" align="left"><button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/next-grey.gif" width="8" height="16" border="0"/></button>
<button style="width:30px" disabled="disabled"><img src="/images/view-navigation/last-grey.gif" width="10" height="16" border="0"/></button></td>
HEREDOC;
		}
		$html .= '</tr></table></div>';

		return $html;
	}

	public function getViewNavigatorExtra($al = 'center', $subview = '')
	{
		if(!array_key_exists(View::KEY_PAGE_SIZE, $this->filters))
		{
			$records_per_page = 0;
		}
		else
		{
			$records_per_page = (integer) $this->filters[View::KEY_PAGE_SIZE]->getValue();
		}


		if($records_per_page > 0)
		{
			$numPages = ceil($this->rowCount / $records_per_page);
		}
		else
		{
			$numPages = 1;
		}

		if(preg_match('/[&]{0,1}_action=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0)
		{
			$qs = '_action='.$matches[1].'&'; // extract the action
		}
		else
		{
			$qs = '';
		}

		if(preg_match('/[&]{0,1}subview=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0)
		{
			$qs .= 'subview='.$matches[1].'&'; // extract the subview
		}


		if(preg_match('/[&]{0,1}id=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0)
		{
			$qs .= 'id='.$matches[1].'&'; // extract the id
		}


		$pageNumberFieldName = get_class($this).'_'.View::KEY_PAGE_NUMBER;
		$qsFirst 	= $qs . $pageNumberFieldName . '=' . '1';
		$qsPrevious = $qs . $pageNumberFieldName . '=' . ($this->pageNumber - 1);
		$qsNext 	= $qs . $pageNumberFieldName . '=' . ($this->pageNumber + 1);
		$qsLast 	= $qs . $pageNumberFieldName . '=' . ($numPages);
		$viewName	= $this->getViewName();
		$pageNumber = $this->pageNumber;
		$pageNumberNext = $pageNumber + 1;
		$pageNumberPrev = $pageNumber - 1;

		// Page number dropdown
		$dropdown = "<select onchange=\"window.location.href='?{$qs}{$pageNumberFieldName}='+this.value;\">";
		$digits = strlen($numPages);
		for($i = 1; $i <= $numPages; $i++)
		{
			if($i != $this->pageNumber)
			{
				// done this way to add leading 0's where required
				$dropdown .= sprintf("<option value=\"%1\$d\">%1\${$digits}d</option>\n", $i);
			}
			else
			{
				$dropdown .= sprintf("<option value=\"%1\$d\" selected=\"selected\">%1\${$digits}d</option>\n", $i);
			}
		}
		$dropdown .= "</select>";

		if($al=='left')												// Khushnood
			$html  = '<div align="left" class="viewNavigator">'; 	// Khushnood
		else														// Khushnood
			$html  = '<div align="center" class="viewNavigator">';
		$html .= '<table width="450"><tr>';

		if($this->pageNumber <= 1)
		{
			$html .= <<<HEREDOC
<td width="20%" align="right"><button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/first-grey.gif" width="10" height="16" border="0"/></button>
<button style="width:30px" disabled="disabled"><img src="/images/view-navigation/previous-grey.gif" width="8" height="16" border="0"/></button></td>
HEREDOC;
		}
		else
		{
			$html .= <<<HEREDOC
<td width="20%" align="right"><button onclick="this.disabled=true;window.location.href='?$qsFirst';return false;" style="width:30px;margin-right:12px;" title="First page"><img src="/images/view-navigation/first.gif" width="10" height="16" border="0"/></button>
<button onclick="this.disabled=true;window.location.href='?$qsPrevious';return false;" style="width:30px" title="Previous page"><img src="/images/view-navigation/previous.gif" width="8" height="16" border="0"/></button></td>
HEREDOC;
		}

		$html .= '<td align="center" width="60%" valign="middle">page ' . $dropdown . ' of ' . $numPages . ' (' . $this->rowCount . ' records)</td>';
		if($this->pageNumber < $numPages)
		{
			$html .= <<<HEREDOC
<td width="20%" align="left"><button onclick="this.disabled=true;window.location.href='?$qsNext';return false;" style="width:30px;margin-right:12px;" title="Next page"><img src="/images/view-navigation/next.gif" width="8" height="16" border="0"/></button>
<button onclick="this.disabled=true;window.location.href='?$qsLast';return false;" style="width:30px" title="Final page"><img src="/images/view-navigation/last.gif" width="10" height="16" border="0"/></button></td>
HEREDOC;
		}
		else
		{
			$html .= <<<HEREDOC
<td width="20%" align="left"><button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/next-grey.gif" width="8" height="16" border="0"/></button>
<button style="width:30px" disabled="disabled"><img src="/images/view-navigation/last-grey.gif" width="10" height="16" border="0"/></button></td>
HEREDOC;
		}
		$html .= '</tr></table></div>';

		return $html;
	}


	/**
	 * Default rendering method. This should always be overridden in subclasses.
	 */
	public function render(PDO $link)
	{
		$st = $link->query($this->getSQL());
		if ($st) {
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr>';

			$fields = $result->fetch_fields();
			foreach ($fields as $field) {
				echo '<th>' . htmlspecialchars($field->name ?? '', ENT_QUOTES, 'UTF-8') . '</th>';
			}

			echo '</tr></thead><tbody>';

			while ($row = $st->fetch()) {
				echo '<tr>';
				foreach ($fields as $field) {
					echo '<td>' . htmlspecialchars($row[$field->name] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
				}
				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator();
		}
	}

	public function renderWithTitle(PDO $link, $title)
	{

	}

	/**
	 * Default export to CSV format.  Should always be overridden in subclasses.
	 */
	public function exportToCSV(PDO $link, $columns)
	{
		$statement = $this->getSQLStatement();
		$statement->removeClause('limit');//$statement->setClause()
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' . $this->getViewName() . '.csv"');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}

			$columns = explode(",", $columns);
			if($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$line = '';
				foreach($row as $field=>$value)
				{
					if(in_array($field, $columns))
					{
						if(strlen($line) > 0)
						{
							$line .= ',';
						}
						$line .= '"' . str_replace('"', '""', $field) . '"';
					}
				}
				echo $line . "\r\n";

				do
				{
					$line = '';

					foreach($row as $field=>$value)	{
						if(in_array($field, $columns)) {
                            if(strlen($line) > 0)
                            {
                                $line .= ',';
                            }
                            $line .= '"' . str_replace('"', '""', $value) . '"';
						}
					}
					echo $line."\r\n";
				} while($row = $st->fetch(PDO::FETCH_ASSOC));
			}
		}
		else
		{
			throw new DatabaseException($link, $statement->__toString());
		}
	}
	public function getPageNumber()
	{
		return $this->pageNumber;
	}


	public function getFilterCrumbs()
	{
		$html = '<div id="div_filter_crumbs" class="filterCrumbs" onclick="if(window.div_filter_crumbs_onclick){window.div_filter_crumbs_onclick(this);}" title="Click to change filters">';

		foreach($this->filters as $filter)
		{
			$desc = $filter->getDescription();
			if($desc != '')
			{
				$html .= ' <span class="filterCrumb">' . str_replace(' ', '&nbsp;', htmlspecialchars($desc ?? '', ENT_QUOTES, 'UTF-8')) . '</span> ';
			}
		}

		$html .= '</div>';

		return $html;
	}


	public function __toString()
	{
		$s = $this->getSQLStatement();
		return $s->__toString();
	}

	public function renderDayNavigation($action, $date_range_filter_name)
	{
		$filter = $this->getFilter($date_range_filter_name);
		if(!$filter){
			return;
		}

		$start_date = new Date($filter->getStartDate());
		$end_date = new Date($filter->getEndDate());
		$start_month = $start_date->getMonth();
		$start_day = $start_date->getDay();
		$start_year = $start_date->getYear();
		$end_month = $end_date->getMonth();
		$end_day = $end_date->getDay();
		$end_year = $end_date->getYear();

		$today = new Date("now");
		$today_day = $today->getDay();
		$today_month = $today->getMonth();
		$today_year = $today->getYear();

		$date = new Date($filter->getStartDate());
		$days_in_month = cal_days_in_month(CAL_GREGORIAN, $date->getMonth(), $date->getYear());
		if($date->getDay() > 1){
			$date->subtractDays($date->getDay() - 1);
		}
		$starting_weekday = $date->format("w"); // Sun == 0
		$weekdays = array("Su", "Mo", "Tu", "We", "Th", "Fr", "Sa");

		echo '<div class="text-center small"><i class="text-muted">Click a numeric date to view records for a particular day or a weekday heading to view records for a particular week</i></div>';
		echo '<table class="table table-bordered MonthNavigation" cellspacing="0" cellpadding="2">';
		echo '<tr><td colspan="33" align="center" class="MonthLabel">'.$date->format("F Y").'</td></tr></tr>';

		echo '<tr>';
		echo '<td rowspan="2" class="NavigationPrevious" align="center" title="Previous month"><i class="fa fa-chevron-circle-left" style="font-size:25px;"></i> </td>';
		$weekday = $starting_weekday;
		for($i = 1; $i <= 31; $i++)
		{
			if($i > $days_in_month)
			{
				echo '<td class="DayLabel"></td>';
			}
			else
			{
				echo '<td class="DayLabel '.(($weekday == 0 || $weekday == 6) ? "Weekend":"Weekday").'" align="center">'.$weekdays[$weekday].'</td>';
				$weekday = ++$weekday > 6 ? 0:$weekday;
			}
		}
		echo '<td rowspan="2" class="NavigationNext" align="center" title="Next month"><i class="fa fa-chevron-circle-right" style="font-size:25px;"></td>';
		echo '</tr>';

		echo '<tr class="dayValues">';
		$weekday = $starting_weekday;
		for($i = 1; $i <= 31; $i++)
		{
			if($i > $days_in_month)
			{
				echo '<td class="DayValue"></td>';
			}
			else
			{
				if($i >= $start_day &&
					(($start_month == $end_month && $i <= $end_day)
						|| ($start_month < $end_month)
						|| ($start_year < $end_year)))
				{
					$selectedClass = "SelectedDay";
				}
				else
				{
					$selectedClass = "";
				}
				$todayClass = ($i == $today_day && $start_month == $today_month && $start_year == $today_year) ? "Today":"";
				echo '<td class="DayValue '.$selectedClass.' '.$todayClass.'" align="center">'.$i.'</td>';
				$weekday = ++$weekday > 6 ? 0:$weekday;
			}
		}
		echo '</tr>';
		echo '</table>';
	}

	protected $sql = '';
	private $name = '';

	//protected $viewName = '';
	protected $filters = array();
	protected $savedFilters = array();
	protected $preferences = array();

	protected $pageNumber = 1;
	protected $rowCount = null;

	protected $last_row_count_update = null;
	protected $last_filter_option_update = null;


	const KEY_PAGE_SIZE 	= '__page_size';
	const KEY_PAGE_NUMBER 	= '__page';
	const KEY_ORDER_BY 		= '__order_by';

	const FILTER_OPTIONS_TTL_SECONDS = 15;

}
