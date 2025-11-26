
<?php
$sunesis_learner = User::loadFromDatabaseById($link, $vo->user_id);
if($_SESSION['user']->type == User::TYPE_MANAGER)
    $ddlCourses = DAO::getResultSet($link, "SELECT id, title FROM courses WHERE courses.active = 1 AND courses.organisations_id='{$_SESSION['user']->employer_id}' ORDER BY title");
else
    $ddlCourses = DAO::getResultSet($link, "SELECT id, title FROM courses WHERE courses.active = 1 ORDER BY title");

if($_SESSION['user']->type == User::TYPE_MANAGER && DB_NAME != 'am_lead')
    $ddlContracts = DAO::getResultset($link,"SELECT id, title FROM contracts WHERE active = 1 AND contract_year >= YEAR(NOW())-2 AND title LIKE '%{$_SESSION['user']->org->legal_name}%' ORDER BY contract_year DESC, title");
else
    $ddlContracts = DAO::getResultSet($link, "SELECT id, title FROM contracts WHERE active = 1 AND contract_year >= YEAR(NOW()) ORDER BY contract_year DESC, title ");

$coaches_sql = <<<HEREDOC
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
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE users.web_access = 1 AND users.type NOT IN (5, 12)
AND users.`username` NOT IN (SELECT ident FROM acl WHERE resource_category = 'application' AND privilege = 'administrator')
ORDER BY CONCAT(firstnames, ' ', surname)
;
HEREDOC;
$ddlCoaches = DAO::getResultset($link, $coaches_sql);

$sql = <<<SQL
SELECT
	contact_id, CONCAT(COALESCE(contact_title), ' ',COALESCE(`contact_name`,''),', ', COALESCE(`contact_department`,''),' - ',COALESCE(`contact_email`,'')), null
FROM
	organisation_contact
WHERE
	org_id = '{$sunesis_learner->employer_id}'
ORDER BY
	contact_name
;
SQL;
$ddlEmployerContacts = DAO::getResultset($link, $sql);
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box  box-info">
            <div class="box-header">
                <div class="box-title">Training Records</div>
                <div class="box-body">
                    <div class="callout callout ">
                        <span class="lead text-green text-bold">Enrolment - Create Training Record</span>
                        <form method="post" class="form-horizontal" name="frmEnrolLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="false">
                            <input type="hidden" name="_action" value="save_ob_learner_enrolment" />
                            <input type="hidden" name="ob_learner_id" value="<?php echo $vo->id; ?>" />
                            <input type="hidden" name="sunesis_learner_id" value="<?php echo $sunesis_learner->id; ?>" />
                            <div class="form-group">
                                <label class="col-sm-3 control-label fieldLabel_compulsory">Employer:</label>
                                <div class="col-sm-9">
                                    <?php
                                    echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$sunesis_learner->employer_id}'");
                                    echo '<br>';
                                    echo DAO::getSingleValue($link, "SELECT CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') FROM locations WHERE locations.id = '{$sunesis_learner->employer_location_id}'");
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="employer_contact" class="col-sm-3 control-label fieldLabel_optional">Employer Contact:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::select('crm_contact_id', $ddlEmployerContacts, '', true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="coach" class="col-sm-3 control-label fieldLabel_optional">Coach:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::select('coach', $ddlCoaches, $vo->coach, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="epa_organisation" class="col-sm-3 control-label fieldLabel_optional">EPA Organisation:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::select('epa_organisation', $ddlEpaOrgs, '', true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contract_id" class="col-sm-3 control-label fieldLabel_compulsory">Contract:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::select('contract_id', $ddlContracts, $vo->contract_id, true, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label fieldLabel_compulsory">Practical Period Dates:</label>
                                <div class="col-sm-9">
                                    Start: <?php echo HTML::datebox('practical_start_date', '', true); ?> &nbsp;
                                    Planned End: <?php echo HTML::datebox('practical_end_date', '', true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label fieldLabel_compulsory">Start and End Dates (including EPA):</label>
                                <div class="col-sm-9">
                                    Start: <?php echo HTML::datebox('start_date', '', true); ?> &nbsp;
                                    Planned End (including EPA): <?php echo HTML::datebox('end_date', '', true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label fieldLabel_compulsory">Off the Job Hours:</label>
                                <div class="col-sm-9">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Weeks on Programme:</th>
                                            <td><input type="text" name="weeks_on_programme" maxlength="5" size="5" onkeypress="return numbersonly(this);" /></td>
                                        </tr>
                                        <tr>
                                            <th>Statutory Annual Leave:</th>
                                            <td><input type="text" name="statutory_annual_leave" id="statutory_annual_leave" maxlength="4" size="5" onkeypress="return numbersonlywithpoint(this);" /></td>
                                        </tr>
                                        <tr>
                                            <th>Normal Weekly Hours:</th>
                                            <td><input type="text" name="emp_q7" id="emp_q7" value="<?php echo $vo->emp_q7; ?>" maxlength="4" size="5" onkeypress="return numbersonlywithpoint(this);" /></td>
                                        </tr>
                                        <tr>
                                            <th>Off-the-job training (hours):</th>
                                            <td><input type="text" name="planned_otj_hours" id="planned_otj_hours" value="<?php echo $vo->planned_otj_hours; ?>" maxlength="5" size="5" onkeypress="return numbersonly(this);" /></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="course_id" class="col-sm-3 control-label fieldLabel_compulsory">Course:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::select('course_id', $ddlCourses, '', true, true); ?>
                                </div>
                            </div>

                            <div class="col-sm-12"><div class="bg-white" id="qualification_details"></div></div>

                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9">
                                    <span class="btn btn-success btn-block btnEnrolLearner"><b><i class="fa fa-graduation-cap"></i> Enrol Learner</b></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">

    function course_id_onchange(element)
    {
        if(element.value == '')
            return;

        var client = ajaxRequest('do.php?_action=ajax_onboarding&subaction=showQualificationsTableOnEnrolment&course_id='+element.value);
        $('#qualification_details').html(client.responseText);
        /*
		loadDDL('getMainAims', element.value);
		loadDDL('getTechCerts', element.value);
		loadDDL('get_l2_found_competences', element.value);
		loadDDL('maths', element.value);
		loadDDL('eng', element.value);
		loadDDL('ict', element.value);
		loadDDL('PLTS', element.value);
		loadDDL('ERR', element.value);
		*/
    }



    function loadDDL(subaction, course_id)
    {
        var ddl_id = '';
        var ddl_loading_msg = 'Loading ';
        var fs_type = '';
        if(subaction == 'getTechCerts')
        {
            ddl_id = '#tech_cert_id';
            ddl_loading_msg += 'technical certificates ...';
        }
        else if(subaction == 'get_l2_found_competences')
        {
            subaction = 'getTechCerts';
            ddl_id = '#l2_found_competence_id';
            ddl_loading_msg += 'L2 foundation competence ...';
        }
        else if(subaction == 'getMainAims')
        {
            ddl_id = '#main_aim_id';
            ddl_loading_msg += 'main aim ...';
        }
        else if(subaction == 'maths')
        {
            subaction = 'getFs';
            fs_type = 'maths';
            ddl_id = '#fs_maths_id';
            ddl_loading_msg += 'FS Maths ...';
        }
        else if(subaction == 'eng')
        {
            subaction = 'getFs';
            fs_type = 'eng';
            ddl_id = '#fs_eng_id';
            ddl_loading_msg += 'FS English ...';
        }
        else if(subaction == 'ict')
        {
            subaction = 'getFs';
            fs_type = 'ict';
            ddl_id = '#fs_ict_id';
            ddl_loading_msg += 'FS ICT ...';
        }
        else if(subaction == 'PLTS')
        {
            subaction = 'getPLTS';
            ddl_id = '#PLTS_id';
            ddl_loading_msg += 'PLTS ...';
        }
        else if(subaction == 'ERR')
        {
            subaction = 'getERR';
            ddl_id = '#ERR_id';
            ddl_loading_msg += 'ERR ...';
        }

        $.ajax({
            type:'GET',
            url:'do.php?_action=ajax_onboarding&subaction='+subaction,
            data: {course_id: course_id, fs_type: fs_type} ,
            beforeSend: function() {
                $(ddl_id)
                    .find('option')
                    .remove()
                    .end()
                    .append('<option value="">' + ddl_loading_msg + '</option>')
                    .val('')
                ;
                $(ddl_id).attr('disabled', true);
            },
            success:function(html){
                $(ddl_id).html(html);
                $(ddl_id).attr('disabled', false);
            },
            error:function(msg){
                alert('Error: Please contact Sunesis Support with the screenshot.\r\n'+msg);
                console.log(msg);
            }
        });
    }


    $(function(){

        $('input#statutory_annual_leave, input#emp_q7').blur(function(){
            if($(this).val() == '')
                return ;
            var num = parseFloat($(this).val());
            var cleanNum = num.toFixed(1);
            $(this).val(cleanNum);
        });

        $("#input_practical_start_date").on('focus', function(){
            this.value = this.value.trim() == '' ? $('#input_start_date').val() : this.value;
        });

        $("#input_practical_end_date").on('focus', function(){
            this.value = this.value.trim() == '' ? $('#input_end_date').val() : this.value;
        });

        $("input[name=weeks_on_programme]").on('focus', function(){
            var sd = stringToDate($('#input_practical_start_date').val());
            var ed = stringToDate($('#input_practical_end_date').val());
            this.value = monthDiff(sd, ed);
        });

        $("input[name=planned_otj_hours]").on('focus', function(){
            if($("input[name=weeks_on_programme]").val().trim() != '' &&
                $("input[name=statutory_annual_leave]").val().trim() != '' &&
                $("input[name=emp_q7]").val().trim() != '' && this.value.trim() == '')
            {
                var v1 = parseFloat($("input[name=weeks_on_programme]").val().trim());
                var v2 = parseFloat($("input[name=statutory_annual_leave]").val().trim());
                var v3 = parseFloat($("input[name=emp_q7]").val().trim());

                this.value = Math.ceil((v1-(v2/5))*v3*0.2);
            }
        });

        $('.btnEnrolLearner').on('click', function(){

            var form = document.forms['frmEnrolLearner'];

            if(!validateForm(form))
            {
                return;
            }

            var searchIDs = $("input[name='selected_quals[]']:checked").map(function(){
                return $(this).val();
            }).toArray();

            if(form.elements["course_id"].value == '')
            {
                return alert('Please select course');
            }
            if(form.elements["contract_id"].value == '')
            {
                return alert('Please select contract');
            }
            if(form.elements["start_date"].value == '')
            {
                alert('Please provide start date');
                form.elements["start_date"].focus();
                return ;
            }
            if(form.elements["end_date"].value == '')
            {
                alert('Please provide planned end date');
                form.elements["end_date"].focus();
                return ;
            }
            if(form.elements["practical_start_date"].value == '')
            {
                alert('Please provide practical start date');
                form.elements["practical_start_date"].focus();
                return ;
            }
            if(form.elements["practical_end_date"].value == '')
            {
                alert('Please provide practical end date');
                form.elements["practical_end_date"].focus();
                return ;
            }

            var datesValid = true;
            $.each(searchIDs, function(i, v){
                if($("#input_sd_"+v).val() == '')
                {
                    alert("Please provide start date of all selected qualifications.");
                    $("input_sd_"+v).focus();
                    datesValid = false;
                    return false;
                }
                if($("#input_ped_"+v).val() == '')
                {
                    alert("Please provide planned end date of all selected qualifications.");
                    $("input_ped_"+v).focus();
                    datesValid = false;
                    return false;
                }
            });

            if(!datesValid)
            {
                return ;
            }

            form.submit();
        });

    });

    function copydate(_date, ele)
    {
        if(_date == 'start')
            ele.value = ele.value.trim() == '' ? $('#input_start_date').val() : ele.value;
        if(_date == 'end')
            ele.value = ele.value.trim() == '' ? $('#input_end_date').val() : ele.value;
    }

    function validateDate(ele)
    {
        if(ele.value != "" && (window.stringToDate(ele.value) == null) )
        {
            var incorrect = ele.value;
            alert("Invalid date format or invalid calendar date '" + incorrect + "'.  Format: dd/mm/yyyy");
            ele.focus();
            return false;
        }
        return true;
    }

    function numbersonlywithpoint(myfield, e, dec)
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
        else if ((("0123456789.").indexOf(keychar) > -1))
            return true;

        // decimal point jump
        else if (dec && (keychar == "."))
        {
            myfield.form.elements[dec].focus();
            myfield.form.elements[dec].select();
            return false;
        }
        else
            return false;
    }

    function monthDiff(d1, d2)
    {
        var months;
        months = (d2.getFullYear() - d1.getFullYear()) * 12;
        months -= d1.getMonth();
        months += d2.getMonth();
        return months <= 0 ? 0 : Math.ceil((months*30)/7);
    }

</script>