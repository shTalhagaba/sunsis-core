<?php /* @var $vo Qualification */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Add/Edit Qualification</title>
    <link rel="stylesheet" href="/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- CSS for Controls -->
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/treeview/assets/skins/sam/treeview.css">
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/container/assets/container.css">

    <!-- CSS for Menu -->
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/menu/assets/skins/sam/menu.css">

    <!-- CSS for TabView -->
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
    <style type="text/css">

        #unitCanvas
        {
            width: 0px;
            height: 0px;
            border: 1px solid black;
            margin-left: 10px;
            padding-top: 10px;
            overflow: scroll;

            background-image:url('/images/paper-background-orange.jpg');
        }

        #fieldsBox
        {
            width: 650px;
            min-height: 200px;
            border: 1px solid black;
            margin: 5px 0px 10px 10px;
        }

        #elementFields
        {
            width: 650px;
            min-height: 200px;
            border: 1px solid black;
            margin: 10px 10px 10px 10px;
            overflow: scroll;
        }

        #unitFields, #unitsFields
        {
            display:none;
            padding: 10px;
        }

        #unitFields > h3, #unitsFields > h3
        {
            margin-top: 5px;
        }

        div.Units
        {
            margin: 3px 10px 3px 20px;
            border: 1px orange dotted;
            padding: 1px 1px 10px 1px;
            background-color: white;
            min-height: 100px;
        }

        div.elementsContainer
        {
            width: 650px;
            min-height: 200px;
            border: 1px solid black;
            margin: 10px 10px 10px 10px;
        }


        div.Elements
        {
            margin: 3px 10px 3px 20px;
            border: 1px orange dotted;
            padding: 1px 1px 10px 1px;
            background-color: white;
            min-height: 100px;
        }

        div.evidence
        {
            margin: 3px 10px 3px 20px;
            padding: 1px 1px 10px 1px;
            background-color: white;
        }

        div.elementsBox
        {
            margin: 3px 10px 3px 20px;
            border: 2px orange dotted;
            padding: 1px 1px 10px 1px;
            background-color: white;
            margin: 10px 10px 10px 10px;
            min-height: 100px;
        }

        div.UnitsTitle
        {
            font-size: 12pt;
            font-weight: bold;
            color: #395596;
            cursor: default;
            padding: 2px;
            margin: 0px;
        }

        div.ElementsTitle
        {
            font-size: 12pt;
            font-weight: bold;
            color: #395596;
            cursor: default;
            padding: 2px;
            margin: 0px;
        }

        div.Root
        {
            margin: 3px 10px 3px 20px;
            border: 3px gray solid;
            -moz-border-radius: 5pt;
            padding: 3px;
            background-color: #395596;
            color: white;
            min-height: 20px;
            width: 50em;
            font-weight: bold;
        }

        div.UnitGroup
        {
            margin: 3px 10px 3px 20px;
            border: 3px gray solid;
            border-radius: 4px;
            padding: 3px;
            background-color: #EE9572;
            color: black;
            min-height: 20px;
            width: 50em;
        }

        div.Unit
        {
            margin: 3px 10px 3px 20px;
            border: 2px gray solid;
            border-radius: 6px;
            padding: 3px;
            color: black;
            min-height: 20px;
            color: black:
            backgourn-color: transparent;
            width: 50em;
        }

        div.ElementGroup
        {
            margin: 3px 10px 3px 20px;
            border: 1px gray solid;
            border-radius: 8px;
            -moz-border-radius: 5pt;
            padding: 3px;
            background-color: #F8D0C1;
            color: black;
            min-height: 20px;
            width: 50em;
            font-weight: bold;
        }

        div.Element
        {
            margin: 3px 10px 3px 20px;
            border: 1px gray solid;
            border-radius: 10px;
            -moz-border-radius: 5pt;
            padding: 3px;
            background-color: #FCEEE8;
            color: black;
            min-height: 20px;
            width: 50em;
        }

        div.Evidence
        {
            margin: 3px 10px 3px 20px;
            border: 1px silver dotted;
            border-radius: 12px;
            -moz-border-radius: 5pt;
            padding: 3px;
            background-color: #FDF1E2;
            color: black;
            min-height: 20px;
            width: 50em;
        }

        div.UnitTitle
        {
            margin: 2px;
            padding: 2px;
            cursor: default;
            font-weight: bold;
            background-color: #FDE3C1;
            -moz-border-radius: 5pt;
        }

        div.UnitDetail
        {
            margin-left:5px;
            margin-bottom:5px;
            display: none;
        }

        div.UnitDetail p
        {
            margin: 0px 5px 10px 5px;
            font-style: italic;
            color: navy;
            text-align: justify;
        }

        div.UnitDetail p.owner
        {
            text-align:right;
            font-style:normal;
            font-weight:bold;
        }

    </style>


</head>
<body class="yui-skin-sam">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Add/Edit Qualification</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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
        <div class="col-sm-12">
            <div class="box box-solid box-success">
                <div class="box-header with-border"><h2 class="box-title">Qualification Details</small></h2>
                    <div class="box-tools pull-right"></div>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class=""><a href="#tabDetails" data-toggle="tab">Basic Details</a></li>
                            <li class="active"><a href="#tabStructure" data-toggle="tab"> Structure</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class=" tab-pane" id="tabDetails">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form name="frmQualification" action="" method="POST">
                                            <input type="hidden" name="_action" value="save_qualification" />
                                            <input type="hidden" name="auto_id" value="<?php echo $vo->auto_id; ?>" />
                                            <input type="hidden" name="qan_before_editing" value="<?php echo htmlspecialchars($vo->id); ?>" />

                                            <p class="sectionDescription">To automatically complete or refresh this form with data from the Ofqual's
                                                <a href="http://register.ofqual.gov.uk/" target="_blank">Register of Regulated Qualifications</a>&nbsp;<img src="/images/external.png" />, fill in the Ofqual reference number (QAN) field and click the "Auto-Complete" button.</p>
                                            <table border="0" cellspacing="4" cellpadding="4">
                                                <col width="200"/><col />
                                                <tr>
                                                    <td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('Qualification Accreditation Number. A unique identifier assigned to a qualification by the regulatory authority once it has been accredited.');" >Ofqual Reference (QAN):</td>
                                                    <td><input class="compulsory" style="font-family:monospace" id="qid" type="text" name="id" value="<?php echo htmlspecialchars($vo->id); ?>" onchange="id_onchange(this);"/>
                                                        <!--					<span class="button" onclick="loadFieldsFromNDAQ(0); return false;">Auto-Complete</span>-->
                                                        <!--					<span class="button" onclick="loadFieldsFromNDAQ(1); return false;">Auto-Fill</span></td>-->
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional">EBS UI Code:</td>
                                                    <td><input class="optional" type="text" name="ebs_ui_code" value="<?php echo $vo->ebs_ui_code; ?>" size="10" maxlength="10" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_compulsory">Qualification Type:</td>
                                                    <td><?php echo HTML::radioButtonGrid('qual_status', $qual_status, $vo->qual_status, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" >Standards Ref:</td>
                                                    <td><input class="optional" type="text" name="lsc_learning_aim" value="" size="50"  maxlength="50" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" style="cursor:help" onclick="alert('An organisation or consortium recognised by the regulatory authorities for the purpose of awarding accredited qualifications.');" >Awarding Body:</td>
                                                    <td><input class="optional" type="text" name="awarding_body" value="" size="60"/></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('A group of qualifications with distinctive structural characteristics.');" >Qualification type:</td>
                                                    <td><?php echo HTML::select('qualification_type', $type_dropdown, null, true, true); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_compulsory" valign="top">Level:</td>
                                                    <td class="fieldValue"><?php echo HTML::checkboxGrid('level', $level_checkboxes, null, 3, true); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" style="cursor:help" onclick="alert('An organisation or consortium recognised by the regulatory authorities for the purpose of awarding accredited qualifications.');" >Guided Learning Hours:</td>
                                                    <td><input class="optional" type="text" name="guided_learning_hours" value="" size="10"/></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" style="cursor:help" >Credit Value:</td>
                                                    <td><input class="optional" type="text" name="total_credit_value" value="" size="10"/></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional">Units Guided Learning Hours:</td>
                                                    <td><input class="optional" type="text" id="units_guided_learning_hours" value="" size="10"/></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional">Units Credit Value:</td>
                                                    <td><input class="optional" type="text" id="units_credit_value" value="" size="10"/></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional">Active?:</td>
                                                    <td class="optional"><input type="checkbox" <?php echo ($vo->active)?"checked":"";?> id = "is_active"></input></td>
                                                </tr>



                                            </table>

                                            <h3>Qualification Lifecycle Dates <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
                                            <p class="sectionDescription">Period during which this qualification is available to centres and students</p>

                                            <table border="0" cellspacing="4" cellpadding="4">
                                                <col width="200"/><col />
                                                <tr>
                                                    <td class="fieldLabel_optional" style="cursor:help" >Regulation start date:</td>
                                                    <td><?php echo HTML::datebox('regulation_start_date', null) ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" style="cursor:help"  >Operational start date:</td>
                                                    <td><?php echo HTML::datebox('operational_start_date', null) ?></td>

                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" style="cursor:help"  >Operational end date:</td>
                                                    <td><?php echo HTML::datebox('operational_end_date', null) ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate must complete their programme of study.');" >Certification end date:</td>
                                                    <td><?php echo HTML::datebox('certification_end_date', null) ?></td>
                                                </tr>
                                            </table>

                                            <h3>Descriptive Text <img id="globe3" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
                                            <table border="0" cellspacing="4" cellpadding="4">
                                                <col width="200"/><col />
                                                <tr>
                                                    <td class="fieldLabel_optional">Title:</td>
                                                    <td><input class="optional" type="text" name="title" value="" size="60" maxlength="300"/></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_compulsory">Internal Title:</td>
                                                    <td><input class="compulsory" type="text" id='internaltitle' name="internaltitle" value="" size="60" maxlength="100"/></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" valign="top">Structure Requirements:</td>
                                                    <td><textarea class="optional"  style="font-family:sans-serif; font-size:10pt" name="description" rows="7" cols="60"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" valign="top">Assessment method:</td>
                                                    <td><textarea class="optional"  style="font-family:sans-serif; font-size:10pt" name="assessment_method" rows="7" cols="60"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" valign="top">SSA 1:</td>
                                                    <td><input class="optional" type="text" name="mainarea" value="" size="60"/></td>
                                                </tr>
                                                <tr>
                                                    <td class="fieldLabel_optional" valign="top">SSA 2:</td>
                                                    <td><input class="optional" type="text" name="subarea" value="" size="60"/></td>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="active tab-pane" id="tabStructure">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <span class="btn btn-info btn-xs" onclick="tree.expandAll();"> Expand All </span>
                                        <span class="btn btn-info btn-xs" onclick="tree.collapseAll();"> Collapse All </span>

                                        <div id="treeDiv1" style="margin-top: 20px;">No qualification imported</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box box-info box-solid" id="unitDialog">
    <div class="box-header with-border"><h2 class="box-title">Please enter unit details</small></h2>
    </div>
    <div class="box-body">
        <form autocomplete="off" class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Reference</td>
                        <td><input class="form-control compulsory" type="text" name="unitReference" /></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel_compulsory">Title</td>
                        <td><textarea name="unitTitle" class="form-control compulsory" rows="3"></textarea></td>
                    </tr>
                    <tr>
                        <td>Credits</td>
                        <td><input class="form-control" type="text" name="unitCredits" value="0" /></td>
                    </tr>
                    <tr>
                        <td>Guided Learning Hours</td>
                        <td><input class="form-control" type="text" name="unitGLH" value="0" /></td>
                    </tr>
                    <tr>
                        <td>Owner Reference</td>
                        <td><input class="form-control" type="text" name="unitOwnerReference" /></td>
                    </tr>
                    <tr>
                        <td>Mandatory: </td>
                        <td><input type="checkbox" name="mandatory" value="1" /></td>
                    </tr>
                    <tr>
                        <td>Proportion</td>
                        <td><input class="form-control" type="text" name="unitProportion" value="0" /></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="box box-info box-solid" id="unitEditDialog">
    <div class="box-header with-border"><h2 class="box-title">Please edit unit details</small></h2></div>
    <div class="box-body">
        <form class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Reference</td>
                        <td><input class="form-control compulsory" type="text" name="unitReference" /></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel_compulsory">Title</td>
                        <td><textarea name="unitTitle" class="form-control compulsory" rows="3"></textarea></td>
                    </tr>
                    <tr>
                        <td>Credits</td>
                        <td><input class="form-control" type="text" name="unitCredits" value="0" /></td>
                    </tr>
                    <tr>
                        <td>Guided Learning Hours</td>
                        <td><input class="form-control" type="text" name="unitGLH" value="0" /></td>
                    </tr>
                    <tr>
                        <td>Owner Reference</td>
                        <td><input class="form-control" type="text" name="unitOwnerReference" /></td>
                    </tr>
                    <tr>
                        <td>Mandatory: </td>
                        <td><input type="checkbox" name="mandatory" value="1" /></td>
                    </tr>
                    <tr>
                        <td>Proportion</td>
                        <td><input class="form-control" type="text" name="unitProportion" value="0" /></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="box box-info box-solid" id="elementDialog">
    <div class="box-header with-border"><h2 class="box-title">Please enter element details</small></h2></div>
    <div class="box-body">
        <form class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Title</td>
                        <td><input class="form-control compulsory" type="text" name="elementTitle" /></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea name="elementDescription" class="form-control" rows="3"></textarea></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="box box-info box-solid" id="elementEditDialog">
    <div class="box-header with-border"><h2 class="box-title">Please edit element details</small></h2></div>
    <div class="box-body">
        <form class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Title</td>
                        <td><input class="form-control compulsory" type="text" name="elementTitle" /></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea name="elementDescription" class="form-control" rows="3"></textarea></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="box box-info box-solid" id="elementGroupDialog">
    <div class="box-header with-border"><h2 class="box-title">Please enter element group details</small></h2></div>
    <div class="box-body">
        <form class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Title</td>
                        <td><textarea name="elementGroupTitle" class="form-control" rows="3"></textarea></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="box box-info box-solid" id="elementEditGroupDialog">
    <div class="box-header with-border"><h2 class="box-title">Please edit element group details</small></h2></div>
    <div class="box-body">
        <form class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Title</td>
                        <td><textarea name="elementGroupTitle" class="form-control" rows="3"></textarea></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="box box-info box-solid" id="unitGroupDialog">
    <div class="box-header with-border"><h2 class="box-title">Please enter unit group details</small></h2></div>
    <div class="box-body">
        <form class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Title</td>
                        <td><textarea name="unitGroupTitle" class="form-control" rows="3"></textarea></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="box box-info box-solid" id="unitEditGroupDialog">
    <div class="box-header with-border"><h2 class="box-title">Please edit unit group details</small></h2></div>
    <div class="box-body">
        <form class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Title</td>
                        <td><textarea name="unitGroupTitle" class="form-control" rows="3"></textarea></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="box box-info box-solid" id="evidenceDialog">
    <div class="box-header with-border"><h2 class="box-title">Please enter evidence details</small></h2></div>
    <div class="box-body">
        <form class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Title</td>
                        <td><textarea name="evidenceTitle" class="form-control" rows="3"></textarea></td>
                    </tr>
                    <tr>
                        <td>Reference</td>
                        <td><input class="form-control" type="text" name="evidenceReference" /></td>
                    </tr>
                    <tr>
                        <td>Portfolio Page No.</td>
                        <td><input class="form-control" type="text" name="evidencePortfolio" /></td>
                    </tr>
                    <tr>
                        <td>Assessment Method</td>
                        <td><?php echo HTML::selectChosen('evidenceAssessmentMethod', $assessment_method_dropdown, null, true, true); ?></td>
                    </tr>
                    <tr>
                        <td>Evidence Type</td>
                        <td><?php echo HTML::selectChosen('evidenceEvidenceType', $evidence_type_dropdown, null, true, true); ?></td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td><?php echo HTML::selectChosen('evidenceCategory', $category_dropdown, null, true, true); ?></td>
                    </tr>
                    <tr>
                        <td>Delivery Hours</td>
                        <td><input class="form-control" type="text" name="evidenceDeliveryHours" value="0" /></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="box box-info box-solid" id="evidenceEditDialog">
    <div class="box-header with-border"><h2 class="box-title">Please edit evidence details</small></h2></div>
    <div class="box-body">
        <form class="form-horizontal small">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="fieldLabel_compulsory" style="width: 20%;">Title</td>
                        <td><textarea name="evidenceTitle" class="form-control" rows="5"></textarea></td>
                    </tr>
                    <tr>
                        <td>Reference</td>
                        <td><input class="form-control" type="text" name="evidenceReference" /></td>
                    </tr>
                    <tr>
                        <td>Portfolio Page No.</td>
                        <td><input class="form-control" type="text" name="evidencePortfolio" /></td>
                    </tr>
                    <tr>
                        <td>Assessment Method</td>
                        <td><?php echo HTML::selectChosen('evidenceAssessmentMethod', $assessment_method_dropdown, null, true, true); ?></td>
                    </tr>
                    <tr>
                        <td>Evidence Type</td>
                        <td><?php echo HTML::selectChosen('evidenceEvidenceType', $evidence_type_dropdown, null, true, true); ?></td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td><?php echo HTML::selectChosen('evidenceCategory', $category_dropdown, null, true, true); ?></td>
                    </tr>
                    <tr>
                        <td>Delivery Hours</td>
                        <td><input class="form-control" type="text" name="evidenceDeliveryHours" value="0" /></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>

<!-- Dependency source files -->

<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

<!-- Menu source file -->

<script type="text/javascript" src="/yui/2.4.1/build/menu/menu.js"></script>


<!-- Page-specific script -->
<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

<script>
    var __qualificationId = '<?php echo addslashes($qualification_id); ?>';
    var __internalTitle = '<?php echo addslashes($internaltitle); ?>';
    var __dbName = '<?php echo addslashes(DB_NAME); ?>';
    var __bcPrevious = '<?php echo $_SESSION['bc']->getPrevious(); ?>';
</script>
<script type="text/javascript" src="/scripts/edit_qualification.js?n=<?php echo time();?>"></script>


<script language="JavaScript">


    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('.datepicker').attr('class', 'datepicker form-control');

    });

</script>

</body>
</html>