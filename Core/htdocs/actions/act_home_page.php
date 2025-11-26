<?php
class home_page implements IAction
{
	public function execute(PDO $link)
	{
		$days_password_changed = DAO::getSingleValue($link, "SELECT DATEDIFF(CURDATE(), users.`password_changed_at`) FROM users WHERE users.id = '{$_SESSION['user']->id}'");
		$days_remaining_for_password_change = SystemConfig::getEntityValue($link, "force_password_change_days") - $days_password_changed;

		 $_SESSION['current_submission_year'] = (!isset($_SESSION['current_submission_year']) || $_SESSION['current_submission_year'] == '' ) ?
			DAO::getSingleValue($link, "SELECT contract_year FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1;") :	$_SESSION['current_submission_year'] = 2025;

		if(in_array(DB_NAME, ["am_duplex"]))
        {
            http_redirect('do.php?_action=home_page_duplex');
        }

	if( DB_NAME == "am_ela" && in_array($_SESSION['user']->username, ["boibrahim"]) )
        {
            http_redirect('do.php?_action=crm_dashboard');
        }

		// re 26/08/2011
		// recruitment module redirect
		// only users of type salesman, who is not an administrator
		// re 31/01/2012 - changed the user type


		
		if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]) && !$_SESSION['user']->isAdmin())
		{
			http_redirect('do.php?_action=home_page_ns');
		}
		if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] == 'doNotShowTempNotification')
		{
			$_SESSION['doNotShowTempNotification'] = 1;
			exit;
		}
		if ( SystemConfig::getEntityValue($link, 'module_recruitment') && ( !$_SESSION['user']->is_admin && $_SESSION['user']->type == 7 ) )
		{
			//$_SESSION['bc']->add($link, "do.php?_action=vacancies_home", "Vacancies Home");
			//http_redirect('do.php?_action=vacancies_home');
			//exit;
		}
		if((SystemConfig::getEntityValue($link, "module_eportfolio") && $_SESSION['user']->type == User::TYPE_LEARNER ))
		{
			http_redirect('do.php?_action=learner_home_page');
		}
		if((SystemConfig::getEntityValue($link, "module_eportfolio") && $_SESSION['user']->type == User::TYPE_ASSESSOR))
		{
			http_redirect('do.php?_action=assessor_home_page');
		}
		if((SystemConfig::getEntityValue($link, "module_eportfolio") && $_SESSION['user']->type == User::TYPE_VERIFIER))
		{
			http_redirect('do.php?_action=assessor_home_page');
		}
		if($_SESSION['user']->type == User::TYPE_LEARNER)
		{
			http_redirect('do.php?_action=learner_home_page_all');
		}
		
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
		$repo = isset($_REQUEST['repo'])?$_REQUEST['repo']:'';

		if($repo==1)
		{
			//$filerepository = 'class="selected"';
			$welcome = '';
		}
		else
		{
			$welcome = 'class="selected"';
			//$filerepository = '';
		}

		$_SESSION['bc']->add($link, "do.php?_action=home_page", "Home");

		$ut = (int)$_SESSION['user']->type;

		$legal = '';
		$learner = '';
		$statustotal = '';
		$trainingrecords = '';
		$ilrstatus = '';


		$username=$_SESSION['user']->username;

		// Build Learner Status Pie Chart
		$learner_status = $this->getLearnerStatus($link);
		foreach($learner_status as $key=>$value)
		{
			//$statustotal .= "{ name:'".$key." (".$value.")',";
			$statustotal .= "{ name:'".$key."',";
			$statustotal .= "y:".$value;

			if ($key == "On Track")
			{
				$statustotal .= ",sliced:true,";
				$statustotal .= "selected:true";
			}

			$statustotal .= "},";
		}


		// Build ILR status Graph with HTML table
		$ilr_count=array('invalid' => 0, 'valid' => 0);
		//$sql="SELECT DISTINCT IF(tr.ilr_status=1,'valid','invalid') AS is_valid, COUNT(tr.ilr_status) AS training_record FROM tr LEFT JOIN ilr ON ilr.contract_id = tr.contract_id AND ilr.tr_id = tr.id AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id = tr.contract_id) WHERE tr.`ilr_status` IS NOT NULL AND tr.status_code = 1 GROUP BY is_valid;";
		$sql="SELECT DISTINCT IF(tr.ilr_status=1,'valid','invalid') AS is_valid_ilr, COUNT(tr.ilr_status) AS training_record FROM tr LEFT JOIN ilr ON ilr.contract_id = tr.contract_id AND ilr.tr_id = tr.id AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id = tr.contract_id) WHERE tr.`ilr_status` IS NOT NULL AND tr.status_code = 1 GROUP BY is_valid_ilr;";
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC, "homepage valid ilrs", 600);
		foreach($rows as $row)
		{
			$ilr_count[$row['is_valid_ilr']] = $row['training_record'];

			if ($row['training_record'] != 0)
			{

				$ilrstatus .= "'".addslashes($row['is_valid_ilr'])."',";
				//$trainingrecords .= $row['training_record'].",";

				$trainingrecords .= "{ name:'".$row['is_valid_ilr']."',";
				$trainingrecords .= "y:".$row['training_record']."},";
			}
		}

		$stat_graphs = "<graph>";
		$stat_graphs = <<<JS


	Highcharts.setOptions({
lang:
{
	exportButtonTitle:"Export chart as Image"
}
});

		/*******Build ILR bar chart******************/

chart = new Highcharts.Chart({
chart:
{
	renderTo: 'ilr',
	height: 300,
	width: 320,
	borderColor: '#727375',
	borderWidth: 0,
	backgroundColor: null,
	defaultSeriesType: 'column',
	inverted: false,
	Style:
	{
		fontFamily: 'arial',
 		fontSize:'11px'
	}
},
title:
{
	text: null,
	floating: false,
	align: 'center',
	style:
	{
		fontFamily: 'arial',
		fontSize: '12px',
		color: 'black',
		fontWeight: 'bold'
	}
},
credits:
{
	enabled: false
},
xAxis:
{
	labels:
	{
		enabled: true,
		style:
		{
			fontFamily: 'arial',
			fontSize: '12px',
			color: 'gray',
			fontWeight: 'bold'
		}
	},
	categories:[$ilrstatus]
},
yAxis:
{
	min: 0,
	title:
	{
		align: 'low',
		text: null,
       	style:
       	{
			fontFamily: 'arial',
			fontSize: '12px',
			color: 'gray'
       	}
	}
},
legend:
{
	layout: 'vertical'
},
colors:
	['#437C17'],
tooltip:
{
	formatter: function() { return ''+ this.y +' Training Records';}
},
plotOptions:
{
		size:200,
		pointPadding: 0.2,
		borderWidth: 0,
        dataLabels:
        {
        	enabled: true,
        	color: '#FFFFFF'
            //align: 'right',
            // x: -10
        }
},
series:
[{
	cursor: 'pointer',
	point:
	{
		events:
		{
			click: function()
			{
				//alert (this.name)
				switch (this.name)
				{
					//Invalid filter
					case 'invalid':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=2';
					break;

					//Valid filter
					case 'valid':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=1';
					break;

					default:
					break;
				}
			}
		}
	},
	name: 'Training Records',
	data: [$trainingrecords]
}],
exporting:
{
	enabled: true,
	width: 500,
	filename: 'ILR Status',
	buttons:
	{
		printButton:
		{
			enabled: false
		}
	}
},
navigation:
{
	buttonOptions:
 	{
		verticalAlign: 'top',
    	y: 0
	}
}

});

											/*******Build Learner Status Pie Chart******************/
var chart;
$(document).ready(function() {
chart = new Highcharts.Chart({
chart:
{
	renderTo: 'learnerStatus',
	height: 300,
	width: 320,
    borderColor: '#727375',
    borderWidth: 0,
    backgroundColor: null,
    defaultSeriesType: 'pie'
},
title:
{
	text: null,
	floating: false,
	align: 'center',
	style:
	{
		fontFamily: 'arial',
		fontSize: '12px',
		color: 'black',
		fontWeight: 'bold'
	}
},
legend:
{
	layout: 'vertical'
},
credits:
{
	enabled: false
},
colors:
[
	'#151B8D',
	'#437C17',
	'#eeeeee'
],
tooltip:
{
	formatter: function()
	{
		//return + this.y+ ' Learners';

		return + this.y+ ' Learners (' + Math.round(this.percentage*10)/10 +' %)';
	}
},
plotOptions:
{
	pie:
	{
		size:200
	}
},
series:
[{
	cursor: 'pointer',
	showInLegend: true,
	dataLabels:
	{
		enabled: true,
        distance: -50,
		color: 'white',
		fontWeight: 'bold',
		formatter: function()
		{
			return this.y;
		},
        borderRadius: 5,
        //padding: 3,
        //shadow: true,
        //backgroundColor: 'rgba(252, 255, 197, 0.7)',
        borderWidth: 1,
        borderColor: 'red'
	},
	point:
	{
		events:
		{
			click: function()
			{
				//alert (this.name)
				switch (this.name)
				{
					//Behind filter
					case 'Behind':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=2';
					break;

					//On track filter
					case 'On Track':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=1';
					break;

					default:
					break;
				}
			}
		}
	},
	type: 'pie',
	name: 'Status Total',
	data:  [$statustotal]
}],
exporting:
{
	enabled: true,
	width: 300,
	filename: 'Learner Status',
	buttons:
	{
		printButton:
		{
			enabled: false
		},
		exportButton:
		{
			menuItems:
			[
				{text: 'Download chart as PNG'},
                {text: 'Download chart as JPG'},
                {text: 'Download chart as PDF'},
                {text: 'Download chart as SVG'}
			]
		}
	}
},
navigation:
{
	buttonOptions:
	{
		verticalAlign: 'top',
		y: 0
	},
	menuItemStyle:
    {
 		width:150,
		borderLeft: '20px solid #E0E0E0'
	}
}

});

/*******Build Learners per Organisation Bar Chart

chart = new Highcharts.Chart({
chart:
{
	renderTo: 'learnerPerOrganisation',
	height: 300,
	width: 320,
	borderColor: '#727375',
	borderWidth: 0,
	backgroundColor: null,
	defaultSeriesType: 'bar',
	inverted: true,
	Style:
	{
		fontFamily: 'arial',
 		fontSize:'11px'
	}
},
title:
{
	text: null,
	floating: false,
	align: 'center',
	style:
	{
		fontFamily: 'arial',
		fontSize: '12px',
		color: 'black',
		fontWeight: 'bold'
	}
},
credits:
{
	enabled: false
},
xAxis:
{
	labels:
	{
		enabled: true,
		style:
		{
			fontFamily: 'arial',
			fontSize: '10px',
			color: 'gray'
		}
	}
},
categories:
	[$legal],
title:
{
	enabled: false
},
yAxis:
{
	min: 0,
	title:
	{
		align: 'low',
		text: null,
       	style:
       	{
			fontFamily: 'arial',
			fontSize: '12px',
			color: 'gray'
       	}
	}
},
legend:
{
	enabled: false
},
colors:
	['#437C17'],
tooltip:
{
	formatter: function() { return ''+ this.y +' Learners';}
},
plotOptions:
{
	column:
	{
		pointPadding: 0.2,
		borderWidth: 0
	}
},
series:
[{
	name: 'Learners',
	data: [$learner]
}]
exporting:
{
	enabled: true,
	width: 500,
	filename: 'Learners-Per-Organisation',
	buttons:
	{
		printButton:
		{
			enabled: false
		}
	}
},
navigation:
{
	buttonOptions:
 	{
		verticalAlign: 'top',
    	y: 0
	}
}

});
******************/

/*******Build ILR status Graph with HTML table
Highcharts.visualize = function(table, options)
{
	// the categories
	options.xAxis.categories = [];
 	$('tbody th', table).each( function(i)
 	{
		options.xAxis.categories.push(this.innerHTML);
 	});

	// the data series
	options.series = [];
 	$('tr', table).each( function(i)
	{
		var tr = this;
		$('th, td', tr).each( function(j)
		{
			if (j > 0)
			{ // skip first column
				if (i == 0)
				{ // get the name and init the series
               		options.series[j - 1] =
					{
						name: null,
						data: [],

						point:
						{
                			events:
                			{
								click: function()
								{
									switch(this.name)
									{
									case 'ilr.is_valid':
									//Not Valid ILR
                        			window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=2';
									break;

									case 'ilr.is_valid':
                        			//Valid ILR
                        			window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=1';
									break;
									}
                    			}
							}
						}
					}
            	} else
            	{ // add values
               		options.series[j - 1].data.push(parseFloat(this.innerHTML));
            	}
			}
		});
	});

   var chart = new Highcharts.Chart(options);
}

var table = document.getElementById('ILRdatatable'),
options = {
chart:
{
	renderTo: 'ILR',
	defaultSeriesType: 'column',
    height: 300,
    width: 320,
    backgroundColor: null,
    borderColor: '#727375',
    borderWidth: 0
},
title:
{
    text: null,
    floating: false,
    align: 'center',
    style:
    {
		fontFamily: 'arial',
      	fontSize: '12px',
      	color: 'black',
      	fontWeight: 'bold'
    }
},
colors:
	['#437C17'],
credits:
{
	enabled: false
},
legend:
{
	enabled: false
},
xAxis:
{
	labels:
    {
    style:
    {
    	fontFamily: 'arial',
       	fontSize: '12px',
       	color: 'gray'
	}
	}
},
yAxis:
{
	style:
	{
		fontFamily: 'arial',
		fontSize: '11px',
		color: 'gray'
    },
	title:
	{
		align: 'center',
		text: "No of ILRs",
		style:
	{
		fontFamily: 'arial',
		fontSize: '11px',
		color: 'black'
	}
    }
},
tooltip:
{
     formatter: function()
     {
		return +this.y +' '+ 'ILRs';
     }
},
exporting:
{
    enabled: false,
    width: 500,
    filename: 'ILR-Validation',
    buttons:
    {
		printButton:
    	{
			enabled: false
    	}
	}
},
navigation:
{
 	buttonOptions:
 	{
		verticalAlign: 'top',
		y: 0
    }
}
};
******************/
//Highcharts.visualize(table, options);
});


JS;
		
		/**
		 *
		 * Build the list of How To guides in the howto directory
		 */
		$help_guide_html = '';
		if( ( DB_NAME != 'am_edexcel' ) && ( file_exists(DATA_ROOT."/uploads/am_demo/howto") ) ) {
			$urls = Array();
			$TrackDir=opendir(DATA_ROOT."/uploads/am_demo/howto");
			// relmes - ensure evaluation of directory names
			// cannot stop the loop
			// - http://php.net/manual/en/function.readdir.php
			while ( false !== ( $file = readdir($TrackDir) ) ) {
				if ($file != "." && $file != ".." && !is_dir(DATA_ROOT."/uploads/am_demo/howto/".$file) ) {
					$icon_class = "icon-pdf";
					if (preg_match('/^HTS_([A-Z]{3})_([A-Z]{3})_([A-Z]{3}) [\- ]{0,1}(.*)\.pdf$/', $file, $cat_filedetails) ) {
						$filename = trim($cat_filedetails[4]);
						if( isset($urls[$file]) ) {
							$urls[$file] .=  '<td><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&f=" . $file . '" class="'.$icon_class.'" title="HTS_'.$cat_filedetails[1].'_'.$cat_filedetails[2].'_'.$cat_filedetails[3].'"><br />'.$filename.'</a>';
						}
						else {
							$urls[$file] = '<td><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&f=" . $file . '" class="'.$icon_class.'" title="HTS_'.$cat_filedetails[1].'_'.$cat_filedetails[2].'_'.$cat_filedetails[3].'">'.$filename.'</a></td>';
						}
					}
					else {
						$filename = preg_replace('/Sunesis HTS/', '', $file);
						$filename = preg_replace('/\.pdf$/', '', $filename);
						if($file != "New Edexcel Qualification.pdf") {
							if( isset($urls[$file]) ) {
								$urls[$file] .=  '<td><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&f=" . $file . '" class="'.$icon_class.'" ><br />'.$filename.'</a></td>';
							}
							else {
								$urls[$file] = '<td><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&f=" . $file . '" class="'.$icon_class.'" >'.$filename.'</a></td>';
							}
						}
					}
				}
			}

			// Sort the results to display alphabetically
			sort($urls);

			// build the How To guide table
			$n2 = 0;
			$help_guide_html = "<table><tbody><tr>";
			foreach ( $urls as $howto_file => $howto_link ) {
				$help_guide_html .= $howto_link;
				$n2++;
				// allow for multiple columns
				$help_guide_html .= "</tr><tr>";
			}
			// remove empty tr if present ast end of html.
			$help_guide_html = preg_replace('/\<\/tr\>\<tr\>$/', '', $help_guide_html);
			$help_guide_html .= "</tr></tbody></table>";
			closedir($TrackDir);
		}



		//************* Fetch filters for SLA/KPI graphs ********************//
		if ( (SystemConfig::getEntityValue($link, 'module_kpi_sla_reports') || DB_NAME=="am_demo") && ( $_SESSION['user']->is_admin) && (1 != 1))
		{
			include_once("act_sla_kpi_reports.php");
			$obj_sla_kpi_reports = new sla_kpi_reports();

			$filter_dtls = array();
			$user_id = $_SESSION['user']->id;
			//for displaying all the SLA/KPI graphs for which user has saved filters
			//$filter_dtls = $obj_sla_kpi_reports->get_filter_details($link, $mode="from_user_id", $idarray=array($user_id));

			//for displaying only fixed number of SLA/KPI graphs order by latest saved for dashboard
			$filter_dtls = $obj_sla_kpi_reports->get_filter_details($link, $mode="from_user_id_with_limit_latest_saved", $idarray=array($user_id,'limit'=>2));

			if($filter_dtls[0] != 'false')
			{
				//pre($filter_dtls);
				//$filter_dtls = $filter_dtls[0];
				//$filter_string = $filter_dtls['filter_string'];
				//$filter_arr = json_decode($filter_string);
			}
			else
			{
				$filter_arr = array();
			}
			//echo 'filter_arr = <pre>';
			//print_r($filter_arr);exit;
		}



		$announcement_view = $this->buildView($link);

		if($_SESSION['user']->isAdmin())
			//$summary_html = $this->getSupportStats($link);
			$summary_html = 'Temporarily unavailable';
	
		if(SystemConfig::getEntityValue($link, 'module_recruitment_v2') && $_SESSION['user']->type == User::TYPE_STORE_MANAGER)
		{
			//$dataHTML = $this->generateCalendarData($link);
			//require_once('tpl_recruitment_v2_home_page_staff.php');
			http_redirect('do.php?_action=rec_view_vacancies');
		}
		else
		{
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER || (DB_NAME == "am_ela" && $_SESSION['user']->type == 9) || (DB_NAME == "am_demo"  && $_SESSION['user']->type != 5) || (DB_NAME == "am_presentation"  && $_SESSION['user']->type == 8))
			{
				$current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");
				$current_year = date('Y');
				$learner_progress_status = $this->getLearnerStatus($link, true);
				$learners_on_track = isset($learner_progress_status['On Track'])?$learner_progress_status['On Track']:0;
				$learners_behind = isset($learner_progress_status['Behind'])?$learner_progress_status['Behind']:0;
				$on_track_plus_behind = $learners_on_track + $learners_behind;
				$on_track_plus_behind == 0?1:$on_track_plus_behind;
				$percentage_on_track = $on_track_plus_behind == $learners_on_track?100:round(($learners_on_track/$on_track_plus_behind)*100);
				$percentage_behind = $on_track_plus_behind == $learners_behind?100:round(($learners_behind/$on_track_plus_behind)*100);
				$valid_ilrs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr INNER JOIN contracts ON ilr.contract_id = contracts.id WHERE contract_year = '{$current_year}' AND is_valid = 1 AND submission = 'W{$current_submission}';");
				$invalid_ilrs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr INNER JOIN contracts ON ilr.contract_id = contracts.id WHERE contract_year = '{$current_year}' AND is_valid = 0 AND submission = 'W{$current_submission}';");
				$total_ilrs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr INNER JOIN contracts ON ilr.contract_id = contracts.id WHERE contract_year = '{$current_year}' AND submission = 'W{$current_submission}';");
				//$l2l3_progressions = $this->getL2L3Progressions($link);
				//$l3l4_progressions = $this->getL3L4Progressions($link);
				//$ttoa_progressions = $this->getTtoAProgressions($link);
                //$study_progressions = $this->getStudyProgressions($link);
				
				$toastr_message = '';
				if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '_action=login'))
				{
					$toastr_message = 'Welcome back, <b>' . $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname . '</b>';
					$toastr_message .= '<br>Your last login: ' . DAO::getSingleValue($link, "SELECT DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') FROM logins WHERE username = '" . $_SESSION['user']->username . "' ORDER BY id DESC LIMIT 1,1");
				}

				$_where = "";
				if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
					$_where = " AND tr.`provider_id` IN ({$_SESSION['user']->employer_id})";

				$assessorsValuesContinuing = array();
				$assessorsValuesCompleted = array();
				$assessorsValuesEarlyLeavers = array();
				$sql = "SELECT DISTINCT tr.assessor FROM tr INNER JOIN contracts ON tr.`contract_id` = contracts.id WHERE tr.assessor IS NOT NULL AND tr.assessor != 0 AND contract_year = '2020' {$_where} GROUP BY tr.assessor ORDER BY tr.assessor";
				$assessors = DAO::getSingleColumn($link, $sql);
				$sql = "SELECT tr.assessor, SUM(IF(tr.`status_code`=1, 1, 0)) AS `continuing` FROM tr INNER JOIN contracts ON tr.`contract_id` = contracts.id WHERE tr.assessor IS NOT NULL AND tr.assessor != 0 AND contract_year = '2020' {$_where} GROUP BY tr.assessor ORDER BY tr.assessor";
				$result = DAO::getResultset($link, $sql);
				foreach($result AS $r)
					$assessorsValuesContinuing[$r[0]] = (int)$r[1];
				$sql = "SELECT tr.assessor, SUM(IF(tr.`status_code`=2, 1, 0)) AS `completed` FROM tr INNER JOIN contracts ON tr.`contract_id` = contracts.id WHERE tr.assessor IS NOT NULL AND tr.assessor != 0 AND contract_year = '2020' {$_where} GROUP BY tr.assessor ORDER BY tr.assessor";
				$result = DAO::getResultset($link, $sql);
				foreach($result AS $r)
					$assessorsValuesCompleted[$r[0]] = (int)$r[1];
				$sql = "SELECT tr.assessor, SUM(IF(tr.`status_code`=3, 1, 0)) AS `completed` FROM tr INNER JOIN contracts ON tr.`contract_id` = contracts.id WHERE tr.assessor IS NOT NULL AND tr.assessor != 0 AND contract_year = '2020' {$_where} GROUP BY tr.assessor ORDER BY tr.assessor";
				$result = DAO::getResultset($link, $sql);
				foreach($result AS $r)
					$assessorsValuesEarlyLeavers[$r[0]] = (int)$r[1];
				foreach($assessors AS &$a)
					$a = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$a}'");
				


				require_once('tpl_home_page_demo.php');
			}
			else
			{
				require_once('tpl_home_page.php');
			}
		}	
	}

	/*private function generateCalendarData(PDO $link)
	{
		require_once('./lib/Calendar.php');
		set_time_limit(0);
		ini_set('memory_limit','512M');
		if(!isset($_REQUEST['v']))
		{
			$_REQUEST['v'] = 1;
		}
		if(!isset($_REQUEST['y']))
		{
			$_REQUEST['y'] = date('Y');
		}
		if(!isset($_REQUEST['m']))
		{
			$_REQUEST['m'] = date('n');
		}
		if(!isset($_REQUEST['d']))
		{
			$_REQUEST['d'] = date('j', strtotime('last sunday'));
		}


		switch($_REQUEST['v'])
		{
			case 1:
				$bc = 'Monthly View';
				$calendar = new Monthly_Calendar($_REQUEST['y'], $_REQUEST['m'], $_REQUEST['d']);
				break;
			case 2:
				$bc = 'Weekly View';
				$calendar = new Weekly_Calendar($_REQUEST['y'], $_REQUEST['m'], $_REQUEST['d']);
				break;
			case 3:
				$bc = 'Daily View';
				$calendar = new Daily_Calendar($_REQUEST['y'], $_REQUEST['m'], $_REQUEST['d']);
				break;
			default:
		}

		$calendar->setQueryString('_action=recruitment_v2_home_page');

		$userCalendar = new UserCalendar(0, array('colour' => '#FFd700'));

		// 2) User events

		$sql = " SELECT * FROM calendar_event WHERE for_whom = '" . $_SESSION['user']->id . "'";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$from_date = new Date($row['datefrom']);
				$to_date = new Date($row['dateto']);
				$event = new CalendarEvent(
					$row['event_id']
					, $userCalendar
					, $row['title']
					, $row['description']
					, $from_date->getYear()
					, $from_date->getMonth()
					, $from_date->getDay()
					, $to_date->getYear()
					, $to_date->getMonth()
					, $to_date->getDay()
					, substr($row['datefromtime'], 0, 2)
					, substr($row['datefromtime'], 3, 2)
					, substr($row['datetotime'], 0, 2)
					, substr($row['datetotime'], 3, 2)
				);
				$event->setLocation($row['location']);
				$calendar->addEvent($event);
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		$dataHTML = $calendar->draw();
		return $dataHTML;
	}*/

	private function buildView(PDO $link)
	{
		$user = $_SESSION['user'];
		$org = $_SESSION['user']->org;
		$one_year_ago = Date::toMySQL("1 year ago");
		$sql = <<<HEREDOC
SELECT
	announcements.*,
	'Perspective' AS `org_legal_name`,
	users.username AS `user_username`,
	users.firstnames AS `user_firstnames`,
	users.surname AS `user_surname`
FROM
	announcements
	LEFT OUTER JOIN users
		ON announcements.users_id = users.username
WHERE
	publication_date IS NOT NULL
	AND publication_date BETWEEN '$one_year_ago' AND CURRENT_DATE
	AND (expiry_date IS NULL OR expiry_date > CURRENT_DATE)
	AND announcements.parent_id IS NULL
ORDER BY
	announcements.publication_date DESC, announcements.modified DESC,announcements.id DESC

HEREDOC;


		$view = new View("view_homepage", $sql);
		$view->setSQL($sql);

		// The magic filter - this contains the WHERE clause that does all the work

		$format = 'WHERE (announcements.publication_date >= \'%1$s\') OR (announcements.modified >= \'%1$s 00:00:00\') ';
		$f = new DateViewFilter('start_date', $format, null);
		$f->setDescriptionFormat("Activity since: %s");
		$view->addFilter($f);

		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(0,'No limit',null,null));
		$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 5, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		$options = array(
			array(1, 'Publication date (asc)', null, 'ORDER BY publication_date ASC'),
			array(2, 'Publication date (desc)', null, 'ORDER BY publication_date DESC'),
			array(3, 'Announcement ID (desc)', null, 'ORDER BY id DESC'),
			array(4, 'Modified date (asc)', null, 'ORDER BY modified ASC'),
			array(5, 'Modified date (desc)', null, 'ORDER BY modified DESC'));
		$f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 3, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);

		$view->refresh($link, $_REQUEST);

		return $view;
	}


	private function renderAnnouncements(PDO $link, $view)
	{
		$st = $link->query($view->getSQL());

		$user = $_SESSION['user'];
		$today = new Date("now");
		$last_logged_in = $user->last_logged_in;
		$ts_last_login = Date::toTimestamp($user->last_logged_in);

		//Set session on announcement
		if( isset($_SESSION['user']->announcement_time) ) {

			$ts_last_login = $_SESSION['user']->announcement_time;
		}
		else {
			$_SESSION['user']->announcement_time = Date::toTimestamp(date('Y-m-d H:i:s'));
		}

		if($st)
		{
			$_SESSION['user']->new_announcement_count = 0;
			while( $row = $st->fetch() )
			{
				$rows[] = $row;
				$ids[] = $row['id'];
				$org = $row['org_legal_name'] ? $row['org_legal_name'] : "Perspective";
				$author = $row['author'];
				$announcement_content=$this->buildAnnouncementBody($row['id'],$row['content']);
				$ts_publication = Date::toTimestamp($row['publication_date']);
				$ts_modified = Date::toTimestamp($row['modified']);

				echo '<div class="Announcement';
				if( $ts_last_login <= $ts_publication || ( $ts_last_login <= $ts_modified && $ts_publication == Date::toTimestamp(date('Y-m-d')) ) )
				{
					$_SESSION['user']->new_announcement_count++;

					echo ' newAnnouncement">'
						.'<span class="NewContent"><img src="/images/new2.png" width="50" height="50"/></span>';
				}
				else
				{
					echo '">';
				}
				echo '	<div class="DateOld">';
				echo '		<div class="Month">'.Date::to($row['publication_date'], "M").'</div>';
				echo '		<div class="Day">'.Date::to($row['publication_date'], "j").'</div>';
				echo '	</div>';

				echo '<div class="Title" onclick="window.location.href=\'do.php?_action=read_announcement&id='.$row['id'].'\';" style="cursor:pointer">'
					.htmlspecialchars($row['title']).'</div>';

				echo '	<div class="Subtitle ">'.$row['subtitle'].'</div>';

				echo '	<div class="Links"><a href="javascript:void(0)" id="morelink_'.$row['id'].'" class="morelink">+ view more</a></div>';

				echo '	<div class="Body longcontent" id="long_'.$row['id'].'">';
				echo 		$announcement_content;

				//echo 		'<div class="Meta">by: ' . $author . ' ('. $org . ')</div>';

				echo 		'<div class="Links"><a href="javascript:void(0)" id="lesslink_'.$row['id'].'" class="lesslink">- view less</a></div>';


				echo '	</div>';

				echo '</div>' ;
			}

		}
//Reset announcement session
		$_SESSION['user']->announcement_time = Date::toTimestamp(date('Y-m-d H:i:s'));
	}

	private function buildAnnouncementBody($announcement_id, $content)
	{
		$wiki = "";

		if(Cache::isAvailable())
		{
			$key = $_SERVER['SERVER_NAME'].' announcement '.$announcement_id;
			$wiki = Cache::get($key);
			if(!$wiki)
			{
				$wiki = HTML::wikify($content);
				Cache::set($key, $wiki, 3600);
			}
		}
		else
		{
			$wiki = HTML::wikify($content);
		}

		return $wiki;
	}


	private function renderFileRepository(PDO $link)
	{
		if(isset($_SESSION['home_page_file_repo_graph']))
            		return $_SESSION['home_page_file_repo_graph'];
		$usedSpace = $this->format_size(Repository::getUsedSpace());

		$test = Repository::getRemainingSpace();

		$max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));
		if(Repository::getRemainingSpace() > $max_file_upload){
			$max_file_upload = Repository::getRemainingSpace();
			Repository::getRemainingSpace();
		}
		$max_file_upload = $this->format_size($max_file_upload);
		$space = SystemConfig::getEntityValue($link,'repository.space');

		$tickInterval = 100;
		$scale = '';
		if(Repository::getTotalSpace() >= 2147483648)
		{
			$tickInterval = 1000;
			$scale = '1k = 1000mb<br>';
		}

		$graph_output = <<<HEREDOC

				/******* File Repository Graph ******************/

	chart = new Highcharts.Chart({
chart:
{
	renderTo: 'fileSize',
	defaultSeriesType: 'bar',
	height: 200,
	width: 300,
	borderColor: '#727375',
	borderWidth: 0,
	backgroundColor: null
},
credits:
{
	enabled: false
},
title:
{
	text: null,
	floating: false,
	align: 'center',
	style:
	{
		fontFamily: 'arial',
		fontSize: '10px',
		color: 'gray',
		fontWeight: 'bold'
	}
},
xAxis:
{
	categories: ['File Space'],
	style:
	{
		fontFamily: 'arial',
		fontSize: '11px',
		color: 'gray'
	}
},
yAxis:
{
	allowDecimals: false,
	min : 0,
	endOnTick:false,
	tickInterval:{$tickInterval},
	plotBands: [],
	plotLines: [],
	showFirstLabel: false,
	title:
	{
		text: '{$scale}Total Space ($space)',
		style:
		{
			fontFamily: 'arial',
			fontSize: '11px',
			color: 'gray'
		}
	}
},
colors:
[
	'#ffffff',
	'#151B8D'
],
legend:
{
	backgroundColor: null,
	reversed: true,
	pointer:false,
	style:
	{
		fontFamily: 'arial',
		fontSize: '10px',
		color: 'gray',
		fontWeight: 'bold'
	}
},
tooltip:
{
	formatter: function()
	{
		return ''+ this.series.name;
	}
},
plotOptions:
{
	series:
	{
		animation: true,
		stacking: 'normal',
		events:
		{
			legendItemClick: function()
			{
              return false;
            }
		}
	}
},
series:
[{
	name: 'Remaining Space ('+({$max_file_upload})+'mb)',
	data: [{$max_file_upload}]
      }, {
	name: 'Used Space ('+{$usedSpace}+'mb)',
	data: [{$usedSpace}]
}],
exporting:
{
	enabled: true,
	width: 500,
	filename: 'File-Repository-Size',
	buttons:
	{
		printButton:
		{
			enabled: false
		},
		exportButton:
		{
			menuItems:
			[
				{text: 'Download chart as PNG'},
                {text: 'Download chart as JPG'},
                {text: 'Download chart as PDF'},
                {text: 'Download chart as SVG'}
			]
		}
	}
},
navigation:
{
	buttonOptions:
	{
		verticalAlign: 'top',
		y: 0
	}
}

});

HEREDOC;
		$_SESSION['home_page_file_repo_graph'] = $graph_output;
		return $graph_output;
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

	/**
	 * Returns count of learner status
	 * @param PDO $link
	 * @return array in the format array("Behind"=>0, "On Track"=>0)
	 */
	private function getLearnerStatus(PDO $link, $current_year_only = false)
	{
		$cache_key = $_SERVER['SERVER_NAME'].' '.$_SESSION['user']->username.' homepage learner status graph';

		$status = Cache::get($cache_key);
		if($status){
			return $status;
		}

		$view = HomePage::getInstance($link);
		if($current_year_only)
			$view->getFilter("filter_contract_year")->setValue(date('Y'));
		$view->refresh($link, $_REQUEST);
		$sql = $view->getSQLStatement()->__toString();

		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		//pre($sql);
		$status = array("Behind"=>0, "On Track"=>0);
		//echo "<br>The number of rows = " . count($rows) . "<br>";
		foreach($rows as $row)
		{
			//pre($row);
			/*
			   if(intval($row['framework_percentage'])>=intval($row['target_status']))
				   $status['On Track']++;
			   else
				   $status['Behind']++;
   */

			//////TODAY24/04/2013$row['target'] = TrainingRecord::getFrameworkTarget($link,$row['tr_id']);
			//if($row['tr_id'] == 3714)
			//echo $row['tr_id'] . "----" . $row['target'] . "------".$row['framework_percentage']. "----------".$row['target_status']."<br>";

			if($row['status_code'] !=2 and $row['status_code'] !=3)
			{
				if(floatval($row['target']) >= 0 || floatval($row['percentage_completed']) >= 0)
				{
					if(floatval($row['percentage_completed']) < floatval($row['target']))
						$status['Behind']++;
					else
						$status['On Track']++;
				}
			}
			/*
			if(floatval($row['target_status']) == "" or intval($row['l36']) >= floatval($row['target_status']))
			{
				$status['On Track']++;
			}
			else if(floatval($row['l36']) < floatval($row['target_status']))
			{
				$status['Behind']++;
			}
			/*
			else
			{
				$status['Not Started']++;
			}
			*/
		}//pre($status);
		Cache::set($cache_key, $status, 600);
		Cache::set($cache_key.' timestamp', Date::toTimestamp("now"), 600);
		return $status;
	}

		private function getSoapClient() {
		$this->client = new SoapClient(null, $this->options);
		try {
			$response = $this->client->login($this->user_auth,"test");
		}
		catch (SoapFault $e) {
			return false;
		}
		$this->session_id = $response->id;
	}

	private function getSupportStats(PDO $link)
	{
		$this->user_auth = array(
			"user_name"	=> "Sunesis Support ",
			"password"	=> md5("perspective"),
			"version"	=> ".01"
		);
		$this->options = array(
			"location" 	=> 'https://sugar.perspective-uk.com/soap.php',
			"uri" 		=> 'https://sugar.perspective-uk.com/',
			"trace" 	=> 1
		);

		// set time and take an hour off to match sugar server???
		$page_load_timestamp = time()-(60*60);

		// create the soap client
		$this->getSoapClient();


		$username = $_SESSION['user']->username;
		$filter_user_complete_name = DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.username = '" . $username . "'");

		$case = $this->client->get_entry_list($this->session_id, 'Cases',' `cases`.`created_by` = "ad7f7f13-2dcf-21c8-b931-4c8e1f495088" and `cases`.`name` like "Support request: % of '.$_SESSION['user']->org->legal_name.'" ', ' `cases`.`status` desc, `cases`.`date_entered` desc ',0,'',1000);

		/**
		 *
		 * Build the summary of Support Request
		 */
		$case_status_collection = array(
			"New" => "New Requests",
			"Assigned" => "Being Looked Into",
			"Reopened" => "Being Looked Into",
			"Validation" => "Being Looked Into",
			"Awaiting Client" => "Requiring Feedback",
			"Awaiting Confirmation" => "Requiring Feedback",
			"Awaiting Development TRAC" => "Under Consideration",
			"Deployment" => "Under Consideration",
			"Development" => "Being Worked On",
			"Closed" => "Finished With",
			"Duplicate" => "Finished With",
			"On Hold" => "Finished With",
			// new case types
			"Not Viable" => "Refused Development",
			"Chargeable Development" => "Bespoke Development"
		);

		$case_group_data = array(
			"New Requests" => "<tr class='header-row' ><td colspan='5' >New Requests</td></tr>",
			"Being Looked Into" => "<tr class='header-row' ><td colspan='5' >Being Looked Into</td></tr>",
			"Requiring Feedback" => "<tr class='header-row' ><td colspan='5' >Requiring Your Feedback</td></tr>",
			"Under Consideration" => "<tr class='header-row' ><td colspan='5' >Under Consideration For Development</td></tr>",
			"Being Worked On" => "<tr class='header-row' ><td colspan='5' >Being Worked On</td></tr>",
			// new headers
			"Chargeable Development" => "<tr class='header-row' ><td colspan='5' >Bespoke Development</td></tr>",
			"Not Viable" => "<tr class='header-row' ><td colspan='5' >Refused Development</td></tr>",
			// ---
			"Finished With" => "<tr class='header-row' ><td colspan='5' >Finished With</td></tr>"
		);

		$case_type_count = array(
			"New Requests" => 0,
			"Being Looked Into" => 0,
			"Requiring Feedback" => 0,
			"Under Consideration" => 0,
			"Being Worked On" => 0,
			// new counters
			"Chargeable Development" => 0,
			"Not Viable" => 0,
			// ---
			"Finished With" => 0
		);
		// fixed
		foreach( $case->entry_list as $field ) {

			$this_client_case = array();
			foreach ( $field->name_value_list as $item ) {
				$this_client_case[$item->name] = $item->value; // fixed curly braces issue should be pushed to production
			}
			if ( array_key_exists($this_client_case['status'], $case_status_collection) ) {

				$case_type_count[$case_status_collection[$this_client_case['status']]]++;
			}
		}

		$summary_html = '<table class="resultset">';

		foreach ( $case_type_count as $this_case_type => $this_case_count ) {

			$summary_html .= '<tr><td style="font-weight: bold; ">'.$this_case_count.'</td><td style="text-align: left;"> requests are <strong>'.$this_case_type.'</strong></td></tr>';
		}

		$summary_html .= '</table>';

		return $summary_html;
	}

	private function getL2L3Progressions(PDO $link)
	{
		$sql = <<<SQL

SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id) AS cnt
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code = 2 AND first_frameworks.`framework_type` = 3 AND first_frameworks.`framework_type` IS NOT NULL
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` = 2 AND second_frameworks.framework_type IS NOT NULL
GROUP BY second_start_year
HAVING second_start_year = '2018';

SQL;
		$res = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(isset($res[0]['cnt']))
			return $res[0]['cnt'] == ''?0:$res[0]['cnt'];
		else
			return 0;
	}

	private function getL3L4Progressions(PDO $link)
	{
		$sql = <<<SQL

SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id) AS cnt
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code = 2 AND first_frameworks.`framework_type` = 2 AND first_frameworks.`framework_type` IS NOT NULL
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` NOT IN (3,2) AND second_frameworks.framework_type IS NOT NULL
GROUP BY second_start_year
HAVING second_start_year = '2018';

SQL;
		$res = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(isset($res[0]['cnt']))
			return $res[0]['cnt'] == ''?0:$res[0]['cnt'];
		else
			return 0;
	}

	private function getTtoAProgressions(PDO $link)
	{
		$sql = <<<SQL

SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id) AS cnt
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code = 2 AND first_frameworks.`framework_type` = 24 AND first_frameworks.`framework_type` IS NOT NULL
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` NOT IN (24) AND second_frameworks.framework_type IS NOT NULL
GROUP BY second_start_year
HAVING second_start_year = '2018';

SQL;
		$res = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(isset($res[0]['cnt']))
			return $res[0]['cnt'] == ''?0:$res[0]['cnt'];
		else
			return 0;
	}

	private function getStudyProgressions(PDO $link)
	{
		$sql = <<<SQL
SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id) as cnt
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code != 1 AND first_frameworks.`framework_type` IS NULL  AND first_frameworks.`framework_code` IS NULL
AND first.id IN (SELECT tr_id FROM ilr WHERE LOCATE('<FundModel>25</FundModel>',ilr)>0 AND LOCATE('<LearnAimRef>ZPROG001</LearnAimRef>',ilr)=0)
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` = 24
GROUP BY second_start_year
HAVING second_start_year = '2018'
SQL;

        $res = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        if(isset($res[0]['cnt']))
            return $res[0]['cnt'] == ''?0:$res[0]['cnt'];
        else
            return 0;
  }

	private $user_auth;
	private $options;
	private $client;
	private $session_id;
	public $main = [];

}
?>