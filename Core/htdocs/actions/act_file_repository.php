<?php
class file_repository implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
		$section = isset($_REQUEST['section']) ? basename(trim($_REQUEST['section'])) : '';
		if (preg_match('/[^A-Za-z0-9 \\-_]/', $section)) {
			throw new Exception("Invalid character in section title '".$section."'");
		}

		// Validate and normalise the section name
		if (strcasecmp($section, self::SECTION_SUPER_USER) === 0) {
			if ($_SESSION['user']->isAdmin()) {
				$section = self::SECTION_SUPER_USER; // Normalise to proper case
			} else {
				$section = ''; // Clear section name - only administrators can view the Perspective section
			}
		}

		switch($subaction)
		{
			case 'createsection':
				if($_SESSION['user']->type == 5)
				{
					$this->createSection($section, $_SESSION['user']->username);
				}
				else
				{
					$this->createSection($section);
				}
				return;
				break;
				
			case 'deletesection':
				if($_SESSION['user']->type == 5)
				{
					$this->deleteSection($section, $_SESSION['user']->username);
				}
				else
				{
					$this->deleteSection($section);
				}
				return;
				break;		
		
			default:
				break;
		}

		// Create the superuser section if it does not already exist
		$this->createSuperUserSection();
		
		// Process any files the user has uploaded
		if($_SESSION['user']->type == 5)
			$target_directory = ($section ? ($_SESSION['user']->username . '/general/'.$section) : '');
		else
			$target_directory = ($section ? ('section_'.$section) : '');
		$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'pptx', 'ppt', 'csv', 'txt', 'xml', 'zip', 'rar', '7z',
			'er', 'er1', 'er01', 'er2', 'er02', 'er3', 'er03', 'er4', 'er04', 'er5', 'er05', 'er6', 'er06',
			'er7', 'er07', 'er8', 'er08', 'er9', 'er09', 'er10', 'er11', 'er12', 'er13',
			'lr', 'lr1', 'lr01', 'lr2', 'lr02', 'lr3', 'lr03', 'lr4', 'lr04', 'lr5', 'lr05', 'lr6', 'lr06',
			'ods', 'odt', 'amr'
		);
		Repository::processFileUploads('uploadedFile', $target_directory, $valid_extensions);

		// Populate sections dropdown
		if($_SESSION['user']->type == 5)
			$section_options = $this->buildSectionDropDownOptions($_SESSION['user']->username);
		else
			$section_options = $this->buildSectionDropDownOptions();
		
		if(isset($_SESSION['usedSpace']) && isset($_SESSION['remaining_space']))
        {
            $usedSpace = $_SESSION['usedSpace'];
            $remaining_space = $_SESSION['remaining_space'];
        }
        else
        {
            $usedSpace = $_SESSION['usedSpace'] = $this->format_size(Repository::getUsedSpace());
            $max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));
            if(Repository::getRemainingSpace() > $max_file_upload){
                $max_file_upload = Repository::getRemainingSpace();
                Repository::getRemainingSpace();
            }
            $remaining_space = $_SESSION['remaining_space'] = $this->format_size($max_file_upload);
        }
        
        $pieChartFileUsage = $this->renderPieChart();
        $panelFileRepoUsage = isset($_SESSION['panelFileRepoUsage']) ? $_SESSION['panelFileRepoUsage'] : $_SESSION['panelFileRepoUsage'] = $this->repo_usage($link);

		require_once('tpl_file_repository.php');
	}
	
	
	private function createSection($section, $username = null)
	{
		if (!$section) {
			return;
		}
		$upload_root = Repository::getRoot();
		if(!is_null($username))
		{
			$path = $upload_root.'/'.trim($username).'/general/'.basename($section);
		}
		else
		{
			$path = $upload_root.'/section_'.basename($section);
		}

		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
	}
	
	private function deleteSection($section, $username = null)
	{
		if (!$section) {
			return;
		}
		
		$upload_root = Repository::getRoot();
		if(!is_null($username))
		{
			$path = $upload_root.'/'.trim($username).'/general/'.basename($section);
		}
		else
		{
			$path = $upload_root.'/section_'.basename($section);
		}
		if (!is_dir($path)) {
			return;
		}

		$files = Repository::readDirectory($path);
		if (count($files) > 0) {
			return;
		}
		
		rmdir($path);
	}

	
	private function renderSpaceRemaining()
	{
		$max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));
		if(Repository::getRemainingSpace() < $max_file_upload){
			$max_file_upload = Repository::getRemainingSpace();
		}
		echo Repository::formatFileSize(Repository::getUsedSpace())." used of "
			.Repository::formatFileSize(Repository::getTotalSpace())." (max file size "
			.Repository::formatFileSize($max_file_upload).")";
	}
	
	


	private function renderSectionFiles($section, $username = null)
	{
		$upload_root = Repository::getRoot();
		if($section)
		{
			if(!is_null($username))
			{
				$path = $upload_root.'/'.$section;
			}
			else
				$path = $upload_root.'/section_'.basename($section);
		}
		else
		{
			$path = $upload_root;
		}
//		throw new Exception($path);
		$files = Repository::readDirectory($path);

		echo <<<HEREDOC
<div class="Directory">
<table cellspacing="0" style="table-layout:fixed; width:570">
<col width="310"/><col width="70"/><col width="170"/>
<tr>
	<th>Filename</th><th>Size</th><th>Upload Date</th><th>&nbsp;</th>
</tr>
HEREDOC;

		/* @var $f RepositoryFile */
		foreach($files as $f)
		{
			if($f->isDir()){
				continue;
			}
			echo "<tr>\r\n";
			echo '<td align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$f->getRelativePath().'\');" title="Download file">'.htmlspecialchars((string)$f->getName()).'</td>';
			echo '<td align="right" style="font-family:monospace" width="70">'.Repository::formatFileSize($f->getSize()).'</td>';
			echo '<td align="right" style="font-family:monospace" width="170">'.date("d/m/Y H:i:s", $f->getModifiedTime()).'</td>';
			if(DB_NAME != "am_reed" && DB_NAME != "am_reed_demo")
			{
				if($_SESSION['user']->isAdmin() ||  ($_SESSION['user']->type == '8'))
				{
					echo '<td align="right" width="20"><img src="/images/trash_can.png" title="Delete file" onclick="deleteFile(\''.$f->getRelativePath().'\');" style="cursor:pointer"/></td>';
				}
				else
				{
					echo '<td align="right" width="20">&nbsp;</td>';
				}
			}
			else
			{
				if($_SESSION['user']->isAdmin())
				{
					echo '<td align="right" width="20"><img src="/images/trash_can.png" title="Delete file" onclick="deleteFile(\''.$f->getRelativePath().'\');" style="cursor:pointer"/></td>';
				}
				else
				{
					echo '<td align="right" width="20">&nbsp;</td>';
				}
			}
			echo "\r\n</tr>\r\n";
		}
		
		echo "</table>\r\n";
		echo "</div>\r\n";
	}

	
	private function isSectionEmpty($section)
	{
		$upload_root = Repository::getRoot();
		if($section)
		{
			$path = $upload_root.'/section_'.basename($section);
		}
		else
		{
			$path = $upload_root;
		}

		$files = Repository::readDirectory($path);
		return count($files) == 0;
	}
	

	private function buildSectionDropDownOptions($username = null)
	{
		$sections = array(array("", "(General)")); // default section
		if(!is_null($username))
		{
			$learner_dir = Repository::getRoot().'/'.trim($username).'/general';
			$files = Repository::readDirectory($learner_dir);
			foreach ($files as $f) {
				if ($f->isDir()) {
//					if ($f->getName() != ('section_' . self::SECTION_SUPER_USER) || $_SESSION['user']->isAdmin()) {
						$sections[] = array($f->getName()); // additional section
//					}
				}
			}
		}
		else
		{
			$root_files = Repository::readDirectory();
			foreach ($root_files as $f) {
				if ($f->isDir() && preg_match('/^section_/', $f->getName())) {
					if ($f->getName() != ('section_' . self::SECTION_SUPER_USER) || $_SESSION['user']->isAdmin()) {
						$sections[] = array(substr($f->getName(),8), substr($f->getName(),8)); // additional section
					}
				}
			}
		}
//		var_dump($sections);
		return $sections;
	}


	private function createSuperUserSection()
	{
		if ($_SESSION['user']->isAdmin()) {
			$this->createSection(self::SECTION_SUPER_USER);
		}
	}

	public function renderPieChart()
    {
        $used_space = $_SESSION['usedSpace'];
        $remaining_space = $_SESSION['remaining_space'];
        $total_space = $used_space + $remaining_space;
        $used_space = round(($used_space/$total_space)*100);
        $remaining_space = round(($remaining_space/$total_space)*100);

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'File Repository'];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y}%'
                ],
                'showInLegend' => false
            ]
        ];
        $options->series = [];
        $series = new stdClass();
        $series->name = 'File Repository';
        $series->colorByPoint = true;
        $series->data = [];
        $series->data[] = (object)[
            'name' => 'Used',
            'y' => $used_space,
            'key' => 'Used',
            'color' => '#FF0000',
        ];
        $series->data[] = (object)[
            'name' => 'Remaining',
            'y' => $remaining_space,
            'key' => 'Remaining',
            'color' => '#00FF00',
        ];
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    function format_size($size)
    {
        if ($size == 0) {
            return 0;
        }
        else {
            return round( ($size / 1024) / 1024, 1);
        }
    }

    public function fn($repository)
    {
        $files = Repository::readDirectory($repository);

        foreach($files AS $f)
        {
            if(($f->isDir()))
            {
                $this->fn($f->getAbsolutePath());
            }
            else
            {
                $data = new stdClass();
                $data->day = date('Y-m', $f->getModifiedTime());
                $data->name = $f->getName();
                $data->size = $f->getSize();
                $this->main[] = $data;
            }
        }
    }

    public function repo_usage(PDO $link)
    {
        $months = [
            '1' => 'January'
            ,'2' => 'February'
            ,'3' => 'March'
            ,'4' => 'April'
            ,'5' => 'May'
            ,'6' => 'June'
            ,'7' => 'July'
            ,'8' => 'August'
            ,'9' => 'September'
            ,'10' => 'October'
            ,'11' => 'November'
            ,'12' => 'December'
        ];

        $this->fn(Repository::getRoot());

        $main = [];

        foreach($this->main AS $data)
        {
            if(!isset($main[$data->day]))
            {
                $main[$data->day] = 0;
            }
            $main[$data->day] += $data->size;
        }


        ksort($main);

        $yearInfo = [];
        foreach($main AS $key => $value)
        {
            $year = substr($key, 0, 4);
            if(!isset($yearInfo[$year]))
            {
                $yearInfo[$year] = 0;
            }
            $yearInfo[$year] += $value;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'File Repository Usage'];

        $options->xAxis = (object)['type' => 'category'];
        $options->yAxis = (object)['title' => (object)['text' => 'File Space (Bytes)']];
        $options->legend = (object)['enabled' => false];
        $options->plotOptions = (object)['series' => (object)[
            'borderWidth' => 0,
            'dataLabels' => (object)[
                'enabled' => true
                , 'formatter' => 'function(){ var i = Math.floor( Math.log(this.y) / Math.log(1024) );return ( this.y / Math.pow(1024, i) ).toFixed(2) * 1 + \' \' + [\'B\', \'KB\', \'MB\', \'GB\', \'TB\'][i];}'
            ]
        ]
        ];
        $options->tooltip = (object)['formatter' => 'function(){ var i = Math.floor( Math.log(this.point.y) / Math.log(1024) );return ( this.point.y / Math.pow(1024, i) ).toFixed(2) * 1 + \' \' + [\'B\', \'KB\', \'MB\', \'GB\', \'TB\'][i];}'];

        $series = new stdClass();
        $series->name = 'File Space';
        $series->colorByPoint = true;
        $series->data = [];
        foreach($yearInfo AS $key => $value)
        {
            $obj = new stdClass();
            $obj->name = $key;
            $obj->y = $value;
            $obj->drilldown = $key;
            $series->data[] = $obj;
        }
        $options->series[] = $series;

        $drilldown_series = [];
        foreach($main AS $key => $value)
        {
            $key_parts = explode('-', $key);
            $y = $key_parts[0];
            $m = $key_parts[1];
            $obj = new stdClass();
            $obj->name = $y;
            $obj->id = $y;

        }
        foreach($yearInfo AS $key => $value)
        {
            $obj = new stdClass();
            $obj->name = $key;
            $obj->id = $key;
            $obj->data = [];
            for($i = 1; $i <= 12; $i++)
            {
                $ii = str_pad($i, 2, "0", STR_PAD_LEFT);
                if(isset($main[$key.'-'.$ii]))
                {
                    $m = $months[$i];// . $key;
                    $obj->data[] = [$m, $main[$key.'-'.$ii]];
                }
            }
            $drilldown_series[] = $obj;
        }
        $options->drilldown = (object)['series' => $drilldown_series];

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

		private $main = [];

	const SECTION_SUPER_USER = 'Superusers Only';
}