<?php
$learning_styles_assessment = DAO::getObject($link, "SELECT * FROM ob_learner_learning_style WHERE tr_id = '{$tr->id}'");

if(isset($learning_styles_assessment->form_data))
{
    $learning_style_form_data = json_decode($learning_styles_assessment->form_data);
    $_answer_a = 0;
    $_answer_b = 0;
    $_answer_c = 0;

    foreach($learning_style_form_data AS $_key => $_value)
    {
        if(substr($_key, 0, 9) == 'question_')
        {
            if($_value == 'a')
                $_answer_a++;
            elseif($_value == 'b')
                $_answer_b++;
            elseif($_value == 'c')
                $_answer_c++;
        }
    }
?>
<div class="row">
    <div class="col-sm-8">
        <table class="table table-bordered">
            <?php 
            
            $result = DAO::getResultset($link, "SELECT * FROM lookup_learning_style_assessment ORDER BY id", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                $question_id = 'question_' . $row['id'];
                $question_options = [
                    ['a', $row['opt_a'], ''],
                    ['b', $row['opt_b'], ''],
                    ['c', $row['opt_c'], ''],
                ];
                echo '<tr>';
                    echo '<td>';
                        echo '<div class="callout callout-default">';
                            echo '<p class="text-bold text-info">' . $row['question'] . '</p>';
                            echo '<table class="table" style="margin-left: 10px;">';
                            foreach($question_options AS $opt)
                            {
                                $checked = ( isset($learning_style_form_data->$question_id) && $learning_style_form_data->$question_id == $opt[0] ) ? '<i class="fa fa-check fa-lg"></i>' : '';
                                echo '<tr>';
                                echo '<td>' . strtoupper($opt[0] ?? '') . ')</td>';
                                echo '<td>';
                                echo $checked . ' &nbsp; ' . $opt[1];
                                echo '</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                        echo '</div>';    
                    echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
    <div class="col-sm-4">
        <?php 
        if($_answer_a > $_answer_b && $_answer_a > $_answer_c)
        {
            echo '<p class="text-info">Based on assessment the learning style of the learner is: <span class="text-bold lead">VISUAL</span></p>';
        }
        elseif($_answer_b > $_answer_a && $_answer_b > $_answer_c)
        {
            echo '<p class="text-info">Based on assessment the learning style of the learner is: <span class="text-bold lead">AUDITORY</span></p>';
        }
        elseif($_answer_c > $_answer_a && $_answer_c > $_answer_b)
        {
            echo '<p class="text-info">Based on assessment the learning style of the learner is: <span class="text-bold lead">KINAESTHETIC </span></p>';
        }
        elseif($_answer_a > $_answer_b && $_answer_a == $_answer_c)
        {
            echo '<p class="text-info">Based on assessment the learning style of the learner is: <span class="text-bold lead">VISUAL & KINAESTHETIC </span></p>';
        }
        elseif($_answer_a == $_answer_b && $_answer_a > $_answer_c)
        {
            echo '<p class="text-info">Based on assessment the learning style of the learner is: <span class="text-bold lead">VISUAL & AUDITORY </span></p>';
        }
        elseif($_answer_b > $_answer_a && $_answer_b == $_answer_c)
        {
            echo '<p class="text-info">Based on assessment the learning style of the learner is: <span class="text-bold lead">AUDITORY & KINAESTHETIC </span></p>';
        }
        ?>
    </div>
</div>
<?php 
}
else
{
    echo '<p><br><i class="fa fa-info-circle"></i> Learner has not yet completed the learning style questionnaire.</br></p>';
} 
?>