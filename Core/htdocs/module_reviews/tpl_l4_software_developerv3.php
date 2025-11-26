<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Learner On Programme Review Level 4 Software Developer V3</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>


<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>

<style>

    table.table1{
        font-family: "Arial", sans-serif;
        font-size: 14px;
        font-weight: bold;
        line-height: 1.4em;
        font-style: normal;
        border-collapse:separate;
    }
    .table1 thead th{
        font-family: "Trebuchet MS", sans-serif;
        font-size: 18px;
        font-weight: bold;
        padding:10px;
        color:black;
        text-shadow:1px 1px 1px #568F23;
        text-align: left;
        /*border:1px solid #93CE37;*/
        /*border-bottom:3px solid #9ED929;*/
        /*background-color:#00539F;*/
        background-color:#CCE2CB;
        margin-left: 10px;
    }
    .table1 thead th:empty{
        background:transparent;
        border:none;
    }
    .table1 tbody th{
        color:#fff;
        text-shadow:1px 1px 1px #568F23;
        background-color:#9DD929;
        border:1px solid #93CE37;
        border-right:1px solid #9ED929;
        padding:0px 10px;
        background:-webkit-gradient(
            linear,
            left bottom,
            right top,
            color-stop(0.02, rgb(158,217,41)),
            color-stop(0.51, rgb(139,198,66)),
            color-stop(0.87, rgb(123,192,67))
        );
        background: -moz-linear-gradient(
            left bottom,
            rgb(158,217,41) 2%,
            rgb(139,198,66) 51%,
            rgb(123,192,67) 87%
        );
        -moz-border-radius:5px 0px 0px 5px;
        -webkit-border-top-left-radius:5px;
        -webkit-border-bottom-left-radius:5px;
        border-top-left-radius:5px;
        border-bottom-left-radius:5px;
    }
    .table1 tfoot td{
        color: #9CD009;
        font-size:32px;
        text-align:center;
        padding:10px 0px;
        text-shadow:1px 1px 1px #444;
    }
    .table1 tfoot th{
        color:#666;
    }
    .table1 tbody td{
        padding:5px;
        background-color:#DEF3CA;
        border: 2px solid white;
        text-align: left;
    }

    td.label1 {
        border-top: 1px solid #96d1f8;
        background: #65a9d7;
        background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
        background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
        background: -moz-linear-gradient(top, #3e779d, #65a9d7);
        background: -ms-linear-gradient(top, #3e779d, #65a9d7);
        background: -o-linear-gradient(top, #3e779d, #65a9d7);
        padding: 5px 10px;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
        -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
        box-shadow: rgba(0,0,0,1) 0 1px 0;
        text-shadow: rgba(0,0,0,.4) 0 1px 0;
        color: white;
        font-size: 14px;
        font-family: Georgia, serif;
        text-decoration: none;
        vertical-align: middle;
    }

    .sigbox {
        border-radius: 3px;
        border: 1px solid #EEE;
        cursor: pointer;
        display: block;
        float: left;
        height: 50px;
        margin: 0 0 5px;
        padding: 5px;
        text-align: center;
        width: 286px;
    }
    .sigboxselected {
        border-radius: 3px;
        border: 1px solid #EEE;
        cursor: pointer;
        display: block;
        float: left;
        height: 50px;
        margin: 0 0 5px;
        padding: 5px;
        text-align: center;
        width: 286px;
        background-color: lightgreen;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.6;
        cursor: not-allowed;
    }

</style>

<script language="JavaScript">
var user = 0;
var fonts = Array("PWSignaturetwo.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Jellyka_End_less_Voyage.ttf","Jellyka_Saint-Andrews_Queen.ttf","Little_Days.ttf","Ruf_In_Den_Wind.ttf","Scriptina.ttf","Signature_Regular.ttf","Susies_Hand.ttf","Windsong.ttf","Zeferino_Three.ttf");
var sizes = Array(30,40,15,30,30,30,25,30,30,25,30,30,25,30,30,30);
var source = <?php echo $source; ?>;
$(function()
{
if(source=='1')
{
$('textarea[name="learner_comment"]').attr('class','disabled');
$('input[name="signature_learner_name"]').attr('class','disabled');
$('input[name="signature_learner_date"]').attr('class','disabled');
$('input[name="signature_employer_name"]').attr('class','disabled');
$('input[name="signature_employer_date"]').attr('class','disabled');
//$('textarea[name="employer_progress_review"]').attr('class','disabled');
/*$('input[name="attendance"]').attr('class','disabled');
$('input[name="punctuality"]').attr('class','disabled');
$('input[name="attitude"]').attr('class','disabled');
$('input[name="communication"]').attr('class','disabled');
$('input[name="enthusiasm"]').attr('class','disabled');
$('input[name="commitment"]').attr('class','disabled');
$('textarea[name="behaviours"]').attr('class','disabled');
$('textarea[name="ability"]').attr('class','disabled');
$('textarea[name="skills_knowledge"]').attr('class','disabled');
$('textarea[name="achievements"]').attr('class','disabled');*/
}
if(source=='2')
{
$('input[name="learner_name"]').attr('class','disabled');
//$('input[name="learner_dob"]').attr('class','disabled');
$('input[name="learner_assessor"]').attr('class','disabled');
//$('input[name="learner_ni"]').attr('class','disabled');
$('input[name="learner_employer"]').attr('class','disabled');
$('input[name="learner_manager"]').attr('class','disabled');
$('input[name="learner_programme"]').attr('class','disabled');
$('input[name="learner_qualification"]').attr('class','disabled');
$('input[name="start_date"]').attr('class','disabled');
$('input[name="registration_number"]').attr('class','disabled');
$('input[name="planned_end_date"]').attr('class','disabled');
$('input[name="review_date"]').attr('class','disabled');
$('textarea[name="plagiarism"]').attr('class','disabled');
$('textarea[name="employer_previous_comments"]').attr('class','disabled');
$('textarea[name="significant_achievement"]').attr('class','disabled');
$('textarea[name="equality_diversity"]').attr('class','disabled');
$('textarea[name="safeguarding"]').attr('class','disabled');
$('textarea[name="prevent"]').attr('class','disabled');
$('textarea[name="health_wellbeing"]').attr('class','disabled');
$('textarea[name="concerns"]').attr('class','disabled');
$('textarea[name="commitment"]').attr('class','disabled');
$('textarea[name="additional_support"]').attr('class','disabled');
$('textarea[name="progress"]').attr('class','disabled');
$('textarea[name="discussion"]').attr('class','disabled');
$('textarea[name="err"]').attr('class','disabled');
$('input[name="main_name_unit1"]').attr('class','disabled');
$('input[name="main_name_unit2"]').attr('class','disabled');
$('input[name="main_name_unit3"]').attr('class','disabled');
$('input[name="main_name_unit4"]').attr('class','disabled');
$('input[name="main_name_unit5"]').attr('class','disabled');
$('input[name="main_name_unit6"]').attr('class','disabled');
$('input[name="main_name_unit7"]').attr('class','disabled');
$('input[name="main_name_unit8"]').attr('class','disabled');
$('input[name="main_name_unit9"]').attr('class','disabled');
$('input[name="main_name_unit10"]').attr('class','disabled');
$('input[name="main_name_unit11"]').attr('class','disabled');
$('input[name="main_name_unit12"]').attr('class','disabled');
$('input[name="main_perc_unit1"]').attr('class','disabled');
$('input[name="main_perc_unit2"]').attr('class','disabled');
$('input[name="main_perc_unit3"]').attr('class','disabled');
$('input[name="main_perc_unit4"]').attr('class','disabled');
$('input[name="main_perc_unit5"]').attr('class','disabled');
$('input[name="main_perc_unit6"]').attr('class','disabled');
$('input[name="main_perc_unit7"]').attr('class','disabled');
$('input[name="main_perc_unit8"]').attr('class','disabled');
$('input[name="main_perc_unit9"]').attr('class','disabled');
$('input[name="main_perc_unit10"]').attr('class','disabled');
$('input[name="main_perc_unit11"]').attr('class','disabled');
$('input[name="main_perc_unit12"]').attr('class','disabled');
$('input[name="workshop1"]').attr('class','disabled');
$('input[name="workshop2"]').attr('class','disabled');
$('input[name="workshop3"]').attr('class','disabled');
$('textarea[name="main_progress"]').attr('class','disabled');
$('input[name="tech_name_unit1"]').attr('class','disabled');
$('input[name="tech_name_unit2"]').attr('class','disabled');
$('input[name="tech_name_unit3"]').attr('class','disabled');
$('input[name="tech_name_unit4"]').attr('class','disabled');
$('input[name="tech_name_unit5"]').attr('class','disabled');
$('input[name="tech_name_unit6"]').attr('class','disabled');
$('input[name="tech_name_unit7"]').attr('class','disabled');
$('input[name="tech_name_unit8"]').attr('class','disabled');
$('input[name="tech_name_unit9"]').attr('class','disabled');
$('input[name="tech_name_unit10"]').attr('class','disabled');
$('input[name="tech_name_unit11"]').attr('class','disabled');
$('input[name="tech_name_unit12"]').attr('class','disabled');
$('input[name="tech_perc_unit1"]').attr('class','disabled');
$('input[name="tech_perc_unit2"]').attr('class','disabled');
$('input[name="tech_perc_unit3"]').attr('class','disabled');
$('input[name="tech_perc_unit4"]').attr('class','disabled');
$('input[name="tech_perc_unit5"]').attr('class','disabled');
$('input[name="tech_perc_unit6"]').attr('class','disabled');
$('input[name="tech_perc_unit7"]').attr('class','disabled');
$('input[name="tech_perc_unit8"]').attr('class','disabled');
$('input[name="tech_perc_unit9"]').attr('class','disabled');
$('input[name="tech_perc_unit10"]').attr('class','disabled');
$('input[name="tech_perc_unit11"]').attr('class','disabled');
$('input[name="tech_perc_unit12"]').attr('class','disabled');
$('textarea[name="tech_progress"]').attr('class','disabled');
$('input[name="english_exempt[]"]').attr('class','disabled');
$('input[name="math_exempt[]"]').attr('class','disabled');
$('input[name="ict_exempt[]"]').attr('class','disabled');
$('textarea[name="use_functional"]').attr('class','disabled');
$('input[name="english_l1"]').attr('class','disabled');
$('input[name="english_l2"]').attr('class','disabled');
$('input[name="math_l1"]').attr('class','disabled');
$('input[name="math_l2"]').attr('class','disabled');
$('input[name="ict_l1"]').attr('class','disabled');
$('input[name="ict_l2"]').attr('class','disabled');
$('input[name="plts"]').attr('class','disabled');
$('textarea[name="functional_progress"]').attr('class','disabled');
$('textarea[name="specific"]').attr('class','disabled');
$('textarea[name="measurable"]').attr('class','disabled');
$('textarea[name="achievable"]').attr('class','disabled');
$('textarea[name="timebound"]').attr('class','disabled');
$('input[name="next_contact"]').attr('class','disabled');
$('input[name="signature_assessor_name"]').attr('class','disabled');
$('input[name="signature_assessor_date"]').attr('class','disabled');
$('input[name="signature_employer_name"]').attr('class','disabled');
$('input[name="signature_employer_date"]').attr('class','disabled');
$('textarea[name="employer_progress_review"]').attr('class','disabled');
$('input[name="attendance"]').attr('class','disabled');
$('input[name="punctuality"]').attr('class','disabled');
$('input[name="attitude"]').attr('class','disabled');
$('input[name="communication"]').attr('class','disabled');
$('input[name="enthusiasm"]').attr('class','disabled');
$('input[name="commitment"]').attr('class','disabled');
$('textarea[name="behaviours"]').attr('class','disabled');
$('textarea[name="ability"]').attr('class','disabled');
$('textarea[name="skills_knowledge"]').attr('class','disabled');
$('textarea[name="achievements"]').attr('class','disabled');
$('input[name="knowledge_module_1"]').attr('class','disabled');
$('input[name="knowledge_module_2"]').attr('class','disabled');
$('input[name="knowledge_module_3"]').attr('class','disabled');
$('input[name="knowledge_module_4"]').attr('class','disabled');
$('input[name="knowledge_module_5"]').attr('class','disabled');
$('input[name="knowledge_module_6"]').attr('class','disabled');
$('input[name="knowledge_module_7"]').attr('class','disabled');
$('input[name="knowledge_module_8"]').attr('class','disabled');
$('input[name="knowledge_module_9"]').attr('class','disabled');
$('input[name="knowledge_module_10"]').attr('class','disabled');
$('input[name="knowledge_module_11"]').attr('class','disabled');
$('input[name="knowledge_module_12"]').attr('class','disabled');
$('input[name="knowledge_status_1"]').attr('class','disabled');
$('input[name="knowledge_status_2"]').attr('class','disabled');
$('input[name="knowledge_status_3"]').attr('class','disabled');
$('input[name="knowledge_status_4"]').attr('class','disabled');
$('input[name="knowledge_status_5"]').attr('class','disabled');
$('input[name="knowledge_status_6"]').attr('class','disabled');
$('input[name="knowledge_status_7"]').attr('class','disabled');
$('input[name="knowledge_status_8"]').attr('class','disabled');
$('input[name="knowledge_status_9"]').attr('class','disabled');
$('input[name="knowledge_status_10"]').attr('class','disabled');
$('input[name="knowledge_status_11"]').attr('class','disabled');
$('input[name="knowledge_status_12"]').attr('class','disabled');
$('textarea[name="knowledge_module"]').attr('class','disabled');
$('input[name="workplace_competence_1"]').attr('class','disabled');
$('input[name="workplace_competence_2"]').attr('class','disabled');
$('input[name="workplace_competence_3"]').attr('class','disabled');
$('input[name="workplace_competence_4"]').attr('class','disabled');
$('input[name="workplace_competence_5"]').attr('class','disabled');
$('input[name="workplace_competence_6"]').attr('class','disabled');
$('input[name="workplace_competence_7"]').attr('class','disabled');
$('input[name="workplace_competence_8"]').attr('class','disabled');
$('input[name="workplace_competence_9"]').attr('class','disabled');
$('input[name="workplace_competence_10"]').attr('class','disabled');
$('input[name="workplace_competence_11"]').attr('class','disabled');
$('input[name="workplace_competence_12"]').attr('class','disabled');
$('input[name="workplace_status_1"]').attr('class','disabled');
$('input[name="workplace_status_2"]').attr('class','disabled');
$('input[name="workplace_status_3"]').attr('class','disabled');
$('input[name="workplace_status_4"]').attr('class','disabled');
$('input[name="workplace_status_5"]').attr('class','disabled');
$('input[name="workplace_status_6"]').attr('class','disabled');
$('input[name="workplace_status_7"]').attr('class','disabled');
$('input[name="workplace_status_8"]').attr('class','disabled');
$('input[name="workplace_status_9"]').attr('class','disabled');
$('input[name="workplace_status_10"]').attr('class','disabled');
$('input[name="workplace_status_11"]').attr('class','disabled');
$('input[name="workplace_status_12"]').attr('class','disabled');
$('textarea[name="workplace_competence"]').attr('class','disabled');
$('input[name="present"]').attr('class','disabled');

$('.disabled').keydown(function(e) {
e.preventDefault();
});
}
if(source=='3')
{
$('input[name="learner_name"]').attr('class','disabled');
//$('input[name="learner_dob"]').attr('class','disabled');
$('input[name="learner_assessor"]').attr('class','disabled');
//$('input[name="learner_ni"]').attr('class','disabled');
$('input[name="learner_employer"]').attr('class','disabled');
$('input[name="learner_manager"]').attr('class','disabled');
$('input[name="learner_programme"]').attr('class','disabled');
$('input[name="learner_qualification"]').attr('class','disabled');
$('input[name="start_date"]').attr('class','disabled');
$('input[name="registration_number"]').attr('class','disabled');
$('input[name="planned_end_date"]').attr('class','disabled');
$('input[name="review_date"]').attr('class','disabled');
$('textarea[name="plagiarism"]').attr('class','disabled');
$('textarea[name="employer_previous_comments"]').attr('class','disabled');
$('textarea[name="significant_achievement"]').attr('class','disabled');
$('textarea[name="equality_diversity"]').attr('class','disabled');
$('textarea[name="safeguarding"]').attr('class','disabled');
$('textarea[name="prevent"]').attr('class','disabled');
$('textarea[name="health_wellbeing"]').attr('class','disabled');
$('textarea[name="concerns"]').attr('class','disabled');
$('textarea[name="commitment"]').attr('class','disabled');
$('textarea[name="additional_support"]').attr('class','disabled');
$('textarea[name="progress"]').attr('class','disabled');
$('textarea[name="discussion"]').attr('class','disabled');
$('textarea[name="err"]').attr('class','disabled');
$('input[name="main_name_unit1"]').attr('class','disabled');
$('input[name="main_name_unit2"]').attr('class','disabled');
$('input[name="main_name_unit3"]').attr('class','disabled');
$('input[name="main_name_unit4"]').attr('class','disabled');
$('input[name="main_name_unit5"]').attr('class','disabled');
$('input[name="main_name_unit6"]').attr('class','disabled');
$('input[name="main_name_unit7"]').attr('class','disabled');
$('input[name="main_name_unit8"]').attr('class','disabled');
$('input[name="main_name_unit9"]').attr('class','disabled');
$('input[name="main_name_unit10"]').attr('class','disabled');
$('input[name="main_name_unit11"]').attr('class','disabled');
$('input[name="main_name_unit12"]').attr('class','disabled');
$('input[name="main_perc_unit1"]').attr('class','disabled');
$('input[name="main_perc_unit2"]').attr('class','disabled');
$('input[name="main_perc_unit3"]').attr('class','disabled');
$('input[name="main_perc_unit4"]').attr('class','disabled');
$('input[name="main_perc_unit5"]').attr('class','disabled');
$('input[name="main_perc_unit6"]').attr('class','disabled');
$('input[name="main_perc_unit7"]').attr('class','disabled');
$('input[name="main_perc_unit8"]').attr('class','disabled');
$('input[name="main_perc_unit9"]').attr('class','disabled');
$('input[name="main_perc_unit10"]').attr('class','disabled');
$('input[name="main_perc_unit11"]').attr('class','disabled');
$('input[name="main_perc_unit12"]').attr('class','disabled');
$('input[name="workshop1"]').attr('class','disabled');
$('input[name="workshop2"]').attr('class','disabled');
$('input[name="workshop3"]').attr('class','disabled');
$('textarea[name="main_progress"]').attr('class','disabled');
$('input[name="tech_name_unit1"]').attr('class','disabled');
$('input[name="tech_name_unit2"]').attr('class','disabled');
$('input[name="tech_name_unit3"]').attr('class','disabled');
$('input[name="tech_name_unit4"]').attr('class','disabled');
$('input[name="tech_name_unit5"]').attr('class','disabled');
$('input[name="tech_name_unit6"]').attr('class','disabled');
$('input[name="tech_name_unit7"]').attr('class','disabled');
$('input[name="tech_name_unit8"]').attr('class','disabled');
$('input[name="tech_name_unit9"]').attr('class','disabled');
$('input[name="tech_name_unit10"]').attr('class','disabled');
$('input[name="tech_name_unit11"]').attr('class','disabled');
$('input[name="tech_name_unit12"]').attr('class','disabled');
$('input[name="tech_perc_unit1"]').attr('class','disabled');
$('input[name="tech_perc_unit2"]').attr('class','disabled');
$('input[name="tech_perc_unit3"]').attr('class','disabled');
$('input[name="tech_perc_unit4"]').attr('class','disabled');
$('input[name="tech_perc_unit5"]').attr('class','disabled');
$('input[name="tech_perc_unit6"]').attr('class','disabled');
$('input[name="tech_perc_unit7"]').attr('class','disabled');
$('input[name="tech_perc_unit8"]').attr('class','disabled');
$('input[name="tech_perc_unit9"]').attr('class','disabled');
$('input[name="tech_perc_unit10"]').attr('class','disabled');
$('input[name="tech_perc_unit11"]').attr('class','disabled');
$('input[name="tech_perc_unit12"]').attr('class','disabled');
$('textarea[name="tech_progress"]').attr('class','disabled');
$('input[name="english_exempt[]"]').attr('class','disabled');
$('input[name="math_exempt[]"]').attr('class','disabled');
$('input[name="ict_exempt[]"]').attr('class','disabled');
$('textarea[name="use_functional"]').attr('class','disabled');
$('input[name="english_l1"]').attr('class','disabled');
$('input[name="english_l2"]').attr('class','disabled');
$('input[name="math_l1"]').attr('class','disabled');
$('input[name="math_l2"]').attr('class','disabled');
$('input[name="ict_l1"]').attr('class','disabled');
$('input[name="ict_l2"]').attr('class','disabled');
$('input[name="plts"]').attr('class','disabled');
$('textarea[name="functional_progress"]').attr('class','disabled');
$('textarea[name="specific"]').attr('class','disabled');
$('textarea[name="measurable"]').attr('class','disabled');
$('textarea[name="achievable"]').attr('class','disabled');
$('textarea[name="timebound"]').attr('class','disabled');
$('input[name="next_contact"]').attr('class','disabled');
$('input[name="signature_assessor_name"]').attr('class','disabled');
$('input[name="signature_assessor_date"]').attr('class','disabled');
$('textarea[name="learner_comment"]').attr('class','disabled');
$('input[name="signature_learner_name"]').attr('class','disabled');
$('input[name="signature_learner_date"]').attr('class','disabled');
$('input[name="knowledge_module_1"]').attr('class','disabled');
$('input[name="knowledge_module_2"]').attr('class','disabled');
$('input[name="knowledge_module_3"]').attr('class','disabled');
$('input[name="knowledge_module_4"]').attr('class','disabled');
$('input[name="knowledge_module_5"]').attr('class','disabled');
$('input[name="knowledge_module_6"]').attr('class','disabled');
$('input[name="knowledge_module_7"]').attr('class','disabled');
$('input[name="knowledge_module_8"]').attr('class','disabled');
$('input[name="knowledge_module_9"]').attr('class','disabled');
$('input[name="knowledge_module_10"]').attr('class','disabled');
$('input[name="knowledge_module_11"]').attr('class','disabled');
$('input[name="knowledge_module_12"]').attr('class','disabled');
$('input[name="knowledge_status_1"]').attr('class','disabled');
$('input[name="knowledge_status_2"]').attr('class','disabled');
$('input[name="knowledge_status_3"]').attr('class','disabled');
$('input[name="knowledge_status_4"]').attr('class','disabled');
$('input[name="knowledge_status_5"]').attr('class','disabled');
$('input[name="knowledge_status_6"]').attr('class','disabled');
$('input[name="knowledge_status_7"]').attr('class','disabled');
$('input[name="knowledge_status_8"]').attr('class','disabled');
$('input[name="knowledge_status_9"]').attr('class','disabled');
$('input[name="knowledge_status_10"]').attr('class','disabled');
$('input[name="knowledge_status_11"]').attr('class','disabled');
$('input[name="knowledge_status_12"]').attr('class','disabled');
$('textarea[name="knowledge_module"]').attr('class','disabled');
$('input[name="workplace_competence_1"]').attr('class','disabled');
$('input[name="workplace_competence_2"]').attr('class','disabled');
$('input[name="workplace_competence_3"]').attr('class','disabled');
$('input[name="workplace_competence_4"]').attr('class','disabled');
$('input[name="workplace_competence_5"]').attr('class','disabled');
$('input[name="workplace_competence_6"]').attr('class','disabled');
$('input[name="workplace_competence_7"]').attr('class','disabled');
$('input[name="workplace_competence_8"]').attr('class','disabled');
$('input[name="workplace_competence_9"]').attr('class','disabled');
$('input[name="workplace_competence_10"]').attr('class','disabled');
$('input[name="workplace_competence_11"]').attr('class','disabled');
$('input[name="workplace_competence_12"]').attr('class','disabled');
$('input[name="workplace_status_1"]').attr('class','disabled');
$('input[name="workplace_status_2"]').attr('class','disabled');
$('input[name="workplace_status_3"]').attr('class','disabled');
$('input[name="workplace_status_4"]').attr('class','disabled');
$('input[name="workplace_status_5"]').attr('class','disabled');
$('input[name="workplace_status_6"]').attr('class','disabled');
$('input[name="workplace_status_7"]').attr('class','disabled');
$('input[name="workplace_status_8"]').attr('class','disabled');
$('input[name="workplace_status_9"]').attr('class','disabled');
$('input[name="workplace_status_10"]').attr('class','disabled');
$('input[name="workplace_status_11"]').attr('class','disabled');
$('input[name="workplace_status_12"]').attr('class','disabled');
$('textarea[name="workplace_competence"]').attr('class','disabled');
$('input[name="present"]').attr('class','disabled');

$('.disabled').keydown(function(e) {
e.preventDefault();
});
}

});


function saveDialogue()
{
<?php if(isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER) { ?>

confirmation("Please save your data now and continue to complete the form").then(function (answer) {
var ansbool = (String(answer) == "true");
if(ansbool){

var myForm = document.forms[0];

$.post('do.php?_action=save_arf_introduction.php',$('#form1').serialize(),function(r)
{
return true;
});
}
});
    <?php } ?>
}

function SignatureSelected(sig)
{
$('.sigboxselected').attr('class','sigbox');
sig.className = "sigboxselected";
}

function getSignature(who)
{
user = who;
if(user!=source)
return false;

if(user==2)
{
learner_comments = $('textarea#learner_comment').val();
if(learner_comments.replace(/\s/g, '')=="")
{
custom_alert_OK_only('Please complete learner comments before signing this form');
return 1;
}
}
if(user==3)
{
learner_comments = $('textarea#employer_progress_review').val();

if(learner_comments=="")
{
custom_alert_OK_only('Please complete mandatory information before signing this form');
return 1;
}

}


if(user==1)
{
// Check if previous signature exists
//signature = getPreviousSignature(who)
var client = ajaxRequest('do.php?_action=ajax_get_previous_signature&type=1&user='+ user + '&review_id=' + <?php echo $review_id; ?>);
if(client != null)
{
if(client.responseText != "")
{
// Attach signature
var data = client.responseText;
if(user==1)
{
$("#assessor_signature").attr('src',data);
$("#signature_assessor_font").val(data);
}
else if(user==2)
{
$("#signature_learner_font").val(data);
}
else if(user==3)
{
$("#signature_employer_font").val(data);
}
}
else
{
$( "#panel_signature" ).dialog( "open");
}
}
}
else
{
$( "#panel_signature" ).dialog( "open");
}
}

function deleteRecord()
{
confirmation2("Do you want to delete this review?").then(function (answer) {
var ansbool = (String(answer) == "true");
if(ansbool){

var client = ajaxRequest('do.php?_action=delete_arf_introduction&tr_id=<?php echo $tr_id;?>+&review_id=<?php echo $review_id?>');
window.location.replace('do.php?_action=read_training_record&id=<?php echo rawurlencode($tr_id);?>');
}
});
}

function save()
{
var myForm = document.forms[0];
if(validateForm(myForm) == false)
{
return false;
}

// Date Validation
dBits = $('input[name="signature_assessor_date"]').val();
dBits2 = $('input[name="signature_learner_date"]').val();
dBits3 = $('input[name="signature_employer_date"]').val();
if(dBits!='')
{
dBits = dBits.split("/");
d = new Date(dBits[2],(dBits[1]-1),dBits[0]);
cd = new Date();
if(d>cd)
{
custom_alert_OK_only('Signature date must not be in the future');
return false;
}
}
else if (dBits2!='')
{
dBits2 = dBits2.split("/");
d = new Date(dBits2[2],(dBits2[1]-1),dBits2[0]);
cd = new Date();
if(d>cd)
{
custom_alert_OK_only('Signature date must not be in the future');
return false;
}
}
else if (dBits3!='')
{
dBits3 = dBits3.split("/");
d = new Date(dBits3[2],(dBits3[1]-1),dBits3[0]);
cd = new Date();
if(d>cd)
{
custom_alert_OK_only('Signature date must not be in the future');
return false;
}
}

source = <?php echo $source; ?>;
if(source==2)
{
learner_comments = $('textarea#learner_comment').val();
learner_signature = $("#signature_learner_font").val();
signature_learner_name = $('input[name="signature_learner_name"]').val();

if(learner_comments.replace(/\s/g, '')=="" || dBits2=="" || learner_signature=="" || signature_learner_name.replace(/\s/g, '')=="")
{
custom_alert_OK_only('Please complete learner comments and signature section before saving');
return false;
}
}
if(source==3)
{
learner_comments = $('textarea#employer_progress_review').val();

if(learner_comments=="")
{
custom_alert_OK_only('Please complete employer progress review and signature section before saving');
return false;
}
}
$("#autosave").val('0');
myForm.submit();
$("#autosave").val('1');
}

function checkLength(e, t, l)
{
if(t.value.length>=l)
{
custom_alert_OK_only('You have reached to the maximum length of this field');
t.value = t.value.substr(0,l);
}
}

function onlyAlphabets(e, t)
{
try {
if (window.event) {
var charCode = window.event.keyCode;
}
else if (e) {
var charCode = e.which;
}
else { return true; }
if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
return true;
else
return false;
}
catch (err) {
alert(err.Description);
}
}

$(function() {
$( "#panel_signature" ).dialog({
autoOpen: false,
modal: true,
draggable: false,

width:
700,
height:
400,
buttons: {
'Add': function() {

if(user==1)
{
$("#assessor_signature").attr('src',$('.sigboxselected').children('img')[0].src);
$("#signature_assessor_font").val($('.sigboxselected').children('img')[0].src);
}
else if(user==2)
{
$("#learner_signature").attr('src',$('.sigboxselected').children('img')[0].src);
$("#signature_learner_font").val($('.sigboxselected').children('img')[0].src);
}
else if(user==3)
{
$("#employer_signature").attr('src',$('.sigboxselected').children('img')[0].src);
$("#signature_employer_font").val($('.sigboxselected').children('img')[0].src);
}

$(this).dialog('close');
},
'Cancel': function() {$(this).dialog('close');}
}
});
});

function refreshSignature()
{
for(i=1; i<=16; i++)
$("#img"+i).attr('src','/img/loading-image.gif');

for(i=0; i<=15; i++)
$("#img"+(i+1)).attr('src','do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
}


</script>


</head>
<body id="candidates">
<div class="banner">
    <div class="Title">Learner On Programme Review Level 4 Software Developer V3</div>
    <div class="ButtonBar">
        <?php //if($source==2 or $source==3)
        if(isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER)
            if($form_arf->signature_employer_font=='' || $form_arf->signature_learner_font=='')
                echo '<button onclick="save();">Save</button>';
            else
                echo '<button onclick="window.location.href=' . $_SESSION['bc']->getPrevious() .'">Close</button>';

        if(isset($_SESSION['user']) && ($_SESSION['user']->isAdmin() || $_SESSION['user']->username=="creay123") && $form_arf->signature_assessor_font==''){?>
            <button onclick="deleteRecord();" style="color:red">Delete</button>
            <?php } ?>
    </div>
    <div class="ActionIconBar"></div>
</div>

<form name="form1" id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="tr_id" value="<?php echo $tr_id ?>" />
<input type="hidden" name="_action" value="save_arf_introduction" />
<input type="hidden" name="review_id" value="<?php echo $review_id; ?>" />
<input type="hidden" name="source" value="<?php echo $source; ?>" />
<input type="hidden" name="signature_learner_font" id="signature_learner_font" value="<?php echo $form_arf->signature_learner_font; ?>" />
<input type="hidden" name="signature_assessor_font" id="signature_assessor_font" value="<?php echo $form_arf->signature_assessor_font; ?>" />
<input type="hidden" name="signature_employer_font" id="signature_employer_font" value="<?php echo $form_arf->signature_employer_font; ?>" />
<input type="hidden" name="autosave" id="autosave" value="1" />

<?php if($source==3) {
    echo '<table style="width: 900px">';
    echo     '<tr>';
    echo         '<td>';
    echo            '<table class="table1">';
    echo                '<thead>';
    echo                '<th style="width: 800px">&nbsp;&nbsp;&nbsp;Please take 30 minutes of your time to fully complete, sign, date and save this form.</th>';
    echo                '</thead>';
    echo            '</table>';
    echo        '</td>';
    echo    '</tr>';
    echo '</table>';
    echo '<br>';
} ?>

<table style="width: 900px">
    <tr>
        <td>
            <table class="table1">
                <thead>
                <th style="width: 800px">&nbsp;&nbsp;&nbsp;Learner On Programme Review Level 4 Software Developer V3</th>
                </thead>
            </table>
        </td>
        <td>
            <?php   if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
            echo '<img height = "44" width = "172" src="/images/logos/baltic-navy.png">';
        else
            echo '<img height = "44" width = "172" src="images/sunesislogo.gif">';
            ?>
        </td>
    </tr>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Details</th>
    </thead>
    <tbody>
    <tr>
        <td>Learner:</td>
        <td><input type="text" name="learner_name" value="<?php echo $form_arf->learner_name; ?>" size=30/></td>
        <td>Reviewer/ Assessor:</td>
        <td><input type="text" name="learner_assessor" value="<?php echo $form_arf->learner_assessor; ?>" size=30/></td>
    </tr>
    <tr>
        <td>Employer Name:</td>
        <td><input type="text" name="learner_employer" value="<?php echo $form_arf->learner_employer; ?>" size=30/></td>
        <td>Line Manager/ Supervisor Name:</td>
        <td><input type="text" name="learner_manager" value="<?php echo $form_arf->learner_manager; ?>" size=30/></td>
    </tr>
    <tr>
        <td>Programme:</td>
        <td><input type="text" name="learner_programme" value="<?php echo $form_arf->learner_programme; ?>" size=30/></td>
        <td>Programme Start Date:</td>
        <td> <?php echo HTML::datebox("start_date", $form_arf->start_date, true, false); ?> </td>
    </tr>
    <tr>
        <td>Expected Completion Date:</td>
        <td> <?php echo HTML::datebox("planned_end_date", $form_arf->planned_end_date, true, false); ?> </td>
        <td>Actual Review Date:</td>
        <td>
            <?php   if($source==1)
            echo HTML::datebox("review_date", $form_arf->review_date, true, false);
        else
            echo $form_arf->review_date;
            ?>
            <?php  ?>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Programme Progress</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Percentage Progress</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="text-align: center">Assessment Plans</td>
                    <td style="text-align: center">Knowledge - Training</td>
                    <td style="text-align: center">Knowledge - Exams</td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        <?php echo $assessment_percentage;?>%
                    </td>
                    <td style="text-align: center"><i>
                        <?php echo $technical_percentage; ?>
                    </i></td>
                    <td style="text-align: center"><i>
                        <?php echo $exam_percentage; ?>
                    </i></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Review of previous SMART targets</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=3>SMART</td><td style="text-align: center;">Met (Y/N)</td>
    </tr>
    <tr>
        <td colspan=3><i>
            <?php echo isset($previous_review->specific)?$previous_review->specific:'';?>
        </i></td>
        <?php   if($source==1 && $form_arf->signature_assessor_font=='') { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart1_achieved=="on")?" checked ":""; echo "<input name = \"smart1_achieved\" type = checkbox $checked>"; ?></td>
        <?php } else { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart1_achieved=="on")?" checked ":""; echo "<input disabled=\"disabled\" name = \"smart1_achieved\" type = checkbox $checked>"; echo "<input type=\"hidden\" name = \"smart1_achieved\" value=\"on\">";?></td>
        <?php } ?>
    </tr>
    <tr>
        <td colspan=3><i>
            <?php echo isset($previous_review->measurable)?$previous_review->measurable:'';?>
        </i></td>
        <?php   if($source==1 && $form_arf->signature_assessor_font=='') { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart2_achieved=="on")?" checked ":""; echo "<input name = \"smart2_achieved\" type = checkbox $checked>"; ?></td>
        <?php } else { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart2_achieved=="on")?" checked ":""; echo "<input disabled=\"disabled\" name = \"smart2_achieved\" type = checkbox $checked>"; echo "<input type=\"hidden\" name = \"smart2_achieved\" value=\"on\">";?></td>
        <?php } ?>
    </tr>
    <tr>
        <td colspan=3><i>
            <?php echo isset($previous_review->achievable)?$previous_review->achievable:''; ?>
        </i></td>
        <?php   if($source==1 && $form_arf->signature_assessor_font=='') { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart3_achieved=="on")?" checked ":""; echo "<input name = \"smart3_achieved\" type = checkbox $checked>"; ?></td>
        <?php } else { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart3_achieved=="on")?" checked ":""; echo "<input disabled=\"disabled\" name = \"smart3_achieved\" type = checkbox $checked>"; echo "<input type=\"hidden\" name = \"smart3_achieved\" value=\"on\">";?></td>
        <?php } ?>
    </tr>
    <tr>
        <td colspan=3><i>
            <?php echo isset($previous_review->timebound)?$previous_review->timebound:''; ?>
        </i></td>
        <?php   if($source==1 && $form_arf->signature_assessor_font=='') { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart4_achieved=="on")?" checked ":""; echo "<input name = \"smart4_achieved\" type = checkbox $checked>"; ?></td>
        <?php } else { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart4_achieved=="on")?" checked ":""; echo "<input disabled=\"disabled\" name = \"smart4_achieved\" type = checkbox $checked>"; echo "<input type=\"hidden\" name = \"smart4_achieved\" value=\"on\">";?></td>
        <?php } ?>
    </tr>
    <tr>
        <td colspan=3><i>
            <?php echo isset($previous_review->smart_line5)?$previous_review->smart_line5:''; ?>
        </i></td>
        <?php   if($source==1 && $form_arf->signature_assessor_font=='') { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart5_achieved=="on")?" checked ":""; echo "<input name = \"smart5_achieved\" type = checkbox $checked>"; ?></td>
        <?php } else { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->smart5_achieved=="on")?" checked ":""; echo "<input disabled=\"disabled\" name = \"smart5_achieved\" type = checkbox $checked>"; echo "<input type=\"hidden\" name = \"smart5_achieved\" value=\"on\">";?></td>
        <?php } ?>
    </tr>
    </tbody>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Personal Development Progress</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>Review Employer Comments</b></td>
    </tr>
    <tr>
        <td colspan=2>Did the employer attend the review session?</td>
        <?php $issues = Array(Array('1','Yes'),Array('2','No')); echo "<td colspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("manager_attendance", $issues, $form_arf->manager_attendance, true, true) . "</td>";?>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="introduction" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">' . $form_arf->introduction . '</textarea>';
        else
            echo $form_arf->introduction;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Workplace progress and significant achievement over past 12 weeks</b></td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="skill_scan" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_arf->skill_scan.'</textarea>';
        else
            echo $form_arf->skill_scan;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Apprenticeship Commitment / Future Planning / Goal Setting / EPA Readiness  Check</b></td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="progress_review" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->progress_review.'</textarea>';
        else
            echo $form_arf->progress_review;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Personal Development Topics</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>E-safety & Digital Resilience<br>Discuss progress and topics that have been set and learnt </td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" name="technical_training" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->technical_training.'</textarea>';
        else
            echo $form_arf->technical_training;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Health & Wellbeing / Prevent / British Values / Citizenship<br>Discuss progress and topics that have been set and learnt</b></td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="assessment" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->assessment.'</textarea>';
        else
            echo $form_arf->assessment;
            ?>
        </i></td>
    </tr>
    </tbody></table><br>


<table class="table1" style="width: 900px">
<thead>
<th colspan=4>&nbsp;&nbsp;&nbsp;Learner Concerns</th>
</thead>
<tbody>
<tr>
    <td colspan=4><b>Welfare Check-In</b></td>
</tr>
<tr>
    <td colspan=4><i>
        <?php   if($source==1)
        echo '<textarea name="end_point_assessment" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="15" cols="123">'.$form_arf->end_point_assessment.'</textarea>';
    else
        echo $form_arf->end_point_assessment;
        ?>
    </i></td>
</tr>

<tr>
    <td colspan=4><b>Additional Support Requirements</b></td>
</tr>
<tr>
    <td colspan=4><i>
        <?php   if($source==1)
        echo '<textarea name="skilsure2" onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" style="font-family:sans-serif; font-size:10pt"  rows="20" cols="123">'. $form_arf->skilsure2 . '</textarea>';
    else
        echo $form_arf->skilsure2;
        ?>
    </i></td>
</tr>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th>
    </thead>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=3>Communication</td>
        <td>Work both independently and as part of a team and follow your organisations standards</td>
        <?php echo "<td rowspan=3 style='text-align:center; vertical-align:middle'>" . $ss_result['Communication'] . "</td>"; ?>
        <?php echo "<td rowspan=3 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>"; ?>
        <?php echo "<td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";?>
        <?php $communication = isset($Assessment_Plan['Communication'])?$Assessment_Plan['Communication']:"&nbsp"; echo "<td rowspan=3 style='text-align:center; vertical-align:middle'>" . $communication . "</td>";?>
    </tr>
    <tr><td>Able to communicate both in writing and orally at all levels</td></tr>
    <tr><td>Use a range of tools and demonstrate strong interpersonal skills and cultural awareness when dealing with colleagues, customers and clients during all tasks.</td>
    </tr>

    <tr>
        <td>IT Security</td>
        <td>Securely operate across all platforms and areas of responsibilities in line with organisations guidance and legislation</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['IT Security'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";?>
        <?php $it_security = isset($Assessment_Plan['IT Security'])?$Assessment_Plan['IT Security']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>";?>
    </tr>

    <tr>
        <td>Remote Infrastructure</td>
        <td>Operate a range of mobile devices and securely add them to a network in accordance with organisations policies and procedures</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['Remote Infrastructure'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";?>
        <?php $remote_infrastructure = isset($Assessment_Plan['Remote Infrastructure'])?$Assessment_Plan['Remote Infrastructure']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>";?>
    </tr>

    <tr>
        <td>Data</td>
        <td>Record, analyse and communicate data at the appropriate level using the organisation's standard tools and processes, to all stakeholders within the responsibility of the position</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['Data'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";?>
        <?php $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $data . "</td>";?>
    </tr>

    <tr>
        <td rowspan=2>Problem Solving</td>
        <td>Apply structured techniques to common and non-routine problems, testing methodologies</td>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $ss_result['Problem Solving'] . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";?>
        <?php $problem_solving = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"&nbsp"; echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>";?>
    </tr>
    <tr><td>Troubleshoot and analyse problems by selecting the digital appropriate tools and techniques in line with organisation guidance and to obtain the relevant logistical support as required</td></tr>
    </tr>

    <tr>
        <td>Workflow Management</td>
        <td>Work flexibly and have the ability to work under pressure to progress allocated tasks in accordance with the organisation’s reporting and quality systems</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['Workflow Management'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";?>
        <?php $workflow_management = isset($Assessment_Plan['Workflow management'])?$Assessment_Plan['Workflow management']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>";?>
    </tr>

    <tr>
        <td>Health and Safety</td>
        <td>Interpret and follow IT legislation to securely and professional work productively</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['Health and Safety'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";?>
        <?php $health_safety = isset($Assessment_Plan['Health and Safety'])?$Assessment_Plan['Health and Safety']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>";?>
    </tr>

    <tr>
        <td rowspan=2>Performance</td>
        <td>Optimise the performance of hardware, software and Network Systems and services in line with business requirements</td>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $ss_result['Performance'] . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";?>
        <?php $performance = isset($Assessment_Plan['Performance'])?$Assessment_Plan['Performance']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>";?>
    </tr>
    <tr><td>Explain the correct processes associated with WEEE (the Waste Electrical and Electronic Equipment Directive)</td>
        <?php $weee = isset($Assessment_Plan['WEEE'])?$Assessment_Plan['WEEE']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $weee . "</td>";?>
    </tr>
</table>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th>
    </thead>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
    <tr>
        <td>A range of cabling and connectivity, the various types of antennas and wireless systems and IT test equipment (Networking MTA)</td>
        <?php echo "<td rowspan=5 style='text-align:center; vertical-align:middle'>" . $tk_result['9628-06 Networking and Architecture Test'] . "</td>"; ?>
        <?php echo "<td rowspan=5 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>"; ?>
        <?php echo "<td rowspan=5 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr><td>Maintenance  processes and applying them in working practices (Networking MTA)</td></tr>
    <tr><td>Applying the basic elements and architecture of computer systems (Networking MTA)</td>
    <tr><td>Where to apply the relevant numerical skills e.g. Binary (Networking MTA)</td>
    <tr><td>Networking skills necessary to maintain a secure network (Networking MTA)</td>
    </tr>

    <tr>
        <td>Similarities, differences and benefits of the current Operating Systems available (Mobility & Devices MTA)</td>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk_result['Mobility and Devices MTA Test'] . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr><td>How to operate remotely and how to deploy and securely integrate mobile devices (Mobility & Devices MTA)</td></tr>
    </tr>

    <tr>
        <td>Similarities and differences between a range of coding and logic (Coding & Logic)</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['9268-09 Coding and Logic Test'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";?>
    </tr>

    <tr>
        <td>Business processes (Business Processes)</td>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk_result['9628-10 ITIL Foundation Test'] . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr><td>Business IT skills relevant to the organization (Business Processes)</td></tr>
    </tr>
</table>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th>
    </thead>
    <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td></tr>

    <tr>
        <td>Logical and creative thinking skills</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="example1" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="100">' . $form_arf->example1 . '</textarea>';
        else
            echo $form_arf->example1;
            ?>
        </i></td>
    </tr>
    <tr>
        <td>Analytical and problem solving skills</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="example2" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="100">' . $form_arf->example2 . '</textarea>';
        else
            echo $form_arf->example2;
            ?>
        </i></td>
    </tr>

    <tr>
        <td>Ability to work independently and to take responsibility</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="example3" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="100">' . $form_arf->example3 . '</textarea>';
        else
            echo $form_arf->example3;
            ?>
        </i></td>
    </tr>

    <tr>
        <td>Can use own initiative</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="example4" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="100">' . $form_arf->example4 . '</textarea>';
        else
            echo $form_arf->example4;
            ?>
        </i></td>
    </tr>

    <tr>
        <td>A thorough and organised approach</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="example5" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="100">' . $form_arf->example5 . '</textarea>';
        else
            echo $form_arf->example5;
            ?>
        </i></td>
    </tr>

    <tr>
        <td>Ability to work with a range of internal and external people</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="example6" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="100">' . $form_arf->example6 . '</textarea>';
        else
            echo $form_arf->example6;
            ?>
        </i></td>
    </tr>

    <tr>
        <td>Ability to communicate effectively in a variety of situations</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="example7" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="100">' . $form_arf->example7 . '</textarea>';
        else
            echo $form_arf->example7;
            ?>
        </i></td>
    </tr>

    <tr>
        <td>Maintain productive, professional and secure working environment</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment'] . "</td>"; ?>
        <?php echo "<td style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>";?>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="example8" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="100">' . $form_arf->example8 . '</textarea>';
        else
            echo $form_arf->example8;
            ?>
        </i></td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Skills Scan</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>What new skills, knowledge and behaviors have been developed since last review & since starting  point</b></td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="setting_work" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->setting_work.'</textarea>';
        else
            echo $form_arf->setting_work;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary - Projects</th>
    </thead>
    <tr><td colspan=2>Project</td><td>Status</td></tr>

    <?php
    $projects = DAO::getResultset($link, "SELECT
                        tr_projects.id
                        ,evidence_project.project
                        ,(SELECT (LENGTH(matrix)-LENGTH(REPLACE(matrix,\",\",\"\"))+1) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_submissions.project_id = tr_projects.id ORDER BY project_submissions.id DESC LIMIT 1) AS matrix
                        ,(SELECT COUNT(*) FROM evidence_criteria WHERE evidence_criteria.course_id = evidence_project.course_id) AS total
                        FROM
                        tr_projects
                        INNER JOIN evidence_project ON tr_projects.project = evidence_project.id
                        WHERE tr_id = '$tr_id';
                        ", DAO::FETCH_ASSOC);


    $total = 0;
    foreach($projects AS $project)
    {
        $matrix = ($project['matrix']=='')?'0':$project['matrix'];
        $total+=(int)$matrix;
        echo '<tr><td colspan=2>' . $project['project'] . '</td><td align=center>' . $matrix . ' / ' . $project['total'] . '</td></tr>';
    }

    if(isset($project['total']))
    {
        echo '<tr><td colspan=2 style="background-color: lightgreen">Total</td><td align=center style="background-color: lightgreen">' . $total . ' / ' . $project['total'] . '</td></tr>';
        $per = round($total/$project['total']*100);
    }
    else
    {
        echo '<tr><td colspan=2 style="background-color: lightgreen">Total</td><td align=center style="background-color: lightgreen">' . $total . ' / 0' . '</td></tr>';
        $per = round($total/1*100);
    }

    echo '<tr><td colspan=2 style="background-color: lightblue">Evidence % </td><td align=center style="background-color: lightblue">' . $per . '%</td></tr>';
    ?>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary - Competence</th>
    </thead>
    <?php
    $projects = DAO::getResultset($link, "SELECT lookup_assessment_plan_log_mode.id
,description
,(SELECT COUNT(*) FROM evidence_criteria WHERE course_id = '$course_id' AND competency = lookup_assessment_plan_log_mode.id) AS total_criteria
,(SELECT COUNT(*) FROM evidence_criteria WHERE competency = lookup_assessment_plan_log_mode.id AND FIND_IN_SET(evidence_criteria.id,(SELECT GROUP_CONCAT(REPLACE(matrix,\" \",\"\")) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id')))<>0) AS matrix
,(SELECT GROUP_CONCAT(criteria) FROM evidence_criteria WHERE competency = lookup_assessment_plan_log_mode.id AND FIND_IN_SET(evidence_criteria.id,(SELECT GROUP_CONCAT(REPLACE(matrix,\" \",\"\")) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id')))<>0) AS completed
FROM
lookup_assessment_plan_log_mode
INNER JOIN student_frameworks ON student_frameworks.id = lookup_assessment_plan_log_mode.framework_id AND student_frameworks.tr_id = '$tr_id';
;
                        ", DAO::FETCH_ASSOC);


    echo '<tr><td>Competency</td><td>Completed Criteria</td><td>Status</td></tr>';
    $total = 0;
    $gt=0;
    foreach($projects AS $project)
    {
        $matrix = ($project['matrix']=='')?'0':$project['matrix'];
        $total+=(int)$matrix;
        $gt+=$project['total_criteria'];
        echo '<tr><td>' . $project['description'] . '</td>';
        echo '<td>'. str_replace(",","<br>",$project['completed']) .'</td>';
        echo '<td align=center>' . $matrix . ' / ' . $project['total_criteria'] . '</td></tr>';
    }

    if(isset($project['total_criteria']))
    {
        echo '<tr><td style="background-color: lightgreen">Total</td><td style="background-color: lightgreen">&nbsp</td><td align=center style="background-color: lightgreen">' . $total . ' / ' . $gt . '</td></tr>';
        $per = round($total/$gt*100);
    }
    else
    {
        echo '<tr><td style="background-color: lightgreen">Total</td><td style="background-color: lightgreen">&nbsp</td><td align=center style="background-color: lightgreen">' . $total . ' / 0' . '</td></tr>';
        $per = round($total/1*100);
    }
    ?>

    <tr>
        <td colspan=6>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
    </tr>
    <tr>
        <td colspan=6><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="workplace_competence" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->workplace_competence.'</textarea>';
        else
            echo $form_arf->workplace_competence;
            ?>
        </i></td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
    </thead>
    <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
    <tr>
        <td>Mobility and devices MTA</td>
        <td><?php echo $this->getEventStatus($events, "Mobility and Devices MTA") . "<br>" . $this->getEventDate($events,'Mobility and Devices MTA'); ?></td>
        <td>Mobility and devices MTA test</td>
        <td><?php echo $this->getEventStatus($events, "Mobility and Devices MTA Test") . "<br>" . $this->getEventDate($events,'Mobility and Devices MTA Test'); ?></td>
        <td>Networking and Architecture</td>
        <td><?php echo $this->getEventStatus($events, "Networking and Architecture") . "<br>" . $this->getEventDate($events,'Networking and Architecture'); ?></td>
    </tr>
    <tr>
        <td>9628-06 Networking and Architecture Test</td>
        <td><?php echo $this->getEventStatus($events, "9628-06 Networking and Architecture Test") . "<br>" . $this->getEventDate($events,'9628-06 Networking and Architecture Test'); ?></td>
        <td>Business Processes</td>
        <td><?php echo $this->getEventStatus($events, "Business Processes") . "<br>" . $this->getEventDate($events,'Business Processes'); ?></td>
        <td>City & Guilds 9628-10 Level 3 Award in Business Processes Test</td>
        <td><?php echo $this->getEventStatus($events, "City and Guilds 9628-10 Level 3 Award in Business Processes Test") . "<br>" . $this->getEventDate($events,'City and Guilds 9628-10 Level 3 Award in Business Processes Test'); ?></td>
    </tr>
    <tr>
        <td>Coding and logic</td>
        <td><?php echo $this->getEventStatus($events, "Coding and Logic") . "<br>" . $this->getEventDate($events,'Coding and Logic'); ?></td>
        <td>9628-09 coding and logic test</td>
        <td><?php echo $this->getEventStatus($events, "9628-09 Coding and Logic Test") . "<br>" . $this->getEventDate($events,'9628-09 Coding and Logic Test'); ?></td>
        <td>Windows Server Fundamentals MTA</td>
        <td><?php echo $this->getEventStatus($events, "Windows Server Fundamentals MTA") . "<br>" . $this->getEventDate($events,'Windows Server Fundamentals MTA'); ?></td>
    </tr>
    <tr>
        <td>Windows Server Fundamentals MTA Test</td>
        <td><?php echo $this->getEventStatus($events, "Windows Server Fundamentals MTA Test") . "<br>" . $this->getEventDate($events,'Windows Server Fundamentals MTA Test'); ?></td>
        <td>Functional Skills English</td>
        <td><?php echo $this->getEventStatus($events, "Functional Skills English") . "<br>" . $this->getEventDate($events,'Functional Skills English');?></td>
        <td>Functional Skills Mathematics</td>
        <td><?php echo $this->getEventStatus($events, "Functional Skills Mathematics") . "<br>" . $this->getEventDate($events,'Functional Skills Mathematics');?></td>
    </tr>
    <tr>
        <td>Functional Skills Mathematics Test</td>
        <td><?php echo $this->getEventStatus($events, "Functional Skills Mathematics Test") . "<br>" . $this->getEventDate($events,'Functional Skills Mathematics Test');?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=6>Record here the detail of the progress. What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=6><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="knowledge_modules" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->knowledge_modules.'</textarea>';
        else
            echo $form_arf->knowledge_modules;
            ?>
        </i></td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4><b>Functional Skills Progress</b></th>
    </thead>
    <tr><td colspan=3>Functional Skills exemptions:</td></tr>
    <tr><td>Maths <?php $checked = ($math_exempt==1)?"checked":""; echo "<input type = checkbox $checked>"; ?></td><td>English <?php $checked = ($english_exempt==1)?"checked":""; echo "<input type = checkbox $checked>"; ?></td><td>ICT <?php $checked = ($ict_exempt==1)?"checked":""; echo "<input type = checkbox $checked>"; ?></td></tr>
    <tr>
        <td colspan=6></td>
    </tr>
    <tr>
        <td colspan=6><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="functional_skills_progress" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->functional_skills_progress.'</textarea>';
        else
            echo $form_arf->functional_skills_progress;
            ?>
        </i></td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=6>&nbsp;&nbsp;&nbsp;Off the job training in the workplace</th>
    </thead>
    <tr><td colspan=6>Record training and learning that has taken place</td></tr>
    <tr>
        <td colspan=6><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="functional_skills_progress2" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->functional_skills_progress2.'</textarea>';
        else
            echo $form_arf->functional_skills_progress2;
            ?>
        </i></td>
    </tr>
    <tr>
        <td>Hours Currently</td><td><input type="text" name="hours_currently" value="<?php echo $form_arf->hours_currently; ?>" size=10/></td>
        <td>On-track/ Behind</td><td><?php echo HTML::selectChosen('ontrack_behind', $attempts, $form_arf->ontrack_behind, true); ?></td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Actions Required for next contact</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>(SMART - Exactly what you will do, how you will know it is complete, how is it realistic for you to achieve, when will you achieve it by?)</td>
    </tr>
    <tr>
        <td style="text-align: center">Specific</td>
        <td style="text-align: center">Measurable</td>
        <td style="text-align: center">Achievable & Realistic</td>
        <td style="text-align: center">Timebound</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td><i>
                        <?php   if(true)
                        echo '<textarea name="specific" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="7" cols="120">'.$form_arf->specific.'</textarea>';
                    else
                        echo $form_arf->specific;
                        ?>
                    </i></td>
                </tr>
                <tr>
                    <td><i>
                        <?php   if(true)
                        echo '<textarea name="measurable" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="7" cols="120">'.$form_arf->measurable.'</textarea>';
                    else
                        echo $form_arf->measurable;
                        ?>
                    </i></td>
                </tr>
                <tr>
                    <td><i>
                        <?php   if(true)
                        echo '<textarea name="achievable" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="7" cols="120">'.$form_arf->achievable.'</textarea>';
                    else
                        echo $form_arf->achievable;
                        ?>
                    </i></td>
                </tr>
                <tr>
                    <td><i>
                        <?php   if(true)
                        echo '<textarea name="timebound" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="7" cols="120">'.$form_arf->timebound.'</textarea>';
                    else
                        echo $form_arf->timebound;
                        ?>
                    </i></td>
                </tr>
                <tr>
                    <td><i>
                        <?php   if(true)
                        echo '<textarea name="smart_line5" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="7" cols="120">'.$form_arf->smart_line5.'</textarea>';
                    else
                        echo $form_arf->smart_line5;
                        ?>
                    </i></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Any other business</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><ul><li>Any other business</li><li>Learner comments</li><li>Learning mentor comments</li><li>Documenting learner development</li></ul></td>
    </tr>
    <tr>
        <?php   if($source==true)
        echo '<td colspan=4><textarea id="learner_comment" name="learner_comment" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">' . $form_arf->learner_comment . '</textarea></td>';
    else
        echo '<td>' . $form_arf->learner_comment . '</td>';
        ?>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th>&nbsp;&nbsp;&nbsp;Completed SMART CLASSROOM Link:</th>
    <th style="text-align: center">
        <?php   if($source==1)
        echo '<td><input type="text" name="adobe" value="' . $form_arf->adobe . '" size=60/></td>';
    else
        echo $form_arf->adobe;
        ?>
    </th>
    </thead>
</table>
<br>

<table class="table1" style="width: 900px">
    <tr>
        <td colspan=4>&nbsp;&nbsp;&nbsp;Date of Next Review: </td>
        <td style="text-align: center">
            <?php   if($source==1)
            echo HTML::datebox("next_contact", $form_arf->next_contact, true, false);
        else
            echo $form_arf->next_contact;
            ?>
        </td>
        <td>&nbsp;&nbsp;&nbsp;Hours: </td>
        <td style="text-align: center">
            <?php   if($source==1)
            echo '<input type="text" name="hours" value="' . $form_arf->hours . '" size=5/>';
        else
            echo $form_arf->hours;
            ?>
        </td>
        <td>&nbsp;&nbsp;&nbsp;Minutes: </td>
        <td style="text-align: center">
            <?php   if($source==1)
            echo '<input type="text" name="minutes" value="' . $form_arf->minutes . '" size=5/>';
        else
            echo $form_arf->minutes;
            ?>
        </td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <tr>
        <td colspan=4>&nbsp;&nbsp;&nbsp;Date of Next Support: </td>
        <td style="text-align: center">
            <?php   if($source==1)
            echo HTML::datebox("next_support", $form_arf->next_support, true, false);
        else
            echo $form_arf->next_contact;
            ?>
        </td>
        <td>&nbsp;&nbsp;&nbsp;Hours: </td>
        <td style="text-align: center">
            <?php   if($source==1)
            echo '<input type="text" name="support_hours" value="' . $form_arf->support_hours . '" size=5/>';
        else
            echo $form_arf->support_hours;
            ?>
        </td>
        <td>&nbsp;&nbsp;&nbsp;Minutes: </td>
        <td style="text-align: center">
            <?php   if($source==1)
            echo '<input type="text" name="support_minutes" value="' . $form_arf->support_minutes . '" size=5/>';
        else
            echo $form_arf->support_minutes;
            ?>
        </td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Progress Review</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Please complete the following section to review your apprentice's progress in their training programme and at work.<br><br>In your opinion how is your apprentice progressing within their apprenticeship?</td>
    </tr>
    <tr>
        <td colspan=4><textarea tabindex="-1" id="employer_progress_review" name="employer_progress_review" onblur="checkLength(event,this,10000)" onkeypress="checkLength(event,this,5000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form_arf->employer_progress_review; ?></textarea></td>
    </tr>
    <tr>
        <td colspan=2>Are there any performance issues?</td>
        <?php $issues = Array(Array('1','Yes'),Array('2','No')); echo "<td colspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("performance_issues", $issues, $form_arf->performance_issues, true, false) . "</td>";?>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3 or $source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="emp_logical_creative" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="123">' . $form_arf->emp_logical_creative . '</textarea>';
        else
            echo $form_arf->emp_logical_creative;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th>&nbsp;</th>
    <th>Signature</th>
    <th>Name</th>
    <th>Date</th>
    </thead>
    <tbody>
    <tr>
        <td>Learner</td>
        <?php
        if($form_arf->signature_learner_font!='')
            echo '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = ' . str_replace(" ","%20",$form_arf->signature_learner_font) .  ' height="49" width="285"/></div></td>';
        else
            echo '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input id="signature_learner_name" name="signature_learner_name" type="text" size=30 value="<?php echo $form_arf->signature_learner_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_learner_date", $form_arf->signature_learner_date, false, false); ?> </td>
    </tr>
    <tr>
        <td>Reviewer</td>
        <?php   if($form_arf->signature_assessor_font!='')
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = ' . str_replace(" ","%20",$form_arf->signature_assessor_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input name="signature_assessor_name" type="text" size=30 value="<?php echo $form_arf->signature_assessor_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_assessor_date", $form_arf->signature_assessor_date, false, false); ?> </td>
    </tr>
    <tr>
        <td>Supervisor/ Company Contact:</td>
        <?php   if($form_arf->signature_employer_font!='')
        echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = ' . str_replace(" ","%20",$form_arf->signature_employer_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input name="signature_employer_name" type="text" size=30 value="<?php echo $form_arf->signature_employer_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_employer_date", $form_arf->signature_employer_date, false, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>
</form>
<?php if( (isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER) || (!isset($_SESSION['user']))){
    if($form_arf->signature_employer_font=='' || $form_arf->signature_learner_font=='') { ?>
    <button onclick="save();">Save</button>
        <?php }} ?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


<div id="panel_signature" title="Signature">
    <div style=" position: absolute; top: 10%;">
        <table><tr><td>Enter Your Name</td></table><input type = "text" id = "signature_text" onkeyup="refreshSignature()" onkeypress="return onlyAlphabets(event,this);"/></td></tr></table>
        <br><br>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig1">
            <img id = "img1" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig2">
            <img id = "img2" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig3">
            <img id = "img3" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig4">
            <img id = "img4" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig5">
            <img id = "img5" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig6">
            <img id = "img6" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig7">
            <img id = "img7" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig8">
            <img id = "img8" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig9">
            <img id = "img9" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig10">
            <img id = "img10" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig11">
            <img id = "img11" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig12">
            <img id = "img12" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig13">
            <img id = "img13" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig14">
            <img id = "img14" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig15">
            <img id = "img15" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig16">
            <img id = "img16" src = "" height="49" width="285"/>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
    timePicker(900); // input parameter is in number of seconds
    });
    var s;
    function timePicker(vr)
    {
    // function for count down timer...
    if (vr > 0)
    {
    vr--;
    s = setTimeout('timePicker(' + vr + ')', 1000);
    }
    else
    {
    clearInterval(s);
    // post data after 10 seconds....
    saveDialogue();
    s = setTimeout('timePicker(' + 300 + ')', 5000);
    }
    }


    function custom_alert_OK_only(output_msg, title_msg)
    {
    if (!title_msg)
    title_msg = 'Alert';

    if (!output_msg)
    output_msg = 'No Message to Display.';

    $("<div></div>").html(output_msg).dialog({
    title: title_msg,
    resizable: false,
    modal: true,
    buttons: {
    "OK": function()
    {
    $( this ).dialog( "close" );
    }
    }
    });
    }

    function confirmation(question) {
    var defer = $.Deferred();
    $('<div></div>')
    .html(question)
    .dialog({
    autoOpen: true,
    modal: true,
    title: 'Confirmation',
    buttons: {
    "Save": function () {
    defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
    $(this).dialog("close");
    }
    },
    open: function(event, ui) {
    $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
    },
    close: function () {
    //$(this).remove();
    $(this).dialog('destroy').remove()
    }
    });
    return defer.promise();
    }

    function confirmation2(question) {
    var defer = $.Deferred();
    $('<div></div>')
    .html(question)
    .dialog({
    autoOpen: true,
    modal: true,
    title: 'Confirmation',
    buttons: {
    "Ok": function () {
    defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
    $(this).dialog("close");
    },
    "Cancel": function () {
    defer.resolve("false");//this text 'true' can be anything. But for this usage, it should be true or false.
    $(this).dialog("close");
    }
    },
    open: function(event, ui) {
    $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
    },
    close: function () {
    //$(this).remove();
    $(this).dialog('destroy').remove()
    }
    });
    return defer.promise();
    }

</script>

</body>
</html>