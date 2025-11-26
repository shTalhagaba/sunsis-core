<?php
class view_ob_report implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_ob_report", "Onboarding Report");

		$view = ViewOnboardingReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		if($subaction == 'export_csv')
		{
			$this->exportToCSV($link, $view);
		}

		include_once('tpl_view_ob_report.php');
	}

	private function exportToCSV(PDO $link, View $view)
	{
		ini_set('memory_limit','512M');

		$columns = '';

		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');

		$columns = '';
		$rows = array();
		$result = DAO::getResultset($link, $statement->__toString(), DAO::FETCH_ASSOC);

		foreach($result AS $rs)
			$rows[] = $rs;
		unset($result);

		$RUI = array('1' => 'About courses or learning opportunities', '2' => 'For surveys and research');
		$PMC = array('1' => 'By post', '2' => 'By phone');
		$LLDDCat = array(
			'4' => 'Visual impairment',
			'5' => 'Hearing impairment',
			'6' => 'Disability affecting mobility',
			'7' => 'Profound complex disabilities',
			'8' => 'Social and emotional difficulties',
			'9' => 'Mental health difficulty',
			'10' => 'Moderate learning difficulty',
			'11' => 'Severe learning difficulty',
			'12' => 'Dyslexia',
			'13' => 'Dyscalculia',
			'14' => 'Autism spectrum disorder',
			'15' => 'Asperger\'s syndrome',
			'16' => 'Temporary disability after illness (for example post-viral) or accident',
			'17' => 'Speech, Language and Communication Needs',
			'93' => 'Other physical disability',
			'94' => 'Other specific learning difficulty (e.g. Dyspraxia)',
			'95' => 'Other medical condition (for example epilepsy, asthma, diabetes)',
			'96' => 'Other learning difficulty',
			'97' => 'Other disability',
			'98' => 'Prefer not to say'
		);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename=OnboardingReport.csv');
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
		{
			header('Pragma: public');
			header('Cache-Control: max-age=0');
		}
		$line = '';
		foreach($rows AS $row)
		{
			$columns = array_keys($row);
			foreach($columns AS $column)
				$line .= ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
			break;
		}
		echo $line . "\r\n";

		foreach($rows AS $row)
		{
			$line = '';
			foreach($columns AS $column)
			{
				if($column == 'stage')
				{
					if($row[$column] == 'Added')
						$line .= ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
					elseif($row[$column] == 'Awaiting Learner')
						$line .= ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
					elseif($row[$column] == 'Learner Completed And Awaiting Employer')
						$line .= ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
					elseif($row[$column] == 'Fully Completed')
						$line .= ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
					else
						$line .= ',';
				}
				elseif($column == 'RUI')
				{
					foreach(explode(',', $row['RUI']) AS $v)
						$line .= isset($RUI[$v]) ? $this->csvSafe($RUI[$v]) . '; ' : '';
					$line .= ',';
				}
				elseif($column == 'PMC')
				{
					foreach(explode(',', $row['PMC']) AS $v)
						$line .= isset($PMC[$v]) ? $this->csvSafe($PMC[$v]) . '; ' : '';
					$line .= ',';
				}
				elseif($column == 'llddcat')
				{
					foreach(explode(',', $row['llddcat']) AS $v)
						$line .= isset($LLDDCat[$v]) ? $this->csvSafe($LLDDCat[$v]) . '; ' : '';
					$line .= ',';
				}
				elseif($column == 'primary_lldd')
				{
					$line .= isset($LLDDCat[$row[$column]])?$this->csvSafe($LLDDCat[$row[$column]]).',':',';
				}
				elseif($column == 'uploaded_files')
				{
					$dir = Repository::getRoot() . '/' . $row['username'] . '/Certificates';
					if(is_dir($dir))
					{
						$files = Repository::readDirectory($dir);
						foreach($files AS $f) /* @var $f RepositoryFile */
						{
							$line .= $this->csvSafe($f->getName()) . '; ';
						}
						$line .= ',';
					}
					else
						$line .= ',';
				}
				else
					$line .= ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
			}

			echo $line . "\r\n";
		}


		exit;
	}

	private function csvSafe($value)
	{
		$value = str_replace(',', ';', $value);
		$value = str_replace(array("\n", "\r"), '', $value);
		$value = str_replace("\t", '', $value);
		$value = '"' . str_replace('"', '""', $value) . '"';
		return $value;
	}
}