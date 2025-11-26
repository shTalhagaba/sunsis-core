<?php 
 /* @var $vo User */
 $bksbRandom = $vo->id%2 === 0;
?>
<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Learner</title>

    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Learner [<?php echo $vo->firstnames . ' ' . $vo->surname; ?>]</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=edit_learner&id=<?php echo $vo->id; ?>&username=<?php echo $vo->username; ?>';"><i class="fa fa-edit"></i> Edit</span>
                <span class="btn btn-xs btn-default" onclick="window.open('do.php?_action=pdf_from_learner&username=<?php echo $vo->username; ?>');"><i class="fa fa-file-pdf-o"></i> Basic ILR</span>
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

<div class="content-wrapper">

    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $photopath; ?>" alt="User profile picture">
                    <span class="profile-username"><?php echo htmlspecialchars((string)$vo->firstnames) . ' ' . htmlspecialchars(strtoupper($vo->surname)); ?></span>
                    <p class="text-muted"><?php echo htmlspecialchars((string)$vo->job_role); ?></p>
                    <div class="col-sm-12 invoice-col">
                        <b><?php echo $vo->org->legal_name; ?></b><br>
                        <?php if ($vo->loc): ?>
                            <?php echo $vo->loc->address_line_3 != ''? $vo->loc->address_line_3 . '<br>':''; ?>
                            <?php echo $vo->loc->address_line_4 != ''? $vo->loc->address_line_4 . '<br>':''; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="box  box-info box-solid">
                <div class="box-header with-border"><span class="box-title">Contact Information</span></div>

                <!-- /.box-header -->
                <div class="box-body">
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Work Contact Details</strong>
                    <address>
                        <?php
                        echo trim((string)$work_address->address_line_1) != ''?htmlspecialchars((string)$work_address->address_line_1).'<br>':'';
                        echo trim((string)$work_address->address_line_2) != ''?htmlspecialchars((string)$work_address->address_line_2).'<br>':'';
                        echo trim((string)$work_address->address_line_3) != ''?htmlspecialchars((string)$work_address->address_line_3).'<br>':'';
                        echo trim((string)$work_address->address_line_4) != ''?htmlspecialchars((string)$work_address->address_line_4).'<br>':'';
                        echo trim((string)$work_address->postcode) != ''?htmlspecialchars((string)$work_address->postcode).'<br>':'';
                        echo trim((string)$vo->work_telephone) != ''?'<span class="fa fa-phone"></span> '.htmlspecialchars((string)$vo->work_telephone).'<br>':'';
                        echo trim((string)$vo->work_mobile) != ''?'<span class="fa fa-mobile-phone"></span> '.htmlspecialchars((string)$vo->work_mobile).'<br>':'';
                        echo trim((string)$vo->work_email) != ''?'<span class="fa  fa-envelope"></span> <a href="mailto:'.$vo->work_email.'">'.htmlspecialchars((string)$vo->work_email).'</a>':'';
                        ?>
                    </address>

                    <hr>

                    <strong><i class="fa fa-map-marker margin-r-5"></i> Home Contact Details</strong>

                    <address>
                        <?php
                        echo trim((string)$home_address->address_line_1) != ''?htmlspecialchars((string)$home_address->address_line_1).'<br>':'';
                        echo trim((string)$home_address->address_line_2) != ''?htmlspecialchars((string)$home_address->address_line_2).'<br>':'';
                        echo trim((string)$home_address->address_line_3) != ''?htmlspecialchars((string)$home_address->address_line_3).'<br>':'';
                        echo trim((string)$home_address->address_line_4) != ''?htmlspecialchars((string)$home_address->address_line_4).'<br>':'';
                        echo trim((string)$home_address->postcode) != ''?htmlspecialchars((string)$home_address->postcode).'<br>':'';
                        echo trim((string)$vo->home_telephone) != ''?'<span class="fa fa-phone"></span> '.htmlspecialchars((string)$vo->home_telephone).'<br>':'';
                        echo trim((string)$vo->home_mobile) != ''?'<span class="fa fa-mobile-phone"></span> '.htmlspecialchars((string)$vo->home_mobile).'<br>':'';
                        echo trim((string)$vo->home_email) != ''?'<span class="fa  fa-envelope"></span> <a href="mailto:'.$vo->home_email.'">'.htmlspecialchars((string)$vo->home_email).'</a>':'';
                        if($vo->rui != '')
                        {
                            $rui = explode(',', (string)$vo->rui);
                            echo '<hr><p class="bg-green">Learner wishes to be contacted for: </p><ul>';
                            echo in_array(1, $rui) ? '<li>About courses or learning opportunities</li>' : '';
                            echo in_array(2, $rui) ? '<li>For surveys and research</li>' : '';
                            echo '</ul>';
                        }
                        if($vo->pmc != '')
                        {
                            $pmc = explode(',', (string)$vo->pmc);
                            echo '<hr><p class="bg-green">Learner contact preferences: </p><ul>';
                            echo in_array(1, $pmc) ? '<li>By Post</li>' : '';
                            echo in_array(2, $pmc) ? '<li>By Phone</li>' : '';
                            echo in_array(3, $pmc) ? '<li>By Email</li>' : '';
                            echo '</ul>';
                        }
                        ?>
                    </address>

                    <hr>

                </div>
                <!-- /.box-body -->
            </div>
	    <?php if(in_array(DB_NAME, ["am_ela","am_demo"])){ ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <span class="box-title">Manage Tags</span>
                    <span class="btn btn-xs btn-primary pull-right" onclick="$('#modalTags').modal('show');"><i class="fa fa-tags"></i> Assign Tags</span>
                </div>
                <div class="box-body">
                    <?php 
                    $learner_tags = DAO::getResultset($link, "SELECT tags.id, tags.name FROM tags INNER JOIN taggables ON tags.id = taggables.tag_id WHERE taggables.taggable_type = 'Learner' AND taggables.taggable_id = '{$vo->id}' ORDER BY tags.name", DAO::FETCH_ASSOC);
                    foreach($learner_tags AS $learner_tag)
                    {
                        echo '<div style="margin: 3px;">';
                        echo '<span class="label label-success label-lg">' . $learner_tag['name'] . ' &nbsp; &nbsp; ';
                        echo '<i class="fa fa-times fa-lg" style="cursor:pointer;" title="Detach this tag from this record" onclick="detach_tag(\''.$learner_tag['id'].'\', \''.$vo->id.'\', \'Learner\');"></i>';
                        echo '</span>';
                        echo '</div>';
                    }
                    ?>
                    <div class="modal fade" id="modalTags" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form class="form-horizontal" method="post" name="frmTags" id="frmTags" method="post" action="do.php?_action=assign_tags">
                                    <input type="hidden" name="formName" value="frmTags" />
                                    <input type="hidden" name="taggable_type" value="Learner" />
                                    <input type="hidden" name="taggable_id" value="<?php echo $vo->id; ?>" />
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h5 class="modal-title text-bold">Assign Tags</h5>
                                    </div>
                                    <div class="modal-body">
                                    
                                        <div class="control-group">
                                            <label class="control-label" for="tag">Select Tag:</label> &nbsp;
                                            <?php echo HTML::selectChosen('tag', Tag::getTagsForSelectList($link, 'Learner'), '', true); ?>
                                        </div>
                                        <p>-----------------------OR-----------------------</p>
                                        <div class="control-group">
                                            <label class="control-label" for="new_tag">Enter Tag:</label> &nbsp;
                                            <input type="text" class="form-control" name="new_tag" id="new_tag" maxlength="70" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary btn-sm btnModalTagsCancel" type="button" onclick="$('#modalTags').modal('hide');">Cancel</button>
                                        <button class="btn btn-success btn-sm" type="submit">Assign </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>	
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">

	    <?php if(in_array(DB_NAME, ["am_ela"]))
            { 
                $learner_tags = DAO::getResultset($link, "SELECT tags.id, tags.name FROM tags INNER JOIN taggables ON tags.id = taggables.tag_id WHERE taggables.taggable_type = 'Learner' AND taggables.taggable_id = '{$vo->id}' ORDER BY tags.name", DAO::FETCH_ASSOC);
                echo '<div class="pull-right">';
                foreach($learner_tags AS $learner_tag)
                {
                    echo '<span class="label label-success">' . $learner_tag['name'] . '</span> &nbsp; ';
                }
                echo '</div>';
            } 
            ?>
	    <?php
            if(DB_NAME == "am_demo")
            {
                $trsFound = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.username = '{$vo->username}'");
                if($trsFound > 0) 
                {
                    echo '<div class="pull-right">';
                    echo '<p><span class="label label-success" style="font-size: small;">Learner Enrolled</span></p>';
                    echo '</div>';
                }
                elseif(!$bksbRandom) 
                {
                    echo '<div class="pull-right">';
                    echo '<p><span class="label label-primary" style="font-size: small;">Awaiting Initial Assessment</span></p>';
                    echo '</div>';
                }
                elseif($trsFound == 0) 
                {
                    echo '<div class="pull-right">';
                    echo '<p><span class="label label-primary" style="font-size: small;">Awaiting Enrolment</span></p>';
                    echo '</div>';
                }
            } 
            ?>

            <div class="nav-tabs-custom bg-gray-light">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_details" data-toggle="tab">Details</a></li>
                    <li><a href="#tab_enrol" data-toggle="tab">Enrolment / Training Records</a></li>
                    <li><a href="#tab_emails" data-toggle="tab">Emails</a></li>
		    <?php if(DB_NAME == "am_demo"){?>
                    <li><a href="#tab_bksb" data-toggle="tab">BKSB</a></li>
                    <?php } ?>
                    <?php if(false && in_array(DB_NAME, ["am_demo"])) {?>
                        <li><a href="#tab_safeguarding" data-toggle="tab">Safeguarding</a></li>
                        <li><a href="#tab_iv" data-toggle="tab">Internal Validation</a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="tab_details">

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="box box-info box-solid">
                                    <div class="box-header with-border">
                                        <span class="box-title">Personal Details</span>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr><th style="width:30%">Gender:</th><td><?php echo htmlspecialchars((string)$gender_description); ?></td></tr>
                                                <tr>
                                                    <th style="width:30%">Date of Birth:</th>
                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars(Date::toMedium($vo->dob));
                                                        if ($vo->dob) {
                                                            echo ' &nbsp; <label class="label label-info" id="lblAgeToday">' . Date::dateDiff(date("Y-m-d"), $vo->dob) . '</label>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr><th style="width:30%">Ethnicity:</th><td><?php echo htmlspecialchars((string)$ethnicity_description); ?></td></tr>
                                                <tr><th style="width:30%">Nationality:</th><td><?php echo htmlspecialchars((string)$nationality_description); ?></td></tr>
                                                <tr><th style="width:30%">Job Role:</th><td><?php echo htmlspecialchars((string)$vo->job_role); ?></td></tr>
                                                <tr><th style="width:30%" class="small">Learner Provider Specified Monitoring (L42a):</th><td><?php echo htmlspecialchars((string)$vo->l42a); ?></td></tr>
                                                <tr><th style="width:30%" class="small">Learner Provider Specified Monitoring (L42b):</th><td><?php echo htmlspecialchars((string)$vo->l42b); ?></td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="box  box-info box-solid">
                                    <div class="box-header with-border">
                                        <span class="box-title">Identifiers</span>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr><th style="width:50%">Enrolment Number:</th><td><?php echo htmlspecialchars((string)$vo->enrollment_no); ?></td></tr>
                                                <tr><th style="width:50%">Unique Learner Number (ULN):</th><td><?php echo htmlspecialchars((string)$vo->uln); ?></td></tr>
                                                <tr><th style="width:50%">ILR Learner Reference Number (L03):</th><td><?php echo htmlspecialchars((string)$tr_l03); ?></td></tr>
                                                <tr><th style="width:50%">System Username:</th><td><code><?php echo htmlspecialchars((string)$vo->username); ?></code></td></tr>
                                                <tr><th style="width:50%">Awarding Body Registration Number:</th><td><?php echo htmlspecialchars((string)$vo->abr_number); ?></td></tr>
                                                <tr><th style="width:50%">UCAS Personal Identifier:</th><td><?php echo htmlspecialchars((string)$vo->ucas); ?></td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="box  box-info box-solid">
                                    <div class="box-header with-border">
                                        <span class="box-title">Diagnostics</span>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr><th style="width:30%">Numeracy:</th><td><?php echo htmlspecialchars((string)$numeracy); echo DB_NAME == "am_ela" ? '<br>'. $vo->numeracy_other : ''; ?></td></tr>
                                                <tr><th style="width:30%">Literacy:</th><td><?php echo htmlspecialchars((string)$literacy); echo DB_NAME == "am_ela" ? '<br>'. $vo->literacy_other : ''; ?></td></tr>
                                                <tr><th style="width:30%">ICT:</th><td><?php echo htmlspecialchars((string)$ict); ?></td></tr>
                                                <tr><th style="width:30%">ESOL:</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_pre_assessment WHERE id = '{$vo->esol}'"); ?></td></tr>
                                                <tr><th style="width:30%">Other:</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_pre_assessment WHERE id = '{$vo->other}'"); ?></td></tr>
                                                <tr><th style="width:30%">Prior Attainment Level:</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM central.lookup_prior_attainment WHERE code = '{$vo->high_level}'"); ?></td></tr>
                                                <tr><th style="width:30%" class="small">English is not the 1st language?:</th><td><?php echo $vo->eng_first == '1' ? 'Yes' : 'No'; ?></td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="box  box-info box-solid">
                                    <div class="box-header with-border">
                                        <span class="box-title">LLDD</span>
                                    </div>
                                    <div class="box-body" style="max-height: 250px; overflow-y: scroll;">
                                        <p><span class="text-bold small">Does learner consider to have a learning difficulty, health problem or disability? </span><?php echo isset($LLDD[$vo->l14]) ? $LLDD[$vo->l14] : ''; ?></p>
                                        <table class="table table-bordered">
                                            <tr><th>LLDD Category</th><th>Primary</th></tr>
                                            <?php
                                            if($vo->lldd_cat == '') echo '<tr><td colspan="2">No LLDD category selected.</td> </tr>';
                                            $lldd_cat = explode(',', (string)$vo->lldd_cat);
                                            foreach($LLDDCat AS $key => $value)
                                            {
                                                if(in_array($key, $lldd_cat))
                                                {
                                                    echo '<tr>';
                                                    echo '<td>' . $value . '</td>';
                                                    echo $key == $vo->primary_lldd ? '<td>Yes</td>' : '<td></td>';
                                                    echo '</tr>';
                                                }
                                            }
                                            ?>
                                            <?php echo $vo->pass_to_als == '1' ? '<tr><td colspan="2"><span class="text-info"><i class="fa fa-info-circle"></i> Additional Learning Support - Passed to ALS team</span></td>' : ''; ?>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>

			<?php if(in_array(DB_NAME, ["am_ela", "am_crackerjack"])) {?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box  box-info box-solid">
                                        <div class="box-header with-border">
                                            <span class="box-title">Emergency Contacts</span>
                                        </div>
                                        <div class="box-body no-padding">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <?php 
                                                    $ob_emergency_contacts = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts INNER JOIN ob_tr ON ob_learner_emergency_contacts.tr_id = ob_tr.id INNER JOIN ob_learners ON ob_tr.ob_learner_id = ob_learners.id WHERE ob_learners.sunesis_learner_id = '{$vo->id}'", DAO::FETCH_ASSOC);
                                                    foreach($ob_emergency_contacts AS $ob_emergency_contact)
                                                    {
                                                        echo '<tr class="text-info">';
                                                        echo '<td>' . $ob_emergency_contact['em_con_title'] . ' ' . $ob_emergency_contact['em_con_name'] . '<br>' . $ob_emergency_contact['em_con_rel'] . '</td>';
                                                        echo '<td>' . $ob_emergency_contact['em_con_tel'] . ' &nbsp;&nbsp; ' . $ob_emergency_contact['em_con_mob'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $vo->nok_title . ' ' . $vo->nok_name . '<br>' . $vo->nok_rel;?> </td>
                                                        <td><?php echo $vo->nok_tel . ' &nbsp;&nbsp; ' . $vo->nok_mob . '<br>' . $vo->nok_email;?> </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

			<?php if( SystemConfig::getEntityValue($link, "module_bootcamp") ) {?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box  box-info box-solid">
                                        <div class="box-header with-border">
                                            <span class="box-title">Emergency Contacts</span>
                                        </div>
                                        <div class="box-body no-padding">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <?php 
                                                    $registration = DAO::getObject($link, "SELECT * FROM registrations WHERE entity_id = '{$vo->id}' AND entity_type = 'User'");
                                                    if( isset($registration->id) )
                                                    {
                                                        echo '<tr class="text-info">';
                                                        echo '<td>' . $registration->em_con_title1 . ' ' . $registration->em_con_name1 . '<br>' . $registration->em_con_rel1 . '</td>';
                                                        echo '<td>' . $registration->em_con_tel1 . ' &nbsp;&nbsp; ' . $registration->em_con_mob1 . '</td>';
                                                        echo '</tr>';
                                                        echo '<tr class="text-info">';
                                                        echo '<td>' . $registration->em_con_title2 . ' ' . $registration->em_con_name2 . '<br>' . $registration->em_con_rel2 . '</td>';
                                                        echo '<td>' . $registration->em_con_tel2 . ' &nbsp;&nbsp; ' . $registration->em_con_mob2 . '</td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $vo->nok_title . ' ' . $vo->nok_name . '<br>' . $vo->nok_rel;?> </td>
                                                        <td><?php echo $vo->nok_tel . ' &nbsp;&nbsp; ' . $vo->nok_mob . '<br>' . $vo->nok_email;?> </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php
                        if(SOURCE_LOCAL || DB_NAME == "am_duplex") {
                            $hs_form = DAO::getObject($link, "SELECT * FROM crm_learner_hs_form WHERE learner_id = '{$vo->id}'");
                            if(!isset($hs_form->learner_id))
                            {
                                $hs_form = new stdClass();
                                $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM crm_learner_hs_form");
                                foreach($records AS $key => $value)
                                    $hs_form->$value = null;
                            }
                            $this->generateSignatureImageFromHsForm($link, $hs_form);
                            ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box  box-info box-solid">
                                        <div class="box-header with-border">
                                            <span class="box-title">Health & Safety Form </span>
                                        </div>
                                        <div class="box-body">
                                            <table class="table-boredered table-condensed" style="width: 100%;">
                                                <tr class="bg-gray-light">
                                                    <th colspan="2">SECTION 1: Delegate Details</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="text-bold">Name: </span><br> <?php echo $vo->firstnames . ' ' . $vo->surname; ?>
                                                    </td>
                                                    <td>
                                                        <span class="text-bold">DOB: </span> <br><?php echo Date::toShort($vo->dob); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-bold">Job Role: </span><br>
                                                        <?php echo $vo->job_role; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-bold">Company Name: </span><br> <?php echo isset($vo->org->legal_name) ? $vo->org->legal_name : ''; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="text-bold">Home Postcode: </span><br>
                                                        <?php echo $vo->home_postcode; ?>
                                                    </td>
                                                    <td>
                                                        <span class="text-bold">Email: </span><br>
                                                        <?php echo $vo->work_email; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-bold">Manager and contact number: </span><br>
                                                        <?php
                                                        echo $vo->line_manager . '<br>' . $vo->line_manager_tel . '<br>' . $vo->line_manager_email;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-bold">Date of course attending: </span><br>
                                                        <?php
                                                        $dates_of_course_attending = [
                                                            1 => 'Level 4 - w/c 14th June',
                                                            2 => 'Level 4 - w/c 5th July',
                                                            3 => 'Level 4 - w/c 26th July',
                                                            4 => 'Level 4 - w/c 13th September',
                                                            5 => 'Level 4 - w/c 11th October',
                                                            6 => 'Level 4 - w/c 1st November',
                                                            7 => 'Level 4 - w/c 22nd November',
                                                            8 => 'Level 4 - w/c 13th December',
                                                        ];
                                                        echo isset($dates_of_course_attending[$hs_form->date_of_course_attending]) ? $dates_of_course_attending[$hs_form->date_of_course_attending] : $hs_form->date_of_course_attending;
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray-light">
                                                    <th colspan="3">SECTION 2: Experience (to be completed by the delegate)</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3">
                                                        In order to attend the Electric vehicle training please complete the required fields below:
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 60%;">
                                                        I have extensive experience working with mechanical, electrical and an awareness of hazardous voltage components and systems.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s2c1 == 1 ? 'Yes' : 'No'; ?>
                                                    </td>
                                                    <td style="width: 30%;">
                                                        <?php echo $hs_form->s2d1; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 60%;">
                                                        I have qualifications and experience in the motor trade.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s2c2 == 1 ? 'Yes' : 'No'; ?>
                                                    </td>
                                                    <td style="width: 30%;">
                                                        <?php echo $hs_form->s2d2; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 60%;">
                                                        I have a thorough knowledge of Health and Safety best practice.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s2c3 == 1 ? 'Yes' : 'No'; ?>
                                                    </td>
                                                    <td style="width: 30%;">
                                                        <?php echo $hs_form->s2d3; ?>
                                                    </td>
                                                </tr>

                                            </table>
                                            <br>
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray-light">
                                                    <th colspan="3">SECTION 3: Self-Assessment (to be completed by the delegate)</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">
                                                        <p>Please read and complete the following details in order to attend this course;</p>
                                                        <p>Any pre-existing medical conditions which might prevent involvement</p>
                                                        <p class="text-bold"><i>To the best of my knowledge:</i></p>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        I do not have, or require the use of, a Pacemaker or ICD (implantable cardioverter defibrillator).
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s3c1 == 1 ? 'Yes' : 'No'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        I have no medical conditions and have had no surgical procedures that would prevent me from working on or near systems or components containing hazardous voltage and magnetic emissions.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s3c2 == 1 ? 'Yes' : 'No'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        I can clearly distinguish the colour 'orange.'
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s3c3 == 1 ? 'Yes' : 'No'; ?>
                                                    </td>
                                                </tr>

                                            </table>
                                            <br>
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-gray-light">
                                                    <th colspan="3">SECTION 4: Acknowledgement (to be completed by the delegate)</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">
                                                        <p>Please read carefully the  statements below and tick the box <u>only</u> if you agree fully agree with the statement</p>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        The information that I have given is accurate to the best of my knowledge at the time of signing this document.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s4c1 == 1 ? 'Yes' : 'No'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80%;">
                                                        I agree that if any of the information should change, I will inform my service manager, as soon as reasonably possible.
                                                    </td>
                                                    <td>
                                                        <?php echo $hs_form->s4c2 == 1 ? 'Yes' : 'No'; ?>
                                                    </td>
                                                </tr>

                                            </table>
                                            <table class="table bordered table-condensed">
                                                <tr>
                                                    <td>
                                                        <img src="do.php?_action=generate_image&<?php echo $hs_form->learner_sign != '' ? $hs_form->learner_sign : 'title=Not Signed&font=Signature_Regular.ttf&size=25'; ?>" />
                                                        <br>
                                                        <?php echo Date::to($hs_form->signed_at, Date::DATETIME); ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        ?>

                    </div>
                    <div class="tab-pane" id="tab_enrol">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box  box-info">
                                    <div class="box-header">
                                        <div class="box-title">Training Records</div>
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tr><th>Contract</th><th>Course / Framework</th><th>Status</th><th>Dates</th><th></th></tr>
                                                    <?php
                                                    $sql = <<<SQL
SELECT
	contracts.title AS contract, courses.title AS course, student_frameworks.title AS framework, tr.start_date, tr.target_date, tr.closure_date, tr.status_code, tr.id
FROM
	tr LEFT JOIN contracts ON tr.contract_id = contracts.id
	LEFT JOIN courses_tr ON tr.id = courses_tr.tr_id
	LEFT JOIN courses ON courses_tr.course_id = courses.id
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
	tr.username = '$vo->username'
ORDER BY
	tr.id DESC
;
SQL;
                                                    $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                                                    if(count($result) == 0)
                                                    {
                                                        echo '<tr><td colspan="5"><i>Currently, this learner has no training records.</i></td> </tr>';
                                                    }
                                                    else
                                                    {
                                                        foreach($result AS $row)
                                                        {
                                                            echo '<tr>';
                                                            echo '<td>' . $row['contract'] . '</td>';
                                                            echo '<td>';
                                                            echo 'Course: ' . $row['course'] . '<br>';
                                                            echo 'Framework: ' . $row['framework'];
                                                            echo '</td>';
                                                            if($row['status_code'] == '1')
                                                                echo '<td><label class="label label-primary">Continuing</label></td>';
                                                            elseif($row['status_code'] == '2')
                                                                echo '<td><label class="label label-success">Completed</label></td>';
                                                            elseif($row['status_code'] == '3')
                                                                echo '<td><label class="label label-danger">Withdrawn</label></td>';
                                                            elseif($row['status_code'] == '6')
                                                                echo '<td><label class="label label-warning">Temp. Withdrawn</label></td>';
                                                            else
                                                                echo '<td><label class="label label-info">' . $row['status_code'] . '</label></td>';
                                                            echo '<td>';
                                                            echo 'Start Date: ' . Date::toShort($row['start_date']) . '<br>';
                                                            echo 'Planned End Date: ' . Date::toShort($row['target_date']) . '<br>';
                                                            echo 'Actual End Date: ' . Date::toShort($row['closure_date']);
                                                            echo '</td>';
                                                            echo '<td><span class="btn btn-sm btn-block btn-info" onclick="window.location.href=\'do.php?_action=read_training_record&id='.$row['id'].'\'"><i class="fa fa-folder-open"></i> View</span> </td>';
                                                            echo '</tr>';
                                                        }
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <div class="callout callout-default">
                                                <p class="lead text-green text-bold">Enrolment - Create Training Record</p>
                                                <form method="post" class="form-horizontal" name="frmEnrolLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="false">
                                                    <input type="hidden" name="_action" value="save_start_training" />
                                                    <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
                                                    <input type="hidden" name="username" value="<?php echo $vo->username; ?>" />
                                                    <div class="form-group">
                                                        <label for="course_id" class="col-sm-5 control-label fieldLabel_compulsory">Course:</label>
                                                        <div class="col-sm-7">
                                                            <?php echo HTML::selectChosen('course_id', $ddlCourses, '', true, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="provider_location_id" class="col-sm-5 control-label fieldLabel_compulsory">Provider:</label>
                                                        <div class="col-sm-7">
                                                            <?php echo HTML::selectChosen('provider_location_id', $ddlLocations, '', true, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="contract_id" class="col-sm-5 control-label fieldLabel_compulsory">Contract:</label>
                                                        <div class="col-sm-7">
                                                            <?php echo HTML::selectChosen('contract_id', $ddlContracts, '', true, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="input_start_date" class="col-sm-5 control-label fieldLabel_compulsory">Start Date / Practical Period Start Date:</label>
                                                        <div class="col-sm-7">
                                                            <?php echo HTML::datebox('start_date', '', true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="input_end_date" class="col-sm-5 control-label fieldLabel_compulsory">Planned End Date / Practical Period End Date:</label>
                                                        <div class="col-sm-7">
                                                            <?php echo HTML::datebox('end_date', '', true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="input_end_date" class="col-sm-5 control-label fieldLabel_optional">Planned EPA Date:</label>
                                                        <div class="col-sm-7">
                                                            <?php echo HTML::datebox('planned_epa_date', '', true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="assessor" class="col-sm-5 control-label fieldLabel_optional">Assessor:</label>
                                                        <div class="col-sm-7">
                                                            <?php echo HTML::selectChosen('assessor', $ddlAssessors, '', true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="tutor" class="col-sm-5 control-label fieldLabel_optional">Tutor:</label>
                                                        <div class="col-sm-7">
                                                            <?php echo HTML::selectChosen('tutor', $ddlTutors, '', true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="verifier" class="col-sm-5 control-label fieldLabel_optional">IQA:</label>
                                                        <div class="col-sm-7">
                                                            <?php echo HTML::selectChosen('verifier', $ddlVerifiers, '', true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-3"></div>
                                                        <div class="col-sm-9">
                                                            <span class="btn btn-primary btn-block btnEnrolLearner"><b><i class="fa fa-graduation-cap"></i> Create New Training Record</b></span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_emails">
                        <span class="lead">Emails</span>
                        <p><br></p>
                        <div class="row">
                            <div class="col-sm-4 col-sm-offset-4">
                                <span id="btnCompose" class="btn btn-primary btn-block margin-bottom" onclick="$(this).hide(); $('#mailBox').hide(); $('#composeNewMessageBox').show();">Compose New Email</span>
                            </div>
                            <div class="col-sm-12" id="composeNewMessageBox" style="display: none;">
                                <?php echo $this->renderComposeNewMessageBox($link, $vo); ?>
                            </div>
                        </div>
                        <hr>
                        <?php echo $this->showSentEmails($link, $vo); ?>
                    </div>
		    <?php if(DB_NAME == "am_demo"){?>
                        <div class="tab-pane active" id="tab_bksb">
                            <h5 class="lead">BKSB</h5>
                            <?php if($bksbRandom) {?>
                            <div class="row">
                                <div class="col-sm-12">
                                                                    <p></p>
                                    <table class="table table-bordered table-condensed">
                                        <tbody><tr>
                                            <th>BKSB Username:</th><td>2330383</td>
                                            <th>BKSB ID:</th><td>68208107</td>
                                        </tr>
                                    </tbody></table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <span class="btn btn-xs btn-info" onclick="downloadIaFromBksb();"><i class="fa fa-refresh"></i> Refresh IA from BKSB</span>                            </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-condensed">
                                            <caption class="text-bold bg-gray">Initial Assessment</caption>
                                            <tbody><tr><th>Session ID</th><th>Course Component Name</th><th>Ability Measurement</th><th>Measuring Assessment Name</th><th>Measured At</th></tr>
                                            <tr><td class="small">574a5705-27e2-416b-bd4f-08da027a42c2</td><td>Functional Skills English</td><td align="center" class="text-bold">2.48</td><td>English Initial Assessment</td><td>11/03/2022 00:54:43</td></tr><tr><td class="small">7c02cc75-71c5-4079-6b55-08da0277de27</td><td>Functional Skills Maths</td><td align="center" class="text-bold">2.19</td><td>Maths Initial Assessment</td><td>12/03/2022 12:00:42</td></tr>                                    </tbody></table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-condensed">
                                            <caption class="text-bold bg-gray">Diagnostic Assessment</caption>
                                            <tbody><tr><th>Session ID</th><th>Course Component Name</th><th>Ability Measurement</th><th>Measuring Assessment Name</th><th>Measured At</th></tr>
                                            <tr><td class="small">bea403af-1a8c-453b-6b49-08da0277de27</td><td>Reading</td><td align="center" class="text-bold">2.54</td><td>English Diagnostic Assessment</td><td>11/03/2022 01:14:56</td></tr><tr><td class="small">bea403af-1a8c-453b-6b49-08da0277de27</td><td>Spelling, Punctuation and Grammar</td><td align="center" class="text-bold">2.59</td><td>English Diagnostic Assessment</td><td>11/03/2022 01:14:56</td></tr><tr><td class="small">bea403af-1a8c-453b-6b49-08da0277de27</td><td>Writing</td><td align="center" class="text-bold">2.62</td><td>English Diagnostic Assessment</td><td>11/03/2022 01:14:56</td></tr><tr><td class="small">bea403af-1a8c-453b-6b49-08da0277de27</td><td>Writing - Text</td><td align="center" class="text-bold">2.62</td><td>English Diagnostic Assessment</td><td>11/03/2022 01:14:56</td></tr><tr><td class="small">bea403af-1a8c-453b-6b49-08da0277de27</td><td>Reading - Text</td><td align="center" class="text-bold">2.55</td><td>English Diagnostic Assessment</td><td>11/03/2022 01:14:56</td></tr><tr><td class="small">bea403af-1a8c-453b-6b49-08da0277de27</td><td>Reading - Word</td><td align="center" class="text-bold">2.49</td><td>English Diagnostic Assessment</td><td>11/03/2022 01:14:56</td></tr><tr><td class="small">bea403af-1a8c-453b-6b49-08da0277de27</td><td>Grammar</td><td align="center" class="text-bold">2.09</td><td>English Diagnostic Assessment</td><td>11/03/2022 01:14:56</td></tr><tr><td class="small">bea403af-1a8c-453b-6b49-08da0277de27</td><td>Punctuation</td><td align="center" class="text-bold">2.79</td><td>English Diagnostic Assessment</td><td>11/03/2022 01:14:56</td></tr><tr><td class="small">bea403af-1a8c-453b-6b49-08da0277de27</td><td>Spelling</td><td align="center" class="text-bold">2.45</td><td>English Diagnostic Assessment</td><td>11/03/2022 01:14:56</td></tr>                                    </tbody></table>
                                    </div>
                                </div>
                            </div>
                            <?php } else { echo '<span class="btn btn-sm btn-primary disabled">Create Learner in BKSB</span>'; } ?>
                        </div>
                    <?php } ?>
                    <?php if(false && in_array(DB_NAME, ["am_demo"])) {?>
                    <div class="tab-pane" id="tab_safeguarding">

                        <div class="row">
                            <div class="col-sm-12">
                                <span id="btnAddIncident" class="btn btn-md btn-primary" onclick="$('#btnAddIncident').hide();$('#divNewIncident').show();"><i class="fa fa-plus"></i> Add Incident</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="box box-primary" style="display: none;" id="divNewIncident">
                                    <div class="box-header with-border"><span class="box-title">Provide incident details</span></div>
                                    <form role="form">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="incident_date">Date</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                    <input type="text" class="form-control pull-right" id="incident_date">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="incident_time">Time</label>
                                                <div class="input-group time">
                                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                                    <input type="text" class="form-control pull-right" id="incident_time">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="categories">Category</label>
                                                <div>
                                                    <select data-placeholder="Select Category" class="chosen-select" id="categories">
                                                        <option value="">Select Category</option>
                                                        <option value="Suicidal">Suicidal</option>
                                                        <option value="Welfare">Welfare</option>
                                                        <option value="Radicalisation">Radicalisation</option>
                                                        <option value="Employment">Employment</option>
                                                        <option value="Medical Issues">Medical Issues</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="alert_users">Users</label>
                                                <div>
                                                    <select data-placeholder="Select Users" class="chosen-select" multiple id="alert_users">
                                                        <option value="">Select</option>
                                                        <?php
                                                        $users = DAO::getSingleColumn($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users LIMIT 50");
                                                        foreach($users AS $user)
                                                            echo '<option value="' . $user . '">' . $user . '</option>';
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="agencies_contacted">Agencies Contacted</label>
                                                <div>
                                                    <select data-placeholder="Select Agencies" class="chosen-select" multiple id="agencies_contacted">
                                                        <option value="">Select</option>
                                                        <?php
                                                        $orgs = DAO::getSingleColumn($link, "SELECT legal_name FROM organisations LIMIT 50");
                                                        foreach($orgs AS $o)
                                                            echo '<option value="' . $o . '">' . $o . '</option>';
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Detail</label>
                                                <textarea class="form-control" rows="3" placeholder="Enter detail..."></textarea>
                                            </div>
                                        </div>

                                        <div class="box-footer">
                                            <button id="btnSaveIncident" onclick="$('#btnAddIncident').show();$('#divNewIncident').hide();" type="button" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                                            <button id="btnCloseDivNewIncident" onclick="$('#btnAddIncident').show();$('#divNewIncident').hide();" type="button" class="btn btn-default pull-right"><i class="fa fa-close"></i> Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box">
                                    <div class="box-header"><span class="box-title">Incidents</span></div>
                                    <div class="box-body">
                                        <table id="tblIncidents" class="table table-bordered table-striped">
                                            <thead>
                                            <tr><th style="width:10%"><i class="fa fa-clock-o"></i> DateTime</th><th style="width:40%"><i class="fa fa-warning"></i> Incident</th><th style="width:10%">Category</th><th style="width:15%"><i class="fa fa-users"></i> Staff Members</th><th style="width:15%"><i class="fa  fa-building"></i> Agencies Contacted</th><th style="width:10%">Actions</th></tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>01/02/2017 11:00</td>
                                                <td>Was seen looking at radical websites, when spoken to he was told about these sites by his older brother.</td>
                                                <td><span class="label label-danger">Radicalisation</span> </td>
                                                <td>Joe Bloggs<br>Bolggs John</td>
                                                <td>NHS</td>
                                                <td>
                                                    <div class="btn-group-vertical">
                                                        <button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
                                                        <button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>02/02/2017 11:00</td>
                                                <td>Noticed red marks on Helen's wrist. She quickly covered them with her jumper when she realise I had noticed.</td>
                                                <td><span class="label label-danger">Self Harm</span> </td>
                                                <td>Joe Bloggs<br>John Smith</td>
                                                <td>NHS</td>
                                                <td>
                                                    <div class="btn-group-vertical">
                                                        <button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
                                                        <button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>03/02/2017 11:00</td>
                                                <td>Due to his medical condition James has needed to go home early twice this week. Needs following up.</td>
                                                <td><span class="label label-warning">Medical Issues</span> </td>
                                                <td>Joe Bloggs<br>John Smith</td>
                                                <td>NHS</td>
                                                <td>
                                                    <div class="btn-group-vertical">
                                                        <button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
                                                        <button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>13/02/2017 11:00</td>
                                                <td>Received a phone call from Jenny this morning to say she couldn't come to work today as she has no money for the bus fare. A colleague has also noticed she has had no food for lunch in the past week.</td>
                                                <td><span class="label label-info">Welfare</span> </td>
                                                <td>Joe Bloggs<br>John Smith</td>
                                                <td>NHS</td>
                                                <td>
                                                    <div class="btn-group-vertical">
                                                        <button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
                                                        <button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>13/02/2017 11:00</td>
                                                <td>Paul has reported to me today that he has been upset about comments made by a colleague who is also contacting him outside of work hours.</td>
                                                <td><span class="label label-warning">Employment</span> </td>
                                                <td>Joe Bloggs<br>Paul</td>
                                                <td>NHS</td>
                                                <td>
                                                    <div class="btn-group-vertical">
                                                        <button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
                                                        <button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="tab_iv">
                        <span class="lead">Internal Validation</span>

                        <p class="callout callout-info"><i class="fa fa-info-circle"></i> This feature can be used to create organisation internal validation checks and save information for each learner</p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <?php
                                $questions = DAO::getSingleColumn($link, "SELECT description FROM rec_questions");
                                $i = 0;
                                foreach($questions AS $qs)
                                {
                                    $i++;
                                    $q = 'q'.$i;
                                    echo '<tr>';
                                    echo '<td style="width: 30%;">' . $qs . '</td>';
//				echo '<td><input class="yes_no_toggle" type="checkbox" name="PEI" id="PEI" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" /></td>';
                                    echo '<td style="width: 30%;">';
                                    echo <<<HTML
<table class="table">
	<tr><td class="text-success">Yes</td><td class="text-danger">No</td><td class="text-info">N/A</td></tr>
	<tr><td><input type="radio" name="$q"></td><td><input type="radio" name="$q"></td><td><input type="radio" name="$q"></td></tr>
</table>
HTML;

                                    echo '</td>';
                                    echo '<td style="width: 40%;"><textarea rows="3" style="width: 100%;"></textarea></td>';
                                    echo '</tr>';
                                }
                                echo '<tr><td colspan="3"><span class="btn btn-sm btn-block btn-primary"><i class="fa fa-save"></i> Save</span></td></tr>';
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <?php } ?>
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->


</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>


<script >
    $(function(){
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('.datepicker').attr('class', 'datepicker form-control');

        $('#frmEmailBody').summernote({
            toolbar:[
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'hr']]
            ],
            height:300,
            callbacks:{
                onImageUpload:function (files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            }
        });

    });

    function course_id_onchange(course, event)
    {
        var providers_locations = document.getElementById('provider_location_id');

        if(course.value != '')
        {
            course.disabled = true;

            providers_locations.disabled = true;
            ajaxPopulateSelect(providers_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_provider_locations&course_id=' + course.value);
            providers_locations.disabled = false;

            course.disabled =false;
        }
        else
        {
            emptySelectElement(providers_locations);
        }
    }

    function provider_location_id_onchange(location, event)
    {
        var assessors = document.getElementById('assessor');
        var tutors = document.getElementById('tutor');

        if(location.value != '')
        {
            location.disabled = true;

            assessors.disabled = true;
            ajaxPopulateSelect(assessors, 'do.php?_action=ajax_load_account_manager&subaction=load_assessors&location_id=' + location.value);
            assessors.disabled = false;

            tutors.disabled = true;
            ajaxPopulateSelect(tutors, 'do.php?_action=ajax_load_account_manager&subaction=load_tutors&location_id=' + location.value);
            tutors.disabled = false;

            location.disabled =false;
        }
        else
        {
            emptySelectElement(assessors);
            emptySelectElement(tutors);
        }
    }

    $('.btnEnrolLearner').on('click', function(){
        var form = document.forms['frmEnrolLearner'];

        if(form.elements["course_id"].value == '')
        {
            return alert('Please select course');
        }
        if(form.elements["provider_location_id"].value == '')
        {
            return alert('Please select location');
        }
        if(form.elements["contract_id"].value == '')
        {
            return alert('Please select contract');
        }
        if(form.elements["start_date"].value == '')
        {
            return alert('Please select start date');
        }
        if(form.elements["end_date"].value == '')
        {
            return alert('Please select end date');
        }

        form.submit();
    });

    function load_email_template_in_frmEmail()
    {
        var frmEmail = document.forms["frmEmail"];
        var learner_id = '<?php echo $vo->id; ?>';
        var email_template_type = frmEmail.frmEmailTemplate.value;

        if(email_template_type == '')
        {
            alert('Please select template from templates list');
            frmEmail.frmEmailTemplate.focus();
            return false;
        }

        function loadAndPrepareLearnerEmailTemplateCallback(client)
        {
            if(client && client.status == 200)
            {
                var result = $.parseJSON(client.responseText);
                if(result.status == 'error')
                {
                    alert(result.message);
                    return;
                }

                $("#frmEmailBody").summernote('code', result.email_content);
            }
        }

        var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=loadAndPrepareLearnerEmailTemplate' +
            '&entity_type=learner&entity_id=' + learner_id +
            '&template_type=' + email_template_type, null, null, loadAndPrepareLearnerEmailTemplateCallback);
    }

    function sendEmail()
    {
        var frmEmail = document.forms["frmEmail"];
        if(!validateForm(frmEmail))
        {
            return;
        }

        frmEmail.frmEmailTemplate.value = "";

        var client = ajaxPostForm(frmEmail);
        if(client)
        {
            if(client.responseText == 'success')
                alert('Email has been sent successfully.');
            else
                alert('Unknown Email Error: Email has not been sent.');
        }
        else
        {
            alert(client);
        }
        window.location.reload();
    }

    function viewEmail(tbl_emails_id)
    {
        var postData = 'do.php?_action=ajax_helper'
            + '&subaction=view_sent_email'
            + '&id=' + encodeURIComponent(tbl_emails_id)
        ;

        var req = ajaxRequest(postData);
        $("<div></div>").html(req.responseText).dialog({
            id: "dlg_lrs_result",
            title: "View Sent Email",
            resizable: false,
            modal: true,
            width: 750,
            height: 500,

            buttons: {
                'Close': function() {$(this).dialog('close');}
            }
        });

    }

</script>
</body>
</html>
