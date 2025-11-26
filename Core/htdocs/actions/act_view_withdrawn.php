<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class view_withdrawn implements IAction
{
    public function execute(PDO $link)
    {
        $export = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';

        $view = VoltView::getViewFromSession('WithdrawnRestartReport', 'WithdrawnRestartReport'); /* @var $view VoltView */
        if (is_null($view)) {
            $view = $_SESSION['WithdrawnRestartReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_withdrawn", 'Withdrawn Restart Report');

        if ($export == 'export') {
            $this->export_csv($link, $view);
            exit;
        }

        require_once("tpl_view_withdrawn.php");
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT 
    tr.id AS current_tr_id,
    trp.id AS previous_tr_id,   
	tr.l03,
	tr.uln,
	tr.`surname`,
	tr.`firstnames`,
	frameworks.`title` AS framework_title,
	(SELECT CONCAT(firstnames, ' ', surname)FROM users WHERE users.`id` = tr.`assessor`) AS assessor,
	(SELECT contracts.`title` FROM contracts WHERE contracts.`id` = tr.`contract_id`) AS current_contract,
	DATE_FORMAT(trp.`start_date`, '%d/%m/%Y') AS original_start_date,
	DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS current_start_date,
	DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS current_target_date,
	DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS current_end_date,
	tr.`status_code` AS current_completion_status,
	tr.`outcome` AS current_outcome,
	(SELECT contracts.`title` FROM contracts WHERE contracts.`id` = trp.`contract_id`) AS previous_contract,
	DATE_FORMAT(trp.`start_date`, '%d/%m/%Y') AS previous_start_date,
	DATE_FORMAT(trp.`target_date`, '%d/%m/%Y') AS previous_target_date,
	DATE_FORMAT(trp.`closure_date`, '%d/%m/%Y') AS previous_end_date,
	trp.`status_code` AS previous_completion_status,
	trp.`outcome` AS previous_outcome
FROM 
	tr 
	INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	INNER JOIN frameworks ON frameworks.id = student_frameworks.id
	LEFT JOIN tr AS trp ON trp.l03 = tr.l03 AND tr.start_date > trp.closure_date
	LEFT JOIN student_frameworks AS sfp ON sfp.tr_id = trp.id
	LEFT JOIN frameworks AS fp ON fp.id = sfp.id
WHERE 
	tr.status_code = 1
	AND trp.status_code = 3
	AND (
		(frameworks.StandardCode IS NOT NULL AND frameworks.StandardCode = fp.StandardCode) OR 
		(frameworks.framework_code IS NOT NULL AND frameworks.framework_code = fp.`framework_code`)
	)
;
		");

        $view = new VoltView('WithdrawnRestartReport', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_firstname', "WHERE tr.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("Firstnames: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
        $f->setDescriptionFormat("ULN: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
        $f->setDescriptionFormat("ULN: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
        $f->setDescriptionFormat("TR IDs: %s");
        $view->addFilter($f);

        $options = array(
            0 => array(0, 'No limit', null, null)
        );
        $f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 0, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {
        $st = $link->query($view->getSQLStatement()->__toString());
        if ($st) {
            echo '<div align="center"><table id="dataMatrix" class="resultset" border="0" cellspacing="0" cellpadding="2">';
            echo '<thead><tr><th class="topRow" colspan="6">Learner Information</th><th class="topRow"></th><th class="topRow" colspan="6">Withdrawn</th><th class="topRow"></th><th class="topRow" bgcolor="black"></th><th class="topRow" colspan="6">Restart</th></tr>';
            echo '<tr><th class="bottomRow">L03</th><th class="bottomRow">ULN</th><th class="bottomRow">Surname</th><th class="bottomRow">Forenames</th><th class="bottomRow">Framework Title</th><th class="bottomRow">Assessor</th>';
            echo '<th class="bottomRow" bgcolor="black"></th>';
            echo '<th class="bottomRow">Contract</th><th class="bottomRow">Start Date</th><th class="bottomRow">Planned End Date</th><th class="bottomRow">Actual End Date</th><th class="bottomRow">Completion Status</th><th class="bottomRow">Outcome</th>';
            echo '<th class="bottomRow" bgcolor="black"></th>';
            echo '<th class="bottomRow">Contract</th><th class="bottomRow">Original Start Date</th><th class="bottomRow">Start Date</th><th class="bottomRow">Planned End Date</th><th class="bottomRow">Actual End Date</th><th class="bottomRow">Completion Status</th><th class="bottomRow">Outcome</th></tr></thead>';

            echo '<tbody>';
            while ($row = $st->fetch()) {
                echo '<tr>';
                echo '<td>' . HTML::cell($row['l03']) . '</td>';
                echo '<td>' . HTML::cell($row['uln']) . '</td>';
                echo '<td>' . HTML::cell($row['surname']) . '</td>';
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td>' . HTML::cell($row['framework_title']) . '</td>';
                echo '<td>' . HTML::cell($row['assessor']) . '</td>';
                echo '<td bgcolor="black"></td>';
                echo '<td>' . HTML::cell($row['previous_contract']) . '</td>';
                echo '<td>' . HTML::cell($row['previous_start_date']) . '</td>';
                echo '<td>' . HTML::cell($row['previous_target_date']) . '</td>';
                echo '<td>' . HTML::cell($row['previous_end_date']) . '</td>';
                echo '<td>' . HTML::cell($row['previous_completion_status']) . '</td>';
                echo '<td>' . HTML::cell($row['previous_outcome']) . '</td>';
                echo '<td bgcolor="black"></td>';
                echo '<td>' . HTML::cell($row['current_contract']) . '</td>';
                echo '<td>' . HTML::cell($row['original_start_date']) . '</td>';
                echo '<td>' . HTML::cell($row['current_start_date']) . '</td>';
                echo '<td>' . HTML::cell($row['current_target_date']) . '</td>';
                echo '<td>' . HTML::cell($row['current_end_date']) . '</td>';
                echo '<td>' . HTML::cell($row['current_completion_status']) . '</td>';
                echo '<td>' . HTML::cell($row['current_outcome']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
        } else {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function export_csv(PDO $link, VoltView $view)
    {
        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        $view = View::getViewFromSession('WithdrawnRestartReport');

        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');

        $st = $link->query($statement);
        if ($st) {
            $objSpreadsheet = new Spreadsheet();
            // Set document properties
            $objSpreadsheet->getProperties()->setCreator($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
                ->setLastModifiedBy($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

            $objSpreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Learner Information')
                ->mergeCells("A1:F1")
                ->setCellValue('G1', 'Withdrawn')
                ->mergeCells("G1:L1")
                ->setCellValue('M1', '.')
                ->setCellValue('N1', 'Restart')
                ->mergeCells("N1:T1")
                ->setCellValue('A2', 'L03')
                ->setCellValue('B2', 'ULN')
                ->setCellValue('C2', 'Surname')
                ->setCellValue('D2', 'Forenames')
                ->setCellValue('E2', 'Framework Title')
                ->setCellValue('F2', 'Assessor')
                ->setCellValue('G2', 'Contract')
                ->setCellValue('H2', 'Start Date')
                ->setCellValue('I2', 'Planned End Date')
                ->setCellValue('J2', 'Actual End Date')
                ->setCellValue('K2', 'Completion Status')
                ->setCellValue('L2', 'Outcome')
                ->setCellValue('M2', '.')
                ->setCellValue('N2', 'Contract')
                ->setCellValue('O2', 'Original Start Date')
                ->setCellValue('P2', 'Start Date')
                ->setCellValue('Q2', 'Planned End Date')
                ->setCellValue('R2', 'Actual End Date')
                ->setCellValue('S2', 'Completion Status')
                ->setCellValue('T2', 'Outcome')

            ;

            $rowNumber = 3;
            while ($row = $st->fetch()) {
                $colTitle = 'A';
                $objSpreadsheet->setActiveSheetIndex(0)
                    ->setCellValue($colTitle . $rowNumber, $row['l03'])
                    ->setCellValue(++$colTitle . $rowNumber, $row['uln'])
                    ->setCellValue(++$colTitle . $rowNumber, $row['surname'])
                    ->setCellValue(++$colTitle . $rowNumber, $row['firstnames'])
                    ->setCellValue(++$colTitle . $rowNumber, $row['framework_title'])
                    ->setCellValue(++$colTitle . $rowNumber, $row['assessor'])
                    ->setCellValue(++$colTitle . $rowNumber, $row['previous_contract'])
                    ->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['previous_start_date']))
                    ->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['previous_target_date']))
                    ->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['previous_end_date']))
                    ->setCellValue(++$colTitle . $rowNumber, $row['previous_completion_status'])
                    ->setCellValue(++$colTitle . $rowNumber, $row['previous_outcome'])
                    ->setCellValue(++$colTitle . $rowNumber, '.')
                    ->setCellValue(++$colTitle . $rowNumber, $row['current_contract'])
                    ->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['original_start_date']))
                    ->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['current_start_date']))
                    ->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['current_target_date']))
                    ->setCellValue(++$colTitle . $rowNumber, Date::toShort($row['current_end_date']))
                    ->setCellValue(++$colTitle . $rowNumber, $row['current_completion_status'])
                    ->setCellValue(++$colTitle . $rowNumber, $row['current_outcome'])
                ;
                $rowNumber++;
            }

            $objSpreadsheet->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $objSpreadsheet->getActiveSheet()->getStyle('G1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $objSpreadsheet->getActiveSheet()->getStyle('N1:T1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $objSpreadsheet->getActiveSheet()->getStyle('M1:M' . $rowNumber)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => '000000')
                    )
                )
            );
            $objSpreadsheet->getActiveSheet()->setTitle('Withdrawn Restart Report');
            foreach (range('A', $objSpreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
                $objSpreadsheet->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
            }
            $objSpreadsheet->getActiveSheet()->getStyle("A1:W1")->getFont()->setBold(true);
            $objSpreadsheet->getActiveSheet()->getStyle("A2:W2")->getFont()->setBold(true);
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objSpreadsheet->setActiveSheetIndex(0);
            
            // Send headers
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="CRMActivities.xlsx"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');

            $objWriter = new Xlsx($objSpreadsheet);
            $objWriter->save('php://output');
        }

        exit;
    }
}