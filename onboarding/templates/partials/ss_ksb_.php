<?php
$scores = LookupHelper::getDDLKsbScores();
$saved_ksb_entries = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learner_ksb WHERE tr_id = '{$tr->id}' AND score > 0");
?>
<div class="row">
    <div class="col-sm-12">
        <span class="lead text-bold"><?php echo $framework->title; ?></span><p><br></p>
    </div>
</div>
<?php if($skills_analysis->lock_for_learner == 1) { ?>
<div class="row">
    <div class="col-sm-12">
        <p class="text-center text-info"><i class="fa fa-info-circle"></i> Your Skills Analysis form is completed by ELA Training. You can just view and move to next page for your sign and submit the form.</span><p><br></p>
    </div>
</div>
<?php } ?>

<div class="row">
    <div class="col-sm-12 well well-sm">
        <?php
            $ksb_result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
            $i = 0;
            foreach($skills_analysis->ksb AS $row)
            {
                echo '<div class="box box-primary" style="box-shadow: 0 5px 5px rgba(1, 1, 0, 0.1)">';

                echo '<div class="box-header with-border">';

                echo '<div class="box-title">';
                echo '<span class="text-bold">' . ++$i . '/' . count($skills_analysis->ksb) . ': </span>';
                echo $row['evidence_title'];
                echo '</div>'; // box-title

                echo '</div>'; // box-header

                echo '<div class="box-body">';
                echo '<div class="form-group">';
                echo '<label for="score_' . $row['id'] . '" class="col-sm-4 control-label fieldLabel_compulsory">Choose an answer that most applies to you: </label>';
                echo '<div class="col-sm-8">';
                echo $skills_analysis->lock_for_learner == 1 ? 
                    HTML::selectChosen('score_' . $row['id'], $scores, $row['score'], true, false, false) : 
                    HTML::selectChosen('score_' . $row['id'], $scores, $row['score'], true);
                echo '</div>';
                echo '</div>';

                echo '<div class="form-group">';
                echo '<label for="comments_' . $row['id'] . '" class="col-sm-4 control-label fieldLabel_optional">Any comments: </label>';
                echo '<div class="col-sm-8">';
                echo $skills_analysis->lock_for_learner == 1 ? 
                    '<textarea disabled="disabled" name="comments_' . $row['id'] . '" id="comments_' . $row['id'] . '" style="width: 100%;">'.nl2br($row['comments'] ?? '').'</textarea>' : 
                    '<textarea name="comments_' . $row['id'] . '" id="comments_' . $row['id'] . '" style="width: 100%;">'.nl2br($row['comments'] ?? '').'</textarea>';
                echo '</div>';
                echo '</div>';
                echo '</div>'; // box-body

                echo '</div>'; // box
            }
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <p class="text-right text-info">You have answered <span id="lblAnswers"><?php echo $saved_ksb_entries; ?></span> out of total <?php echo count($skills_analysis->ksb); ?> questions.</p>
    </div>
</div>


<script>
    $(function(){
        
        function ksb_questions_answered()
        {
            var _ksb_questions_answerd = 0;
            $("select[name^=score_]").each(function(){
                if(this.value != '')
                {
                    _ksb_questions_answerd++;
                }
                $("span#lblAnswers").html(_ksb_questions_answerd);
            });
        }

        $("select[name^=score_]").on('change', function(){
            if(this.value != '')
            {
                $(this).closest('div.box-body').removeClass('bg-red');
                $(this).closest('div.box').removeClass('box-danger');
                $(this).closest('div.box').addClass('box-primary');
            }            
            ksb_questions_answered();
        }) ;
    });
</script>