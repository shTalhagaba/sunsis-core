<div class="row vertical-center-row">
    <div class="col-sm-12">
        <h5 class="lead text-bold"><?php echo $framework->title . ' Delivery Plan'; ?></h5>

        <div class="text-center">
            <?php
            $dp_signs = DAO::getObject($link, "SELECT * FROM delivery_plan_signatures WHERE tr_id = '{$tr->id}'");
            if (isset($dp_signs->learner_sign) && $dp_signs->learner_sign != '') {
                echo '<span class="label label-success"><i class="fa fa-check"></i> Learner Signed</span>';
            }
            if (isset($dp_signs->employer_sign) && $dp_signs->employer_sign != '') {
                echo ' &nbsp; <span class="label label-success"><i class="fa fa-check"></i> Employer Signed</span>';
            }
            if (isset($dp_signs->provider_sign) && $dp_signs->provider_sign != '') {
                echo ' &nbsp; <span class="label label-success"><i class="fa fa-check"></i> Provider Signed</span>';
            }
            ?>
        </div>

    </div>
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">
                <form name="frmUploadDpFile" id="frmUploadDpFile" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=upload_learner_files" ENCTYPE="multipart/form-data">
                    <input type="hidden" name="_action" value="upload_learner_files" />
                    <input type="hidden" name="ob_learner_id" value="<?php echo $tr->ob_learner_id; ?>" />
                    <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                    <input type="hidden" name="dir" value="delivery_plan" />
                    <table class="table table-responsive">
                        <tr>
                            <td colspan="2">
                                <input class="compulsory" type="file" name="input_uploaded_learner_dp_file" id="input_uploaded_learner_dp_file" accept=".jpg, .pdf, .doc, .docx, .xls, .xlsx, .csv, .txt, .xml, .zip, .rar, .7z" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" id="uploadDpFileButton" class="btn btn-xs btn-primary"><i class="fa fa-upload"></i> Click to Upload</span>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="box-footer">
                <?php echo $this->renderFileRepository($tr, "delivery_plan"); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <table style="margin-top: 5px;" class="table table-bordered table-condensed">
            <caption class="bg-gray-light text-bold" style="padding: 5px;">Signatures:</caption>
            <tr>
                <th>Learner</th>
                <th>Employer</th>
            </tr>
            <tr>
                <td>
                    <?php if (isset($dp_signs->learner_sign) && $dp_signs->learner_sign != '') { ?>
                        <img src="do.php?_action=generate_image&<?php echo $dp_signs->learner_sign ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                        <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?><br>
                        <?php echo Date::toShort($dp_signs->learner_sign_date); ?>
                    <?php } else { ?>
                        <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                    <?php } ?>
                </td>
                <td>
                    <?php if (isset($dp_signs->employer_sign) && $dp_signs->employer_sign != '') { ?>
                        <img src="do.php?_action=generate_image&<?php echo $dp_signs->employer_sign ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                        <?php echo $dp_signs->employer_sign_name; ?><br>
                        <?php echo Date::toShort($dp_signs->employer_sign_date); ?>
                    <?php } else { ?>
                        <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
</div>