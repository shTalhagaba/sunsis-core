<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Learner On Programme Review Software Development Technician</title>
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
    <div class="Title">Learner On Programme Review Software Development Technician</div>
    <div class="ButtonBar">
        <?php //if($source==2 or $source==3)
        if(isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER)
            if($form_arf->signature_employer_font=='' || $form_arf->signature_learner_font=='')
                echo '<button onclick="save();">Save</button>';
            else
                echo '<button onclick="window.location.href=' . $_SESSION['bc']->getPrevious() .'">Close</button>';

        //elseif($source==1 && ($form->signature_assessor_font=="" && $form->signature_assessor_name=="" && $form->signature_assessor_date==""))
        // echo '<button onclick="save();">Save</button>';
        ?>
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
                <th style="width: 800px">&nbsp;&nbsp;&nbsp;Learner On Programme Review Software Development Technician</th>
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
                        <?php echo TrainingRecord::getAssessmentProgress($link, $tr_id);?>%
                    </td>
                    <td><i>
                        <?php ?>
                    </i></td>
                    <td><i>
                        <?php ?>
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
        <td colspan=4>SMART</td>
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
                        <?php echo $previous_review->specific;?>
                    </i></td>
                    <td><i>
                        <?php echo $previous_review->measurable;?>
                    </i></td>
                    <td><i>
                        <?php echo $previous_review->achievable; ?>
                    </i></td>
                    <td><i>
                        <?php echo $previous_review->timebound; ?>
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
    <th colspan=4>&nbsp;&nbsp;&nbsp;Personal Development Progress</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>Review Employer Comments</b> <br>Discuss employer comments from previous review</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="introduction" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">' . $form_arf->introduction . '</textarea>';
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
        <td style="text-align: center;"><?php echo "";?></td>
        <td style="text-align: center;"><?php echo ""; ?></td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="learner_concerns" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form_arf->learner_concerns.'</textarea>';
        else
            echo $form_arf->learner_concerns;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=5>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th>
    </thead>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=1>Logic</td>
        <td>Write simple code for discrete software components following appropriate logical approach to agreed standards</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Logic'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("communication_status", $ss_statuses, $form_arf->communication_status, true, false) . "</td>";?>
        <?php $communication = isset($Assessment_Plan['Logic'])?$Assessment_Plan['Logic']:"&nbsp"; echo "<td rowspan=3 style='text-align:center; vertical-align:middle'>" . $communication . "</td>";?>
    </tr>

    <tr>
        <td>Security</td>
        <td>Apply secure development principles to specific software components</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['Security'] . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("it_security_status", $ss_statuses, $form_arf->it_security_status, true, false) . "</td>";?>
        <?php $it_security = isset($Assessment_Plan['Security'])?$Assessment_Plan['Security']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>";?>
    </tr>

    <tr>
        <td>Development Support</td>
        <td>Apply industry standard approaches for configuration management and version control to manage code during build and release</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['Development Support'] . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("remote_infrastructure_status", $ss_statuses, $form_arf->remote_infrastructure_status, true, false) . "</td>";?>
        <?php $remote_infrastructure = isset($Assessment_Plan['Development Support'])?$Assessment_Plan['Development Support']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>";?>
    </tr>

    <tr>
        <td>Data</td>
        <td>Make connections between code and defined data sources</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['Data'] . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("data_status", $ss_statuses, $form_arf->data_status, true, false) . "</td>";?>
        <?php $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $data . "</td>";?>
    </tr>

    <tr>
        <td rowspan=1>Test</td>
        <td>Conduct functionality tests on deliverables for that component</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Test'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("problem_solving_status", $ss_statuses, $form_arf->problem_solving_status, true, false) . "</td>";?>
        <?php $problem_solving = isset($Assessment_Plan['Test'])?$Assessment_Plan['Test']:"&nbsp"; echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>";?>
    </tr>

    <tr>
        <td>Analysis</td>
        <td>Basic analysis models such as use cases and process maps</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['Analysis'] . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("workflow_management_status", $ss_statuses, $form_arf->workflow_management_status, true, false) . "</td>";?>
        <?php $workflow_management = isset($Assessment_Plan['Analysis'])?$Assessment_Plan['Analysis']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>";?>
    </tr>

    <tr>
        <td>Development Lifecycle</td>
        <td>Supports the Software Developers at the build and test stages of the software development lifecycle</td>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . $ss_result['Development Lifecycle'] . "</td>"; ?>
        <?php echo "<td style='text-align:center; vertical-align:middle'>" . HTML::select("health_safety_status", $ss_statuses, $form_arf->health_safety_status, true, false) . "</td>";?>
        <?php $health_safety = isset($Assessment_Plan['Development Lifecycle'])?$Assessment_Plan['Development Lifecycle']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>";?>
    </tr>

    <tr>
        <td rowspan=1>Quality</td>
        <td>Follows organisational and industry good coding practices (including those for naming, commenting etc.)</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Quality'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("performance_status", $ss_statuses, $form_arf->performance_status, true, false) . "</td>";?>
        <?php $performance = isset($Assessment_Plan['Quality'])?$Assessment_Plan['Quality']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>";?>
    </tr>

    <tr>
        <td rowspan=1>Problem Solving</td>
        <td>Solve logical problems including appropriate mathematical application</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Problem Solving'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("performance_status", $ss_statuses, $form_arf->performance_status, true, false) . "</td>";?>
        <?php $performance = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>";?>
    </tr>

    <tr>
        <td rowspan=1>Business Environment</td>
        <td>Respond to business issues related to software development</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Business Environment'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("performance_status", $ss_statuses, $form_arf->performance_status, true, false) . "</td>";?>
        <?php $performance = isset($Assessment_Plan['Business Environment'])?$Assessment_Plan['Business Environment']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>";?>
    </tr>

    <tr>
        <td rowspan=1>Communication</td>
        <td>Articulate the role and function of software components to a variety of stakeholders</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Business Environment'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("performance_status", $ss_statuses, $form_arf->performance_status, true, false) . "</td>";?>
        <?php $performance = isset($Assessment_Plan['Communication'])?$Assessment_Plan['Communication']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>";?>
    </tr>

    <tr>
        <td rowspan=1>User Interface</td>
        <td>Develop user interfaces appropriate to the organisations development standards and the type of component being developed.</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Business Environment'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("performance_status", $ss_statuses, $form_arf->performance_status, true, false) . "</td>";?>
        <?php $performance = isset($Assessment_Plan['User Interface'])?$Assessment_Plan['User Interface']:"&nbsp"; echo "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>";?>
    </tr>

</table>

<table class="table1" style="width: 900px">
    <thead>
        <th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th>
    </thead>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td>A range of cabling and connectivity, the various types of antennas and wireless systems and IT test equipment (Networking MTA)</td>
        <?php echo "<td rowspan=5 style='text-align:center; vertical-align:middle'>" . $tk_result['Network Fundamentals MTA Test'] . "</td>"; ?>
        <?php echo "<td rowspan=5 style='text-align:center; vertical-align:middle'>" . HTML::select("network_fundamentals_status", $ss_statuses, $form_arf->network_fundamentals_status, true, false) . "</td>";?>
        <?php echo "<td rowspan=5 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'Network Fundamentals MTA Test') . "</td>";?>
    </tr>
    <tr><td>Maintenance  processes and applying them in working practices (Networking MTA)</td></tr>
    <tr><td>Applying the basic elements and architecture of computer systems (Networking MTA)</td>
    <tr><td>Where to apply the relevant numerical skills e.g. Binary (Networking MTA)</td>
    <tr><td>Networking skills necessary to maintain a secure network (Networking MTA)</td>
    </tr>

    <tr>
        <td>Similarities, differences and benefits of the current Operating Systems available (Mobility & Devices MTA)</td>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk_result['Motability and Devices Test'] . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("motability_devices_status", $ss_statuses, $form_arf->motability_devices_status, true, false) . "</td>";?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'Motability and Devices Test') . "</td>";?>
    </tr>
    <tr><td>How to operate remotely and how to deploy and securely integrate mobile devices (Mobility & Devices MTA)</td></tr>
    </tr>

    <tr>
        <td>Cloud and Cloud Service (Cloud MTA)</td>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk_result['Cloud Fundamentals Test'] . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("cloud_fundamentals_status", $ss_statuses, $form_arf->cloud_fundamentals_status, true, false) . "</td>";?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'Cloud Fundamentals Test') . "</td>";?>
    </tr>
    <tr><td>Importance of disaster recovery and how a disaster recovery plan works and their role within it (Cloud MTA)</td></tr>
    </tr>

    <tr>
        <td>Similarities and differences between a range of coding and logic (Coding & Logic)</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['9268-09 Coding and Logic Test'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("coding_logic_status", $ss_statuses, $form_arf->coding_logic_status, true, false) . "</td>";?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'9268-09 Coding and Logic Test') . "</td>";?>
    </tr>

    <tr>
        <td>Business processes (Business Processes)</td>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk_result['9628-10 ITIL Foundation Test'] . "</td>"; ?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("itil_foundation_status", $ss_statuses, $form_arf->itil_foundation_status, true, false) . "</td>";?>
        <?php echo "<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $this->getEventStatus($events,'9628-10 ITIL Foundation Test') . "</td>";?>
    </tr>
    <tr><td>Business IT skills relevant to the organization (Business Processes)</td></tr>
    </tr>

</table>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th>
    </thead>
    <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>

    <tr>
        <td>Logical and creative thinking skills</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("logical_creative_status", $ss_statuses, $form_arf->logical_creative_status, true, false) . "</td>";?>
        <?php   if($this->isAssessmentComplete($Assessment_Plan, Array("Data","Problem Solving","Workflow Management")))
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
        ?>
    </tr>

    <tr>
        <td>Analytical and problem solving skills</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("analytical_status", $ss_statuses, $form_arf->analytical_status, true, false) . "</td>";?>
        <?php   if($this->isAssessmentComplete($Assessment_Plan, Array("Data","Problem Solving")))
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
        ?>
    </tr>

    <tr>
        <td>Ability to work independently and to take responsibility</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("independently_status", $ss_statuses, $form_arf->independently_status, true, false) . "</td>";?>
        <?php   if($this->isAssessmentComplete($Assessment_Plan, Array("Communication","IT Security","Remote Infrastructure","Data","Problem Solving","Workflow Management","Health and Safety","Performance","WEEE")))
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
        ?>
    </tr>

    <tr>
        <td>Can use own initiative</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("initiative_status", $ss_statuses, $form_arf->initiative_status, true, false) . "</td>";?>
        <?php   if($this->isAssessmentComplete($Assessment_Plan, Array("Communication","IT Security","Remote Infrastructure","Data","Problem Solving","Workflow Management","Health and Safety","Performance","WEEE")))
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
        ?>
    </tr>

    <tr>
        <td>A thorough and organised approach</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("organised_status", $ss_statuses, $form_arf->organised_status, true, false) . "</td>";?>
        <?php   if($this->isAssessmentComplete($Assessment_Plan, Array("IT Security","Problem Solving","Workflow Management","Performance","WEEE")))
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
        ?>
    </tr>

    <tr>
        <td>Ability to work with a range of internal and external people</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("internal_external_status", $ss_statuses, $form_arf->internal_external_status, true, false) . "</td>";?>
        <?php   if($this->isAssessmentComplete($Assessment_Plan, Array("Problem Solving","Health and Safety","Performance","WEEE","Communication")))
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
        ?>
    </tr>

    <tr>
        <td>Ability to communicate effectively in a variety of situations</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("communicate_effectively_status", $ss_statuses, $form_arf->communicate_effectively_status, true, false) . "</td>";?>
        <?php   if($this->isAssessmentComplete($Assessment_Plan, Array("Communication")))
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
        ?>
    </tr>

    <tr>
        <td>Maintain productive, professional and secure working environment</td>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment'] . "</td>"; ?>
        <?php echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("maintain_productive_status", $ss_statuses, $form_arf->maintain_productive_status, true, false) . "</td>";?>
        <?php   if($this->isAssessmentComplete($Assessment_Plan, Array("Communication","IT Security","Remote Infrastructure","Data","Problem Solving","Workflow Management","Health and Safety","Performance","WEEE")))
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        echo "<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
        ?>
    </tr>

</table>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="workplace_competence" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->workplace_competence.'</textarea>';
        else
            echo $form_arf->workplace_competence;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Record here the detail of the progress. What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="knowledge_modules" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'. $form_arf->knowledge_modules.'</textarea>';
        else
            echo $form_arf->knowledge_modules;
            ?>
        </i></td>
    </tr>
    </tbody>
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
                        <?php   if(true)
                        echo '<textarea name="specific" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="10" cols="26">'.$form_arf->specific.'</textarea>';
                    else
                        echo $form_arf->specific;
                        ?>
                    </i></td>
                    <td><i>
                        <?php   if(true)
                        echo '<textarea name="measurable" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="10" cols="26">'.$form_arf->measurable.'</textarea>';
                    else
                        echo $form_arf->measurable;
                        ?>
                    </i></td>
                    <td><i>
                        <?php   if(true)
                        echo '<textarea name="achievable" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="10" cols="26">'.$form_arf->achievable.'</textarea>';
                    else
                        echo $form_arf->achievable;
                        ?>
                    </i></td>
                    <td><i>
                        <?php   if(true)
                        echo '<textarea name="timebound" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" rows="10" cols="26">'.$form_arf->timebound.'</textarea>';
                    else
                        echo $form_arf->timebound;
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
        echo $form_arf->learner_comment;
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
        <td colspan=4><textarea tabindex="-1" id="employer_progress_review" name="employer_progress_review" onblur="checkLength(event,this,5000)" onkeypress="checkLength(event,this,5000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form_arf->employer_progress_review; ?></textarea></td>
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
            <?php   if($source==3)
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
            <?php   if($source==3)
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
            <?php   if($source==3)
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
            <?php   if($source==3)
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
            <?php   if($source==3)
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
            <?php   if($source==3)
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
            <?php   if($source==3)
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


</script>

</body>
</html>