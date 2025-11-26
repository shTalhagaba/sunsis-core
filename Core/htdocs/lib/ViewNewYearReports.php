<?php
class ViewNewYearReports extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            /*$sql = new SQLStatement("
            SELECT tr.l03 as LearnRefNumber,firstnames as GivenNames, surname as FamilyName, ilr.contract_id, submission, ilr.L03, tr_id, contracts.contract_year as contract_year
            FROM ilr 
            INNER JOIN tr ON tr.id = ilr.tr_id
            INNER JOIN contracts on contracts.id = ilr.contract_id
            WHERE 
            contracts.contract_year in (SELECT contract_year FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date)
            and ilr.submission in (SELECT submission FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date)
            ");*/

            $sql = new SQLStatement("
            SELECT tr.l03 as LearnRefNumber,firstnames as GivenNames, surname as FamilyName, ilr.contract_id, submission, ilr.L03, tr_id, contracts.contract_year as contract_year
            FROM ilr 
            INNER JOIN tr ON tr.id = ilr.tr_id
            INNER JOIN contracts on contracts.id = ilr.contract_id
            WHERE 
            contracts.contract_year in (2025)
            and ilr.submission in ('W13')
            ");

            $view = $_SESSION[$key] = new ViewNewYearReports();
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
                0=>array(0, 'Where all aims are coded as withdrawn but the ZPROG is not withdrawn', null, 'where EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType=1]/CompStatus")=1
                AND FIND_IN_SET("1",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType!=1]/CompStatus")," ",","))=0
                '),
                1=>array(1, 'Where the ZPROG is coded as withdrawn but one or more aims are continuing', null, 'WHERE EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType=1]/CompStatus")=3
                AND FIND_IN_SET("1",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType!=1]/CompStatus")," ",","))<>0'),
                2=>array(2, 'Where the Training Record is set to withdrawn but the ILR is continuing.', null, 'WHERE (EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType=1]/CompStatus")=1
                OR FIND_IN_SET("1",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType!=1]/CompStatus")," ",","))<>0)
                AND tr.status_code = 3;
                '),
                3=>array(3, 'Where Training Record RS is set to continuing but the ILR has not continuing aims so it would not be migrated.', null, 'WHERE FIND_IN_SET("1",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery/CompStatus")," ",","))=0
                AND tr.status_code = 1;'),
                4=>array(4, 'Where the Training RS is set to completed/withdrawn / BIL but the ILR still has continuing aims.', null, 'WHERE  FIND_IN_SET("1",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery/CompStatus")," ",","))<>0
                AND tr.status_code <> 1'),
                5=>array(5, 'Where all aims are set to Completed or Withdrawn but the ZPROG is set to Continuing.', null, 'WHERE  EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType=1]/CompStatus")=1
                AND FIND_IN_SET("1",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType!=1]/CompStatus")," ",","))=0;
                '),
                6=>array(6, 'Where ZPROG is set to BIL but some aims are still continuing.', null, 'WHERE  EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType=1]/CompStatus")=6
                AND FIND_IN_SET("1",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType!=1]/CompStatus")," ",","))<>0;
                '),
                7=>array(7, 'Where all aims are set to BIL but ZPROG is still continuing', null, 'WHERE  EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType=1]/CompStatus")=1
                AND FIND_IN_SET("6",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType!=1]/CompStatus")," ",","))<>0;
                '),
                8=>array(8, 'Where all aims are set to BIL but ZPROG is still completed or withdrawn.', null, 'WHERE  (EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType=1]/CompStatus")=2 OR EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType=1]/CompStatus")=3)
                AND FIND_IN_SET("6",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery[AimType!=1]/CompStatus")," ",","))<>0;
                '),
                9=>array(9, 'Where Training Record RS is set to Temporarily Withdrawn but ZPROG and aims are not.', null, 'WHERE  (FIND_IN_SET("1",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery/CompStatus")," ",","))<>0
                OR FIND_IN_SET("2",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery/CompStatus")," ",","))<>0
                OR FIND_IN_SET("3",REPLACE(EXTRACTVALUE(ilr, "/Learner/LearningDelivery/CompStatus")," ",","))<>0)
            AND tr.status_code = 6;
            ')

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
                echo <<<HEREDOC
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead class="bg-gray">
					<tr>
						<th class="bottomRow">#</th>
						<th class="bottomRow">LearnRefNumber</th>
						<th class="bottomRow">Given Names</th>
						<th class="bottomRow">Family Name</th>
					</tr>
					</thead>
HEREDOC;

            echo '<tbody>';
            $index =0;
            while($row = $st->fetch())
            {

                $index++;
                echo '<tr>';
                echo HTML::viewrow_opening_tag('do.php?_action=edit_ilr'.$row['contract_year'].'&submission='.rawurlencode($row['submission']).'&contract_id='.$row['contract_id'].'&L03='.$row['L03'].'&tr_id='.$row['tr_id']);
                echo '<td>' . HTML::cell($index) . '</td>';
                echo '<td>' . HTML::cell($row['LearnRefNumber']) . '</td>';
                echo '<td>' . HTML::cell($row['GivenNames']) . '</td>';
                echo '<td>' . HTML::cell($row['FamilyName']) . '</td>';
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