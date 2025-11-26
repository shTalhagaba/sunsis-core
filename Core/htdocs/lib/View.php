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
        $selected2 = implode(",", $selected);
        return $selected2;
    }

    public function getSelectedColumns($link)
    {
        $class = get_class($this);
        $selected = DAO::getSingleColumn($link, "select colum from view_columns where user='master' and view='$class' and visible=1 and concat(view,colum) not in (select concat(view,colum) from view_columns where user='{$_SESSION['user']->username}') order by sequence");
        return $selected;
    }

    static public function getViewFromSession($key, $viewName = '')
    {
        $view = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        /* @var $view View */

        if ($viewName == '') {
            return $view;
        } else {
            if (is_null($view) || !($view instanceof View) || ($view->getViewName() != $viewName)) {
                return null;
            } else {
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
        if (array_key_exists($filterName, $this->filters)) {
            return $this->filters[$filterName];
        } else {
            return null;
        }
    }

    public function getFilterHTML($filterName)
    {
        if (array_key_exists($filterName, $this->filters)) {
            return $this->filters[$filterName]->toHTML();
        } else {
            return "";
        }
    }


    public function getFilterValue($filterName)
    {
        if (array_key_exists($filterName, $this->filters)) {
            return $this->filters[$filterName]->getValue();
        } else {
            throw new Exception("Unknown filter '$filterName'");
        }
    }


    public function resetFilters()
    {
        $filters_have_changed = false;
        foreach ($this->filters as $f) {
            $value_before_reset = $f->getValue();
            $f->reset();
            $filters_have_changed = $filters_have_changed || ($f->getValue() != $value_before_reset);
        }
        return $filters_have_changed;
    }


    public function refresh(PDO $link, array $form_submission)
    {
        try {
            // Update filter settings from user's form submission
            $filter_values_have_changed = false; // flag

            // check to see if we are applying a saved filter
            if (isset($_REQUEST['savedFilter']) and $_REQUEST['savedFilter'] > 0) {
                // trim off the flag to indicate user or global
                $_REQUEST['savedFilter'] = substr($_REQUEST['savedFilter'], 1);

                $savedFilter = SavedFilters::getSavedFilter($link, $_REQUEST['savedFilter']);
                $savedFilter->filters = unserialize($savedFilter->filters);
                foreach ($savedFilter->filters as $key => $val) {
                    if (isset($this->filters["$key"])) {
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
            if (array_key_exists("_reset", $form_submission)) {
                $filter_values_have_changed = $this->resetFilters();
            }

            // Update selected values in filters
            foreach ($form_submission as $key => $value) {
                if (preg_match('/^' . get_class($this) . '_(.+)/', $key, $matches)) {
                    if (array_key_exists($matches[1], $this->filters)) {
                        $filter_values_have_changed = ($this->filters[$matches[1]]->setValue($value)) || $filter_values_have_changed;
                    } else {
                        $this->setPreference($matches[1], $value);
                    }
                }
            }

            // Refresh dropdown filter options
            $this->updateFilterOptions($link, $filter_values_have_changed, 20);

            // Check to see if we're saving a saved filter
            if (!empty($_REQUEST['filter_name'])) {
                $filterData = array();
                foreach ($this->filters as $key => $val) {
                    $filterData["$key"] = $val->getValue();
                }
                if (!isset($_REQUEST['filter_id'])) {
                    $_REQUEST['filter_id'] = 0;
                }
                SavedFilters::saveFilter($link, $_REQUEST['filter_id'], $_REQUEST['filter_name'], $this->getFilterURLBits(), $_SESSION['user']->username, $filterData);
            }

            $this->savedFilters = $filter = SavedFilters::getSavedFilters($link, $this->getFilterURLBits(), $_SESSION['user']->username);

            // Change the page number if the filters have changed or if the user
            // has specified a choice
            if ($filter_values_have_changed) {
                // When the filters change, the view must always begin at page 1
                $this->setPreference(View::KEY_PAGE_NUMBER, 1);
                $this->pageNumber = 1;
            } elseif (!is_null($this->getPreference(View::KEY_PAGE_NUMBER))) {
                // Use the user's choice of page number
                $this->pageNumber = $this->getPreference(View::KEY_PAGE_NUMBER);
            }

            $this->updateRowCount($link, $filter_values_have_changed, 20);
        } catch (Exception $e) {
            // Clear all views from the user's session
            foreach ($_SESSION as $key => $value) {
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
        foreach ($form_submission as $key => $value) {
            if (!array_key_exists($key, $this->filters)) {
                $this->preferences[$key] = $value;
            }
        }
    }


    public function setPreference($key, $value)
    {
        if (!array_key_exists($key, $this->filters)) {
            $this->preferences[$key] = $value;
        }
    }


    public function getPreference($key)
    {
        if (array_key_exists($key, $this->preferences)) {
            return $this->preferences[$key];
        } else {
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
        if (array_key_exists(View::KEY_PAGE_SIZE, $this->filters)) {
            $records_per_page = (integer)$this->filters[View::KEY_PAGE_SIZE]->getValue();
            if ($records_per_page > 0) {
                $skip = ($this->pageNumber - 1) * $records_per_page;
                $s->setClause('LIMIT ' . $skip . ',' . $this->filters[View::KEY_PAGE_SIZE]->getValue());
            } else {
                $s->removeClause('LIMIT');
            }
        }

        // Modify the basic statement with conditional clauses from view filters
        foreach ($this->filters as $key => $filter) {
            if (($key == View::KEY_PAGE_SIZE) || ($key == View::KEY_PAGE_NUMBER)) {
                continue; // These two filters have to be excluded -- they are a special case
            }

            $filter_statement = $filter->getSQLStatement();
            if (!is_null($filter_statement)) {
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
        if ($force_update || (time() - $cache_time_in_seconds) > $this->last_row_count_update) {
            $s = $this->getSQLStatement();
            $s->removeClause("order by");
            $s->setClause("LIMIT 1");
            if (!$s->hasClause("having") && !$s->hasClause("group by") && !preg_match("/^SELECT.+?DISTINCT/si", $s->getClause("select"))) {
                $s->setClause("SELECT SQL_CALC_FOUND_ROWS 1", true);
            } else {
                $s->setClause("SELECT SQL_CALC_FOUND_ROWS " . substr($s->getClause("select"), 7));
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
        foreach ($this->filters as $filter) {
            if ($null_options_found = ($filter instanceof DropDownViewFilter && is_null($filter->getOptions()))) {
                break;
            }
        }

        if ($force_update || $null_options_found || (time() - $cache_time_in_seconds > $this->last_filter_option_update)) {
            foreach ($this->filters as $filter) {
                if ($filter instanceof DropDownViewFilter or $filter instanceof CheckboxViewFilter) {
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
        if (!array_key_exists(View::KEY_PAGE_SIZE, $this->filters)) {
            $records_per_page = 0;
        } else {
            $records_per_page = (integer)$this->filters[View::KEY_PAGE_SIZE]->getValue();
        }


        if ($records_per_page > 0) {
            $numPages = ceil($this->rowCount / $records_per_page);
        } else {
            $numPages = 1;
        }

        // View objects keep their state, so the URL needs only to contain the action
        // and the page number.  The event (window.navigator_onclick()) can be used to
        // handle more complex page transitions that require the addition of further
        // querystring parameters e.g. when the a View object is used on the student enrollment form.
        if (preg_match('/[&]{0,1}_action=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0) {
            $qs = '_action=' . $matches[1] . '&'; // extract the action
        } else {
            $qs = '';
        }

        if (preg_match('/[&]{0,1}subview=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0) {
            $qs .= 'subview=' . $matches[1] . '&'; // extract the subview
        }

        // ick: id is now passed on with next prev option
        if (preg_match('/[&]{0,1}id=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0) {
            $qs .= 'id=' . $matches[1] . '&'; // extract the id
        }


        $pageNumberFieldName = get_class($this) . '_' . View::KEY_PAGE_NUMBER;
        $qsFirst = $qs . $pageNumberFieldName . '=' . '1';
        $qsPrevious = $qs . $pageNumberFieldName . '=' . ($this->pageNumber - 1);
        $qsNext = $qs . $pageNumberFieldName . '=' . ($this->pageNumber + 1);
        $qsLast = $qs . $pageNumberFieldName . '=' . ($numPages);
        $viewName = $this->getViewName();
        $pageNumber = $this->pageNumber;
        $pageNumberNext = $pageNumber + 1;
        $pageNumberPrev = $pageNumber - 1;

        // Page number dropdown
        $dropdown = "<select onchange=\"window.location.href='?{$qs}{$pageNumberFieldName}='+this.value;\">";
        $digits = strlen($numPages);
        for ($i = 1; $i <= $numPages; $i++) {
            if ($i != $this->pageNumber) {
                // done this way to add leading 0's where required
                $dropdown .= sprintf("<option value=\"%1\$d\">%1\${$digits}d</option>\n", $i);
            } else {
                $dropdown .= sprintf("<option value=\"%1\$d\" selected=\"selected\">%1\${$digits}d</option>\n", $i);
            }
        }
        $dropdown .= "</select>";

        if ($al == 'left')                                                // Khushnood
        {
            $html = '<div align="left" class="viewNavigator">';
        }    // Khushnood
        else                                                        // Khushnood
        {
            $html = '<div align="center" class="viewNavigator">';
        }
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

        if ($this->pageNumber <= 1) {
            $html .= <<<HEREDOC
<td width="20%" align="right"><button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/first-grey.gif" width="10" height="16" border="0"/></button>
<button style="width:30px" disabled="disabled"><img src="/images/view-navigation/previous-grey.gif" width="8" height="16" border="0"/></button></td>
HEREDOC;
        } else {
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
        if ($this->pageNumber < $numPages) {
            $html .= <<<HEREDOC
<td width="20%" align="left"><button onclick="this.disabled=true;window.location.href='?$qsNext';return false;" style="width:30px;margin-right:12px;" title="Next page"><img src="/images/view-navigation/next.gif" width="8" height="16" border="0"/></button>
<button onclick="this.disabled=true;window.location.href='?$qsLast';return false;" style="width:30px" title="Final page"><img src="/images/view-navigation/last.gif" width="10" height="16" border="0"/></button></td>
HEREDOC;
        } else {
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
    /**
     * removed to allow php 5.3 upgrade
     * public function render(PDO $link)
     * {
     *    $st = $link->query($this->getSQL());
     *    if($st)
     *    {
     *        echo $this->getViewNavigator();
     *        echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
     *        echo '<thead><tr>';
     *
     *        $fields = $result->fetch_fields();
     *        foreach($fields as $field)
     *        {
     *            echo '<th>'.htmlspecialchars((string)$field->name).'</th>';
     *        }
     *
     *        echo '</tr></thead><tbody>';
     *
     *        while($row = $st->fetch())
     *        {
     *            echo '<tr>';
     *            foreach($fields as $field)
     *            {
     *                echo '<td>'.htmlspecialchars((string)$row[$field->name]).'</td>';
     *            }
     *            echo '</tr>';
     *        }
     *
     *        echo '</tbody></table></div>';
     *        echo $this->getViewNavigator();
     *
     *    }
     *
     * }
     */


    /**
     * Default export to CSV format.  Should always be overridden in subclasses.
     */
    public function exportToCSV(PDO $link, $columns = '', $extra = '', $key = '', $where = '')
    {
        if ($key == 'view_ViewLearningAims') {
            $where = " ORDER BY tr_id ASC, l03, learning_aim_reference DESC ";
            DAO::execute($link, "DROP TABLE IF EXISTS dm");
            $sql = <<<HEREDOC
create temporary table dm
select
	DISTINCT
	tr.id as tr_id,
	tr.uln,
	tr.contract_id,
	tr.l03,
	employers.edrs,
	tr.firstnames,
	tr.surname,
	REPLACE(student_qualifications.id,'/','') as learning_aim_reference,
	tr.ni as ni_number,
	tr.dob as date_of_birth,
	assessors.firstnames as assessor_forename,
	assessors.surname as assessor_surname,
	employers.legal_name as employer,
	#employers.code,
	brands.title as brand,
	(SELECT description FROM lookup_employer_size WHERE lookup_employer_size.code = employers.code) AS employer_size,
	contracts.title as contract,
	frameworks.title as qualification_framework,
    courses.title as qualification_course,
	'' as qualification_status,
    student_qualifications.lsc_learning_aim as area_of_learning,
	student_qualifications.start_date AS start_date,
	tr.created AS creation_date,
	tr.closed_date AS closed_date,
	student_qualifications.awarding_body_date as registration_date,
	student_qualifications.awarding_body_reg as registration_number,
	student_qualifications.end_date AS planned_end_date,
	student_qualifications.actual_end_date AS actual_end_date,
	student_qualifications.achievement_date AS achievement_date,
	assessor_review.meeting_date AS last_review_date,
	student_qualifications.qualification_type,
	courses.programme_type,
	frameworks.id as framework_id,
	employers.manufacturer,
    IF(student_qualifications.end_date <= CURDATE(), '100', `student milestones subquery`.target) as progress_target,
	round(IF(unitsUnderAssessment>=100,100,unitsUnderAssessment)) as progress_percentage,
    '                                              ' AS home_region,
    '                                              ' AS employer_region,
	CASE
		WHEN aptitude = 1 THEN "<img src='/images/exempt.gif' border = '0'></img>"
		WHEN IF(unitsUnderAssessment>=100,100,unitsUnderAssessment) >= `student milestones subquery`.target THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		ELSE "<img src='/images/red-cross.gif' border='0'> </img>"
	END AS `progress_status`,
    '' as ilr_destination_code,
    '' as ilr_actual_end_date,
    '' as health_problems,
    '' as disability,
    '' as learning_difficulty,
    '' as ethnicity,
    contracts.contract_year,
	student_qualifications.aptitude,
	CASE
		WHEN aptitude = 1 THEN "<img src='/images/exempt.gif' border = '0'></img>"
		WHEN IF(unitsUnderAssessment>100,100,unitsUnderAssessment) >= `student milestones subquery`.target THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		ELSE "<img src='/images/red-cross.gif' border='0'> </img>"
	END AS `status`,
	tr.gender,
	tr.status_code,
	if(tr.target_date < CURDATE(),100,`student milestones subquery`.target) as target,
	'' as outcome,
    '' as project,
    lookup_contract_types.contract_type as funded,
    providers.legal_name as training_provider,
    '' as ukprn,
    '' as learner_postcode,
    '' as delivery_postcode,
    '' as additional_learning_need,
    '' as res_code,
	'' as project_a,
    tr.employer_id,
    tr.provider_id,
    employers.sector,
lookup_reason_past_planned.description as reason_past_planned
from student_qualifications
	LEFT JOIN courses_tr on courses_tr.tr_id = student_qualifications.tr_id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN frameworks on frameworks.id = courses.framework_id
	LEFT JOIN organisations on organisations.id = courses.organisations_id
	LEFT JOIN tr on tr.id = student_qualifications.tr_id
	LEFT JOIN lookup_reason_past_planned on lookup_reason_past_planned.id = tr.reason_unfunded
#	LEFT JOIN central.`lookup_postcode_la` AS hrla ON hrla.`postcode` = tr.home_postcode
#	LEFT JOIN central.`lookup_la_gor` AS hrgor ON hrgor.`local_authority` = hrla.`local_authority`
	LEFT JOIN users as assessors on tr.assessor = assessors.id
	LEFT JOIN organisations as employers on employers.id = tr.employer_id
	LEFT JOIN brands on brands.id = employers.manufacturer
	LEFT JOIN locations ON locations.`organisations_id` = employers.id AND locations.`is_legal_address` = 1
	LEFT JOIN central.`lookup_postcode_la` AS erla ON erla.`postcode` = locations.postcode
	LEFT JOIN central.`lookup_la_gor` AS ergor ON ergor.`local_authority` = erla.`local_authority`
	LEFT JOIN organisations as providers on providers.id = tr.provider_id
	LEFT JOIN contracts on contracts.id = tr.contract_id
    LEFT JOIN lookup_contract_types ON lookup_contract_types.id = contracts.`funded`
	LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND CONCAT(assessor_review.id,assessor_review.meeting_date) = (SELECT MAX(CONCAT(id,meeting_date)) FROM assessor_review WHERE tr_id = tr.id AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00')
	LEFT OUTER JOIN (
		SELECT
			tr.id AS 'tr_id',
			CASE timestampdiff(MONTH, tr.start_date, CURDATE())
				WHEN 0 THEN 0
				WHEN 1 THEN avg(student_milestones.month_1)
				WHEN 2 THEN avg(student_milestones.month_2)
				WHEN 3 THEN avg(student_milestones.month_3)
				WHEN 4 THEN avg(student_milestones.month_4)
				WHEN 5 THEN avg(student_milestones.month_5)
				WHEN 6 THEN avg(student_milestones.month_6)
				WHEN 7 THEN avg(student_milestones.month_7)
				WHEN 8 THEN avg(student_milestones.month_8)
				WHEN 9 THEN avg(student_milestones.month_9)
				WHEN 10 THEN avg(student_milestones.month_10)
				WHEN 11 THEN avg(student_milestones.month_11)
				WHEN 12 THEN avg(student_milestones.month_12)
				WHEN 13 THEN avg(student_milestones.month_13)
				WHEN 14 THEN avg(student_milestones.month_14)
				WHEN 15 THEN avg(student_milestones.month_15)
				WHEN 16 THEN avg(student_milestones.month_16)
    			WHEN 17 THEN avg(student_milestones.month_17)
				WHEN 18 THEN avg(student_milestones.month_18)
				WHEN 19 THEN avg(student_milestones.month_19)
				WHEN 20 THEN avg(student_milestones.month_20)
				WHEN 21 THEN avg(student_milestones.month_21)
				WHEN 22 THEN avg(student_milestones.month_22)
				WHEN 23 THEN avg(student_milestones.month_23)
				WHEN 24 THEN avg(student_milestones.month_24)
				WHEN 25 THEN avg(student_milestones.month_25)
				WHEN 26 THEN avg(student_milestones.month_26)
				WHEN 27 THEN avg(student_milestones.month_27)
				WHEN 28 THEN avg(student_milestones.month_28)
				WHEN 29 THEN avg(student_milestones.month_29)
				WHEN 30 THEN avg(student_milestones.month_30)
				WHEN 31 THEN avg(student_milestones.month_31)
				WHEN 32 THEN avg(student_milestones.month_32)
				WHEN 33 THEN avg(student_milestones.month_33)
				WHEN 34 THEN avg(student_milestones.month_34)
				WHEN 35 THEN avg(student_milestones.month_35)
				WHEN 36 THEN avg(student_milestones.month_36)
				ELSE 100
			END	AS `target`
		FROM
			tr
			LEFT JOIN student_milestones ON student_milestones.tr_id = tr.id
			LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id
			WHERE chosen=1 AND student_qualifications.aptitude != 1 AND student_milestones.qualification_id = student_qualifications.id
		GROUP BY
			tr.id ) AS `student milestones subquery` ON tr.id = `student milestones subquery`.tr_id

where student_qualifications.framework_id!='0' and aptitude != 1
UNION
SELECT zprogs.tr_id, tr.uln, tr.`contract_id`, tr.`l03`, organisations.edrs, tr.firstnames, tr.surname, 'ZPROG001' AS learning_aim_reference, tr.ni AS ni_number, tr.dob AS date_of_birth,
assessors.firstnames AS assessor_forename, assessors.surname AS assessor_surname, organisations.legal_name AS employer, brands.`title` AS brand,
(SELECT description FROM lookup_employer_size WHERE lookup_employer_size.code = organisations.code) AS employer_size,
contracts.title AS contract, frameworks.`title` AS qualification_framework, courses.`title` AS qualification_course, zprogs.comp_status AS qualification_status, '' AS area_of_learning,
zprogs.start_date AS start_date,
tr.created AS creation_date,
tr.closed_date AS closed_date,
'' AS registration_date,
'' AS registration_number,
zprogs.planned_end_date AS planned_end_date,
zprogs.actual_end_date AS actual_end_date,
zprogs.ach_date AS achievement_date,
assessor_review.meeting_date AS last_review_date,
'ProgramAim',
courses.programme_type,
frameworks.id as framework_id,
organisations.manufacturer,
IF(zprogs.planned_end_date <= CURDATE(), '100', `student milestones subquery`.target) AS progress_target,
tr.l36 AS progress_percentage,
'                                              ' AS home_region,
'                                              ' AS employer_region,
CASE
	WHEN tr.l36>=tr.target THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
	ELSE "<img src='/images/red-cross.gif' border='0'> </img>"
END AS `progress_status`,
'' AS ilr_destination_code,
'' AS ilr_actual_end_date,
'' AS health_problems,
'' AS disability,
'' AS learning_difficulty,
'' AS ethnicity,
contracts.contract_year,
'0' AS aptitude,
CASE
	WHEN tr.l36>=tr.target THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
	ELSE "<img src='/images/red-cross.gif' border='0'> </img>"
END AS `status`,
tr.gender,
tr.status_code,
IF(zprogs.planned_end_date < CURDATE(),100,`student milestones subquery`.target) AS target,
'' AS outcome,
'' AS project,
lookup_contract_types.contract_type AS funded,
providers.legal_name AS training_provider,
'' AS ukprn,
'' AS learner_postcode,
'' AS delivery_postcode,
'' AS additional_learning_need,
'' as res_code,
'' as project_a,
tr.employer_id,
tr.provider_id,
employers.sector,
lookup_reason_past_planned.description as reason_past_planned
FROM zprogs
LEFT JOIN tr ON tr.id = zprogs.tr_id
LEFT JOIN lookup_reason_past_planned on lookup_reason_past_planned.id = tr.reason_unfunded
LEFT JOIN organisations AS providers ON providers.id = tr.provider_id
LEFT JOIN organisations as employers on employers.id = tr.employer_id
LEFT JOIN users AS assessors ON assessors.id = tr.assessor
#LEFT JOIN central.`lookup_postcode_la` AS hrla ON hrla.`postcode` = tr.home_postcode
#LEFT JOIN central.`lookup_la_gor` AS hrgor ON hrgor.`local_authority` = hrla.`local_authority`
LEFT JOIN contracts ON contracts.id = tr.`contract_id`
LEFT JOIN lookup_contract_types ON lookup_contract_types.id = contracts.`funded`
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.`course_id`
LEFT JOIN frameworks ON frameworks.id = courses.`framework_id`
LEFT JOIN organisations ON organisations.id = tr.`employer_id`
LEFT JOIN brands ON brands.`id` = organisations.`manufacturer`
LEFT JOIN locations ON locations.`organisations_id` = organisations.id AND locations.`is_legal_address` = 1
LEFT JOIN central.`lookup_postcode_la` AS erla ON erla.`postcode` = locations.postcode
LEFT JOIN central.`lookup_la_gor` AS ergor ON ergor.`local_authority` = erla.`local_authority`
LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND CONCAT(assessor_review.id,assessor_review.meeting_date) = (SELECT MAX(CONCAT(id,meeting_date)) FROM assessor_review WHERE tr_id = tr.id AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00')
	LEFT OUTER JOIN (
		SELECT
			tr.id AS 'tr_id',
			CASE timestampdiff(MONTH, tr.start_date, CURDATE())
				WHEN 0 THEN 0
				WHEN 1 THEN AVG(student_milestones.month_1)
				WHEN 2 THEN AVG(student_milestones.month_2)
				WHEN 3 THEN AVG(student_milestones.month_3)
				WHEN 4 THEN AVG(student_milestones.month_4)
				WHEN 5 THEN AVG(student_milestones.month_5)
				WHEN 6 THEN AVG(student_milestones.month_6)
				WHEN 7 THEN AVG(student_milestones.month_7)
				WHEN 8 THEN AVG(student_milestones.month_8)
				WHEN 9 THEN AVG(student_milestones.month_9)
				WHEN 10 THEN AVG(student_milestones.month_10)
				WHEN 11 THEN AVG(student_milestones.month_11)
				WHEN 12 THEN AVG(student_milestones.month_12)
				WHEN 13 THEN AVG(student_milestones.month_13)
				WHEN 14 THEN AVG(student_milestones.month_14)
				WHEN 15 THEN AVG(student_milestones.month_15)
				WHEN 16 THEN AVG(student_milestones.month_16)
				WHEN 17 THEN AVG(student_milestones.month_17)
				WHEN 18 THEN AVG(student_milestones.month_18)
				WHEN 19 THEN AVG(student_milestones.month_19)
				WHEN 20 THEN AVG(student_milestones.month_20)
				WHEN 21 THEN AVG(student_milestones.month_21)
				WHEN 22 THEN AVG(student_milestones.month_22)
				WHEN 23 THEN AVG(student_milestones.month_23)
				WHEN 24 THEN AVG(student_milestones.month_24)
				WHEN 25 THEN AVG(student_milestones.month_25)
				WHEN 26 THEN AVG(student_milestones.month_26)
				WHEN 27 THEN AVG(student_milestones.month_27)
				WHEN 28 THEN AVG(student_milestones.month_28)
				WHEN 29 THEN AVG(student_milestones.month_29)
				WHEN 30 THEN AVG(student_milestones.month_30)
				WHEN 31 THEN AVG(student_milestones.month_31)
				WHEN 32 THEN AVG(student_milestones.month_32)
				WHEN 33 THEN AVG(student_milestones.month_33)
				WHEN 34 THEN AVG(student_milestones.month_34)
				WHEN 35 THEN AVG(student_milestones.month_35)
				WHEN 36 THEN AVG(student_milestones.month_36)
				ELSE 100
			END	AS `target`
		FROM
			tr
			LEFT JOIN student_milestones ON student_milestones.tr_id = tr.id
			LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id
			WHERE chosen=1 AND student_qualifications.aptitude != 1 AND student_milestones.qualification_id = student_qualifications.id
		GROUP BY
			tr.id ) AS `student milestones subquery` ON tr.id = `student milestones subquery`.tr_id
		$where
HEREDOC;
            DAO::execute($link, $sql);
            DAO::execute($link, "update dm
LEFT JOIN tr on tr.id = dm.tr_id
LEFT JOIN central.`lookup_postcode_la` AS hrla ON hrla.`postcode` = tr.home_postcode
LEFT JOIN central.`lookup_la_gor` AS hrgor ON hrgor.`local_authority` = hrla.`local_authority`
set home_region = IF(hrgor.government_region is null, '', hrgor.government_region)");
        }
        $statement = $this->getSQLStatement();
        $statement->removeClause('limit');//$statement->setClause()
        $st = $link->query($statement->__toString());
        if ($st) {
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename="' . $this->getViewName() . '.csv"');
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }

            if (is_array($columns)) {
                if ($extra != '') {
                    $columns[] = $extra;
                }
            } else {
                $columns .= "," . $extra;

                $columns = array_map('trim', explode(",", trim((string)$columns)));
            }


            if ($row = $st->fetch(PDO::FETCH_ASSOC)) {
                $line = '';
                foreach ($row as $field => $value) {
                    if (in_array($field, $columns)) {
                        if (strlen($line) > 0) {
                            $line .= ',';
                        }
                        $line .= '"' . str_replace('"', '""', $field) . '"';
                    }
                }
                if ($extra == 'student_qualifications' && DB_NAME == 'am_lead') {
                    $line .= ',completion_status,outcome';
                }
                echo $line . "\r\n";
                $planned_reviews = array();
                do {
                    $line = '';
                    if (get_class($this) == 'ViewInterviewsReport' && in_array('interview_comments', $columns)) {
                        if (isset($row['interview_comments'])) {
                            $row['interview_comments'] = $row['interview_comments_complete'];
                        }
                    }

                    if ($extra == 'programme_type') {
                        $tr_id = $row['tr_id'];
                        $LearnAimRef = str_replace("/", "", $row['learning_aim_reference']);
                        if ($row['contract_year'] < 2012) {
                            $x = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A34|/ilr/main[A09='$LearnAimRef']/A34|/ilr/subaim[A09='$LearnAimRef']/A34" . '"';
                            $y = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A35|/ilr/main[A09='$LearnAimRef']/A35|/ilr/subaim[A09='$LearnAimRef']/A35" . '"';
                            $z = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A31|/ilr/main[A09='$LearnAimRef']/A31|/ilr/subaim[A09='$LearnAimRef']/A31" . '"';
                            $destination = '"' . "/ilr/learner/L39" . '"';
                            $health_problems = '"' . "/ilr/learner/L14" . '"';
                            $disability = '"' . "/ilr/learner/L15" . '"';
                            $learning_difficulty = '"' . "/ilr/learner/L16" . '"';
                            $ethnicity = '"' . "/ilr/learner/L12" . '"';
                            $provspec = '"' . "/ilr/learner/L42b" . '"';
                            $partnerUKPRN = '"' . "/ilr/programmeaim[A09='$LearnAimRef']/A22|/ilr/main[A09='$LearnAimRef']/A22|/ilr/subaim[A09='$LearnAimRef']/A22" . '"';
                            $LearnerPostcode = '"' . "/ilr/learner/L17" . '"';
                            $DelLocPostCode = '"' . "/ilr/programmeaim[A09='$LearnAimRef']/A23|/ilr/main[A09='$LearnAimRef']/A23|/ilr/subaim[A09='$LearnAimRef']/A23" . '"';
                            $aln = '"' . "/ilr/programmeaim[A09='$LearnAimRef']/A53|/ilr/main[A09='$LearnAimRef']/A53|/ilr/subaim[A09='$LearnAimRef']/A53" . '"';
                            $res_code = '"' . "/" . '"';
                            $provspec_a = '"' . "/ilr/learner/L42a" . '"';
                        } else {
                            $x = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/CompStatus" . '"';
                            $y = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/Outcome" . '"';
                            $z = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearnActEndDate" . '"';
                            $destination = '"' . "/Learner/Dest" . '"';
                            $health_problems = '"' . "/Learner/LLDDHealthProb" . '"';
                            $disability = '"' . "/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode" . '"';
                            $learning_difficulty = '"' . "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode" . '"';
                            $ethnicity = '"' . "/Learner/Ethnicity" . '"';
                            $provspec = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon" . '"';
                            $partnerUKPRN = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/PartnerUKPRN" . '"';
                            $LearnerPostcode = '"' . "/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode" . '"';
                            $DelLocPostCode = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/DelLocPostCode" . '"';
                            $aln = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ALN\']" . '"';
                            if ($row['contract_year'] > 2012) {
                                $aln = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType='LSF']" . '"';
                            }
                            $res_code = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode" . '"';
                            $provspec_a = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon" . '"';
                        }
                        $res = DAO::getResultset($link,
                            "select extractvalue(ilr,$x),extractvalue(ilr,$y),extractvalue(ilr,$z),extractvalue(ilr,$destination),extractvalue(ilr,$health_problems),extractvalue(ilr,$disability),extractvalue(ilr,$learning_difficulty),extractvalue(ilr,$ethnicity),extractvalue(ilr,$provspec),extractvalue(ilr,$partnerUKPRN),extractvalue(ilr,$LearnerPostcode),extractvalue(ilr,$DelLocPostCode),extractvalue(ilr,$aln),extractvalue(ilr,$res_code),extractvalue(ilr,$provspec_a) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
                        $row['qualification_status'] = @$res[0][0];
                        $row['comp_status'] = @$res[0][0];
                        $row['outcome'] = @$res[0][1];
                        $row['ilr_actual_end_date'] = @$res[0][2];
                        if ($row['learning_aim_reference'] == 'ZPROG001') {
                            $row['actual_end_date'] = @$res[0][2];
                        }
                        $row['ilr_destination_code'] = @$res[0][3];
                        $row['health_problems'] = @$res[0][4];
                        $row['disability'] = @$res[0][5];
                        $row['learning_difficulty'] = @$res[0][6];
                        $row['ethnicity'] = @$res[0][7];
                        if (!strpos(@$res[0][8], '-')) {
                            $row['project'] = substr(@$res[0][8], 0, 5);
                        } else {
                            $row['project'] = substr(@$res[0][8], 0, 8);
                        }
                        $row['ukprn'] = (@$res[0][9] == 'undefined') ? '' : @$res[0][9];
                        $row['learner_postcode'] = @$res[0][10];
                        $DelLocPostCode1 = explode(" ", @$res[0][11]);
                        $DelLocPostCode = @$DelLocPostCode1[0] . " " . @$DelLocPostCode1[1];
                        $row['delivery_postcode'] = $DelLocPostCode;
                        $aln = explode(" ", @$res[0][12]);
                        $row['additional_learning_need'] = @$aln[0];
                        $row['res_code'] = @$res[0][13];
                        $row['project_a'] = @$res[0][14];
                    }
                    if ($extra == 'student_qualifications' && DB_NAME != 'am_lead') {
                        if (DB_NAME != "am_lead") {
                            $qual_id = $row['qual_id'];
                            if (!isset($row['cmonth'])) {
                                $row['cmonth'] = 100;
                            }

                            $current_month_since_study_start_date = $row['cmonth'];

                            $month = "month_" . ($current_month_since_study_start_date);

                            $internaltitle = $row['title'];

                            if (!isset($row['passed'])) {
                                $row['passed'] = 0;
                            }

                            if ($row['passed'] == '1') {
                                $target = 100;
                            } else {
                                if ($current_month_since_study_start_date >= 1 && $current_month_since_study_start_date <= 36) {// Calculating target month and target
                                    $internaltitle = addslashes((string)$internaltitle);
                                    $que = "select avg($month) from student_milestones LEFT JOIN student_qualifications ON student_qualifications.id = student_milestones.qualification_id AND student_qualifications.tr_id = student_milestones.tr_id where student_qualifications.aptitude!=1 and chosen=1 and qualification_id='$qual_id' and student_milestones.internaltitle='$internaltitle' and student_milestones.tr_id={$row['tr_id']}";
                                    $target = trim((string)DAO::getSingleValue($link, $que));
                                } else {
                                    $target = '0';
                                }
                            }
                            $tdate = new Date($row['planned_end_date']);
                            $cdate = new Date(date('d-m-Y'));
                            if ($cdate->getDate() >= $tdate->getDate()) {
                                $target = 100;
                            }

                            $sdate = new Date($row['start_date']);
                            if ($cdate->getDate() < $sdate->getDate()) {
                                $target = 0;
                            }
                            $row['target'] = $target;
                        }
                        $tr_id = $row['tr_id'];
                        $LearnAimRef = str_replace("/", "", $row['learning_aim_reference']);
                        if ($row['contract_year'] < 2012) {
                            $x = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A34|/ilr/main[A09='$LearnAimRef']/A34|/ilr/subaim[A09='$LearnAimRef']/A34" . '"';
                            $y = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A35|/ilr/main[A09='$LearnAimRef']/A35|/ilr/subaim[A09='$LearnAimRef']/A35" . '"';
                            $z = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A31|/ilr/main[A09='$LearnAimRef']/A31|/ilr/subaim[A09='$LearnAimRef']/A31" . '"';
                            $destination = '"' . "/ilr/learner/L39" . '"';
                            $health_problems = '"' . "/ilr/learner/L14" . '"';
                            $disability = '"' . "/ilr/learner/L15" . '"';
                            $learning_difficulty = '"' . "/ilr/learner/L16" . '"';
                            $ethnicity = '"' . "/ilr/learner/L12" . '"';
                            $provspec = '"' . "/ilr/learner/L42b" . '"';
                            $partnerUKPRN = '"' . "/ilr/programmeaim[A09='$LearnAimRef']/A22|/ilr/main[A09='$LearnAimRef']/A22|/ilr/subaim[A09='$LearnAimRef']/A22" . '"';
                            $LearnerPostcode = '"' . "/ilr/learner/L17" . '"';
                            $DelLocPostCode = '"' . "/ilr/programmeaim[A09='$LearnAimRef']/A23|/ilr/main[A09='$LearnAimRef']/A23|/ilr/subaim[A09='$LearnAimRef']/A23" . '"';
                            $DelLocPostCode = explode(" ", $DelLocPostCode);
                            $DelLocPostCode = @$DelLocPostCode[0] . " " . @$DelLocPostCode[1];
                            $aln = '"' . "/ilr/programmeaim[A09='$LearnAimRef']/A53|/ilr/main[A09='$LearnAimRef']/A53|/ilr/subaim[A09='$LearnAimRef']/A53" . '"';
                            $llddhealthprob = '"' . "ilr/learner/L14" . '"';
                            $disability = '"' . "ilr/learner/L15" . '"';
                            $learning_difficulty = '"' . "ilr/learner/L16" . '"';
                        } else {
                            $x = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/CompStatus" . '"';
                            $y = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/Outcome" . '"';
                            $z = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearnActEndDate" . '"';
                            $destination = '"' . "/Learner/Dest" . '"';
                            $health_problems = '"' . "/Learner/LLDDHealthProb" . '"';
                            $disability = '"' . "/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode" . '"';
                            $learning_difficulty = '"' . "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode" . '"';
                            $ethnicity = '"' . "/Learner/Ethnicity" . '"';
                            $provspec = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon" . '"';
                            $partnerUKPRN = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/PartnerUKPRN" . '"';
                            $LearnerPostcode = '"' . "/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode" . '"';
                            $DelLocPostCode = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/DelLocPostCode" . '"';
                            $aln = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ALN\']" . '"';
                            $prior_attain = '"' . "/Learner/PriorAttain" . '"';
                            $ffi = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode" . '"';
                            $sof = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode" . '"';
                            $eef = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode" . '"';
                            $wpl = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='WPL']/LearnDelFAMCode" . '"';
                            $res = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode" . '"';
                            $asl = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='ASL']/LearnDelFAMCode" . '"';
                            $spp = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='SPP']/LearnDelFAMCode" . '"';
                            $llddhealthprob = '"' . "/Learner/LLDDHealthProb" . '"';
                            $disability = '"' . "/Learner/LLDDandHealthProblem[LLDDType=\'DS\']/LLDDCode" . '"';
                            $learning_difficulty = '"' . "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode" . '"';
                            $PlanLearnHours = '"' . "/Learner/PlanLearnHours" . '"';
                            $PlanEEPHours = '"' . "/Learner/PlanEEPHours" . '"';
                            $PwayCode = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/PwayCode" . '"';
                            $PartnerUKPRN = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/PartnerUKPRN" . '"';
                            $ProviderSpecifiedLearnMonitoring_A = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon" . '"';
                            $ProviderSpecifiedLearnMonitoring_B = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon" . '"';
                            $WithdrawReason = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/WithdrawReason" . '"';
                            $ProviderSpecifiedDelMonitoring_A = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon" . '"';
                            $ProviderSpecifiedDelMonitoring_B = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon" . '"';
                            $ProviderSpecifiedDelMonitoring_C = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon" . '"';
                            $ProviderSpecifiedDelMonitoring_D = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon" . '"';
                            $EmpOutcome = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/EmpOutcome" . '"';

                        }
                        if ($row['contract_year'] < 2012) {
                            $res = DAO::getResultset($link,
                                "select extractvalue(ilr,$x),extractvalue(ilr,$y),extractvalue(ilr,$z),extractvalue(ilr,$destination),extractvalue(ilr,$health_problems),extractvalue(ilr,$disability),extractvalue(ilr,$learning_difficulty),extractvalue(ilr,$ethnicity),extractvalue(ilr,$provspec),extractvalue(ilr,$partnerUKPRN),extractvalue(ilr,$LearnerPostcode),extractvalue(ilr,$DelLocPostCode),extractvalue(ilr,$aln),extractvalue(ilr,$llddhealthprob),extractvalue(ilr,$disability),extractvalue(ilr,$learning_difficulty) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
                        } else {
                            $res = DAO::getResultset($link,
                                "select extractvalue(ilr,$x),extractvalue(ilr,$y),extractvalue(ilr,$z),extractvalue(ilr,$destination),extractvalue(ilr,$health_problems),extractvalue(ilr,$disability),extractvalue(ilr,$learning_difficulty),extractvalue(ilr,$ethnicity),extractvalue(ilr,$provspec),extractvalue(ilr,$partnerUKPRN),extractvalue(ilr,$LearnerPostcode),extractvalue(ilr,$DelLocPostCode),extractvalue(ilr,$aln),extractvalue(ilr,$prior_attain),extractvalue(ilr,$ffi),extractvalue(ilr,$sof),extractvalue(ilr,$eef),extractvalue(ilr,$wpl),extractvalue(ilr,$res),extractvalue(ilr,$asl),extractvalue(ilr,$spp),extractvalue(ilr,$llddhealthprob),extractvalue(ilr,$PlanLearnHours),extractvalue(ilr,$PlanEEPHours), extractvalue(ilr,$PwayCode), extractvalue(ilr,$PartnerUKPRN), extractvalue(ilr,$ProviderSpecifiedLearnMonitoring_A), extractvalue(ilr,$ProviderSpecifiedLearnMonitoring_B), extractvalue(ilr,$WithdrawReason),extractvalue(ilr,$disability),extractvalue(ilr,$learning_difficulty), extractvalue(ilr, $ProviderSpecifiedDelMonitoring_A), extractvalue(ilr, $ProviderSpecifiedDelMonitoring_B), extractvalue(ilr, $ProviderSpecifiedDelMonitoring_C), extractvalue(ilr, $ProviderSpecifiedDelMonitoring_D), extractvalue(ilr, $EmpOutcome) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
                        }
                        $row['qualification_status'] = @$res[0][0];
                        $row['comp_status'] = @$res[0][0];
                        $row['outcome'] = @$res[0][1];
                        $row['ilr_actual_end_date'] = @$res[0][2];
                        if ($row['learning_aim_reference'] == 'ZPROG001') {
                            $row['actual_end_date'] = @$res[0][2];
                        }
                        $row['ilr_destination_code'] = @$res[0][3];
                        $row['health_problems'] = @$res[0][4];
                        $row['disability'] = @$res[0][5];
                        $row['learning_difficulty'] = @$res[0][6];
                        $row['ethnicity'] = @$res[0][7];
                        $row['project'] = substr(@$res[0][8], 0, 5);
                        $row['ukprn'] = (@$res[0][9] == 'undefined') ? '' : @$res[0][9];
                        $row['learner_postcode'] = @$res[0][10];
                        $row['delivery_postcode'] = @$res[0][11];
                        $row['additional_learning_need'] = (@$res[0][12] == '11') ? '1' : ((@$res[0][12] == '13') ? '1' : '');
                        if ($row['contract_year'] >= 2013) {
                            $row['prior_attain'] = @$res[0][13];
                            $row['ffi'] = @$res[0][14];
                            $row['sof'] = @$res[0][15];
                            $row['eef'] = @$res[0][16];
                            $row['wpl'] = @$res[0][17];
                            $row['res'] = @$res[0][18];
                            $row['asl'] = @$res[0][19];
                            $row['spp'] = @$res[0][20];
                        }
                        if ($row['contract_year'] < 2012) {
                            $row['llddhealthprob'] = @$res[0][13];
                            $row['disability'] = @$res[0][14];
                            $row['learning_difficulty'] = @$res[0][15];
                        } else {
                            $row['llddhealthprob'] = @$res[0][21];
                            $row['planlearnhours'] = (isset($res[0][22]) and ($res[0][22] != 'undefined')) ? $res[0][22] : '&nbsp';
                            $row['planeephours'] = (isset($res[0][23]) and ($res[0][23] != 'undefined')) ? $res[0][23] : '&nbsp';
                            $row['pwaycode'] = (isset($res[0][24]) and ($res[0][24] != 'undefined')) ? $res[0][24] : '&nbsp';
                            $row['partnerukprn'] = (isset($res[0][25]) and ($res[0][25] != 'undefined')) ? $res[0][25] : '&nbsp';
                            $row['prov_spec_learn_mon_a'] = (isset($res[0][26]) and ($res[0][26] != 'undefined')) ? $res[0][26] : '&nbsp';
                            $row['prov_spec_learn_mon_b'] = (isset($res[0][27]) and ($res[0][27] != 'undefined')) ? $res[0][27] : '&nbsp';
                            $row['withdraw_reason'] = (isset($res[0][28]) and ($res[0][28] != 'undefined')) ? $res[0][28] : '';
                            $row['disability'] = (isset($res[0][29]) and ($res[0][29] != 'undefined')) ? $res[0][29] : '';
                            $row['learning_difficulty'] = (isset($res[0][30]) and ($res[0][30] != 'undefined')) ? $res[0][30] : '';
                            $row['prov_spec_del_mon_a'] = (isset($res[0][31]) and ($res[0][30] != 'undefined')) ? $res[0][31] : '';
                            $row['prov_spec_del_mon_b'] = (isset($res[0][32]) and ($res[0][30] != 'undefined')) ? $res[0][32] : '';
                            $row['prov_spec_del_mon_c'] = (isset($res[0][33]) and ($res[0][30] != 'undefined')) ? $res[0][33] : '';
                            $row['prov_spec_del_mon_d'] = (isset($res[0][34]) and ($res[0][30] != 'undefined')) ? $res[0][34] : '';
                            $row['emp_outcome'] = (isset($res[0][35]) and ($res[0][35] != 'undefined')) ? $res[0][35] : '&nbsp';
                        }
                        $tr_id = $row['tr_id'];
                        $last_review = DAO::getResultset($link, "SELECT meeting_date,comments FROM assessor_review WHERE tr_id = '$tr_id' AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00' ORDER BY meeting_date DESC LIMIT 0,1");
                        $row['last_review'] = @$last_review[0][0];
                        if (@$last_review[0][1] == 'green') {
                            $row['review_status'] = 'green';
                        } elseif (@$last_review[0][1] == 'yellow') {
                            $row['review_status'] = 'yellow';
                        } elseif (@$last_review[0][1] == 'red') {
                            $row['review_status'] = 'red';
                        } else {
                            $row['review_status'] = 'No Review';
                        }

                        $row['ssa1'] = DAO::getSingleValue($link, "SELECT CONCAT(lad201314.ssa_tier1_codes.SSA_TIER1_CODE,' ',lad201314.ssa_tier1_codes.SSA_TIER1_DESC) AS AOL FROM lad201314.all_annual_values INNER JOIN lad201314.ssa_tier1_codes ON ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE WHERE LEARNING_AIM_REF = '" . $row['a09'] . "'");
                        $row['ssa2'] = DAO::getSingleValue($link, "SELECT CONCAT(lad201314.ssa_tier2_codes.SSA_TIER2_CODE,' ',lad201314.ssa_tier2_codes.SSA_TIER2_DESC) AS AOL FROM lad201314.all_annual_values INNER JOIN lad201314.ssa_tier2_codes ON ssa_tier2_codes.SSA_TIER2_CODE = all_annual_values.SSA_TIER2_CODE WHERE LEARNING_AIM_REF = '" . $row['a09'] . "'");

                    }
                    if ($extra == 'student_qualifications' && DB_NAME == 'am_lead') {
                        $tr_id = $row['tr_id'];
                        $LearnAimRef = str_replace("/", "", $row['learning_aim_reference']);
                        $contract_id = $row['contract_id'];
                        $get_marked_date = DAO::getSingleValue($link, "SELECT DATE_FORMAT(MAX(t1.date), '%d/%m/%Y') FROM ilr_audit t1 INNER JOIN ilr_audit_trail_entry t2 ON t1.id = t2.`ilr_audit_id` WHERE t1.tr_id = $tr_id AND t1.contrat_id = $contract_id AND LOCATE('$LearnAimRef :: Learning Actual End Date', t2.`field_changed`) > 0 ;");
                        $row['marked_date'] = $get_marked_date;
                        $tr_id = $row['tr_id'];
                        $LearnAimRef = str_replace("/", "", $row['learning_aim_reference']);
                        $cs = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/CompStatus" . '"';
                        $oc = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/Outcome" . '"';
                        $_res = DAO::getResultset($link, "select extractvalue(ilr,$cs),extractvalue(ilr,$oc) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
                        $row['comp_status'] = @$_res[0][0];
                        $row['outcome'] = @$_res[0][1];
                    }
                    if ($extra == 'ViewAttendanceV2AdHocRegistersReport') {
                        $tr_id = $row['tr_id'];
                        $sql = <<<SQL
SELECT *
FROM
	lesson_extra_learners INNER JOIN lessons INNER JOIN tr
	ON lesson_extra_learners.`lesson_id` = lessons.id
	AND tr.id = lesson_extra_learners.tr_id
	LEFT JOIN register_entries ON lessons.id = register_entries.`lessons_id` AND tr.id = register_entries.`pot_id`
	WHERE tr.id = $tr_id AND register_entries.entry = '1'


SQL;

                        $lessons = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

                        $attended_hours = 0;
                        foreach ($lessons as $l) {

                            $from = $l['start_time'];
                            $to = $l['end_time'];

                            $_total = strtotime($to) - strtotime($from);
                            $_hours = floor($_total / 60 / 60);
                            $_minutes = round(($_total - ($_hours * 60 * 60)) / 60);

                            $attended_hours += floatval($_hours . '.' . $_minutes);
                        }
                        $row['actual_hours'] = $attended_hours;
                    }
                    if ($extra == 'ViewAttendanceV2Report') {
                        $tr_id = $row['tr_id'];
                        $sql = <<<SQL
SELECT *
FROM
	group_members INNER JOIN lessons INNER JOIN tr
	ON group_members.groups_id = lessons.groups_id
	AND tr.id = group_members.tr_id


	LEFT JOIN register_entries ON lessons.id = register_entries.`lessons_id` AND tr.id = register_entries.`pot_id`
	WHERE tr.id = $tr_id AND register_entries.entry = '1'


SQL;

                        $lessons = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

                        $attended_hours = 0;
                        foreach ($lessons as $l) {

                            $from = $l['start_time'];
                            $to = $l['end_time'];

                            $_total = strtotime($to) - strtotime($from);
                            $_hours = floor($_total / 60 / 60);
                            $_minutes = round(($_total - ($_hours * 60 * 60)) / 60);

                            $attended_hours += floatval($_hours . '.' . $_minutes);
                        }
                        $row['actual_hours'] = $attended_hours;
                    }
                    if ($extra == 'ViewDataReport') {
                        $tr_id = $row['TRID'];
                        $tnp1 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount" . '"';
                        $tnp2 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount" . '"';
                        $tnp3 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=3]/TBFinAmount" . '"';
                        $tnp4 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=4]/TBFinAmount" . '"';
                        $aln = '"' . "/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='LSF']/LearnDelFAMCode[last()]" . '"';
                        $outgrade = '"' . "/Learner/LearningDelivery[AimType=1]/OutGrade" . '"';
                        $res = DAO::getResultset($link, "SELECT extractvalue(ilr, $tnp1),extractvalue(ilr,$tnp2),extractvalue(ilr,$tnp3),extractvalue(ilr,$tnp4),extractvalue(ilr,$aln),extractvalue(ilr,$outgrade) FROM ilr WHERE ilr.tr_id = $tr_id  ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                        $row['TNP1'] = @$res[0][0];
                        $row['TNP2'] = @$res[0][1];
                        $row['TNP3'] = @$res[0][2];
                        $row['TNP4'] = @$res[0][3];
                        $row['ALN'] = (@$res[0][4] == 1) ? 'Yes' : 'No';
                        $row['EPA_result'] = @$res[0][5];
                    }
                    if ($extra == 'ViewTrainingRecords') {
                        if (isset($row['tags'])) {
                            $tagsSql = "SELECT GROUP_CONCAT(tags.name SEPARATOR '; ') FROM tags INNER JOIN taggables ON tags.id = taggables.tag_id WHERE taggables.taggable_id = '{$row['tr_id']}' AND tags.type = 'Training Record';";
                            $row['tags'] = DAO::getSingleValue($link, $tagsSql);
                        }
                        if (file_exists(Repository::getRoot() . '/' . $row['username'])) {
                            $upload_dir = new RepositoryFile(Repository::getRoot() . '/' . $row['username']);
                            $row['repository_size'] = Repository::formatFileSize($upload_dir->getSize());
                            $row['repository_size'] = str_replace('&nbsp;', '', $row['repository_size']);
                        }
                        $tr_id = $row['tr_id'];

                        if (isset($row['otj_hours_due'])) {
                            $otj_minutes_due = $row['otj_hours_due'] == '' ? 0 : $row['otj_hours_due'] * 60;
                            $row['otj_hours_due'] = ViewTrainingRecords::convertToHoursMins($otj_minutes_due, '%02d hours %02d minutes');
                        }

                        if (DB_NAME == "am_city_skills") {
                            $gateway_date = DAO::getSingleValue($link, "SELECT gateway_date FROM tr where id = '$tr_id'");
                            $row['gateway_date'] = $gateway_date;
                        }

                        if (isset($row['otj_hours_actual'])) {
                            $otj_minutes_actual = $row['otj_hours_actual'] == '' ? 0 : $row['otj_hours_actual'];
                            if (in_array(DB_NAME, ["am_city_skills"])) {
                                $otj_minutes_actual = $row['otj_hours_actual'] + ViewTrainingRecords::calculateAttendanceMinutes($link, $tr_id);
                            }
                            $row['otj_hours_actual'] = ViewTrainingRecords::convertToHoursMins($otj_minutes_actual, '%02d hours %02d minutes');
                        }

                        if (DB_NAME == 'am_baltic' or DB_NAME == 'am_baltic_demo') {
                            $last_review = DAO::getResultset($link, "SELECT assessor_review.`meeting_date`
,(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id) AS emailed
,(SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id) AS signature
,DATEDIFF((SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id),(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id)) AS days
,paperwork_received
FROM assessor_review
WHERE tr_id = '$tr_id' ORDER BY meeting_date DESC LIMIT 0,1;");

                            if (@$last_review[0][1] == '') {
                                $row['review_status'] = 'Not emailed';
                            } elseif (@$last_review[0][2] == '') {
                                $row['review_status'] = 'Red';
                            } elseif (@$last_review[0][3] <= 7) {
                                $row['review_status'] = 'Green';
                            } elseif (@$last_review[0][3] <= 28) {
                                $row['review_status'] = 'Amber';
                            } elseif (@$last_review[0][3] > 28) {
                                $row['review_status'] = 'Red';
                            } else {
                                $row['review_status'] = 'Red';
                            }
                            switch (@$last_review[0][5]) {
                                case 0:
                                    $row['paperwork_received'] = 'Not Received';
                                    break;
                                case 1:
                                    $row['paperwork_received'] = 'Received';
                                    break;
                                case 2:
                                    $row['paperwork_received'] = 'Rejected';
                                    break;
                                case 3:
                                    $row['paperwork_received'] = 'Accepted';
                                    break;
                                default:
                                    $row['paperwork_received'] == '';
                                    break;
                            }

                            $row['learner_type'] = DAO::getSingleValue($link, "select CASE inductee_type
                              WHEN 'NA' THEN 'New Apprentice'
                              WHEN 'WFD' THEN 'WFD'
                              WHEN 'P' THEN 'Progression'
                              WHEN 'ANEW' THEN 'ACCM - New'
                              WHEN 'AWFD' THEN 'ACCM - WFD'
                              WHEN 'KNEW' THEN 'KEY ACCT - New'
                              WHEN 'KWFD' THEN 'KEY ACCT - WFD'
                              WHEN 'NSSU' THEN 'NB - STRAIGHT SIGN UP'
                              WHEN 'ASSU' THEN 'ACCM - STRAIGHT SIGN UP'
                              WHEN 'KSSU' THEN 'KEY ACCT - STRAIGHT SIGN UP'
                              END AS learner_type from inductees where sunesis_username = '{$row['username']}'");
                        } else {
                            $last_review = DAO::getResultset($link, "SELECT meeting_date,comments,paperwork_received FROM assessor_review WHERE tr_id = '$tr_id' AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00' ORDER BY meeting_date DESC LIMIT 0,1");
                            $row['last_review'] = @$last_review[0][0];
                            if (@$last_review[0][1] == 'green') {
                                $row['review_status'] = 'green';
                            } elseif (@$last_review[0][1] == 'yellow') {
                                $row['review_status'] = 'yellow';
                            } elseif (@$last_review[0][1] == 'red') {
                                $row['review_status'] = 'red';
                            } else {
                                $row['review_status'] = 'No Review';
                            }
                            switch (@$last_review[0][2]) {
                                case 0:
                                    $row['paperwork_received'] = 'Not Received';
                                    break;
                                case 1:
                                    $row['paperwork_received'] = 'Received';
                                    break;
                                case 2:
                                    $row['paperwork_received'] = 'Rejected';
                                    break;
                                case 3:
                                    $row['paperwork_received'] = 'Accepted';
                                    break;
                                default:
                                    $row['paperwork_received'] = '';
                                    break;
                            }
                        }


                        $subsequent = $row['frequency'];
                        $weeks = $row['subsequent'];
                        $sql = "SELECT GROUP_CONCAT(meeting_date) AS all_dates from assessor_review WHERE tr_id = " . $row['id'] . " AND meeting_date != '0000-00-00' ";
                        $meetingDatesResult = $link->query($sql);
                        if ($meetingDatesResult) {
                            $dates = $meetingDatesResult->fetchColumn(0);//echo $dates[0];
                        }//exit(0);
                        if ($dates != '') {
                            $dates = explode(",", $dates);
                            $next_review = new Date($row['start_date']);
                            $next_review->addDays($weeks * 7);
                            $color = "red";
                            foreach ($dates as $date) {
                                if ($date != '0000-00-00') {
                                    if ($next_review->before($date) && DB_NAME != 'am_gigroup' && DB_NAME != 'am_aet') {
                                        $next_review->addDays($subsequent * 7);
                                    } else {
                                        $next_review = new Date($date);
                                        $next_review->addDays($subsequent * 7);
                                    }
                                }
                            }
                        } else {
                            $next_review = new Date($row['start_date']);
                            $next_review->addDays($weeks * 7);
                        }
                        $row['next_review'] = Date::toMySQL($next_review);

                        if (DB_NAME == 'am_lcurve' || DB_NAME == 'am_edudo' || DB_NAME == 'am_demo' || DB_NAME == 'am_superdrug' || DB_NAME == 'ams' || DB_NAME == "am_lead" || DB_NAME == "am_baltic" || DB_NAME == "am_gigroup") {
                            $tr_id = $row['tr_id'];
                            $llddhealthprob = '"' . "/Learner/LLDDHealthProb|ilr/learner/L14" . '"';
                            $provspec_a = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon" . '"';
                            $provspec_b = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon" . '"';
                            $disability = '"' . "/Learner/LLDDandHealthProblem[LLDDType=\'DS\']/LLDDCode|/ilr/learner/L15" . '"';
                            $learning_difficulty = '"' . "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode|/ilr/learner/L16" . '"';
                            $program_type = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProgType" . '"';
                            $pathway_code = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/PwayCode" . '"';
                            $prior_attain = '"' . "/Learner/PriorAttain" . '"';
                            $ilr_destination = '"' . "/Learner/Dest" . '"';
                            $WithdrawReason = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/WithdrawReason" . '"';
                            $ilr_restart_field = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode" . '"';
                            $primary_lldd = '"' . "/Learner/LLDDandHealthProblem[PrimaryLLDD=\'1\']/LLDDCat" . '"';
                            $achievement_date = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/AchDate" . '"';
                            $provspecdelmona = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'A\']/ProvSpecDelMon" . '"';
                            $provspecdelmonb = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'B\']/ProvSpecDelMon" . '"';
                            $provspecdelmonc = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'C\']/ProvSpecDelMon" . '"';
                            $provspecdelmond = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'D\']/ProvSpecDelMon" . '"';
                            $res = DAO::getResultset($link,
                                "SELECT extractvalue(ilr, $llddhealthprob),extractvalue(ilr,$provspec_a),extractvalue(ilr,$provspec_b),extractvalue(ilr,$disability),extractvalue(ilr,$learning_difficulty),extractvalue(ilr,$program_type),extractvalue(ilr,$pathway_code),extractvalue(ilr,$prior_attain),extractvalue(ilr,$ilr_destination),extractvalue(ilr,$WithdrawReason),extractvalue(ilr,$ilr_restart_field),extractvalue(ilr,$primary_lldd),extractvalue(ilr,$achievement_date),extractvalue(ilr,$provspecdelmona),extractvalue(ilr,$provspecdelmonb),extractvalue(ilr,$provspecdelmonc),extractvalue(ilr,$provspecdelmond) FROM ilr WHERE ilr.tr_id = $tr_id  ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                            $row['llddhealthprob'] = @$res[0][0];
                            $row['provspeclearnmona'] = @$res[0][1];
                            $row['provspeclearnmonb'] = @$res[0][2];
                            $row['disability'] = @$res[0][3];
                            $row['learning_difficulty'] = @$res[0][4];
                            $row['program_type'] = @$res[0][5];
                            $row['pathway_code'] = @$res[0][6];
                            $row['prior_attain'] = @$res[0][7];
                            $row['ilr_destination'] = @$res[0][8];
                            $row['withdraw_reason'] = @$res[0][9];
                            $row['ilr_restart_field'] = @$res[0][10];
                            if (isset($row['primary_lldd'])) {
                                $row['primary_lldd'] = @$res[0][11];
                            }
                            $row['achievement_date'] = @$res[0][12];
                            $row['provspecdelmona'] = @$res[0][13];
                            $row['provspecdelmonb'] = @$res[0][14];
                            $row['provspecdelmonc'] = @$res[0][15];
                            $row['provspecdelmond'] = @$res[0][16];
                            $main_aim_query = "SELECT LEVEL FROM framework_qualifications WHERE REPLACE(framework_qualifications.id,'/','') IN (SELECT REPLACE(student_qualifications.id,'/','') FROM student_qualifications WHERE tr_id = $tr_id) AND main_aim  = 1";
                            $row['main_aim_level'] = DAO::getSingleValue($link, $main_aim_query);
                        }


                        $ilrSql = <<<ilrSQL
SELECT
	(SELECT contracts.`contract_year` FROM contracts WHERE contracts.id = ilr.`contract_id`) AS contract_year,
    extractvalue(ilr, "/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode") AS nlm,
	extractvalue(ilr, "/Learner/LLDDHealthProb|ilr/learner/L14") AS lldd_health_problem,
	extractvalue(ilr, "/Learner/LLDDandHealthProblem[PrimaryLLDD='1']/LLDDCat") AS primary_lldd_cat
FROM
	ilr
WHERE
	ilr.tr_id = '{$tr_id}'
ORDER BY
	ilr.`contract_id` DESC, submission DESC
LIMIT
	0,1
ilrSQL;
                        $ilrRow = DAO::getObject($link, $ilrSql);
                        foreach (['nlm', 'lldd_health_problem', 'primary_lldd_cat'] as $_f) {
                            if (isset($row[$_f])) {
                                $row[$_f] = isset($ilrRow->$_f) ? $ilrRow->$_f : '';
                            }
                        }


                        $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as assessor
                                            FROM group_members
                                            INNER JOIN groups ON groups.id = group_members.`groups_id`
                                            INNER JOIN users ON users.id = groups.`assessor`
                                            WHERE tr_id = '$tr_id'
                                            UNION
                                            SELECT CONCAT(users.firstnames, ' ',users.surname)
                                            FROM users
                                            INNER JOIN tr ON tr.assessor = users.`id` AND tr.id = '$tr_id';");
                        if ($stgroups) {
                            while ($rowgroups = $stgroups->fetch()) {
                                $row['assessor'] = $rowgroups['assessor'];
                            }
                        }

                        // Groups
                        $stgroups = $link->query("select title from groups where id in (select groups_id from group_members where tr_id = $tr_id);");
                        if ($stgroups) {
                            while ($rowgroups = $stgroups->fetch()) {
                                $row['group_title'] = $rowgroups['title'];
                            }
                        }
                        // Groups

                        // Tutor
                        $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as tutor
                                        FROM group_members
                                        INNER JOIN groups ON groups.id = group_members.`groups_id`
                                        INNER JOIN users ON users.id = groups.`tutor`
                                        WHERE tr_id = '$tr_id'
                                        UNION
                                        SELECT CONCAT(users.firstnames, ' ',users.surname)
                                        FROM users
                                        INNER JOIN tr ON tr.tutor = users.`id` AND tr.id = '$tr_id';");
                        if ($stgroups) {
                            $tutor = '';
                            while ($rowgroups = $stgroups->fetch()) {
                                $tutor .= $rowgroups['tutor'];
                            }
                        }
                        $row['tutor'] = $tutor;

                        // Verifier
                        $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as verifier
                                            FROM group_members
                                            INNER JOIN groups ON groups.id = group_members.`groups_id`
                                            INNER JOIN users ON users.id = groups.`verifier`
                                            WHERE tr_id = '$tr_id' and groups.provider_ref is null
                                            UNION
                                            SELECT CONCAT(users.firstnames, ' ',users.surname)
                                            FROM users
                                            INNER JOIN tr ON tr.verifier = users.`id` AND tr.id = '$tr_id';");
                        if ($stgroups) {
                            $verifier = '';
                            while ($rowgroups = $stgroups->fetch()) {
                                $verifier = $verifier . $rowgroups['verifier'];
                            }
                        }
                        $row['verifier'] = $verifier;
                        $zprog_plan_end_date = DAO::getSingleValue($link, "SELECT DATE_FORMAT(extractvalue(ilr.ilr, \"/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearnPlanEndDate\"), \"%d/%m/%Y\") FROM ilr WHERE ilr.tr_id = '{$tr_id}'  ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                        $row['zprog_plan_end_date'] = $zprog_plan_end_date;
                    }
                    if ($extra == 'ViewBirminghamLAReport') {
                        $tr_id = $row['tr_id'];
                        $contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts WHERE id = " . $row['contract_id']);
                        if ($contract_year < 2012) {
                            $ilrDestinationCode = '"' . "/learner/L39" . '"';
                        } else {
                            $ilrDestinationCode = '"' . "/Learner/Dest" . '"';
                        }
                        $res = DAO::getResultset($link, "select extractvalue(ilr, $ilrDestinationCode) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
                        $row['destination_code'] = (isset($res[0][0]) and ($res[0][0] != 'undefined')) ? $res[0][0] : '&nbsp';
                        if ($row['destination_code'] != '' && $row['destination_code'] != 'undefined') {
                            $row['destination'] = DAO::getSingleValue($link, "SELECT LEFT(CONCAT(Dest, ' ', Dest_Desc),50), null from lis201415.ilr_dest WHERE Dest = " . $row['destination_code']);
                        }
                    }
                    if ($extra == 'ViewAssessmentPlanLogs') {
                        if ($row['status'] == '1') {
                            $row['status'] = "Green";
                        } elseif ($row['status'] == '2') {
                            $row['status'] = "Yellow";
                        } elseif ($row['status'] == '3') {
                            $row['status'] = "Red";
                        }
                        $paperwork_ddl = array(
                            array('1', 'In progress'),
                            array('2', 'Awaiting marking'),
                            array('3', 'Complete'),
                            array('4', 'Rework required'),
                            array('5', 'IQA'),
                            array('6', 'Overdue')
                        );
                        $row['paperwork'] = $paperwork_ddl[$row['paperwork'] - 1][1];
                        $mode_ddl = array(
                            array('1', 'Analysis'),
                            array('2', 'Business Operation'),
                            array('3', 'Communication'),
                            array('4', 'Customer Service'),
                            array('5', 'Data'),
                            array('6', 'Digital Analytics'),
                            array('7', 'Digital Tools'),
                            array('8', 'Implementation'),
                            array('9', 'Industry Developments & Practices'),
                            array('10', 'Problem Solving'),
                            array('11', 'Research'),
                            array('12', 'Specialist Areas'),
                            array('13', 'Technologies'),
                            array('14', 'H&S'),
                            array('15', 'Remote Infrastructure'),
                            array('16', 'Workflow Management'),
                            array('17', 'IT Security'),
                            array('18', 'WEEE'),
                            array('19', 'Performance'),
                            array('20', 'Business'),
                            array('21', 'Development Lifecycle'),
                            array('22', 'Logic'),
                            array('23', 'Quality'),
                            array('24', 'Security'),
                            array('25', 'Test'),
                            array('26', 'User Interface'),
                            array('27', 'Assess & Qualify Sales Leads'),
                            array('28', 'Context & CPD'),
                            array('29', 'Customer Experience'),
                            array('30', 'Data Security'),
                            array('31', 'Database & Campaign Management'),
                            array('32', 'Sales Process'),
                            array('33', 'Data manipulating & Linking'),
                            array('34', 'Performance Queries'),
                            array('35', 'Data Quality'),
                            array('36', 'Presenting Data'),
                            array('37', 'Investigation Techniques'),
                            array('38', 'Data Modelling'),
                            array('39', 'Stakeholder Analysis & Management'),
                            array('40', 'Diagnostic Tools & Techniques'),
                            array('41', 'Integrating Network Software'),
                            array('42', 'Monitor Test & Adjust Networks'),
                            array('43', 'Service Level Agreements'),
                            array('44', 'Business Environment'),
                            array('45', 'Operational Requirements'),
                            array('46', 'Advise and Support Others'),
                            array('47', 'Developing & Collecting Data'),
                            array('48', 'Presenting Test Results'),
                            array('49', 'Test Cases'),
                            array('50', 'Legislation'),
                            array('51', 'Technical'),
                            array('52', 'Data Analysis Security & Policies'),
                            array('53', 'Statistical Analysis'),
                            array('54', 'Applications'),
                            array('55', 'Data Architecture'),
                            array('56', 'Business Process Modelling'),
                            array('57', 'Gap Analysis'),
                            array('58', 'Business Impact Assessment'),
                            array('59', 'Documenting'),
                            array('60', 'Interpret Written Requirements and Tech Specs'),
                            array('61', 'Network Installation'),
                            array('62', 'Troubleshooting & Repair'),
                            array('63', 'Deployment'),
                            array('64', 'Testing'),
                            array('65', 'Conduct Software Testing'),
                            array('66', 'Implementing Software Testing'),
                            array('67', 'Results vs Expectations'),
                            array('68', 'Test Outcomes'),
                            array('69', 'Project Management'),
                            array('70', 'Data Migration'),
                            array('71', 'Collect & Compile Data'),
                            array('72', 'Analytical Techniques'),
                            array('73', 'Reporting Data'),
                            array('74', 'Business Analysis'),
                            array('75', 'Requirements Engineering & Management'),
                            array('76', 'Acceptance Testing'),
                            array('77', 'Design Networks from a Specification'),
                            array('78', 'Effective Business Operation'),
                            array('79', 'Logging & Responding to Calls'),
                            array('80', 'Network Performance'),
                            array('81', 'Upgrading Network Systems'),
                            array('82', 'Design'),
                            array('83', 'User Interface'),
                            array('84', 'Design Test Strategies'),
                            array('85', 'Legislation & Standards'),
                            array('86', 'Software Requirements'),
                            array('87', 'Service Level Agreements '),
                            array('88', 'Test Plans'),
                            array('89', 'Test Outcomes')
                        );

                        if (isset($row['mode'])) {
                            $row['mode'] = $mode_ddl[$row['mode'] - 1][1];
                        }

                        $row['assessment_progress'] = '1';
                        // AP PRogress
                        $class = 'bg-green';
                        $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                        $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                        $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork = '3';");
                        $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                        if (isset($max_month_row->id)) {
                            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                            $class = 'bg-red';
                            if ($current_training_month == 0) {
                                $class = 'bg-green';
                            } elseif ($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps) {
                                $class = 'bg-green';
                            } else {
                                $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id LIMIT 1");
                                if ($aps_to_check == '' || $passed_units >= $aps_to_check) {
                                    $class = 'bg-green';
                                }
                            }
                        }
                        /*if(count($total_units) != 0)
                            $row['assessment_progress'] = round(($passed_units/$total_units) * 100);
                        else
                            $row['assessment_progress']=0;
                        */

                    }
                    if ($extra == 'ViewGroupEmployers') {
                        if ($row['timeliness'] <= 60 and $row['timeliness'] > 0) {
                            $row['h_and_s_due_in_two_months'] = 'Yes';
                        }
                        if (isset($row['telephone']) && $row['telephone'] != '') {
                            $row['telephone'] = '"' . $row['telephone'] . '"';
                        }
                        if (isset($row['contact_telephone']) && $row['contact_telephone'] != '') {
                            $row['contact_telephone'] = '"' . $row['contact_telephone'] . '"';
                        }
                    }
                    if ($extra == 'ViewReviewsReport') {

                        $review_id = $row['review_id'];
                        if (DB_NAME == 'am_baltic' or DB_NAME == 'am_baltic_demo') {
                            $last_review = DAO::getResultset($link, "SELECT assessor_review.`meeting_date`
,(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id) AS emailed
,(SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id) AS signature
,DATEDIFF((SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id),(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id)) AS days
,paperwork_received
FROM assessor_review
WHERE id = '$review_id' ORDER BY meeting_date DESC LIMIT 0,1;");

                            if (@$last_review[0][1] == '') {
                                $row['review_status'] = 'Not emailed';
                            } elseif (@$last_review[0][2] == '') {
                                $row['review_status'] = 'Red';
                            } elseif (@$last_review[0][3] <= 7) {
                                $row['review_status'] = 'Green';
                            } elseif (@$last_review[0][3] <= 28) {
                                $row['review_status'] = 'Amber';
                            } elseif (@$last_review[0][3] > 28) {
                                $row['review_status'] = 'Red';
                            } else {
                                $row['review_status'] = 'Red';
                            }
                            switch (@$last_review[0][5]) {
                                case 0:
                                    $row['paperwork_received'] = 'Not Received';
                                    break;
                                case 1:
                                    $row['paperwork_received'] = 'Received';
                                    break;
                                case 2:
                                    $row['paperwork_received'] = 'Rejected';
                                    break;
                                case 3:
                                    $row['paperwork_received'] = 'Accepted';
                                    break;
                                default:
                                    $row['paperwork_received'] == '';
                                    break;
                            }
                        }


                        if ($row['paperwork_received'] == 1) {
                            $row['paperwork_received'] = "Received";
                        } elseif ($row['paperwork_received'] == 0) {
                            $row['paperwork_received'] = "Not Received";
                        } elseif ($row['paperwork_received'] == 2) {
                            $row['paperwork_received'] = "Rejected";
                        } elseif ($row['paperwork_received'] == 10) {
                            $row['paperwork_received'] = "";
                        }
                    }
                    if ($extra == 'ViewEVReport') {
                        $tr_id = $row['tr_id'];
                        $LearnAimRef = str_replace("/", "", $row['a09']);
                        if ($row['contract_year'] < 2012) {
                            $x = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A34|/ilr/main[A09='$LearnAimRef']/A34|/ilr/subaim[A09='$LearnAimRef']/A34" . '"';
                            $y = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A35|/ilr/main[A09='$LearnAimRef']/A35|/ilr/subaim[A09='$LearnAimRef']/A35" . '"';
                            $z = "0";
                        } else {
                            $x = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/CompStatus" . '"';
                            $y = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/Outcome" . '"';
                            $z = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode" . '"';
                        }
                        $res = DAO::getResultset($link, "select extractvalue(ilr,$x),extractvalue(ilr,$y),extractvalue(ilr,$z) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
                        $row['comp_status'] = @$res[0][0];
                        $row['outcome'] = @$res[0][1];
                        if (isset($res[0][2])) {
                            $row['res'] = $res[0][2];
                        } else {
                            $row['res'] = '';
                        }

                        if ($row['contract_year'] < 2012) {
                            $row['res'] = '';
                        }
                        $minutes_attended = DAO::getSingleValue($link, "SELECT (SUM(HOUR(TIMEDIFF(time_to, time_from)))*60) + (SUM(MINUTE(TIMEDIFF(time_to, time_from)))) FROM otj WHERE tr_id = '{$tr_id}'");
                        $hours_attended = ViewOTJ::convertToHoursMins($minutes_attended, '%02d hours %02d minutes');
                        $minutes_remaining = ($row['otj_hours_due'] * 60) - $minutes_attended;
                        $hours_remaining = ViewOtj::convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');
                        $row['otj_hours_actual'] = $hours_attended;
                        $row['otj_hours_remain'] = $hours_remaining;
                    }
                    if ($extra == 'ViewIVReport') {
                        $tr_id = $row['tr_id'];
                        $minutes_attended = DAO::getSingleValue($link, "SELECT (SUM(HOUR(TIMEDIFF(time_to, time_from)))*60) + (SUM(MINUTE(TIMEDIFF(time_to, time_from)))) FROM otj WHERE tr_id = '{$tr_id}'");
                        $hours_attended = ViewOTJ::convertToHoursMins($minutes_attended, '%02d hours %02d minutes');
                        $minutes_remaining = ($row['otj_hours_due'] * 60) - $minutes_attended;
                        $hours_remaining = ViewOtj::convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');
                        $row['otj_hours_actual'] = $hours_attended;
                        $row['otj_hours_remain'] = $hours_remaining;

                    }
                    if ($extra == 'next_review_date') {
                        $tt = DAO::getResultset($link, 'SELECT MAX(meeting_date) AS `assessment_date`, GROUP_CONCAT(meeting_date) as all_dates FROM assessor_review where assessor_review.tr_id  = ' . $row['tr_id'] . ' AND assessor_review.meeting_date != \'0000-00-00\' group by assessor_review.tr_id');
                        $row['last_review_date'] = @$tt[0][0];
                        $row['all_dates'] = @$tt[0][1];

                        $start_date = $row['start_date'];
                        $display = true;
                        // Calculate Next Review
                        $tr_id = $row['tr_id'];
                        $subsequent = $row['frequency'];
                        $weeks = $row['subsequent'];
                        $dates = $row['all_dates'];
                        $planned_reviews = array();
                        $total_reviews = 0;
                        if ($dates != '') {
                            $dates = explode(",", $dates);
                            $total_reviews = sizeof($dates);
                            $next_review = new Date($row['start_date']);

                            if ($weeks == 1) {
                                $next_review->addMonths($weeks);
                            } else {
                                $next_review->addDays($weeks * 7);
                            }

                            $color = "red";
                            foreach ($dates as $date) {
                                if ($next_review->before($date) || DB_NAME == 'am_gigroup' || DB_NAME == 'am_aet' || DB_NAME == 'am_baltic') {
                                    if ($subsequent == 1) {
                                        $next_review->addMonths($subsequent);
                                    } else {
                                        $next_review->addDays($subsequent * 7);
                                    }
                                } else {
                                    $next_review = new Date($date);
                                    if ($subsequent == 1) {
                                        $next_review->addMonths($subsequent);
                                    } else {
                                        $next_review->addDays($subsequent * 7);
                                    }
                                }
                            }
                        } else {
                            $next_review = new Date($row['start_date']);
                            if ($weeks == 1) {
                                $next_review->addMonths($weeks);
                            } else {
                                $next_review->addDays($weeks * 7);
                            }
                        }
                        $row['next_review_date'] = $next_review->formatShort();
                        $planned_reviews[] = $next_review->formatShort();

                        $d = strtotime($next_review->formatMySQL());
                        $c = strtotime(date("Y-m-d"));
                        $color = 'blue';
                        if ($d < $c) {
                            $color = 'red';
                        }

                        $start_date = $this->getFilterValue('start_date');
                        $end_date = $this->getFilterValue('end_date');
                        if ($start_date != '') {
                            $start_date = new Date($start_date);
                            $s = strtotime($start_date->formatMySQL());
                            if ($s > $d) {
                                $display = false;
                            }
                        }
                        if ($end_date != '') {
                            $end_date = new Date($end_date);
                            $s = strtotime($end_date->formatMySQL());
                            if ($s < $d) {
                                $display = false;
                            }
                        }

                        // Remove from planned to get all missed
                        $c_date = date('d/m/Y');
                        if ($row['planned_end_date'] == '' || $row['planned_end_date'] == 'NULL') {
                            $p_date = new Date($row['start_date']);
                        } else {
                            $p_date = new Date($row['planned_end_date']);
                        }
                        if ($p_date->before($c_date)) {
                            $loop_date = $p_date->formatShort();
                        } else {
                            $loop_date = $c_date;
                        }

                        while ($next_review->before($loop_date)) {
                            if ($subsequent == 1) {
                                $next_review->addMonths($subsequent);
                            } else {
                                $next_review->addDays($subsequent * 7);
                            }
                            $planned_reviews[] = $next_review->formatShort();
                        }
                        $all_dates = explode(",", $row['all_dates']);
                        $net_planned_reviews = '';
                        $no_net_planned_reviews = 0;

                        $tr_end_date = null;
                        if (isset($row['status_code']) && $row['status_code'] != 1) {
                            $tr_end_date = DAO::getSingleValue($link, "SELECT closure_date FROM tr WHERE tr.id = '" . $row['tr_id'] . "'");
                        }
                        if ($tr_end_date != '' && !is_null($tr_end_date)) {
                            $tr_end_date = new Date($tr_end_date);
                        }

                        for ($pr = 0; $pr < sizeof($planned_reviews); $pr++) {
                            $frd = new Date($planned_reviews[$pr]);
                            if ($row['status_code'] != '1' && !is_null($tr_end_date)) {
                                if ($frd->before($loop_date) && !$frd->before($row['next_review_date']) && !$frd->after($tr_end_date)) {
                                    $net_planned_reviews .= (";" . $planned_reviews[$pr]);
                                }
                            } else {
                                if ($frd->before($loop_date) && (!$frd->before($row['next_review_date']))) {
                                    //$net_planned_reviews .= ("," . $planned_reviews[$pr]);
                                    //$no_net_planned_reviews++;
                                    $net_planned_reviews .= (";" . $planned_reviews[$pr]);
                                }
                            }
                        }
                        $net_planned_reviews = substr($net_planned_reviews, 1);
                        $row['dates_reviews_missed'] = $net_planned_reviews;
                        $row['no_reviews_missed'] = $no_net_planned_reviews;
                        $row['no_reviews_completed'] = $total_reviews;
                        $row['no_planned_reviews'] = $total_reviews + $no_net_planned_reviews;
                        $row['missed_reviews'] = HTML::cell($net_planned_reviews);
                        $row['missed_reviews'] = str_replace('&nbsp;', '', $row['missed_reviews']);
                    }
                    foreach ($row as $field => $value) {
                        if (in_array($field, $columns)) {
                            if (strlen($line) > 0) {
                                $line .= ',';
                            }

                            $value = trim((string)$value);
                            if (preg_match("/green-tick.gif/", $value)) {
                                $value = "Yes";
                            } elseif (preg_match("/red-cross.gif/", $value)) {
                                $value = "No";
                            } elseif (preg_match("/notstarted.gif/", $value)) {
                                $value = "Not Started";
                            } elseif (preg_match("/exempt.gif/", $value)) {
                                $value = "Exempt";
                            } elseif (preg_match("/warning-17.JPG/", $value)) {
                                $value = "Warning";
                            }
                            $value = str_replace(',', '', $value);
                            $value = str_replace(array("\n", "\r"), '', $value);
                            $value = str_replace("\t", '', $value);
                            if (DB_NAME != "am_reed" && DB_NAME == "am_reed_demo") {
                                if (strlen($value) == 10 and $field != 'uln') {
                                    $line .= str_replace('"', '""', $value);
                                } else {
                                    $line .= '="' . str_replace('"', '""', $value) . '"';
                                }
                            } else {
                                if (strlen($value) == 10 and $field != 'uln') {
                                    $line .= str_replace('"', '""', $value);
                                } else {
                                    $line .= '"' . str_replace('"', '""', $value) . '"';
                                }
                            }
                        }
                    }
                    echo $line . "\r\n";
                } while ($row = $st->fetch(PDO::FETCH_ASSOC));
            }
        } else {
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

        foreach ($this->filters as $filter) {
            $desc = $filter->getDescription();
            if ($desc != '') {
                $html .= ' <span class="filterCrumb">' . str_replace(' ', '&nbsp;', htmlspecialchars((string)$desc)) . '</span> ';
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
        if (!$filter) {
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
        if ($date->getDay() > 1) {
            $date->subtractDays($date->getDay() - 1);
        }
        $starting_weekday = $date->format("w"); // Sun == 0
        $weekdays = array("Su", "Mo", "Tu", "We", "Th", "Fr", "Sa");

        echo '<div class="text-center small"><i class="text-muted">Click a numeric date to view records for a particular day or a weekday heading to view records for a particular week</i></div>';
        echo '<table class="table table-bordered MonthNavigation" cellspacing="0" cellpadding="2">';
        echo '<tr><td colspan="33" align="center" class="MonthLabel">' . $date->format("F Y") . '</td></tr></tr>';

        echo '<tr>';
        echo '<td rowspan="2" class="NavigationPrevious" align="center" title="Previous month"><i class="fa fa-chevron-circle-left" style="font-size:25px;"></i> </td>';
        $weekday = $starting_weekday;
        for ($i = 1; $i <= 31; $i++) {
            if ($i > $days_in_month) {
                echo '<td class="DayLabel"></td>';
            } else {
                echo '<td class="DayLabel ' . (($weekday == 0 || $weekday == 6) ? "Weekend" : "Weekday") . '" align="center">' . $weekdays[$weekday] . '</td>';
                $weekday = ++$weekday > 6 ? 0 : $weekday;
            }
        }
        echo '<td rowspan="2" class="NavigationNext" align="center" title="Next month"><i class="fa fa-chevron-circle-right" style="font-size:25px;"></td>';
        echo '</tr>';

        echo '<tr class="dayValues">';
        $weekday = $starting_weekday;
        for ($i = 1; $i <= 31; $i++) {
            if ($i > $days_in_month) {
                echo '<td class="DayValue"></td>';
            } else {
                if ($i >= $start_day &&
                    (($start_month == $end_month && $i <= $end_day)
                        || ($start_month < $end_month)
                        || ($start_year < $end_year))) {
                    $selectedClass = "SelectedDay";
                } else {
                    $selectedClass = "";
                }
                $todayClass = ($i == $today_day && $start_month == $today_month && $start_year == $today_year) ? "Today" : "";
                echo '<td class="DayValue ' . $selectedClass . ' ' . $todayClass . '" align="center">' . $i . '</td>';
                $weekday = ++$weekday > 6 ? 0 : $weekday;
            }
        }
        echo '</tr>';
        echo '</table>';
    }

    public function getViewNavigatorExtra($al = 'center', $subview = '')
    {
        if (!array_key_exists(View::KEY_PAGE_SIZE, $this->filters)) {
            $records_per_page = 0;
        } else {
            $records_per_page = (integer)$this->filters[View::KEY_PAGE_SIZE]->getValue();
        }


        if ($records_per_page > 0) {
            $numPages = ceil($this->rowCount / $records_per_page);
        } else {
            $numPages = 1;
        }

        if (preg_match('/[&]{0,1}_action=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0) {
            $qs = '_action=' . $matches[1] . '&'; // extract the action
        } else {
            $qs = '';
        }

        if (preg_match('/[&]{0,1}subview=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0) {
            $qs .= 'subview=' . $matches[1] . '&'; // extract the subview
        }


        if (preg_match('/[&]{0,1}id=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0) {
            $qs .= 'id=' . $matches[1] . '&'; // extract the id
        }


        $pageNumberFieldName = get_class($this) . '_' . View::KEY_PAGE_NUMBER;
        $qsFirst = $qs . $pageNumberFieldName . '=' . '1';
        $qsPrevious = $qs . $pageNumberFieldName . '=' . ($this->pageNumber - 1);
        $qsNext = $qs . $pageNumberFieldName . '=' . ($this->pageNumber + 1);
        $qsLast = $qs . $pageNumberFieldName . '=' . ($numPages);
        $viewName = $this->getViewName();
        $pageNumber = $this->pageNumber;
        $pageNumberNext = $pageNumber + 1;
        $pageNumberPrev = $pageNumber - 1;

        // Page number dropdown
        $dropdown = "<select onchange=\"window.location.href='?{$qs}{$pageNumberFieldName}='+this.value;\">";
        $digits = strlen($numPages);
        for ($i = 1; $i <= $numPages; $i++) {
            if ($i != $this->pageNumber) {
                // done this way to add leading 0's where required
                $dropdown .= sprintf("<option value=\"%1\$d\">%1\${$digits}d</option>\n", $i);
            } else {
                $dropdown .= sprintf("<option value=\"%1\$d\" selected=\"selected\">%1\${$digits}d</option>\n", $i);
            }
        }
        $dropdown .= "</select>";

        if ($al == 'left')                                                // Khushnood
        {
            $html = '<div align="left" class="viewNavigator">';
        }    // Khushnood
        else                                                        // Khushnood
        {
            $html = '<div align="center" class="viewNavigator">';
        }
        $html .= '<table width="450"><tr>';

        if ($this->pageNumber <= 1) {
            $html .= <<<HEREDOC
<td width="20%" align="right"><button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/first-grey.gif" width="10" height="16" border="0"/></button>
<button style="width:30px" disabled="disabled"><img src="/images/view-navigation/previous-grey.gif" width="8" height="16" border="0"/></button></td>	
HEREDOC;
        } else {
            $html .= <<<HEREDOC
<td width="20%" align="right"><button onclick="this.disabled=true;window.location.href='?$qsFirst';return false;" style="width:30px;margin-right:12px;" title="First page"><img src="/images/view-navigation/first.gif" width="10" height="16" border="0"/></button>
<button onclick="this.disabled=true;window.location.href='?$qsPrevious';return false;" style="width:30px" title="Previous page"><img src="/images/view-navigation/previous.gif" width="8" height="16" border="0"/></button></td>
HEREDOC;
        }

        $html .= '<td align="center" width="60%" valign="middle">page ' . $dropdown . ' of ' . $numPages . ' (' . $this->rowCount . ' records)</td>';
        if ($this->pageNumber < $numPages) {
            $html .= <<<HEREDOC
<td width="20%" align="left"><button onclick="this.disabled=true;window.location.href='?$qsNext';return false;" style="width:30px;margin-right:12px;" title="Next page"><img src="/images/view-navigation/next.gif" width="8" height="16" border="0"/></button>
<button onclick="this.disabled=true;window.location.href='?$qsLast';return false;" style="width:30px" title="Final page"><img src="/images/view-navigation/last.gif" width="10" height="16" border="0"/></button></td>
HEREDOC;
        } else {
            $html .= <<<HEREDOC
<td width="20%" align="left"><button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/next-grey.gif" width="8" height="16" border="0"/></button>
<button style="width:30px" disabled="disabled"><img src="/images/view-navigation/last-grey.gif" width="10" height="16" border="0"/></button></td>
HEREDOC;
        }
        $html .= '</tr></table></div>';

        return $html;
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


    const KEY_PAGE_SIZE = '__page_size';
    const KEY_PAGE_NUMBER = '__page';
    const KEY_ORDER_BY = '__order_by';

    const FILTER_OPTIONS_TTL_SECONDS = 15;

}
