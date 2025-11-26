<div class="row">
    <div class="col-sm-12">
        <?php
        $_dob = new Date($ob_learner->dob);
        $_today_date = new Date(date('Y-m-d'));
        $_diff_in_years = (Date::dateDiffInfo($_dob, $_today_date));
        $_diff_in_years = isset($_diff_in_years['year']) ? $_diff_in_years['year'] : '0';

        echo '<div class="callout callout-default">';
        if($_diff_in_years >= 19)
        {
            echo '<p class="text-info">At least 1 emergency contact required</p>';
        }
        else
        {
            echo '<p class="text-info">2 emergency contacts required</p>';
        }
        echo '</div>';
        ?>
        <div class="table-responsive">
            <table class="table table-bordered">

                <caption>
                    Enter details of your emergency contacts
                </caption>
                <tr>
                    <th>Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>Full Name</th>
                    <th>Relationship</th>
                    <th>Telephone</th>
                    <th>Mobile</th>
                </tr>
                <?php
                $emergency_contacts_result = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
                $i = 1;
                foreach($emergency_contacts_result AS $row_emergency_contact_row)
                {
                    echo '<tr>';
                    echo '<td>' . HTML::selectChosen('em_con_title'.$i, $titlesDDl, $row_emergency_contact_row['em_con_title'], true) . '</td>';
                    echo '<td><input type="text" name="em_con_name'.$i.'" id="em_con_name'.$i.'" value="'.$row_emergency_contact_row['em_con_name'].'" maxlength="99"></td>';
                    echo '<td><input type="text" name="em_con_rel'.$i.'" id="em_con_rel'.$i.'" value="'.$row_emergency_contact_row['em_con_rel'].'" maxlength="99"></td>';
                    echo '<td><input type="text" name="em_con_tel'.$i.'" id="em_con_tel'.$i.'" value="'.$row_emergency_contact_row['em_con_tel'].'" maxlength="69"></td>';
                    echo '<td><input type="text" name="em_con_mob'.$i.'" id="em_con_mob'.$i.'" value="'.$row_emergency_contact_row['em_con_mob'].'" maxlength="69"></td>';
                    echo '</tr>';
                    $i++;
                }
                if($i < 3)
                {
                    for($j = $i; $j < 3; $j++)
                    {
                        echo '<tr>';
                        echo '<td>' . HTML::selectChosen('em_con_title'.$j, $titlesDDl, '', true) . '</td>';
                        echo '<td><input type="text" name="em_con_name'.$j.'" id="em_con_name'.$j.'" value="" maxlength="99"></td>';
                        echo '<td><input type="text" name="em_con_rel'.$j.'" id="em_con_rel'.$j.'" value="" maxlength="99"></td>';
                        echo '<td><input type="text" name="em_con_tel'.$j.'" id="em_con_tel'.$j.'" value="" maxlength="69"></td>';
                        echo '<td><input type="text" name="em_con_mob'.$j.'" id="em_con_mob'.$j.'" value="" maxlength="69"></td>';
                        echo '</tr>';
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>