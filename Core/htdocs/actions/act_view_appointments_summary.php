<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;

class view_appointments_summary implements IAction
{
	public function execute(PDO $link)
	{
		$export = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_appointments_summary", "View Appointments Summary");

		$view = ViewAppointmentsSummary::getInstance($link);
		$view->refresh($link, $_REQUEST);

		if ($export == 'excel')
			$this->generateXLSXOutput($link, $view);

		require_once('tpl_view_appointments_summary.php');
	}

	private function generateXLSXOutput(PDO $link, ViewAppointmentsSummary $view)
	{
		$html = "";
		$columns = $view->getSelectedColumns($link);
		$st = $link->query($view->getSQL());
		if ($st) {
			$html .= '<table>';
			$html .= '<thead>';
			$html .= '<tr>';
			foreach ($columns as $column) {
				$html .= '<th>' . ucwords(str_replace("_", " ", str_replace("_and_", " & ", $column))) . '</th>';
			}
			$html .= '<th>Grand Total</th>';
			$html .= '</tr></thead>';
			$html .= '<tbody>';
			$event_total = 0;
			$booked_total = 0;
			$attended_total = 0;
			$attended_late_total = 0;
			$auth_absence_total = 0;
			$cancelled_total = 0;
			$failed_total = 0;
			$rescheduled_total = 0;
			$first_row = true;
			$event_period = "";
			while ($row = $st->fetch()) {

				$row_total = 0;
				if ($event_period != $row['event_reed_period'] && !$first_row) {
					$html .= '<tr><td></td><td></td><td>' . $booked_total . '</td><td>' . $attended_total . '</td><td>' . $attended_late_total . '</td><td>' . $auth_absence_total . '</td><td>' . $cancelled_total . '</td><td>' . $failed_total . '</td><td>' . $rescheduled_total . '</td><td>' . $event_total . '</td></tr>';
					$event_total = 0;
					$booked_total = 0;
					$attended_total = 0;
					$attended_late_total = 0;
					$auth_absence_total = 0;
					$cancelled_total = 0;
					$failed_total = 0;
					$rescheduled_total = 0;
				}
				$html .= '<tr>';
				foreach ($columns as $column) {
					$html .= '<td>' . ((isset($row[$column])) ? (($row[$column] == '') ? '&nbsp' : $row[$column]) : '&nbsp') . '</td>';
					if ($column != "event_reed_period" && $column != "event_name")
						$row_total += ((isset($row[$column])) ? (($row[$column] == '') ? 0 : $row[$column]) : 0);
				}

				$booked_total += $row['booked'];
				$attended_total += $row['attended'];
				$attended_late_total += $row['attended_late'];
				$auth_absence_total += $row['authorised_absence'];
				$cancelled_total += $row['cancelled'];
				$failed_total += $row['failed_to_attend'];
				$rescheduled_total += $row['rescheduled'];

				$html .= '<td>' . $row_total . "</td>";
				$html .= '</tr>';

				$event_total += $row_total;
				$event_period = $row['event_reed_period'];
				$first_row = false;
			}

			$html .= '<tr><td></td><td></td><td>' . $booked_total . '</td><td>' . $attended_total . '</td><td>' . $attended_late_total . '</td><td>' . $auth_absence_total . '</td><td>' . $cancelled_total . '</td><td>' . $failed_total . '</td><td>' . $rescheduled_total . '</td><td>' . $event_total . '</td></tr>';
			$html .= '</tbody></table>';
		}


		$html = preg_replace('/<\/?a[^>]*>/', '', $html);

		// Put the html into a temporary file
		$tmpfile = time() . '.html';
		file_put_contents($tmpfile, $html);

		// Read the contents of the file into PHPSpreadSheet Reader class
		// Create reader
		$reader = new Html();
		$content = $reader->load($tmpfile);
		$content->getActiveSheet()->setTitle('Appointments Summary');

		$content->getActiveSheet()->getStyle("A2:J2")->getFont()->setBold(true);
		foreach (range('A', $content->getActiveSheet()->getHighestDataColumn()) as $col) {
			$content->getActiveSheet()
				->getColumnDimension($col)
				->setAutoSize(true);
		}

		// Redirect output to a client�s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="AppointmentsSummaryReport.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		// Pass to writer and output as needed
		$objWriter = IOFactory::createWriter($content, 'Xls');

		$objWriter->save('php://output');

		// Delete temporary file
		unlink($tmpfile);

		exit;
	}
}
