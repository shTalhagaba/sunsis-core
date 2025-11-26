<?php /* @var $vo User */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Learner</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        #home_postcode, #work_postcode, #ni{text-transform:uppercase}
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Edit Learner <?php echo $vo->id != '' ? '[' . $vo->firstnames . ' ' . $vo->surname . ']' : ''; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<br>

<div class="container-fluid">

    <div class="row">

        <div class="col-sm-12">
            <div class="nav-tabs-custom bg-gray-light">
                <ul class="nav nav-tabs">
                    <li class="<?php echo $tabPersonalDetails; ?>"><a href="#tabPersonalDetails" data-toggle="tab">Personal Details</a></li>
                    <li class="<?php echo $tabContactDetails; ?>"><a href="#tabContactDetails" data-toggle="tab">Contact</a></li>
                    <li class="<?php echo $tabLLDD; ?>"><a href="#tabLLDD" data-toggle="tab">LLDD</a></li>
                    <li class="<?php echo $tabDiagnostics; ?>"><a href="#tabDiagnostics" data-toggle="tab">Diagnostics & Qualifications</a></li>
                    <li class="<?php echo $tabEmployment; ?>"><a href="#tabEmployment" data-toggle="tab">Employment</a></li>
                    <li class="<?php echo $tabAccess; ?>"><a href="#tabAccess" data-toggle="tab">Access</a></li>                    
                </ul>

                <div class="tab-content">
                    <div class="<?php echo $tabPersonalDetails; ?> tab-pane" id="tabPersonalDetails">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box box-primary">
                                    <form method="post" class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>"  enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
                                        <input type="hidden" name="username" value="<?php echo $vo->username; ?>" />
                                        <input type="hidden" name="_action" value="save_learner" />
                                        <input type="hidden" name="formName" value="frmLearner" />
                                        <input type="hidden" name="selected_tab" value="tabPersonalDetails" />
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span  class="lead text-info">Personal Details</span>
                                                    <div class="form-group">
                                                        <label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">Firstnames:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $vo->firstnames; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $vo->surname; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="input_dob" class="col-sm-4 control-label fieldLabel_compulsory">Date of Birth:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo in_array(DB_NAME, ["am_duplex"]) ? HTML::datebox('dob', $vo->dob, false) : HTML::datebox('dob', $vo->dob, true); ?>
                                                            <label class="label label-info" id="lblAgeToday"><?php echo Date::dateDiff(date("Y-m-d"), $vo->dob); ?></label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="gender" class="col-sm-4 control-label fieldLabel_compulsory">Gender:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('gender', InductionHelper::getDDLGender(), $vo->gender, true, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ethnicity" class="col-sm-4 control-label fieldLabel_compulsory">Ethnicity:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('ethnicity', $ddlEthnicities, $vo->ethnicity, true, true); ?>
                                                        </div>
                                                    </div>
						    <div class="form-group">
                                                        <label for="home_postcode" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control compulsory" name="home_postcode" id="home_postcode" value="<?php echo strtoupper((string)$vo->home_postcode); ?>" maxlength="10" onkeyup="this.value = this.value.toUpperCase();" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ni" class="col-sm-4 control-label fieldLabel_optional">National Insurance:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="ni" id="ni" value="<?php echo strtoupper((string)$vo->ni); ?>" maxlength="9" onkeyup="this.value = this.value.toUpperCase();" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="job_role" class="col-sm-4 control-label fieldLabel_optional">Job Role:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::selectChosen('job_role', $ddlJobRoles, $vo->job_role, true); ?>
							    <input type="text" class="form-control optional" name="job_role" id="job_role" value="<?php echo $vo->job_role; ?>" maxlength="100" />	
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nationality" class="col-sm-4 control-label fieldLabel_optional">Nationality:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('nationality', $ddlNationalities, $vo->nationality, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="enrollment_no" class="col-sm-4 control-label fieldLabel_optional">Enrollment Number:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="enrollment_no" id="enrollment_no" value="<?php echo $vo->enrollment_no; ?>" maxlength="10" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="uln" class="col-sm-4 control-label fieldLabel_optional">Unique Learner Number (ULN):</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control optional" name="uln" id="uln" value="<?php echo $vo->uln; ?>" maxlength="10" />
                                                        </div>
							<?php if(SystemConfig::getEntityValue($link, "lrs")) {?>
                                                        <div class="col-sm-3">
                                                            <button type="button" class="btn btn-primary btn-sm" id="btnDownloadUln"><i class="fa fa-cloud-download"></i> Download from LRS</button>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="abr_number" class="col-sm-4 control-label fieldLabel_optional">Awarding Body Registration Number:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="abr_number" id="abr_number" value="<?php echo $vo->abr_number; ?>" maxlength="15" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ucas" class="col-sm-4 control-label fieldLabel_optional">UCAS Personal Identifier:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="ucas" id="ucas" value="<?php echo $vo->ucas; ?>" maxlength="15" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="L42a" class="col-sm-4 control-label fieldLabel_optional">Learner Provider Specified Monitoring (L42a):</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="l42a" id="l42a" value="<?php echo $vo->l42a; ?>" maxlength="20" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="L42b" class="col-sm-4 control-label fieldLabel_optional">Learner Provider Specified Monitoring (L42b):</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="l42b" id="l42b" value="<?php echo $vo->l42b; ?>" maxlength="20" />
                                                        </div>
                                                    </div>
                                                    <div class="callout callout-default">
                                                        <h5 class="lead text-bold">Next of Kin</h5>
                                                        <div class="form-group">
                                                            <label for="nok_title" class="col-sm-4 control-label fieldLabel_optional">Title:</label>
                                                            <div class="col-sm-8">
                                                                <?php echo HTML::select("nok_title", $ddlTitles, $vo->nok_title, true); ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nok_name" class="col-sm-4 control-label fieldLabel_optional">Name:</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control optional" name="nok_name" id="nok_name" value="<?php echo $vo->nok_name; ?>" maxlength="100" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nok_rel" class="col-sm-4 control-label fieldLabel_optional">Relationship to learner:</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control optional" name="nok_rel" id="nok_rel" value="<?php echo $vo->nok_rel; ?>" maxlength="100" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nok_tel" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control optional" name="nok_tel" id="nok_tel" value="<?php echo $vo->nok_tel; ?>" maxlength="20" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nok_mob" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control optional" name="nok_mob" id="nok_mob" value="<?php echo $vo->nok_mob; ?>" maxlength="20" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nok_email" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control optional" name="nok_email" id="nok_email" value="<?php echo $vo->nok_email; ?>" maxlength="150" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span  class="lead text-info">Profile Picture</span>
                                                    <div class="box-body box-profile">
                                                        <img class="profile-user-img img-responsive" src="<?php echo $photopath; ?>" alt="User profile picture">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="uploadedfile" class="col-sm-4 control-label fieldLabel_optional">Upload Photograph:</label>
                                                        <div class="col-sm-8">
                                                            <input class="optional" type="file" name="uploadedfile" /><span style="margin-left:10px;color:gray">(100KB max)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="box-footer">
                                            <button type="button" class="btn btn-success pull-right" onclick="saveFrmLearner('frmLearner'); "><i class="fa fa-save"></i> Save Learner</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="<?php echo $tabContactDetails; ?> tab-pane" id="tabContactDetails">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="box box-primary">
                                    <form method="post" class="form-horizontal" name="frmLearnerAddress" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="id" value="<?php echo $vo->id; ?>" /><input type="hidden" name="username" value="<?php echo $vo->username; ?>" />
                                        <input type="hidden" name="_action" value="save_learner" />
                                        <input type="hidden" name="formName" value="frmLearnerAddress" />
                                        <input type="hidden" name="selected_tab" value="tabContactDetails" />
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span  class="lead text-info">Contact Details</span>
                                                    <div class="form-group">
                                                        <label for="home_address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Address Line 1:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control compulsory" name="home_address_line_1" id="home_address_line_1" value="<?php echo $vo->home_address_line_1; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="home_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="home_address_line_2" id="home_address_line_2" value="<?php echo $vo->home_address_line_2; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="home_address_line_3" class="col-sm-4 control-label fieldLabel_optional">Address Line 3:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="home_address_line_3" id="home_address_line_3" value="<?php echo $vo->home_address_line_3; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="home_address_line_4" class="col-sm-4 control-label fieldLabel_optional">Address Line 4:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="home_address_line_4" id="home_address_line_4" value="<?php echo $vo->home_address_line_4; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo strtoupper((string)$vo->home_postcode); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="home_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="home_telephone" id="home_telephone" value="<?php echo $vo->home_telephone; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="home_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="home_mobile" id="home_mobile" value="<?php echo $vo->home_mobile; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="home_email" class="col-sm-4 control-label fieldLabel_optional">Personal Email:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="home_email" id="home_email" value="<?php echo $vo->home_email; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="table-responsive">
                                                        <strong>Does learner wish to be contacted for the following:</strong>
                                                        <table class="table">
                                                            <?php $rui = explode(',', (string)$vo->rui); ?>
                                                            <tr><td><input type="checkbox" name="rui[]" value="1" <?php echo in_array(1, $rui) ? 'checked="checked"' : ''; ?> /><label>About courses or learning opportunities</label></td></tr>
                                                            <tr><td><input type="checkbox" name="rui[]" value="2" <?php echo in_array(2, $rui) ? 'checked="checked"' : ''; ?> /><label>For surveys and research</label></td></tr>
                                                        </table>
                                                    </div>
                                                    <p><br></p>
                                                    <div class="table-responsive">
                                                        <strong>Learner contact preferences:</strong>
                                                        <table class="table">
                                                            <?php $pmc = explode(',', (string)$vo->pmc); ?>
                                                            <tr><td><input type="checkbox" name="pmc[]" value="1" <?php echo in_array(1, $pmc) ? 'checked="checked"' : ''; ?> /><label>By Post</label></td></tr>
                                                            <tr><td><input type="checkbox" name="pmc[]" value="2" <?php echo in_array(2, $pmc) ? 'checked="checked"' : ''; ?> /><label>By Phone</label></td></tr>
                                                            <tr><td><input type="checkbox" name="pmc[]" value="3" <?php echo in_array(3, $pmc) ? 'checked="checked"' : ''; ?> /><label>By Email</label></td></tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="box-footer">
                                            <button type="button" class="btn btn-success pull-right" onclick="saveFrmLearner('frmLearnerAddress'); "><i class="fa fa-save"></i> Save Learner</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="<?php echo $tabLLDD; ?> tab-pane" id="tabLLDD">
                        <div class="box box-primary">
                            <form method="post" class="form-horizontal" name="frmLearnerLLDD" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="id" value="<?php echo $vo->id; ?>" /><input type="hidden" name="username" value="<?php echo $vo->username; ?>" />
                                <input type="hidden" name="_action" value="save_learner" />
                                <input type="hidden" name="formName" value="frmLearnerLLDD" />
                                <input type="hidden" name="selected_tab" value="tabLLDD" />
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <span  class="lead text-info">LLDD</span>
                                            <div class="form-group">
                                                <label for="LLDD" class="col-sm-12 fieldLabel_compulsory">Does learner consider to have a learning difficulty, health problem or disability?:</label>
                                                <div class="col-sm-12">
                                                    <?php echo HTML::selectChosen('l14', $ddlLldd, $vo->l14, true, true); ?>
                                                </div>
                                            </div>
                                            <div class="form-group" id="divLLDDCat">
                                                <div class="col-sm-12">
                                                    <label>Select categories:</label>
                                                    <table class="table table-bordered table-hover">
                                                        <tr><th>Category</th><th>Primary
                                                                <small>(only one)</small></th></tr>
                                                        <?php
                                                        $lldd_cat = explode(',', (string)$vo->lldd_cat);
                                                        foreach($ddlLlddCat AS $key => $value)
                                                        {
                                                            $checked = in_array($key, $lldd_cat) ? ' checked="checked" ' : '';
                                                            $checked_pri = $vo->primary_lldd == $key ? ' checked="checked" ' : '';
                                                            echo '<tr><td><input class="clsICheck" type="checkbox" name="lldd_cat[]" '.$checked.' value="'.$key.'" /><label>'.$value.'</label></td><td align="center"><p><input type="radio" name="primary_lldd" value="'.$key.'" '.$checked_pri.'></p></td></tr>';
                                                        }
                                                        ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="table-responsive">
                                                <strong>Additional Learning Support</strong>
                                                <table class="table">
                                                    <tr><td><input type="checkbox" name="pass_to_als[]" value="1" <?php echo $vo->pass_to_als == '1' ? ' checked="checked" ' : ''; ?> /><label>Pass to ALS team</label></td></tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="button" class="btn btn-success pull-right" onclick="saveFrmLearner('frmLearnerLLDD'); "><i class="fa fa-save"></i> Save Learner</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="<?php echo $tabDiagnostics; ?> tab-pane" id="tabDiagnostics">
                        <div class="box box-primary">
                            <form method="post" class="form-horizontal" name="frmLearnerDiagnostics" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="id" value="<?php echo $vo->id; ?>" /><input type="hidden" name="username" value="<?php echo $vo->username; ?>" />
                                <input type="hidden" name="_action" value="save_learner" />
                                <input type="hidden" name="selected_tab" value="tabDiagnostics" />
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <span  class="lead text-info">Initial Assessment & Diagnostics</span>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="numeracy_diagnostic" value="1" <?php echo $vo->numeracy_diagnostic == '1' ? 'checked="checked"' : ''; ?> /><label>Numeracy Initial Assessment</label>
                                                        </td>
                                                        <td>
                                                            <?php echo HTML::selectChosen('numeracy', $ddlPreAssessment, $vo->numeracy, true); ?>
							                                <?php if(DB_NAME == "am_ela"){ ?>
                                                                <br><input class="form-control" type="text" name="numeracy_other" id="numeracy_other" value="<?php echo $vo->numeracy_other; ?>" maxlength="50" placeholder="Other numeracy grade" />
                                                            <?php }?>	
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="literacy_diagnostic" value="1" <?php echo $vo->literacy_diagnostic == '1' ? 'checked="checked"' : ''; ?> /><label>Literacy Initial Assessment</label>
                                                        </td>
                                                        <td>
                                                            <?php echo HTML::selectChosen('literacy', $ddlPreAssessment, $vo->literacy, true); ?>
							                                <?php if(DB_NAME == "am_ela"){ ?>
                                                                <br><input class="form-control" type="text" name="literacy_other" id="literacy_other" value="<?php echo $vo->literacy_other; ?>" maxlength="50" placeholder="Other literacy grade" />
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="chk_numeracy_diagnostic" value="1" <?php echo $vo->chk_numeracy_diagnostic == '1' ? 'checked="checked"' : ''; ?> /><label>Numeracy Diagnostic Assessment</label>
                                                        </td>
                                                        <td>
                                                            <?php echo HTML::selectChosen('grade_numeracy_diagnostic', $ddlPreAssessment, $vo->grade_numeracy_diagnostic, true); ?>
							                                <?php if(DB_NAME == "am_ela"){ ?>
                                                                <br><input class="form-control" type="text" name="grade_numeracy_diagnostic_other" id="grade_numeracy_diagnostic_other" value="<?php echo $vo->grade_numeracy_diagnostic_other; ?>" maxlength="50" placeholder="Other literacy dianostic grade" />
                                                            <?php }?>	
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="chk_literacy_diagnostic" value="1" <?php echo $vo->chk_literacy_diagnostic == '1' ? 'checked="checked"' : ''; ?> /><label>Literacy Diagnostic Assessment</label>
                                                        </td>
                                                        <td>
                                                            <?php echo HTML::selectChosen('grade_literacy_diagnostic', $ddlPreAssessment, $vo->grade_literacy_diagnostic, true); ?>
							                                <?php if(DB_NAME == "am_ela"){ ?>
                                                                <br><input class="form-control" type="text" name="grade_literacy_diagnostic_other" id="grade_literacy_diagnostic_other" value="<?php echo $vo->grade_literacy_diagnostic_other; ?>" maxlength="50" placeholder="Other numeracy diagnostic grade" />
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="ict_diagnostic" value="1" <?php echo $vo->ict_diagnostic == '1' ? 'checked="checked"' : ''; ?> /><label>ICT Assessment</label>
                                                        </td>
                                                        <td>
                                                            <?php echo HTML::selectChosen('ict', $ddlPreAssessment, $vo->ict, true); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="esol_diagnostic" value="1" <?php echo $vo->esol_diagnostic == '1' ? 'checked="checked"' : ''; ?> /><label>ESOL Assessment</label>
                                                        </td>
                                                        <td>
                                                            <?php echo HTML::selectChosen('esol', $ddlPreAssessment, $vo->esol, true); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="other_diagnostic" value="1" <?php echo $vo->other_diagnostic == '1' ? 'checked="checked"' : ''; ?> /><label>Other Assessment</label>
                                                        </td>
                                                        <td>
                                                            <?php echo HTML::selectChosen('other', $ddlPreAssessment, $vo->other, true); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>English is not the 1st language?:</td>
                                                        <td><input class="yes_no_toggle" type="checkbox" name="eng_first" id="eng_first" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $vo->eng_first == '1' ? 'checked="checked"' : ''; ?> /></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <span  class="lead text-info">Prior Attainment</span>

                                            <div class="table-responsive">
                                                <table class="table table-responsive row-border cw-table-list">
                                                    <tr style="background-color: #e0ffff;">
                                                        <td colspan="5">
                                                            <label>Prior Attainment Level</label>
                                                            <p>
                                                                <i class="text-muted">Please use the <span style="margin-top: 2px;" class="btn btn-info btn-sm" onclick="window.open('PriorAttainmentGuidance2018_19.pdf', '_blank')"><i class="fa fa-info-circle"></i> Guidance Notes</span>
                                                                    to record the overall level of prior attainment of learner's qualifications achieved to date.<br>For example,</i>
                                                            </p>
                                                            <ul style="margin-left: 25px;">
                                                                <li><i class="text-muted">if learner has 4 GCSE's with Grades A - C, this would fall into Level 1</i></li>
                                                                <li><i class="text-muted">if learner has 5 GCSE's with Grades A - C, this would fall into Level 2</i></li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr style="background-color: #e0ffff;">
                                                        <th colspan="1" align="right">
                                                            Prior Attainment Level
                                                        </th>
                                                        <td colspan="4" align="left">
                                                            <?php echo HTML::selectChosen('high_level', $ddlPriorAttain, $vo->high_level, true);?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="col-sm-12"><hr></div>
                                        <!-- <div class="col-sm-12">
                                            <div class="well well-sm"><p>Please list learner's educational prior attainment and include maths, english, ICT, or any other engineering related qualifications.</p></div>
                                            <div style="max-height: 600px; overflow-y: scroll;">
                                                <table class="table table-responsive row-border cw-table-list">
                                                    <tr><th style="width: 25%;">GCSE/A/AS Level</th><th style="width: 25%;">Subject</th><th style="width: 15%;">Predicted Grade</th><th style="width: 15%;">Actual Grade</th><th style="width: 20%;">Date Completed</th></tr>
                                                    <tbody>
                                                    <tr>
                                                        <td>GCSE <input type="hidden" name="gcse_english_level" value="101" /></td>
                                                        <td>English Language<input type="hidden" name="gcse_english_subject" value="English" /></td>
                                                        <td>
                                                            <?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades WHERE description NOT IN ('Credit', 'Distinction*') ORDER BY id;", DAO::FETCH_NUM);
                                                            echo HTML::selectChosen('gcse_english_grade_predicted', $qual_grades, '', true);
                                                            ?>
                                                        </td>
                                                        <td><?php echo HTML::selectChosen('gcse_english_grade_actual', $qual_grades, '', true); ?></td>
                                                        <td><input class="datecontrol  form-control" type="text" name="gcse_english_date_completed" id="input_gcse_english_date_completed" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>GCSE <input type="hidden" name="gcse_maths_level" value="102" /></td>
                                                        <td>Maths<input type="hidden" name="gcse_maths_subject" value="Maths" /></td>
                                                        <td><?php echo HTML::selectChosen('gcse_maths_grade_predicted', $qual_grades, '', true); ?></td>
                                                        <td><?php echo HTML::selectChosen('gcse_maths_grade_actual', $qual_grades, '', true); ?></td>
                                                        <td><input class="datecontrol  form-control" type="text" name="gcse_maths_date_completed" id="input_gcse_maths_date_completed" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
                                                    </tr>
                                                    <?php
                                                    for($i = 1; $i <= 5; $i++)
                                                    {
                                                        echo '<tr>';
                                                        echo '<td>' . HTML::selectChosen('level'.$i, $ddlQualLevels, '', true) . '</td>';
                                                        echo '<td><input class="form-control optional" type="text" name="subject'.$i.'" id="subject'.$i.'" value="" /></td>';
                                                        echo '<td>' . HTML::selectChosen('predicted_grade'.$i, $qual_grades, '', true) . '</td>';
                                                        echo '<td>' . HTML::selectChosen('actual_grade'.$i, $qual_grades, '', true) . '</td>';
                                                        echo '<td><input class="datecontrol form-control" type="text" name="date_completed'.$i.'" id="input_date_completed'.$i.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /><input class="form-control optional" type="hidden" name="q_type'.$i.'" id="q_type'.$i.'" value="'.$i.'" /></td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="button" class="btn btn-success pull-right" onclick="saveFrmLearner('frmLearnerDiagnostics'); "><i class="fa fa-save"></i> Save Learner</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="<?php echo $tabEmployment; ?> tab-pane" id="tabEmployment">
                        <div class="box box-primary">
                            <form method="post" class="form-horizontal" name="frmLearnerEmployment" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="id" value="<?php echo $vo->id; ?>" /><input type="hidden" name="username" value="<?php echo $vo->username; ?>" />
                                <input type="hidden" name="_action" value="save_learner" />
                                <input type="hidden" name="formName" value="frmLearnerEmployment" />
                                <input type="hidden" name="selected_tab" value="tabEmployment" />
                                <div class="box-body">
                                    <div class="col-sm-6">
                                        <span class="lead text-info">Employment Details</span>
                                        <div class="form-group">
                                            <label for="employer_id" class="col-sm-4 control-label fieldLabel_compulsory">Employer:</label>
                                            <div class="col-sm-8">
                                                <?php echo HTML::selectChosen('employer_id', $ddlEmployers, $vo->employer_id, true, true); ?>
                                                <span class="text-info"><i class="fa fa-info-circle"></i> Select employer to bring its locations in the locations drop down.</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="employer_location_id" class="col-sm-4 control-label fieldLabel_compulsory">Employer Location:</label>
                                            <div class="col-sm-8">
                                                <?php echo HTML::selectChosen('employer_location_id', $ddlEmployersLocations, $vo->employer_location_id, true); ?>
                                                <span class="text-info"><i class="fa fa-info-circle"></i> Select location to pull location address in the address fields.</span>
                                            </div>
                                        </div>
                                        <div class="callout callout-default">
                                            <span class="text-info"><i class="fa fa-info-circle"></i> Following fields are auto populated if you change the location. These fields are to record learner's work address.</span>
                                            <div class="form-group">
                                                <label for="work_address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Address Line 1:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control compulsory" name="work_address_line_1" id="work_address_line_1" value="<?php echo $vo->work_address_line_1; ?>" maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="work_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control optional" name="work_address_line_2" id="work_address_line_2" value="<?php echo $vo->work_address_line_2; ?>" maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="work_address_line_3" class="col-sm-4 control-label fieldLabel_optional">Address Line 3:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control optional" name="work_address_line_3" id="work_address_line_3" value="<?php echo $vo->work_address_line_3; ?>" maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="work_address_line_4" class="col-sm-4 control-label fieldLabel_optional">Address Line 4:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control optional" name="work_address_line_4" id="work_address_line_4" value="<?php echo $vo->work_address_line_4; ?>" maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="work_postcode" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control compulsory" name="work_postcode" id="work_postcode" value="<?php echo strtoupper((string)$vo->work_postcode); ?>" maxlength="10" onkeyup="this.value = this.value.toUpperCase();" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="work_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control optional" name="work_telephone" id="work_telephone" value="<?php echo $vo->work_telephone; ?>" maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="work_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control optional" name="work_mobile" id="work_mobile" value="<?php echo $vo->work_mobile; ?>" maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="work_email" class="col-sm-4 control-label fieldLabel_optional">Work Email:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control optional" name="work_email" id="work_email" value="<?php echo $vo->work_email; ?>" maxlength="100" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-sm-6 callout callout-default">
                                        <span  class="lead text-info">Employment Questionnaire</span>

                                        <p><br></p>
                                        <p class="text-bold">What did the learner do prior to starting Apprenticeship Programme?</label></p>
                                        <div class="form-group">
                                            <label for="EmploymentStatus" class="col-sm-12 fieldLabel_optional">Was the learner</label>
                                            <div class="col-sm-12">
                                                <p class="text-bold"><span style="border: 1px #003399 solid; border-radius: 5px; padding: 5px;"><input type="radio" name="EmploymentStatus" value="10"></span> In paid employment</p>
                                                <p class="text-bold"><span style="border: 1px #003399 solid; border-radius: 5px; padding: 5px;"><input type="radio" name="EmploymentStatus" value="11"></span> Not in paid employment, looking for work and available to start work</p>
                                                <p class="text-bold"><span style="border: 1px #003399 solid; border-radius: 5px; padding: 5px;"><input type="radio" name="EmploymentStatus" value="12"></span> Not in paid employment, not looking for work and/or not available to start work</p>
                                                <p class="text-bold"><span style="border: 1px #003399 solid; border-radius: 5px; padding: 5px;"><input type="radio" name="EmploymentStatus" value="98"></span> Not known / don't want to provide</p>
                                            </div>
                                        </div>
                                        <table class="table table-bordered tabl-hover" id="tbl_emp_status_10" class="table row-border" style="display: none;">
                                            <tr>
                                                <td colspan="2">
                                                    <input type="checkbox" name="work_curr_emp" id="work_curr_emp"  value="1" /><label>Was the learner employed with the current employer prior to you starting Apprenticeship Programme?</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><input type="checkbox" name="SEI" id="SEI" /><label>If not, was the learner self-employed?</label></td>
                                            </tr>
                                            <tr>
                                                <th>What is the Employer Name?</th>
                                                <td><input class="form-control compulsory" type="text" name="empStatusEmployer" id="empStatusEmployer" value="" /></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><input type="checkbox" name="SEM" id="SEM" /><label>Was the company a Small Employer with less than 50 employees?</label></td>
                                            </tr>
                                            <tr>
                                                <th>How long the learner employed?</th>
                                                <td><?php echo HTML::selectChosen('LOE', $ddlLoe, '', false); ?></td>
                                            </tr>
                                            <tr>
                                                <th>How many hours did learner work each week?</th>
                                                <td><?php echo HTML::selectChosen('EII', $ddlEii, '', false); ?></td>
                                            </tr>
                                        </table>
                                        <table id="tbl_emp_status_11_12" class="table row-border" style="display: none;">
                                            <tr>
                                                <th>How long was the learner un-employed before apprenticeship start</label>?</th>
                                                <td><?php echo HTML::selectChosen('LOU', $ddlLou, '', false); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Did learner receive any of these benefits?</th>
                                                <td><?php echo HTML::selectChosen('BSI', $ddlBsi, '', false); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><input type="checkbox" name="PEI" id="PEI" /><label>Was the learner in Full Time Education or Training prior to the apprenticeship start</label></td>
                                            </tr>
                                        </table>
                                    </div> -->
                                </div>
                                <div class="box-footer">
                                    <button type="button" class="btn btn-success pull-right" onclick="saveFrmLearner('frmLearnerEmployment'); "><i class="fa fa-save"></i> Save Learner</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="<?php echo $tabAccess; ?> tab-pane" id="tabAccess">
                        <form class="form-horizontal" name="frmLearnerAccess" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
                            <input type="hidden" name="username" value="<?php echo $vo->username; ?>" />
                            <input type="hidden" name="_action" value="save_learner" />
                            <input type="hidden" name="formName" value="frmLearnerAccess" />
                            <input type="hidden" name="selected_tab" value="tabAccess" />
                            <div class="box-body">
                                <div class="col-sm-12">
                                    <div class="box box-primary">
                                        <div class="box-header with-border"><h5  class="lead text-info no-margin">Security Credentials</h5> </div>
                                        <div class="box-body">
                                            <div class="callout callout-info">
                                                <p><i class="fa fa-info-circle"></i> Usernames, once set, cannot be changed. To change a username delete the user and create a new user with the desired new username.</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="username" class="col-sm-4 control-label fieldLabel_compulsory">Username: </label>
                                                <div class="col-sm-4"><code><?php echo $vo->username; ?></code></div>
                                            </div>
                                            <div class="callout callout-info ">
                                                <p><i class="fa fa-info-circle"></i> Strong passwords are important for the protection of learner data.
                                                    The password may contain letters, numbers, spaces and punctuation.
                                                    The password must be between 8 and 50 characters long and contain at least one number, one lowercase letter and one uppercase letter.
                                                    Passwords based on single words are vulnerable to automated dictionary-attacks and are not allowed.</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="password" class="col-sm-4 control-label fieldLabel_optional">Password/Passphrase: </label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control optional" name="password" id="password" maxlength="45" />
                                                </div>
                                                <div class="col-sm-4">
                                                    <span class="btn btn-info btn-md" onclick="document.getElementById('password').value=dicewarePassword(4,8,50);"><i class="fa fa-refresh"></i> Generate</span>
                                                </div>
                                                <div class="col-sm-4"></div>
                                                <div class="col-sm-8">
                                                    <p class="text-info"><i class="fa fa-info-circle"></i> Leave this field blank to retain the user's existing passphrase. </p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="username" class="col-sm-4 control-label fieldLabel_compulsory">Web Access: </label>
                                                <div class="col-sm-8">
                                                    <?php
                                                    echo $vo->web_access == '1' ?
                                                        '<input value="1" class="yes_no_toggle" type="checkbox" name="web_access" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                                                        '<input value="0" class="yes_no_toggle" type="checkbox" name="web_access" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-6"></div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-success pull-right" onclick="saveFrmLearner('frmLearnerAccess'); "><i class="fa fa-save"></i> Save Learner</button>
                            </div>
                        </form>
                    </div>

                    
                </div>
            </div>

        </div>

    </div>

</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/js/jquery/jquery.timepicker.js"></script>
<script src="/password.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>

<script language="JavaScript">
    $(function(){

        $('input[type=checkbox]').not(".yes_no_toggle").each(function(){

            var self = $(this);
            var label = self.next();
            var label_text = label.text();
            var checkboxClass;

            if (this.checked) {
                checkboxClass = 'icheckbox_line-green';
            } else  {
                checkboxClass = 'icheckbox_line-blue';
            }
            label.remove();
            self.iCheck({
                checkboxClass: checkboxClass,
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });

        });

        $(document).on('ifChanged', '[type=checkbox]', function() {
            var self = $(this);
            var label = self.parent();
            var label_text = label.text();
            var checkboxClass;
            if (this.checked) {
                checkboxClass = 'icheckbox_line-green';
            } else  {
                checkboxClass = 'icheckbox_line-blue';
            }
            self.iCheck({
                checkboxClass: checkboxClass,
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });



        }).trigger('ifChanged');

        $('input[type=radio]').iCheck({
            radioClass: 'iradio_square-blue'
        });

        $("input[name=EmploymentStatus]").on('ifChecked', function(event){
            if(this.value == 10)
            {
                $('#tbl_emp_status_10').show();
                $('#tbl_emp_status_11_12').hide();
            }
            else if(this.value == 11 || this.value == 12)
            {
                $('#tbl_emp_status_10').hide();
                $('#tbl_emp_status_11_12').show();
            }
            else
            {
                $('#tbl_emp_status_10').hide();
                $('#tbl_emp_status_11_12').hide();
            }
        });

        <?php if($toastr_message != ''){?>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "preventDuplicates": true,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr.success('<?php echo $toastr_message; ?>');
        <?php } ?>

        $('input[name="uln"]').keyup(function(e){
            $('input[name="uln"]').css('color', isValidUln($('input[name="uln"]').val()) ? 'green':'red');
        });
        $('input[name="uln"]').bind('paste', function(e){
            setTimeout(function() {
                $('input[name="uln"]').css('color', isValidUln($('input[name="uln"]').val()) ? 'green':'red');
            }, 100);
        });
        $('input[name="uln"]').css('color', isValidUln($('input[name="uln"]').val()) ? 'green':'red');
    });

    function employer_id_onchange(employer, event)
    {
        var f = employer.form;

        var employer_locations = document.getElementById('employer_location_id');

        if(employer.value != '')
        {
            employer.disabled = true;

            employer_locations.disabled = true;
            ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_locations&employer_id=' + employer.value);
            employer_locations.disabled = false;

            employer.disabled =false;
        }
        else
        {
            emptySelectElement(employer_locations);

        }
    }

    function employer_location_id_onchange(loc)
    {
        if(loc.value != '')
        {
            populateAddress('do.php?_action=ajax_load_organisation_address&loc_id=' + loc.value, document.forms["frmLearnerEmployment"], 'work_');
        }
    }

    function populateAddress(url, form, elementPrefix)
    {
        if (elementPrefix == null) {
            elementPrefix = '';
        }

        var xml = ajaxRequest(url);
        var $xml = $(xml.responseXML);


        $('[name="edrs"]', form).val($xml.find('edrs').text());
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

    function saveFrmLearner(form_name)
    {
        var myForm = document.forms[form_name];
        if(!validateForm(myForm))
        {
            return;
        }
        if(form_name == 'frmLearner' && myForm.ni.value.trim() != '' && !validateNI(myForm.ni.value))
        {
            alert('Please enter valid National Insurance.');
            myForm.ni.focus();
            return;
        }
        if(form_name == 'frmLearner' && myForm.uln.value.trim() != '' && !isValidUln(myForm.uln.value))
        {
            alert("The ULN '" + myForm.uln.value.trim() + "' is invalid. Please correct or remove the ULN before saving.");
            myForm.uln.focus();
            return false;
        }
        if(form_name == 'frmLearner' && myForm.uln.value.trim() != '' && isValidUln(myForm.uln.value))
        {
            var id = myForm.id.value;
            var employerId = $('#employer_id').val();
            var uln = myForm.uln.value;
            var client = ajaxRequest('do.php?_action=edit_learner&subaction=findExistingUln'
                + '&id=' + encodeURIComponent(id)
                + '&employer_id=' + encodeURIComponent(employerId)
                + '&uln=' + encodeURIComponent(uln));
            if (client)
            {
                var records = jQuery.parseJSON(client.responseText);
                if (records.length)
                {
                    alert("Another learner exists with ULN '" + uln + "' ("
                        + records[0]['firstnames'] + " " + records[0]['surname']
                        + ") . The ULN is a unique identifier. Please correct or remove the ULN before saving.");
                    myForm.uln.focus();
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        if(form_name == 'frmLearner' && !validatePostcode(myForm.home_postcode.value))
        {
            alert('Please enter valid postcode.');
            myForm.home_postcode.focus();
            return;
        }
        if(form_name == 'frmLearnerAddress' && myForm.home_email.value.trim() != '' && !validateEmail(myForm.home_email.value))
        {
            alert('Please enter valid email address.');
            myForm.home_email.focus();
            return;
        }
        if(form_name == 'frmLearnerLLDD')
        {
            if(myForm.l14.value == '1')
            {
                if($('[name="lldd_cat[]"]:checked').length == 0)
                {
                    alert('Please select the LLDD Category.');
                    return;
                }
                else
                {
                    if($(".checked input[name=primary_lldd]").val() === undefined)
                    {
                        alert('Please select which one is the primary LLDD');
                        return;
                    }
                }
            }
        }
        if(form_name == 'frmLearnerEmployment' && myForm.work_postcode.value.trim() != '' && !validatePostcode(myForm.work_postcode.value))
        {
            alert('Please enter valid postcode.');
            myForm.work_postcode.focus();
            return;
        }
        if(form_name == 'frmLearnerEmployment' && myForm.work_email.value.trim() != '' && !validateEmail(myForm.work_email.value))
        {
            alert('Please enter valid email address.');
            myForm.work_email.focus();
            return;
        }

        myForm.submit();
    }

    /**
     *
     * @param string uln
     * @return boolean True if the value is a valid ULN, false if the value is empty or an invalid ULN
     */
    function isValidUln(uln)
    {
        uln = jQuery.trim(uln);
        var valid_pattern = /^[1-9]{1}[0-9]{9}$/;
        if (uln.match(valid_pattern))
        {
            var remainder = ((10 * uln.charAt(0))
                + (9 * uln.charAt(1))
                + (8 * uln.charAt(2))
                + (7 * uln.charAt(3))
                + (6 * uln.charAt(4))
                + (5 * uln.charAt(5))
                + (4 * uln.charAt(6))
                + (3 * uln.charAt(7))
                + (2 * uln.charAt(8))) % 11;

            if (remainder == 0) {
                return false;
            }

            var check_digit = 10 - remainder;
            if (check_digit != uln.charAt(9)) {
                return false;
            }

            return true;
        }

        return false;
    }

    $('button#btnDownloadUln').on('click', function(event){
            //event.preventDefault();
            fields_valid = validate_fields();
            if(!fields_valid)
            {
                return false;
            }
            $(this).attr('disabled', true);
            $(this).html('<i class="fa fa-refresh fa-spin"></i> Contacting LRS ...');
            $.ajax({
                url: 'do.php?_action=ajax_lrs&subaction=learnerByDemographics',
                type: 'GET',
                data: {
                    'FindType': 'FUL',
                    'FamilyName': $("input[name=surname]").val(),
                    'GivenName': $("input[name=firstnames]").val(),
                    'DateOfBirth': $("input[name=dob]").val(),
                    'Gender': $("select[name=gender]").val(),
                    'LastKnownPostCode': $("input[name=home_postcode]").val(),
                    'EmailAddress': $("input[name=home_email]").val()
                },
                dataType: 'json',
                success: function(response) {
                    $('button#btnDownloadUln').attr('disabled', false);
                    $('button#btnDownloadUln').html('<i class="fa fa-cloud-download"></i> Download from LRS');
                    if(response.status == "WSRC0004")
                    {
                        if(response.learners_count === 1)
                        {
                            $("input[name=uln]").val(response.learner[0].ULN);
                        }
                    }
                    if(response.status == "WSRC0003")
                    {
                        if(response.learners_count === 1)
                        {
                            $("input[name=uln]").val(response.learner[0].ULN);
                            var html = '<i class="fa fa-info-circle"></i> This is a linked learner record with Master ULN and Linked ULN.<br>';
                            html += '<span class="text-bold">Master ULN:</span> ' + response.learner[0].ULN + '<br>';
                            html += '<span class="text-bold">Linked ULNs:</span> ' + response.learner[0].LinkedULNs.ULN.join(", ") + '<br>';
                            html += 'System has copied the Master ULN in Unique Learner Number field.';
                            var title = response.status + ": Information: Linked Learner"; 
                        }
                        else
                        {
                            var html = '<i class="fa fa-info-circle"></i> Too many matches.<br>';
                            html += 'LRS has returned ' + response.learners_count + ' possible matches based on your search. ';
                            html += 'Please provide some more information.';
                            var title = response.status + ": Information: Possible matches"; 
                        }
                        $("<div></div>").html(html).dialog({
                                id: "dlg_lrs_result",
                                title: title,
                                resizable: false,
                                modal: true,
                                width: 750,
                                height: 500,
                                buttons: {
                                    'Close': function() {
                                        $(this).dialog('close');
                                        return false;
                                    }
                                }
                        });
                    }
                    if(response.status == "WSRC0001")
                    {
                        var html = '<i class="fa fa-info-circle"></i> No match.<br>';
                        html += 'LRS could not find any matching record for this learner.';
                        $("<div></div>").html(html).dialog({
                            id: "dlg_lrs_result",
                            title: response.status + ": Information: No Match",
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 250,
                            buttons: {
                                'Close': function() {
                                    $(this).dialog('close');
                                    return false;
                                }
                            }
                        });
                    }
                    if(response.SOAP_faultcode !== undefined && response.SOAP_faultcode != '')
                    {
                        var fault = 'SOAP faultcode: ' + response.SOAP_faultcode + '<br>' + 
                            'LRS_ErrorCode: ' + response.LRS_ErrorCode + '<br>' + 
                            'LRS_Description: ' + response.LRS_Description + '<br>' + 
                            'LRS_FurtherDetails: ' + response.LRS_FurtherDetails + '<br>' 
                            ;
                        $("<div></div>").html(fault).dialog({
                            id: "dlg_lrs_result",
                            title: "Error",
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 500,
                            buttons: {
                                'Close': function() {
                                    $(this).dialog('close');
                                    return false;
                                }
                            }
                        });
                    }
                    console.log('success');
                    console.log(response);
                },
                error: function(request, error) {
                    $('button#btnDownloadUln').attr('disabled', false);
                    $('button#btnDownloadUln').html('<i class="fa fa-cloud-download"></i> Download from LRS');
                    console.log("error");
                    console.log("Request: " + JSON.stringify(request));
                }
            });
        });

        function validate_fields()
        {
            var myForm = document.forms["frmLearner"];

            if(myForm.elements['firstnames'].value.trim() == '')
            {
                alert('Please enter learner\'s first name(s)');
                myForm.elements['firstnames'].focus();
                return false;
            }
            if(myForm.elements['surname'].value.trim() == '')
            {
                alert('Please enter learner\'s surname');
                myForm.elements['surname'].focus();
                return false;
            }
            if(myForm.elements['home_postcode'].value.trim() == '')
            {
                alert('Please enter learner\'s postcode');
                myForm.elements['home_postcode'].focus();
                return false;
            }
            if(!validatePostcode(myForm.elements['home_postcode'].value))
            {
                alert('Please enter valid postcode');
                myForm.elements['home_postcode'].focus();
                return false;
            }
            if(myForm.elements['dob'].value.trim() == '')
            {
                alert('Please enter learner\'s date of birth');
                myForm.elements['dob'].focus();
                return false;
            }
            if(myForm.elements['gender'].value.trim() == '')
            {
                alert('Please enter learner\'s gender');
                myForm.elements['gender'].focus();
                return false;
            }
            return true;
        }

</script>

</body>
</html>