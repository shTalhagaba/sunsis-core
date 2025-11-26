<?php
class view_feedbacks implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
        
        $view = VoltView::getViewFromSession('ViewFeedbacks', 'ViewFeedbacks'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewFeedbacks'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        if($subaction == 'export_csv')
        {
            $this->export_csv($link, $view);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_feedbacks", "View Feedbacks");

        include_once('tpl_view_feedbacks.php');

    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT 
    learner_feedbacks.*,
    (q1+q2+q3+q4+q5+q6) AS q_total,
    crm_training_schedule.level, 
    crm_training_schedule.venue,
    crm_training_schedule.training_date,
    crm_training_schedule.training_end_date 

FROM 
    learner_feedbacks 
    LEFT JOIN training ON learner_feedbacks.training_id = training.id
    LEFT JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id
ORDER BY 
    created_at DESC 
		");

        $view = new VoltView('ViewFeedbacks', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_learner_name', "WHERE learner_feedbacks.learner_name LIKE '%s%%'", null);
        $f->setDescriptionFormat("Learner Name: %s");
        $view->addFilter($f);

        $format = "WHERE learner_feedbacks.created_at >= '%s'";
        $f = new VoltDateViewFilter('filter_from_submitted_date', $format, '');
        $f->setDescriptionFormat("From Submitted Date: %s");
        $view->addFilter($f);

        $format = "WHERE learner_feedbacks.created_at <= '%s'";
        $f = new VoltDateViewFilter('filter_to_submitted_date', $format, '');
        $f->setDescriptionFormat("To Submitted Date: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, '< 30', null, 'HAVING q_total < 30'),
            1=>array(2, '>= 30', null, 'HAVING q_total >= 30'));
        $f = new VoltDropDownViewFilter('filter_q_total', $options, null, true);
        $f->setDescriptionFormat("Total: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Submitted Date (Newest first), Learner Name', null, 'ORDER BY created_at DESC, learner_name ASC'),
            1=>array(2, 'Submitted Date (Oldest first), Learner Name', null, 'ORDER BY created_at ASC, learner_name ASC'));
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
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<br><div align="center" ><table id="tblEntries" class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';
            echo '<th>Submitted At</th><th>Learner Name</th><th>Level</th><th>Venue</th><th>Training Dates</th><th>Total Score Given</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_feedback&id='.$row['id']);
                echo '<td>' . Date::to($row['created_at'], Date::DATETIME) . '</td>';
                echo '<td>' . $row['learner_name'] . '</td>';
                echo '<td>' . $row['level'] . '</td>';
                echo '<td>' . $row['venue'] . '</td>';
                echo '<td>' . Date::toShort($row['training_date']) . ' - ' . Date::toShort($row['training_end_date']) . '</td>';
                echo '<td>' . $row['q_total'] . '</td>';

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
                if($row['level'] == 'L1')
                    echo 'Level 1,';
                elseif($row['level'] == 'L2')
                    echo 'Level 2,';
                elseif($row['level'] == 'L3')
                    echo 'Level 3,';
                elseif($row['level'] == 'L4')
                    echo 'Level 4,';
                else
                    echo ',';
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