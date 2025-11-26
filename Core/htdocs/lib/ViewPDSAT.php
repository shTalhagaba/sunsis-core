<?php
class ViewPDSAT extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            $sql = new SQLStatement("
SELECT
*
FROM
    PDSAT
			");

            $view = $_SESSION[$key] = new ViewPDSAT();
            $view->setSQL($sql->__toString());

            $options = array(
                0=>array(1, 'LearnRefNumber (asc)', null, 'ORDER BY LearnRefNumber'),
                1=>array(2, 'LearnRefNumber(desc)', null, 'ORDER BY LearnRefNumber DESC'),
                2=>array(3, 'Family name (asc)', null, 'ORDER BY FamilyName'),
                3=>array(4, 'Given names (asc)', null, 'ORDER BY GivenNames'));
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, '20B-002 Posible Duplicate Learners', null, 'where true'),
                1=>array(1, '20B-003 Possible duplicate or overlapping programmes', null, 'WHERE true'),
                2=>array(2, '20B-005 Transferring learners', null, 'WHERE true'),
                3=>array(3, '20A-106 Learning support funding', null, 'WHERE true'),
                4=>array(4, '20A-202 19+ apprentices with enhanced or extended funding', null, 'WHERE true')
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
						<th class="bottomRow">LearnRedNumber</th>
						<th class="bottomRow">Given Names</th>
						<th class="bottomRow">Family Name</th>
						<th class="bottomRow">Date of Birth</th>
						<th class="bottomRow">NI Number</th>
						<th class="bottomRow">Sex</th>
						<th class="bottomRow">ULN</th>
						<th class="bottomRow">PostCode</th>
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
                    $uln = $row['ULN'];
                    $ni = $row['NINumber'];
                    $uln_learners = DAO::getSingleValue($link, "select count(distinct LearnRefNumber) from pdsat where ULN = '$uln'");
                    $ni_learners = DAO::getSingleValue($link, "select count(distinct LearnRefNumber) from pdsat where NINumber = '$ni'");
                    if(($uln_learners==1 and $ni_learners==1) or in_array($row['LearnRefNumber'],$Learners))
                        continue;
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


                $Learners[] = $row['LearnRefNumber'];
                $index++;
                echo '<tr>';
                echo '<td>' . HTML::cell($index) . '</td>';
                echo '<td>' . HTML::cell($row['LearnRefNumber']) . '</td>';
                echo '<td>' . HTML::cell($row['GivenNames']) . '</td>';
                echo '<td>' . HTML::cell($row['FamilyName']) . '</td>';
                echo '<td>' . HTML::cell($row['DOB']) . '</td>';
                echo '<td>' . HTML::cell($row['NINumber']) . '</td>';
                echo '<td>' . HTML::cell($row['Sex']) . '</td>';
                echo '<td>' . HTML::cell($row['ULN']) . '</td>';
                echo '<td>' . HTML::cell($row['PostcodePrior']) . '</td>';
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