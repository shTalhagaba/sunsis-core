<?php /* @var $vo Qualification */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == ''?'Add Qualification':'Edit Qualification'; ?></title>
    <link rel="stylesheet" href="/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/treeview/assets/skins/sam/treeview.css">
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/calendar/assets/skins/sam/calendar.css">
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/container/assets/container.css">

    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/menu/assets/skins/sam/menu.css">

    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

    <style type="text/css">
        .icon-ppt { padding-left: 20px; background: transparent url(/images/icons.png) 0 0px no-repeat; }
        .icon-dmg { padding-left: 20px; background: transparent url(/images/icons.png) 0 -36px no-repeat; }
        .icon-prv { padding-left: 20px; background: transparent url(/images/icons.png) 0 -72px no-repeat; }
        .icon-gen { padding-left: 20px; background: transparent url(/images/icons.png) 0 -108px no-repeat; }
        .icon-doc { padding-left: 20px; background: transparent url(/images/icons.png) 0 -144px no-repeat; }
        .icon-jar { padding-left: 20px; background: transparent url(/images/icons.png) 0 -180px no-repeat; }
        .icon-zip { padding-left: 20px; background: transparent url(/images/icons.png) 0 -216px no-repeat; }
    </style>



</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Add Qualification':'Edit Qualification'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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
    <form autocomplete="off" class="form-horizontal" name="frmQualification" id="frmQualification"
          action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="_action" value="save_qualification"/>
        <input type="hidden" name="auto_id" value="<?php echo $vo->auto_id; ?>" />

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <span class="box-title"><span class="text-bold">Qualification Structure</span></span>
                    </div>
                    <div class="box-body">
                        <form name="frmQualification" action="do.php?_action=save_qualification_details" method="post">
                            <input type="hidden" name="_action" value="save_qualification_details"/>
                            <input type="hidden" name="auto_id" value="<?php echo $vo->auto_id ?>" />

                            <div id="test"></div>
                            <p class="sectionDescription">This is edit mode. You can click on any element to expand or collapse its sub-elements.
                                Please right click on any element to view, edit, delete, cut, copy, paste etc.

                            <div id="treeDiv1" style="margin-top: 20px;">No qualification imported</div>

                            <div id="unitDialog">
                                <div class="hd">Please enter unit details</div>
                                <div style="height: 40px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Reference</td>
                                            <td><input class="optional" type="text" name="unitReference" size="20"/></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional" type="text" name="unitTitle" size="60" onKeyPress='return alphaonly(this, event)'/></td>
                                        </tr>
                                        <tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Credits</td>
                                            <td><input class="optional" type="text" name="unitCredits" size="5" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Guided Learning Hours</td>
                                            <td><input class="optional" type="text" name="unitGLH" size="5" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Owner Reference</td>
                                            <td><input class="optional" type="text" name="unitOwnerReference" size="20" /></td>
                                        </tr>
                                        <tr>
                                            <td width="140" class="fieldLabel_optional">Mandatory: </td>
                                            <td><input class="optional" type="checkbox" name="mandatory" value="1" /></td>
                                        </tr>
                                        <tr>
                                            <td width="140" class="fieldLabel_optional">Unit to track: </td>
                                            <td><input class="optional" type="checkbox" name="track" value="1" /></td>
                                        </tr>
                                        <?php if(SystemConfig::getEntityValue($link, 'operations_tracker')) {?>
                                            <tr>
                                                <td class="fieldLabel_optional">Operations Title</td>
                                                <td><input class="optional" type="text" name="op_title" size="60" onKeyPress='return alphaonly(this, event)'/></td>
                                            </tr>
                                        <?php } else {?>
                                            <tr><td colspan="2"><input class="optional" type="hidden" name="op_title" size="60" /></td></tr>
                                        <?php } ?>
                                        <tr>
                                            <td class="fieldLabel_optional">Proportion</td>
                                            <td><input class="optional" type="text" name="unitProportion" size="3" value="0" /></td>
                                        </tr>
                                        <!--
                                   <tr>
                                       <td class="fieldLabel_optional" valign="top">Description</td>
                                       <td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="unitDescription" rows="7" cols="70" ></textarea></td>
                                   </tr>
                               -->
                                    </table>
                                </form>
                            </div>

                            <div id="unitEditDialog">
                                <div class="hd">Please edit unit details</div>
                                <div style="height: 40px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Reference</td>
                                            <td><input class="optional" type="text" name="unitReference" size="20" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional" type="text" name="unitTitle" size="60" onKeyPress='return alphaonly(this, event)'/></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Credits</td>
                                            <td><input class="optional" type="text" name="unitCredits" size="10" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Guided Learning Hours</td>
                                            <td><input class="optional" type="text" name="unitGLH" size="10" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Owner Reference</td>
                                            <td><input class="optional" type="text" name="unitOwnerReference" size="20" /></td>
                                        </tr>
                                        <tr>
                                            <td width="140" class="fieldLabel_optional">Mandatory: </td>
                                            <td><input class="optional" type="checkbox" name="mandatory" value="1" /></td>
                                        </tr>
                                        <tr>
                                            <td width="140" class="fieldLabel_optional">Unit to track: </td>
                                            <td><input class="optional" type="checkbox" name="track" value="1" /></td>
                                        </tr>
                                        <?php if(SystemConfig::getEntityValue($link, 'operations_tracker')) {?>
                                            <tr>
                                                <td class="fieldLabel_optional">Operations Title</td>
                                                <td><input class="optional" type="text" name="op_title" size="60" onKeyPress='return alphaonly(this, event)'/></td>
                                            </tr>
                                        <?php } else {?>
                                            <tr><td colspan="2"><input class="optional" type="hidden" name="op_title" size="60" /></td></tr>
                                        <?php } ?>
                                        <tr>
                                            <td class="fieldLabel_optional">Proportion</td>
                                            <td><input class="optional" type="text" name="unitProportion" size="3"  /></td>
                                        </tr>
                                        <!-- <tr>
                                       <td class="fieldLabel_optional" valign="top">Description</td>
                                       <td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="unitDescription" rows="7" cols="70" ></textarea></td>
                                   </tr>

                               -->
                                    </table>
                                </form>
                            </div>

                            <div id="elementDialog">
                                <div class="hd">Please enter element details</div>
                                <div style="height: 40px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional" type="text" name="elementTitle" size="60"  /></td>
                                        </tr>
                                        <!-- <tr>
                                       <td class="fieldLabel_optional">Reference</td>
                                       <td><input class="optional" type="text" name="elementReference" size="20" /></td>
                                   </tr>
                                    <tr>
                                       <td class="fieldLabel_optional">Proportion</td>
                                       <td><input class="optional" type="text" name="elementProportion" size="60"  /></td>
                                   </tr>
                               -->
                                        <tr>
                                            <td class="fieldLabel_optional" valign="top">Description</td>
                                            <td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="elementDescription" rows="7" cols="70" ></textarea></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>

                            <div id="elementEditDialog">
                                <div class="hd">Please edit element details</div>
                                <div style="height: 40px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional" type="text" name="elementTitle" size="60"  /></td>
                                        </tr>
                                        <!-- <tr>
                                       <td class="fieldLabel_optional">Reference</td>
                                       <td><input class="optional" type="text" name="elementReference" size="20" /></td>
                                   </tr>
                                    <tr>
                                       <td class="fieldLabel_optional">Proportion</td>
                                       <td><input class="optional" type="text" name="elementProportion" size="60"  /></td>
                                   </tr>
                               -->
                                        <tr>
                                            <td class="fieldLabel_optional" valign="top">Description</td>
                                            <td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="elementDescription" rows="7" cols="70" ></textarea></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>

                            <div id="elementGroupDialog">
                                <div class="hd">Please enter element group details</div>
                                <div style="height: 40px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional"  type="text" name="elementGroupTitle" size="60"  /></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>

                            <div id="elementEditGroupDialog">
                                <div class="hd">Please edit element group details</div>
                                <div style="height: 40px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional"  type="text" name="elementGroupTitle" size="60"  /></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>

                            <div id="unitGroupDialog">
                                <div class="hd">Please enter unit group details</div>
                                <div style="height: 40px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional"  type="text" name="unitGroupTitle" size="60"  /></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>

                            <div id="unitEditGroupDialog">
                                <div class="hd">Please edit unit group details</div>
                                <div style="height: 40px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional"  type="text" name="unitGroupTitle" size="60"  /></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>

                            <div id="evidenceDialog">
                                <div class="hd">Please enter evidence</div>
                                <div style="height: 40px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional"  type="text" name="evidenceTitle" size="60"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Reference</td>
                                            <td><input class="optional"  type="text" name="evidenceReference" size="5"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Portfolio Page no.</td>
                                            <td><input class="optional"  type="text" name="evidencePortfolio" size="5"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Assessment Method</td>
                                            <td><?php echo HTML::select('evidenceAssessmentMethod', $assessment_method_dropdown, null, true, true); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Evidence Type</td>
                                            <td><?php echo HTML::select('evidenceEvidenceType', $evidence_type_dropdown, null, true, true); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Category</td>
                                            <td><?php echo HTML::select('evidenceCategory', $category_dropdown, null, true, true); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Delivery Hours</td>
                                            <td><input class="optional"  type="text" name="evidenceDeliveryHours" size="2"  /></td>
                                        </tr>

                                        <!--
	<tr><td colspan=2> &nbsp; </tr></tr>

	<tr><td colspan=2> Learner level details just for an indication, will be filled at learner level </tr></tr>
	<tr>
		<td class="fieldLabel_compulsory">Status:</td>
		<td><?php //echo HTML::radioButtonGrid('evidenceStatus', $status, null, 2, false); ?></td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Marks</td>
		<td><input class="optional"  type="text" disabled name="evidenceMarks" size="2"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Assessor Comments</td>
		<td><textarea disabled class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceComments" rows="5" cols="70" ></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Verified</td>
		<td><input type='checkbox' disabled class="optional" id="evidenceVerified" name="evidenceVerified"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Verifier Comments</td>
		<td><textarea disabled class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceVComments" rows="5" cols="70" ></textarea></td>
	</tr>
-->
                                    </table>
                                </form>
                            </div>

                            <div id="evidenceEditDialog">
                                <div class="hd">Please edit evidence</div>
                                <div style="height: 10px; margin-left:10px; " ></div>
                                <form>
                                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
                                        <tr>
                                            <td class="fieldLabel_optional">Title</td>
                                            <td><input class="optional"  type="text" name="evidenceTitle" size="60"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Reference</td>
                                            <td><input class="optional"  type="text" name="evidenceReference" size="5"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Portfolio Page no.</td>
                                            <td><input class="optional"  type="text" name="evidencePortfolio" size="5"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Assessment Method</td>
                                            <td><?php echo HTML::select('evidenceAssessmentMethod', $assessment_method_dropdown, null, true, true); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Evidence Type</td>
                                            <td><?php echo HTML::select('evidenceEvidenceType', $evidence_type_dropdown, null, true, true); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Category</td>
                                            <td><?php echo HTML::select('evidenceCategory', $category_dropdown, null, true, true); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldLabel_optional">Delivery Hours</td>
                                            <td><input class="optional"  type="text" name="evidenceDeliveryHours" size="2"  /></td>
                                        </tr>
                                        <!--
	<tr><td colspan=2> &nbsp; </tr></tr>

	<tr><td colspan=2> Learner level details just for an indication, will be filled at learner level </tr></tr>
	<tr>
		<td class="fieldLabel_compulsory">Status:</td>
		<td><?php //echo HTML::radioButtonGrid('evidenceStatus', $status, null, 2, false); ?></td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Marks</td>
		<td><input class="optional"  type="text" disabled name="evidenceMarks" size="2"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Assessor Comments</td>
		<td><textarea disabled class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceComments" rows="5" cols="70" ></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Verified</td>
		<td><input type='checkbox' disabled class="optional" id="evidenceVerified" name="evidenceVerified"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Verifer Comments</td>
		<td><textarea disabled class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceVComments" rows="5" cols="70" ></textarea></td>
	</tr>
-->
                                    </table>
                                </form>
                            </div>



                        </form>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>

<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/menu/menu.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

<script type="text/javascript" src="/scripts/edit_qualification.js?n=<?php echo time();?>"></script>

<script language="JavaScript">

$(function() {

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy'
    });

    $('.datepicker').attr('class', 'datepicker form-control');



});

</script>

</body>
</html>