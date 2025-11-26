<?php
class ViewQualifications extends View
{

    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT
	title,
	id as qan,
	lsc_learning_aim standard_ref,
	qualification_type as type,
	level,
	awarding_body,
	description,
	assessment_method,
	structure,
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
	mandatory_units,
	mainarea as tier_1,
	subarea as tier_2,
	clients,
	mainarea,
	subarea,
	qual_status,
	guided_learning_hours,
	concat('"',mandatory_units,'/',(units-mandatory_units),'"') as total_units,
	internaltitle as internal_title,
	IF(qualifications.active = '1', 'Yes', 'No') AS is_active,
    qualifications.auto_id   
	
FROM
	qualifications;
HEREDOC;

            $view = $_SESSION[$key] = new ViewQualifications();
            $view->setSQL($sql);

            $f = new TextboxViewFilter('filter_qan', "WHERE replace(qualifications.id,'/','') LIKE replace('%%%s%%','/','') ", null);
            $f->setDescriptionFormat("Filter by QAN: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_title', "WHERE qualifications.title LIKE '%%%s%%' ", null);
            $f->setDescriptionFormat("Filter by Title: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT awarding_body, awarding_body, NULL, CONCAT(" WHERE awarding_body=",CHAR(39),awarding_body,CHAR(39)) FROM qualifications WHERE awarding_body IS NOT NULL ORDER BY awarding_body;	';
            $f = new DropDownViewFilter('filter_awarding_body', $options, null, true);
            $f->setDescriptionFormat("Awarding Body: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT level, level, null, CONCAT(" WHERE level=",char(39),level,char(39)) FROM qualifications order by level';
            $f = new DropDownViewFilter('filter_level', $options, null, true);
            $f->setDescriptionFormat("Level: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT qualification_type, qualification_type, null, CONCAT(" WHERE qualification_type=",char(39),qualification_type,char(39)) FROM qualifications order by qualification_type';
            $f = new DropDownViewFilter('filter_qualification_type', $options, null, true);
            $f->setDescriptionFormat("Qualification Type: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(0,'No limit',null,null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Title (asc), Type (asc)', null, 'ORDER BY internal_title, type'),
                1=>array(2, 'Type (asc), Level (asc)', null, 'ORDER BY type, level'),
                2=>array(3, 'Type (desc), Level (desc)', null, 'ORDER BY type DESC, level DESC'));
            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT awarding_body, awarding_body, null, CONCAT(" WHERE awarding_body=",char(39),awarding_body,char(39)) FROM qualifications order by awarding_body';
            $f = new DropDownViewFilter('filter_awarding_body', $options, null, true);
            $f->setDescriptionFormat("Awarding Body: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT level, level, null, CONCAT(" WHERE level=",char(39),level,char(39)) FROM qualifications order by level';
            $f = new DropDownViewFilter('filter_level', $options, null, true);
            $f->setDescriptionFormat("Level: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT qualification_type, qualification_type, null, CONCAT(" WHERE qualification_type=",char(39),qualification_type,char(39)) FROM qualifications order by qualification_type';
            $f = new DropDownViewFilter('filter_qualification_type', $options, null, true);
            $f->setDescriptionFormat("Qualification Type: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT mainarea, mainarea, null, CONCAT(" WHERE mainarea=",char(39),mainarea,char(39)) FROM qualifications order by mainarea';
            $f = new DropDownViewFilter('filter_qualification_mainarea', $options, null, true);
            $f->setDescriptionFormat("Qualification Sector Subject Area: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_title', "WHERE qualifications.title LIKE '%%%s%%' ", null);
            $f->setDescriptionFormat("Filter by Title: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_qan', "WHERE replace(qualifications.id,'/','') LIKE replace('%%%s%%','/','') ", null);
            $f->setDescriptionFormat("Filter by QAN: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT subarea, subarea, null, CONCAT(" WHERE subarea=",char(39),subarea,char(39)) FROM qualifications order by subarea';
            $f = new DropDownViewFilter('filter_qualification_subarea', $options, null, true);
            $f->setDescriptionFormat("Qualification Sector Subject Sub-area: %s");
            $view->addFilter($f);

            // $options = array(
            //     0=>array(1, 'Accessible', null, ' where clients like "%' . DB_NAME .'%"'),
            //     1=>array(2, 'Not Accessible', null, ' where clients not like "%' . DB_NAME . '%"'));
            // $f = new DropDownViewFilter('filter_accessibility', $options, 1, false);
            // $f->setDescriptionFormat("Access: %s");
            // $view->addFilter($f);

            $options = array(
                0=>array(1, 'All Qualifications', null, null),
                1=>array(2, 'Fully built', null, ' where qual_status = 1'),
                2=>array(3, 'Unit Level only', null, ' where qual_status = 0'));
            $f = new DropDownViewFilter('filter_status', $options, 1, false);
            $f->setDescriptionFormat("Status: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'All Qualifications', null, null),
                1=>array(2, 'Active Qualifications', null, 'where  active=1'),
                2=>array(3, 'Inactive Qualifications', null, 'where active<>1'));
            $f = new DropDownViewFilter('by_active', $options, 2, false);
            $f->setDescriptionFormat("Active: %s");
            $view->addFilter($f);
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        $columns = $this->getSelectedColumns($link);
        
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());

        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table class="table table-bordered sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th>&nbsp;</th>';

            foreach($columns as $column)
            {
                echo '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . '</th>';
            }

            echo '</tr></thead><tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_qualification&id=' . rawurlencode($row['qan']) . '&internaltitle=' . rawurlencode($row['internal_title']) . '&auto_id=' . $row['auto_id']);

                echo '<td><img src="/images/rosette.gif" /></td>';

                foreach($columns as $column)
                {
                    if($column != "total_units")
                        echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                    else if($column == "total_units")
                    {
                        echo '<td align="center">' . htmlspecialchars($row['mandatory_units'] ?? '') . " / " . htmlspecialchars($row['units']-$row['mandatory_units']) .  "</td>";
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