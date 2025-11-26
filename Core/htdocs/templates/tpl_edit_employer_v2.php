<?php /* @var $employer Employer */ ?>
<?php /* @var $mainLocation Location */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $employer->id == ''?'Create Employer':'Edit Employer'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $employer->id == ''?'Create Employer':'Edit Employer'; ?></div>
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

<div class="container-fluid">
    <form class="form-horizontal" name="frmEmployer" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $employer->id; ?>" />
        <input type="hidden" name="main_location_id" value="<?php echo $mainLocation->id; ?>" />
        <input type="hidden" name="_action" value="save_employer_v2" />
        <?php if (isset($employer->organisation_type) && $employer->organisation_type): ?>
            <input type="hidden" name="organisation_type" value="<?php echo $employer->organisation_type; ?>" />
        <?php endif; ?>
        <div class="row">
            <div class="col-sm-7">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Basic Details</h2>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="active" class="col-sm-4 control-label fieldLabel_compulsory">Active:</label>
                            <div class="col-sm-8">
                                <?php
                                echo $employer->active == '1' ?
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                ?>
                            </div>
                        </div>
			<?php if(in_array(DB_NAME, ["am_duplex"])) { ?>
                        <div class="form-group">
                            <label for="org_status" class="col-sm-4 control-label fieldLabel_optional">Organisation Status:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('org_status', DAO::getResultset($link, "SELECT id, description, null FROM lookup_org_status"), $employer->org_status, false); ?>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="legal_name" class="col-sm-4 control-label fieldLabel_compulsory">Legal Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="legal_name" id="legal_name" value="<?php echo $employer->legal_name; ?>" maxlength="200" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trading_name" class="col-sm-4 control-label fieldLabel_compulsory">Trading Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="trading_name" id="trading_name" value="<?php echo $employer->trading_name; ?>" maxlength="200" />
                            </div>
                        </div>
			<?php if(!in_array(DB_NAME, ["am_duplex"])) { ?>
                        <div class="form-group">
                            <label for="short_name" class="col-sm-4 control-label fieldLabel_compulsory">Abbreviation:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="short_name" id="short_name" value="<?php echo $employer->short_name; ?>" maxlength="20" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edrs" class="col-sm-4 control-label fieldLabel_optional">EDRS:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="edrs" id="edrs" value="<?php echo $employer->edrs; ?>" maxlength="10" onkeypress="return numbersonly(this);" />
                            </div>
                        </div>
			<?php } ?>
                        <div class="form-group">
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Company Number:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="company_number" id="company_number" value="<?php echo $employer->company_number; ?>" maxlength="10" />
                            </div>
                        </div>
			<?php if(!in_array(DB_NAME, ["am_duplex"])) { ?>
                        <div class="form-group">
                            <label for="vat_number" class="col-sm-4 control-label fieldLabel_optional">VAT Number:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="vat_number" id="vat_number" value="<?php echo $employer->vat_number; ?>" maxlength="10" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="retailer_code" class="col-sm-4 control-label fieldLabel_optional">Retailer Code:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="retailer_code" id="retailer_code" value="<?php echo $employer->retailer_code; ?>" maxlength="10" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="employer_code" class="col-sm-4 control-label fieldLabel_optional">Employer Code:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="employer_code" id="employer_code" value="<?php echo $employer->employer_code; ?>" maxlength="10" />
                            </div>
                        </div>
			<?php } ?>
                    </div>
                </div>
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Additional Details</h2>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="company_rating" class="col-sm-4 control-label fieldLabel_optional">Rating:</label>
                            <div class="col-sm-8">
                                <table class="table table-bordered text-center">
                                    <tr>
                                        <td><i class="fa fa-trophy fa-2x" style="color: #ffd700;"></i></td>
                                        <td><i class="fa fa-trophy fa-2x" style="color: silver;"></i></td>
                                        <td><i class="fa fa-trophy fa-2x" style="color: #cd7f32;"></i></td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="company_rating" <?php echo $employer->company_rating == 'G' ? 'checked="checked"' : ''; ?> value="G"></td>
                                        <td><input type="radio" name="company_rating" <?php echo $employer->company_rating == 'S' ? 'checked="checked"' : ''; ?> value="S"></td>
                                        <td><input type="radio" name="company_rating" <?php echo $employer->company_rating == 'B' ? 'checked="checked"' : ''; ?> value="B"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sector" class="col-sm-4 control-label fieldLabel_optional">Sector:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('sector', $ddlSectors, $employer->sector, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="manufacturer" class="col-sm-4 control-label fieldLabel_optional">Group Employer:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('manufacturer', $ddlGroupEmployers, $employer->manufacturer, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="region" class="col-sm-4 control-label fieldLabel_optional">Sales Region:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('region', $ddlRegions, $employer->region, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="code" class="col-sm-4 control-label fieldLabel_optional">Size:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('code', $ddlCodes, $employer->code, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_employees" class="col-sm-4 control-label fieldLabel_optional">On-site Employees:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="site_employees" id="site_employees" value="<?php echo $employer->site_employees; ?>" maxlength="5" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="creator" class="col-sm-4 control-label fieldLabel_optional">Account Manager:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('creator', $ddlAccountManagers, $employer->creator, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lead_referral" class="col-sm-4 control-label fieldLabel_optional">Lead Referral:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="lead_referral" id="lead_referral" value="<?php echo $employer->lead_referral; ?>" maxlength="50" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="parent_org" class="col-sm-4 control-label fieldLabel_optional">Delivery Partner:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('parent_org', $ddlDeliveryPartners, $employer->parent_org, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="health_safety" class="col-sm-4 control-label fieldLabel_optional">Health & Safety:</label>
                            <div class="col-sm-8">
                                <?php
                                echo $employer->health_safety == '1' ?
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="health_safety" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="health_safety" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="due_diligence" class="col-sm-4 control-label fieldLabel_optional">Due Diligence:</label>
                            <div class="col-sm-8">
                                <?php
                                echo $employer->due_diligence == '1' ?
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="due_diligence" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="due_diligence" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ono" class="col-sm-4 control-label fieldLabel_optional">ONA:</label>
                            <div class="col-sm-8">
                                <?php
                                echo $employer->ono == '1' ?
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="ono" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="ono" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="levy_employer" class="col-sm-4 control-label fieldLabel_optional">Levy Employer:</label>
                            <div class="col-sm-8">
                                <?php
                                echo $employer->levy_employer == '1' ?
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="levy_employer" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="levy_employer" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="levy" class="col-sm-4 control-label fieldLabel_optional">Levy Amount:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="levy" id="levy" value="<?php echo $employer->levy; ?>" maxlength="10" onkeypress="return numbersonly(this);" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="url" class="col-sm-4 control-label fieldLabel_optional">URL:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="url" id="url" value="<?php echo $employer->url; ?>" maxlength="250" />
                            </div>
                        </div>
                        <?php if(in_array(DB_NAME, ["am_duplex"])) { ?>
                            <div class="form-group">
                                <label for="area" class="col-sm-4 control-label fieldLabel_optional">Area:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('area', [['WMCA', 'WMCA'], ['Out of Area', 'Out of Area']], $employer->area, true, false); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array(DB_NAME, ["am_sd_demo", "am_superdrug"])) { ?>
                            <div class="form-group">
                                <label for="salary_rate" class="col-sm-4 control-label fieldLabel_optional">Salary Rate:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('salary_rate', $salary_rate_options, $employer->salary_rate, false, false); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic"])) { ?>
                            <div class="form-group">
                                <label for="source" class="col-sm-4 control-label fieldLabel_optional">Source:</label>
                                <div class="col-sm-8">
                                    <?php
                                    $source_options = DAO::getResultset($link, "SELECT id, description FROM lookup_prospect_source ORDER BY description");
                                    echo HTML::selectChosen('source', $source_options, $employer->source, true);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="not_linked" class="col-sm-4 control-label fieldLabel_optional">No longer working with this employer:</label>
                                <div class="col-sm-8">
                                    <?php
                                    echo $employer->not_linked == '1' ?
                                        '<input value="1" class="yes_no_toggle" type="checkbox" name="not_linked" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                                        '<input value="1" class="yes_no_toggle" type="checkbox" name="not_linked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="not_linked_comments" class="col-sm-4 control-label fieldLabel_optional">Comments:</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="not_linked_comments" id="not_linked_comments" cols="30" rows="10"><?php echo nl2br((string) $employer->not_linked_comments); ?></textarea>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if(in_array(DB_NAME, ["am_lead_demo", "am_lead"])) {?>
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <h2 class="box-title">Products & Forecasts</h2>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <?php
                                $frameworks_ddl = OnboardingHelper::getAssessmentTypesDDL();
                                $products = DAO::getResultset($link, "SELECT * FROM employer_products WHERE employer_id = '{$employer->id}'", DAO::FETCH_ASSOC);
                                $row = 1;
                                foreach($products AS $product)
                                {
                                    $prefix = 'row_'.$row.'_';
                                    echo '<tr>';
                                    echo '<td>' . HTML::selectChosen($prefix.'framework_id', $frameworks_ddl, $product['framework_id'], true) . '</td>';
                                    echo '<td><input type="text" name="'.$prefix.'forecast" id="'.$prefix.'forecast" value="'.$product['forecast'].'" maxlength="5" onkeypress="return numbersonly(this);" /></td>';
                                    echo '</tr>';
                                    $row++;
                                }
                                for($i = $row; $i <= $row+2; $i++)
                                {
                                    $prefix = 'row_'.$i.'_';
                                    echo '<tr>';
                                    echo '<td>' . HTML::selectChosen($prefix.'framework_id', $frameworks_ddl, '', true) . '</td>';
                                    echo '<td><input type="text" name="'.$prefix.'forecast" id="'.$prefix.'forecast"  maxlength="5" onkeypress="return numbersonly(this);" /></td>';
                                    echo '</tr>';
                                }

                                ?>

                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-5">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Main Location Details</h2>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="full_name" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="full_name" id="full_name" value="<?php echo $mainLocation->full_name == '' ? 'Main Site' : $mainLocation->full_name; ?>" maxlength="50" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Address Line 1:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="address_line_1" id="address_line_1" value="<?php echo $mainLocation->address_line_1; ?>" maxlength="100" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="address_line_2" id="address_line_2" value="<?php echo $mainLocation->address_line_2; ?>" maxlength="100" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 3:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="address_line_3" id="address_line_3" value="<?php echo $mainLocation->address_line_3; ?>" maxlength="100" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 4:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="address_line_4" id="address_line_4" value="<?php echo $mainLocation->address_line_4; ?>" maxlength="100" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="postcode" id="postcode" value="<?php echo $mainLocation->postcode; ?>" maxlength="10" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="telephone" id="telephone" value="<?php echo $mainLocation->telephone; ?>" maxlength="15" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Fax:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="fax" id="fax" value="<?php echo $mainLocation->fax; ?>" maxlength="15" />
                            </div>
                        </div>
                        <div class="callout callout-default">
                            <h5 class="text-bold">Primary Contact Person Details</h5>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="contact_name" id="contact_name" value="<?php echo $mainLocation->contact_name; ?>" maxlength="50" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="contact_mobile" id="contact_mobile" value="<?php echo $mainLocation->contact_mobile; ?>" maxlength="15" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="contact_telephone" id="contact_telephone" value="<?php echo $mainLocation->contact_telephone; ?>" maxlength="15" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="contact_email" id="contact_email" value="<?php echo $mainLocation->contact_email; ?>" maxlength="50" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
		<?php if( $employer->id != '' && SystemConfig::getEntityValue($link, 'onefile.integration')) { ?>
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Onefile</h2>
                    </div>
                    <div class="box-body" id="divOnefile">
                        <div class="form-group">
                            <label for="full_name" class="col-sm-4 control-label fieldLabel_optional">Onefile Organisation:</label>
                            <div class="col-sm-8">
                                <?php 
                                echo HTML::selectChosen('onefile_organisation_id', Onefile::getOnefileOrganisationsDdl($link), $employer->onefile_organisation_id);
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="full_name" class="col-sm-4 control-label fieldLabel_optional">Onefile Placement:</label>
                            <div class="col-sm-8">
                                <?php 
                                $onefile_placements_list = [];
                                if($employer->organisation_type == Organisation::TYPE_EMPLOYER)
                                {
                                    $onefile_placements_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'placements_{$employer->onefile_organisation_id}'");
                                }
                    
                                if($onefile_placements_list_from_db != '')
                                {
                                    $onefile_placements_list_from_db = json_decode($onefile_placements_list_from_db);
                                    foreach($onefile_placements_list_from_db AS $onefile_placement)
                                    {
					$onefile_placements_list[] = [$onefile_placement->ID, '[' . $onefile_placement->ID . '] ' . $onefile_placement->Name];
                                    }
                                }
                                echo HTML::selectChosen('onefile_placement_id', $onefile_placements_list, $employer->onefile_placement_id, true);
                                ?>                                
                                <br>
                                <button type="button" class="btn btn-sm btn-info" id="btnOnefileRefresh" onclick="refresh_onefile_placements();"><i class="fa fa-refresh"></i> Refresh Placements List</button></td>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-sm btn-primary" id="btnOnefileCreatePlacement" onclick="create_onefile_placement();"><i class="fa fa-plus"></i> Create in Onefile</button></td>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </form>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms["frmEmployer"];
        if(!validateForm(myForm))
        {
            return;
        }

        if(!validatePostcode(myForm.postcode.value))
        {
            alert('Please enter valid postcode.');
            myForm.postcode.focus();
            return;
        }

        if(myForm.contact_email.value != '' && !validateEmail(myForm.contact_email.value))
        {
            alert('Please enter valid email address.');
            myForm.contact_email.focus();
            return;
        }

	<?php if(in_array(DB_NAME, ["am_lead_demo", "am_lead"])) { ?>
        var selected = [];
        var loopOK = true;
        $("select[name$='_framework_id']").each(function(index){

            if(this.value != '')
            {
                if($.inArray(this.value, selected) != -1)
                {
                    loopOK = false;
                    return false;
                }
                else
                {
                    selected.push(this.value);
                }
            }
        });
        if(!loopOK)
        {
            alert('Please select each framework once in "Products & Forecasts" panel.');	
            return;
        }
	<?php } ?>

	<?php if(in_array(DB_NAME, ["am_duplex"])) {?> myForm.submit(); <?php } else {?>

        var client = ajaxRequest('do.php?_action=save_employer_v2&subaction=validateEDRS&edrs=' + encodeURIComponent(myForm.edrs.value));

        if(client)
        {
            if(client.responseText == 0)
            {
                alert('Invalid EDRS');
                myForm.edrs.focus();
                return;
            }
            else
                return myForm.submit();
        }
        else
        {
            alert(client);
        }

	<?php } ?>

    }

    $(function(){
        $('input[type=radio]').iCheck({
            radioClass: 'iradio_square-green'
        });
    });

	<?php if($employer->organisation_type == Organisation::TYPE_EMPLOYER){ ?>
    function refresh_onefile_placements()
    {
        var onefile_organisation_id = $("#onefile_organisation_id").val();
        var url = 'do.php?_action=ajax_onefile&subaction=getOnefilePlacements'
        + "&organisation_id=" + encodeURIComponent(onefile_organisation_id);
        
        $("button#btnOnefileRefresh").attr('disabled', true);
        $("button#btnOnefileRefresh").html('<i class="fa fa-refresh fa-spin"></i> Please wait');

        function onefileRefreshCallback()
        {
            var onefile_placement_id_select = document.getElementById('onefile_placement_id');
            onefile_placement_id_select.disabled = true;
            ajaxPopulateSelect(onefile_placement_id_select, 'do.php?_action=ajax_load_account_manager&subaction=load_onefile_placements&organisation_id='+encodeURIComponent(onefile_organisation_id));
            onefile_placement_id_select.disabled = false;

            $("button#btnOnefileRefresh").attr('disabled', false);
            $("button#btnOnefileRefresh").html('<i class="fa fa-refresh"></i> Refresh Placements List');
        }

        var client = ajaxRequest(url, null, null, onefileRefreshCallback);
    }

    function create_onefile_placement()
    {
        if(!confirm("This action will create a new placement in Onefile system. Are you sure you want to continue?"))
        {
            return false;
        }
        var onefile_organisation_id = $("#onefile_organisation_id").val();
        var url = 'do.php?_action=ajax_onefile&subaction=createPlacementInOnefile'
        + "&employer_id=" + encodeURIComponent('<?php echo $employer->id; ?>')
        + "&organisation_id=" + encodeURIComponent(onefile_organisation_id);

        $("button#btnOnefileCreatePlacement").attr('disabled', true);
        $("button#btnOnefileCreatePlacement").html('<i class="fa fa-refresh fa-spin"></i> Please wait');

        function onefileCreatePlacementCallback(client)
        {
            if (client) 
            {
                if(client.responseText == 200)
                {
                    refresh_onefile_placements();
                }
                else if(client.responseText == 400)
                {
                    alert("Error: 400 Bad Request");
                }
                else if(client.responseText == 401)
                {
                    alert("Error: 401 Unauthorized");
                }
                else if(client.responseText == 403)
                {
                    alert("Error: 403 Forbidden");
                }
                else if(client.responseText == 500)
                {
                    alert("Error: 500 Internal Server Error");
                }
                else
                {
                    alert(client.responseText);
                }
            }

            $("button#btnOnefileCreatePlacement").attr('disabled', false);
            $("button#btnOnefileCreatePlacement").html('<i class="fa fa-plus"></i> Create in Onefile');
        }

        var client = ajaxRequest(url, null, null, onefileCreatePlacementCallback);

    }
    <?php } ?>


</script>

</body>
</html>