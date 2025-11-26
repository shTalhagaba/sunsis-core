<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualification</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>

<!-- CSS for Controls -->
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/treeview/assets/skins/sam/treeview.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/calendar/assets/skins/sam/calendar.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/container/assets/container.css">

<!-- CSS for Menu -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/menu/assets/skins/sam/menu.css">

<!-- CSS for TabView -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">


<!-- Dependency source files -->

<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

<!-- Menu source file -->

<script type="text/javascript" src="/yui/2.4.1/build/menu/menu.js"></script>


<!-- Page-specific script -->
<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/animation/animation.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.css" />





<!-- Initialise calendar popup -->
<script language="JavaScript">
    var calPop = new CalendarPopup("calPop1");
    calPop.showNavigationDropdowns();
    document.write(getCalendarStyles());
</script>

<script language="JavaScript">

function changeCRMContracts(id)
{
    window.location.replace('do.php?id='+id+'&_action=edit_crm_contract');
}

</script>
<script type="text/javascript" src="/scripts/edit_crm_subjects.js?n=7"></script>
<style type="text/css">
.ygtvitem
{
}

dl.accordion-menu dd.a-m-d .bd{
    padding:0.5em;
    border:none 1px #ffc5ef;
    background-color: transparent;
    margin: 0px 5px 10px 5px;
    font-style: italic;
    color: navy;
    text-align: justify;

}

#unitCanvas
{
    width: 0px;
    height: 0px;
    border: 1px solid black;
    margin-left: 10px;
    padding-top: 10px;
    overflow: scroll;

}

#fieldsBox
{
    width: 650px;
    min-height: 200px;
    border: 1px solid black;
    margin: 5px 0px 10px 10px;
}

#elementFields
{
    width: 650px;
    min-height: 200px;
    border: 1px solid black;
    margin: 10px 10px 10px 10px;
    overflow: scroll;
}

#unitFields, #unitsFields
{
    display:none;
    padding: 10px;
}

#unitFields > h3, #unitsFields > h3
{
    margin-top: 5px;
}

div.Units
{
    margin: 3px 10px 3px 20px;
    border: 1px orange dotted;
    padding: 1px 1px 10px 1px;
    background-color: white;

    min-height: 100px;
}

div.elementsContainer
{
    width: 650px;
    min-height: 200px;
    border: 1px solid black;
    margin: 10px 10px 10px 10px;
}


div.Elements
{
    margin: 3px 10px 3px 20px;
    border: 1px orange dotted;
    padding: 1px 1px 10px 1px;
    background-color: white;

    min-height: 100px;
}

div.evidence
{
    margin: 3px 10px 3px 20px;
    padding: 1px 1px 10px 1px;
    background-color: white;
}

div.elementsBox
{
    margin: 3px 10px 3px 20px;
    border: 2px orange dotted;
    padding: 1px 1px 10px 1px;
    background-color: white;
    margin: 10px 10px 10px 10px;
    min-height: 100px;
}

div.UnitsTitle
{
    font-size: 12pt;
    font-weight: bold;
    color: #395596;
    cursor: default;
    padding: 2px;
    margin: 0px;
}

div.ElementsTitle
{
    font-size: 12pt;
    font-weight: bold;
    color: #395596;
    cursor: default;
    padding: 2px;
    margin: 0px;
}


div.Root
{
    margin: 3px 10px 3px 20px;
    border: 3px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    background-color: #395596;
    color: white;
    min-height: 20px;
    width: 35em;
    font-weight: bold;
}

div.UnitGroup
{
    margin: 3px 10px 3px 20px;
    border: 3px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    background-color: #EE9572;
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.Unit
{
    margin: 3px 10px 3px 20px;
    border: 2px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    /*background-color: #F3B399;*/
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.ElementGroup
{
    margin: 3px 10px 3px 20px;
    border: 1px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    background-color: #F8D0C1;
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.Element
{
    margin: 3px 10px 3px 20px;
    border: 1px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    /*background-color: #FCEEE8;*/
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.Evidence
{
    margin: 3px 10px 3px 20px;
    border: 1px silver dotted;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    /*background-color: #FDF1E2; */
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.UnitTitle
{
    margin: 2px;
    padding: 2px;
    cursor: default;
    font-weight: bold;
    /* background-color: #FDE3C1; */
    -moz-border-radius: 5pt;
}

div.UnitDetail
{
    margin-left:5px;
    margin-bottom:5px;
    display: none;
    /*width: 500px;*/
}

div.UnitDetail p
{
    margin: 0px 5px 10px 5px;
    font-style: italic;
    color: navy;
    text-align: justify;
}

.bdx
{
    margin: 0px 5px 10px 5px;
    font-style: italic;
    color: navy;
    /*		text-align: justify; */
    /*		padding: 0px; */
    border-style: none;
}

div.UnitDetail p.owner
{
    text-align:right;
    font-style:normal;
    font-weight:bold;
}

td.greenl
{
    background-image:url('/images/trafficlight-green.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.2;
    filter: alpha(opacity=20);
}

td.redl
{
    background-image:url('/images/trafficlight-red.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.2;
    filter: alpha(opacity=20);
}

td.yellowl
{
    background-image:url('/images/trafficlight-yellow.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.2;
    filter: alpha(opacity=20);
}

td.greend
{
    background-image:url('/images/trafficlight-green.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 1;
    filter: alpha(opacity=100);
}

td.redd
{
    background-image:url('/images/trafficlight-red.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 1;
    filter: alpha(opacity=100);
}

td.yellowd
{
    background-image:url('/images/trafficlight-yellow.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 1;
    filter: alpha(opacity=100);
}

</style>

</head>
<body style="font-size: 75%" class="yui-skin-sam">
<div class="banner">
    <div class="Title">CRM Subjects</div>
    <div class="ButtonBar">
        <button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
    </div>
    <div class="ActionIconBar">
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div id="demo" class="yui-navset">
	<span class="button" onclick="newCRMSubject();">New</span>

	<?php if($_SESSION['user']->isAdmin())
{
    $sql = "SELECT id FROM lookup_crm_subject";
    $st = $link->query($sql);
    $markedEvents = array();
    while($row = $st->fetch())
    {
        $markedEvents[] = $row['id'];
    }

 //   echo '<span class="button" onclick="addNew()"> Add New </span>';
 //   echo '<span class="button" onclick="save()"> &nbsp; Save &nbsp; </span>';
//    echo '<span class="button" onclick="delete_events()" > &nbsp; Delete &nbsp; </span>';
// Compliance Data
    $sql = <<<HEREDOC
SELECT
	id, description, (SELECT GROUP_CONCAT(title) FROM contracts INNER JOIN crm_subjects_contracts ON crm_subjects_contracts.`contract_id` = contracts.id WHERE lookup_crm_subject.id = crm_subjects_contracts.`contract_id` GROUP BY contract_id ) AS contracts
FROM
lookup_crm_subject  ORDER BY description;
HEREDOC;

    $st = $link->query($sql);
    if($st)
    {
        $c=0;
        echo '<table id="events" class="resultset" border="0" cellspacing="0" cellpadding="6">';
	    echo '<thead><tr><th>CRM Subject</th><th>Attach to Contracts</th><th>Edit CRM Subject Title</th></tr></thead>';
        echo '<tbody>';
        $ids = array();
//		while($row = $st->fetch())
        while($row = $st->fetch())
        {
            $did = $row['id'];
            $app= '';
            $non_app = '';
            echo '<tr>';
            echo '<td align="left"><input onchange="invertColour(this)" size="50" name = "txt" id="' . $did . '" type="text" value="' . htmlspecialchars((string)$row['description']) . '"</td>';
            //echo '<td align="left"><input readonly onchange="invertColour(this)" size="50" name = "contracts" id="contracts" type="text" value="' . $row['contracts'] . '"</td>';
            echo '<td align="center"><span class="button" onclick="changeCRMContracts('.$did.')">Attach</span>';
	        echo '<td align="center"><span class="button" onclick="editCRMSubjectTitle('.$did.')">Edit</span>';
            echo '</tr>';
        }
        echo '</table>';
    }

}

?>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>