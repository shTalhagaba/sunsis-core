<?php /* @var $employer Employer */ ?>
<?php /* @var $hs EmployerHealthAndSafety */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $hs->id == '' ? 'Create Employer H&S record' : 'Edit Employer H&S Record'; ?></title>
    <link rel="stylesheet" href="css/common.css" type="text/css" />
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

        input[type=checkbox],
        input[type=radio] {
            transform: scale(1.4);
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-sm-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;"><?php echo $hs->id == '' ? 'Create Employer H&S Record' : 'Edit Employer H&S Record'; ?></div>
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
        <div class="col-sm-12">
            <?php $_SESSION['bc']->render($link); ?>
        </div>
    </div>
    <br>

    <div class="container-fluid">
        <form class="form-horizontal" name="frmEmployerHs" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $hs->id; ?>" />
            <input type="hidden" name="employer_id" value="<?php echo $hs->employer_id; ?>" />
            <input type="hidden" name="_action" value="save_employer_hs" />
            <div class="row">
                <div class="col-sm-7">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <h2 class="box-title">Details</h2>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <label for="location_id" class="col-sm-4 control-label fieldLabel_optional">Location:</label>
                                <div class="col-sm-8">
                                    <?php
                                    echo HTML::selectChosen('location_id', DAO::getResultset($link, "SELECT locations.id, CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),', ',COALESCE(`postcode`,''), ')') AS detail, null FROM locations WHERE locations.organisations_id = '$hs->employer_id' ORDER BY full_name ;"), '', false, true);
                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="assessment_type" class="col-sm-4 control-label fieldLabel_optional">Assessment Type:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('assessment_type', [['1', 'Initial Assessment'], ['2', 'Re-Assessment'], ['3', 'Other (please specify)']], $hs->assessment_type, true); ?>
                                    <br>
                                    <input class="form-control" type="text" name="assessment_type_other" id="assessment_type_other" value="<?php echo $hs->assessment_type_other; ?>" maxlength="150" placeholder="Specify assessment type" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="last_assessment" class="col-sm-4 control-label fieldLabel_optional">Date of Last Assessment:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('last_assessment', $hs->last_assessment, false); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="next_assessment" class="col-sm-4 control-label fieldLabel_optional">Date of Next Assessment:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('next_assessment', $hs->next_assessment, false); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="assessor" class="col-sm-4 control-label fieldLabel_optional">Assessor:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="assessor" id="assessor" value="<?php echo $hs->assessor; ?>" maxlength="50">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="employer_rep" class="col-sm-4 control-label fieldLabel_optional">Employer Representative:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('employer_rep', $employerRepsDDL, $hs->employer_rep, true, false); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="hs_contact_person" class="col-sm-4 control-label fieldLabel_optional">Employer Health And Safety Contact Person:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('hs_contact_person', $employerRepsDDL, $hs->hs_contact_person, true, false); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pl_date" class="col-sm-4 control-label fieldLabel_optional">PL Expiry Date:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('pl_date', $hs->pl_date); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pl_insurer" class="col-sm-4 control-label fieldLabel_optional">PL Insurer:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="pl_insurer" id="pl_insurer" value="<?php echo $hs->pl_insurer; ?>" maxlength="185" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pl_insurance" class="col-sm-4 control-label fieldLabel_optional"> PL Insurance:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="pl_insurance" id="pl_insurance" value="<?php echo $hs->pl_insurance; ?>" maxlength="25" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="el_date" class="col-sm-4 control-label fieldLabel_optional">EL Expiry Date:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('el_date', $hs->el_date); ?>
                                </div>
                            </div>

                           <div class="form-group">
                                <label for="el_insurer" class="col-sm-4 control-label fieldLabel_optional">EL Insurer:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="el_insurer" id="el_insurer" value="<?php echo $hs->el_insurer; ?>" maxlength="185" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="el_insurance" class="col-sm-4 control-label fieldLabel_optional"> EL Insurance:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="el_insurance" id="el_insurance" value="<?php echo $hs->el_insurance; ?>" maxlength="25" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="comments" class="col-sm-4 control-label fieldLabel_optional">Comments:</label>
                                <div class="col-sm-8">
                                    <textarea name="comments" id="comments" class="form-control" rows="2"><?php echo nl2br($hs->comments); ?></textarea>
                                </div>
                            </div>

                            <div class="callout callout-default">
                                <label for="complient" class="col-sm-4 control-label fieldLabel_optional">Outcome Status:</label>
                                <div class="col-sm-8">
                                    <table class="table row-border">
                                        <tr>
                                            <td style="width: 50%;">Compliant</td>
                                            <td><input type="radio" name="complient" value="1" <?php echo $hs->complient == 1 ? 'checked' : ''; ?> ></td>
                                        </tr>
                                        <tr>
                                            <td>Non-Compliant</td>
                                            <td><input type="radio" name="complient" value="2" <?php echo $hs->complient == 2 ? 'checked' : ''; ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Outstanding Action</td>
                                            <td><input type="radio" name="complient" value="3" <?php echo $hs->complient == 3 ? 'checked' : ''; ?>></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <label for="paperwork_received" class="col-sm-4 control-label fieldLabel_optional"> Paperwork Received:</label>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="paperwork_received" id="paperwork_received" value="1" <?php echo $hs->paperwork_received == 1 ? 'checked' : ''; ?> />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="recommendation" class="col-sm-4 control-label fieldLabel_optional">RAG Status:</label>
                                    <div class="col-sm-8">
                                        <?php echo HTML::selectChosen('age_range', [['1', 'Red'], ['2', 'Amber'], ['3', 'Green']], $hs->age_range, true); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="recommendation" class="col-sm-4 control-label fieldLabel_optional">Recommendation:</label>
                                    <div class="col-sm-8">
                                        <?php echo HTML::selectChosen('recommendation', [['1', 'Suitable'], ['2', 'Suitable with Action Plan'], ['3', 'Unsuitable']], $hs->recommendation, true); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="risk_category" class="col-sm-4 control-label fieldLabel_optional">Risk Category:</label>
                                    <div class="col-sm-8">
                                        <?php echo HTML::selectChosen('risk_category', [['Low', 'Low'], ['Medium', 'Medium'], ['High', 'High']], $hs->risk_category, true); ?>
                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="box-footer">
                            <span class="btn btn-sm btn-primary btn-block" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="callout callout-default">
                        <?php
                        echo '<span class="lead text-bold">' . $employer->legal_name . '</span><br>';
                        echo $org_main_location->address_line_1 != '' ? $org_main_location->address_line_1 . '<br>' : '';
                        echo $org_main_location->address_line_2 != '' ? $org_main_location->address_line_2 . '<br>' : '';
                        echo $org_main_location->address_line_3 != '' ? $org_main_location->address_line_3 . '<br>' : '';
                        echo $org_main_location->address_line_4 != '' ? $org_main_location->address_line_4 . '<br>' : '';
                        echo $org_main_location->postcode != '' ? $org_main_location->postcode . '<br>' : '';
                        ?>
                    </div>
                    <?php 
                    $where = $hs->id != '' ? ' AND health_safety.id != ' . $hs->id : '';
                    $sql = <<<HEREDOC
SELECT
health_safety.`id`,
`employer_id`,
`location_id`,
DATE_FORMAT(`last_assessment`, '%d/%m/%Y') AS `last_assessment`,
DATE_FORMAT(`next_assessment`, '%d/%m/%Y') AS `next_assessment`,
`assessor`,
`comments`,
CASE `complient`
    WHEN '1' THEN 'Compliant'
    WHEN '2' THEN 'Non-Compliant'
    WHEN '3' THEN 'Outstanding Action'
    ELSE ''
END AS `complient`,
IF(`paperwork_received` = 1, 'Yes', 'No') AS `paperwork_received`,
CASE `age_range`
    WHEN '1' THEN 'Red'
    WHEN '2' THEN 'Amber'
    WHEN '3' THEN 'Green'
    ELSE ''
END AS `rag_status`,
DATE_FORMAT(`pl_date`, '%d/%m/%Y') AS `pl_date`,
`pl_insurance`,
DATE_FORMAT(`el_date`, '%d/%m/%Y') AS `el_date`,
`el_insurance`,
`employer_rep`,
`assessment_type`,
`assessment_type_other`,
CASE `recommendation`
    WHEN '1' THEN 'Suitable'
    WHEN '2' THEN 'Suitable with Action Plan'
    WHEN '3' THEN 'Unsuitable'
    ELSE ''
END AS `recommendation`,
`risk_category`,
`hs_contact_person`,
    locations.`full_name`,
    locations.`address_line_1`,
    locations.`address_line_2`,
    locations.`address_line_3`,
    locations.`address_line_4`,
    locations.`postcode`,
    locations.organisations_id
FROM
    health_safety INNER JOIN locations ON health_safety.`location_id` = locations.`id`
WHERE
    locations.organisations_id = '{$hs->employer_id}' {$where}
ORDER BY
    health_safety.`last_assessment`
;
HEREDOC;

                    $st = $link->query($sql);
                    if($st)
                    {
                        while($row = $st->fetch())
                        {
                            $assessment_type = '';
                            if($row['assessment_type'] == 1)
                            {
                                $assessment_type = 'Inital Assessment';
                            }
                            elseif($row['assessment_type'] == 2)
                            {
                                $assessment_type = 'Re-Assessment';
                            }
                            elseif($row['assessment_type'] == 3)
                            {
                                $assessment_type = $row['assessment_type_other'];
                            }
            
                            echo <<<HTML
<div class="box box-primary">
    <div class="box-header with-border">
        <span class="box-title">{$assessment_type}</span>		
    </div>
    <div class="box-body">
        <span class="text-bold">Location: </span> {$row['full_name']} {$row['address_line_1']} {$row['address_line_2']} {$row['address_line_3']} {$row['address_line_4']} {$row['postcode']}<br>
        <span class="text-bold">Last Assessment: </span>{$row['last_assessment']} | <span class="text-bold">Next Assessment: </span>{$row['next_assessment']} | <span class="text-bold">Assessor: </span>{$row['assessor']} | 
        <span class="text-bold">PL Date: </span>{$row['pl_date']} | <span class="text-bold">PL Insurance: </span>{$row['pl_insurance']} | <span class="text-bold">Comments: </span>{$row['comments']} <br>
        <span class="text-bold">Outcome Status: </span>{$row['complient']} | <span class="text-bold">Paperwork Received: </span>{$row['paperwork_received']} | <span class="text-bold">RAG Status: </span>{$row['rag_status']}
    </div>
</div>				
HTML;
            
                        }
                    }
                    else
                    {
                        throw new DatabaseException($link, $sql);
                    }
                    ?>
                </div>
            </div>

        </form>
    </div>

    <br>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>

    <script language="JavaScript">
        function save() {
            var myForm = document.forms["frmEmployerHs"];
            if (!validateForm(myForm)) {
                return;
            }

            myForm.submit();
        }

        $(function() {

            $('.datepicker').addClass('form-control');
            assessment_type_onchange(document.forms['frmEmployerHs'].elements["assessment_type"]);

        });

        function assessment_type_onchange(assessment_type) {
            $('#assessment_type_other').hide();

            if (assessment_type.value == '3') {
                $('#assessment_type_other').show();
            }
        }
    </script>

</body>

</html>