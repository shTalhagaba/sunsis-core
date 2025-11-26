<?php
class view_ace_batch implements IAction
{
	public function execute(PDO $link)
	{
		$export = isset($_REQUEST['export'])?$_REQUEST['export']:'';

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_ace_batch", "View ACE Batch");

		$view = ViewAceBatch::getInstance();
		$view->refresh($link, $_REQUEST);

		if($export=='export_zip')
		{
			$this->exportRecordsToExcelZip($link, $view);
		}
		elseif($export=='export_csv')
		{
			$this->exportRecordsToExcel($link, $view);
		}

		require_once('tpl_view_ace_batch.php');
	}

	private function exportRecordsToExcel(PDO $link, ViewAceBatch $view)
	{
		set_time_limit(0);
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=apprentices.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			$line = '';
			$line .= "Prefix,* Gender,* Forename,* Surname,Middle name,* Date Of Birth (DD/MM/YYYY),* Ethnic Group,* NI Number,Unique Number,* Apprentice Street,* Apprentice Postcode,* Apprentice Town,Apprentice Country (UK = 232),Apprentice Email,Apprentice Phone,* Apprentice Start Date (DD/MM/YYYY),* Employer Name,* Contact,* Employer Size,Contact Position,* Employer Street,* Employer Postcode,* Employer Town,Employer Email,* Employer Phone,* Employer Sector,PO Number,Awarding Body Number,* Apprentice Funding,Cost Centre,Notes";
			echo $line . "\r\n";
			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$tr_id = $row['tr_id'];
				$sof = '"' . "/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode" . '"';
				$res = DAO::getResultset($link, "SELECT extractvalue(ilr,$sof) FROM ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $tr_id  ORDER BY contract_year DESC, submission DESC LIMIT 1");
				$row['apprentice_funding'] = (isset($res[0][0]) AND ($res[0][0] != 'undefined'))? $res[0][0]: '&nbsp;';

				$line = ',';//prefix
				$line .= str_replace(',', ' ', $row['gender']) . ',';
				$line .= str_replace(',', ' ', $row['firstnames']) . ',';
				$line .= str_replace(',', ' ', $row['surname']) . ',';
				$line .= ',';//middle name
				$line .= str_replace(',', ' ', Date::toShort($row['dob'])) . ',';
				$line .= str_replace(',','; ', $row['ethnicity']) . ',';
				$line .= str_replace(',','; ', $row['ni']) . ',';
				$line .= str_replace(',','; ', $row['uln']) . ',';
				$line .= str_replace(',','; ', $row['home_address_line_1']) . ',';
				$line .= str_replace(',','; ', $row['home_postcode']) . ',';
				$line .= str_replace(',','; ', $row['home_address_line_3']) . ',';
				$line .= str_replace(',','; ', 232) . ', ';
				$line .= str_replace(',','; ', $row['home_email']) . ',';
				$line .= str_replace(',','; ', $row['home_telephone']) . ',';
				$line .= str_replace(',','; ', Date::toShort($row['start_date'])) . ',';
				$line .= str_replace(',','; ', $row['legal_name']) . ',';
				$line .= str_replace(',','; ', $row['contact_name']) . ',';
				$line .= str_replace(',','; ', $row['size']) . ',';
				$line .=  ', ';//contact position
				$line .= str_replace(',','; ', $row['address_line_1']) . ',';
				$line .= str_replace(',','; ', $row['postcode']) . ',';
				$line .= str_replace(',','; ', $row['address_line_3']) . ',';
				$line .= str_replace(',','; ', $row['contact_email']) . ',';
				$line .= str_replace(',','; ', $row['telephone']) . ',';
				$line .= str_replace(',','; ', $row['sector']) . ',';
				$line .=  ',';//po number
				$line .=  ',';//awarding body number
				$line .= str_replace(',','; ', $row['apprentice_funding']) . ',';
				$line .=  ',';//cost centre
				$line .=  '';//notes
				echo $line . "\r\n";
			}
		}

		exit;
	}

	private function exportRecordsToExcelZip(PDO $link, ViewAceBatch $view)
	{
		set_time_limit(0);

		$upload_root = Repository::getRoot();
		$client_db_name = DB_NAME;
		if (file_exists($upload_root . "/ace_downloads"))
		{
			$files = Repository::readDirectory($upload_root . "/ace_downloads");
			foreach($files as $f)
			{
				unlink($upload_root . $f->getRelativePath());
			}
			rmdir($upload_root . "/ace_downloads");
		}

		mkdir($upload_root . "/ace_downloads", 0777, true);

		$CSVFileName = $upload_root . "/ace_downloads/apprentices.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');

		// create object
		$zip = new ZipArchive();
		if ($zip->open($upload_root . "/ace_downloads/ACEBatchReport.zip", ZIPARCHIVE::CREATE) !== TRUE)
		{
			die ("Could not open archive");
		}


		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Prefix';
			$csv_fields[0][] = '* Gender';
			$csv_fields[0][] = '* Forename';
			$csv_fields[0][] = '* Surname';
			$csv_fields[0][] = 'Middle name';
			$csv_fields[0][] = '* Date Of Birth (DD/MM/YYYY)';
			$csv_fields[0][] = '* Ethnic Group';
			$csv_fields[0][] = '* NI Number';
			$csv_fields[0][] = 'Unique Number';
			$csv_fields[0][] = '* Apprentice Street';
			$csv_fields[0][] = '* Apprentice Postcode';
			$csv_fields[0][] = '* Apprentice Town';
			$csv_fields[0][] = '* Apprentice Country (UK = 232)';
			$csv_fields[0][] = 'Apprentice Email';
			$csv_fields[0][] = 'Apprentice Phone';
			$csv_fields[0][] = 'Apprentice Start Date (DD/MM/YYYY)';
			$csv_fields[0][] = 'Apprentice End Date (DD/MM/YYYY)';
			$csv_fields[0][] = '* Employer Name';
			$csv_fields[0][] = '* Contact';
			$csv_fields[0][] = '* Employer Size';
			$csv_fields[0][] = 'Contact Position';
			$csv_fields[0][] = '* Employer Street';
			$csv_fields[0][] = '* Employer Postcode';
			$csv_fields[0][] = '* Employer Town';
			$csv_fields[0][] = 'Employer Email';
			$csv_fields[0][] = '* Employer Phone';
			$csv_fields[0][] = '* Employer Sector';
			$csv_fields[0][] = 'PO Number';
			$csv_fields[0][] = 'Awarding Body Number';
			$csv_fields[0][] = '* Apprentice Funding';
			$csv_fields[0][] = 'Cost Centre';
			$csv_fields[0][] = 'Notes';
			$index = 0;


			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$tr_id = $row['tr_id'];
				$sof = '"' . "/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode" . '"';
				$res = DAO::getResultset($link, "SELECT extractvalue(ilr,$sof) FROM ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $tr_id  ORDER BY contract_year DESC, submission DESC LIMIT 1");
				$row['apprentice_funding'] = (isset($res[0][0]) AND ($res[0][0] != 'undefined'))? $res[0][0]: '&nbsp;';

				$index++;
				$csv_fields[$index][] = '';//prefix
				$csv_fields[$index][] = $row['gender'];
				$csv_fields[$index][] = $row['firstnames'];
				$csv_fields[$index][] = $row['surname'];
				$csv_fields[$index][] = '';//middle name
				$csv_fields[$index][] = Date::toShort($row['dob']);
				$csv_fields[$index][] = $row['ethnicity'];
				$csv_fields[$index][] = $row['ni'];
				$csv_fields[$index][] = $row['uln'];
				$csv_fields[$index][] = $row['home_address_line_1'];
				$csv_fields[$index][] = $row['home_postcode'];
				$csv_fields[$index][] = $row['home_address_line_3'];
				$csv_fields[$index][] = '232';
				$csv_fields[$index][] = $row['home_email'];
				$csv_fields[$index][] = $row['home_telephone'];
				$csv_fields[$index][] = Date::toShort($row['start_date']);
				$csv_fields[$index][] = Date::toShort($row['closure_date']);
				$csv_fields[$index][] = $row['legal_name'];
				$csv_fields[$index][] = $row['contact_name'];
				$csv_fields[$index][] = $row['size'];
				$csv_fields[$index][] = '';
				$csv_fields[$index][] = $row['address_line_1'];
				$csv_fields[$index][] = $row['postcode'];
				$csv_fields[$index][] = $row['address_line_3'];
				$csv_fields[$index][] = $row['contact_email'];
				$csv_fields[$index][] = $row['telephone'];
				$csv_fields[$index][] = $row['sector'];
				$csv_fields[$index][] = '';//po number
				$csv_fields[$index][] = '';//awarding body number
				$csv_fields[$index][] = $row['apprentice_funding'];
				$csv_fields[$index][] = '';//cost centre
				$csv_fields[$index][] = '';//notes

				$learner_dir = Repository::getRoot().'/'.trim($row['username']).'/ACE Documents';
				$files = Repository::readDirectory($learner_dir);
				foreach($files as $f)
				{
					if($f->isDir())
					{
						continue;
					}
					$zip->addFile($upload_root . $f->getRelativePath(), $f->getName()) or die ("ERROR: Could not add file:");
				}
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

/*		// create object
		$zip = new ZipArchive();
		$path = $upload_root.'/section_'.basename('ACE Documents');
		$files = Repository::readDirectory($path);

		if ($zip->open($upload_root . "/ace_downloads/ACEBatchReport.zip", ZIPARCHIVE::CREATE) !== TRUE)
		{
			die ("Could not open archive");
		}
		$zip->addFile($upload_root . "/ace_downloads/apprentices.csv", "apprentices.csv") or die ("ERROR: Could not add file:");

		foreach($files as $f)
		{
			if($f->isDir()){
				continue;
			}
			$zip->addFile($upload_root . $f->getRelativePath(), $f->getName()) or die ("ERROR: Could not add file:");
		}
		$zip->close();*/

		$zip->addFile($upload_root . "/ace_downloads/apprentices.csv", "apprentices.csv") or die ("ERROR: Could not add file:");

		$zip->close();

		http_redirect("do.php?_action=downloader&path=/" . $client_db_name . "/ace_downloads/&f=ACEBatchReport.zip");


		exit;
	}
}
?>