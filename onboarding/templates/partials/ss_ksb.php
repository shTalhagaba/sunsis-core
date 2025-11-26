<?php
$scores = LookupHelper::getDDLKsbScores();
?>
<div class="row">
    <div class="col-sm-12">
        <p class="small">
            In this section, choose which statement best describes your knowledge, skills or experience against the
            apprenticeship programme elements in the left-hand columns - where you do have some prior relevant
            experience or knowledge, tell us and how and when this was obtained - i.e. through a current or prior
            qualification, or experience in your current or previous role.
        </p>
        <p class="text-bold small">
            Key
        </p>
        <ul style="margin-left: 15px;">
            <li><span class="text-bold">1</span> - I have <span class="text-bold">no knowledge or skills</span> in this topic area.</li>
            <li><span class="text-bold">2</span> - I have <span class="text-bold">minimal knowledge and skills</span> in this topic area.</li>
            <li><span class="text-bold">3</span> - I have <span class="text-bold">some of the knowledge and skills</span> to carry out my role, but not yet to full competence and with confidence.</li>
            <li><span class="text-bold">4</span> - I have <span class="text-bold">the majority of the knowledge and skills</span> required to carry out my role, but not yet fully competent and confident.</li>
            <li><span class="text-bold">5</span> - I am <span class="text-bold">fully competent</span> in this area and can provide evidence to support.</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Knowledge, Skills & Behaviours</th>
                    <th>Topic</th>
                    <th>What is required?</th>
                    <th style="width: 25%;">Score 1-5 <small>(please select from drop down list)</small></th>
                    <th style="width: 20%;">Comments/Details</th>
                </tr>
                <?php
                $ksb_result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
                foreach($skills_analysis->ksb AS $row)
                {
                    echo '<tr>';
                    echo '<td>' . $row['unit_group'] .'</td>';
                    echo '<td class="small">' . $row['unit_title'] .'</td>';
                    echo '<td>' . $row['evidence_title'] .'</td>';
                    echo '<td>' . HTML::selectChosen('score_' . $row['id'], $scores, $row['score'], true) .'</td>';
                    echo '<td><textarea name="comments_' . $row['id'] . '" id="comments_' . $row['id'] . '" style="width: 100%;">'.nl2br($row['comments'] ?? '').'</textarea></td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>

</div>

<script>
    $(function(){
        $("select[name^=score_]").on('change', function(){
            if(this.value != '')
                $(this).closest('td').removeClass('bg-red');
        }) ;
    });
</script>