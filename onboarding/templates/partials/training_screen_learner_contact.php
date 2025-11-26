<form class="form-horizontal" name="frmLearnerContacts" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
    <input type="hidden" name="_action" value="save_learner_contacts" />
    <div class="row">
        <div class="col-sm-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2 class="box-title">Details</h2>
                    <div class="pull-right box-tools">
                        <span class="btn btn-sm btn-primary" onclick="save_learner_contacts();">
                            <i class="fa fa-save"></i> Save Learner Contacts
                        </span>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-responsive row-border cw-table-list">
                        <tr>
                            <th style="width: 10%;">Title</th>
                            <th style="width: 20%;">Full Name</th>
                            <th style="width: 20%;">Relationship</th>
                            <th style="width: 15%;">Telephone</th>
                            <th style="width: 15%;">Mobile</th>
                            <th style="width: 20%;">Email</th>
                        </tr>
                        <?php
			$titlesDdl = [
                            ['Mr', 'Mr'],
                            ['Mrs', 'Mrs'],
                            ['Miss', 'Miss'],
                            ['Ms', 'Ms']
                        ];
                        $saved_contacts = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
                        
                        for ($i = 1; $i <= 2; $i++) {
                            if (isset($saved_contacts[$i - 1]['tr_id']) && isset($saved_contacts[$i - 1]['tr_id']) == $tr->id) {
                                echo '<tr>';
                                echo '<td>';
                                echo HTML::select("em_con_title{$i}", $titlesDdl, $saved_contacts[$i - 1]['em_con_title'], true);
                                echo '</td>';
                                echo '<td><input class="form-control" type="text" name="em_con_name' . $i . '" id="em_con_name' . $i . '" value="' . $saved_contacts[$i - 1]['em_con_name'] . '" /></td>';
                                echo '<td><input class="form-control" type="text" name="em_con_rel' . $i . '" id="em_con_rel' . $i . '" value="' . $saved_contacts[$i - 1]['em_con_rel'] . '" /></td>';
                                echo '<td><input class="form-control" type="text" name="em_con_tel' . $i . '" id="em_con_tel' . $i . '" value="' . $saved_contacts[$i - 1]['em_con_tel'] . '" /></td>';
                                echo '<td><input class="form-control" type="text" name="em_con_mob' . $i . '" id="em_con_mob' . $i . '" value="' . $saved_contacts[$i - 1]['em_con_mob'] . '" /></td>';
				echo '<td><input class="form-control" type="email" name="em_con_email' . $i . '" id="em_con_email' . $i . '" value="' . $saved_contacts[$i - 1]['em_con_email'] . '" /></td>';
                                echo '</tr>';
                            } else {
                                echo '<tr>';
                                echo '<td>';
                                echo HTML::select("em_con_title{$i}", $titlesDdl, '', true);
                                echo '</td>';
                                echo '<td><input class="form-control" type="text" name="em_con_name' . $i . '" id="em_con_name' . $i . '" value="" /></td>';
                                echo '<td><input class="form-control" type="text" name="em_con_rel' . $i . '" id="em_con_rel' . $i . '" value="" /></td>';
                                echo '<td><input class="form-control" type="text" name="em_con_tel' . $i . '" id="em_con_tel' . $i . '" value="" /></td>';
                                echo '<td><input class="form-control" type="text" name="em_con_mob' . $i . '" id="em_con_mob' . $i . '" value="" /></td>';
				echo '<td><input class="form-control" type="email" name="em_con_email' . $i . '" id="em_con_email' . $i . '" value="" /></td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>