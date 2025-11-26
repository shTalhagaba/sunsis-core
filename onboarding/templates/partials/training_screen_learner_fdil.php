<?php 
$fdil_entry = DAO::getObject($link, "SELECT * FROM ob_learner_fdil WHERE tr_id = '{$tr->id}'");
?>

<?php if(!isset($fdil_entry->tr_id)){ ?>
<div class="row vertical-center-row">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmFdil">
        <input type="hidden" name="_action" value="save_fdil">
        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">

        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th class="bg-gray text-center" colspan="4">Session</th>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="fdil_session_date" class="col-sm-3 control-label">Date:</label>
                                    <div class="col-sm-9"><?php echo HTML::datebox('fdil_session_date', ''); ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="fdil_session_start_time" class="col-sm-3 control-label">Start Time:</label>
                                    <div class="col-sm-9"><?php echo HTML::timebox('fdil_session_start_time', ''); ?><br>(24 Hours format)</div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="fdil_session_end_time" class="col-sm-3 control-label">End Time:</label>
                                    <div class="col-sm-9"><?php echo HTML::timebox('fdil_session_end_time', ''); ?><br>(24 Hours format)</div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="fdil_session_hours" class="col-sm-3 control-label">Hours:</label>
                                    <div class="col-sm-9"><input type="number" name="fdil_session_hours" id="fdil_session_hours" value="" /></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Trainer Name:</th>
                            <td><input class="form-control" type="text" name="fdil_trainer_name" id="fdil_trainer_name" value=""></td>
                            <th></th>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">
                                <button type="button" class="btn btn-xs btn-success btn-block" onclick="submitFrmFdil();"><i class="fa fa-save"></i> Save</button><br>
                                <span class="text-warning"><i class="fa fa-warning"></i> Please only create this entry if everything else has been completed for this learner.</span>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>
<?php } else {?>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Session Date</th><th>Session Start Time</th><th>Session End Time</th><th>Session Hours</th><th>Learner Comments</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo Date::toShort($fdil_entry->fdil_session_date); ?></td>
                            <td><?php echo $fdil_entry->fdil_session_start_time; ?></td>
                            <td><?php echo $fdil_entry->fdil_session_end_time; ?></td>
                            <td><?php echo $fdil_entry->fdil_session_hours; ?></td>
                            <td rowspan="2"><?php echo nl2br($fdil_entry->learner_comments ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                Learner Sign<br>
                                <img src="do.php?_action=generate_image&<?php echo $fdil_entry->learner_sign != '' ? $fdil_entry->learner_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo $fdil_entry->learner_sign_date != '' ? Date::toShort($fdil_entry->learner_sign_date) : ''; ?>
                            </th>
                            <th colspan="2">
                                Tutor Sign<br>
                                <img src="do.php?_action=generate_image&<?php echo $fdil_entry->tutor_sign != '' ? $fdil_entry->tutor_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo $fdil_entry->tutor_sign_date != '' ? Date::toShort($fdil_entry->tutor_sign_date) : ''; ?>
                            </th>
                        </tr>
                        <?php if($fdil_entry->learner_sign != '' && $fdil_entry->tutor_sign != '') { ?>
                        <tr>
                            <td colspan="5">
                                <span class="btn btn-xs btn-success" onclick="generateFdilPdf();"><i class="fa fa-file-pdf-o"></i> Generate PDF</span>
                            </td>
                        </tr>
                        <?php } ?>
			<?php if($_SESSION['user']->isTypeAdmin()) { ?>
                        <tr>
                            <td colspan="5">
                                <span class="btn btn-xs btn-danger" onclick="deleteFdil('<?php echo $fdil_entry->id; ?>');"><i class="fa fa-trash"></i> Delete</span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>