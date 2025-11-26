<?php /* @var $pot_vo TrainingRecord */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Training Record</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

    <link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
    <script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
    <script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
    <script language="JavaScript" src="/common.js"></script>


    <script language="JavaScript">

        function numbersonly(myfield, e, dec)
        {
            var key;
            var keychar;

            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;
            keychar = String.fromCharCode(key);

// control keys
            if ((key==null) || (key==0) || (key==8) ||
                (key==9) || (key==13) || (key==27) )
                return true;

// numbers
            else if ((("0123456789").indexOf(keychar) > -1))
                return true;

// decimal point jump
            else if (dec && (keychar == "."))
            {
                myfield.form.elements[dec].focus();
                return false;
            }
            else
                return false;
        }

        function status_code_onchange(e)
        {
            var myForm = document.forms[0];
            var closureLabelCell = document.getElementById('closureDateLabelCell');
            var closureFieldCell = document.getElementById('closureDateFieldCell');
            var closureField = myForm.elements['closure_date'];

            // If status code is set to active..
            if(e.value == 1)
            {
                // clear the closure date
                closureField.value = '';

                closureLabelCell.className = "fieldLabel_optional";
                closureField.className = "optional";



                //closureLabelCell.style.visibility = "hidden";
                //closureFieldCell.style.visibility = "hidden";
            }
            else
            {
                // Default closure date to today's date
                if(closureField.value == '')
                {
                    var d = new Date();
                    closureField.value = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
                }

                closureLabelCell.className = "fieldLabel_compulsory";
                closureField.className = "compulsory";

                document.getElementById('reasonsForLeaving').style.display = 'Block';

                //closureLabelCell.style.visibility = "visible";
                //closureFieldCell.style.visibility = "visible";
            }
        }


        function populateAddress(url, form, elementPrefix)
        {
            if (elementPrefix == null) {
                elementPrefix = '';
            }

            var xml = ajaxRequest(url);
            var $xml = $(xml.responseXML);

            $('[name="' + elementPrefix + 'address_line_1' + '"]', form).val($xml.find('address_line_1').text());
            $('[name="' + elementPrefix + 'address_line_2' + '"]', form).val($xml.find('address_line_2').text());
            $('[name="' + elementPrefix + 'address_line_3' + '"]', form).val($xml.find('address_line_3').text());
            $('[name="' + elementPrefix + 'address_line_4' + '"]', form).val($xml.find('address_line_4').text());
            $('[name="' + elementPrefix + 'postcode' + '"]', form).val($xml.find('postcode').text());

            $('[name="' + elementPrefix + 'telephone' + '"]', form).val($xml.find('telephone').text());
            $('[name="' + elementPrefix + 'mobile' + '"]', form).val($xml.find('mobile').text());
            $('[name="' + elementPrefix + 'fax' + '"]', form).val($xml.find('fax').text());
            $('[name="' + elementPrefix + 'email' + '"]', form).val($xml.find('email').text());

            var envelope = document.getElementById(elementPrefix + '_envelope');
            if (envelope) {
                envelope.update(form, elementPrefix);
            }
        }

        /**
         * Helper to populateAddress()
         */
        function extractAddressField(address, fieldName)
        {
            var elements = address.getElementsByTagName(fieldName);
            if(elements.length > 0)
            {
                if(elements[0].firstChild)
                {
                    return elements[0].firstChild.nodeValue;
                }
            }

            return '';
        }


        function employer_id_onchange(employer_id)
        {
            var form = employer_id.form;
            var employer_location_id = form.elements['employer_location_id'];

            if(employer_id.value != '')
            {
                ajaxPopulateSelect(employer_location_id, 'do.php?_action=ajax_load_location_dropdown&org_id=' + employer_id.value);
            }
            else
            {
                emptySelectElement(employer_location_id);
            }
        }


        function provider_id_onchange(provider_id)
        {
            var form = provider_id.form;
            var provider_location_id = form.elements['provider_location_id'];

            if(provider_id.value != '')
            {
                ajaxPopulateSelect(provider_location_id, 'do.php?_action=ajax_load_location_dropdown&org_id=' + provider_id.value);
            }
            else
            {
                emptySelectElement(provider_location_id);
            }
        }


        function employer_location_id_onchange(loc)
        {
            if(loc.value != '')
            {
                populateAddress('do.php?_action=ajax_load_organisation_address&loc_id=' + loc.value, document.forms[0], 'work_');
            }
        }

        function provider_location_id_onchange(loc)
        {
            if(loc.value != '')
            {
                populateAddress('do.php?_action=ajax_load_organisation_address&loc_id=' + loc.value, document.forms[0], 'provider_');
            }
        }

        function saveReasonsForLeaving()
        {
            document.getElementById('reasonsDiv').style.display='None';
            postData = 'reason=' + document.getElementById('reason').value;
            var client = ajaxRequest('do.php?_action=ajax_save_reasons_for_leaving', postData);

            document.getElementById('reason').value = '';
            var form = document.forms[0];
            var reasonsForLeaving = form.elements['reasons_for_leaving'];
            ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=ajax_load_reasons_for_leaving_dropdown');
        }

        function SaveNewACM()
        {
            document.getElementById('ACMDiv').style.display='None';
            postData = 'acm=' + document.getElementById('acm_new').value;
            var client = ajaxRequest('do.php?_action=ajax_save_new_acm', postData);

            document.getElementById('acm_new').value = '';
            var form = document.forms[0];
            var acm_list = form.elements['acm'];
            ajaxPopulateSelect(acm_list, 'do.php?_action=ajax_load_acm_dropdown');
        }

        function SaveNewIVLineManager()
        {
            document.getElementById('IVLineManagerDiv').style.display='None';
            postData = 'iv_line_manager=' + document.getElementById('iv_line_manager_new').value;
            var client = ajaxRequest('do.php?_action=ajax_save_new_iv_line_manager', postData);

            document.getElementById('iv_line_manager_new').value = '';
            var form = document.forms[0];
            var iv_line_manager_list = form.elements['iv_line_manager'];
            ajaxPopulateSelect(iv_line_manager_list, 'do.php?_action=ajax_load_iv_line_manager_dropdown');
        }

        function save()
        {
            var showWarningForZProgEmptyEndDate = '<?php echo $showWarningForZProgEmptyEndDate; ?>';


            var myForm = document.forms[0];

            // General validation
            if(validateForm(myForm) == false)
                return false;

            // More specific validation
            var status = myForm.status_code.value;
            var closure_date = myForm.closure_date.value;

            if(status == 1 && closure_date != '')
            {
                myForm.closure_date.value = '';
            }

            if( (status == 2 || status == 3) && closure_date == '')
            {
                var d = new Date();
                alert("The training status you have chosen requires that you provide a closure date for the record.");
                myForm.closure_date.focus();
                myForm.closure_date.value = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
                return false;
            }

            if(showWarningForZProgEmptyEndDate == 1 && closure_date != '')
            {
                var c = confirm("One or more learning aims are continuing. Do you want to continue?");
                if(!c)
                    return;
            }

            myForm.submit();

            /*var req = ajaxPostForm(myForm);
               if(req != null)
               {
                   window.location.replace('do.php?_action=read_training_record&id=' + req.responseText);
               }
               */

        }


    </script>
</head>


<body>
<div class="banner">
    <div class="Title">Training Record</div>
    <div class="ButtonBar">
        <?php if( ( in_array($_SESSION['user']->username, ['cturnbull1', 'phutchinson', 'hgibson1', 'dparks', 'leahmiller', 'atodd123', 'scooper9', 'bmilburn']) && DB_NAME == 'am_baltic') || $_SESSION['user']->type!=12 || $_SESSION['user']->isAdmin()){?>
            <button	onclick="save();">Save</button>
        <?php }?>
        <button onclick="if(confirm('Are you sure?'))<?php echo $js_cancel; ?>">Cancel</button>
    </div>
    <div class="ActionIconBar">

    </div>
</div>
<?php $_SESSION['bc']->render($link); ?>
<h3>Dates &amp; Status</h3>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" ENCTYPE="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$pot_vo->id); ?>" />
    <?php if($_SESSION['user']->username == 'dparks' && DB_NAME == 'am_baltic') { ?>
        <input type="hidden" name="_action" value="save_training_record_customized" />
    <?php } else {?>
        <input type="hidden" name="_action" value="save_training_record" />
    <?php } ?>
    <input type="hidden" name="tr_id" value="<?php echo htmlspecialchars((string)$id); ?>"/>
    <input type="hidden" name="username" value="<?php echo $pot_vo->username; ?>" />
    <table style="margin-left:10px" cellspacing="4" cellpadding="4">
        <col width="150" /><col />
        <tr>
            <td class="fieldLabel_compulsory">Record Status:</td>
            <td><?php echo HTML::select('status_code', $status_select, $pot_vo->status_code, false, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Start Date:</td>
            <td><?php echo HTML::datebox('start_date', $pot_vo->start_date, true) ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Projected End Date:</td>
            <td><?php echo HTML::datebox('target_date', $pot_vo->target_date, false) ?></td>
        </tr>
        <tr>
            <td id="closureDateLabelCell" class="fieldLabel_optional">Actual End Date:</td>
            <td id="closureDateFieldCell"><?php echo HTML::datebox('closure_date', $pot_vo->closure_date) ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Planned EPA Date:</td>
            <td><?php echo HTML::datebox('planned_epa_date', $pot_vo->planned_epa_date) ?></td>
        </tr>
        <tr>
            <td id="closureDateLabelCell" class="fieldLabel_optional">Entry End Date:</td>
            <td id="closureDateFieldCell"><?php echo HTML::datebox('marked_date', $pot_vo->marked_date) ?></td>
        </tr>
        <?php if(DB_NAME=='am_edudo') { ?>
            <tr>
                <td class="fieldLabel_optional">TR01 Received:</td>
                <td><?php echo HTML::datebox('tr_01_received', $pot_vo->tr_01_received); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Added To Issues List:</td>
                <td><?php echo HTML::datebox('added_to_issues', $pot_vo->added_to_issues); ?></td>
            </tr>
        <?php } ?>
        <?php if(DB_NAME=='am_pera') { ?>
            <tr>
                <td class="fieldLabel_optional">Reason past planned date:</td>
                <td><?php echo HTML::select('reason_unfunded', $reasons_unfunded_dropdown, $pot_vo->reason_unfunded, true, false); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td id="closureDateLabelCell" class="fieldLabel_optional">Reasons for leaving:</td>
            <td>
                <?php echo HTML::select('reasons_for_leaving', $reasons_for_leaving_dropdown, $pot_vo->reasons_for_leaving, true, false); ?>
                <span class="button" onclick="document.getElementById('reasonsDiv').style.display='block'"> New </span>
            </td>
        </tr>
        <tr id="reasonsDiv" style="Display: None;">
            <td> Reason for Leaving</td>
            <td><input class="optional" type="text" id="reason" value="" size="40" maxlength="40" /></td>
            <td><span class="button" onclick="saveReasonsForLeaving();"> Save </span></td>
        </tr>
        <?php if(SOURCE_LOCAL || DB_NAME == 'am_ligauk') {
            $yes_no = array(
                array('0', 'No', ''),
                array('1', 'Yes', '')
            );
            ?>
            <tr>
                <td class="fieldLabel_optional">At Risk</td>
                <td class="optional"><?php echo HTML::select('at_risk', $yes_no, $pot_vo->at_risk, false); ?></td>
            </tr>
        <?php } ?>
        <?php
        if (DB_NAME=='am_lead' || DB_NAME=='am_lmpqswift' || DB_NAME=='am_demo' ) {
            // make non compulsory re 30/06/2011
            echo '<tr><td class="fieldLabel_optional">Archive Box Number</td>';
            echo '<td><input class="optional" type="text" id="archive_box" name="archive_box" value="'. htmlspecialchars((string)$pot_vo->archive_box) . '" size="4" maxlength="4"/></td>';
            echo '<tr><td class="fieldLabel_optional">Destruction Date</td>';
            echo '<td>' . HTML::datebox("destruction_date", $pot_vo->destruction_date, false) . '</td>';
        }
        if (DB_NAME=='am_skillspoint' || DB_NAME=='ams') {
            // make non compulsory re 30/06/2011
            echo '<tr><td class="fieldLabel_optional">Revised Planned End Date</td>';
            echo '<td>' . HTML::datebox("revised_planned", $pot_vo->revised_planned, false) . '</td>';
        }
        if (DB_NAME=='am_pathway' || DB_NAME=='am_demo') {
            echo '<tr><td class="fieldLabel_optional">Portfolio Location</td>';
            //$categories = array("0" => "Office", "1" => "Assessor");
            $categories = array("0" => "Office", "1" => "Assessor", "2"=>"IV", "3"=>"EV - RPVD", "4"=>"EV - HSC, CS, CSS", "5"=>"EV - C&G", "6"=>"IV - 1", "7"=>"IV - 2",
                "8"=>"IV - 3", "9"=>"IV - 4", "10"=>"IV - 5", "11"=>"IV - 6", "12"=>"IV - 7", "13"=>"IV - 8", "14"=>"IV - 9", "15"=>"ARCHIVE", "16"=>"Smart Assessor");
            echo '<td>' . HTML::select('archive_box', $categories, $pot_vo->archive_box, true) . "</td></tr>";
            echo '<tr></tr><td class="fieldLabel_optional">Portfolio In Date:</td>';
            echo '<td>' . HTML::datebox("portfolio_in_date", $pot_vo->portfolio_in_date, false) . '</td></tr>';
            echo '<tr></tr><td class="fieldLabel_optional">Portfolio IV Date:</td>';
            echo '<td>' . HTML::datebox("portfolio_iv_date", $pot_vo->portfolio_iv_date, false) . '</td></tr>';
            echo '<tr><td class="fieldLabel_optional">ACE Sign Date:</td>';
            echo '<td>' . HTML::datebox("ace_sign_date", $pot_vo->ace_sign_date, false) . '</td></tr>';
            echo '<tr><td class="fieldLabel_optional">Notification Status:</td>';
            echo '<td>' . HTML::select("notification_status", DAO::getResultset($link, "SELECT id, description, null FROM lookup_notification_status ORDER BY description "), $pot_vo->notification_status,true) . '</td></tr>';
        } ?>
        <tr>
            <td class="fieldLabel_optional">OTJ Hours</td>
            <td class="optional">
                <input type="text" class="optional" name="otj_hours" id="otj_hours" value="<?php echo $pot_vo->otj_hours; ?>" onkeypress="return numbersonly(this);" maxlength="4" />&nbsp;<i class="text-info">(hours)</i>
            </td>
        </tr>
	<tr>
            <td class="fieldLabel_optional">Recognise Prior Learning</td>
            <td class="optional"><?php echo HTML::select('rpl', [[0, 'No'], [1, 'Yes']], $pot_vo->rpl, true); ?></td>
        </tr>
        <?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])){ ?>
        <tr>
            <td class="fieldLabel_optional">Original Start Date</td>
            <td class="optional"><?php echo HTML::datebox('original_start_date', $pot_vo->original_start_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Funding to be claimed</td>
            <td class="optional"><input type="text" name="red_price" id="red_price" value = "<?php echo $pot_vo->red_price; ?>" onkeypress="return numbersonly();" maxlength="6" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Transfer Learner</td>
            <td class="optional"><?php echo HTML::checkbox('amount_transfer_learner', 1, $pot_vo->amount_transfer_learner, true, false); ?></td>
        </tr>
        <?php } else { ?>
        <tr>
            <td class="fieldLabel_optional">Duration Reduced By</td>
            <td class="optional"><input type="text" name="red_duration" id="red_duration" value = "<?php echo $pot_vo->red_duration; ?>" onkeypress="return numbersonly();" maxlength="3" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Price Reduced By</td>
            <td class="optional"><input type="text" name="red_price" id="red_price" value = "<?php echo $pot_vo->red_price; ?>" onkeypress="return numbersonly();" maxlength="6" /></td>
        </tr>
        <?php } ?>
	<?php if(DB_NAME == "am_demo"){?>
        <tr>
            <td class="fieldLabel_optional">Bootcamp Outcome</td>
            <td class="optional">
                <?php echo HTML::select('bootcamp_outcome', [
                    [1, 'New role with current employer'],
                    [2, 'New or increased responsibilities at work'],
                    [3, 'Self employment'],
                    [4, 'New employment'],
                    [5, 'No positive outcome reported'],
                ], $pot_vo->bootcamp_outcome, true); ?>
            </td>
        </tr>
        <?php } ?>
	    <?php if(DB_NAME == "am_ela"){?>
        <tr>
            <td class="fieldLabel_optional">Sales Lead</td>
            <td class="optional">
                <?php echo HTML::select('sales_lead', [
                    [1, 'Frontline'],
                    [2, 'Links Training'],
                    [3, 'MOD'],
                    [4, 'Internal ELA'],
                    [5, 'Admin Sales'],
                ], $pot_vo->sales_lead, true); ?>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">BIL/ Withdrawal Marker</td>
            <td class="optional">
                <?php echo HTML::select('bil_withdrawal', [
                    [1, 'Under consideration for BIL'],
                    [2, 'Under consideration for withdrawal'],
                ], $pot_vo->bil_withdrawal, true); ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h3>Learner</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <col width="150" /><col />

        <tr>
            <td class="fieldLabel_optional">FS Tutor:</td>
            <td><?php
                if($is_grouped && DB_NAME!='am_baltic')
                    echo HTML::select('tutor', $tutor_select, $tutor, true, false, false);
                else
                    echo HTML::select('tutor', $tutor_select, $pot_vo->tutor, true, false);
                ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Assessor:</td>
            <td><?php
                //	if($is_grouped)
                //		echo HTML::select('assessor', $assessor_select, $assessor, true, true, false);
                //	else
                $ass = ($pot_vo->assessor!='')?$pot_vo->assessor:$assessor;
                echo HTML::select('assessor', $assessor_select, $ass, true, false);
                ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">IQA:</td>
            <td><?php
                if($is_grouped)
                    echo HTML::select('verifier', $verifier_select, $verifier, true, false, false);
                else
                    echo HTML::select('verifier', $verifier_select, $pot_vo->verifier, true, false);
                ?></td>
        </tr>
        <?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo") {?>
            <tr>
                <td class="fieldLabel_optional">Coordinator:</td>
                <td>
                    <?php
                    $baltic_coordinators_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
WHERE
	users.username IN ('adavison1', 'rherdman16', 'lepearson', 'bmilburn', 'nimaxwell', 'opennington', 'ajohnson18', 'mattward1', 'sophiemayes', 'elliepearson', 'sophiegilroy', 'gslack12', 'rlumsdon', 'bkitching', 'bmyers12', 'ebarker12', 'lseline1', 'alarking', 'alarkings')
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;
                    $baltic_coordinators = DAO::getResultset($link, $baltic_coordinators_sql);
                    echo HTML::select('coordinator', $baltic_coordinators, $pot_vo->coordinator, true, false);
                    ?>
                </td>
            </tr>
        <?php } else {?>
            <tr>
                <td class="fieldLabel_optional">Apprentice Coordinator:</td>
                <td><?php
                    if($_SESSION['user']->isAdmin())
                        echo HTML::select('programme', $acoordinator_select, $pot_vo->programme, true, false);
                    else
                        echo HTML::select('programme', $acoordinator_select, $pot_vo->programme, true, false, false);
                    ?></td>
            </tr>
        <?php } ?>
        <?php if(DB_NAME=="ams" || DB_NAME=="am_demo" || DB_NAME=="am_baltic" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_hybrid" || SystemConfig::getEntityValue($link, 'module_onboarding')) {?>
            <tr>
                <td class="fieldLabel_optional">Line Manager/ Supervisor:</td>
                <td><?php echo HTML::select('crm_contact_id', $crm_contacts_dropdown, $pot_vo->crm_contact_id, true, false); ?>
                </td>
            </tr>
        <?php } ?>
        <?php if(DB_NAME=="am_baltic" || DB_NAME=="am_baltic_demo") {?>
            <tr>
                <td class="fieldLabel_optional">Account Relationship Manager:</td>
                <td>
                    <input type="text" class="compulsory" name="account_rel_manager" id="account_rel_manager" value="<?php echo $account_rel_manager; ?>" maxlength="100">
                </td>
            </tr>
        <?php } ?>

        <?php if(SystemConfig::getEntityValue($link, "workplace")){ ?>
            <tr>
                <td class="fieldLabel_compulsory">Work Experience Coordinator:</td>
                <td><?php echo HTML::select('wbcoordinator', $wbcoordinator_select, $pot_vo->wbcoordinator, true, true, false); ?></td>
            </tr>
        <?php } ?>
        <?php if(DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo"){
            $colleges_list = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = 7 ORDER BY legal_name");
            ?>
            <tr>
                <td class="fieldLabel_optional">College:</td>
                <td><?php echo HTML::select('college_id', $colleges_list, $pot_vo->college_id, true); ?></td>
            </tr>
        <?php } ?>
        <?php if(SOURCE_LOCAL || in_array(DB_NAME, ["am_lead_demo", "am_lead"])) {?>
            <tr>
                <td class="fieldLabel_compulsory">Coach:</td>
                <td><?php echo HTML::select('coach', $coaches_list, $pot_vo->coach, true); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td class="fieldLabel_compulsory">Surname:</td>
            <td><input class="compulsory" type="text" name="surname" value="<?php echo htmlspecialchars((string)$pot_vo->surname); ?>" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Firstname(s):</td>
            <td><input class="compulsory" type="text" name="firstnames" value="<?php echo htmlspecialchars((string)$pot_vo->firstnames); ?>" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Gender:</td>
            <td><?php echo HTML::select('gender', $gender_select, $pot_vo->gender, false, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">L12 - Ethnicity:</td>
            <td><?php echo HTML::select('ethnicity', $L12_dropdown, ($pot_vo->ethnicity ? $pot_vo->ethnicity:99), false, true); ?></td>
        </tr>
        <!--<tr>
	<td class="fieldLabel_compulsory">L15 - Disability or Health Problem </td>
	<td><?php /*echo HTML::select('disability', $L15_dropdown, $l15, true, false); */?></td>
</tr>
<tr>
	<td class="fieldLabel_compulsory">L16 - Learning Difficulty </td>
	<td><?php /*echo HTML::select('learning_difficulty', $L16_dropdown, $l16, true, false); */?></td>
</tr>
-->
        <?php if(DB_NAME == "am_baltic") { ?>
            <tr>
                <td class="fieldLabel_optional">Learning Difficulties/ Disability </td>
                <td><textarea name="ad_lldd" id="ad_lldd" style="width: 100%;" rows="3"><?php echo $pot_vo->ad_lldd; ?></textarea></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Support arrangements requested</td>
                <td><textarea name="ad_arrangement_req" id="ad_arrangement_req" style="width: 100%;" rows="3"><?php echo $pot_vo->ad_arrangement_req; ?></textarea></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Support arrangements agreed</td>
                <td><textarea name="ad_arrangement_agr" id="ad_arrangement_agr" style="width: 100%;" rows="3"><?php echo $pot_vo->ad_arrangement_agr; ?></textarea></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Evidence</td>
                <td><?php echo HTML::select('ad_evidence', InductionHelper::getDDLAdditionalSupportEvidence(), $pot_vo->ad_evidence, true); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td class="fieldLabel_compulsory">Contract </td>
            <td> <?php echo HTML::select('contract_id', $contract, $pot_vo->contract_id, false, false); ?></td>
        </tr>
        <?php if(DB_NAME == "am_crackerjack"){?>
        <tr>
            <td class="fieldLabel_compulsory">DOB:</td>
            <td>
                <!--<input class="optional" type="text" name="dob" id="dob" value="<?php /*if(substr(htmlspecialchars((string)$pot_vo->dob),8,2)!='')echo substr(htmlspecialchars((string)$pot_vo->dob),8,2).'/'.substr(htmlspecialchars((string)$pot_vo->dob),5,2).'/'.substr(htmlspecialchars((string)$pot_vo->dob),0,4); */?>" />-->
                <?php echo HTML::datebox('dob', $pot_vo->dob, true) ?>
            </td>
        </tr>
        <?php } else {?>
        <tr>
            <td class="fieldLabel_optional">DOB:</td>
            <td>
                <!--<input class="optional" type="text" name="dob" id="dob" value="<?php /*if(substr(htmlspecialchars((string)$pot_vo->dob),8,2)!='')echo substr(htmlspecialchars((string)$pot_vo->dob),8,2).'/'.substr(htmlspecialchars((string)$pot_vo->dob),5,2).'/'.substr(htmlspecialchars((string)$pot_vo->dob),0,4); */?>" />-->
                <?php echo HTML::datebox('dob', $pot_vo->dob, false) ?>
            </td>
        </tr>
        <?php } ?>
        <?php if(DB_NAME=="am_edudo") {?>
            <tr>
                <td class="fieldLabel_optional">Cost Code:</td>
                <td><input class="optional" type="text" name="cost_code" size="19" value="<?php echo htmlspecialchars((string)$pot_vo->cost_code); ?>" /></td>
            </tr>
        <?php } ?>
        <?php if(DB_NAME=="am_doncaster") {
            $project_code_list = DAO::getResultset($link, "SELECT DISTINCT upi, upi AS description, NULL FROM tr WHERE upi IS NOT NULL AND upi != '';");
            ?>
            <tr>
                <td class="fieldLabel_compulsory">Project Code:</td>
                <td> <?php echo HTML::select('upi', $project_code_list, $pot_vo->upi, true, false); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td class="fieldLabel_optional">System Username:</td>
            <td style="font-family:monospace"><?php echo htmlspecialchars((string)$pot_vo->username); ?></td>
        </tr>
        <!--
	 <tr>
		<td class="fieldLabel_optional">Unique Learner Number:</td>
		<td><input class="optional" type="text" name="uln" onKeyPress="return numbersonly(this, event)"  maxlength="10" value="<?php //echo htmlspecialchars((string)$pot_vo->uln); ?>" /></td>
	</tr>
-->
        <!-- 
	<tr>
		<td class="fieldLabel_optional">Unique Pupil Identifier:</td>
		<td><input class="optional" type="text" name="upi" value="<?php //echo htmlspecialchars((string)$pot_vo->upi); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Unique Pupil Number:</td>
		<td><input class="optional" type="text" name="upn" value="<?php //echo htmlspecialchars((string)$pot_vo->upn); ?>" /></td>
	</tr>
-->
        <!-- <tr>
		<td class="fieldLabel_optional">Learner Reference Number:</td>
		<td><input class="optional" type="text" name="l03" maxlength="12" value="<?php //echo htmlspecialchars((string)$pot_vo->l03); ?>" /></td>
	</tr>
-->
        <!-- <tr>
		<td class="fieldLabel_optional">National Insurance:</td>
		<td><input class="optional" type="text" name="ni" value="<?php //echo htmlspecialchars((string)$pot_vo->ni); ?>" /></td>
	</tr>
-->
        <tr>
            <td class="fieldLabel_optional" valign="top">Postal Address:</td>
            <td><?php echo $home_bs7666->formatEdit(); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Email:</td>
            <td><input class="optional" type="text" name="home_email" value="<?php echo htmlspecialchars((string)$pot_vo->home_email); ?>" size="40"/></td>
        </tr>
        <?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo") {?>
            <tr>
                <td class="fieldLabel_optional">Work Email</td>
                <td><input class="optional" type="text" name="learner_work_email" value="<?php echo htmlspecialchars((string)$pot_vo->learner_work_email); ?>" size="30" maxlength="80"/></td>
            </tr>
        <?php } ?>
        <tr>
            <td class="fieldLabel_optional">Telephone:</td>
            <td><input class="optional" disabled type="text" name="home_telephone" value="<?php echo htmlspecialchars((string)$pot_vo->home_telephone); ?>" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Mobile:</td>
            <td><input class="optional" type="text" name="home_mobile" value="<?php echo htmlspecialchars((string)$pot_vo->home_mobile); ?>" /></td>
        </tr>
        <!-- 
	<tr>	
		<td class="fieldLabel_compulsory">L14 - Learning Difficulties/ Disabilities </td>
		<td><?php //echo HTML::select('learning_difficulties', $L14_dropdown, $pot_vo->learning_difficulties, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Current Postcode </td>
		<td> <input class="optional" type="text" value="<?php //echo htmlspecialchars((string)$pot_vo->current_postcode); ?>" style="font-family:monospace" name="current_postcode" maxlength=8 size=8> </td>
	</tr>
	<tr>	
		<td class="fieldLabel_compulsory">L24 - Country of Domicile </td>
		<td> <?php //echo HTML::select('country_of_domicile', $L24_dropdown, $pot_vo->country_of_domicile, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">L35 - Prior Attainment Level  </td>
		<td> <?php //echo HTML::select('prior_attainment_level', $L35_dropdown, $pot_vo->prior_attainment_level, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L28 - Eligibility for enhanced funding (1)</td>
		<td> <?php //echo HTML::select('l28a', $L28_dropdown, $pot_vo->l28a, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L28 - Eligibility for enhanced funding (2)</td>
		<td> <?php //echo HTML::select('l28b', $L28_dropdown, $pot_vo->l28b, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L34 - Learner support reason (1)</td>
		<td> <?php //echo HTML::select('l34a', $L34_dropdown, $pot_vo->l34a, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L34 - Learner support reason (2)</td>
		<td> <?php //echo HTML::select('l34b', $L34_dropdown, $pot_vo->l34b, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L34 - Learner support reason (3)</td>
		<td> <?php //echo HTML::select('l34c', $L34_dropdown, $pot_vo->l34c, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L34 - Learner support reason (4)</td>
		<td> <?php //echo HTML::select('l34d', $L34_dropdown, $pot_vo->l34d, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L36 Learner status on last working day before learning</td>
		<td> <?php //echo HTML::select('l36', $L36_dropdown, $pot_vo->l36, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L37 - Employment status on first day of learning</td>
		<td> <?php //echo HTML::select('l37', $L37_dropdown, $pot_vo->l37, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L39 - Destination</td>
		<td> <?php //echo HTML::select('l39', $L39_dropdown, $pot_vo->l39, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L40 - National learner monitoring (1)</td>
		<td> <?php //echo HTML::select('l40a', $L40_dropdown, $pot_vo->l40a, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L40 - National learner monitoring (2)</td>
		<td> <?php //echo HTML::select('l40b', $L40_dropdown, $pot_vo->l40b, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L41 - Local learner monitoring (1)</td>
		<td><input class="optional" type="text" name="l41a" maxlength="12" onKeyPress="return numbersonly(this, event)" value="<?php echo htmlspecialchars((string)$pot_vo->l41a); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L41 - Local learner monitoring (2)</td>
		<td><input class="optional" type="text" name="l41b" maxlength="12" onKeyPress="return numbersonly(this, event)" value="<?php echo htmlspecialchars((string)$pot_vo->l41b); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">L47 - Current employment status</td>
		<td> <?php //echo HTML::select('l47', $L47_dropdown, $pot_vo->l47, true, false); ?></td>
	</tr>
	-->
        <tr>
            <td class="fieldLabel_optional">Work Experience:</td>
            <td class="optional"><?php echo HTML::checkbox('work_experience', 1, $pot_vo->work_experience, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Enrolment No:</td>
            <td><input class="optional" type="text" name="enrollment_no" value="<?php echo htmlspecialchars((string)$enrolment_no); ?>" size="10" maxlength="100"/> <span style="font-size: smaller;color:gray;font-style:italic">Learner level field</span></td>
        </tr>
	<?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])){ ?>
	<tr>
            <td class="fieldLabel_optional">Inherited Date:</td>
            <td class="optional"><?php echo HTML::datebox('inherited_date', $pot_vo->inherited_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Prior Record:</td>
            <td class="optional"><?php echo HTML::checkbox('prior_record', 1, $pot_vo->prior_record, true, false); ?></td>
        </tr>
        <?php } ?>
        <?php if(DB_NAME == "am_baltic" && in_array($_SESSION['user']->username, ['dpetrusowsv', 'nwatson1', 'codiefoster', 'arockett16', 'jparkin18', 'jbailey1', 'cherylreay', 'creay123'])) { ?>
            <tr>
                <td class="fieldLabel_optional">Learner Profile </td>
                <td><textarea name="learner_profile" id="learner_profile" style="width: 100%;" rows="5"><?php echo $pot_vo->learner_profile; ?></textarea></td>
            </tr>
        <?php } ?>

        <?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo") {

        $apprenticeship_titles = DAO::getResultSet($link, "SELECT distinct apprenticeship_title, apprenticeship_title FROM courses WHERE apprenticeship_title IS NOT NULL AND apprenticeship_title != ''");

        ?>
	<tr>
            <td class="fieldLabel_optional">Trusted Contact Name:</td>
            <td><input type="text" name="trusted_contact_name" id="trusted_contact_name" value="<?php echo $pot_vo->trusted_contact_name; ?>" maxlength="100" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Trusted Contact Mobile:</td>
            <td><input type="text" name="trusted_contact_mobile" id="trusted_contact_mobile" value="<?php echo $pot_vo->trusted_contact_mobile; ?>" maxlength="15" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Trusted Contact Relationship:</td>
            <td><input type="text" name="trusted_contact_rel" id="trusted_contact_rel" value="<?php echo $pot_vo->trusted_contact_rel; ?>" maxlength="100" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Details Checked Date:</td>
            <td><?php echo HTML::datebox('details_checked_date', $pot_vo->details_checked_date); ?></td>
        </tr>
    </table>
    <!--<h3>Progression</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <tr>
            <td class="fieldLabel_optional">Progression Status</td>
            <td><?php //echo HTML::select('progression_status', InductionHelper::getDDLProgressionStatus(), $pot_vo->progression_status, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Progression Programme</td>
            <td><?php //echo HTML::select('app_title', $apprenticeship_titles, $pot_vo->app_title, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Notified ARM</td>
            <td><?php //echo HTML::select('notified_arm', InductionHelper::getDDLYesNo(), $pot_vo->notified_arm, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Reason for not progressing</td>
            <td><?php //echo HTML::select('reason_not_progressing', InductionHelper::getDDLReasonForNotProgressing(), $pot_vo->reason_not_progressing, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Progression Comments</td>
            <td><textarea name="progression_comments" id="progression_comments" style="width: 100%;" rows="5"><?php //echo $pot_vo->progression_comments; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Last Update</td>
            <td><?php //echo HTML::datebox('progression_last_date', $pot_vo->progression_last_date); ?></td>
        </tr>
	<tr>
            <td class="fieldLabel_optional">Progression Rating</td>
            <td><?php //echo HTML::select('progression_rating', InductionHelper::getDdlProgressionRating(), $pot_vo->progression_rating, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Passed to ARM</td>
            <td><?php //echo HTML::datebox('passed_to_arm', $pot_vo->passed_to_arm); ?></td>
        </tr>
    </table>

    <h3>ARM Progression</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <tr>
            <td class="fieldLabel_optional">ARM Progression Status</td>
            <td><?php //echo HTML::select('arm_prog_status', InductionHelper::getDdlArmProgressionStatus(), $pot_vo->arm_prog_status, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">ARM Reason for Non Progression</td>
            <td><?php //echo HTML::select('arm_reason_not_prog', InductionHelper::getDdlArmReasonForNonProgression(), $pot_vo->arm_reason_not_prog, true, false); ?></td>
        </tr>
	<tr>
            <td class="fieldLabel_optional">ARM Closed Date</td>
            <td><?php //echo HTML::datebox('arm_closed_date', $pot_vo->arm_closed_date, false); ?></td>
        </tr>
	<tr>
            <td class="fieldLabel_optional">ARM Date to Revisit Progression</td>
            <td><?php //echo HTML::datebox('arm_revisit_progression', $pot_vo->arm_revisit_progression, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">ARM Progression Rating</td>
            <td><?php //echo HTML::select('arm_prog_rating', InductionHelper::getDdlArmProgressionRating(), $pot_vo->arm_prog_rating, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Chance to Progress</td>
            <td><?php //echo HTML::select('arm_chance_to_progress', InductionHelper::getDdlArmChanceToProgress(), $pot_vo->arm_chance_to_progress, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">ARM Comments</td>
            <td><textarea name="arm_comments" id="arm_comments" cols="50" rows="5"><?php //echo nl2br((string) $pot_vo->arm_comments);?></textarea></td>
        </tr>
    </table>-->

    <h3>Management Progression</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <tr>
            <td class="fieldLabel_optional">Actual Progression</td>
            <td><?php echo HTML::select('actual_progression', InductionHelper::getDDLYesNo(), $pot_vo->actual_progression, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Planned Induction Date</td>
            <td><?php echo HTML::datebox('planned_induction_date', $pot_vo->planned_induction_date, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Actual Induction Date</td>
            <td><?php echo HTML::datebox('actual_induction_date', $pot_vo->actual_induction_date, false); ?></td>
        </tr>
    </table>

    <h3>Employer Mentor Information</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <tr>
            <td class="fieldLabel_optional">Employer Mentor</td>
            <td><?php echo HTML::select('employer_mentor', InductionHelper::getDdlEmployerMentor(), $pot_vo->employer_mentor, true, false); ?></td>
        </tr>
    </table>	
    <h3>Learning Difficulty & Disability</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <tr>
            <td class="fieldLabel_optional">Age Category</td>
            <td><?php echo HTML::select('ldd_age_category', InductionHelper::getLddAgeCategoryDdl(), $pot_vo->ldd_age_category, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Gender Identity</td>
            <td><?php echo HTML::select('ldd_gender_ident', InductionHelper::getLddGenderIdentDdl(), $pot_vo->ldd_gender_ident, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Sexual Orientation</td>
            <td><?php echo HTML::select('ldd_sex_orient', InductionHelper::getLddSexOrientDdl(), $pot_vo->ldd_sex_orient, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Learning Difficulties or neurodiverse conditions</td>
            <td><?php echo HTML::checkboxGrid('ldd_condition', InductionHelper::getLddConditionsDdl(), explode(',', $pot_vo->ldd_condition), 1, true); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="fieldLabel_optional">If you selected <strong>Other Learning Difficulty</strong> or <strong>Other Neurodiverse Condition</strong>, detail the name of difficulty or condition.</td>
        </tr>
        <tr>
            <td colspan="2"><textarea maxlength="349" name="ldd_condition_other" id="ldd_condition_other" style="width: 100%;" rows="3"><?php echo $pot_vo->ldd_condition_other; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Mental Illnesses or Difficulties</td>
            <td><?php echo HTML::checkboxGrid('ldd_mental', InductionHelper::getLddMentalDdl(), explode(',', $pot_vo->ldd_mental), 1, true); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="fieldLabel_optional">If you selected <strong>Other Mental Health</strong>, detail the name of difficulty or condition.</td>
        </tr>
        <tr>
            <td colspan="2"><textarea maxlength="349" name="ldd_mental_other" id="ldd_mental_other" style="width: 100%;" rows="3"><?php echo $pot_vo->ldd_mental_other; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Physical disabilities or Difficulties</td>
            <td><?php echo HTML::checkboxGrid('ldd_physical', InductionHelper::getLddPhysicalDdl(), explode(',', $pot_vo->ldd_physical), 1, true); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="fieldLabel_optional">If you selected <strong>Other Disability Affecting Mobility</strong>, <strong>"Other Medical Condition</strong> or <strong>Other Physical Disability</strong> the name of difficulty or condition.</td>
        </tr>
        <tr>
            <td colspan="2"><textarea maxlength="349" name="ldd_physical_other" id="ldd_physical_other" style="width: 100%;" rows="3"><?php echo $pot_vo->ldd_physical_other; ?></textarea></td>
        </tr>
        <tr>
            <td colspan="2" class="fieldLabel_optional">If you consider you have a learning difficulty, neurodiverse condition, mental illness, or physical difficulty but you are currently undiagnosed, please provide details</td>
        </tr>
        <tr>
            <td colspan="2"><textarea maxlength="349" name="ldd_undiagnosed" id="ldd_undiagnosed" style="width: 100%;" rows="3"><?php echo $pot_vo->ldd_undiagnosed; ?></textarea></td>
        </tr>
        <tr>
            <td colspan="2" class="fieldLabel_optional">
                We want to ensure our learners are properly supported on their apprenticeship journey, are you happy if this survey is picked up by our support team (if necessary)? &nbsp; 
                <?php echo HTML::select('ldd_survey_choice', InductionHelper::getDDLYesNo(), $pot_vo->ldd_survey_choice, true, false); ?>
            </td>
        </tr>
    </table>
<?php } ?>

    </table>

    <?php if(DB_NAME=="am_lead" || DB_NAME=="am_lmpqswift" || DB_NAME=="ams"){ ?>
        <h3>User Defined Fields</h3>
        <table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
            <col width="190"
            <tr>
                <td class="fieldLabel_optional">Training Record Defined Field 1 (TDF1):</td>
                <td><input class="optional" type="text" name="tdf1" value="<?php echo htmlspecialchars((string)$pot_vo->tdf1); ?>" size="30" maxlength="30" /><span style="color:gray;margin-left:10px">(max characters 30)</span></td></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Training Record Field 2 (TDF2):</td>
                <td><input class="optional" type="text" name="tdf2" value="<?php echo htmlspecialchars((string)$pot_vo->tdf2); ?>" size="30" maxlength="30" /><span style="color:gray;margin-left:10px">(max characters 30)</span></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Achievement Date:</td>
                <td><?php echo HTML::datebox('achievement_date', $pot_vo->achievement_date, false) ?></td>
            </tr>
        </table>
    <?php } ?>

    <h3>Employer</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <col width="150" /><col />
        <tr>
            <td class="fieldLabel_compulsory">Employer:</td>
            <td><?php echo HTML::select('employer_id', $employers, $pot_vo->employer_id, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Location:</td>
            <td><?php echo HTML::select('employer_location_id', $employer_locations, $pot_vo->employer_location_id, true, false); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Postal Address:</td>
            <td><?php echo $work_bs7666->formatEdit(); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Email:</td>
            <td><input class="optional" type="text" name="work_email" value="<?php echo htmlspecialchars((string)$pot_vo->work_email); ?>" size="40"/></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Telephone:</td>
            <td><input class="optional" type="text" name="work_telephone" value="<?php echo htmlspecialchars((string)$pot_vo->work_telephone); ?>" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Mobile:</td>
            <td><input class="optional" type="text" name="work_mobile" value="<?php echo htmlspecialchars((string)$pot_vo->work_mobile); ?>" /></td>
        </tr>
        <?php if(DB_NAME == "am_balti" || DB_NAME == "am_baltic_dem") {

            $contracted_hours = array(
                array('38', 'Up to 37.5 hours', ''),
                array('40', '38 to 40 hours', ''),
                array('43', '40.05 to 42.5 hours', ''),
                array('45', '43 to 45 hours', '')
            );

            ?>
            <tr>
                <td class="fieldLabel_optional">Contracted Hours</td>
                <td><?php echo HTML::select('school_id', $contracted_hours, $pot_vo->school_id, true, true); ?></td>
            </tr>
        <?php } ?>
    </table>

    <h3>Training Provider</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <col width="150" /><col />
        <tr>
            <td class="fieldLabel_compulsory">Training Provider:</td>
            <td><?php echo HTML::select('provider_id', $providers, $pot_vo->provider_id, true, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Location:</td>
            <td><?php echo HTML::select('provider_location_id', $provider_locations, $pot_vo->provider_location_id, true, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Postal Address:</td>
            <td><?php echo $provider_bs7666->formatEdit(); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Email:</td>
            <td><input class="optional" type="text" name="provider_email" value="<?php echo htmlspecialchars((string)$pot_vo->provider_email); ?>" size="40"/></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Telephone:</td>
            <td><input class="optional" type="text" name="provider_telephone" value="<?php echo htmlspecialchars((string)$pot_vo->provider_telephone); ?>" /></td>
        </tr>
        <tr><td>
                <button	onclick="save();">Save</button>
            </td></tr>
    </table>

    <?php if(! in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) {?>
    <h3>End Point Assessment Organisation</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <col width="150" /><col />
        <tr>
            <td class="fieldLabel_optional">EPA Organisation:</td>
            <td>
                <?php
                $epa_organisations = DAO::getResultset($link, "SELECT EPA_ORG_ID, CONCAT(EPA_ORG_ID, ' - ', EP_Assessment_Organisations) AS description, UPPER(LEFT(EP_Assessment_Organisations, 1)) FROM central.`epa_organisations` ORDER BY EP_Assessment_Organisations;"); 
                echo HTML::select('epa_organisation', $epa_organisations, $pot_vo->epa_organisation, true, false); 
                ?>
            </td>
        </tr>
    </table>
    <?php } ?>

    <!-- 
<h3>Document Access Control</h3>
<h4>Read</h4>
<div style="margin-left:10px"><?php /*
	if($_SESSION['user']->isAdmin())
	{
		$acl->renderList($link, 'acl_read', $acl->getIdentities('read'));
	}
	elseif(count($_SESSION['user']->getACLFilters()) > 0)
	{
		$acl->renderList($link, 'acl_read', $acl->getIdentities('read'), ACL::EMPLOYEES, $_SESSION['user']->getACLFilters());
	}
	else
	{
		echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('read')).'</p>';
	} */
    ?></div>

<h4>Edit</h4>
<div style="margin-left:10px"><?php /*
	if($_SESSION['user']->isAdmin())
	{
		$acl->renderList($link, 'acl_write', $acl->getIdentities('write'));
	}
	elseif(count($_SESSION['user']->getACLFilters()) > 0)
	{
		$acl->renderList($link, 'acl_write', $acl->getIdentities('write'), ACL::EMPLOYEES, $_SESSION['user']->getACLFilters());
	}
	else
	{
		echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('write')).'</p>';
	} */
    ?></div>
-->



</form>

<br/>


<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>