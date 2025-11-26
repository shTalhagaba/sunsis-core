<?php /* @var $framework Framework */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $isFramework ? 'Framework' : 'Programme'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $isFramework ? 'Framework' : 'Programme'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                <?php if(!in_array($_SESSION['user']->type, [5, 12, 13])) { ?>
                    <span class="btn btn-sm btn-default" onclick="window.location.replace('do.php?_action=edit_framework&framework_id=<?php echo $framework->id; ?>');"><i class="fa fa-edit"></i> Edit</span>
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

<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-7">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title"><?php echo $framework->title; ?></h2>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Status</th>
                                    <td><?php echo $framework->active == "1" ? '<label class="label label-success">Active</label>' : '<label class="label label-info">Inactive</label>'; ?></td>
                                </tr>
                                <tr>
                                    <th>Provider Duration</th>
                                    <td><?php echo $framework->duration_in_months; ?> months</td>
                                </tr>
                                <tr>
                                    <th>Programme Type</th>
                                    <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(ProgType, ' ' , ProgTypeDesc) FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE ProgType = '{$framework->framework_type}'"); ?></td>
                                </tr>
                                <?php if($isFramework) { ?>
                                    <tr>
                                        <th>Framework Code</th>
                                        <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(FworkCode, ' ', IssuingAuthorityTitle) FROM lars201718.`Core_LARS_Framework` WHERE FworkCode = '{$framework->framework_code}'"); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Pathway Code</th>
                                        <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(PwayCode, ' ' , PathwayName) FROM lars201718.`Core_LARS_Framework` WHERE PwayCode = '{$framework->PwayCode}' AND FworkCode = '{$framework->framework_code}' AND ProgType = '{$framework->framework_type}'"); ?></td>
                                    </tr>
                                    <tr>
                                        <th>LARS Duration</th>
                                        <td><?php echo DAO::getSingleValue($link, "SELECT Round(Duration) FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipCode = '{$framework->framework_code}' and ApprenticeshipType='FWK' order by EffectiveFrom limit 0,1"); ?></td>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <th>LARS Standard Code</th>
                                        <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(StandardCode, ' ' , StandardName) FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$framework->StandardCode}'"); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Standard Reference Number</th>
                                        <td><?php echo $framework->standard_ref_no; ?></td>
                                    </tr>
                                    <tr>
                                        <th>LARS Duration</th>
                                        <td><?php echo DAO::getSingleValue($link, "SELECT Round(Duration) FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipCode = '{$framework->StandardCode}' and ApprenticeshipType='STD' order by EffectiveFrom limit 0,1"); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Maximum Funding Cap</th>
                                        <td><?php echo DAO::getSingleValue($link, "SELECT Round(MaxEmployerLevyCap) FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipCode = '{$framework->StandardCode}' and ApprenticeshipType='STD' order by EffectiveFrom limit 0,1"); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Standard Document</th>
                                        <td><A href="<?php echo DAO::getSingleValue($link, "SELECT UrlLink FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$framework->StandardCode}'"); ?>" target="_blank"><?php echo DAO::getSingleValue($link, "SELECT UrlLink FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$framework->StandardCode}'"); ?></A></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th>EPA Organisation</th>
                                    <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(EPA_ORG_ID, ' - ', EP_Assessment_Organisations) FROM central.`epa_organisations` WHERE EPA_ORG_ID = '{$framework->epa_org_id}'"); ?></td>
                                </tr>
                                <tr>
                                    <th>EPA Assessor</th>
                                    <td>
                                        <?php
                                        $sql = <<<SQL
SELECT CONCAT(
    COALESCE(title, ' '),
    `firstnames`, ' ',
    `surname`,
    ' (',
    COALESCE(`address1`, ''), ' ',
    COALESCE(`address4`, ' '), ' ',
    `postcode`, ') ',
    COALESCE(`email`, ''), ' '
  ) FROM epa_org_assessors WHERE id = '$framework->epa_org_assessor_id';
SQL;
                                        echo DAO::getSingleValue($link, $sql); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Off the Job Hours</th>
                                    <td><?php echo $framework->otj_hours; ?></td>
                                </tr>
                                <?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])){?>
                                    <tr>
                                        <th>Gateway Forecast</th>
                                        <td><?php echo $framework->gateway_forecast; ?> months</td>
                                    </tr>
                                    <tr>
                                        <th>EPA Forecast</th>
                                        <td><?php echo $framework->epa_forecast; ?> months</td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th>Comments</th>
                                    <td class="small"><?php echo nl2br((string) $framework->comments); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="nav-tabs-custom bg-gray-light">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tags" data-toggle="tab" aria-expanded="true">Tags</a></li>
                        <li><a href="#tab_funding" data-toggle="tab">Funding</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_tags">
                            <span class="btn btn-xs btn-primary" onclick="$('#modalTags').modal('show');"><i class="fa fa-tags"></i> Assign Tags</span><hr>
                            <?php 
                            $programme_tags = DAO::getResultset($link, "SELECT tags.id, tags.name FROM tags INNER JOIN taggables ON tags.id = taggables.tag_id WHERE taggables.taggable_type = 'Programme' AND taggables.taggable_id = '{$framework->id}' ORDER BY tags.name", DAO::FETCH_ASSOC);
                            foreach($programme_tags AS $programme_tag)
                            {
                                echo '<div style="margin-top: 3px; display:inline-block; margin-left: 3px;">';
                                echo '<span class="label label-success label-lg">' . $programme_tag['name'] . ' &nbsp; &nbsp; ';
                                echo '<i class="fa fa-times fa-lg" style="cursor:pointer;" title="Detach this tag from this record" onclick="detach_tag(\''.$programme_tag['id'].'\', \''.$framework->id.'\', \'Programme\');"></i>';
                                echo '</span>';
                                echo '</div>';
                            }
                            ?>
                            <div class="modal fade" id="modalTags" role="dialog" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form class="form-horizontal" method="post" name="frmTags" id="frmTags" method="post" action="do.php?_action=assign_tags">
                                            <input type="hidden" name="formName" value="frmTags" />
                                            <input type="hidden" name="taggable_type" value="Programme" />
                                            <input type="hidden" name="taggable_id" value="<?php echo $framework->id; ?>" />
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h5 class="modal-title text-bold">Assign Tags</h5>
                                            </div>
                                            <div class="modal-body">
                                            
                                                <div class="control-group">
                                                    <label class="control-label" for="tag">Select Tag:</label> &nbsp;
                                                    <?php echo HTML::selectChosen('tag', Tag::getTagsForSelectList($link, 'Programme'), '', true); ?>
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
                        <div class="tab-pane" id="tab_funding">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Aim Reference</th>
                                        <th>16-18 Apps</th>
                                        <th>19-23 Apps</th>
                                        <th>24+ Apps</th>
                                        <th>ER Other</th>
                                    </tr>
                                    <?php
                                    $total = 0;
                                    foreach($frame as $f => $value1)
                                    {
                                        echo '<tr><td>' . $f . '</td>';
                                        foreach($frame[$f] as $g => $value2)
                                        {
                                            echo '<td>&pound; ' . sprintf("%.2f",$value2) . '</td>';
                                        }
                                        echo '</td>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
		        <br>
                
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title">Qualifications</h2>
            </div>
            <div class="box-body">
                <?php if(DB_NAME != "am_ela" && !in_array($_SESSION['user']->type, [5, 12, 13])) { ?>
                    <span class="btn btn-xs btn-primary" onclick="window.location.replace('do.php?_action=get_qualification&framework_id=<?php echo rawurlencode($framework->id); ?>');">
					<i class="fa fa-edit"></i>&nbsp;
					<i class="fa fa-graduation-cap"></i>&nbsp;
					Add/Remove Qualifications
				</span>
                    <?php if($isFramework){?>
                        <span class="btn btn-xs btn-info" onclick="validateFramework();">
					<i class="fa fa-check-circle"></i>&nbsp;
					Validate
				</span>
                    <?php } ?>
                <?php } ?>
                <?php if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]) && $_SESSION['user']->isAdmin() && false) { ?>
                    <span class="btn btn-xs btn-primary" onclick="window.location.replace('do.php?_action=edit_fwk_compliance_checklist&framework_id=<?php echo rawurlencode($framework->id); ?>');">
					<i class="fa fa-edit"></i>&nbsp;
					<i class="fa fa-list"></i>&nbsp; Edit Compliance Checklist
				</span>
                <?php } ?>
		<?php if(SystemConfig::getEntityValue($link, 'onefile.integration') && $_SESSION['user']->isAdmin()) { ?>
                    <span class="btn btn-xs btn-primary" onclick="link_onefile_standards();">
					<i class="fa fa-link"></i>&nbsp; Attach Onefile Aims
				</span>
                <?php } ?>
                <p></p>
                <div class="table-responsive">
                    <?php echo $view->render($link, $framework->title); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6"><div id="panelLearnersByEthnicity"></div></div>
    <div class="col-sm-6"><div id="panelLearnersByAgeBand"></div></div>
</div>
<div class="row">
    <div class="col-sm-6"><div id="panelLearnersByGender"></div></div>
    <div class="col-sm-6"><div id="panelLearnersByProgress"></div></div>
</div>
<div class="row">
    <div class="col-sm-6"><div id="panelLearnersByOutcomeType"></div></div>
    <div class="col-sm-6"><div id="panelLearnersByOutcomeCode"></div></div>
</div>
<div class="row">
    <div class="col-sm-12"><div id="panelLearnersByAssessors"></div></div>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>

<script language="JavaScript">

    $(function() {
        var chart = new Highcharts.chart('panelLearnersByEthnicity', <?php echo $panelLearnersByEthnicity; ?>);
        var chart = new Highcharts.chart('panelLearnersByAgeBand', <?php echo $panelLearnersByAgeBand; ?>);
        var chart = new Highcharts.chart('panelLearnersByGender', <?php echo $panelLearnersByGender; ?>);
        var chart = new Highcharts.chart('panelLearnersByAssessors', <?php echo $panelLearnersByAssessors; ?>);
        var chart = new Highcharts.chart('panelLearnersByOutcomeType', <?php echo $panelLearnersByOutcomeType; ?>);
        var chart = new Highcharts.chart('panelLearnersByOutcomeCode', <?php echo $panelLearnersByOutcomeCode; ?>);
        var chart = new Highcharts.chart('panelLearnersByProgress', <?php echo $panelLearnersByProgress; ?>);
    });

    function validateFramework()
    {
        var postData = 'framework_id=' + <?php echo rawurlencode($framework->id); ?>

        var client = ajaxRequest('do.php?_action=ajax_framework_validation', postData);
        if(client != null)
        {
            var xml = client.responseText;
            alert(xml);
        }
    }

    function saveFrmInductionCapacity()
    {
        var myForm = document.forms["frmInductionCapacity"];
        if(validateForm(myForm) == false)
        {
            return false;
        }
        myForm.submit();
    }

	function link_onefile_standards()
    {
        var onefile_organisation_id = '<?php echo $framework->onefile_organisation_id; ?>';
        if(onefile_organisation_id == '')
        {
            alert('Please edit the record and select OneFile organisation.');
            return;
        }
        
        window.location.replace('do.php?_action=link_sun_one_aims&framework_id=<?php echo rawurlencode($framework->id); ?>');
    }

</script>

</body>
</html>