<?php /* @var $vo Organisation*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>View Employer</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>

	<!-- CSS for TabView -->
	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

	<!-- Dependency source files -->
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

	<!-- Page-specific script -->
	<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>


	<script type="text/javascript">
		function deleteRecord()
		{
			if(window.confirm("Delete this employer?"))
			{
				window.location.replace('do.php?_action=delete_employer&id=<?php echo $vo->id; ?>');
			}
		}

		function treeInit() {
			myTabs = new YAHOO.widget.TabView("demo");
			myTabs = new YAHOO.widget.TabView("post_progression_inner_tabs");
		}

		YAHOO.util.Event.onDOMReady(treeInit);

		function save()
		{

			var myForm = document.forms[0];
			if (validateForm(myForm) == false)
			{
				return false;
			}

			myForm.submit();
		}

		function uploadFile()
		{
			var myForm = document.forms["frmUploadFile"];
			myForm.submit();
		}

		<?php if(DB_NAME == "am_demo" and isset($line_manager)){?>
        function sendEmailEmployerAgreement(usertype,review_id,tr_id)
        {
            line_manager_name = <?php echo "'" . addslashes((string)$line_manager->contact_name) . "'"; ?>;
            line_manager_email = <?php echo "'" . addslashes((string)$line_manager->contact_email) . "'"; ?>;
            own_work_email = <?php echo "'" . addslashes((string)$_SESSION['user']->work_email) . "'"; ?>;

            if(own_work_email=='')
            {
                custom_alert_OK_only("Please enter your work email address in your user record before sending this email");
                return false;
            }
            if(usertype=='3')
            {
                if(line_manager_email=='')
                {
                    custom_alert_OK_only("Employer's email address not found!");
                    return false;
                }
                else
                {
                    confirmation("Do you want to email this form to the line manager " + line_manager_name + " to email address [" + line_manager_email + "]?").then(function (answer) {
                        var ansbool = (String(answer) == "true");
                        if(ansbool){
                            var client = ajaxRequest('do.php?_action=mail_employer_agreement&source='+usertype+'&tr_id='+tr_id+'&review_id='+review_id);
                            if(client != null && client.responseText == 'true')
                                custom_alert_OK_only('Email has been sent');
                            else
                                custom_alert_OK_only('Operation aborted, please try again.');
                        }
                    });
                }
            }
        }


        function custom_alert_OK_only(output_msg, title_msg)
        {
            if (!title_msg)
                title_msg = 'Alert';

            if (!output_msg)
                output_msg = 'No Message to Display.';

            $("<div></div>").html(output_msg).dialog({
                title: title_msg,
                resizable: false,
                modal: true,
                buttons: {
                    "OK": function()
                    {
                        $( this ).dialog( "close" );
                    }
                }
            });
        }


        function confirmation(question) {
            var defer = $.Deferred();
            $('<div></div>')
                    .html(question)
                    .dialog({
                        autoOpen: true,
                        modal: true,
                        title: 'Confirmation',
                        buttons: {
                            "Yes": function () {
                                defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
                                $(this).dialog("close");
                            },
                            "No": function () {
                                defer.resolve("false");//this text 'false' can be anything. But for this usage, it should be true or false.
                                $(this).dialog("close");
                            }
                        },
                        close: function () {
                            //$(this).remove();
                            $(this).dialog('destroy').remove()
                        }
                    });
            return defer.promise();
        }
		<?php } ?>

	$(function(){
			$( "#modalTags" ).dialog({
				autoOpen: false,
				title: "Attach tag to the employer record",
				width: 700,
				height: 350,
				buttons: {
					"Assign": function() {
						$("#tagValidation").hide();
						let existingTag = $("form#frmTags #tag").val();    
						let newTag = $("form#frmTags #new_tag").val();
						if(existingTag == '' && newTag.trim() == '')
						{
							$("#tagValidation").fadeIn(500).fadeOut(400).fadeIn(300).fadeOut(200).fadeIn(100).show();
							return false;
						}
						else
						{
							$("form#frmTags").submit();
						}
					},
					"Close": function() { $( this ).dialog( "close" ); }
				}
			});
		});
	</script>

	<style type="text/css">
		fieldset {
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}

		legend {
			font-size: 12px;
			color: #15428B;
			font-weight: 900;
		}
	</style>
	<script type="text/javascript">
		window.onload = function () {
			$(".loading-gif").hide();
		}
	</script>
</head>
<body class="yui-skin-sam">
<div class="banner">
	<div class="Title"><?php echo $vo->legal_name; ?></div>
	<div class="ButtonBar">
		<button onclick="window.location.replace('<?php echo $_SESSION['bc']->getPrevious(); ?>');">Close</button>
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_SALESPERSON || $_SESSION['user']->type == User::TYPE_MANAGER || ($_SESSION['user']->type==User::TYPE_VERIFIER && DB_NAME=='am_mcq')) { ?>
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_employer&edit=1');">Edit</button>
		<?php } ?>
		<?php if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && $_SESSION['user']->isAdmin()){?>
		<button onclick="deleteRecord();">Delete</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div class="loading-gif" id="progress">
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif"/>
</div>

<div id="demo" class="yui-navset">
	<ul class="yui-nav">
		<li <?php echo $tab1; ?>><a href="#tab1"><em>Details</em></a></li>
		<li <?php echo $tab2; ?>><a href="#tab2"><em>Locations</em></a></li>
		<li <?php echo $tab3; ?>><a href="#tab3"><em>CRM Notes</em></a></li>
		<li <?php echo $tab4; ?>><a href="#tab4"><em>Learners</em></a></li>
		<li <?php echo $tab5; ?>><a href="#tab5"><em>File Repository</em></a></li>
		<li <?php echo $tab6; ?>><a href="#tab6"><em>System Users</em></a></li>
		<li <?php echo $tab7; ?>><a href="#tab7"><em>Contacts</em></a></li>
		<li <?php echo $tab8; ?>><a href="#tab8"><em>Vacancies</em></a></li>
        <li <?php echo $tab9; ?>><a href="#tab9"><em>Digital Account</em></a></li>

        <?php if(DB_NAME=='am_demo'){?> <li <?php echo $tab10; ?>><a href="#tab10"><em>Agreement</em></a></li> <?php } ?>

	</ul>
	<div class="yui-content" style='background: white;border-radius: 12px;border-width:1px;border-style:solid;border-color:#00A4E4;'>

		<div id="tab1">
			<h3>Basic Information</h3>

			<div style="float: right; margin-right: 35%; width: 370px;">
				<span class="fieldLabel">Tags</span> &nbsp; &nbsp; &nbsp; <span class="button" onclick="$('#modalTags').dialog('open');" >Assign Tags</span><br>
				<?php
				$employertags = DAO::getResultset($link, "SELECT tags.id, tags.name FROM tags INNER JOIN taggables ON tags.id = taggables.tag_id WHERE taggables.taggable_type = 'Employer' AND taggables.taggable_id = '{$vo->id}' ORDER BY tags.name", DAO::FETCH_ASSOC);
				if( count($employertags) == 0 )
				{
					echo '<p>No tags have been attached to the training record.</p>';
				}
				else
				{
					foreach( $employertags AS $employertag )
					{
						echo '<div style="background-color: green; color: white; font-weight: bold; padding: 5px; border-radius: 5px; display: inline-block; margin: 2px;">';
						echo '<span>' . $employertag['name'] . ' &nbsp; &nbsp;</span>';
						echo '<span title="Click to detach tag" style="cursor: pointer;" onclick="detach_tag(\'' . $employertag['id'] . '\', \'' . $vo->id . '\', \'Employer\');">X</span>';
						echo '</div>';
					}
				}
				?>
			</div>
			<div class="modal fade" id="modalTags" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none">
				<div class="modal-dialog">
					<div class="modal-content">
						<form class="form-horizontal" method="post" name="frmTags" id="frmTags" method="post" action="do.php?_action=assign_tags">
							<input type="hidden" name="formName" value="frmTags" />
							<input type="hidden" name="taggable_type" value="Employer" />
							<input type="hidden" name="taggable_id" value="<?php echo $vo->id; ?>" />

							<table style="margin-left:10px; width: 100%;" cellspacing="4" cellpadding="4">
								<col width="150" /><col />
								<tr>
									<td class="fieldLabel_optional">Select Tag:</td>
									<td>
										<?php 
										$tags_sql = "SELECT id, `name`, `type` FROM tags WHERE tags.type IN ('Employer') ORDER BY `type`, `name`";
										echo HTML::select('tag', DAO::getResultset($link, $tags_sql), '', true); 
										?>
									</td>
								</tr>
								<tr>
									<td colspan="2">----------------------- OR -----------------------</td>
								</tr>
								<tr>
									<td class="fieldLabel_optional">Enter Tag:</td>
									<td><input type="text" class="optional" name="new_tag" id="new_tag" maxlength="70" size="50" autocomplete="0" /></td>
								</tr>
							</table>
							<p id="tagValidation" style="color: red; text-align: center; display: none;">Please select tag from list or enter new tag!</p>                
						</form>
					</div>
				</div>
			</div>

			<table border="0" cellspacing="4" cellpadding="6">
				<tr><td class="fieldLabel">Legal name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name); ?></td></tr>
				<tr><td class="fieldLabel">Trading name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->trading_name); ?></td></tr>
				<tr><td class="fieldLabel">Abbreviation:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->short_name); ?></td></tr>
				<tr><td class="fieldLabel">Sector:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$sector); ?></td></tr>
				<tr><td class="fieldLabel">EDRS No:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->edrs); ?></td></tr>
				<tr><td class="fieldLabel">Retailer code:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->retailer_code); ?></td></tr>
				<tr><td class="fieldLabel">Size:</td><td class="fieldValue"><?php echo DAO::getSingleValue($link, "select description from lookup_employer_size where code = '$vo->code'"); ?></td></tr>
				<tr><td class="fieldLabel">District:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->district); ?></td></tr>
				<tr>
					<td class="fieldLabel">Group Employer:</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$group_employer); ?></td>
				</tr>
				<tr><td class="fieldLabel">Company Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->company_number); ?></td></tr>
				<tr><td class="fieldLabel">VAT Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vat_number); ?></td></tr>
				<tr>
					<td class="fieldLabel">Account Manager:</td>
					<td class="fieldValue"><?php echo DAO::getSingleValue($link,"select CONCAT(firstnames,' ',surname) from users where username = '$vo->creator'"); ?></td>
				</tr>
				<tr><td class="fieldLabel">Lead Referral:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->lead_referral); ?></td></tr>
				<tr><td class="fieldLabel">ONA:</td><td class="fieldValue"><?php if(is_null($vo->ono)) echo '';else echo $vo->ono? 'Yes':'No'; ?></td></tr>
				<tr><td class="fieldLabel">Number of on-site Employees:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->site_employees); ?></td></tr>
                <tr><td class="fieldLabel">Monthly Levy Amount:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->levy); ?></td></tr>
				<?php if(DB_NAME == "am_superdrug"){ ?>
				<tr>
					<td class="fieldLabel">Salary Rate:</td>
					<?php
					$salary_rate_label = "";
					switch($vo->salary_rate)
					{
						case 1:
							$salary_rate_label = 'Grade 1';
							break;
						case 2:
							$salary_rate_label = 'Grade 2';
							break;
						case 3:
							$salary_rate_label = 'Grade 3';
							break;
						default:
							$salary_rate_label = "";
							break;
					}
					?>
					<td class="fieldValue"><?php echo $salary_rate_label; ?></td>
				</tr>
				<?php } ?>
			</table>
		</div>

		<div id="tab2">
			<h3>Locations</h3>
			<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_SALESPERSON || $_SESSION['user']->type == User::TYPE_MANAGER || (DB_NAME=='am_mcq' && $_SESSION['user']->type == TYPE_VERIFIER)) { ?>
			<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=<?php echo "employer"; ?>'"> Add New</span>
			<?php } ?>
			<?php $this->renderLocations($link, $vo); ?>
		</div>

		<div id="tab3">
			<h3>Notes</h3>
			<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type== User::TYPE_SALESPERSON || $_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ASSESSOR || $_SESSION['user']->type == User::TYPE_VERIFIER || ($_SESSION['user']->type== User::TYPE_ADMIN && $_SESSION['user']->org->organisation_type != Organisation::TYPE_EMPLOYER) || (DB_NAME=='am_baltic' && $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER)) { ?>
			<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_note&mode=new&organisations_id=<?php echo $vo->id; ?>&organisation_type=read_workplace'"> Add New</span>
			<?php } ?>
			<?php $crmNotes->render($link,'read_employer'); ?>
		</div>

		<div id="tab4">
			<h3>Learners</h3>
			<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_SALESPERSON || $_SESSION['user']->type == User::TYPE_MANAGER) { ?>
			<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Learner"; ?>&people_type=<?php echo 5; ?>'"> Add New</span>
			<?php } $this->renderLearners($link, $vo);  ?>
		</div>

		<div id="tab5">
			<h3>File Repository</h3>
			<div>
				<form name="frmUploadFile" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository" ENCTYPE="multipart/form-data">
					<input type="hidden" name="_action" value="save_employer_repository" />
					<input type="hidden" name="emp_id" value="<?php echo $vo->id;?>" />

					<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
						<col width="150" />
						<tr>
							<td class="fieldLabel_compulsory">File to upload:</td>
							<td><input class="compulsory" type="file" name="uploaded_employer_file" />&nbsp;
								<span id="uploadFileButton" class="button" onclick="uploadFile()">&nbsp;Upload&nbsp;</span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<?php $this->renderFileRepository($vo);?>
		</div>

		<div id="tab6">
			<h3>System Users</h3>
			<?php if($_SESSION['user']->isAdmin()) { ?>
			<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Admin"; ?>&people_type=<?php echo 1; ?> '"> Add administrator </span>
			<?php if(DB_NAME=="am_siemens" || DB_NAME == "am_siemens_demo") {?>
				<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Assessor"; ?>&people_type=<?php echo 3; ?> '"> Add assessor </span>
			<?php } ?>
			<?php if(SystemConfig::getEntityValue($link, 'module_recruitment_v2')) {?>
				<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Business Resource Manager"; ?>&people_type=<?php echo User::TYPE_STORE_MANAGER; ?> '"> Add Store Manager </span>
			<?php } ?>
			<p style="margin-bottom: 15px;"></p>
			<?php } $this->renderPersonnel($link, $vo);?>
		</div>

		<div id="tab7">
			<h3>Contacts</h3>
			<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_contact&org_type=employer&org_id=<?php echo $vo->id; ?>'"> Add New</span>
			<?php
			echo $viewCRMContacts->render($link);
			?>
		</div>

		<div id="tab8">
			<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_BUSINESS_RESOURCE_MANAGER || $_SESSION['user']->type == User::TYPE_SALESPERSON || $_SESSION['user']->type == User::TYPE_MANAGER) { ?>
			<h3>Vacancies</h3>
			<?php
			if ( sizeof($vo->getLocations($link)) <= 0 ) {
				echo 'No Vacancies can be set up as there are no locations for this organisation';
			}
			else {
				echo '<span style="margin-bottom: 15px;" class="button" onclick="window.location.href=\'do.php?_action=rec_edit_vacancy&selected_tab=tab8&employer_id='.$vo->id.'\'"> Add New</span>';
				$vacancies->render($link);
			}
			?>
			<?php } ?>
		</div>

        <div id="tab9">
            <h3>Digital Account</h3>
            <?php
            $months = Array("May 2017","June 2017","July 2017","August 2017","September 2017","October 2017","November 2017","December 2017","January 2018","February 2018","March 2018","April 2018","May 2018","June 2018","July 2018");
            $total_on_programme = Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            $total_balancing = Array();
            $total_completion = Array();
            $total_1618provider = Array();
            $total_1618employer = Array();

            $st = $link->query("select * from tr where employer_id = '$id' and id in (select tr_id from ilr where locate('<FundModel>36',ilr)>0)");
            if($st)
            {
                while($row = $st->fetch())
                {
                    $tr_id = $row['id'];
                    $contract_id = $row['contract_id'];
                    $current_submission = DAO::getSingleValue($link, "SELECT submission FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = '$tr_id' ORDER BY contract_year DESC, submission DESC LIMIT 1");

                    require_once('./lib/funding/FundingCore.php');
                    require_once('./lib/funding/PeriodLookup.php');
                    require_once('./lib/funding/LearnerFunding.php');
                    require_once('./lib/funding/FundingPeriod.php');
                    require_once('./lib/funding/FundingPrediction.php');
                    require_once('./lib/funding/FundingPredictionPeriod.php');

                    if(!isset($_REQUEST['output']))
                    {
                        $_REQUEST['output'] = 'HTML';
                    }

                    $predictions = new FundingPredictionPeriod($link, $contract_id, 25, '', '', '', $current_submission, '', $tr_id);
                    $data = $predictions->get_learnerdata();
                    for($i = 0; $i<sizeof($data); $i++)
                    {
                        $total_on_programme[0]+=$data[$i]['P1_total'];
                        $total_on_programme[1]+=$data[$i]['P2_total'];
                        $total_on_programme[2]+=$data[$i]['P3_total'];
                        $total_on_programme[3]+=$data[$i]['P4_total'];
                        $total_on_programme[4]+=$data[$i]['P5_total'];
                        $total_on_programme[5]+=$data[$i]['P6_total'];
                        $total_on_programme[6]+=$data[$i]['P7_total'];
                        $total_on_programme[7]+=$data[$i]['P8_total'];
                        $total_on_programme[8]+=$data[$i]['P9_total'];
                        $total_on_programme[9]+=$data[$i]['P10_total'];
                        $total_on_programme[10]+=$data[$i]['P11_total'];
                        $total_on_programme[11]+=$data[$i]['P12_total'];
                        $total_on_programme[12]+=$data[$i]['P13_total'];
                        $total_on_programme[13]+=$data[$i]['P14_total'];
                        $total_on_programme[14]+=$data[$i]['P15_total'];
                        $total_on_programme[15]+=$data[$i]['P16_total'];
                        $total_on_programme[16]+=$data[$i]['P17_total'];
                        $total_on_programme[17]+=$data[$i]['P18_total'];
                        $total_on_programme[18]+=$data[$i]['P19_total'];
                        $total_on_programme[19]+=$data[$i]['P20_total'];
                        $total_on_programme[20]+=$data[$i]['P21_total'];
                        $total_on_programme[21]+=$data[$i]['P22_total'];
                        $total_on_programme[22]+=$data[$i]['P23_total'];
                        $total_on_programme[23]+=$data[$i]['P24_total'];
                    }
                }
            }

            echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th>&nbsp;</th><th>Month</th><th>Monthly Levy Amount</th><th>Provider Payment</th><th>Balance</th></tr></thead>';
            $textStyle = '';
            $balance = 0;
            for($i=0; $i<sizeof($months);$i++)
            {
                $balance+=$vo->levy-$total_on_programme[$i+9];
                echo '<tr>';
                echo '<td style="' . $textStyle . '" align="left">' . HTML::cell("") . "</td>";
                echo '<td style="' . $textStyle . '" align="left">' . HTML::cell($months[$i]) . "</td>";
                echo '<td style="' . $textStyle . '" align="left">&pound;' . HTML::cell(sprintf('%.2f',$vo->levy)) . "</td>";
                echo '<td style="' . $textStyle . '" align="left">&pound;' . HTML::cell(sprintf('%.2f',$total_on_programme[$i+9])) . "</td>";
                echo '<td style="' . $textStyle . '" align="left">&pound;' . HTML::cell(sprintf('%.2f',$balance)) . "</td>";
                echo '</tr>';
            }
            echo '</table>';
            ?>
            </div>
        </div>

        <?php if(DB_NAME=='am_demo'){?>
        <div id="tab10">
            <h3>Employer Agreement</h3>
            <span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_employer_agreement&org_type=employer&org_id=<?php echo $vo->id; ?>'"> Add New</span>
            <?php
            echo $viewEmployerAgreement->render($link);
            ?>
        </div>
        <?php } ?>

	</div>
</div>

</body>
</html>
