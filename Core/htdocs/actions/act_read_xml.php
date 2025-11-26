<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;

class read_xml implements IAction
{

	function sortArrayByArray(array $array, array $orderArray)
	{
		$ordered = array();
		foreach ($orderArray as $key) {
			if (array_key_exists($key, $array)) {
				$ordered[$key] = $array[$key];
				unset($array[$key]);
			}
		}
		return $ordered + $array;
	}


	public function execute(PDO $link)
	{
		$student_frameworks = array();
		$s_frameworks = DAO::getResultset($link, "SELECT tr_id, framework_id, title FROM courses_tr INNER JOIN frameworks ON courses_tr.`framework_id` = frameworks.id GROUP BY tr_id;", DAO::FETCH_ASSOC);
		foreach ($s_frameworks as $f)
			$student_frameworks[$f['tr_id']] = $f['title'];

		ini_set('memory_limit', '2048M');
		$StatusList = array();
		$StatusList[0] = "";
		$StatusList[1] = " [Not Started]";
		$StatusList[2] = " [Behind]";
		$StatusList[3] = " [On Track]";
		$StatusList[4] = " [Completed]";

		$info_required = array();
		$info_required[] = "reference";
		$info_required[] = "title";
		$info_required[] = "owner_reference";
		$info_required[] = "mandatory";
		$info_required[] = "percentage";

		$text_html = "";
		$text_html .= "<table border='1' class='resultset'>";
		$text_html .= "<thead>";
		//		$text_html .= "<th>Firstname(s)</th><th>Surname</th><th>L03</th><th>Qualification Number</th><th>Framework Title</th>";
		$text_html .= "<th>Assessor</th><th>L03</th><th>Surname</th><th>Fornames</th><th>Framework Title</th><th>Qual Aim No.</th><th>Exempt</th>";
		foreach ($info_required as $column_header)
			$text_html .= "<th>" . $column_header . "</th>";
		$text_html .= "</thead>";
		$text_html .= "<tbody>";

		//		$student_qualifications = DAO::getResultset($link, "SELECT student_qualifications.id, tr_id, tr.l03, tr.firstnames, tr.surname, student_qualifications.evidences FROM student_qualifications INNER JOIN tr ON student_qualifications.tr_id = tr.id INNER JOIN qualifications ON REPLACE(student_qualifications.id, '/','') = REPLACE(qualifications.id, '/','') WHERE qualifications.`active` = 1  LIMIT 50;# WHERE tr_id = 4172;# AND student_qualifications.id = '100/5570/8';", DAO::FETCH_ASSOC);
		$sql = <<<SQL
SELECT DISTINCT
  student_qualifications.id,
  tr_id,
tr.assessor,
  tr.l03,
  tr.firstnames,
  tr.surname,
  IF(student_qualifications.`aptitude` = 1, 'Yes', 'No') AS exempt,
  student_qualifications.evidences
FROM
  student_qualifications
  INNER JOIN tr
    ON student_qualifications.tr_id = tr.id
  INNER JOIN qualifications
    ON REPLACE(
      student_qualifications.id,
      '/',
      ''
    ) = REPLACE(qualifications.id, '/', '')
WHERE qualifications.`active` = 1  #AND student_qualifications.`framework_id` = 132 AND tr.status_code = 1 # AND tr.l03 IN ('108001767245', '108001767278', '108001767400')# AND REPLACE(qualifications.id, '/', '') = '60002669'
 ;
SQL;

		$student_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach ($student_qualifications as $qualification) {
			$evidence = XML::loadSimpleXML($qualification['evidences']); //pre(count($evidence->xpath('//root/units/units')));

			if (count($evidence->xpath('//root/units/units')) == 0) {
				if (count($evidence->xpath('//root/units')) == 0) {
					foreach ($evidence as $individual_unit) {
						$text_html .= "<tr>";
						if ($qualification['assessor'] == '' || is_null($qualification['assessor']))
							$text_html .= "<td>" . DAO::getSingleValue($link, "SELECT CONCAT (firstnames, ' ', surname) FROM users WHERE id = " . $qualification['assessor']) . "</td>";
						else
							$text_html .= "<td></td>";
						$text_html .= "<td>" . $qualification['l03'] . "</td>";
						$text_html .= "<td>" . $qualification['surname'] . "</td>";
						$text_html .= "<td>" . $qualification['firstnames'] . "</td>";
						$text_html .= isset($student_frameworks[$qualification['tr_id']]) ? "<td>" . $student_frameworks[$qualification['tr_id']] . "</td>" : '<td></td>';
						$text_html .= "<td>" . $qualification['id'] . "</td>";
						$text_html .= "<td>" . $qualification['exempt'] . "</td>";


						$temp = array();
						$temp = (array) $individual_unit->attributes();
						$temp = $temp['@attributes'];

						$temp = $this->sortArrayByArray($temp, $info_required);
						//pre($temp);
						foreach ($temp as $key => $value) {
							if (in_array($key, $info_required))
								$text_html .= "<td>" . $value . "</td>";
						}
						$text_html .= "</tr>";
					}
				} elseif (count($evidence->xpath('//root/units')) > 0) {
					foreach ($evidence->children() as $main_unit_group) {
						foreach ($main_unit_group as $individual_unit) {
							$text_html .= "<tr>";
							$text_html .= "<td>" . $qualification['l03'] . "</td>";
							$text_html .= "<td>" . $qualification['surname'] . "</td>";
							$text_html .= "<td>" . $qualification['firstnames'] . "</td>";
							$text_html .= isset($student_frameworks[$qualification['tr_id']]) ? "<td>" . $student_frameworks[$qualification['tr_id']] . "</td>" : '<td></td>';
							$text_html .= "<td>" . $qualification['id'] . "</td>";
							$text_html .= "<td>" . $qualification['exempt'] . "</td>";

							$temp = array();
							$temp = (array) $individual_unit->attributes();
							$temp = $temp['@attributes'];

							$temp = $this->sortArrayByArray($temp, $info_required);
							//pre($temp);
							foreach ($temp as $key => $value) {
								if (in_array($key, $info_required))
									$text_html .= "<td>" . $value . "</td>";
							}
							$text_html .= "</tr>";
						}
					}
				}
			} elseif (count($evidence->xpath('//root/units/units')) > 0) {
				foreach ($evidence->children() as $main_unit_group) // foreach main unit group
				{
					if (count($main_unit_group->xpath('units')) == 0) {
						foreach ($main_unit_group as $individual_unit) {
							//pre($individual_unit);
							$text_html .= "<tr>";
							$text_html .= "<td>" . $qualification['l03'] . "</td>";
							$text_html .= "<td>" . $qualification['surname'] . "</td>";
							$text_html .= "<td>" . $qualification['firstnames'] . "</td>";
							$text_html .= isset($student_frameworks[$qualification['tr_id']]) ? "<td>" . $student_frameworks[$qualification['tr_id']] . "</td>" : '<td></td>';
							$text_html .= "<td>" . $qualification['id'] . "</td>";
							$text_html .= "<td>" . $qualification['exempt'] . "</td>";

							$temp = array();
							$temp = (array) $individual_unit->attributes();
							$temp = $temp['@attributes'];

							$temp = $this->sortArrayByArray($temp, $info_required);

							foreach ($temp as $key => $value) {
								if (in_array($key, $info_required))
									$text_html .= "<td>" . $value . "</td>";
							}
							$text_html .= "</tr>";
						}
					} elseif (count($main_unit_group->xpath('units')) > 0) {
						foreach ($main_unit_group as $sub_unit_group) {
							if (count($sub_unit_group->xpath('units')) == 0) {
								foreach ($sub_unit_group as $individual_unit) {
									$text_html .= "<tr>";
									$text_html .= "<td>" . $qualification['l03'] . "</td>";
									$text_html .= "<td>" . $qualification['surname'] . "</td>";
									$text_html .= "<td>" . $qualification['firstnames'] . "</td>";
									$text_html .= isset($student_frameworks[$qualification['tr_id']]) ? "<td>" . $student_frameworks[$qualification['tr_id']] . "</td>" : '<td></td>';
									$text_html .= "<td>" . $qualification['id'] . "</td>";
									$text_html .= "<td>" . $qualification['exempt'] . "</td>";

									$temp = array();
									$temp = (array) $individual_unit->attributes();
									$temp = $temp['@attributes'];

									$temp = $this->sortArrayByArray($temp, $info_required);

									foreach ($temp as $key => $value) { //if($value == '2 - Option 2')pre($individual_unit);
										if (in_array($key, $info_required))
											$text_html .= "<td>" . $value . "</td>";
									}
									$text_html .= "</tr>";
								}
							} elseif (count($sub_unit_group->xpath('units')) > 0) {
								foreach ($sub_unit_group as $sub_sub_unit_group) {
									foreach ($sub_sub_unit_group as $individual_unit) {
										$text_html .= "<tr>";
										$text_html .= "<td>" . $qualification['l03'] . "</td>";
										$text_html .= "<td>" . $qualification['surname'] . "</td>";
										$text_html .= "<td>" . $qualification['firstnames'] . "</td>";
										$text_html .= isset($student_frameworks[$qualification['tr_id']]) ? "<td>" . $student_frameworks[$qualification['tr_id']] . "</td>" : '<td></td>';
										$text_html .= "<td>" . $qualification['id'] . "</td>";
										$text_html .= "<td>" . $qualification['exempt'] . "</td>";

										$temp = array();
										$temp = (array) $individual_unit->attributes();
										$temp = $temp['@attributes'];

										$temp = $this->sortArrayByArray($temp, $info_required);

										foreach ($temp as $key => $value) { //if($value == '2 - Option 2')pre($individual_unit);
											if (in_array($key, $info_required))
												$text_html .= "<td>" . $value . "</td>";
										}
										$text_html .= "</tr>";
									}
								}
							}
						}
					}
				}
			}
		}



		$text_html .= "</tbody>";
		$text_html .= "</table>";

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == 'excel') {
			$this->exportToExcel($text_html);
			exit;
		}

		include('tpl_read_xml.php');
	}

	private function exportToExcel($data)
	{

		$data = preg_replace('/<\/?a[^>]*>/', '', $data);

		// Put the html into a temporary file
		$tmpfile = time() . '.html';
		file_put_contents($tmpfile, $data);

		// Create reader
		$reader = new Html();
		$content = $reader->load($tmpfile);
		$content->getActiveSheet()->setTitle('Sheet No 1');


		$content1 = $reader->load($tmpfile);
		$content1->getActiveSheet()->setTitle('Sheet No 2');
		// Copy worksheets from $content1 to $content
		foreach ($content1->getAllSheets() as $sheet) {
			$content->addExternalSheet($sheet);
		}

		// Redirect output to a client�s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="LearnersProgressReport.xls"');
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
		//$objWriter->save('excelfile.xlsx');
		$objWriter->save('php://output');

		// Delete temporary file
		unlink($tmpfile);
	}
}