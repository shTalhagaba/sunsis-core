<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $pool->id == '' ? 'Create Pool Organisation' : 'Edit Pool Organisation'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
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

        .disabled {
            pointer-events: none;
            opacity: 0.4;
        }
    </style>
</head>

<body>

    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;"><?php echo $pool->id == '' ? 'Create Pool Organisation' : 'Edit Pool Organisation'; ?></div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-xs btn-default" onclick="savePoolOrganisation();"><i class="fa fa-save"></i> Save</span>
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
        <form class="form-horizontal" name="frmPoolOrganisation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="_action" value="save_pool_organisation" />
            <input type="hidden" name="id" value="<?php echo $pool->id ?>" />
            <div class="col-md-6">


                <span class="lead text-bold text-blue">Company Details</span>

                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_compulsory">Legal Name:</label>
                    <div class="col-sm-8">
                        <input class="form-control compulsory" type="text" name="legal_name" value="<?php echo htmlspecialchars((string)$pool->legal_name); ?>" maxlength="500" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label">Trading Name:</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="trading_name" value="<?php echo htmlspecialchars((string)$pool->trading_name); ?>" maxlength="500" onfocus="trading_name_onfocus(this);" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_optional">Sector:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::selectChosen('sector', DAO::getResultset($link, "SELECT id, description FROM lookup_sector_types ORDER BY description"), $pool->sector, true); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_optional">Industry:</label>
                    <div class="col-sm-8">
                        <input class="form-control optional" type="text" name="industry" value="<?php echo htmlspecialchars((string)$pool->industry); ?>" maxlength="200" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_optional">Year Founded:</label>
                    <div class="col-sm-8">
                        <?php 
                        $years = []; 
                        for($i = 1800; $i <= date('Y'); $i++)
                        {
                            $years[] = [$i, $i];
                        }
                        echo HTML::selectChosen('year_founded', $years, $pool->year_founded, true); 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_optional">Number of Employees:</label>
                    <div class="col-sm-8">
                        <input class="form-control optional" type="text" name="site_employees" value="<?php echo htmlspecialchars((string)$pool->site_employees); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_optional">Credit Rating:</label>
                    <div class="col-sm-8">
                        <input class="form-control optional" type="text" name="credit_rating" value="<?php echo htmlspecialchars((string)$pool->credit_rating); ?>" maxlength="50" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_optional">Credit Limit:</label>
                    <div class="col-sm-8">
                        <input class="form-control optional" type="text" name="credit_limit" value="<?php echo htmlspecialchars((string)$pool->credit_limit); ?>" maxlength="50" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_optional">Incorporation Date:</label>
                    <div class="col-sm-8">
                        <?php echo HTML::datebox('incorporation_date', $pool->incorporation_date); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_optional">Annual Turnover:</label>
                    <div class="col-sm-8">
                        <input class="form-control optional" type="text" name="annual_turnover" value="<?php echo htmlspecialchars((string)$pool->annual_turnover); ?>" onkeypress="return numbersonly(this);" maxlength="8" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label fieldLabel_optional">Net Worth:</label>
                    <div class="col-sm-8">
                        <input class="form-control optional" type="text" name="net_worth" value="<?php echo htmlspecialchars((string)$pool->net_worth); ?>" maxlength="50" />
                    </div>
                </div>

                <div class="callout callout-default">
                    <span class="lead text-bold text-blue">Director Details</span>

                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Director Title:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="director_title" value="<?php echo htmlspecialchars((string)$pool->director_title); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Director Forename:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="director_forename" value="<?php echo htmlspecialchars((string)$pool->director_forename); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Director Surname:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="director_surname" value="<?php echo htmlspecialchars((string)$pool->director_surname); ?>" />
                        </div>
                    </div>
                </div>
                <div class="callout callout-default">
                    <span class="lead text-bold text-blue">Main Site</span>

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
                    <div class="form-group">
                        <label for="region" class="col-sm-4 control-label fieldLabel_optional">Region:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="region" id="region" value="<?php echo $mainLocation->region; ?>" maxlength="150" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country" class="col-sm-4 control-label fieldLabel_optional">Country:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="country" id="country" value="<?php echo $mainLocation->country; ?>" maxlength="150" />
                        </div>
                    </div>

                </div>




            </div>

            <div class="col-sm-6">
                <div class="callout callout-default">
                    <span class="lead text-bold text-blue">Identifiers</span>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Company Number (Companies House):</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="company_number" value="<?php echo htmlspecialchars((string)$pool->company_number); ?>" maxlength="20" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">EDRS:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="edrs" value="<?php echo htmlspecialchars((string)$pool->edrs); ?>" maxlength="30" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Client ID:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="client_id" value="<?php echo htmlspecialchars((string)$pool->client_id); ?>" maxlength="20" />
                        </div>
                    </div>

                </div>
                <div class="callout callout-default">
                    <span class="lead text-bold text-blue">Domain/URL Info</span>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Website:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="website" value="<?php echo htmlspecialchars((string)$pool->website); ?>" maxlength="250" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Domain Name:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="domain_name" value="<?php echo htmlspecialchars((string)$pool->domain_name); ?>" maxlength="250" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Email Domain:</label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="email_domain" value="<?php echo htmlspecialchars((string)$pool->email_domain); ?>" maxlength="250" />
                        </div>
                    </div>

                </div>
                <div class="callout callout-default">
                    <span class="lead text-bold text-blue">Social Media</span>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">LinkedIn Page <i class="fa fa-linkedin-square"></i></label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="linked_in_page" name="linked_in_page" value="<?php echo htmlspecialchars((string)$pool->linked_in_page); ?>" maxlength="500" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Twitter Handle: <i class="fa fa-twitter-square"></i></label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="twitter_handle" name="twitter_handle" value="<?php echo htmlspecialchars((string)$pool->twitter_handle); ?>" maxlength="500" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_optional">Facebook Page <i class="fa fa-facebook-square"></i></label>
                        <div class="col-sm-8">
                            <input class="form-control optional" type="text" name="facebook_page" value="<?php echo htmlspecialchars((string)$pool->facebook_page); ?>" maxlength="500" />
                        </div>
                    </div>

                </div>

            </div>

	    <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <span class="btn btn-sm btn-block btn-primary" onclick="savePoolOrganisation();">
                            <i class="fa fa-save"></i> Save
                        </span>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </div>		

        </form>
    </div>

    <br>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script language="JavaScript">
        $(function() {

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });

            // $('#input_start_date').attr('class', 'datepicker compulsory form-control');
            // $('#input_end_date').attr('class', 'datepicker compulsory form-control');
        });

        function savePoolOrganisation() {
            var frmPoolOrganisation = document.forms["frmPoolOrganisation"];
            if (validateForm(frmPoolOrganisation) == false) {
                return false;
            }
            frmPoolOrganisation.submit();
        }

	function trading_name_onfocus(trading_name)
        {
            if(trading_name.value == '')
            {
                trading_name.value = trading_name.form.elements['legal_name'].value;
            }
        }
    </script>

</body>

</html>