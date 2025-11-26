<?php
class view_crm_scheduler implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view = VoltView::getViewFromSession('ViewCrmScheduler', 'ViewCrmScheduler'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewCrmScheduler'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_crm_scheduler", "View Training Scheudler");

        if($subaction == 'export_csv')
        {
            $view->exportToCSV($link);
            exit;
        }

        include_once 'tpl_view_crm_scheduler.php';
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
        SELECT 
            `id`,
            `training_date`,
            `level`,
            `capacity`,
            `venue`,
            `duration`,
            `training_end_date`,
            `trainer`,
            `start_time`,
            `end_time` 
        FROM 
            crm_training_schedule
        ");

	if($_SESSION['user']->employer_id == 3278)
        {
            $sql->setClause("WHERE crm_training_schedule.venue='Peterborough Skills Academy'");
        }

        $view = new VoltView('ViewCrmScheduler', $sql->__toString());

        $options = "SELECT DISTINCT venue, venue, NULL, CONCAT('WHERE crm_training_schedule.venue=', CHAR(39), venue, CHAR(39)) FROM lookup_crm_training_schedule_venue ORDER BY venue";
        $f = $_SESSION['user']->employer_id == 3278 ? new VoltDropDownViewFilter('filter_venue', $options, 'Peterborough Skills Academy', true) : new VoltDropDownViewFilter('filter_venue', $options, null, true);
        $f->setDescriptionFormat("Venue: %s");
        $view->addFilter($f);

        $format = "WHERE crm_training_schedule.training_date >= '%s'";
        $f = new VoltDateViewFilter('filter_from_training_date', $format, date('Y').'-01-01');
        $f->setDescriptionFormat("From start date: %s");
        $view->addFilter($f);

        $format = "WHERE crm_training_schedule.training_date <= '%s'";
        $f = new VoltDateViewFilter('filter_to_training_date', $format, '');
        $f->setDescriptionFormat("To start date: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Start Date (asc.)', null, 'ORDER BY crm_training_schedule.training_date ASC'),
            1=>array(2, 'Start Date (desc.)', null, 'ORDER BY crm_training_schedule.training_date DESC'));
        $f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
        $f->setDescriptionFormat("Sort by: %s");
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
        $f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 20, false);
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
            $duration_ddl = [];
            for($i = 1; $i <= 30; $i++)
            {
                $duration_ddl[] = [$i, $i];
            }
            $trainers_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames, ' ', surname), job_role FROM users WHERE users.type = 2 ORDER BY job_role, firstnames");
	    $veneues_ddl = $_SESSION['user']->employer_id == 3278 ?  
                DAO::getResultset($link, "SELECT DISTINCT venue, venue FROM lookup_crm_training_schedule_venue WHERE venue = 'Peterborough Skills Academy' ORDER BY venue") : 
                DAO::getResultset($link, "SELECT DISTINCT venue, venue FROM lookup_crm_training_schedule_venue ORDER BY venue");

            echo $view->getViewNavigatorExtra('', $view->getViewName());
            
            echo '<table class="table table-bordered table-condensed">';
            echo '<tr class="bg-gray-active"><th>Level</th><th>Training Date</th><th>Duration</th><th>Training End Date</th><th>Start Time</th><th>End Time</th><th>Capacity</th><th>Trainer</th><th>Venue</th><th>Actions</th></tr>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo '<tr>';
                echo '<td>' . HTML::select('level'.$row['id'], AppHelper::duplexTrainingLevelsDdl(), $row['level']) . '</td>';
                echo '<td>' . HTML::datebox('date'.$row['id'], $row['training_date']) . '</td>';
                echo '<td>' . HTML::selectChosen('duration'.$row['id'], $duration_ddl, $row['duration']) . '</td>';
                echo '<td>' . HTML::datebox('training_end_date'.$row['id'], $row['training_end_date']) . '</td>';
                echo '<td>' . HTML::timebox('start_time'.$row['id'], $row['start_time']) . '</td>';
                echo '<td>' . HTML::timebox('end_time'.$row['id'], $row['end_time']) . '</td>';
                echo '<td>' . HTML::textbox('capacity'.$row['id'], $row['capacity'], 'onkeypress="return numbersonly();" maxlength="2" size="2" id="capacity'.$row['id'].'"') . '</td>';
                
                echo '<td>' . HTML::selectChosen('trainer'.$row['id'], $trainers_ddl, $row['trainer']) . '</td>';
                echo '<td>' . HTML::selectChosen('venue'.$row['id'], $veneues_ddl, $row['venue'], false) . '</td>';
                echo '<td>';
                echo '<span class="btn btn-primary btn-xs" onclick="saveEntry(\''.$row['id'].'\');"><i class="fa fa-save"></i></span>&nbsp;';
                echo '<span class="btn btn-danger btn-xs" onclick="deleteEntry(\''.$row['id'].'\');"><i class="fa fa-trash"></i></span>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';

            echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }
}

