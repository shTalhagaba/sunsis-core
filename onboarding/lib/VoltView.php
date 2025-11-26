<?php
class VoltView extends CoreView
{
	/**
	 * Enter description here...
	 *
	 * @param string $viewName A unique identifier for the view
	 * @param string $sql SQL string to base the view on
	 */
	public function __construct($viewName, $sql)
	{
		if(is_null($viewName) || $viewName == '')
		{
			$this->viewName = $_SERVER['PHP_SELF']; // Default to PHP file name
		}
		else
		{
			$this->viewName = $viewName;
		}

		// Remove commented SQL
		$sql = preg_replace('#/\\*.*?\\*/#ms', '', $sql);
		$sql = preg_replace('/^\\s*(#|--\\+).*?$/m', '', $sql);

		$this->sql = $sql;
	}

	public function setSQL($sql)
	{
		// Remove commented SQL
		$sql = preg_replace('#/\\*.*?\\*/#ms', '', $sql);
		$sql = preg_replace('/^\\s*(#|--\\+).*?$/m', '', $sql);

		return $this->sql = $sql;
	}

	public function getViewName()
	{
		return $this->viewName;
	}


	public function addFilter(VoltIViewFilter $f)
	{
		$f->setParentView($this);
		$this->filters[$f->getName()] = $f;
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
			throw new Exception("No filter of name '$filterName' available.");
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
			return "";
		}
	}


	public function resetFilters()
	{
		foreach($this->filters as $f)
		{
			$f->reset();
		}
	}

	public function hasFilter($filterName)
	{
		return array_key_exists($filterName, $this->filters);
	}

	public function refresh(array $form_submission, PDO $link)
	{
		// Update filter settings from user's form submission
		$filters_have_changed = false; // flag

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
					$this->filters["$key"]->setValue($val);
				}
			}
		}

		if(!is_null($form_submission))
		{
			foreach($this->filters as $filter)
			{
				// Update the filter's state and set a flag if any of the filters change state
				$filters_have_changed = ($filter->setValue($form_submission)) || $filters_have_changed;
			}
		}

		// Refresh filters' options
		// (must occur *after* the filter values have been refreshed as some
		//  filter's options may depend on the value of other filters)
		foreach($this->filters as $filter) /* @var $filter IViewFilter */
		{
			$filter->refresh($link);
		}

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
			//$this->refresh($link, $form_submission);
		}

		$this->savedFilters = $filter = SavedFilters::getSavedFilters($link, $this->getFilterURLBits(), $_SESSION['user']->username);


		// Set the page number (or keep the current one)
		if($filters_have_changed)
		{
			// When the filters change, the view must always begin at page 1
			$this->pageNumber = 1;
		}
		elseif(array_key_exists(View::KEY_PAGE_NUMBER, $form_submission))
		{
			// Otherwise, use any user specified page number
			$this->pageNumber = (integer) $form_submission[View::KEY_PAGE_NUMBER];
		}

		// Count number of rows that will be returned by the new query
		/*$s = $this->getSQLStatement();
		$s->removeClause("limit");
		$s->removeClause("order by");
		$query = $s->__toString();
		$st = $link->query($query);
		if($result = $st->rowCount())
		{
			$this->rowCount = $result;
		}
		else
		{
			$this->rowCount = 0;
		}*/
		$this->updateRowCount($link, $filters_have_changed);

		// Store any other variables submitted that aren't filter values
		$this->setPreferences($form_submission);

		return $filters_have_changed;
	}


	/**
	 * Updates the row count
	 * @param PDO $link database connection
	 * @param boolean $force_update force update even if cache time is not exceeded
	 * @param integer cache_time_in_seconds time to cache row count
	 */
	private function updateRowCount(PDO $link, $force_update = false)
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

		return $s;
	}




	public function getRowCount()
	{
		return $this->rowCount;
	}


	/**
	 * Enter description here...
	 *
	 */
	public function getViewNavigator()
	{
		if(!array_key_exists(View::KEY_PAGE_SIZE, $this->filters))
		{
			throw new Exception("This View needs a filter of name View::KEY_PAGE_SIZE in order to use getViewNavigator() function.");
		}

		$records_per_page = (integer) $this->filters[View::KEY_PAGE_SIZE]->getValue();
		if($records_per_page > 0)
		{
			$numPages = ceil($this->rowCount / $records_per_page);
		}
		else
		{
			$numPages = 1;
		}

		// Remove any page number argument that is present in the querystring
		/*
		$qs = $_SERVER['QUERY_STRING'];
		$pos1 = strpos($qs, View::KEY_PAGE_NUMBER);
		if($pos1 !== false)
		{
			$pos2 = strpos($qs, '&', $pos1);
			
			// If the page number argument is followed by other arguments..
			if($pos2 !== false)
			{
				// Cut out the pagenumber argument
				$qs = substr($qs, 0, $pos1) . substr($qs, $pos2);
			}
			else
			{
				// If the page number argument is not the first and only argument
				if($pos1 > 0)
				{
					// Adjust $pos1 to include the preceeding ampersand too
					$pos1--;
				}
				
				// Trim the querystring to include everything up until the pagenumber argument
				$qs = substr($qs, 0, $pos1);
			}
		}
		
		if(strlen($qs) > 0)
		{
			$qs .= '&';
		}
		*/


		//$qs = preg_replace('/&{0,1}'.View::KEY_PAGE_NUMBER.'=[^&]*/', '', $_SERVER['QUERY_STRING']);
		//if(strlen($qs) > 0)
		//{
		//	$qs .= '&';
		//}


		// View objects keep their state, so the URL needs only to contain the action
		// and the page number.  The event (window.navigator_onclick()) can be used to
		// handle more complex page transitions that require the addition of further
		// querystring parameters e.g. when the a View object is used on the student enrollment form.
		if(preg_match('/[&]{0,1}_action=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0)
		{
			$qs = '_action='.$matches[1].'&';
		}
		else
		{
			$qs = '';
		}



		$qsFirst 	= $qs . View::KEY_PAGE_NUMBER . '=' . '1';
		$qsPrevious = $qs . View::KEY_PAGE_NUMBER . '=' . ($this->pageNumber - 1);
		$qsNext 	= $qs . View::KEY_PAGE_NUMBER . '=' . ($this->pageNumber + 1);
		$qsLast 	= $qs . View::KEY_PAGE_NUMBER . '=' . ($numPages);
		$viewName	= $this->viewName;
		$pageNumber = $this->pageNumber;
		$pageNumberNext = $pageNumber + 1;
		$pageNumberPrev = $pageNumber - 1;
		$pageNumberFieldName = View::KEY_PAGE_NUMBER;

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

		//$html .= '<td align="center" width="60%">page ' . $this->pageNumber . ' of ' . $numPages . ' (' . $this->rowCount . ' records)</td>';
		$html .= '<td align="center" width="60%" valign="middle">page ' . $dropdown . ' of ' . $numPages . ' (' . $this->rowCount . ' records)</td>';

		/*if($this->pageNumber < $numPages)
		{
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
		}*/
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


	public function exportToCSV($link)
	{
		$statement = $this->getSQLStatement();
		$statement->removeClause('limit');

		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' . $this->getViewName() . '.csv"');

			// Internet Explorer requires two extra headers when downloading files over HTTPS
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}

			if($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				// Write header row
				$line = '';
				foreach($row as $field=>$value)
				{
					if(strlen($line) > 0)
					{
						$line .= ',';
					}
					$line .= '"' . str_replace('"', '""', $field) . '"';
				}
				echo $line . "\r\n";

				// Write value rows
				do
				{
					$line = '';
					foreach($row as $field=>$value)
					{
						if(strlen($line) > 0)
						{
							$line .= ',';
						}
						$line .= '"' . str_replace('"', '""', $value) . '"';
					}
					echo $line . "\r\n";
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
				$html .= ' <span class="filterCrumb">' . str_replace(' ', '&nbsp;', htmlspecialchars($desc)) . '</span> ';
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

	/**
	 * Enter description here...
	 *
	 */
	public function getViewNavigatorExtra($tab = '', $subview = '')
	{
		if(!array_key_exists(View::KEY_PAGE_SIZE, $this->filters))
		{
			throw new Exception("This View needs a filter of name View::KEY_PAGE_SIZE in order to use getViewNavigator() function.");
		}

		$records_per_page = (integer) $this->filters[View::KEY_PAGE_SIZE]->getValue();
		if($records_per_page > 0)
		{
			$numPages = ceil($this->rowCount / $records_per_page);
		}
		else
		{
			$numPages = 1;
		}

		// Remove any page number argument that is present in the querystring
		/*
		$qs = $_SERVER['QUERY_STRING'];
		$pos1 = strpos($qs, View::KEY_PAGE_NUMBER);
		if($pos1 !== false)
		{
			$pos2 = strpos($qs, '&', $pos1);

			// If the page number argument is followed by other arguments..
			if($pos2 !== false)
			{
				// Cut out the pagenumber argument
				$qs = substr($qs, 0, $pos1) . substr($qs, $pos2);
			}
			else
			{
				// If the page number argument is not the first and only argument
				if($pos1 > 0)
				{
					// Adjust $pos1 to include the preceeding ampersand too
					$pos1--;
				}

				// Trim the querystring to include everything up until the pagenumber argument
				$qs = substr($qs, 0, $pos1);
			}
		}

		if(strlen($qs) > 0)
		{
			$qs .= '&';
		}
		*/


		//$qs = preg_replace('/&{0,1}'.View::KEY_PAGE_NUMBER.'=[^&]*/', '', $_SERVER['QUERY_STRING']);
		//if(strlen($qs) > 0)
		//{
		//	$qs .= '&';
		//}


		// View objects keep their state, so the URL needs only to contain the action
		// and the page number.  The event (window.navigator_onclick()) can be used to
		// handle more complex page transitions that require the addition of further
		// querystring parameters e.g. when the a View object is used on the student enrollment form.
		if(preg_match('/[&]{0,1}_action=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0)
		{
			$qs = '_action='.$matches[1].'&view='.$this->viewName.'&';
			if($tab != '')
				$qs .= 'selected_tab='.$tab.'&';
			if($subview != '')
				$qs .= 'subview='.$subview.'&';
		}
		else
		{
			$qs = '';
		}

		$qsFirst 	= $qs . View::KEY_PAGE_NUMBER . '=' . '1';
		$qsPrevious = $qs . View::KEY_PAGE_NUMBER . '=' . ($this->pageNumber - 1);
		$qsNext 	= $qs . View::KEY_PAGE_NUMBER . '=' . ($this->pageNumber + 1);
		$qsLast 	= $qs . View::KEY_PAGE_NUMBER . '=' . ($numPages);
		$viewName	= $this->viewName;
		$pageNumber = $this->pageNumber;
		$pageNumberNext = $pageNumber + 1;
		$pageNumberPrev = $pageNumber - 1;
		$pageNumberFieldName = View::KEY_PAGE_NUMBER;

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

		//$html .= '<td align="center" width="60%">page ' . $this->pageNumber . ' of ' . $numPages . ' (' . $this->rowCount . ' records)</td>';
		$html .= '<td align="center" width="60%" valign="middle">page ' . $dropdown . ' of ' . $numPages . ' (' . $this->rowCount . ' records)</td>';

		/*if($this->pageNumber < $numPages)
		{
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
		}*/
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



	protected $sql = '';

	protected $viewName = '';
	protected $filters = array();
	protected $preferences = null;
	protected $savedFilters = array();

	protected $pageNumber = 1;
	protected $rowCount = null;


	const KEY_PAGE_SIZE 	= '__page_size';
	const KEY_PAGE_NUMBER 	= '__page';
	const KEY_ORDER_BY 		= '__order_by';

}
?>