<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Learner On Programme Review Cyber Security Risk Analyst</title>
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
        commitment = $('input[name="commitment"]:checked').val();

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
        commitment = $('input[name="commitment"]:checked').val();

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
    <div class="Title">Learner On Programme Review Cyber Security Risk Analyst</div>
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
                <th style="width: 800px">&nbsp;&nbsp;&nbsp;Learner On Programme Review Cyber Security Risk Analyst</th>
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
        <td colspan=4><b>Review Employer Comments</b> <br>Discuss employer comments from previous review</td>
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
        <td colspan=4><b>Significant Achievement over past 4 weeks</b><br>Learner to identify a personal achievement – for example a piece of work, team contribution, Apprentice of the month or learner of the week nomination.</td>
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
        <td colspan=4><b>Equality and Diversity</b><br>Review learner understanding of Equality and Diversity, QCF Appeals procedure and bullying and harassment. Educate and challenge as appropriate.</td>
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
    <tr>
        <td colspan=4><b>Safeguarding including E-Safety</b><br>Explore learner understanding of Safeguarding. Discuss with learner whether they feel safe at work. Discuss with learners their understanding of e-safety, privacy setting, the negative aspects of social media. Educate and challenge as appropriate.</td>
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
        <td colspan=4><b>Prevent, Radicalisation and Extremism</b><br>Explore with learners their understanding of Prevent, Radicalisation and Extremism and British values. Educate and challenge as appropriate.</td>
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
    <tr>
        <td colspan=4><b>Health and Wellbeing</b><br>Raise awareness of anxiety & Depression. Discuss with the learner topics such as diet and exercise, factors that affect their health, i.e drugs, alcohol and smoking.</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="functional_skills" onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->functional_skills.'</textarea>';
        else
            echo $form_arf->functional_skills;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Learner Concerns</b><br>Does the learner have any concerns or wish to raise any Safeguarding, Equality & Diversity, Health & Safety, Health & Wellbeing issues.</td>
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
        <td colspan=4><b>Apprenticeship Commitment</b><br>Are there any issues or anything you would like to disclose which could prevent you completing your 12 month apprenticeship?</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="skilsure" onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" style="font-family:sans-serif; font-size:10pt"  rows="20" cols="123">'. $form_arf->skilsure . '</textarea>';
        else
            echo $form_arf->skilsure;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Additional Support Requirements</b><br>Is there any additional support you would like from Baltic Training or your Line Manager</td>
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
    <tr>
        <td colspan=4><b>Learner Progress at Placement / Employment</b><br>Discuss both positive and development areas.  Comment on attendance, time keeping, attitude and ability including new skills developed.  Identify new skills and experience that have been learnt and applied at work.</td>
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
    <tr>
        <td colspan=4><b>Off the job training in the workplace</b><br>Record here training and learning that has taken place at work.  This will include job shadowing, mentoring by a supervisor, project work. (Learning Logs/Learning Diary can support these statements).</td>
    </tr>
    <tr>
        <td style="text-align: center">Previous:</td>
        <td style="text-align: center">Current:</td>
        <td style="text-align: center">Other:</td>
    </tr>
    <tr>
        <td style="text-align: center;"><?php echo DAO::getSingleValue($link, "SELECT SUM(hours) FROM assessor_review WHERE tr_id = '$tr_id'"); ?></td>
        <?php   if($source==1)
        echo '<td style="text-align: center"><input type="text" name="current_hours" value="'. $form_arf->current_hours . '" size=3/></td>';
    else
        echo '<td style="text-align: center">' . $form_arf->current_hours . '</td>';
        ?>
        <td style="text-align: center;"><?php echo DAO::getSingleValue($link, "SELECT (SELECT COALESCE(SUM(HOUR(TIMEDIFF(time_to, time_from))),0)  FROM additional_support WHERE tr_id = '$tr_id')+
(SELECT
                      COALESCE(SUM(HOUR(TIMEDIFF(start_time,end_time))),0)
                    FROM
                      session_attendance
                      INNER JOIN session_entries
                        ON session_attendance.`session_entry_id` = session_entries.`entry_id`
                      INNER JOIN sessions
                        ON session_entries.`entry_session_id` = sessions.`id`
                    WHERE sessions.`event_type` = 'SUP' AND session_attendance.`attendance_code` = 1
                    AND session_entries.`entry_tr_id` = '$tr_id');
"); ?></td>
    </tr>
    <tr>
            <?php   if($source==1)
            echo '<td colspan=4><textarea name="learner_concerns" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_arf->learner_concerns.'</textarea></td>';
        else
            echo '<td>' . $form_arf->learner_concerns . '</td>';
            ?>
    </tr>
    </tbody>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
    </thead>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Risk Analyst Activity 1</td>
        <td><?php if(isset($Assessment_Plan['Risk Analyst Activity 1'])) echo $Assessment_Plan['Risk Analyst Activity 1']; else "&nbsp;" ?></td>
        <td>Risk Analyst Activity 2</td>
        <td><?php if(isset($Assessment_Plan['Risk Analyst Activity 2'])) echo $Assessment_Plan['Risk Analyst Activity 2']; else "&nbsp;" ?></td>
        <td>Risk Analyst Activity 3</td>
        <td><?php if(isset($Assessment_Plan['Risk Analyst Activity 3'])) echo $Assessment_Plan['Risk Analyst Activity 3']; else "&nbsp;" ?></td>
    </tr>
    <tr>
        <td>Risk Analyst Activity 4</td>
        <td><?php if(isset($Assessment_Plan['Risk Analyst Activity 4'])) echo $Assessment_Plan['Risk Analyst Activity 4']; else "&nbsp;" ?></td>
        <td>Core Activity 1</td>
        <td><?php if(isset($Assessment_Plan['Core Activity 1'])) echo $Assessment_Plan['Core Activity 1']; else "&nbsp;" ?></td>
        <td>Core Activity 2</td>
        <td><?php if(isset($Assessment_Plan['Core Activity 2'])) echo $Assessment_Plan['Core Activity 2']; else "&nbsp;" ?></td>
    </tr>
    <tr>
        <td>Core Activity 3</td>
        <td><?php if(isset($Assessment_Plan['Core Activity 3'])) echo $Assessment_Plan['Core Activity 3']; else "&nbsp;" ?></td>
        <td>Core Activity 4</td>
        <td><?php if(isset($Assessment_Plan['Core Activity 4'])) echo $Assessment_Plan['Core Activity 4']; else "&nbsp;" ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
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

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
    </thead>
    <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
    <tr>
        <td>Certificate in cyber security introduction</td>
        <td><?php echo $this->getEventStatus($events, "Certificate in cyber security introduction") . "<br>" . $this->getEventDate($events,'Certificate in cyber security introduction'); ?></td>
        <td>Certificate in cyber security introduction test</td>
        <td><?php echo $this->getEventStatus($events, "Certificate in cyber security introduction test") . "<br>" . $this->getEventDate($events,'Certificate in cyber security introduction test'); ?></td>
        <td>Certificate in network and digital communications</td>
        <td><?php echo $this->getEventStatus($events, "Certificate in network and digital communications") . "<br>" . $this->getEventDate($events,'Certificate in network and digital communications'); ?></td>
    </tr>
    <tr>
        <td>Certificate in security technology building blocks</td>
        <td><?php echo $this->getEventStatus($events, "Certificate in security technology building blocks") . "<br>" . $this->getEventDate($events,'Certificate in security technology building blocks'); ?></td>
        <td>Certificate in governance organisation law regulation and standards</td>
        <td><?php echo $this->getEventStatus($events, "Certificate in governance organisation law regulation and standards") . "<br>" . $this->getEventDate($events,'Certificate in governance organisation law regulation and standards'); ?></td>
        <td>Certificate in governance organisation law regulation and standards test</td>
        <td><?php echo $this->getEventStatus($events, "Certificate in governance organisation law regulation and standards test") . "<br>" . $this->getEventDate($events,'Certificate in governance organisation law regulation and standards test'); ?></td>
    </tr>
    <tr>
        <td>Award in risk assessment</td>
        <td><?php echo $this->getEventStatus($events, "Award in risk assessment") . "<br>" . $this->getEventDate($events,'Award in risk assessment'); ?></td>
        <td>Award in risk assessment test</td>
        <td><?php echo $this->getEventStatus($events, "Award in risk assessment test") . "<br>" . $this->getEventDate($events,'Award in risk assessment test'); ?></td>
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
    <th colspan=3>&nbsp;&nbsp;&nbsp;Functional Skills Development: In the workplace - In everyday use - In training</th>
    </thead>
    <tr><td colspan=3>Functional Skills exemptions:</td></tr>
    <tr><td>Maths <?php $checked = ($math_exempt==1)?"checked":""; echo "<input type = checkbox $checked>"; ?></td><td>English <?php $checked = ($english_exempt==1)?"checked":""; echo "<input type = checkbox $checked>"; ?></td><td>ICT <?php $checked = ($ict_exempt==1)?"checked":""; echo "<input type = checkbox $checked>"; ?></td></tr>
    <tr>
        <td colspan=6>Identify use of Maths, English and ICT in the work role, how are these skills being developed in the work role. Provide examples of work task that continue to develop functional skills knowledge. Recognise their everyday use. Learner to comment on FS workshop activity, mordle use of one to one specialist support received. In first review – Review induction task here. </td>
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
    <th colspan=6>&nbsp;&nbsp;&nbsp;Progress summary: Functional Skills – (complete only if working towards qualification)</th>
    </thead>
    <tr><td colspan=6>Indicate units completed with a %</td></tr>
    <tr>
        <td>English L2</td><td>Maths L1</td><td>Maths L2</td><td>ICT L1</td><td>ICT L2</td><td>PLTS</td>
    </tr>
    <tr>
        <td><?php echo DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%English%\" AND internaltitle LIKE \"%Level 2%\" and tr_id = '$tr_id';"); ?></td>
        <td><?php echo DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%Math%\" AND internaltitle LIKE \"%Level 1%\" and tr_id = '$tr_id';"); ?></td>
        <td><?php echo DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%Math%\" AND internaltitle LIKE \"%Level 2%\" and tr_id = '$tr_id';"); ?></td>
        <td><?php echo DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%ICT%\" AND internaltitle LIKE \"%Level 1%\" and tr_id = '$tr_id';"); ?></td>
        <td><?php echo DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%ICT%\" AND internaltitle LIKE \"%Level 2%\" and tr_id = '$tr_id';"); ?></td>
        <td><?php echo DAO::getSingleValue($link, "SELECT IF(actual_end_date IS NOT NULL,\"Passed\",IF(aptitude=0,\"Required\",IF(aptitude=1,\"Exempt\",\"\"))) FROM student_qualifications WHERE internaltitle LIKE \"%PLTS%\" and tr_id = '$tr_id';"); ?></td>
    </tr>
    <tr>
        <td colspan=6><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="functional_skills_progress2" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->functional_skills_progress2.'</textarea>';
        else
            echo $form_arf->functional_skills_progress2;
            ?>
        </i></td>
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
    <tr>
    <td>&nbsp;&nbsp;&nbsp;Manager Attendance:
        <?php   if($source==1 && $form_arf->signature_assessor_font=='') { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->manager_attendance=="1")?" checked ":""; echo "<input name = \"manager_attendance\" type = checkbox $checked>"; ?></td>
        <?php } else { ?>
        <td style="text-align: center;"><?php $checked = ($form_arf->manager_attendance=="1")?" checked ":""; echo "<input disabled=\"disabled\" name = \"manager_attendance\" type = checkbox $checked>"; echo "<input type=\"hidden\" name = \"manager_attendance\" value=\"1\">";?></td>
        <?php } ?>
        </td>
    </tr>
</table>

<table class="table1" style="width: 900px">
    <tr>
        <td colspan=4>&nbsp;&nbsp;&nbsp;Date of next contact: </td>
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
<table class="table1" style="width: 900px">
    <thead>
    <th>&nbsp;&nbsp;&nbsp;Adobe Link: </th>
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
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Comments</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>(to include general comments on course, feedback on how they feel it is going, new skills developed)</td>
    </tr>
    <tr>
        <?php   if($source==2)
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
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Progress Review</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Please complete the following section to review your apprentice's progress in their apprenticeship.<br>How does your apprentice contribute to your team/business?</td>
    </tr>
    <tr>
        <td colspan=4><textarea tabindex="-1" id="employer_progress_review" name="employer_progress_review" onblur="checkLength(event,this,10000)" onkeypress="checkLength(event,this,5000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form_arf->employer_progress_review; ?></textarea></td>
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
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attendance", 1, ($form_arf->attendance==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attendance", 2, ($form_arf->attendance==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attendance", 3, ($form_arf->attendance==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attendance", 4, ($form_arf->attendance==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Punctuality/Timekeeping</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("punctuality", 1, ($form_arf->punctuality==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("punctuality", 2, ($form_arf->punctuality==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("punctuality", 3, ($form_arf->punctuality==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("punctuality", 4, ($form_arf->punctuality==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Attitude</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attitude", 1, ($form_arf->attitude==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attitude", 2, ($form_arf->attitude==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attitude", 3, ($form_arf->attitude==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("attitude", 4, ($form_arf->attitude==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Communication</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("communication", 1, ($form_arf->communication==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("communication", 2, ($form_arf->communication==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("communication", 3, ($form_arf->communication==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("communication", 4, ($form_arf->communication==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Enthusiasm</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("enthusiasm", 1, ($form_arf->enthusiasm==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("enthusiasm", 2, ($form_arf->enthusiasm==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("enthusiasm", 3, ($form_arf->enthusiasm==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("enthusiasm", 4, ($form_arf->enthusiasm==4)?true:false, true, false); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Commitment to the role</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("commitment", 1, ($form_arf->commitment==1)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("commitment", 2, ($form_arf->commitment==2)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("commitment", 3, ($form_arf->commitment==3)?true:false, true, false); ?></td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::radio("commitment", 4, ($form_arf->commitment==4)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Comments (Please record here any comments you would like to add judging the attitude and behaviour of your apprentice in regards to the following areas:)</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>Logical and creative thinking skills:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="emp_logical_creative" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="123">' . $form_arf->emp_logical_creative . '</textarea>';
        else
            echo $form_arf->emp_logical_creative;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Analytical and problem solving skills:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3 or $source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="emp_problem_solving" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="123">' . $form_arf->emp_problem_solving . '</textarea>';
        else
            echo $form_arf->emp_problem_solving;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Ability to work independently and to take responsibility:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3 or $source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="emp_independently" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="123">' . $form_arf->emp_independently . '</textarea>';
        else
            echo $form_arf->emp_independently;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Can use own initiative:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3 or $source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="emp_initiative" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="123">' . $form_arf->emp_initiative . '</textarea>';
        else
            echo $form_arf->emp_initiative;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>A thorough and organised approach:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3 or $source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="emp_organised" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="123">' . $form_arf->emp_organised . '</textarea>';
        else
            echo $form_arf->emp_organised;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Ability to work with a range of internal and external people:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3 or $source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="emp_internal_external" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="123">' . $form_arf->emp_internal_external . '</textarea>';
        else
            echo $form_arf->emp_internal_external;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Ability to communicate effectively in a variety of situations:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3 or $source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="emp_communicate_effectively" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="123">' . $form_arf->emp_communicate_effectively . '</textarea>';
        else
            echo $form_arf->emp_communicate_effectively;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Maintain productive, professional and secure working environment:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3 or $source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="emp_maintain_productive" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="123">' . $form_arf->emp_maintain_productive . '</textarea>';
        else
            echo $form_arf->emp_maintain_productive;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>All comments are important and any development areas will be set as objectives for your apprentice</td>
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