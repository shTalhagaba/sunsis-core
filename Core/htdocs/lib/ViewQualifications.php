<?php
class ViewQualifications extends View
{

    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            if(SystemConfig::getEntityValue($link, "manager") && ($_SESSION['user']->type==User::TYPE_MANAGER || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER))
            {
                $org_id = $_SESSION['user']->employer_id;
                $db = DB_NAME;
                $sql = <<<HEREDOC
SELECT
	id as qan,
	lsc_learning_aim as standard_ref,
	awarding_body,
	title,
	description,
	assessment_method,
	structure,
	(SELECT CONCAT(id, ' - ', description) FROM lookup_qual_level WHERE id = qualifications.level) as level,
	(SELECT CONCAT(id, ' - ', description) FROM lookup_qual_type WHERE id = qualification_type) as type,
	regulation_start_date,
	operational_start_date,
	operational_end_date,
	certification_end_date,
	#evidences,
	units,
	total_proportion,
	unitswithevidence,
	elements_without_evidence,
	units_required,
	EXTRACTVALUE(evidences, 'count(//unit[@mandatory="true"])') AS mandatory_units,
	clients,
	mainarea as tier_1,
	subarea as tier_2,
	qual_status,
	guided_learning_hours,
	EXTRACTVALUE(evidences, 'count(//unit)') AS total_units,
	qualifications.internaltitle as internal_title,
	guided_learning_hours
FROM
	qualifications 
	    INNER JOIN $db.provider_qualifications ON REPLACE($db.provider_qualifications.qualification_id,'/','') = REPLACE(qualifications.id,'/','') 
	    AND $db.provider_qualifications.internaltitle = qualifications.internaltitle
WHERE
    $db.provider_qualifications.org_id = '$org_id'
;
HEREDOC;
            }
            else
            {
                $sql = <<<HEREDOC
SELECT
	id as qan,
	lsc_learning_aim standard_ref,
	awarding_body,
	title,
	description,
	assessment_method,
	structure,
	level,
	(SELECT CONCAT(id, ' - ', description) FROM lookup_qual_type WHERE id = qualification_type) as type,
	regulation_start_date,
	operational_start_date,
	operational_end_date,
	certification_end_date,
	#evidences,
	units,
	total_proportion,
	unitswithevidence,
	elements_without_evidence,
	units_required,
	EXTRACTVALUE(evidences, 'count(//unit[@mandatory="true"])') AS mandatory_units,
	mainarea as tier_1,
	subarea as tier_2,
	clients,
	mainarea,
	subarea,
	qual_status,
	guided_learning_hours,
	EXTRACTVALUE(evidences, 'count(//unit)') AS total_units,
	internaltitle as internal_title
FROM
	qualifications;
HEREDOC;
            }

            $view = $_SESSION[$key] = new ViewQualifications();
            $view->setSQL($sql);

            $options = array(
                0 => array(20,20,null,null),
                1 => array(50,50,null,null),
                2 => array(100,100,null,null),
                3 => array(0,'No limit',null,null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(1, 'Title (asc), Type (asc)', null, 'ORDER BY internal_title, type'),
                1 => array(2, 'Type (asc), Level (asc)', null, 'ORDER BY type, level'),
                2 => array(3, 'Type (desc), Level (desc)', null, 'ORDER BY type DESC, level DESC'));
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT awarding_body, awarding_body, null, CONCAT(" WHERE awarding_body=",char(39),awarding_body,char(39)) FROM qualifications ORDER BY awarding_body';
            $f = new DropDownViewFilter('filter_awarding_body', $options, null, true);
            $f->setDescriptionFormat("Awarding Body: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT id, CONCAT(id, \' - \', description), null, CONCAT("WHERE qualifications.level=", CHAR(39), id, CHAR(39)) FROM lookup_qual_level ORDER BY id';
            $f = new DropDownViewFilter('filter_level', $options, null, true);
            $f->setDescriptionFormat("Level: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT id, CONCAT(id, \' - \', description), null, CONCAT("WHERE qualification_type=", CHAR(39), id, CHAR(39)) FROM lookup_qual_type ORDER BY description';
            $f = new DropDownViewFilter('filter_qualification_type', $options, null, true);
            $f->setDescriptionFormat("Qualification Type: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT mainarea, mainarea, null, CONCAT(" WHERE mainarea=",char(39),mainarea,char(39)) FROM qualifications ORDER BY mainarea';
            $f = new DropDownViewFilter('filter_qualification_mainarea', $options, null, true);
            $f->setDescriptionFormat("Qualification Sector Subject Area: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_title', "WHERE qualifications.title LIKE '%%%s%%' ", null);
            $f->setDescriptionFormat("Filter by Title: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_qan', "WHERE REPLACE(qualifications.id,'/','') LIKE replace('%%%s%%','/','') ", null);
            $f->setDescriptionFormat("Filter by QAN: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT subarea, subarea, null, CONCAT(" WHERE subarea=",char(39),subarea,char(39)) FROM qualifications ORDER BY subarea';
            $f = new DropDownViewFilter('filter_qualification_subarea', $options, null, true);
            $f->setDescriptionFormat("Qualification Sector Subject Sub-area: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(1, 'Accessible', null, ' WHERE clients LIKE "%' . DB_NAME .'%"'),
                1 => array(2, 'Not Accessible', null, ' WHERE clients NOT LIKE "%' . DB_NAME . '%"'));
            $f = new DropDownViewFilter('filter_accessibility', $options, 1, false);
            $f->setDescriptionFormat("Access: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'All Qualifications', null, null),
                1=>array(2, 'Fully built', null, ' WHERE qual_status = 1'),
                2=>array(3, 'Unit Level only', null, ' WHERE qual_status = 0'));
            $f = new DropDownViewFilter('filter_status', $options, 1, false);
            $f->setDescriptionFormat("Status: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'All Qualifications', null, null),
                1=>array(2, 'Active Qualifications', null, 'WHERE  active = 1'),
                2=>array(3, 'Inactive Qualifications', null, 'WHERE active <> 1'));
            $f = new DropDownViewFilter('by_active', $options, 2, false);
            $f->setDescriptionFormat("Active: %s");
            $view->addFilter($f);
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link, $columns)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table class="table table-bordered">';
            echo '<thead><tr><th>&nbsp;</th>';

            foreach($columns as $column)
            {
                echo '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . '</th>';
            }
            echo '</tr></thead><tbody>';
            while($row = $st->fetch())
            {
                $pos = strpos($row['clients'], DB_NAME);
                if($pos === false)
                {
                    echo '<tr>';
                }
                else
                {
                    echo HTML::viewrow_opening_tag('do.php?_action=read_qualification&id=' . rawurlencode($row['qan']) . '&internaltitle=' . rawurlencode($row['internal_title']));
                }

                echo '<td><img src="/images/rosette.gif" /></td>';

                foreach($columns as $column)
                {
                    if($column == "total_units")
                    {
                        echo '<td align="center">' . htmlspecialchars((string)$row['mandatory_units']) . " / " . htmlspecialchars((string)$row['total_units']-$row['mandatory_units']) .  "</td>";
                    }
                    else
                    {
                        echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                    }
                }
                echo '</tr>';
            }
            echo '</tbody></table></div>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}
?>