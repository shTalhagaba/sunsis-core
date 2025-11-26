<?php
class ViewALSReports extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            $sql = new SQLStatement("
SELECT 
tr.id AS tr_id,
CONCAT(tr.`firstnames`, ' ', tr.`surname`) AS learner_name,
tr.`start_date`,
tr.`target_date`,
courses.`title` AS programme,
(SELECT CASE outcome WHEN 1 THEN 'Completed ALS Support Plan' WHEN 2 THEN 'ALS Plan Completed - No funding required' WHEN 3 THEN 'No ALS support required' END FROM als WHERE tr_id = tr.id ORDER BY outcome_date LIMIT 1) AS outcome,
(SELECT outcome_date FROM als WHERE tr_id = tr.id ORDER BY outcome_date LIMIT 1) AS outcome_date,
(SELECT reason FROM als WHERE tr_id = tr.id ORDER BY outcome_date LIMIT 1) AS reason,
(SELECT referral_date FROM als WHERE tr_id = tr.id ORDER BY referral_date LIMIT 1) AS referral_date,
(SELECT CASE referred_by WHEN 1 THEN 'Learner' WHEN 2 THEN 'Assessor' WHEN 3 THEN 'Employer' END FROM als WHERE tr_id = tr.id ORDER BY outcome_date LIMIT 1) AS referred_by,
date_discussed,
support_required
FROM tr
INNER JOIN courses_tr ON courses_tr.tr_id = tr.id
INNER JOIN courses ON courses.id = courses_tr.`course_id`
LEFT JOIN ob_tr ON ob_tr.`sunesis_tr_id` = tr.`id`
LEFT JOIN ob_learner_als ON ob_learner_als.tr_id = ob_tr.id;
			");

            $view = $_SESSION[$key] = new ViewALSReports();
            $view->setSQL($sql->__toString());

            $options = array(
                0=>array(1, 'LearnRefNumber (asc)', null, 'ORDER BY L03'),
                1=>array(2, 'LearnRefNumber(desc)', null, 'ORDER BY L03 DESC'),
                2=>array(3, 'Family name (asc)', null, 'ORDER BY surname'),
                3=>array(4, 'Given names (asc)', null, 'ORDER BY firstnames'));
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            // Add view filters
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
                2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
                3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
                4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
                5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
                6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new DropDownViewFilter('filter_record_status', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            // Add view filters
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. Support required', null, 'WHERE support_required=\'Y\''),
                2=>array(2, '2. Support not required', null, 'WHERE support_required=\'N\''));
            $f = new DropDownViewFilter('filter_support_required', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'ALS Report', null, 'where true')
            );
            $f = new DropDownViewFilter('filter_report', $options, 0, false);
            $f->setDescriptionFormat("Report: %s");
            $view->addFilter($f);
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            $report = $this->getFilterValue("filter_report");

            if($report==0)
            {
                echo <<<HEREDOC
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead class="bg-gray">
					<tr>
						<th class="bottomRow">#</th>
						<th class="bottomRow">Learn Name</th>
						<th class="bottomRow">Start Date</th>
						<th class="bottomRow">Planned End Date</th>
						<th class="bottomRow">Programme</th>
						<th class="bottomRow">Referral Status</th>
						<th class="bottomRow">Referral Date</th>
						<th class="bottomRow">Outcome</th>
						<th class="bottomRow">Date of Outcome</th>
						<th class="bottomRow">Reason</th>
					</tr>
					</thead>
HEREDOC;
            }
            else
            {
                echo <<<HEREDOC
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead class="bg-gray">
					<tr>
						<th class="bottomRow">#</th>
						<th class="bottomRow">LearnRedNumber</th>
						<th class="bottomRow">Given Names</th>
						<th class="bottomRow">Family Name</th>
						<th class="bottomRow">Date of Birth</th>
						<th class="bottomRow">NI Number</th>
						<th class="bottomRow">Sex</th>
						<th class="bottomRow">ULN</th>
						<th class="bottomRow">PostCode</th>
						<th class="bottomRow">LearnAimRef</th>
						<th class="bottomRow">Start Date</th>
						<th class="bottomRow">Planned End Date</th>
						<th class="bottomRow">Actual End Date</th>
					</tr>
					</thead>
HEREDOC;
            }

            echo '<tbody>';
            $index =0;
            $Learners = Array();
            while($row = $st->fetch())
            {
                if($report==0)
                {
                    //$uln = $row['ULN'];
                    //$ni = $row['NINumber'];
                    //$uln_learners = DAO::getSingleValue($link, "select count(distinct LearnRefNumber) from pdsat where ULN = '$uln'");
                    //$ni_learners = DAO::getSingleValue($link, "select count(distinct LearnRefNumber) from pdsat where NINumber = '$ni'");
                    //if(($uln_learners==1 and $ni_learners==1) or in_array($row['LearnRefNumber'],$Learners))
                    //    continue;
                }
                if($report==1)
                {
                    $aimseq = $row['AimSeqNumber'];
                    $l03 = $row['LearnRefNumber'];
                    $a09 = $row['LearnAimRef'];
                    $start_date = $row['LearnStartDate'];
                    $planned_date = $row['PlannedEndDate'];
                    $actual_date = $row['ActEndDate'];

                    $overlapped_learners = DAO::getSingleValue($link, "SELECT count(*) FROM pdsat WHERE AimSeqNumber!='$aimseq' AND LearnRefNumber = '$l03' AND LearnAimRef = '$a09' AND LearnStartDate BETWEEN '$start_date' AND '$planned_date'");
                    if($overlapped_learners==0)
                        continue;
                }
                if($report==2)
                {
                    $aimseq = $row['AimSeqNumber'];
                    $wr = $row['WithdrawReason'];
                    $l03 = $row['LearnRefNumber'];
                    $a09 = $row['LearnAimRef'];
                    $start_date = $row['LearnStartDate'];
                    $planned_date = $row['PlannedEndDate'];
                    $actual_date = $row['ActEndDate'];

                    if($wr!=40)
                        continue;
                    $transfer = DAO::getSingleValue($link, "SELECT count(*) FROM pdsat WHERE AimSeqNumber!='$aimseq' AND LearnRefNumber = '$l03' AND LearnStartDate > '$start_date'");
                    if($transfer>0)
                        continue;
                }
                if($report==3)
                {
                    if($row['LSFCode']!=1)
                        continue;
                }
                if($report==4)
                {
                    if($row['EEFCode']=="" or $row['LearnAimRef']!='ZPROG001')
                        continue;
                }

                $index++;
                echo '<tr>';
                echo '<td>' . HTML::cell($index) . '</td>';
                echo '<td>' . HTML::cell($row['learner_name']) . '</td>';
                echo '<td>' . HTML::cell($row['start_date']) . '</td>';
                echo '<td>' . HTML::cell($row['target_date']) . '</td>';
                echo '<td>' . HTML::cell($row['programme']) . '</td>';
                echo '<td>' . HTML::cell($row['support_required']) . '</td>';
                echo '<td>' . HTML::cell($row['date_discussed']) . '</td>';
                echo '<td>' . HTML::cell($row['outcome']) . '</td>';
                echo '<td>' . HTML::cell($row['outcome_date']) . '</td>';
                echo '<td>' . HTML::cell($row['reason']) . '</td>';

                if($report!=0)
                {
                    echo '<td>' . HTML::cell($row['LearnAimRef']) . '</td>';
                    echo '<td>' . HTML::cell($row['LearnStartDate']) . '</td>';
                    echo '<td>' . HTML::cell($row['PlannedEndDate']) . '</td>';
                    echo '<td>' . HTML::cell($row['ActEndDate']) . '</td>';
                }
                echo '</tr>';
            }

            echo '</tbody></table>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

}
?>