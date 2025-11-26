<?php /* @var $vo Employer */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Employer</title>
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

    <style>
        .row.is-flex {
            display: flex;
            flex-wrap: wrap;
        }
        .row.is-flex > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }
        .tooltip {
            position: relative;
            display: inline-block;
        }


    </style>

</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Employer</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=edit_employer&edit=1&id=<?php echo $vo->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
		        <?php if($_SESSION['user']->isAdmin()){ ?>
                    <span class="btn btn-xs btn-danger" onclick="deleteEmployer();"><i class="fa fa-remove"></i> Delete</span>
                <?php } ?>
                <?php if(SystemConfig::getEntityValue($link, 'module_onboarding') && in_array(DB_NAME, ["am_lead", "am_lead_demo"])){ ?>
                    <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=view_employer_tna&employer_id=<?php echo $vo->id; ?>';"><i class="fa fa-table"></i> TNA</span>
                <?php } ?>
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
        <div class="col-sm-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1 class="box-title text-bold">
                        <?php echo $vo->legal_name; ?>
                    </h1>
                    <?php
                    echo '<span style="display: inline;"> ';
                    $trophy = $vo->company_rating;
                    if($trophy == 'G')
                        echo '<i title="GOLD Employer" class="fa fa-trophy fa-2x" style="color: gold;"></i>';
                    elseif($trophy == 'S')
                        echo '<i title="Silver Employer" class="fa fa-trophy fa-2x" style="color: silver;"></i>';
                    elseif($trophy == 'B')
                        echo '<i title="Bronze Employer" class="fa fa-trophy fa-2x" style="color: #cd7f32;"></i>';
                    echo '</span>';
                    ?>
                    <br>
                    <small><?php echo $vo->trading_name; ?></small>
                    <div class="pull-right">
                        <span class="label <?php echo $vo->active == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->active=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Active</span>
                        <?php
                        if(in_array(DB_NAME, ["am_duplex"]) && $vo->org_status != '')
                        {
                            echo '<span class="label label-info">' . DAO::getSingleValue($link, "SELECT description FROM lookup_org_status WHERE id = '{$vo->org_status}'") . '</span>';
                        }
                        ?>
                        <span class="label <?php echo $vo->levy_employer == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->levy_employer=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Levy Employer</span>
                        <span class="label <?php echo $vo->health_safety == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->health_safety=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Health and Safety</span>
                        <span class="label <?php echo $vo->ono == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->ono=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> ONA</span>
                        <span class="label <?php echo $vo->due_diligence == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->due_diligence=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Due Diligence</span>
                        <span class="label <?php echo $vo->due_diligence == '1'?'label-success':'label-danger'; ?>"><?php echo $vo->due_diligence=='1'?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Due Diligence</span>
                        <?php if(SystemConfig::getEntityValue($link, 'module_onboarding')  && in_array(DB_NAME, ["am_lead", "am_lead_demo"])){ ?>
                            <span class="label <?php echo $is_tna_completed > 0 ?'label-success':'label-danger'; ?>"><?php echo $is_tna_completed > 0?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> TNA Form</span>
                        <?php } ?>
                    </div>
                </div>
                <div class="box-body">
                    <dl class="dl-horizontal">
                        <dt>EDRS:</dt><dd><span class="text-muted"><?php echo $vo->edrs; ?></span></dd>
                        <dt>Company Number:</dt>
                        <dd>
                            <span class="text-muted">
                                <a href="https://beta.companieshouse.gov.uk/company/<?php echo $vo->company_number; ?>" target="_blank"><?php echo $vo->company_number; ?></a>
                            </span>
                        </dd>
                        <dt>VAT Number:</dt><dd><span class="text-muted"><?php echo $vo->vat_number; ?></span></dd>
                        <dt>Retailer Code:</dt><dd><span class="text-muted"><?php echo $vo->retailer_code; ?></span></dd>
                        <dt>Employer Code:</dt><dd><span class="text-muted"><?php echo $vo->employer_code; ?></span></dd>
                        <dt>Sector:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$sector); ?></span></dd>
                        <dt>Group:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$group_employer); ?></span></dd>
                        <dt>Region:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$vo->region); ?></span></dd>
                        <dt>Size:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$size); ?></span></dd>
                        <dt>On-site Employees:</dt><dd><span class="text-muted"><?php echo htmlspecialchars((string)$vo->site_employees); ?></span></dd>
                        <?php if($vo->levy_employer == '1') {?>
                            <dt>Levy Amount:</dt><dd><span class="text-muted"><?php echo $vo->levy; ?></span></dd>
                        <?php } ?>
                        <dt>URL:</dt><dd><span class="text-muted"><small><?php echo htmlspecialchars((string)$vo->url); ?></small></span></dd>
                        <dt>Account Manager:</dt><dd><span class="text-muted"><small><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE username = '{$vo->creator}'"); ?></small></span></dd>
                        <dt>System ID:</dt><dd><span class="text-muted"><?php echo $vo->id; ?></span></dd>
                        <?php if ( SystemConfig::getEntityValue($link, 'onefile.integration') ) { ?>
                        <dt>OneFile ID:</dt><dd><span class="text-muted"><?php echo $vo->onefile_placement_id == '' ? '<span class="label label-danger">Not Linked</span>' : '<span class="label label-success">Linked (OneFile Placement ID: ' . $vo->onefile_placement_id . ')</span>'; ?></span></dd>
                        <?php } ?>
                    </dl>

                    <?php
                    if (SystemConfig::getEntityValue($link, "module_crm"))
                    {
                        $from_crm = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_opportunities WHERE TRIM(company) LIKE '" . addslashes((string)$vo->legal_name) . "'");
                        if($from_crm > 0)
                        {
                            $crm_url = "{$_SERVER['SCRIPT_URI']}?_action=view_opportunities&_reset=1&filter_company={$vo->legal_name}";
                            echo '<span class="btn btn-xs btn-success" onclick="window.location.href=\''.$crm_url.'\'">Linked Opportunities</span>';
                        }
                    }
                    ?>
                </div>
                <div class="box-footer">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <span class="box-title">Manage Tags</span>
                            <span class="btn btn-xs btn-primary pull-right" onclick="$('#modalTags').modal('show');"><i class="fa fa-tags"></i> Assign Tags</span>
                        </div>
                        <div class="box-body">
                            <?php 
                            $employertags = DAO::getResultset($link, "SELECT tags.id, tags.name FROM tags INNER JOIN taggables ON tags.id = taggables.tag_id WHERE taggables.taggable_type = 'Employer' AND taggables.taggable_id = '{$vo->id}' ORDER BY tags.name", DAO::FETCH_ASSOC);
                            foreach($employertags AS $employertag)
                            {
                                echo '<div style="margin: 3px;">';
                                echo '<span class="label label-success label-lg">' . $employertag['name'] . ' &nbsp; &nbsp; ';
                                echo '<i class="fa fa-times fa-lg" style="cursor:pointer;" title="Detach this tag from this record" onclick="detach_tag(\''.$employertag['id'].'\', \''.$vo->id.'\', \'Employer\');"></i>';
                                echo '</span>';
                                echo '</div>';
                            }
                            ?>
                            <div class="modal fade" id="modalTags" role="dialog" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form class="form-horizontal" method="post" name="frmTags" id="frmTags" method="post" action="do.php?_action=assign_tags">
                                            <input type="hidden" name="formName" value="frmTags" />
                                            <input type="hidden" name="taggable_type" value="Employer" />
                                            <input type="hidden" name="taggable_id" value="<?php echo $vo->id; ?>" />
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h5 class="modal-title text-bold">Assign Tags</h5>
                                            </div>
                                            <div class="modal-body">
                                            
                                                <div class="control-group">
                                                    <label class="control-label" for="tag">Select Tag:</label> &nbsp;
                                                    <?php echo HTML::selectChosen('tag', Tag::getTagsForSelectList($link, 'Employer'), '', true); ?>
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
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php if(SystemConfig::getEntityValue($link, "module_crm") && in_array(DB_NAME, ["am_demo", "am_ela", "am_presentation"])){?>
                        <li class="active"><a href="#tabEnquiries" data-toggle="tab">Enquiries <label class="label label-info"><?php echo $enquiries_count; ?></label></a></li>
                        <li><a href="#tabLeads" data-toggle="tab">Leads <label class="label label-info"><?php echo $leads_count; ?></label></a></li>
                        <li><a href="#tabOpportunities" data-toggle="tab">Opportunities <label class="label label-info"><?php echo $opportunities_count; ?></label></a></li>
                    <?php } ?>
                    <li class="<?php echo (SystemConfig::getEntityValue($link, "module_crm") && in_array(DB_NAME, ["am_demo", "am_ela", "am_presentation"])) ? '' : 'active'; ?>"><a href="#tabLocations" data-toggle="tab">Locations <label class="label label-info"><?php echo $locations_count; ?></label></a></li>
                    <li><a href="#tabLearners" data-toggle="tab">Learners <label class="label label-info"><?php echo $learners_count; ?></label></a></li>
                    <li><a href="#tabUsers" data-toggle="tab">System Users <label class="label label-info"><?php echo $users_count; ?></label></a></li>
                    <li><a href="#tabCRMNotes" data-toggle="tab">CRM Notes <label class="label label-info"><?php echo $crm_notes_count; ?></label></a></li>
                    <li><a href="#tabCRMContacts" data-toggle="tab">CRM Contact <label class="label label-info"><?php echo $crm_contacts_count; ?></label></a></li>
                    <?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) { ?>
                        <li><a href="#tabComplaints" data-toggle="tab">Complaints <label class="label label-info"><?php echo $learner_complaints_count + $complaints_count; ?></label></a></li>
                    <?php } ?>
                    <?php if(SystemConfig::getEntityValue($link, 'module_recruitment_v2')) {?><li><a href="#tabVacancies" data-toggle="tab">Vacancies</a></li><?php } ?>
                    <li><a href="#tabFiles" data-toggle="tab"> File Repository <label class="label label-info"><?php echo $files_count; ?></label></a></li>
		            <?php if(!in_array(DB_NAME, ["am_ela", "am_demo"])){?>
                    <li><a href="#tabHS" data-toggle="tab"> Health & Safety <label class="label label-info"><?php echo $hs_count; ?></a></li>
		            <?php } ?>	
                    <?php if(SystemConfig::getEntityValue($link, 'module_onboarding') && in_array(DB_NAME, ["am_lead", "am_lead_demo"])) {?><li><a href="#tabEmailTemplates" data-toggle="tab"> On-boarding</a></li><?php } ?>
		            <?php if(!in_array(DB_NAME, ["am_ela", "am_demo"])){?>
                    <li><a href="#tabAgreements" data-toggle="tab"> Agreements <label class="label label-info"><?php echo $agreements_count; ?></a></li>
                    <?php } ?>

                </ul>
                <div class="tab-content">
                    <?php if(SystemConfig::getEntityValue($link, "module_crm") && in_array(DB_NAME, ["am_demo", "am_ela", "am_presentation"])){?>
                        <div class="active tab-pane" id="tabEnquiries">
                            <p>
                                <span onclick="window.location.href='do.php?_action=edit_enquiry&org_id=<?php echo $vo->id; ?>&org_type=employer'" class="btn btn-primary btn-xs">
                                    <i class="fa fa-plus"></i> Add New Enquiry
                                </span>
                            </p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php $this->renderEnquiries($link, $vo->id); ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabLeads">
                            <p>
                                <span onclick="window.location.href='do.php?_action=edit_lead&org_id=<?php echo $vo->id; ?>&org_type=employer'" class="btn btn-primary btn-xs">
                                    <i class="fa fa-plus"></i> Add New Lead
                                </span>
                            </p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php $this->renderLeads($link, $vo->id); ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabOpportunities">

                            <div class="row">
                                <div class="col-sm-12">
                                    <?php $this->renderOpportunities($link, $vo->id); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="<?php echo (SystemConfig::getEntityValue($link, "module_crm") && in_array(DB_NAME, ["am_demo", "am_ela", "am_presentation"])) ? '' : 'active'; ?> tab-pane" id="tabLocations">
                        <p><span onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=employer'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Location</span></p>
                        <div class=""><?php $this->renderLocations($link,'read_employer_v3'); ?></div>
                    </div>
                    <div class="tab-pane" id="tabLearners">
                        <p>
			                            <span onclick="window.location.href='do.php?_action=<?php echo in_array(DB_NAME, ["am_duplex"]) ? 'edit_learner_duplex' : 'add_learner';?>&organisations_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs">
                                			<i class="fa fa-plus"></i> Add New Learner
                            				</span>
                        </p>
                        <p><input id="txtSearchLearners" type="text" placeholder="Search.."></p>
                        <div class="divLearners"><?php $this->renderLearners($link); ?></div>
                    </div>
                    <div class="tab-pane" id="tabUsers">
                        <p><span onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=Admin&people_type=1'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Administrator</span></p>
                        <div class=""><?php $this->renderSystemUsers($link); ?></div>
                    </div>
                    <div class="tab-pane" id="tabCRMNotes">
                        <p><span onclick="window.location.href='do.php?_action=edit_crm_note&mode=new&organisations_id=<?php echo $vo->id; ?>&organisation_type=read_workplace'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New CRM Note</span></p>
                        <div class="table-responsive"><?php $this->renderCRMNotes($link,'read_employer'); ?></div>
                    </div>
                    <div class="tab-pane" id="tabCRMContacts">
                        <p><span onclick="window.location.href='do.php?_action=edit_crm_contact&org_type=employer&org_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New CRM Contact</span></p>
                        <div class=""><?php $this->renderCRMContacts($link); ?></div>
                    </div>
                    <?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) { ?>
                        <div class="tab-pane" id="tabComplaints">
                            <p><span onclick="window.location.href='do.php?_action=edit_complaint_employer&id=&record_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Complaint</span></p>
                            <div class="table-responsive">
                                <span class="lead">Learners Complaints</span>
                                <?php echo $this->renderLearnerComplaints($link, $vo); ?>
                                <span class="lead">Employer Complaints</span>
                                <?php echo $this->renderEmployerComplaints($link, $vo); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(SystemConfig::getEntityValue($link, 'module_recruitment_v2')){ ?>
                        <div class="tab-pane" id="tabVacancies">
                            <p><span onclick="window.location.href='do.php?_action=rec_edit_vacancy&selected_tab=&employer_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Vacancy</span></p>
                            <div class="table-responsive"><?php $vacancies->render($link); ?></div>
                        </div>
                    <?php } ?>
                    <div class="tab-pane" id="tabFiles">
                        <p><br></p>

                        <div class="row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4">
                                <div class="box">
                                    <div class="box-body">
                                        <form name="frmUploadFile" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository" ENCTYPE="multipart/form-data">
                                            <input type="hidden" name="_action" value="save_employer_repository" />
                                            <input type="hidden" name="emp_id" value="<?php echo $vo->id;?>" />
                                            <input class="compulsory" type="file" name="uploaded_employer_file" />
                                            <span id="uploadFileButton" class="btn btn-sm btn-primary pull-right" onclick="uploadFile();"><i class="fa fa-upload"></i></span>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=""><?php echo $this->renderFileRepository($link, $vo); ?></div>
                    </div>
                    <?php if(!in_array(DB_NAME, ["am_ela", "am_demo"])){?>
                    <div class="tab-pane" id="tabHS">
                        <div class="table-responsive"><p><?php echo $this->renderHS($link, $vo); ?></p></div>
                    </div>
		            <?php } ?>
                    <?php if(SystemConfig::getEntityValue($link, 'module_onboarding') && in_array(DB_NAME, ["am_lead", "am_lead_demo"])){ ?>
                        <div class="tab-pane" id="tabEmailTemplates">
                            <div class="row">
                                <div class="col-sm-12">
                                    <span class="lead text-bold">Emails</span>
                                </div>
                                <div class="col-sm-4">
                                    <span class="text-info text-bold pull-right">Employer TNA Form URL</span>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input id="tna_url" readonly type="text" class="form-control" value="<?php echo OnboardingHelper::generateEmployerTnaUrl($vo->id); ?>">
                                        <span class="input-group-addon" title="Click to copy the URL" onclick="copyUrl();" >
										<i class="fa fa-copy"></i>
									</span>
                                    </div>
                                    <span id="copyUrlTooltip"></span>
                                </div>
                            </div>

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
                            <div class="row">
                                <div class="col-sm-12">
                                    <span class="lead text-bold">Onboarding Learners</span>
                                </div>
                                <div class="col-sm-12">
                                    <div class="tab-pane" id="tabLearners">
                                        <p><span onclick="window.location.href='do.php?_action=add_ob_learners&employer_id=<?php echo $vo->id; ?>'" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Add New Learner</span></p>

                                        <p><input id="txtSearchObLearners" type="text" placeholder="Search.."></p>
                                        <div class="divObLearners"><?php $this->renderObLearners($link); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
		   
                    <?php if(!in_array(DB_NAME, ["am_ela", "am_demo"])){?>
                    <div class="tab-pane" id="tabAgreements">
                        <p><span onclick="createNewAgreement();" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i> Create New Agreement</span></p>
                        <div class="table-responsive"><p><?php echo $this->renderAgreements($link, $vo); ?></p></div>
                    </div>
                    <?php } ?>
                    	
                    
                </div>
            </div>
        </div>
    </div>

    <div id="dialogDeleteFile" style="display:none" title="Delete file"></div>

    <div id="dialogDeleteRecord" style="display:none" title="Delete Record"></div>

    <div class="modal fade" id="emailModal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title text-bold">Email Editor</h5>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" class="form-horizontal" method="post" name="frmAgreementEmail" id="frmAgreementEmail" method="post" action="do.php">
                        <input type="hidden" name="_action" value="ajax_email_actions" />
                        <input type="hidden" name="subaction" value="sendEmail" />
                        <input type="hidden" name="frmEmailEntityType" value="organisations" />
                        <input type="hidden" name="frmEmailEntityId" value="<?php echo $vo->id; ?>" />
                        <input type="hidden" name="agreement_id" value="" />
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="control-group"><label class="control-label" for ="frmEmailTo">To:</label>
                                    <input autocomplete="off" type="text" name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="control-group"><label class="control-label" for ="frmEmailSubject">Subject:</label>
                                    <input autocomplete="off" type="text" name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" value="Employer Agreement">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for ="frmEmailBody">Message:</label>
                            <textarea name="frmEmailBody" id="frmEmailBody" class="form-control"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#emailModal').modal('hide');">Cancel</button>
                    <button type="button" id="btnEmailModalSave" class="btn btn-primary btn-md"><i class="fa fa-send"></i> Send</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.js"></script>
<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

<script>
    $(function() {
        $('#frmEmailBody').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'hr']]
            ],
            height: 300,
            callbacks: {
                onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            }
        });

        $('#dialogDeleteFile').dialog({
            modal: true,
            width: 450,
            closeOnEscape: true,
            autoOpen: false,
            resizable: false,
            draggable: false,
            buttons: {
                'Delete': function() {
                    $(this).dialog('close');
                    var client = ajaxRequest('do.php?_action=delete_file&f=' + encodeURIComponent($(this).data('filepath')));
                    if(client){
                        window.location.reload();
                    }
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });

        $('#dialogDeleteRecord').dialog({
            modal: true,
            width: 450,
            closeOnEscape: true,
            autoOpen: false,
            resizable: false,
            draggable: false,
            buttons: {
                'Delete': function() {
                    var record = $(this).data('record');
                    var client = ajaxRequest('do.php?_action=delete_record_from_org&'+$.param(record));
                    if(client)
                    {
                        $(this).dialog('close');
                        $('<div>'+client.responseText+'</div>').dialog({
                            title: 'Deletion result',
                            buttons: {
                                'OK': function() {
                                    $( this ).dialog( "close" );
                                    window.location.reload();
                                }
                            }
                        });
                    }
                    else
                    {
                        alert(client);
                    }
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });

        $("#txtSearchLearners").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".divLearners .col-sm-3").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $("#txtSearchObLearners").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tblObLearners tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

	$("button#btnEmailModalSave").click(function(){
            var frmEmail = document.forms['frmAgreementEmail'];
            var agreement_id = frmEmail.agreement_id.value;

            if(!validateForm(frmEmail))
            {
                return;
            }

            var client1 = ajaxPostForm(document.forms['frmAgreementEmail']);
            if(client1 && client1.responseText == 'success')
            {
                var client2 = ajaxRequest('do.php?_action=ajax_helper&subaction=updateEmployerAgreementStatus&agreement_id='+agreement_id+'&status=<?php echo EmployerAgreement::TYPE_SENT; ?>');
                if(client2 && client2.status == 200)
                {
                    window.location.reload();
                }
            }
        });

    });

    function deleteFile(path)
    {
        var $dialog = $('#dialogDeleteFile');

        $dialog.data('filepath', path);

        var filename = path.split('/').pop();
        $dialog.html("<p>Delete <b>" + filename + "</b>.</p><p>Deletion is permanent and irrecoverable.  Continue?</p>");

        $dialog.dialog("open");
    }

    function uploadFile()
    {
        var myForm = document.forms["frmUploadFile"];
        if(validateForm(myForm) == false)
        {
            return false;
        }
        myForm.submit();
    }

    function deleteRecord(record_type, record_username, record_id, record_name)
    {
        var record = {};
        record["record_type"] = record_type;
        record["record_username"] = record_username;
        record["record_id"] = record_id;

        var $dialog = $('#dialogDeleteRecord');

        $dialog.data('record', record);

        $dialog.html('<p><b>'+record_type.replace('_', ' ').toUpperCase()+'</b><br>'+record_name+' ('+record_username+')</p>' + '<p>Deletion is permanent and irrecoverable.  Continue?</p>');

        $dialog.dialog("open");
    }

    function copyUrl()
    {
        var copyText = document.getElementById("tna_url");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand('copy');
        var tooltip = document.getElementById("copyUrlTooltip");
        tooltip.innerHTML = "Copied: " + copyText.value;
        $("#copyUrlTooltip").show().delay( 1000 ).hide(0);
    }

    function sendFile(file, editor, welEditable)
    {
        data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: "POST",
            url: "do.php?_action=ajax_actions&subaction=uploadImageToEmailEditor",
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) {
                //editor.insertImage(welEditable, url);
                $('#compose-textarea').summernote('editor.insertImage', url);
            }
        });
    }

    function sendEmail()
    {
        var frmEmail = document.forms["frmEmail"];
        if(!validateForm(frmEmail))
        {
            return;
        }

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

    function load_email_template_in_frmEmail()
    {
        var frmEmail = document.forms["frmEmail"];
        var employer_id = '<?php echo $vo->id; ?>';
        var email_template_type = frmEmail.frmEmailTemplate.value;

        if(email_template_type == '')
        {
            alert('Please select template from templates list');
            frmEmail.frmEmailTemplate.focus();
            return false;
        }

        function loadAndPrepareEmailTemplateCallback(client)
        {
            if(client.status == 200)
                $("#frmEmailBody").summernote("code", client.responseText);
        }

        var client = ajaxRequest('do.php?_action=ajax_actions&subaction=loadAndPrepareEmailTemplate' +
            '&entity_type=employers&entity_id=' + employer_id +
            '&template_type=' + email_template_type, null, null, loadAndPrepareEmailTemplateCallback);
    }

    function frmEmailTemplate_onchange(template)
    {
        if(template.value == "EMPLOYER_TNA")
        {
            var client_name = '<?php echo $client_name = SystemConfig::getEntityValue($link, "client_name"); ?>';
            document.forms["frmEmail"].frmEmailSubject.value = client_name + " - Training Needs Analysis";
        }
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

    function deleteEmployer()
    {
        if(window.confirm("Delete this employer?"))
        {
            window.location.replace('do.php?_action=delete_employer&id=<?php echo $vo->id; ?>');
        }
    }

    function createNewAgreement()
    {
        window.location.href='do.php?_action=edit_employer_agreement&id=&employer_id=<?php echo $vo->id; ?>';
    }

    function load_and_prepare_agreement_email(agreement_id)
    {
        var frmEmail = document.forms["frmAgreementEmail"];
        frmEmail.elements['agreement_id'].value = agreement_id;

        function getEmployerAgreementTemplateCallback(client)
        {
            if(client.status == 200)
            {
                $("#frmEmailBody").summernote("code", client.responseText);
            }
            $('#emailModal').modal('show');
        }

        var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=getEmployerAgreementTemplate' +
            '&agreement_id=' + agreement_id , null, null, getEmployerAgreementTemplateCallback);
    }

	function downloadAgreement(id) {
            window.location.href = "do.php?_action=generate_pdf&subaction=employerAgreement&id=" + id;
        }

</script>
</body>
</html>
