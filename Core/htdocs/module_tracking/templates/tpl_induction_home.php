
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Induction Home</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		#home_postcode{text-transform:uppercase}
		#tooltip
		{
			width:300px;
			background-image:url('/images/shadow-30.png');
			position: absolute;
			display: none;
			top: 50%;
			left: 50%;
			margin-top: -50px;
			margin-left: -50px;
		}

		#tooltip_content
		{
			position:relative;
			top: -3px;
			left: -3px;
			background-color: #FDF1E2;
			border: 1px gray solid;
			padding: 2px;
			font-family: sans-serif;
			font-size: 10pt;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Induction Home</div>
			<div class="ButtonBar"></div>
			<div class="ActionIconBar"></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<p></p>

<div class="container-fluid">


<div class="row"><div class="col-lg-10"><p><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=edit_inductee';"><i class="fa fa-plus"></i> New Induction</span></p></div></div>

<div class="row">
<div class="col-md-9 col-sm-7" >
<div class="nav-tabs-custom ">
<ul class="nav nav-tabs">
	<li class="<?php echo $tab1; ?>"><a class="tabHyperlink" href="#tab1" data-toggle="tab">Projected Starts</a></li>
	<?php
	$tab = 2;
	$today = new DateTime('now');
	$today->setDate($today->format('Y'), $today->format('m'), $today->format('d'));
	for($i = 1; $i <= 4; $i++)
	{
		$t = 'tab'.$tab;
		echo '<li class="'.$$t.'"><a class="tabHyperlink" href="#tab' . $tab . '" data-toggle="tab">' . $today->format('M Y') . '</a></li>';
		$tab++;
		InductionHelper::addMonths($today, '-1');
	}
	?>
	<li class="<?php echo $tab6; ?>"><a class="tabHyperlink" href="#tab6" data-toggle="tab">All</a></li>
</ul>
<div class="tab-content">
<div class="<?php echo $tab1; ?> tab-pane" id="tab1">

	<?php
	$viewNameSuffix = 'ProjectStarts';
	$view = VoltView::getViewFromSession('view_ViewInduction_'.$viewNameSuffix, 'view_ViewInduction_'.$viewNameSuffix); /* @var $view VoltView */
	if(is_null($view))
	{
		$view = $_SESSION['view_ViewInduction_'.$viewNameSuffix] = $this->buildView($viewNameSuffix);
	}
	if(array_key_exists('view', $_REQUEST))
	{
		if($_REQUEST['view'] == 'view_ViewInduction_'.$viewNameSuffix)
			$view->refresh($_REQUEST, $link);
	}
	else
	{
		$view->refresh($_REQUEST, $link);
	}
	$formIdentity = 'frmFilters'.$viewNameSuffix;
	$divIdentity = 'divFilters'.$viewNameSuffix;
	echo '<div class="callout">';
	echo '<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock(\''.$divIdentity.'\');showHideBlock(\'div_filter_crumbs\');" title="Show/hide filters"></span>';
	echo ' &nbsp; <span class="btn btn-sm btn-info fa fa-download" onclick="window.location.href=\'do.php?_action=export_current_view_to_excel&key='.$view->getViewName().'\'" title="Export to csv"></span>';
	echo '</div>';
	$filter_firstnames = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_firstnames');
	$filter_surname = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_surname');
	$filter_induction_status = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_induction_status');
	$filter_brm = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_brm');
	$filter_resourcer = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_resourcer');
	$filter_lead_gen = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_lead_gen');
	$filter_learner_type = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_learner_type');
	$filter_employer = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_employer');
	$filter_age_group = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_age_group');
	$filter_programme = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_programme');
	//$filter_miap = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_miap');
	// $filter_headset = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_headset');
	$filter_comt_stmt = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_comt_stmt');
	//$filter_i_assessor = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_i_assessor');
	$filter_a_assessor = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_a_assessor');
	//$filter_wfd = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_wfd');
	// $filter_iag_numeracy = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_iag_numeracy');
	// $filter_iag_literacy = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_iag_literacy');
	// $filter_iag_ict = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_iag_ict');
	$filter_levy_payer = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_levy_payer');

	echo <<<HTML
	<small>
	<div id="$divIdentity" style="display:none">

		<form name="$formIdentity" method="get" action="do.php" id="applyFilter">
			<input type="hidden" name="page" value="1" />
			<input type="hidden" name="_action" value="induction_home" />
			<input type="hidden" name="view" value="view_ViewInduction_$viewNameSuffix" />
			<input type="hidden" id="filter_name" name="filter_name" value="" />
			<input type="hidden" id="filter_id" name="filter_id" value="" />
			<input type="hidden" id="selected_tab" name="selected_tab" value="tab1" />

			<div id="filterBox" class="clearfix">
				<fieldset>
					<div class="field float"><label>First Name:</label>$filter_firstnames </div>
					<div class="field float"><label>Surname:</label>$filter_surname </div>
					<div class="field float"><label>CEM:</label>$filter_brm </div>
					<div class="field float"><label>Recruiter:</label>$filter_resourcer </div>
					<div class="field float"><label>Lead Generator:</label>$filter_lead_gen </div>
					<div class="field float"><label>Employer:</label>$filter_employer </div>
					<div class="field float"><label>Age Group:</label>$filter_age_group </div>
					
					
					<div class="field float"><label>Commitment Statement:</label>$filter_comt_stmt </div>
					
					<div class="field float"><label>Assigned Learning Mentor:</label>$filter_a_assessor </div>

					
					<div class="field float"><label>Levy Payer:</label>$filter_levy_payer </div>
					<div class="field newrow"></div>
					<div class="field float"><label>Induction Status:</label>$filter_induction_status </div>
					<div class="field float"><label>Learner Type:</label>$filter_learner_type </div>
					<div class="field float"><label>Programme:</label>$filter_programme </div>
				</fieldset>
				<fieldset>
					<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters($formIdentity);" value="Reset" />
				</fieldset>
			</div>

		</form>
	</div>
	</small>

HTML;
	echo $this->renderView($link, $view, 'tab1');
	?>

</div>
<?php
$tab = 2;
$first = strtotime("first day this month");
$d = '';
for ($i = 0; $i <= 3; $i++)
{

	$viewNameSuffix = date('MY', strtotime("-$i month", $first));
	$first_date = date('Y-m-d',strtotime("first day of -$i month"));
	$last_date = date('Y-m-d',strtotime("last day of -$i month"));

	if($i == 0)
		$viewNameSuffix = 'Feb2020';
	if($i == 1)
		$viewNameSuffix = 'Jan2020';
	if($i == 2)
		$viewNameSuffix = 'Dec2019';
	if($i == 3)
		$viewNameSuffix = 'Nov2019';

	$view = VoltView::getViewFromSession('view_ViewInduction_'.$viewNameSuffix, 'view_ViewInduction_'.$viewNameSuffix); /* @var $view VoltView */
	if(is_null($view))
	{
		$view = $_SESSION['view_ViewInduction_'.$viewNameSuffix] = $this->buildView($viewNameSuffix, $first_date, $last_date);
	}
	if(array_key_exists('view', $_REQUEST))
	{
		if($_REQUEST['view'] == 'view_ViewInduction_'.$viewNameSuffix)
			$view->refresh($_REQUEST, $link);
	}
	else
	{
		$view->refresh($_REQUEST, $link);
	}
	$induction_capacity = DAO::getSingleValue($link, "SELECT capacity FROM lookup_induction_capacity WHERE REPLACE(month, '_', '') = '" . $viewNameSuffix . "'");
	$tab_identification = 'tab'.$tab;
	echo '<div class="'.$$tab_identification.' tab-pane" id="'.$tab_identification.'">';
	$formIdentity = 'frmFilters'.$viewNameSuffix;
	$divIdentity = 'divFilters'.$viewNameSuffix;
	echo '<div class="callout">';
	echo '<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock(\''.$divIdentity.'\');showHideBlock(\'div_filter_crumbs\');" title="Show/hide filters"></span>';
	echo ' &nbsp; <span class="btn btn-sm btn-info fa fa-download" onclick="window.location.href=\'do.php?_action=export_current_view_to_excel&key='.$view->getViewName().'\'" title="Export to csv"></span>';
	echo '<span class="pull-right">Induction Capacity: '.$induction_capacity.'</span>';
	echo '</div>';
	$filter_firstnames = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_firstnames');
	$filter_surname = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_surname');
	$filter_brm = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_brm');
	$filter_resourcer = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_resourcer');
	$filter_lead_gen = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_lead_gen');
	$filter_learner_type = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_learner_type');
	$filter_employer = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_employer');
	$filter_age_group = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_age_group');
	$filter_programme = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_programme');
	//$filter_miap = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_miap');
	// $filter_headset = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_headset');
	$filter_comt_stmt = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_comt_stmt');
	//$filter_i_assessor = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_i_assessor');
	$filter_a_assessor = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_a_assessor');
	//$filter_wfd = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_wfd');
	// $filter_iag_numeracy = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_iag_numeracy');
	// $filter_iag_literacy = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_iag_literacy');
	// $filter_iag_ict = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_iag_ict');
	$filter_induction_status = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_induction_status');
	$filter_levy_payer = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_levy_payer');

	echo <<<HTML
	<small>
	<div id="$divIdentity" style="display:none;">

		<form name="$formIdentity" method="get" action="do.php" id="applyFilter">
			<input type="hidden" name="page" value="1" />
			<input type="hidden" name="_action" value="induction_home" />
			<input type="hidden" name="view" value="view_ViewInduction_$viewNameSuffix" />
			<input type="hidden" id="filter_name" name="filter_name" value="" />
			<input type="hidden" id="filter_id" name="filter_id" value="" />
			<input type="hidden" id="selected_tab" name="selected_tab" value="$tab_identification" />

			<div id="filterBox" class="clearfix">
				<fieldset>
					<div class="field float"><label>First Name:</label>$filter_firstnames </div>
					<div class="field float"><label>Surname:</label>$filter_surname </div>
					<div class="field float"><label>CEM:</label>$filter_brm </div>
					<div class="field float"><label>Recruiter:</label>$filter_resourcer </div>
					<div class="field float"><label>Lead Generator:</label>$filter_lead_gen </div>
					<div class="field float"><label>Employer:</label>$filter_employer </div>
					<div class="field float"><label>Age Group:</label>$filter_age_group </div>
					
					
					<div class="field float"><label>Commitment Statement:</label>$filter_comt_stmt </div>
					
					<div class="field float"><label>Assigned Learning Mentor:</label>$filter_a_assessor </div>

					
					<div class="field float"><label>Levy Payer:</label>$filter_levy_payer </div>
					<div class="field newrow"></div>
					<div class="field float"><label>Induction Status:</label>$filter_induction_status </div>
					<div class="field float"><label>Learner Type:</label>$filter_learner_type </div>
					<div class="field float"><label>Programme:</label>$filter_programme </div>
				</fieldset>
				<fieldset>
					<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters($formIdentity);" value="Reset" />
				</fieldset>
			</div>

		</form>
	</div>
	</small>

HTML;

	echo $this->renderView($link, $view, $tab_identification);
	echo '</div>';
	$tab++;

	if($i == 0)
	{
		$d .= <<<HEREDOC
	$('#tbl_ViewInduction_$viewNameSuffix').DataTable({
		"paging": false,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": false,
		"autoWidth": true
	});

HEREDOC;

	}
}
?>
<div class="<?php echo $tab6; ?> tab-pane" id="tab6">
	<?php
	$viewNameSuffix = 'All';
	$view = VoltView::getViewFromSession('view_ViewInduction_'.$viewNameSuffix, 'view_ViewInduction_'.$viewNameSuffix); /* @var $view VoltView */
	//$view=null;
	if(is_null($view))
	{
		$view = $_SESSION['view_ViewInduction_'.$viewNameSuffix] = $this->buildView($viewNameSuffix);
	}

	if(array_key_exists('view', $_REQUEST))
	{
		if($_REQUEST['view'] == 'view_ViewInduction_'.$viewNameSuffix)
			$view->refresh($_REQUEST, $link);
	}
	else
	{
		$view->refresh($_REQUEST, $link);
	}
	//if(DB_NAME == "am_baltic_demo")
	{
		$_t = $view->getFilterValue('view_ViewInduction_Allfilter_programme');
		$_t = isset($_t[0]) ? $_t[0] : '';
		if($_t == 'SHOW_ALL')
		{
			$_f1 = $view->getFilter('view_ViewInduction_Allfilter_programme');
			$_f1->setValue(null);
		}
		$_t = $view->getFilterValue('view_ViewInduction_Allfilter_induction_status');
		$_t = isset($_t[0]) ? $_t[0] : '';
		if($_t == 'SHOW_ALL')
		{
			$_f1 = $view->getFilter('view_ViewInduction_Allfilter_induction_status');
			$_f1->setValue(null);
		}
	}

	$formIdentity = 'frmFilters'.$viewNameSuffix;
	$divIdentity = 'divFilters'.$viewNameSuffix;
	echo '<div class="callout">';
	echo '<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock(\''.$divIdentity.'\');showHideBlock(\'div_filter_crumbs\');" title="Show/hide filters"></span>';
	echo ' &nbsp; <span class="btn btn-sm btn-info fa fa-download" onclick="window.location.href=\'do.php?_action=export_current_view_to_excel&key='.$view->getViewName().'\'" title="Export to csv"></span>';
	echo '</div>';
	$filter_firstnames = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_firstnames');
	$filter_surname = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_surname');
	$filter_induction_status = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_induction_status');
	$filter_brm = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_brm');
	$filter_resourcer = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_resourcer');
	$filter_lead_gen = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_lead_gen');
	$filter_learner_type = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_learner_type');
	$filter_employer = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_employer');
	$filter_age_group = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_age_group');
	$filter_programme = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_programme');
	//$filter_miap = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_miap');
	// $filter_headset = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_headset');
	$filter_comt_stmt = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_comt_stmt');
	//$filter_i_assessor = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_i_assessor');
	$filter_a_assessor = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_a_assessor');
	//$filter_wfd = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_wfd');
	// $filter_iag_numeracy = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_iag_numeracy');
	// $filter_iag_literacy = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_iag_literacy');
	// $filter_iag_ict = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_iag_ict');
	// $filter_cg = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_cg');
	$filter_levy_payer = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_levy_payer');
	$filter_sunesis_account = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_sunesis_account');
	$filter_from_induction_date = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_from_induction_date');
	$filter_to_induction_date = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_to_induction_date');
	$filter_from_projected_induction_date = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_from_projected_induction_date');
	$filter_to_projected_induction_date = $view->getFilterHTML('view_ViewInduction_'.$viewNameSuffix.'filter_to_projected_induction_date');

	echo <<<HTML
	<small>
	<div id="$divIdentity" style="display:none">

		<form name="$formIdentity" method="get" action="do.php" id="applyFilter">
			<input type="hidden" name="page" value="1" />
			<input type="hidden" name="_action" value="induction_home" />
			<input type="hidden" name="view" value="view_ViewInduction_$viewNameSuffix" />
			<input type="hidden" id="filter_name" name="filter_name" value="" />
			<input type="hidden" id="filter_id" name="filter_id" value="" />
			<input type="hidden" id="selected_tab" name="selected_tab" value="tab6" />

			<div id="filterBox" class="clearfix">
				<fieldset>
					<div class="field float"><label>First Name:</label>$filter_firstnames </div>
					<div class="field float"><label>Surname:</label>$filter_surname </div>
					<div class="field float"><label>CEM:</label>$filter_brm </div>
					<div class="field float"><label>Recruiter:</label>$filter_resourcer </div>
					<div class="field float"><label>Lead Generator:</label>$filter_lead_gen </div>
					<div class="field float"><label>Employer:</label>$filter_employer </div>
					<div class="field float"><label>Age Group:</label>$filter_age_group </div>
					
					
					<div class="field float"><label>Commitment Statement:</label>$filter_comt_stmt </div>
					
					<div class="field float"><label>Assigned Learning Mentor:</label>$filter_a_assessor </div>

					
					
					<div class="field float"><label>Levy Payer:</label>$filter_levy_payer </div>
					<div class="field float"><label>Sunesis Account:</label>$filter_sunesis_account </div>
					<div class="field float"><label>Induction date between</label>$filter_from_induction_date&nbsp;and&nbsp;$filter_to_induction_date</div>
					<div class="field float"><label>Projected induction date between</label>$filter_from_projected_induction_date&nbsp;and&nbsp;$filter_to_projected_induction_date</div>
					<div class="field newrow"></div>
					<div class="field float"><label>Induction Status:</label>$filter_induction_status </div>
					<div class="field float"><label>Learner Type:</label>$filter_learner_type </div>
					<div class="field float"><label>Programme:</label>$filter_programme </div>
				</fieldset>
				<fieldset>
					<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters($formIdentity);" value="Reset" />
				</fieldset>
			</div>

		</form>
	</div>
	</small>

HTML;
	echo $this->renderView($link, $view, 'tab6');
	?>
</div>
</div>
</div>
</div>

<div class="col-md-3 col-sm-5">
	<div class="well small" id="divDetail">
		<p>select learner to view details</p>
	</div>
</div>


</div>

</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>

<script language="JavaScript">
	$(function() {

		$('img[title="Show calendar"]').hide();

		$("input[type='text'][id$='filter_from_induction_date'], input[type='text'][id$='filter_to_induction_date']").datepicker({
			dateFormat: 'dd/mm/yy'
		});

		$("input[type='text'][id$='filter_from_projected_induction_date'], input[type='text'][id$='filter_to_projected_induction_date']").datepicker({
			dateFormat: 'dd/mm/yy'
		});

		$('#tbl_ViewInduction_All, #tbl_ViewInduction_ProjectStarts').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": false,
			"autoWidth": true
		});

	<?php echo $d; ?>
		$('.tabHyperlink').click(function(){
			var selected_tab = $(this).attr('href');
			selected_tab = selected_tab.replace('#', '');
			var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=saveTabInSession&selected_tab='+selected_tab);
		});

		$("#tbl_ViewInduction_ProjectStarts tbody tr td").hover(function(){
			var col = $(this).parent().children().index($(this));
			var header_text = $("#tbl_ViewInduction_ProjectStarts thead tr th").eq(col)[0].innerHTML;
			var header_text_of_first_column = $("#tbl_ViewInduction_ProjectStarts thead tr th").eq(0)[0].innerHTML;
			var surname = $('td:nth-child(9)', $(this).parents('tr')).text();
			var firstname = $('td:nth-child(10)', $(this).parents('tr')).text();
			var html = '<strong>' + firstname + ' ' + surname + '</strong><br><small>this is </small><u>' + header_text + '</u>';
			entry_onmouseover(html) ;

		},function(){
			entry_onmouseout();
		});
		$("#tbl_ViewInduction_All tbody tr td").hover(function(){
			var col = $(this).parent().children().index($(this));
			var header_text = $("#tbl_ViewInduction_ProjectStarts thead tr th").eq(col)[0].innerHTML;
			var header_text_of_first_column = $("#tbl_ViewInduction_ProjectStarts thead tr th").eq(0)[0].innerHTML;
			var surname = $('td:nth-child(9)', $(this).parents('tr')).text();
			var firstname = $('td:nth-child(10)', $(this).parents('tr')).text();
			var html = '<strong>' + firstname + ' ' + surname + '</strong><br><small>this is </small><u>' + header_text + '</u>';
			entry_onmouseover(html) ;

		},function(){
			entry_onmouseout();
		});

		$('#right-button').click(function() {
			event.preventDefault();
			$('#content').animate({
				scrollLeft: "+=300px"
			}, "slow");
		});

		$('#left-button').click(function() {
			event.preventDefault();
			$('#content').animate({
				scrollLeft: "-=300px"
			}, "slow");
		});

	});

	function entry_onmouseover(header_text)
	{
		var tooltip = document.getElementById('tooltip');
		var content = document.getElementById('tooltip_content');
		content.innerHTML = header_text;
		tooltip.style.display = "block";
	}

	function entry_onmouseout()
	{
		var tooltip = document.getElementById('tooltip');
		tooltip.style.display = "none";
	}

	function loadQuickForm(inductee_learner_id)
	{
		$('#divDetail').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
		var client = ajaxRequest('do.php?_action=induction_home&subaction=loadQuickForm&inductee_learner_id='+inductee_learner_id, null, null, loadQuickFormCallback);
	}

	function loadQuickFormCallback(client)
	{
		$('#divDetail').html(client.responseText);
		$( "#input_date_moved_from_grey_section" ).datepicker({ dateFormat: 'dd/mm/yy' });
	}

	function saveQuickSaveInduction()
	{
		var myForm = document.forms["frmQuickSaveInduction"];
		if(validateForm(myForm) == false)
		{
			return false;
		}
		if(myForm.elements['work_email'].value != '' && validateEmail(myForm.elements['work_email'].value) == false)
		{
			alert('Incorrect format for learner\'s work email address');
			return false;
		}
		$('#divDetail').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
		var client = ajaxPostForm(myForm, saveQuickSaveInductionCallback);
	}

	function saveQuickSaveInductionCallback(req)
	{
		if(req != null && req.responseText == 'success')
		{
			alert('Your changes have been saved successfully.');
			window.location.reload();
		}
		else
		{
			alert(req.responseText);
		}
	}

	function showNotes(induction_id, note_type)
	{
		if(induction_id == '')
			return;

		var postData = 'do.php?_action=ajax_tracking'
				+ '&induction_id=' + encodeURIComponent(induction_id)
				+ '&subaction=' + encodeURIComponent("getInductionNotes")
				+ '&note_type=' + encodeURIComponent(note_type)
			;

		var req = ajaxRequest(postData);
		$("<div></div>").html(req.responseText).dialog({
			id: "dlg_lrs_result",
			title: "Saved Comments",
			resizable: false,
			modal: true,
			width: 750,
			height: 500,

			buttons: {
				'Close': function() {$(this).dialog('close');}
			}
		});
	}

	function resetFilters()
	{
		var form = document.forms["filters"];
		resetViewFilters(form);

		if ( $('#grid_filter_contract').length )
		{
			var grid = document.getElementById('grid_filter_contract');
			grid.resetGridToDefault();
		}
	}
</script>

<div id="tooltip" style="position: fixed;display: none;"><div id="tooltip_content"></div></div>

</body>
</html>