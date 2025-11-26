<?php
class view_crm_schedule_entries implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
        
        $view = VoltView::getViewFromSession('ViewCrmScheduleEntries', 'ViewCrmScheduleEntries'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewCrmScheduleEntries'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        if($subaction == 'export_csv')
        {
            $this->export_csv($link, $view);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_crm_schedule_entries", "View/Manage CRM Training");

        include_once('tpl_view_crm_schedule_entries.php');

    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT 
       crm_training_schedule.*, 
       (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = crm_training_schedule.trainer) AS trainer_name,
       (SELECT COUNT(*) FROM training WHERE training.`schedule_id` = crm_training_schedule.`id`) AS assigned,
       DATE_FORMAT(crm_training_schedule.training_date, '%a') as `dayofweek`
FROM 
     crm_training_schedule 
ORDER BY training_date 
		");

	if($_SESSION['user']->employer_id == 3278)
        {
            $sql->setClause("WHERE crm_training_schedule.venue = 'Peterborough Skills Academy'");
        }

        $view = new VoltView('ViewCrmScheduleEntries', $sql->__toString());

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Level 1', null, 'WHERE crm_training_schedule.level = "L1"'),
            2=>array(2, 'Level 2', null, 'WHERE crm_training_schedule.level = "L2"'),
            3=>array(3, 'Level 3', null, 'WHERE crm_training_schedule.level = "L3"'),
            4=>array(4, 'Level 4', null, 'WHERE crm_training_schedule.level = "L4"'),
            5=>array(5, 'MAN Level 3', null, 'WHERE crm_training_schedule.level = "ML3"'),
            6=>array(6, 'F-Gas', null, 'WHERE crm_training_schedule.level = "FG"'),
            7=>array(7, 'ADAS Level 1', null, 'WHERE crm_training_schedule.level = "ADASL1"'),
            8=>array(8, 'ADAS Level 2', null, 'WHERE crm_training_schedule.level = "ADASL2"'),
            9=>array(9, 'ADAS Level 3', null, 'WHERE crm_training_schedule.level = "ADASL3"'),
            10=>array(10, 'LVDT', null, 'WHERE crm_training_schedule.level = "LVDT"'),
        );
        $f = new VoltDropDownViewFilter('filter_level', $options, 0, false);
        $f->setDescriptionFormat("Level: %s");
        $view->addFilter($f);

        $format = "WHERE crm_training_schedule.training_date >= '%s'";
        $f = new VoltDateViewFilter('filter_from_training_date', $format, '');
        $f->setDescriptionFormat("From Training Date: %s");
        $view->addFilter($f);

        $format = "WHERE crm_training_schedule.training_date <= '%s'";
        $f = new VoltDateViewFilter('filter_to_training_date', $format, '');
        $f->setDescriptionFormat("To Training Date: %s");
        $view->addFilter($f);

        $options = DAO::getResultset($link, "SELECT DISTINCT crm_training_schedule.trainer, CONCAT(firstnames, ' ', surname), null, CONCAT('WHERE crm_training_schedule.trainer=', crm_training_schedule.trainer) FROM crm_training_schedule INNER JOIN users ON crm_training_schedule.trainer = users.id ORDER BY users.firstnames");
        $f = new VoltDropDownViewFilter('filter_trainer', $options, '', true);
        $f->setDescriptionFormat("Trainer: %s");
        $view->addFilter($f);

        $options = DAO::getResultset($link, "SELECT DISTINCT venue, venue, null, CONCAT('WHERE crm_training_schedule.venue=\"', venue, '\"') FROM crm_training_schedule ORDER BY venue");
        $f = new VoltDropDownViewFilter('filter_venue', $options, '', true);
        $f->setDescriptionFormat("Venue: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(20,20,null,null),
            1=>array(50,50,null,null),
            2=>array(100,100,null,null),
            3=>array(200,200,null,null),
            4=>array(300,300,null,null),
            5=>array(400,400,null,null),
            6=>array(500,500,null,null),
            7=>array(0, 'No limit', null, null));
        $f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {
        //pr($view->getSQLStatement()->__toString());
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table id="tblEntries" class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';
            echo '<th>Start Date</th><th>End Date</th><th>Duration</th><th>Level</th><th>Capacity</th><th>Venue</th><th>Assigned</th><th>Remaining</th><th>Trainer</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=view_edit_crm_schedule&id='.$row['id']);
                echo '<td>'.Date::toShort($row['training_date']).'</td>';
                echo '<td>'.Date::toShort($row['training_end_date']).'</td>';
                echo '<td>'.$row['duration'].'</td>';
                echo '<td>' . AppHelper::duplexTrainingLevelDesc($row['level']) . '</td>';
                echo '<td>'.$row['capacity'].'</td>';
                echo '<td>'.$row['venue'].'</td>';
                $capacity = intval($row['capacity']);
                $assigned = intval($row['assigned']);
                $remaining = $capacity - $assigned;
                echo '<td>'.$assigned.'</td>';
                echo '<td>'.$remaining.'</td>';
                echo '<td>'.$row['trainer_name'].'</td>';

                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function export_csv(PDO $link, $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=TrainingDates.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            echo "Start Date,End Date,Duration,Level,Capacity,Venue,Assigned,Remaining,Trainer";
            echo "\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo Date::toShort($row['training_date']).',';
                echo Date::toShort($row['training_end_date']).',';
                echo $row['duration'].',';
                echo AppHelper::duplexTrainingLevelDesc($row['level']).',';		
                echo $row['capacity'].',';
                echo $row['venue'].',';
                $capacity = (int)$row['capacity'];
                $assigned = intval($row['assigned']);
                $remaining = $capacity - $assigned;
                echo $assigned.',';
                echo $remaining.',';
                echo HTML::csvSafe($row['trainer_name']).',';

                echo "\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

}