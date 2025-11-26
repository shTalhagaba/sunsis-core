<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Health & Safety</title>
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
        //$('input[name="signature_employer_name"]').attr('class','disabled');
        //$('input[name="signature_employer_date"]').attr('class','disabled');
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

            $.post('do.php?_action=save_health_safety_form.php',$('#form1').serialize(),function(r)
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

        behaviours = $('textarea#behaviours').val();
        ability = $('textarea#ability').val();
        skills_knowledge = $('textarea#skills_knowledge').val();
        achievements = $('textarea#achievements').val();

        attendance = $('input[name="attendance"]:checked').val();
        punctuality = $('input[name="punctuality"]:checked').val();
        attitude = $('input[name="attitude"]:checked').val();
        communication = $('input[name="communication"]:checked').val();
        enthusiasm = $('input[name="enthusiasm"]:checked').val();
        commitment = $('input[name="commitment"]:checked').val();

    }


    $( "#panel_signature" ).dialog( "open");
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
      /*  learner_comments = $('textarea#employer_progress_review').val();
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
        commitment = $('input[name="commitment"]:checked').val(); */


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


function Assessment()
{
    var overall = true;

    if($('input[name="assessment1"]:checked').val()=="on" && $('input[name="assessment2"]:checked').val()=="on")
        document.getElementById("standard1").src="/images/green-tick.gif";
    else
    {
        document.getElementById("standard1").src="/images/red-cross.gif";
        overall = false;
    }

    if($('input[name="assessment3"]:checked').val()=="on" && ($('input[name="assessment4"]:checked').val()=="1" || $('input[name="assessment4"]:checked').val()=="3"))
        document.getElementById("standard2").src="/images/green-tick.gif";
    else
    {
        document.getElementById("standard2").src="/images/red-cross.gif";
        overall = false;
    }

    if($('input[name="assessment5"]:checked').val()=="on" && $('input[name="assessment6"]:checked').val()=="on")
        document.getElementById("standard3").src="/images/green-tick.gif";
    else
    {
        document.getElementById("standard3").src="/images/red-cross.gif";
        overall = false;
    }

    if($('input[name="assessment7"]:checked').val()=="on" && $('input[name="assessment8"]:checked').val()=="on")
        document.getElementById("standard4").src="/images/green-tick.gif";
    else
    {
        document.getElementById("standard4").src="/images/red-cross.gif";
        overall = false;
    }

    if(($('input[name="assessment9"]:checked').val()=="1" || $('input[name="assessment9"]:checked').val()=="3") && ($('input[name="assessment10"]:checked').val()=="1" || $('input[name="assessment10"]:checked').val()=="3"))
        document.getElementById("standard5").src="/images/green-tick.gif";
    else
    {
        document.getElementById("standard5").src="/images/red-cross.gif";
        overall = false;
    }

    if(($('input[name="assessment11"]:checked').val()=="1" || $('input[name="assessment11"]:checked').val()=="3") && ($('input[name="assessment12"]:checked').val()=="1" || $('input[name="assessment12"]:checked').val()=="3"))
        document.getElementById("standard6").src="/images/green-tick.gif";
    else
    {
        document.getElementById("standard6").src="/images/red-cross.gif";
        overall = false;
    }

    if($('input[name="assessment13"]:checked').val()=="on" && $('input[name="assessment14"]:checked').val()=="on")
        document.getElementById("standard7").src="/images/green-tick.gif";
    else
    {
        document.getElementById("standard7").src="/images/red-cross.gif";
        overall = false;
    }

    if($('input[name="assessment15"]:checked').val()=="on" && $('input[name="assessment16"]:checked').val()=="on")
        document.getElementById("standard8").src="/images/green-tick.gif";
    else
    {
        document.getElementById("standard8").src="/images/red-cross.gif";
        overall = false;
    }

    if($('input[name="assessment17"]:checked').val()=="on" && ($('input[name="assessment18"]:checked').val()=="1" || $('input[name="assessment18"]:checked').val()=="3"))
        document.getElementById("standard9").src="/images/green-tick.gif";
    else
    {
        document.getElementById("standard9").src="/images/red-cross.gif";
        overall = false;
    }

    if(overall==true)
        document.getElementById("standard10").src="/images/green-tick.gif";
    else
        document.getElementById("standard10").src="/images/red-cross.gif";


}

</script>


</head>
<body id="candidates" onload="Assessment()">
<div class="banner">
    <div class="Title">Health & Safety Form</div>
    <div class="ButtonBar">
        <?php //if($source==2 or $source==3)
        if(isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER)
            if($form_arf->signature_employer_font=='' or $form_arf->signature_assessor_font=='')
                echo '<button onclick="save();">Save</button>';
            else
                echo '<button onclick="window.location.href=' . $_SESSION['bc']->getPrevious() .'">Close</button>';?>
    </div>
    <div class="ActionIconBar"></div>
</div>

<form name="form1" id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_health_safety_form" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="source" value="<?php echo $source; ?>" />
<input type="hidden" name="signature_assessor_font" id="signature_assessor_font" value="<?php echo $form_arf->signature_assessor_font; ?>" />
<input type="hidden" name="signature_employer_font" id="signature_employer_font" value="<?php echo $form_arf->signature_employer_font; ?>" />
<input type="hidden" name="autosave" id="autosave" value="1" />

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Site Information</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=1>Company Name:</td>
        <td colspan=3><input type="text" name="company_name" value="<?php echo $form_arf->company_name; ?>" size=100/></td>
    </tr>
    <tr>
        <td><label for="address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Building No./Name & Street:</label></td>
        <td><input type="text" class="form-control optional" name="address_line_1" id="address_line_1" size=40 value="<?php echo $form_arf->address_line_1; ?>" maxlength="100" /></td>
        <td><label for="address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Suburb / Village::</label></td>
        <td><input type="text" class="form-control optional" name="address_line_2" id="address_line_2" size=40 value="<?php echo $form_arf->address_line_2; ?>" maxlength="100" /></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Town / City:</label></td>
        <td><input type="text" class="form-control optional" name="address_line_3" id="address_line_3" size=40 value="<?php echo $form_arf->address_line_3; ?>" maxlength="100" /></td>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">County:</label></td>
        <td><input type="text" class="form-control optional" name="address_line_4" id="address_line_4" size=40 value="<?php echo $form_arf->address_line_4; ?>" maxlength="100" /></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label></td>
        <td><input type="text" class="form-control optional" name="postcode" id="postcode" size=40 value="<?php echo $form_arf->postcode; ?>" onkeyup="this.value = this.value.toUpperCase();" maxlength="10" /></td>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Telephone:</label></td>
        <td><input type="text" class="form-control optional" name="telephone" id="telephone" size=40 value="<?php echo $form_arf->telephone; ?>" maxlength="100" /></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Email Address:</label></td>
        <td><input type="text" class="form-control optional" name="email" id="email" size=40 value="<?php echo $form_arf->email; ?>" maxlength="100" /></td>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Number of employees:</label></td>
        <td><input type="text" class="form-control optional" name="employees" id="employees" size=40 value="<?php echo $form_arf->employees; ?>" maxlength="100" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
        <thead>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Site point of contact</th>
        </thead>
        <tbody>
        <tr>
            <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Contact name:</label></td>
            <td><input type="text" class="form-control optional" name="contact_name" id="contact_name" size=40 value="<?php echo $form_arf->contact_name; ?>" maxlength="100" /></td>
            <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Job role:</label></td>
            <td><input type="text" class="form-control optional" name="job_role" id="job_role" size=40 value="<?php echo $form_arf->job_role; ?>" maxlength="100" /></td>
        </tr>
        <tr>
            <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Phone Number:</label></td>
            <td><input type="text" class="form-control optional" name="contact_phone" id="contact_phone" size=40 value="<?php echo $form_arf->contact_phone; ?>" maxlength="100" /></td>
            <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Mobile Number:</label></td>
            <td><input type="text" class="form-control optional" name="contact_mobile" id="contact_mobile" size=40 value="<?php echo $form_arf->contact_mobile; ?>" maxlength="100" /></td>
        </tr>
        <tr>
            <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Email Address:</label></td>
            <td><input type="text" class="form-control optional" name="contact_email" id="contact_email" size=40 value="<?php echo $form_arf->contact_email; ?>" maxlength="100" /></td>
        </tr>
        </tbody>
    </table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Enforcement Actions (<a href="https://www.hse.gov.uk/enforce/">https://www.hse.gov.uk/enforce/</a>)</th>
    </thead>
    <tbody>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Do you have any enforcement actions?</label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->enforcement_actions=="on")?" checked ":""; echo "<input name = \"enforcement_actions\" type = checkbox $checked>"; ?></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Liability Insurance</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=1><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Insurer's Name:</label></td>
        <td colspan=3><input type="text" class="form-control optional" name="insurer_name" id="insurer_name" size=102 value="<?php echo $form_arf->insurer_name; ?>" maxlength="100" /></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Policy Number:</label></td>
        <td><input type="text" class="form-control optional" name="policy_number" id="policy_number" size=40 value="<?php echo $form_arf->policy_number; ?>" maxlength="100" /></td>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Expiry Date:</label></td>
        <td> <?php echo HTML::datebox("expiry_date", $form_arf->expiry_date, true, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=3>&nbsp;&nbsp;&nbsp;1 Health and Safety Policy &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evidence/ Comments</th>
    </thead>
    <tbody>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Is there a H&S policy in place?</label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment1=="on")?" checked ":""; echo "<input name = \"assessment1\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td colspan=2><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments1" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="60"> <?php echo $form_arf->comments1 ?></textarea></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Does it:<br><table><tr><td>A</td><td>Include your general approach to health and safety</td></tr><tr><td>B</td><td>Explain how you, as an employer, will manage health and safety in your business</td></tr><tr><td>C</td><td>Clearly say who does what, when and how</td></tr></table></label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment2=="on")?" checked ":""; echo "<input name = \"assessment2\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td rowspan=1><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments2" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="60"> <?php echo $form_arf->comments2 ?></textarea></td>
    </tr>
    <tr>
        <td bgcolor="red" colspan=1>Assessment of Standard 1 Met?</td>
        <td colspan=3 align="center"><img id="standard1" src="/images/red-cross.gif" border="0" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=3>&nbsp;&nbsp;&nbsp;2 Risk assessment and control &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evidence/ Comments</th>
    </thead>
    <tbody>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Have Risk Assessments been carried out and been recorded?</label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment3=="on")?" checked ":""; echo "<input name = \"assessment3\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td colspan=2><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments3" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="60"> <?php echo $form_arf->comments3 ?></textarea></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Do they cover:<br><table><tr><td>A</td><td>both adult and young persons and significant risks identified (where relevant)</td></tr><tr><td>B</td><td>significant findings and details of any groups identified as being especially at risk been recorded (where relevant)</td></tr><tr><td>C</td><td>Have control measures been identified and put in place as a result of the risk assessments? (where relevant)</td></tr><tr><td>D</td><td>Do the risk assessments take into account young persons, including giving consideration to their age, inexperience, immaturity and lack of awareness of risks? (where relevant)</td></tr><tr><td>E</td><td>Are risk assessments reviewed and does active monitoring take place with the findings acted upon?</td></tr></table></label></td>
        <td width="50" style="text-align: left;"><input type = radio id = "assessment4" name = "assessment4" value="1" onclick="Assessment()" <?php echo ($form_arf->assessment4==1)?" checked ":"";?>>Yes<br><input type = radio id = "assessment4" name = "assessment4" value="2" onclick="Assessment()" <?php echo ($form_arf->assessment4==2)?" checked ":"";?>>No<br><input type = radio id = "assessment4" name = "assessment4" value="3" onclick="Assessment()" <?php echo ($form_arf->assessment4==3)?" checked ":"";?>>N/A</td>
        <td rowspan=1><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments4" style="font-family:sans-serif; font-size:10pt"  rows="26" cols="60"> <?php echo $form_arf->comments4 ?></textarea></td>
    </tr>
    <tr>
        <td bgcolor="red" colspan=1>Assessment of Standard 2 Met?</td>
        <td colspan=3 align="center"><img id="standard2" src="/images/red-cross.gif" border="0" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=3>&nbsp;&nbsp;&nbsp;3 Accident, incidents and first aid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evidence/ Comments</th>
    </thead>
    <tbody>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Have adequate arrangements for first aid materials been made?</label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment5=="on")?" checked ":""; echo "<input name = \"assessment5\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td colspan=2><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments5" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="60"> <?php echo $form_arf->comments5 ?></textarea></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Do they include:<br><table><tr><td>A</td><td>Adequate arrangements for trained first aid persons been made?</td></tr><tr><td>B</td><td>Are accidents recorded?</td></tr><tr><td>C</td><td>Do arrangements exist for employees to report all accidents / near misses to enable suitable remedial action to be taken?</td></tr><tr><td>D</td><td>Are or will all legally reportable accidents be reported?</td></tr><tr><td>E</td><td>Are the arrangements for accidents, incidents, ill-health and first aid made known to all employees?</td></tr></table></label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment6=="on")?" checked ":""; echo "<input name = \"assessment6\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td rowspan=1><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments6" style="font-family:sans-serif; font-size:10pt"  rows="19" cols="60"> <?php echo $form_arf->comments6 ?></textarea></td>
    </tr>
    <tr>
        <td bgcolor="red" colspan=1>Assessment of Standard 3 Met?</td>
        <td colspan=3 align="center"><img id="standard3" src="/images/red-cross.gif" border="0" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=3>&nbsp;&nbsp;&nbsp;4 Supervision, training, info and instruction &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evidence/ Comments</th>
    </thead>
    <tbody>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Are employees provided with adequate competent supervision?</label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment7=="on")?" checked ":""; echo "<input name = \"assessment7\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td colspan=2><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments7" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="60"> <?php echo $form_arf->comments7 ?></textarea></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory"><table><tr><td>A</td><td>Are initial health and safety information, instruction and training given to all new employees on recruitment?</td></tr><tr><td>B</td><td>Are ongoing health and safety information, instruction and training provided to all employees?</td></tr><tr><td>C</td><td>Are health and safety information, instruction and training recorded?</td></tr><tr><td>D</td><td>Is the effectiveness of health and safety information, instruction and training assessed, and is the assessment recorded?</td></tr></table></label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment8=="on")?" checked ":""; echo "<input name = \"assessment8\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td rowspan=1><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments8" style="font-family:sans-serif; font-size:10pt"  rows="18" cols="60"> <?php echo $form_arf->comments8 ?></textarea></td>
    </tr>
    <tr>
        <td bgcolor="red" colspan=1>Assessment of Standard 4 Met?</td>
        <td colspan=3 align="center"><img id="standard4" src="/images/red-cross.gif" border="0" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=3>&nbsp;&nbsp;&nbsp;5 Work equipment and machinery &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evidence/ Comments</th>
    </thead>
    <tbody>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Is correct machinery and equipment provided to the appropriate standards?</label></td>
        <td width="50" style="text-align: left;"><input type = radio id = "assessment9" name = "assessment9" value="1" onclick="Assessment()" <?php echo ($form_arf->assessment9==1)?" checked ":"";?>>Yes<br><input type = radio id = "assessment9" name = "assessment9" value="2" onclick="Assessment()" <?php echo ($form_arf->assessment9==2)?" checked ":"";?>>No<br><input type = radio id = "assessment9" name = "assessment9" value="3" onclick="Assessment()" <?php echo ($form_arf->assessment9==3)?" checked ":"";?>>N/A</td>
        <td colspan=2><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments9" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="60"> <?php echo $form_arf->comments9 ?></textarea></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory"><table><tr><td>A</td><td>Is equipment adequately maintained?</td></tr><tr><td>B</td><td>Are guards and control measures in place as determined through risk assessment?</td></tr><tr><td>C</td><td>Are safe electrical systems and equipment provided and maintained?</td></tr></table></label></td>
        <td width="50" style="text-align: left;"><input type = radio id = "assessment10" name = "assessment10" value="1" onclick="Assessment()" <?php echo ($form_arf->assessment10==1)?" checked ":"";?>>Yes<br><input type = radio id = "assessment10" name = "assessment10" value="2" onclick="Assessment()" <?php echo ($form_arf->assessment10==2)?" checked ":"";?>>No<br><input type = radio id = "assessment10" name = "assessment10" value="3" onclick="Assessment()" <?php echo ($form_arf->assessment10==3)?" checked ":"";?>>N/A</td>
        <td rowspan=1><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments10" style="font-family:sans-serif; font-size:10pt"  rows="9" cols="60"> <?php echo $form_arf->comments10 ?></textarea></td>
    </tr>
    <tr>
        <td bgcolor="red" colspan=1>Assessment of Standard 5 Met?</td>
        <td colspan=3 align="center"><img id="standard5" src="/images/red-cross.gif" border="0" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=3>&nbsp;&nbsp;&nbsp;6 P.P.E and Clothing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evidence/ Comments</th>
    </thead>
    <tbody>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Is PPE/C provided, free of charge, to employees as determined through risk assessment?</label></td>
        <td width="50" style="text-align: left;"><input type = radio id = "assessment11" name = "assessment11" value="1" onclick="Assessment()" <?php echo ($form_arf->assessment11==1)?" checked ":"";?>>Yes<br><input type = radio id = "assessment11" name = "assessment11" value="2" onclick="Assessment()" <?php echo ($form_arf->assessment11==2)?" checked ":"";?>>No<br><input type = radio id = "assessment11" name = "assessment11" value="3" onclick="Assessment()" <?php echo ($form_arf->assessment11==3)?" checked ":"";?>>N/A</td>
        <td colspan=2><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments11" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="60"> <?php echo $form_arf->comments11 ?></textarea></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory"><table><tr><td>A</td><td>Is training and information on the safe use of PPE/C provided to all employees?</td></tr><tr><td>B</td><td>Is the proper use and storage of PPE/C enforced?</td></tr></table></label></td>
        <td width="50" style="text-align: left;"><input type = radio id = "assessment12" name = "assessment12" value="1" onclick="Assessment()" <?php echo ($form_arf->assessment12==1)?" checked ":"";?>>Yes<br><input type = radio id = "assessment12" name = "assessment12" value="2" onclick="Assessment()" <?php echo ($form_arf->assessment12==2)?" checked ":"";?>>No<br><input type = radio id = "assessment12" name = "assessment12" value="3" onclick="Assessment()" <?php echo ($form_arf->assessment12==3)?" checked ":"";?>>N/A</td>
        <td rowspan=1><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments12" style="font-family:sans-serif; font-size:10pt"  rows="7" cols="60"> <?php echo $form_arf->comments12 ?></textarea></td>
    </tr>
    <tr>
        <td bgcolor="red" colspan=1>Assessment of Standard 6 Met?</td>
        <td colspan=3 align="center"><img id="standard6" src="/images/red-cross.gif" border="0" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=3>&nbsp;&nbsp;&nbsp;7 Fire and emergencies &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evidence/ Comments</th>
    </thead>
    <tbody>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Is there a means of raising the alarm and fire detection in place?</label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment13=="on")?" checked ":""; echo "<input name = \"assessment13\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td colspan=2><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments13" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="60"> <?php echo $form_arf->comments13 ?></textarea></td>
    </tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory"><table><tr><td>A</td><td>Are there appropriate means of fighting fire in place?</td></tr><tr><td>B</td><td>Are effective means of escape in place including unobstructed routes and exits?</td></tr><tr><td>C</td><td>Is there a named person(s) for emergencies?</td></tr><tr><td>D</td><td>Is fire-fighting equipment, preventive measures and emergency arrangements maintained, including through tests and practice drills?</td></tr><tr><td>E</td><td>Is fire-fighting equipment, preventive measures and emergency arrangements maintained, including through tests and practice drills?</td></tr><tr><td>F</td><td>Is a fire log/record book kept?</td></tr></table></label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment14=="on")?" checked ":""; echo "<input name = \"assessment14\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td rowspan=1><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments14" style="font-family:sans-serif; font-size:10pt"  rows="21" cols="60"> <?php echo $form_arf->comments14 ?></textarea></td>
    </tr>
    <tr>
        <td bgcolor="red" colspan=1>Assessment of Standard 7 Met?</td>
        <td colspan=3 align="center"><img id="standard7" src="/images/red-cross.gif" border="0" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=3>&nbsp;&nbsp;&nbsp;8 Safe and healthy working environment &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evidence/ Comments</th>
    </thead>
    <tbody>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Is the environment safe to work in?</label></td>
        <td style="text-align: center;"><?php $checked = ($form_arf->assessment15=="on")?" checked ":""; echo "<input name = \"assessment15\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
        <td colspan=2><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments15" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="60"> <?php echo $form_arf->comments15 ?></textarea></td>
    </tr>
    <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory"><table><tr><td>A</td><td>Are premises (structure, fabric, fixtures and fittings) safe and healthy (suitable, maintained and kept clean)?</td></tr><tr><td>B</td><td>Is the working environment (temperature, lighting, space, ventilation, noise) an appropriate safe and healthy one?</td></tr><tr><td>C</td><td>Are welfare facilities (toilets, washing, drinking, eating, changing) provided as appropriate and maintained?</td></tr><tr><td>D</td><td>Is exposure to hazards from physical, chemical and biological agents adequately controlled?</td></tr></table></label></td>
    <td style="text-align: center;"><?php $checked = ($form_arf->assessment16=="on")?" checked ":""; echo "<input name = \"assessment16\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
    <td rowspan=1><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments16" style="font-family:sans-serif; font-size:10pt"  rows="18" cols="60"> <?php echo $form_arf->comments16 ?></textarea></td>
    </tr>
    <tr>
        <td bgcolor="red" colspan=1>Assessment of Standard 8 Met?</td>
        <td colspan=3 align="center"><img id="standard8" src="/images/red-cross.gif" border="0" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=3>&nbsp;&nbsp;&nbsp;9 General health and safety management &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evidence/ Comments</th>
    </thead>
    <tbody>
    <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">As an Employer, do you;<br><table><tr><td>A</td><td>Consult and communicate with employees and allow them to participate in health and safety?</td></tr><tr><td>B</td><td>Have access to competent health and safety advice and assistance?</td></tr><tr><td>C</td><td>Review health and safety and equality & diversity annually?</td></tr><tr><td>D</td><td>Display the necessary signs and notices? (health & safety poster, fire action etc)</td></tr><tr><td>E</td><td>Assess, review and update employees' capabilities?</td></tr><tr><td>F</td><td>Do you employees and apprentices have formal contracts of employment?</td></tr></table></label></td>
    <td style="text-align: center;"><?php $checked = ($form_arf->assessment17=="on")?" checked ":""; echo "<input name = \"assessment17\" type = checkbox $checked onclick=\"Assessment()\">"; ?></td>
    <td rowspan=2><textarea onblur="checkLength(event,this,4000)" onkeypress="checkLength(event,this,4000)" name="comments17" style="font-family:sans-serif; font-size:10pt"  rows="30" cols="60"> <?php echo $form_arf->comments17 ?></textarea></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Do you provide medical / health screening as appropriate and any required medical / health surveillance?</label></td>
        <td width="50" style="text-align: left;"><input type = radio id = "assessment18" name = "assessment18" value="1" onclick="Assessment()" <?php echo ($form_arf->assessment18==1)?" checked ":"";?>>Yes<br><input type = radio id = "assessment18" name = "assessment18" value="2" onclick="Assessment()" <?php echo ($form_arf->assessment18==2)?" checked ":"";?>>No<br><input type = radio id = "assessment18" name = "assessment18" value="3" onclick="Assessment()" <?php echo ($form_arf->assessment18==3)?" checked ":"";?>>N/A</td>
    </tr>
    <tr>
        <td colspan=2><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Who would a member of staff talk to if they had a grievance</label></td>
        <td colspan=2><input type="text" class="form-control optional" name="comments18" id="comments18" size=58 value="<?php echo $form_arf->comments18; ?>" maxlength="100" /></td>
    </tr>
    <tr>
        <td bgcolor="red" colspan=1>Assessment of Standard 9 Met?</td>
        <td colspan=3 align="center"><img id="standard9" src="/images/red-cross.gif" border="0" /></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>Employer/ Representative Agreement</th>
    </thead>
    <tbody>
    <tr>
        <td>Signed</td>
        <?php   if($form_arf->signature_employer_font!='')
            echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = ' . str_replace(" ","%20",$form_arf->signature_employer_font) .  ' height="49" width="285"/></div></td>';
        else
            echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td>Name</td>
        <td style="text-align: left"><input id="signature_employer_name" name="signature_employer_name" type="text" size=40 value="<?php echo $form_arf->signature_employer_name; ?>"/></td>
    </tr>
    <tr>
        <td>Job Title</td>
        <td style="text-align: left"><input id="employer_job_title" name="employer_job_title" type="text" size=50 value="<?php echo $form_arf->employer_job_title; ?>"/></td>
        <td>Date</td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_employer_date", $form_arf->signature_employer_date, false, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Office use only </th>
    </thead>
    <tbody>
    <tr>
        <td colspan=1><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Nature of work</label></td>
        <td colspan=3><input type="text" class="form-control optional" name="nature_of_work" id="nature_of_work" size=78 value="<?php echo $form_arf->nature_of_work; ?>" maxlength="100" /></td>
    </tr>
    <tr>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Status</label></td>
        <td colspan=1 align="center"><img id="standard10" src="/images/red-cross.gif" border="0" /></td>
        <td><label for="address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">Risk Category</label></td>
        <td width="250" style="text-align: left;"><input type = radio id = "assessment19" name = "assessment19" value="1" onclick="Assessment()" <?php echo ($form_arf->assessment19==1)?" checked ":"";?>>Red (High)<br><input type = radio id = "assessment19" name = "assessment19" value="2" onclick="Assessment()" <?php echo ($form_arf->assessment19==2)?" checked ":"";?>>Amber (Medium)<br><input type = radio id = "assessment19" name = "assessment19" value="3" onclick="Assessment() <?php echo ($form_arf->assessment19==3)?" checked ":"";?>">Green (Low)</td>
    </tr>
    </tbody>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>Vetting sign-off</th>
    </thead>
    <tbody>
    <tr>
        <?php   if($form_arf->signature_assessor_font!='')
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = ' . str_replace(" ","%20",$form_arf->signature_assessor_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td>Name</td>
        <td style="text-align: center"><input name="signature_assessor_name" type="text" size=30 value="<?php echo $form_arf->signature_assessor_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_assessor_date", $form_arf->signature_assessor_date, false, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>


</form>
<?php if( (isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER) || (!isset($_SESSION['user']))){
    if($form_arf->signature_employer_font=='') { ?>
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