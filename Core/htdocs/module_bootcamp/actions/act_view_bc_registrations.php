<?php
class view_bc_registrations implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
        
        $view = VoltView::getViewFromSession('ViewBCregistrations', 'ViewBCregistrations'); /* @var $view VoltView */
        //if(is_null($view))
        {
            $view = $_SESSION['ViewBCregistrations'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        if($subaction == 'export_csv')
        {
            $this->export_csv($link, $view);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_bc_registrations", "View Applicants");

        include_once('tpl_view_bc_registrations.php');

    }

    private function buildView(PDO $link)
    {
        $awaitingInfo = Registration::STATUS_AWAITING_INFO;
        $awaitingCompliance = Registration::STATUS_COMPLIANCE_AWAITING;
        $compliant = Registration::STATUS_COMPLIANCE_COMPLETE;
        $learnerCreated = Registration::STATUS_LEARNER_CREATED;
        $nonCompliant = Registration::STATUS_NOT_COMPLIANT;

        $sql = new SQLStatement("
SELECT 
    registrations.*,
    IF( 
        registrations.is_finished IS NULL OR registrations.is_finished = 'N',
        '{$awaitingInfo}',
        IF(
            registrations.is_finished = 'Y' AND registrations.is_compliant = 0,
            '{$awaitingCompliance}',
            IF(
                registrations.is_finished = 'Y' AND registrations.is_compliant = 2,
                '{$nonCompliant}',
                IF(
                    registrations.entity_id IS NOT NULL,
                    '{$learnerCreated}',
                    IF(
                        registrations.is_finished = 'Y' AND registrations.is_compliant = 1,
                        '{$compliant}',
                        ''
                    )
                )
            )
        )
     ) AS registration_status

FROM 
    registrations
ORDER BY 
    learner_sign_date DESC 
		");

        // $sql->setClause("WHERE registrations.is_finished = 'Y'");

        $view = new VoltView('ViewBCregistrations', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE registrations.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("Learner First Name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE registrations.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Learner Surname: %s");
        $view->addFilter($f);

        $format = "WHERE registrations.learner_sign_date >= '%s'";
        $f = new VoltDateViewFilter('filter_from_learner_sign_date', $format, '');
        $f->setDescriptionFormat("From Learner Signed Date: %s");
        $view->addFilter($f);

        $format = "WHERE registrations.learner_sign_date <= '%s'";
        $f = new VoltDateViewFilter('filter_to_learner_sign_date', $format, '');
        $f->setDescriptionFormat("To Learner Signed Date: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Awaiting Information', null, 'HAVING registration_status = "' . $awaitingInfo . '"'),
            1=>array(2, 'Awaiting Compliance Check', null, 'HAVING registration_status = "' . $awaitingCompliance . '"'),
            2=>array(3, 'Compliant', null, 'HAVING registration_status = "' . $compliant . '"'),
            3=>array(4, 'Non Compliant', null, 'HAVING registration_status = "' . $nonCompliant . '"'),
            4=>array(5, 'Learner Created', null, 'HAVING registration_status = "' . $learnerCreated . '"'));
        $f = new VoltDropDownViewFilter('filter_status', $options, '');
        $f->setDescriptionFormat("Status: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Learner Signed Date (Newest first), Learner Name', null, 'ORDER BY learner_sign_date DESC, firstnames ASC'),
            1=>array(2, 'Learner Signed Date (Oldest first), Learner Name', null, 'ORDER BY learner_sign_date ASC, firstnames ASC'));
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
        // pr($view->getSQLStatement()->__toString());
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<br><div align="center" ><table id="tblEntries" class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';
            echo '<th>Signed At</th><th>Name</th><th>Email</th><th>Telephone</th><th>Mobile</th><th>Postcode</th><th>Status</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_bc_registration&id='.$row['id']);
                echo '<td>' . Date::toShort($row['learner_sign_date']) . '</td>';
                echo '<td>' . $row['firstnames'] . ' ' . $row['surname'] . '</td>';
                echo '<td>' . $row['home_email'] . '</td>';
                echo '<td>' . $row['home_telephone'] . '</td>';
                echo '<td>' . $row['home_mobile'] . '</td>';
                echo '<td>' . $row['home_postcode'] . '</td>';
                echo '<td>';
                echo '<span class="label label-' . Registration::getStatusLabelColor($row['registration_status']) . '" style="font-size: small;">' . $row['registration_status'] . '</span>';
                if($row['registration_status'] == Registration::STATUS_COMPLIANCE_COMPLETE)
                {
                    echo '<br><br> &nbsp; &nbsp; &nbsp;<i class="fa  fa-long-arrow-right fa-lg"></i>';
                    
                    echo '<span class="label label-primary" style="font-size: small;">Create Learner</span>';
                }
                if($row['registration_status'] == Registration::STATUS_LEARNER_CREATED)
                {
                    echo '<br><br> &nbsp; &nbsp; &nbsp;<i class="fa  fa-long-arrow-right fa-lg"></i>';
                    
                    $isEnrolled = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr INNER JOIN users ON tr.username = users.username WHERE users.id = '{$row['entity_id']}'");
                    if(!$isEnrolled)
                    {
                        echo $row['entity_id']%2 === 0 ? 
                            '<span class="label label-primary" style="font-size: small;">Awaiting Enrolment</span>' : 
                            '<span class="label label-primary" style="font-size: small;">Awaiting Initial Assessment</span>';
                    }
                    else
                    {
                        echo '<span class="label label-success" style="font-size: small;">Learner Enrolled</span>';
                    }
                }
                echo '</td>';

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