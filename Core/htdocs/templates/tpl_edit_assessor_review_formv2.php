<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Assessor Review Form</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

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
        color:#fff;
        text-shadow:1px 1px 1px #568F23;
        text-align: left;
        /*border:1px solid #93CE37;*/
        /*border-bottom:3px solid #9ED929;*/
        background-color:#00539F;
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
        $('textarea[name="employer_progress_review"]').attr('class','disabled');
        $('input[name="attendance"]').attr('class','disabled');
        $('input[name="punctuality"]').attr('class','disabled');
        $('input[name="attitude"]').attr('class','disabled');
        $('input[name="communication"]').attr('class','disabled');
        $('input[name="enthusiasm"]').attr('class','disabled');
        $('input[name="commitment2"]').attr('class','disabled');
        $('textarea[name="behaviours"]').attr('class','disabled');
        $('textarea[name="ability"]').attr('class','disabled');
        $('textarea[name="skills_knowledge"]').attr('class','disabled');
        $('textarea[name="achievements"]').attr('class','disabled');
    }
    if(source=='2')
    {
        $('input[name="learner_name"]').attr('class','disabled');
        $('input[name="learner_dob"]').attr('class','disabled');
        $('input[name="learner_assessor"]').attr('class','disabled');
        $('input[name="learner_ni"]').attr('class','disabled');
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
        $('input[name="commitment2"]').attr('class','disabled');
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
    }
    if(source=='3')
    {
        $('input[name="learner_name"]').attr('class','disabled');
        $('input[name="learner_dob"]').attr('class','disabled');
        $('input[name="learner_assessor"]').attr('class','disabled');
        $('input[name="learner_ni"]').attr('class','disabled');
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
    }

});

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

        behaviours = $('textarea#behaviours').val();
        ability = $('textarea#ability').val();
        skills_knowledge = $('textarea#skills_knowledge').val();
        achievements = $('textarea#achievements').val();

        if(learner_comments=="" || achievements=="" || skills_knowledge=="" || ability=="" || behaviours=="")
        {
            custom_alert_OK_only('Please complete mandatory information before signing this form');
            return 1;
        }

        attendance = $('input[name="attendance"]:checked').val();
        punctuality = $('input[name="punctuality"]:checked').val();
        attitude = $('input[name="attitude"]:checked').val();
        communication = $('input[name="communication"]:checked').val();
        enthusiasm = $('input[name="enthusiasm"]:checked').val();
        commitment = $('input[name="commitment2"]:checked').val();

        if((attendance!=1 && attendance!=2 && attendance!=3 && attendance!=4) || (punctuality!=1 && punctuality!=2 && punctuality!=3 && punctuality!=4) || (attitude!=1 && attitude!=2 && attitude!=3 && attitude!=4) || (communication!=1 && communication!=2 && communication!=3 && communication!=4) || (enthusiasm!=1 && enthusiasm!=2 && enthusiasm!=3 && enthusiasm!=4) || (commitment!=1 && commitment!=2 && commitment!=3 && commitment!=4))
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
        learner_signature = $("#signature_employer_font").val();
        signature_learner_name = $('input[name="signature_employer_name"]').val();

        behaviours = $('textarea#behaviours').val();
        ability = $('textarea#ability').val();
        skills_knowledge = $('textarea#skills_knowledge').val();
        achievements = $('textarea#achievements').val();

        if(learner_comments=="" || dBits3=="" || learner_signature=="" || signature_learner_name=="" || achievements=="" || skills_knowledge=="" || ability=="" || behaviours=="")
        {
            custom_alert_OK_only('Please complete employer progress review and signature section before saving');
            return false;
        }

        attendance = $('input[name="attendance"]:checked').val();
        punctuality = $('input[name="punctuality"]:checked').val();
        attitude = $('input[name="attitude"]:checked').val();
        communication = $('input[name="communication"]:checked').val();
        enthusiasm = $('input[name="enthusiasm"]:checked').val();
        commitment = $('input[name="commitment2"]:checked').val();

        if((attendance!=1 && attendance!=2 && attendance!=3 && attendance!=4) || (punctuality!=1 && punctuality!=2 && punctuality!=3 && punctuality!=4) || (attitude!=1 && attitude!=2 && attitude!=3 && attitude!=4) || (communication!=1 && communication!=2 && communication!=3 && communication!=4) || (enthusiasm!=1 && enthusiasm!=2 && enthusiasm!=3 && enthusiasm!=4) || (commitment!=1 && commitment!=2 && commitment!=3 && commitment!=4))
        {
            custom_alert_OK_only('Please complete mandatory information before signing this form');
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
        custom_alert_OK_only('You have reached to the maximum length of this field');
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
    <div class="Title">Review Form</div>
    <div class="ButtonBar">
        <?php //if($source==2 or $source==3)
        echo '<button onclick="save();">Save</button>';
        //elseif($source==1 && ($form->signature_assessor_font=="" && $form->signature_assessor_name=="" && $form->signature_assessor_date==""))
        // echo '<button onclick="save();">Save</button>';
        ?>
    </div>
    <div class="ActionIconBar"></div>
</div>

<form name="form1" id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="tr_id" value="<?php echo $tr_id ?>" />
<input type="hidden" name="_action" value="save_assessor_review_formv2" />
<input type="hidden" name="review_id" value="<?php echo $review_id; ?>" />
<input type="hidden" name="source" value="<?php echo $source; ?>" />
<input type="hidden" name="signature_learner_font" id="signature_learner_font" value="<?php echo $form_learner->signature_learner_font; ?>" />
<input type="hidden" name="signature_assessor_font" id="signature_assessor_font" value="<?php echo $form_assessor4->signature_assessor_font; ?>" />
<input type="hidden" name="signature_employer_font" id="signature_employer_font" value="<?php echo $form_employer->signature_employer_font; ?>" />
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
                <th style="width: 800px">&nbsp;&nbsp;&nbsp;Learner Review</th>
                </thead>
            </table>
        </td>
        <td>
            <?php   if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
            echo '<img height = "100" width = "80" src="/images/logos/' . SystemConfig::getEntityValue($link, "logo") . '">';
        else
            echo '<img height = "100" width = "80" src="images/sunesislogo.gif">';
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
        <td>Learner Name:</td>
        <td><input type="text" name="learner_name" value="<?php echo $form_assessor1->learner_name; ?>" size=30/></td>
        <td>Date of Birth:</td>
        <td> <?php echo HTML::datebox("learner_dob", $form_assessor1->learner_dob, true, false); ?> </td>
    </tr>
    <tr>
        <td>Reviewer/ Assessor:</td>
        <td><input type="text" name="learner_assessor" value="<?php echo $form_assessor1->learner_assessor; ?>" size=30/></td>
        <td>NI Number:</td>
        <td><input type="text" name="learner_ni" value="<?php echo $form_assessor1->learner_ni; ?>" size=30/></td>
    </tr>
    <tr>
        <td>Employer Name:</td>
        <td><input type="text" name="learner_employer" value="<?php echo $form_assessor1->learner_employer; ?>" size=30/></td>
        <td>Line Manager:</td>
        <td><input type="text" name="learner_manager" value="<?php echo $form_assessor1->learner_manager; ?>" size=30/></td>
    </tr>
    <tr>
        <td>Programme:</td>
        <td><input type="text" name="learner_programme" value="<?php echo $form_assessor1->learner_programme; ?>" size=30/></td>
        <td>Qualification Title:</td>
        <td><input type="text" name="learner_qualification" value="<?php echo $form_assessor1->learner_qualification; ?>" size=30/></td>
    </tr>
    <tr>
        <td>Start Date:</td>
        <td> <?php echo HTML::datebox("start_date", $form_assessor1->start_date, true, false); ?> </td>
        <td>Registration Number:</td>
        <td><input type="text" name="registration_number" value="<?php echo $form_assessor1->registration_number; ?>" size=30/></td>
    </tr>
    <tr>
        <td>Expected Completion Date:</td>
        <td> <?php echo HTML::datebox("planned_end_date", $form_assessor1->planned_end_date, true, false); ?> </td>
        <td>Actual Review Date:</td>
        <td>
            <?php   if($source==1)
                        echo HTML::datebox("review_date", $form_assessor1->review_date, true, false);
                    else
                        echo $form_assessor1->review_date;
            ?>
            <?php  ?>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;IF REVIEW 1</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Confirm understanding of plagiarism</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea onpaste="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="plagiarism" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">' . $form_assessor1->plagiarism . '</textarea>';
                    else
                        echo $form_assessor1->plagiarism;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Review Employer Comments</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Discuss employer comments from previous review and any challenges set at FAP</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea onpaste="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="employer_previous_comments" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor1->employer_previous_comments.'</textarea>';
                    else
                        echo $form_assessor1->employer_previous_comments;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Significant Achievement over past 4 weeks</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Learner to identify a personal achievement  for example a piece of work, team contribution, work place recognition, Apprentice of the Month or Learner of the Week nomination. </td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea onpaste="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="significant_achievement" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_assessor1->significant_achievement.'</textarea>';
                    else
                        echo $form_assessor1->significant_achievement;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Equality and Diversity</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Review learner understanding of Equality and Diversity, QCF Appeals procedure and bullying and harassment.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" name="equality_diversity" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_assessor2->equality_diversity.'</textarea>';
                    else
                        echo $form_assessor2->equality_diversity;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Safeguarding including E Safety</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Discuss with learner whether they feel safe at work, are they aware of <?php if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo') echo 'Baltic '; else echo 'Perspective '; ?>Training&apos;s Safeguarding policy.  Explore their understanding of safeguarding.  Discuss with learners their understanding of e safety, privacy setting, the negative aspects of social media.  Educate and challenge as appropriate.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="safeguarding" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_assessor2->safeguarding.'</textarea>';
                    else
                        echo $form_assessor2->safeguarding;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Prevent, Radicalisation and Extremism</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Explore with learners their understanding of Prevent, Radicalisation and Extremism.   Educate and challenge as appropriate.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="prevent" onpaste="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_assessor2->prevent.'</textarea>';
                    else
                        echo $form_assessor2->prevent;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Health & Wellbeing</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Discuss with learner topics such as five a day, diet and exercise, factors that affect their health, i.e. drugs, alcohol and smoking. Raise awareness of anxiety and depression.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="health_wellbeing" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="15" cols="123">'.$form_assessor3->health_wellbeing.'</textarea>';
                    else
                        echo $form_assessor3->health_wellbeing;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Concerns</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Does the learner have any concerns or wish to raise any Safeguarding, Equality & Diversity, Health & Safety, Health  & wellbeing issues (ASK THIS QUESTION EVERY MONTH)</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="concerns" onpaste="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" style="font-family:sans-serif; font-size:10pt"  rows="20" cols="123">'. $form_assessor3->concerns . '</textarea>';
                    else
                        echo $form_assessor3->concerns;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Apprenticeship Commitment</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Are there any issues or anything you would like to disclose which could prevent you completing your apprenticeship?</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="commitment" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_assessor3->commitment.'</textarea>';
                    else
                        echo $form_assessor3->commitment;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Additional Support Requirements</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Is there any additional support you would like from <?php if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo') echo 'Baltic '; else echo 'Perspective '; ?>Training or your Line Manager?</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="additional_support" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor4->additional_support.'</textarea>';
                    else
                        echo $form_assessor4->additional_support;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Progress at Placement / Employment</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Discuss both positive and development areas.  Comment on attendance, time keeping, attitude and ability including new skills developed.  Identify new skills and experience that have been learnt and applied at work.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="progress" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor4->progress.'</textarea>';
                    else
                        echo $form_assessor4->progress;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Off the job training in the workplace</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Record here training and learning that has taken place at work.  This will include job shadowing, mentoring by a supervisor, project work. (Learning Logs/Learning Diary can support these statements).</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="discussion" onpaste="checkLength(event,this,1000)" onkeypress="checkLength(event,this,1000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_assessor4->discussion.'</textarea>';
                    else
                        echo $form_assessor4->discussion;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;ERR</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="err" onpaste="checkLength(event,this,5000)" onkeypress="checkLength(event,this,5000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor4->err.'</textarea>';
        else
            echo $form_assessor4->err;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Complete Main Aim and Tech Cert Progress for Apprentices Completing Frameworks</th>
    </tr><tr>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Main Aim  (Indicate units completed with a %)</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Main aim title (Units):</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="main_name_unit1" type=text size=5 value="<?php echo $form_assessor2->main_name_unit1; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="main_name_unit2" type=text size=5 value="<?php echo $form_assessor2->main_name_unit2; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="main_name_unit3" type=text size=5 value="<?php echo $form_assessor2->main_name_unit3; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="main_name_unit4" type=text size=5 value="<?php echo $form_assessor2->main_name_unit4; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="main_name_unit5" type=text size=5 value="<?php echo $form_assessor2->main_name_unit5; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="main_name_unit6" type=text size=5 value="<?php echo $form_assessor2->main_name_unit6; ?>"/></td>
                </tr>
                <tr>
                    <td colspan=2><input name="main_perc_unit1" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit1; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit2" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit2; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit3" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit3; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit4" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit4; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit5" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit5; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit6" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit6; ?>"/></td>
                </tr>
                <tr>
                    <td>Unit</td>
                    <td><input name="main_name_unit7" type=text size=5 value="<?php echo $form_assessor2->main_name_unit7; ?>"/></td>
                    <td>Unit</td>
                    <td><input name="main_name_unit8" type=text size=5 value="<?php echo $form_assessor2->main_name_unit8; ?>"/></td>
                    <td>Unit</td>
                    <td><input name="main_name_unit9" type=text size=5 value="<?php echo $form_assessor2->main_name_unit9; ?>"/></td>
                    <td>Unit</td>
                    <td><input name="main_name_unit10" type=text size=5 value="<?php echo $form_assessor2->main_name_unit10; ?>"/></td>
                    <td>Unit</td>
                    <td><input name="main_name_unit11" type=text size=5 value="<?php echo $form_assessor2->main_name_unit11; ?>"/></td>
                    <td>Unit</td>
                    <td><input name="main_name_unit12" type=text size=5 value="<?php echo $form_assessor2->main_name_unit12; ?>"/></td>
                </tr>
                <tr>
                    <td colspan=2><input name="main_perc_unit7" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit7; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit8" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit8; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit9" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit9; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit10" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit10; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit11" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit11; ?>"/></td>
                    <td colspan=2><input name="main_perc_unit12" type=text size=13 value="<?php echo $form_assessor2->main_perc_unit12; ?>"/></td>
                </tr>
            </table>
        </td>
    <tr>
        <td>
            <table>
                <tr>
                    <td style="text-align: center">Workshop 1</td>
                    <td style="text-align: center">Workshop 2</td>
                    <td style="text-align: center">Workshop 3</td>
                </tr>
                <tr>
                    <td><input name="workshop1" type=text size=34 value="<?php echo $form_assessor2->workshop1; ?>"/></td>
                    <td><input name="workshop2" type=text size=34 value="<?php echo $form_assessor2->workshop2; ?>"/></td>
                    <td><input name="workshop3" type=text size=34 value="<?php echo $form_assessor2->workshop3; ?>"/></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Progress toward target date. What has the learner been doing towards completing this aim? Insert Progress made in FAP</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="main_progress" onpaste="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor2->main_progress.'</textarea>';
                    else
                        echo $form_assessor2->main_progress;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Tech Cert (Indicate units completed with a %)</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Tech Cert title (Units):</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="tech_name_unit1" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit1; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="tech_name_unit2" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit2; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="tech_name_unit3" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit3; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="tech_name_unit4" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit4; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="tech_name_unit5" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit5; ?>"/></td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td><input name="tech_name_unit6" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit6; ?>"/></td>
                <tr>
                </tr>
                <td colspan=2><input name="tech_perc_unit1" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit1; ?>"/></td>
                <td colspan=2><input name="tech_perc_unit2" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit2; ?>"/></td>
                <td colspan=2><input name="tech_perc_unit3" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit3; ?>"/></td>
                <td colspan=2><input name="tech_perc_unit4" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit4; ?>"/></td>
                <td colspan=2><input name="tech_perc_unit5" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit5; ?>"/></td>
                <td colspan=2><input name="tech_perc_unit6" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit6; ?>"/></td>
                <tr>
                </tr>
                <td>&nbsp;Unit&nbsp;</td>
                <td><input name="tech_name_unit7" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit7; ?>"/></td>
                <td>&nbsp;Unit&nbsp;</td>
                <td><input name="tech_name_unit8" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit8; ?>"/></td>
                <td>&nbsp;Unit&nbsp;</td>
                <td><input name="tech_name_unit9" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit9; ?>"/></td>
                <td>&nbsp;Unit&nbsp;</td>
                <td><input name="tech_name_unit10" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit10; ?>"/></td>
                <td>&nbsp;Unit&nbsp;</td>
                <td><input name="tech_name_unit11" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit11; ?>"/></td>
                <td>&nbsp;Unit&nbsp;</td>
                <td><input name="tech_name_unit12" type=text size=5 value="<?php echo $form_assessor3->tech_name_unit12; ?>"/></td>
                </tr>
                <tr>
                    <td colspan=2><input name="tech_perc_unit7" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit7; ?>"/></td>
                    <td colspan=2><input name="tech_perc_unit8" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit8; ?>"/></td>
                    <td colspan=2><input name="tech_perc_unit9" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit9; ?>"/></td>
                    <td colspan=2><input name="tech_perc_unit10" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit10; ?>"/></td>
                    <td colspan=2><input name="tech_perc_unit11" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit11; ?>"/></td>
                    <td colspan=2><input name="tech_perc_unit12" type=text size=13 value="<?php echo $form_assessor3->tech_perc_unit12; ?>"/></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Progress toward target date - What has the learner been doing towards completing this aim? Insert Progress made in FAP</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="tech_progress" onpaste="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor3->tech_progress.'</textarea>';
                    else
                        echo $form_assessor3->tech_progress;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Complete Knowledge Module and Competence Progress for Apprentices Completing Standards</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Progress Summary: Knowledge Modules</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="knowledge_module_1" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_1; ?>"/></td>
                    <td><input name="knowledge_status_1" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_1; ?>"/></td>
                    <td><input name="knowledge_module_2" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_2; ?>"/></td>
                    <td><input name="knowledge_status_2" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_2; ?>"/></td>
                    <td><input name="knowledge_module_3" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_3; ?>"/></td>
                    <td><input name="knowledge_status_3" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_3; ?>"/></td>
                </tr>
                <tr>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="knowledge_module_4" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_4; ?>"/></td>
                    <td><input name="knowledge_status_4" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_4; ?>"/></td>
                    <td><input name="knowledge_module_5" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_5; ?>"/></td>
                    <td><input name="knowledge_status_5" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_5; ?>"/></td>
                    <td><input name="knowledge_module_6" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_6; ?>"/></td>
                    <td><input name="knowledge_status_6" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_6; ?>"/></td>
                </tr>
                <tr>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="knowledge_module_7" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_7; ?>"/></td>
                    <td><input name="knowledge_status_7" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_7; ?>"/></td>
                    <td><input name="knowledge_module_8" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_8; ?>"/></td>
                    <td><input name="knowledge_status_8" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_8; ?>"/></td>
                    <td><input name="knowledge_module_9" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_9; ?>"/></td>
                    <td><input name="knowledge_status_9" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_9; ?>"/></td>
                </tr>
                <tr>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="knowledge_module_10" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_10; ?>"/></td>
                    <td><input name="knowledge_status_10" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_10; ?>"/></td>
                    <td><input name="knowledge_module_11" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_11; ?>"/></td>
                    <td><input name="knowledge_status_11" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_11; ?>"/></td>
                    <td><input name="knowledge_module_12" type=text size=16 value="<?php echo $form_assessor4->knowledge_module_12; ?>"/></td>
                    <td><input name="knowledge_status_12" type=text size=10 value="<?php echo $form_assessor4->knowledge_status_12; ?>"/></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Record here the detail of the progress.  What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="knowledge_module" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor4->knowledge_module.'</textarea>';
                    else
                        echo $form_assessor4->knowledge_module;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4>Progress Summary: Workplace Competence</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="workplace_competence_1" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_1; ?>"/></td>
                    <td><input name="workplace_status_1" type=text size=13 value="<?php echo $form_assessor4->workplace_status_1; ?>"/></td>
                    <td><input name="workplace_competence_2" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_2; ?>"/></td>
                    <td><input name="workplace_status_2" type=text size=13 value="<?php echo $form_assessor4->workplace_status_2; ?>"/></td>
                    <td><input name="workplace_competence_3" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_3; ?>"/></td>
                    <td><input name="workplace_status_3" type=text size=13 value="<?php echo $form_assessor4->workplace_status_3; ?>"/></td>
                </tr>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="workplace_competence_4" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_4; ?>"/></td>
                    <td><input name="workplace_status_4" type=text size=13 value="<?php echo $form_assessor4->workplace_status_4; ?>"/></td>
                    <td><input name="workplace_competence_5" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_5; ?>"/></td>
                    <td><input name="workplace_status_5" type=text size=13 value="<?php echo $form_assessor4->workplace_status_5; ?>"/></td>
                    <td><input name="workplace_competence_6" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_6; ?>"/></td>
                    <td><input name="workplace_status_6" type=text size=13 value="<?php echo $form_assessor4->workplace_status_6; ?>"/></td>
                </tr>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="workplace_competence_7" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_7; ?>"/></td>
                    <td><input name="workplace_status_7" type=text size=13 value="<?php echo $form_assessor4->workplace_status_7; ?>"/></td>
                    <td><input name="workplace_competence_8" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_8; ?>"/></td>
                    <td><input name="workplace_status_8" type=text size=13 value="<?php echo $form_assessor4->workplace_status_8; ?>"/></td>
                    <td><input name="workplace_competence_9" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_9; ?>"/></td>
                    <td><input name="workplace_status_9" type=text size=13 value="<?php echo $form_assessor4->workplace_status_9; ?>"/></td>
                </tr>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="workplace_competence_10" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_10; ?>"/></td>
                    <td><input name="workplace_status_10" type=text size=13 value="<?php echo $form_assessor4->workplace_status_10; ?>"/></td>
                    <td><input name="workplace_competence_11" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_11; ?>"/></td>
                    <td><input name="workplace_status_11" type=text size=13 value="<?php echo $form_assessor4->workplace_status_11; ?>"/></td>
                    <td><input name="workplace_competence_12" type=text size=13 value="<?php echo $form_assessor4->workplace_competence_12; ?>"/></td>
                    <td><input name="workplace_status_12" type=text size=13 value="<?php echo $form_assessor4->workplace_status_12; ?>"/></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Record here the detail of the progress.  What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="workplace_competence" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor4->workplace_competence.'</textarea>';
                    else
                        echo $form_assessor4->workplace_competence;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Functional Skills Development: In the Workplace - In Everyday Use - In Training</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Functional Skills exemptions (Tick if exempt):</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="text-align: center; width: 100px">English</td><td><?php echo HTML::checkbox("english_exempt", 1, ($form_assessor4->english_exempt==1)?true:false, true, false); ?></td>
                    <td style="text-align: center; width: 100px">Maths</td><td><?php echo HTML::checkbox("math_exempt", 1, ($form_assessor4->math_exempt==1)?true:false, true, false); ?></td>
                    <td style="text-align: center; width: 100px">ICT</td><td><?php echo HTML::checkbox("ict_exempt", 1, ($form_assessor4->ict_exempt==1)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Identify use of Maths, English and ICT in the work role, how are these skills being developed in the work role. Provide examples of work tasks that continue to develop functional skills knowledge.  Recognise their everyday use.  Learner to comment on FS workshop activity, moredle use or one to one specialist support received.  If First Review - review induction task  here.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="use_functional" onpaste="checkLength(event,this,8000)" onkeypress="checkLength(event,this,8000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor4->use_functional.'</textarea>';
                    else
                        echo $form_assessor4->use_functional;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Functional Skills - (complete only if working towards qualification)</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Indicate units completed with a %</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="text-align: center">&nbsp;&nbsp;Qualification&nbsp;&nbsp;</td>
                    <td style="text-align: center">English L1</td>
                    <td style="text-align: center">English L2</td>
                    <td style="text-align: center">Math L1</td>
                    <td style="text-align: center">Math L2</td>
                    <td style="text-align: center">ICT L1</td>
                    <td style="text-align: center">ICT L2</td>
                    <td style="text-align: center">PLTS</td>
                </tr>
                <tr>
                    <td>%</td>
                    <td><input name="english_l1" type=text size=8 value="<?php echo $form_assessor4->english_l1; ?>"/></td>
                    <td><input name="english_l2" type=text size=8 value="<?php echo $form_assessor4->english_l2; ?>"/></td>
                    <td><input name="math_l1" type=text size=8 value="<?php echo $form_assessor4->math_l1; ?>"/></td>
                    <td><input name="math_l2" type=text size=8 value="<?php echo $form_assessor4->math_l2; ?>"/></td>
                    <td><input name="ict_l1" type=text size=8 value="<?php echo $form_assessor4->ict_l1; ?>"/></td>
                    <td><input name="ict_l2" type=text size=8 value="<?php echo $form_assessor4->ict_l2; ?>"/></td>
                    <td><input name="plts" type=text size=8 value="<?php echo $form_assessor4->plts; ?>"/></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Progress toward target date - What has the learner been doing towards completing this aim? Insert Progress made in FAP</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
                        echo '<textarea name="functional_progress" onpaste="checkLength(event,this,5000)" onkeypress="checkLength(event,this,5000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_assessor4->functional_progress.'</textarea>';
                    else
                        echo $form_assessor4->functional_progress;
            ?>
        </i></td>
    </tr>
    </tbody>
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
        <td colspan=4>
            <table>
                <tr>
                    <td style="text-align: center">S - Specific</td>
                    <td style="text-align: center">M - Measurable</td>
                    <td style="text-align: center">AR - Achievable & Realistic</td>
                    <td style="text-align: center">T - Timebound</td>
                </tr>
                <tr>
                    <td><i>
                        <?php   if($source==1)
                                    echo '<textarea name="specific" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="10" cols="26">'.$form_assessor4->specific.'</textarea>';
                                else
                                    echo $form_assessor4->specific;
                        ?>
                    </i></td>
                    <td><i>
                        <?php   if($source==1)
                                    echo '<textarea name="measurable" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="10" cols="26">'.$form_assessor4->measurable.'</textarea>';
                                else
                                    echo $form_assessor4->measurable;
                        ?>
                    </i></td>
                    <td><i>
                        <?php   if($source==1)
                                    echo '<textarea name="achievable" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="10" cols="26">'.$form_assessor4->achievable.'</textarea>';
                                else
                                    echo $form_assessor4->achievable;
                        ?>
                    </i></td>
                    <td><i>
                        <?php   if($source==1)
                                    echo '<textarea name="timebound" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="10" cols="26">'.$form_assessor4->timebound.'</textarea>';
                                else
                                    echo $form_assessor4->timebound;
                        ?>
                    </i></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=3 style="text-align: center; width: 100px">The manager was present during the review</td><td><?php echo HTML::checkbox("present", 1, ($form_assessor4->present==1)?true:false, true, false); ?></td>
    </tr>
    </tbody>
</table>
<br>



<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Date of next contact: </th>
    <th style="text-align: center">
        <?php   if($source==1)
                    echo HTML::datebox("next_contact", $form_assessor4->next_contact, true, false);
                else
                    echo $form_assessor4->next_contact;
        ?>
    </th>
    </thead>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Comments</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>(to include general comments on course, feedback on how they feel it is going, new skills developed)</td>
    </tr>
    <tr>
        <td colspan=4><textarea id="learner_comment" name="learner_comment" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form_learner->learner_comment; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Progress Review</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Please complete the following section to review your apprentice's progress in their apprenticeship.<br>How does your apprentice contribute to your team/business?</td>
    </tr>
    <tr>
        <td colspan=4><textarea id="employer_progress_review" name="employer_progress_review" onpaste="checkLength(event,this,5000)" onkeypress="checkLength(event,this,5000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form_employer->employer_progress_review; ?></textarea></td>
    </tr>
    <tr>
        <td colspan=4>Please tick the following (this is to support your apprentice to maintain/improve behaviour).</td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td style="width: 200px; text-align: center">&nbsp;</td>
                    <td style="width: 200px; text-align: center">Poor</td>
                    <td style="width: 200px; text-align: center">Satisfactory</td>
                    <td style="width: 200px; text-align: center">Good</td>
                    <td style="width: 200px; text-align: center">Excellent</td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Attendance</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attendance", 1, ($form_employer->attendance==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attendance", 2, ($form_employer->attendance==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attendance", 3, ($form_employer->attendance==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attendance", 4, ($form_employer->attendance==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Punctuality/Timekeeping</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("punctuality", 1, ($form_employer->punctuality==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("punctuality", 2, ($form_employer->punctuality==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("punctuality", 3, ($form_employer->punctuality==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("punctuality", 4, ($form_employer->punctuality==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Attitude</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attitude", 1, ($form_employer->attitude==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attitude", 2, ($form_employer->attitude==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attitude", 3, ($form_employer->attitude==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attitude", 4, ($form_employer->attitude==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Communication</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("communication", 1, ($form_employer->communication==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("communication", 2, ($form_employer->communication==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("communication", 3, ($form_employer->communication==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("communication", 4, ($form_employer->communication==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Enthusiasm</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("enthusiasm", 1, ($form_employer->enthusiasm==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("enthusiasm", 2, ($form_employer->enthusiasm==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("enthusiasm", 3, ($form_employer->enthusiasm==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("enthusiasm", 4, ($form_employer->enthusiasm==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Commitment to the role</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("commitment2", 1, ($form_employer->commitment2==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("commitment2", 2, ($form_employer->commitment2==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("commitment2", 3, ($form_employer->commitment2==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("commitment2", 4, ($form_employer->commitment2==4)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Please record further comments regarding:</td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td>Behaviours:</td>
                    <td colspan=3><textarea id="behaviours" name="behaviours"  onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="6" cols="100"><?php echo $form_employer->behaviours; ?></textarea></td>
                </tr>
                <tr>
                    <td>Ability:</td>
                    <td colspan=3><textarea id="ability" name="ability" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="6" cols="100"><?php echo $form_employer->ability; ?></textarea></td>
                </tr>
                <tr>
                    <td>Skills and Knowledge:</td>
                    <td colspan=3><textarea id="skills_knowledge" name="skills_knowledge" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="6" cols="100"><?php echo $form_employer->skills_knowledge; ?></textarea></td>
                </tr>
                <tr>
                    <td>Achievements/ progress at work:</td>
                    <td colspan=3><textarea id="achievements" name="achievements" onpaste="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="6" cols="100"><?php echo $form_employer->achievements; ?></textarea></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Your comments are important and any development areas will be set as objectives for your apprentice</td>
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
        if($form_learner->signature_learner_font!='')
            echo '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = ' . str_replace(" ","%20",$form_learner->signature_learner_font) .  ' height="49" width="285"/></div></td>';
        else
            echo '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input id="signature_learner_name" name="signature_learner_name" type="text" size=30 value="<?php echo $form_learner->signature_learner_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_learner_date", $form_learner->signature_learner_date, false, false); ?> </td>
    </tr>
    <tr>
        <td>Reviewer</td>
        <?php   if($form_assessor4->signature_assessor_font!='')
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = ' . str_replace(" ","%20",$form_assessor4->signature_assessor_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input name="signature_assessor_name" type="text" size=30 value="<?php echo $form_assessor4->signature_assessor_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_assessor_date", $form_assessor4->signature_assessor_date, false, false); ?> </td>
    </tr>
    <tr>
        <td>Supervisor/ Company Contact:</td>
        <?php   if($form_employer->signature_employer_font!='')
        echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = ' . str_replace(" ","%20",$form_employer->signature_employer_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input name="signature_employer_name" type="text" size=30 value="<?php echo $form_employer->signature_employer_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_employer_date", $form_employer->signature_employer_date, false, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>
</form>
<button onclick="save();">Save</button>
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
        timePicker(300); // input parameter is in number of seconds
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
            $.post('do.php?_action=save_assessor_review_formv2.php',$('#form1').serialize(),function(r)
            {
                s = setTimeout('timePicker(' + 300 + ')', 5000);
                return false;

            });
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

</script>

</body>
</html>