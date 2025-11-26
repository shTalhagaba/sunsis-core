<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Employer Agreement</title>
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
        font-weight: normal;
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
            $('input[name="signature_employer_name"]').attr('class','disabled');
            $('input[name="signature_employer_date"]').attr('class','disabled');
        }

    /*    if(source=='1')
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
            $('input[name="present"]').attr('class','disabled');

            $('.disabled').keydown(function(e) {
                e.preventDefault();
            });
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
            $('input[name="present"]').attr('class','disabled');

            $('.disabled').keydown(function(e) {
                e.preventDefault();
            });
        } */

});


function saveDialogue()
{
<?php if(isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER) { ?>

    confirmation("Please save your data now and continue to complete the form").then(function (answer) {
        var ansbool = (String(answer) == "true");
        if(ansbool){

            var myForm = document.forms[0];

            $.post('do.php?_action=save_employer_agreement_form.php',$('#form1').serialize(),function(r)
            {
                return true;
            });
            /*if(client != null && client.responseText == 'true')
                custom_alert_OK_only('Email has been sent');
            else3
                custom_alert_OK_only('Operation aborted, please try again.');*/
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
        /*if(learner_comments.replace(/\s/g, '')=="")
        {
            custom_alert_OK_only('Please complete learner comments before signing this form');
            return 1;
        }*/
    }
    if(user==3)
    {
        learner_comments = $('textarea#employer_progress_review').val();

        behaviours = $('textarea#behaviours').val();
        ability = $('textarea#ability').val();
        skills_knowledge = $('textarea#skills_knowledge').val();
        achievements = $('textarea#achievements').val();

        /*if(learner_comments=="" || achievements=="" || skills_knowledge=="" || ability=="" || behaviours=="")
        {
            custom_alert_OK_only('Please complete mandatory information before signing this form');
            return 1;
        }*/

        attendance = $('input[name="attendance"]:checked').val();
        punctuality = $('input[name="punctuality"]:checked').val();
        attitude = $('input[name="attitude"]:checked').val();
        communication = $('input[name="communication"]:checked').val();
        enthusiasm = $('input[name="enthusiasm"]:checked').val();
        commitment = $('input[name="commitment2"]:checked').val();

        /*if((attendance!=1 && attendance!=2 && attendance!=3 && attendance!=4) || (punctuality!=1 && punctuality!=2 && punctuality!=3 && punctuality!=4) || (attitude!=1 && attitude!=2 && attitude!=3 && attitude!=4) || (communication!=1 && communication!=2 && communication!=3 && communication!=4) || (enthusiasm!=1 && enthusiasm!=2 && enthusiasm!=3 && enthusiasm!=4) || (commitment!=1 && commitment!=2 && commitment!=3 && commitment!=4))
        {
            custom_alert_OK_only('Please complete mandatory information before signing this form');
            return 1;
        }*/
    }


    if(user==1)
    {
        // Check if previous signature exists
        //signature = getPreviousSignature(who)
        var client = ajaxRequest('do.php?_action=ajax_get_previous_signature&type=1&user='+ user + '&id=' + <?php echo $id; ?>);
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

        /*if((attendance!=1 && attendance!=2 && attendance!=3 && attendance!=4) || (punctuality!=1 && punctuality!=2 && punctuality!=3 && punctuality!=4) || (attitude!=1 && attitude!=2 && attitude!=3 && attitude!=4) || (communication!=1 && communication!=2 && communication!=3 && communication!=4) || (enthusiasm!=1 && enthusiasm!=2 && enthusiasm!=3 && enthusiasm!=4) || (commitment!=1 && commitment!=2 && commitment!=3 && commitment!=4))
        {
            custom_alert_OK_only('Please complete mandatory information before signing this form');
            return false;
        }*/

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
    <div class="Title">Employer Agreement</div>
    <div class="ButtonBar">
        <?php //if($source==2 or $source==3)
        /*if(isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER)
            if($form->signature_employer_font=='')
                echo '<button onclick="save();">Save</button>';
            else
            {
                //echo '<button onclick="window.location.href=' . $_SESSION['bc']->getPrevious() .'">Close</button>';
            }*/


        //elseif($source==1 && ($form->signature_assessor_font=="" && $form->signature_assessor_name=="" && $form->signature_assessor_date==""))
        // echo '<button onclick="save();">Save</button>';
        if($form->signature_employer_font=='')
            echo '<button onclick="save();">Save</button>';

        ?>
    </div>
    <div class="ActionIconBar"></div>
</div>

<form name="form1" id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="employer_id" value="<?php echo $form->employer_id ?>" />
<input type="hidden" name="_action" value="save_employer_agreement_form" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="source" value="<?php echo $source; ?>" />
<input type="hidden" name="signature_assessor_font" id="signature_assessor_font" value="<?php echo $form->signature_assessor_font; ?>" />
<input type="hidden" name="signature_employer_font" id="signature_employer_font" value="<?php echo $form->signature_employer_font; ?>" />
<input type="hidden" name="autosave" id="autosave" value="1" />


<table style="width: 900px">
    <tr>
        <td>
            <table class="table1">
                <thead>
                <th style="width: 900px">&nbsp;&nbsp;&nbsp;Apprenticeship Training Services Agreement</th>
                </thead>
            </table>
        </td>
<!--        <td>
            <?php   //if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo' || DB_NAME=='am_demo')
           // echo '<img height = "100" width = "80" src="/images/logos/' . SystemConfig::getEntityValue($link, "logo") . '">';
        //else
            //echo '<img height = "100" width = "80" src="images/sunesislogo.gif">';
            ?>
        </td> -->
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Details</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="width: 600px; text-align: center">&nbsp;</td>
                    <td style="width: 300px; text-align: center">Over 3 million</td>
                    <td style="width: 300px; text-align: center">Under 3 million</td>
                </tr>
                <tr>
                    <td style="width: 600px; text-align: left">Employer Annual PAYE bill:</td>
                    <td style="width: 300px; text-align: center"><?php echo HTML::radio("paye_bill", 1, ($form->paye_bill==1)?true:false, true, false); ?></td>
                    <td style="width: 300px; text-align: center"><?php echo HTML::radio("paye_bill", 2, ($form->paye_bill==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="width: 600px; text-align: center">&nbsp;</td>
                    <td style="width: 300px; text-align: center">49 or fewer</td>
                    <td style="width: 300px; text-align: center">50 or more</td>
                </tr>
                <tr>
                    <td style="width: 600px; text-align: left">Company Size (No of Employees):</td>
                    <td style="width: 300px; text-align: center"><?php echo HTML::radio("company_size", 1, ($form->company_size==1)?true:false, true, false); ?></td>
                    <td style="width: 300px; text-align: center"><?php echo HTML::radio("company_size", 2, ($form->company_size==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    <td colspan=1>&nbsp;This agreement is dated </td>
    <td colspan=3 style="text-align: left">
        <?php   if($source==1)
        echo HTML::datebox("meeting_date", $form->meeting_date, true, false);
    else
        echo $form->meeting_date;
        ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
        <td>Employer:</td>
        <td><input type="text" name="employer" value="<?php echo $employer_name; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Company Number:</td>
        <td><input type="text" name="company_number" value="<?php echo $form->company_number; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Employer's Address:</td>
        <td><input type="text" name="employer_address" value="<?php echo $employer_address; ?>" size=90/></td>
    </tr>
    <tr>
        <td colspan=4><b>Employer's representative</b></td>
    </tr>
    <tr>
        <td>Name:</td>
        <td><input type="text" name="employer_name" value="<?php echo $form->employer_name; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Title:</td>
        <td><input type="text" name="employer_title" value="<?php echo $form->employer_title; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><input type="text" name="employer_email" value="<?php echo $form->employer_email; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Telephone:</td>
        <td><input type="text" name="employer_telephone" value="<?php echo $form->employer_telephone; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Contact Address (If different from above):</td>
        <td><input type="text" name="employer_postal_address" value="<?php echo $form->employer_postal_address; ?>" size=90/></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;TRAINING PROVIDER DETAILS</th>
    </thead>
    <tbody>
    <tr>
        <td>Training Provider:</td>
        <td><input type="text" name="provider" value="<?php echo $form->provider; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Company Number:</td>
        <td><input type="text" name="provider_company_number" value="<?php echo $form->provider_company_number; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Address:</td>
        <td><input type="text" name="provider_address" value="<?php echo $form->provider_address; ?>" size=90/></td>
    </tr>
    <tr>
        <td>UKPRN:</td>
        <td><input type="text" name="ukprn" value="<?php echo $form->ukprn; ?>" size=90/></td>
    </tr>
    <tr>
        <td>VAT Number:</td>
        <td><input type="text" name="vat" value="<?php echo $form->vat; ?>" size=90/></td>
    </tr>
    <tr>
        <td colspan=4><b>Training Provider's contact</b></td>
    </tr>
    <tr>
        <td>Name:</td>
        <td><input type="text" name="provider_name" value="<?php echo $form->provider_name; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Title:</td>
        <td><input type="text" name="provider_title" value="<?php echo $form->provider_title; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><input type="text" name="provider_email" value="<?php echo $form->provider_email; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Telephone:</td>
        <td><input type="text" name="provider_telephone" value="<?php echo $form->provider_telephone; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Full Postal Address:</td>
        <td><input type="text" name="provider_postal_address" value="<?php echo $form->provider_postal_address; ?>" size=90/></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Specific Terms</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="width: 600px; text-align: center">Tick the required option:</td>
                    <td style="width: 300px; text-align: center">Fixed: This agreement is for the Apprenticeship Programme</td>
                    <td style="width: 300px; text-align: center">Multiple: This agreement is for multiple Apprenticeship Programmes</td>
                </tr>
                <tr>
                    <td style="width: 600px; text-align: left">Fixed or Multiple Apprenticeship Programmes:</td>
                    <td style="width: 300px; text-align: center"><?php echo HTML::radio("fixed_multiple", 1, ($form->fixed_multiple==1)?true:false, true, false); ?></td>
                    <td style="width: 300px; text-align: center"><?php echo HTML::radio("fixed_multiple", 2, ($form->fixed_multiple==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td>Training Provider Administration Service:<br><br>Tick this box if the Training Provider will be completing on-line administrative tasks on behalf of the Employer</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::checkbox("administration_service", 1, ($form->administration_service==1)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td>Complaints Procedure:<br><br>Tick this box for the Training Provider's Complaints Procedure to have effect and take priority over the process</td>
                    <td style="width: 200px; text-align: center"><?php echo HTML::checkbox("complaints", 1, ($form->complaints==1)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
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
        <td>SIGNED on behalf of the TRAINING PROVIDER:</td>
        <?php   if($form->signature_assessor_font!='')
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = ' . str_replace(" ","%20",$form->signature_assessor_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input name="signature_assessor_name" type="text" size=30 value="<?php echo $form->signature_assessor_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_assessor_date", $form->signature_assessor_date, false, false); ?> </td>
    </tr>
    <tr>
        <td>SIGNED on behalf of the EMPLOYER:</td>
        <?php   if($form->signature_employer_font!='')
        echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = ' . str_replace(" ","%20",$form->signature_employer_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input name="signature_employer_name" type="text" size=30 value="<?php echo $form->signature_employer_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_employer_date", $form->signature_employer_date, false, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>

<table style="width: 900px">
    <tr>
        <td colspan=2 style="text-align: center;"><b>Contract Terms</b></td>
    </tr>
    <tr>
        <td><b>1.</b></td>
        <td style="text-align: justify;"><b>Definitions and Interpretation</b></td>
    </tr>
    <tr>
        <td style="vertical-align: top"><br>1.1</td>
        <td style="text-align: justify;"><br>The definitions and rules of interpretation in this clause apply to this agreement:</td>
    </tr>
</table>




<br><br><br><br><br><br><br><br>

<table style="width: 900px">
<tr>
    <td colspan=2 style="text-align: center;"><b>Payment Guidelines</b></td>
</tr>
<tr>
    <td style="vertical-align: top"><br>1.</td>
    <td style="text-align: justify;"><br>All Levy paying organisations will commit to paying the detailed funds directly via their DAS account.  A cohort request is to be sent to the training provider using <b>UKPRN-10004577</b> on agreement of a learner being accepted on to the apprenticeship programme.</td>
</tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;DAS contact for double locking purposes </th>
    </thead>
    <tbody>
    <tr>
        <td>Contact Name:</td>
        <td><input type="text" name="contact_name" value="<?php echo $form->contact_name; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Contact telephone number:</td>
        <td><input type="text" name="contact_telephone" value="<?php echo $form->contact_telephone; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Contact email address:</td>
        <td><input type="text" name="contact_email" value="<?php echo $form->contact_email; ?>" size=90/></td>
    </tr>
    </tbody>
</table>
<br>

<table style="width: 900px">
    <tr>
        <td style="vertical-align: top"><br>2.</td>
        <td style="text-align: justify;"><br>Non levy organisations that are paying for 10% of the apprenticeship training or levy organisations that are now contributing towards their apprenticeship training are to agree to the below terms of payment:</td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Non-Funded payment terms </th>
    </thead>
    <tbody>
    <tr>
        <td>A</td>
        <td>One Off Initial Payment  - this is the only option for training charges under &pound;175</td>
    </tr>
    <tr>
        <td>B</td>
        <td>6 month Instalment Plan for total charges &pound;200 - &pound;800 - Please complete a separate Direct Debit Form</td>
    </tr>
    <tr>
        <td>C</td>
        <td>12 month Instalment Plan for total charges &pound;1500+ - Please complete a separate Direct Debit Form</td>
    </tr>
    </tbody>
</table>
<br>

<table style="width: 900px">
    <tr>
        <td style="vertical-align: top"><br>&nbsp;</td>
        <td style="text-align: justify;"><br>All invoices will be raised in a timely manner by the training provider and sent to the below invoicing address:</td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Invoicing details</th>
    </thead>
    <tbody>
    <tr>
        <td>Employer address for invoicing:</td>
        <td><input type="text" name="invoice_address" value="<?php echo $form->invoice_address; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Employer contact for invoicing matters:</td>
        <td><input type="text" name="invoice_contact" value="<?php echo $form->invoice_contact; ?>" size=90/></td>
    </tr>
    </tbody>
</table>
<br>

<table style="width: 900px">
    <tr>
        <td style="vertical-align: top"><br>3.</td>
        <td style="text-align: justify;"><br>The below BACS details must be completed in order to ensure fast and efficient payment of any incentive monies owed:</td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer BACS Details (applicable only where Government incentive payments due)</th>
    </thead>
    <tbody>
    <tr>
        <td>Bank/ Building Society Name:</td>
        <td><input type="text" name="bank_name" value="<?php echo $form->bank_name; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Bank Address:</td>
        <td><input type="text" name="bank_address" value="<?php echo $form->bank_address; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Account Name:</td>
        <td><input type="text" name="account_name" value="<?php echo $form->account_name; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Account Number:</td>
        <td><input type="text" name="account_number" value="<?php echo $form->account_number; ?>" size=90/></td>
    </tr>
    <tr>
        <td>Bank Sort Code:</td>
        <td><input type="text" name="sort_code" value="<?php echo $form->sort_code; ?>" size=90/></td>
    </tr>
    </tbody>
</table>
<br>






















































































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