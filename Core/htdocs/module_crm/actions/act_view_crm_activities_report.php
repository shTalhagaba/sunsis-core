<?php

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class view_crm_activities_report implements IAction
{
    public function execute(PDO $link)
    {
        $subview = isset($_REQUEST['subview']) ? $_REQUEST['subview'] : 'All';
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view_name = 'ViewActivities' . $subview;

        $view = View::getViewFromSession($view_name, $view_name);
        if (is_null($view)) {
            $view = $_SESSION[$view_name] = ViewCrmActivities::getInstance($link, $view_name);
        }
        $view->refresh($link, $_REQUEST);

        if ($subaction == 'export') {
            $this->export($link, $view);
            exit;
        }

        //$_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_crm_activities_report", "View CRM Activities Report");

        include_once('tpl_view_crm_activities_report.php');
    }

    private function renderView(PDO $link, View $view)
    {
        //pre($view->getSQLStatement()->__toString());
        $meeting_types = ['1' => 'Face-to-face Meeting', '2' => 'Online', '3' => 'Other', '' => ''];
        $st = $link->query($view->getSQLStatement()->__toString());
        if ($st) {
            echo $view->getViewNavigatorExtra();
            echo '<table id="tblActivities" class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';
            echo '<th>Activity&nbsp;Type</th><th>Linked&nbsp;Record</th><th>Title</th><th>Company</th><th>Created&nbsp;By</th><th>Created&nbsp;On</th><th>Subject/Title</th><th>Due&nbsp;Date</th><th>Details</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . ucfirst($row['activity_type']) . '</td>';
                echo '<td>' . ucfirst($row['entity_type']) . '</td>';
                switch ($row['entity_type']) {
                    case 'enquiry':
                        echo '<td><a href="do.php?_action=read_enquiry&id=' . $row['entity_id'] . '">' . $row['enquiry_title'] . '</a></td>';
                        break;
                    case 'lead':
                        echo '<td><a href="do.php?_action=read_lead&id=' . $row['entity_id'] . '">' . $row['lead_title'] . '</a></td>';
                        break;
                    case 'opportunity':
                        echo '<td><a href="do.php?_action=read_opportunity&id=' . $row['entity_id'] . '">' . $row['opportunity_title'] . '</a></td>';
                        break;
                    default:
                        echo '<td></td>';
                        break;
                }
                switch ($row['entity_type']) {
                    case 'enquiry':
                        echo '<td>' . $row['enquiry_company'] . '</td>';
                        break;
                    case 'lead':
                        echo '<td>' . $row['lead_company'] . '</td>';
                        break;
                    case 'opportunity':
                        echo '<td>' . $row['opportunity_company'] . '</td>';
                        break;
                    default:
                        echo '<td></td>';
                        break;
                }
                echo '<td>' . $row['creator'] . '</td>';
                echo '<td>' . Date::to($row['created_at'], Date::DATETIME) . '</td>';
                echo '<td>' . $row['subject'] . '</td>';
                echo '<td>' . Date::toShort($row['due_date']) . '</td>';
                $detail = XML::loadSimpleXML($row['detail']);
                echo '<td class="small">';
                switch ($row['activity_type']) {
                    case 'email':
                        echo '<span class="text-bold">To: </span>' . htmlspecialchars((string)$detail->To) . '<br>';
                        echo '<span class="text-bold">Subject: </span>' . htmlspecialchars((string)$detail->Subject) . '<br>';
                        echo '<span class="text-bold">Message: </span>' . nl2br(htmlspecialchars((string)$detail->Message)) . '<br>';
                        break;
                    case 'meeting':
                        echo !empty((string)$detail->Type)
                            ? '<span class="text-bold">Type: </span>' . $meeting_types[(string)$detail->Type] . '<br>'
                            : '';
                        echo '<span class="text-bold">Location: </span>' . htmlspecialchars((string)$detail->Location) . '<br>';
                        echo '<span class="text-bold">Time: </span>' . htmlspecialchars((string)$detail->Time) . '<br>';
                        echo '<span class="text-bold">Duration: </span>' . htmlspecialchars((string)$detail->Duration) . '<br>';
                        echo nl2br(htmlspecialchars((string)$detail->Comments));
                        break;
                    case 'phone':
                        //echo '<span class="text-bold">Person Contacted: </span>' . $detail->PersonContacted->__toString() . '<br>';
                        echo nl2br(htmlspecialchars((string)$detail->Comments));
                        break;
                    case 'task':
                        echo !empty((string)$detail->Priority)
                            ? '<span class="text-bold">Priority: </span>' . Lead::getListLeadTaskPriority((string)$detail->Priority) . '<br>'
                            : '';
                        echo nl2br(htmlspecialchars((string)$detail->Comments));
                        break;
                }
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody></table><p><br></p>';
            echo $view->getViewNavigatorExtra();
        } else {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function exportSection(PDO $link, $row_content, $section, Worksheet &$sheet, &$cell_row, &$cell_col)
    {
        $meeting_types = ['1' => 'Meeting', '2' => 'Training', '3' => 'Other', '' => ''];

        $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, ucfirst($row_content['entity_type']));
        $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, $row_content['creator']);
        $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, Date::to($row_content['created_at'], Date::DATETIME));
        $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, $row_content['subject']);
        $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, Date::toShort($row_content['date']));
        $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, Date::toShort($row_content['due_date']));
        $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, Date::toShort($row_content['next_action_date']));

        $detail = XML::loadSimpleXML($row_content['detail']);

        switch ($section) {
            case 'email':
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->To);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->Subject);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, strip_tags((string)$detail->Message));
                break;
            case 'task':
                $statusValue = Lead::getListLeadTaskStatus((string)$detail->Status);
                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row,
                    is_array($statusValue) ? implode(', ', $statusValue) : (string)$statusValue
                );

                $priorityValue = Lead::getListLeadTaskPriority((string)$detail->Priority);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, is_array($priorityValue) ? implode(', ', $priorityValue) : (string)$priorityValue);

                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->PersonContacted);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->JobTitle);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, nl2br((string)$detail->Comments));
                break;
            case 'meeting':
                $meetingType = $meeting_types[(string)$detail->Type] ?? '';
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, $meetingType);

                $meetingStatus = Lead::getListLeadMeetingStatus((string)$detail->Status);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, is_array($meetingStatus) ? implode(', ', $meetingStatus) : (string)$meetingStatus);

                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->Location);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->Time);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->Duration);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->PersonContacted);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, nl2br((string)$detail->Comments));
                break;
            case 'phone':
                $status1 = Lead::getListLeadCallStatus1((string)$detail->Status1);
                $status2 = Lead::getListLeadCallStatus2((string)$detail->Status2);

                $status1 = is_array($status1) ? implode(', ', $status1) : (string)$status1;
                $status2 = is_array($status2) ? implode(', ', $status2) : (string)$status2;

                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, $status1 . ' - ' . $status2);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->PersonContacted);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, (string)$detail->JobTitle);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$cell_col) . $cell_row, nl2br((string)$detail->Comments));
                break;
        }
        $cell_row++;
        $cell_col = -1;
    }

    public function export(PDO $link, View $view)
    {


        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        $objSpreadsheet = new Spreadsheet();

        $objSpreadsheet->getProperties()->setCreator("Sunesis")
            ->setLastModifiedBy($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
            ->setTitle("CRM Activities")
            ->setSubject("CRM Activities")
            ->setDescription("CRM Activities")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("CRM Activities");

        $sheet = $objSpreadsheet->getActiveSheet();
        $sheet->setTitle('Tasks');
        $sheet
            ->setCellValue('A1', 'Linked Record')
            ->setCellValue('B1', 'Created By')
            ->setCellValue('C1', 'Created On')
            ->setCellValue('D1', 'Subject')
            ->setCellValue('E1', 'Date')
            ->setCellValue('F1', 'Due Date')
            ->setCellValue('G1', 'Next Action Date')
            ->setCellValue('H1', 'Status')
            ->setCellValue('I1', 'Priority')
            ->setCellValue('J1', 'Person Contacted')
            ->setCellValue('K1', 'Job Title')
            ->setCellValue('L1', 'Comments')
        ;
        $sheet->getStyle("A1:L1")->getFont()->setBold(true);

        $objSpreadsheet->createSheet();
        $sheet = $objSpreadsheet->setActiveSheetIndex(1);
        $sheet->setTitle('Meetings');
        $sheet
            ->setCellValue('A1', 'Linked Record')
            ->setCellValue('B1', 'Created By')
            ->setCellValue('C1', 'Created On')
            ->setCellValue('D1', 'Subject')
            ->setCellValue('E1', 'Date')
            ->setCellValue('F1', 'Due Date')
            ->setCellValue('G1', 'Next Action Date')
            ->setCellValue('H1', 'Type')
            ->setCellValue('I1', 'Status')
            ->setCellValue('J1', 'Location')
            ->setCellValue('K1', 'Time')
            ->setCellValue('L1', 'Duration')
            ->setCellValue('M1', 'Person Contacted')
            ->setCellValue('N1', 'Job Title')
            ->setCellValue('O1', 'Comments')
        ;
        $sheet->getStyle("A1:O1")->getFont()->setBold(true);

        $objSpreadsheet->createSheet();
        $sheet = $objSpreadsheet->setActiveSheetIndex(2);
        $sheet->setTitle('Phone Calls');
        $sheet
            ->setCellValue('A1', 'Linked Record')
            ->setCellValue('B1', 'Created By')
            ->setCellValue('C1', 'Created On')
            ->setCellValue('D1', 'Subject')
            ->setCellValue('E1', 'Date')
            ->setCellValue('F1', 'Due Date')
            ->setCellValue('G1', 'Next Action Date')
            ->setCellValue('H1', 'Status')
            ->setCellValue('I1', 'Person Contacted')
            ->setCellValue('J1', 'Job Title')
            ->setCellValue('K1', 'Comments')
        ;
        $sheet->getStyle("A1:K1")->getFont()->setBold(true);

        $objSpreadsheet->createSheet();
        $sheet = $objSpreadsheet->setActiveSheetIndex(3);
        $sheet->setTitle('Emails');
        $sheet
            ->setCellValue('A1', 'Linked Record')
            ->setCellValue('B1', 'Created By')
            ->setCellValue('C1', 'Created On')
            ->setCellValue('D1', 'Subject')
            ->setCellValue('E1', 'Date')
            ->setCellValue('F1', 'Due Date')
            ->setCellValue('G1', 'Next Action Date')
            ->setCellValue('H1', 'To')
            ->setCellValue('I1', 'Subject')
            ->setCellValue('J1', 'Comments')
        ;
        $sheet->getStyle("A1:J1")->getFont()->setBold(true);

        $sheet1 = $objSpreadsheet->setActiveSheetIndex(0);
        $sheet2 = $objSpreadsheet->setActiveSheetIndex(1);
        $sheet3 = $objSpreadsheet->setActiveSheetIndex(2);
        $sheet4 = $objSpreadsheet->setActiveSheetIndex(3);

        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if ($st) {
            $t_row = 2;
            $t_col = -1;
            $e_row = 2;
            $e_col = -1;
            $m_row = 2;
            $m_col = -1;
            $p_row = 2;
            $p_col = -1;

            while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
                if ($row['activity_type'] == 'email') {
                    $this->exportSection($link, $row, 'email', $sheet4, $e_row, $e_col);
                }
                if ($row['activity_type'] == 'task') {
                    $this->exportSection($link, $row, 'task', $sheet1, $t_row, $t_col);
                }
                if ($row['activity_type'] == 'meeting') {
                    $this->exportSection($link, $row, 'meeting', $sheet2, $m_row, $m_col);
                }
                if ($row['activity_type'] == 'phone') {
                    $this->exportSection($link, $row, 'phone', $sheet3, $p_row, $p_col);
                }
            }
        } else {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }

        $objSpreadsheet->setActiveSheetIndex(0);

        if (ob_get_length()) {
            ob_end_clean();
        }

        // Send headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CRMActivities.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $objWriter = new Xlsx($objSpreadsheet);
        $objWriter->save('php://output');
    }
}