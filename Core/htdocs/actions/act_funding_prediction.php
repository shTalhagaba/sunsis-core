<?php

class funding_prediction implements IAction
{


	public function execute(PDO $link)
	{
		set_time_limit(0);
		ini_set('memory_limit','1024M');
		if(!isset($_REQUEST['contract']))
		{
			throw new Exception('No contract ID specified in the URL');
		}

		// Check if it is LR contract
		$contract_id = $_REQUEST['contract'];
		$contract_type = DAO::getSingleValue($link, "select funding_body from contracts where id = '$contract_id'");
		if($contract_type == '1')
		{
			require_once('tpl_lr_predictor.php');

		}
		else
		{
			// dependencies
			require_once('./lib/funding/FundingCore.php');
			require_once('./lib/funding/PeriodLookup.php');
			require_once('./lib/funding/LearnerFunding.php');
			require_once('./lib/funding/FundingPeriod.php');
			require_once('./lib/funding/FundingPrediction.php');
			require_once('./lib/funding/FundingPredictionPeriod.php');


			// defaults
			if(!isset($_REQUEST['period']))
			{
				$_REQUEST['period'] = 0;
			}
			$period = intval($_REQUEST['period']);


			// see if we're looking at an individual qualification
			if(!isset($_REQUEST['sq']))
			{
				$_REQUEST['sq'] = 0;
			}
			$sq = intval($_REQUEST['sq']);

			// see if this is course level predictor
			if(!isset($_REQUEST['course']))
			{
				$_REQUEST['course'] = 0;
			}
			$course = intval($_REQUEST['course']);


			// see if this is assessor level predictor
			if(!isset($_REQUEST['assessor']))
			{
				$_REQUEST['assessor'] = 0;
			}
			$assessor = $_REQUEST['assessor'];


			// see if this is tutor level predictor
			if(!isset($_REQUEST['tutor']))
			{
				$_REQUEST['tutor'] = 0;
			}
			$tutor = $_REQUEST['tutor'];


			// see if this is employer level predictor
			if(!isset($_REQUEST['employer']))
			{
				$_REQUEST['employer'] = 0;
			}
			$employer = $_REQUEST['employer'];

			// see if this is provider level predictor
			if(!isset($_REQUEST['provider']))
			{
				$_REQUEST['provider'] = 0;
			}
			$provider = $_REQUEST['provider'];

			// see if this is employer business code level predictor
			$filter_emp_b_code = isset($_REQUEST['filter_emp_b_code'])?$_REQUEST['filter_emp_b_code']:'';

			if(!isset($_REQUEST['submission']))
			{
				$_REQUEST['submission'] = 0;
			}
			$submission = $_REQUEST['submission'];

			if($submission=='')
				$submission = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE last_submission_date>=CURDATE() AND contract_type = 2 ORDER BY last_submission_date LIMIT 1;");


			// try and get the contract info via the url
			if(!isset($_REQUEST['contract']))
			{
				throw new Exception('No contract ID specified in the URL');
			}
			$contracts = $_REQUEST['contract'];
			//		$contractInfo = Contract::loadFromDatabase($link, $contract);
			if($contracts=='')
			{
				throw new Exception('A contract with ID: ' . $contracts . ' does not exist in the database');
			}

			if(!isset($_REQUEST['output']))
			{
				$_REQUEST['output'] = 'HTML';
			}

			$count = DAO::getSingleValue($link, "select count(*) from ilr where contract_id in ($contracts) and submission = '$submission'");
			if($count==0)
				pre("No ILRs available ");

			$sql = new SQLStatement("
SELECT
  COUNT(*)
FROM
  ilr
  LEFT JOIN contracts
    ON contracts.id = ilr.contract_id
  LEFT JOIN tr
    ON tr.id = ilr.tr_id
  LEFT JOIN users AS learners
  	ON tr.username = learners.username
  LEFT JOIN users AS assessors
    ON assessors.id = tr.assessor
  LEFT JOIN users AS tutors
    ON tutors.id = tr.tutor
  LEFT JOIN courses_tr
    ON courses_tr.tr_id = tr.id
  LEFT JOIN courses
    ON courses.id = courses_tr.course_id
  LEFT JOIN organisations AS providers
    ON providers.id = tr.provider_id
  LEFT JOIN organisations AS employers
    ON employers.id = tr.employer_id ");

			$sql->setClause("WHERE ilr.is_active = 1");
			if($contracts != '' && $contracts != '0')
				$sql->setClause("WHERE ilr.contract_id IN ($contracts)");
			if($assessor != '' && $assessor != '0')
				$sql->setClause("WHERE assessors.id = '$assessor'");
			if($employer != '' && $employer != '0')
				$sql->setClause("WHERE employers.id = '$employer'");
			if($provider != '' && $provider != '0')
				$sql->setClause("WHERE providers.id = '$provider'");
			if($course != '' && $course != '0')
				$sql->setClause("WHERE courses.id = '$course'");
			if($tutor != '' && $tutor != '0')
				$sql->setClause("WHERE tutors.id = '$tutor'");
			if($filter_emp_b_code != '' && (DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo"))
			{
				$sql->setClause("WHERE learners.employer_business_code = '$filter_emp_b_code'");
			}

			$count = DAO::getSingleValue($link, $sql->__toString());
			if($count==0)
				pre("No ILRs available for your selected filters.");

            if(empty($period))
            {
                $predictions = new FundingPrediction($link, $contracts, $sq, $course, $assessor, $employer, $submission, false, $tutor, $filter_emp_b_code);

                if($_REQUEST['output'] == 'XLS')
                    if(isset($_REQUEST['tab']) && $_REQUEST['tab']=='1924TraineeshipNP')
                        $predictions->to1924TraineeshipNP($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='1924TraineeshipNP')
                        $predictions->to1924TraineeshipNP($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='1924TraineeshipPNov17')
                        $predictions->to1924TraineeshipPNov17($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='AEBOtherNP')
                        $predictions->toAEBOtherNP($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='AEBOtherPNov17')
                        $predictions->toAEBOtherPNov17($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='1618Apps')
                        $predictions->to1618Apps($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='1923Apps')
                        $predictions->to1923Apps($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='24Apps')
                        $predictions->to24Apps($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='1618AppsLevyMay17')
                        $predictions->to1618AppsLevyMay17($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='1618AppsNLNPMay17')
                        $predictions->to1618AppsNLNPMay17($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='1618AppsNLPMay17')
                        $predictions->to1618AppsNLPMay17($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='19AppsLevyMay17')
                        $predictions->to19AppsLevyMay17($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='19AppsNLNPMay17')
                        $predictions->to19AppsNLNPMay17($_REQUEST['output']);
                    elseif(isset($_REQUEST['tab']) && $_REQUEST['tab']=='19AppsNLPMay17')
                        $predictions->to19AppsNLPMay17($_REQUEST['output']);

                if(!empty($_REQUEST['sq']))
                {
                    $dataHTMLTotal = '<h4>Funding for </h4>';
                }

                $dataHTMLTotal = '<table class="funding-box" cellpadding="15">';
                $dataHTMLTotal .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';

                if(!empty($_REQUEST['sq']))
                {
                    $dataHTMLTotal .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart($link, false) . '</td>';
                }
                else
                {
                    $dataHTMLTotal .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart($link, true) . '</td>';
                }

                $dataHTMLTotal .= '<td rowspan="2"><h3>';
                $dataHTMLTotal .= '<a title="Click to see the learning aim level report for all periods" href="/do.php?_action=funding_prediction&amp;contract=' . $contracts . '&amp;period=25&amp;employer=' . $employer . '&amp;course=' . $course . '&amp;assessor=' . $assessor . '&amp;tutor=' . $tutor . '&amp;submission=' . $submission . '&amp;filter_emp_b_code=' . $filter_emp_b_code . '"> Detailed View' . '</a>';
                $dataHTMLTotal .= '</h3>' . $predictions->to($_REQUEST['output']) . '</td></tr>';
                $dataHTMLTotal .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTMLTotal .= '</table>';

                $dataHTML1924TraineeshipNP = '<table class="funding-box" cellpadding="15">';
                $dataHTML1924TraineeshipNP .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML1924TraineeshipNP .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart1924TraineeshipNP($link, true) . '</td>';
                $dataHTML1924TraineeshipNP .= '<td rowspan="2">';
                $dataHTML1924TraineeshipNP .= '<h3> Funding for 19-24 Traineeship (non-procured) </h3>';
                $dataHTML1924TraineeshipNP .= '</h3>' . $predictions->to1924TraineeshipNP($_REQUEST['output']) . '</td></tr>';
                $dataHTML1924TraineeshipNP .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=1924TraineeshipNP"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML1924TraineeshipNP .= '</table>';

                $dataHTML1924TraineeshipPNov17 = '<table class="funding-box" cellpadding="15">';
                $dataHTML1924TraineeshipPNov17 .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML1924TraineeshipPNov17 .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart1924TraineeshipPNov17($link, true) . '</td>';
                $dataHTML1924TraineeshipPNov17 .= '<td rowspan="2">';
                $dataHTML1924TraineeshipPNov17 .= '<h3> Funding for 19-24 Traineeship (procured from Nov 2017) </h3>';
                $dataHTML1924TraineeshipPNov17 .= '</h3>' . $predictions->to1924TraineeshipPNov17($_REQUEST['output']) . '</td></tr>';
                $dataHTML1924TraineeshipPNov17 .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=1924TraineeshipPNov17"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML1924TraineeshipPNov17 .= '</table>';

                $dataHTMLAEBOtherNP = '<table class="funding-box" cellpadding="15">';
                $dataHTMLAEBOtherNP .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTMLAEBOtherNP .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChartAEBOtherNP($link, true) . '</td>';
                $dataHTMLAEBOtherNP .= '<td rowspan="2">';
                $dataHTMLAEBOtherNP .= '<h3> Funding for AEB - Other Learning (non-procured) </h3>';
                $dataHTMLAEBOtherNP .= '</h3>' . $predictions->toAEBOtherNP($_REQUEST['output']) . '</td></tr>';
                $dataHTMLAEBOtherNP .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=AEBOtherNP"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTMLAEBOtherNP .= '</table>';

                $dataHTMLAEBOtherPNov17 = '<table class="funding-box" cellpadding="15">';
                $dataHTMLAEBOtherPNov17 .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTMLAEBOtherPNov17 .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChartAEBOtherPNov17($link, true) . '</td>';
                $dataHTMLAEBOtherPNov17 .= '<td rowspan="2">';
                $dataHTMLAEBOtherPNov17 .= '<h3> Funding for AEB - Other Learning (procured from Nov 2017) </h3>';
                $dataHTMLAEBOtherPNov17 .= '</h3>' . $predictions->toAEBOtherPNov17($_REQUEST['output']) . '</td></tr>';
                $dataHTMLAEBOtherPNov17 .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=AEBOtherPNov17"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTMLAEBOtherPNov17 .= '</table>';

                $dataHTML1618Apps = '<table class="funding-box" cellpadding="15">';
                $dataHTML1618Apps .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML1618Apps .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart1618Apps($link, true) . '</td>';
                $dataHTML1618Apps .= '<td rowspan="2">';
                $dataHTML1618Apps .= '<h3> Funding for 16-18 Apprenticeships </h3>';
                $dataHTML1618Apps .= '</h3>' . $predictions->to1618Apps($_REQUEST['output']) . '</td></tr>';
                $dataHTML1618Apps .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=1618Apps"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML1618Apps .= '</table>';

                $dataHTML1923Apps = '<table class="funding-box" cellpadding="15">';
                $dataHTML1923Apps .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML1923Apps .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart1923Apps($link, true) . '</td>';
                $dataHTML1923Apps .= '<td rowspan="2">';
                $dataHTML1923Apps .= '<h3> Funding for 19-23 Apprenticeships </h3>';
                $dataHTML1923Apps .= '</h3>' . $predictions->to1923Apps($_REQUEST['output']) . '</td></tr>';
                $dataHTML1923Apps .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=1923Apps"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML1923Apps .= '</table>';

                $dataHTML24Apps = '<table class="funding-box" cellpadding="15">';
                $dataHTML24Apps .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML24Apps .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart24Apps($link, true) . '</td>';
                $dataHTML24Apps .= '<td rowspan="2">';
                $dataHTML24Apps .= '<h3> Funding for 24+ Apprenticeships </h3>';
                $dataHTML24Apps .= '</h3>' . $predictions->to24Apps($_REQUEST['output']) . '</td></tr>';
                $dataHTML24Apps .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=24Apps"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML24Apps .= '</table>';

                $dataHTML1618AppsLevyMay17 = '<table class="funding-box" cellpadding="15">';
                $dataHTML1618AppsLevyMay17 .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML1618AppsLevyMay17 .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart1618AppsLevyMay17($link, true) . '</td>';
                $dataHTML1618AppsLevyMay17 .= '<td rowspan="2">';
                $dataHTML1618AppsLevyMay17 .= '<h3> Funding for 16-18 Apprenticeship (From May 2017) Levy Contract </h3>';
                $dataHTML1618AppsLevyMay17 .= '</h3>' . $predictions->to1618AppsLevyMay17($_REQUEST['output']) . '</td></tr>';
                $dataHTML1618AppsLevyMay17 .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=1618AppsLevyMay17"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML1618AppsLevyMay17 .= '</table>';

                $dataHTML1618AppsNLNPMay17 = '<table class="funding-box" cellpadding="15">';
                $dataHTML1618AppsNLNPMay17 .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML1618AppsNLNPMay17 .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart1618AppsNLNPMay17($link, true) . '</td>';
                $dataHTML1618AppsNLNPMay17 .= '<td rowspan="2">';
                $dataHTML1618AppsNLNPMay17 .= '<h3> Funding for 16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured) </h3>';
                $dataHTML1618AppsNLNPMay17 .= '</h3>' . $predictions->to1618AppsNLNPMay17($_REQUEST['output']) . '</td></tr>';
                $dataHTML1618AppsNLNPMay17 .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=1618AppsNLNPMay17"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML1618AppsNLNPMay17 .= '</table>';

                $dataHTML1618AppsNLPMay17 = '<table class="funding-box" cellpadding="15">';
                $dataHTML1618AppsNLPMay17 .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML1618AppsNLPMay17 .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart1618AppsNLPMay17($link, true) . '</td>';
                $dataHTML1618AppsNLPMay17 .= '<td rowspan="2">';
                $dataHTML1618AppsNLPMay17 .= '<h3> Funding for 16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured) </h3>';
                $dataHTML1618AppsNLPMay17 .= '</h3>' . $predictions->to1618AppsNLPMay17($_REQUEST['output']) . '</td></tr>';
                $dataHTML1618AppsNLPMay17 .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=1618AppsNLPMay17"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML1618AppsNLPMay17 .= '</table>';

                $dataHTML19AppsLevyMay17 = '<table class="funding-box" cellpadding="15">';
                $dataHTML19AppsLevyMay17 .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML19AppsLevyMay17 .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart19AppsLevyMay17($link, true) . '</td>';
                $dataHTML19AppsLevyMay17 .= '<td rowspan="2">';
                $dataHTML19AppsLevyMay17 .= '<h3> Funding for 19+ Apprenticeship (From May 2017) Levy Contract </h3>';
                $dataHTML19AppsLevyMay17 .= '</h3>' . $predictions->to19AppsLevyMay17($_REQUEST['output']) . '</td></tr>';
                $dataHTML19AppsLevyMay17 .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=19AppsLevyMay17"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML19AppsLevyMay17 .= '</table>';

                $dataHTML19AppsNLNPMay17 = '<table class="funding-box" cellpadding="15">';
                $dataHTML19AppsNLNPMay17 .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML19AppsNLNPMay17 .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart19AppsNLNPMay17($link, true) . '</td>';
                $dataHTML19AppsNLNPMay17 .= '<td rowspan="2">';
                $dataHTML19AppsNLNPMay17 .= '<h3> Funding for 19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured) </h3>';
                $dataHTML19AppsNLNPMay17 .= '</h3>' . $predictions->to19AppsNLNPMay17($_REQUEST['output']) . '</td></tr>';
                $dataHTML19AppsNLNPMay17 .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=19AppsNLNPMay17"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML19AppsNLNPMay17 .= '</table>';

                $dataHTML19AppsNLPMay17 = '<table class="funding-box" cellpadding="15">';
                $dataHTML19AppsNLPMay17 .= '<tr><td colspan="2" style="background: #FFE1E1"><img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" /> Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.</td></tr>';
                $dataHTML19AppsNLPMay17 .= '<tr valign="top"><td><h3>Graph</h3>' . $predictions->toBarChart19AppsNLPMay17($link, true) . '</td>';
                $dataHTML19AppsNLPMay17 .= '<td rowspan="2">';
                $dataHTML19AppsNLPMay17 .= '<h3> Funding for 19+ Apprenticeship Non-Levy Contract (procured) </h3>';
                $dataHTML19AppsNLPMay17 .= '</h3>' . $predictions->to19AppsNLPMay17($_REQUEST['output']) . '</td></tr>';
                $dataHTML19AppsNLPMay17 .= '<tr valign="top"><td><h3>Export options</h3><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS&amp;tab=19AppsNLPMay17"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a>&nbsp;&nbsp;<a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=PDF"><img src="/images/pdf_export.gif" alt="Export to excel (*.xls)" /></a></td></tr>';
                $dataHTML19AppsNLPMay17 .= '</table>';

                if(empty($_REQUEST['sq']))
                {
                    $_SESSION['bc']->index = 0;
                    $_SESSION['bc']->add($link, 'do.php?_action=funding_prediction&amp;contract=' . $_REQUEST['contract'], 'Funding prediction ');
                }
                else
                {
                    $getlinfo = $link->query("
						SELECT
							CONCAT(tr.surname,', ',tr.firstnames) as name, tr.L03
						FROM
							student_qualifications as sq
						LEFT JOIN
							tr ON (tr.id = sq.tr_id)
						WHERE
							sq.auto_id = '" . intval($sq) . "'
					");
                    while($row = $getlinfo->fetch())
                    {
                        $learnerInfo = $row;
                    }
                    $_SESSION['bc']->index = 2;
                    $_SESSION['bc']->add($link, 'do.php?_action=funding_prediction&amp;contract=' . $_REQUEST['contract'] . '&amp;sq=' . $sq, 'Funding prediction for ' . $learnerInfo['name'] . ' (' . $learnerInfo['L03'] . ')');
                }
            }
            else // period-drilldown view
            {
                $predictions = new FundingPredictionPeriod($link, $contracts, $period, $course, $assessor, $employer, $submission, $tutor, '', $filter_emp_b_code);
                $dataHTMLTotal = '<h3>Funding for W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</h3><h4 class="noleftmargin">Export options:</h4><p><a href="' . str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&amp;output=XLS"><img src="/images/excel_export.gif" alt="Export to excel (*.xls)" /></a></p>' . $predictions->to($_REQUEST['output']);
                $_SESSION['bc']->index = 1;
                $_SESSION['bc']->add($link, 'do.php?_action=funding_prediction&amp;contract=' . $_REQUEST['contract'] . '&amp;period=' . $period, 'W' . str_pad($period, 2, '0', STR_PAD_LEFT));
            }




            require_once('tpl_funding_prediction.php');
		}
	}
}

?>