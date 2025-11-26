<?php
class edit_crm_subjects implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=read_trainingprovider&id=" . $id, "View Training Provider");

        $vo = new TrainingProvider();

        // Load categories of organisation
        $lookup_org_type = "SELECT id, org_type FROM lookup_org_type ORDER BY id;";
        $lookup_org_type = DAO::getLookupTable($link, $lookup_org_type);

        // Page title
        if($vo->id == 0)
        {
            $page_title = "New Training Provider";
        }
        elseif(strlen($vo->trading_name) > 50)
        {
            $page_title = substr($vo->trading_name, 0, 50).'...';
        }
        else
        {
            $page_title = $vo->trading_name;
        }

        $type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
        $type_checkboxes = DAO::getResultset($link, $type_checkboxes);





        $course_sql = <<<HEREDOC
SELECT
	DISTINCT
	courses.id,
	title,
	NULL
FROM
	courses
#INNER JOIN courses_tr ON courses_tr.course_id = courses.id
#INNER JOIN tr ON tr.id = courses_tr.tr_id
#INNER JOIN locations ON locations.id = tr.employer_location_id
WHERE courses.organisations_id = '$id';
HEREDOC;
        $course_select = DAO::getResultset($link, $course_sql);

        $sql = <<<HEREDOC
SELECT
	*
FROM
	events_template
WHere
	provider_id = '$id';
HEREDOC;

        $eventsResultSet = $link->query($sql);

        // Presentation
        include('tpl_edit_crm_subjects.php');
    }


    private function renderLearners(PDO $link, TrainingProvider $vo)
    {
        $personnel = $vo->getLearners($link);
        if(count($personnel) > 0)
        {
            echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
            echo '<tr><th>&nbsp;</th><th>Surname</th><th>Firstnames</th><th>Telephone</th></tr>';

            foreach($personnel as $per)
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $per->username);
                echo '<td><a href="do.php?_action=read_personnel&id=' . $per->username . '"><img src="/images/blue-person.png" border="0" /></a></td>';
                echo '<td>' . HTML::cell($per->surname) . '</td>';
                echo '<td>' . HTML::cell($per->firstnames) . '</td>';
                echo '<td>' . HTML::cell($per->work_telephone) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        }
        else
        {
            echo '<p class="sectionDescription">None entered.</p>';
        }
    }

    private function format_size($size)
    {
        $sizes = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        if($size == 0)
        {
            return('n/a');
        }
        else
        {
            $i = 0;
            $s = $size;
            while($size > 1024){
                $size = $size/1024;
                $i++;
            }
            return sprintf("%.1f" . $sizes[$i], $size);
            //return sprintf("%.1f",($size/pow(1024, ($i = floor(log($size, 1024))))) . $sizes[$i]);
        }
    }


}
?>