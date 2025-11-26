<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Qualification</title>
    <link rel="stylesheet" href="/common.css" type="text/css" />
    <link rel="stylesheet" href="/print.css" media="print" type="text/css" />
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

    <!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
    <?php // #186 {0000000204} - removed separate file references 
	?>

    <!-- CSS for Controls -->
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/treeview/assets/skins/sam/treeview.css">
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/calendar/assets/skins/sam/calendar.css">
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/container/assets/container.css">

    <!-- CSS for Menu -->

    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/menu/assets/skins/sam/menu.css">

    <!-- CSS for TabView -->

    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

    <link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css" />
    <script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
    <script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
    <script language="JavaScript" src="/common.js"></script>

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

    <script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js"></script>
    <script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

    <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.css" />



    <script type="text/javascript">
    YAHOO.namespace("am.scope");
    //var oTreeView,      // The YAHOO.widget.TreeView instance
    //var oContextMenu,       // The YAHOO.widget.ContextMenu instance
    //oTextNodeMap = {},      // Hash of YAHOO.widget.TextNode instances in the tree
    //oCurrentTextNode = null;    // The YAHOO.widget.TextNode instance whose "contextmenu" DOM event triggered the display of the context menu
    oTextNodeMap = {};
    tree = null;
    root = null;
    mytabs = null;
    tags = new Array();
    tagcount = 0;
    xml = "<root>";

    // Get evidences through ajax
    var request = ajaxBuildRequestObject();
    request.open("GET", expandURI('do.php?_action=ajax_get_evidence_types'), false);
    request.setRequestHeader("x-ajax", "1"); // marker for server code
    request.send(null);

    arr = new Array();
    arr[0] = "";
    if (request.status == 200) {
        var xml = request.responseXML;
        var xmlDoc = xml.documentElement;

        if (xmlDoc.tagName != 'error') {
            for (var i = 0; i < xmlDoc.childNodes.length; i++) {
                arr[i + 1] = xmlDoc.childNodes[i].childNodes[0].nodeValue;
            }
        }
    }

    function deleteRecord() {
        <?php if (DB_NAME == "am_doncaster" || DB_NAME == "am_siemens" || DB_NAME == "am_siemens_demo") { ?>
        if (window.confirm("Delete this provider?")) {
            window.location.replace('do.php?_action=delete_provider&id=<?php echo $vo->id; ?>');
        }
        <?php } else { ?>
        alert('Please contact Sunesis Support to delete this record.');
        <?php } ?>
    }

    /**
     * Create a new Document object. If no arguments are specified,
     * the document will be empty. If a root tag is specified, the document
     * will contain that single root tag. If the root tag has a namespace
     * prefix, the second argument must specify the URL that identifies the
     * namespace.
     */
    function uploadFile() {
        var myForm = document.forms[1];
        myForm.submit();
    }


    function treeInit() {


        myTabs = new YAHOO.widget.TabView("demo");
        graph();
    }


    YAHOO.util.Event.onDOMReady(treeInit);
    </script>


    <script language="JavaScript">
    var elements_counter = 0;
    var oldReference = '';
    var unitTitleElement = '';

    evidence_methods = new Array();
    evidence_types = new Array();
    evidence_categories = new Array();


    function displayLearner() {
        if (document.getElementById('learner').style.display == 'none')
            document.getElementById('learner').style.display = 'block';
        else
            document.getElementById('learner').style.display = 'none';
    }




    function editLessons(event) {
        var myForm = document.forms[0];
        var buttons = myForm.elements['contract'];

        id = buttons[buttons.selectedIndex].value;

        if (id == '') {
            alert("Please select an ILR");
            return false;
        } else {

            values = id.split("*");

            var contract_year = parseFloat(values[3]);

            //		var contract_year = parseFloat(values[3])-2000;

            /*		var next_year = contract_year+1;

            		if(contract_year<10)
            			contract_year = '0'+contract_year;
            			
            		if(next_year<10)
            			next_year = '0'+next_year;

            		contract_year = contract_year + '' + next_year;
            */
            window.location.href = ('do.php?_action=edit_ilr' + contract_year + '&submission=' + values[0] +
                '&contract_id=' + values[1] + '&tr_id=' + values[2] + '&L03=' + values[4]);
        }
    }


    function editILP(event) {
        var myForm = document.forms[1];
        var buttons = myForm.elements['ilpqualification'];

        id = buttons[buttons.selectedIndex].value;

        if (id == '') {
            alert("Please select an Qualification");
            return false;
        } else {

            values = id.split("*");

            window.open('do.php?_action=ttg_ilp&qualification_id=' + values[0] + '&framework_id=' + values[1] +
                '&tr_id=' + values[2] + '&internaltitle=' + values[3]);
        }
    }


    function init() {

        myTabs = new YAHOO.widget.TabView("demo");
        // document.getElementById('achieved').style.width = ;

        /*	sd = <?php //echo '"' . $start_date . '"' 
        			?>;
        	ed = <?php ///echo '"' . $end_date . '"' 
        			?>;

        	start_date = new Date;
        	end_date = new Date;

        	start_date.setDate(parseInt(sd.substr(0,2)));
        	start_date.setMonth(parseInt(sd.substr(3,2)));
        	start_date.setFullYear(parseInt(sd.substr(6,4)));
        	end_date.setDate(parseInt(ed.substr(0,2)));
        	end_date.setMonth(parseInt(ed.substr(3,2)));
        	end_date.setFullYear(parseInt(ed.substr(6,4)));

        	
        	 var ONE_DAY = 1000 * 60 * 60 * 24
            // Convert both dates to milliseconds
            var date1_ms = start_date.getTime()
            var date2_ms = end_date.getTime()
            // Calculate the difference in milliseconds
            var difference_ms = Math.abs(date1_ms - date2_ms)
            // Convert back to days and return
            no_of_days = Math.round(difference_ms/ONE_DAY);
        */

        /*	td = document.createElement('TD');
        	td.innerHTML = "<td width='50px'> dsfdsfdf</td>";
        	document.getElementById("targets").appendChild(td);
        */





    }

    function showHideAttendance(visible) {
        var table = document.getElementById('trainingRecordsTable');

        var headers = table.getElementsByTagName('th');
        var cells = table.getElementsByTagName('td');

        for (var i = 0; i < headers.length; i++) {
            if (headers[i].className.indexOf('AttendanceStatistic') > -1) {
                if (visible == null) {
                    showHideBlock(headers[i]);
                } else {
                    showHideBlock(headers[i], visible);
                }
            }
        }


        for (var i = 0; i < cells.length; i++) {
            if (cells[i].className.indexOf('AttendanceStatistic') > -1) {
                if (visible == null) {
                    showHideBlock(cells[i]);
                } else {
                    showHideBlock(cells[i], visible);
                }
            }
        }
    }

    function showHideProgress(visible) {
        var table = document.getElementById('trainingRecordsTable');

        var headers = table.getElementsByTagName('th');
        var cells = table.getElementsByTagName('td');

        for (var i = 0; i < headers.length; i++) {
            if (headers[i].className.indexOf('ProgressStatistic') > -1) {
                if (visible == null) {
                    showHideBlock(headers[i]);
                } else {
                    showHideBlock(headers[i], visible);
                }
            }
        }

        for (var i = 0; i < cells.length; i++) {
            if (cells[i].className.indexOf('ProgressStatistic') > -1) {
                if (visible == null) {
                    showHideBlock(cells[i]);
                } else {
                    showHideBlock(cells[i], visible);
                }
            }
        }
    }

    function showComments(s) {
        s.title = document.getElementById("comments" + s.id).value;
        showHideBlock(document.getElementById("comments" + s.id));
    }

    function showComplianceComments(s) {
        s.title = document.getElementById("compliancecomments" + s.id).value;
        showHideBlock(document.getElementById("compliancecomments" + s.id));
    }

    function entry_onclick(radio) {
        var td = radio.parentNode;
        var tr = td.parentNode;

        var inputs = tr.getElementsByTagName("td");

        for (var i = 5; i < 8; i++) {
            if (inputs[i].tagName == 'TD') {
                if (inputs[i].className == 'redd')
                    inputs[i].className = 'redl';

                if (inputs[i].className == 'greend')
                    inputs[i].className = 'greenl';

                if (inputs[i].className == 'yellowd')
                    inputs[i].className = 'yellowl';
            }
        }

        if (td.className == 'redl')
            td.className = 'redd';

        if (td.className == 'greenl')
            td.className = 'greend';

        if (td.className == 'yellowl')
            td.className = 'yellowd';
    }

    var existingEvents = <?php echo $eventsResultSet->rowCount(); ?>;

    var courseString = <?php echo HTML::select('courseForNewRow0123456789', $course_select, '', true, false); ?>;
    //courseString = courseString.replace(/(\r\n|\n|\r)/gm,"");

    function addNew() {
        existingEvents++;
        oTable = document.getElementById("events");
        var oRow = oTable.insertRow(-1);
        var oCell = oRow.insertCell(-1);
        oCell.align = 'center';
        oCell.vAlign = 'middle';
        //oCell.innerHTML = '<img height="80%" width = "80%" src="/images/event.jpg" />';
        oCell.innerHTML = '&nbsp;';

        var oCell = oRow.insertCell(-1);
        oCell.align = "center";
        oCell.vAlign = "middle";
        oCell.innerHTML =
            '<input onchange="invertColour(this)" size="50" type="text" name="txt" value="" style="background-color: lightgreen;"/>';

        var oCell = oRow.insertCell(-1);
        oCell.align = "center";
        oCell.vAlign = "middle";
        oCell.innerHTML = courseString;
        document.getElementById('courseForNewRow0123456789').id = 'courseForNewRow' + existingEvents;

        var oCell = oRow.insertCell(-1);
        oCell.align = "center";
        oCell.vAlign = "middle";
        oCell.innerHTML = 'App<input type="radio" checked value="2" id="trafficnew"' + existingEvents +
            ' name="trafficnew"' + existingEvents + ' title="Apprenticeship" />';

        var oCell = oRow.insertCell(-1);
        oCell.align = "center";
        oCell.vAlign = "middle";
        oCell.innerHTML = 'Other<input type="radio"  value="2" name="trafficnew"' + existingEvents +
            ' title="NonApp" />';

        var oCell = oRow.insertCell(-1);
        oCell.align = "center";
        oCell.vAlign = "middle";
        oCell.innerHTML = '&nbsp;';

        //	table.appendChild(tr);

    }

    function changeColour() {
        for (a = 0; a < txt.length; a++) {
            txt[a].style.background = 'white';
        }
    }

    function invertColour(obj) {
        obj.style.background = 'lightgreen';
    }

    function save() {
        var inputs = document.getElementsByTagName('input');
        var selects = document.getElementsByTagName('select');
        var selectsArray = new Array();
        for (var x = 0; x < selects.length; x++) {
            if (selects.item(x).name.match(/^course/)) {
                selectsArray.push(selects.item(x).options[selects.item(x).selectedIndex].value);
            }
        }
        xml = '<events>';
        var counter = 0;
        for (var i = 0; i < inputs.length; i++) {
            if (inputs.item(i).getAttribute('name') == 'txt') {
                xml += '<event><event_id>' + inputs.item(i).id + '</event_id><title>' + htmlspecialchars(inputs.item(i)
                    .value) + '</title>';
                xml += '<course_id>' + selectsArray[counter] + '</course_id>';
                counter++;
            }

            if (inputs.item(i).type == 'radio') {
                if (inputs.item(i).title == "NonApp") {
                    if (inputs.item(i).checked) {
                        xml += '<type>1</type></event>';
                    } else {
                        xml += '<type>2</type></event>';
                    }
                }
            }
        }
        xml += '</events>';

        var postData = 'provider_id=' + <?php echo $id; ?> +
            '&xml=' + encodeURIComponent(xml);

        var request = ajaxRequest('do.php?_action=save_provider_events', postData);

        if (request.status == 200) {
            //changeColour();
            alert("Event template has been saved and applied to all learners");
            location.reload();
        } else {
            alert(request.responseText);
        }
    }

    function delete_events() {
        var selectedEvents = document.getElementsByName("selectEvent");
        var selectedEventsLength = selectedEvents.length;
        var noneSelected = true;
        xml = '<events>';
        for (var k = 0; k < selectedEventsLength; k++) {
            if (selectedEvents[k].checked) {
                xml += '<event><event_id>' + selectedEvents[k].value + '</event_id></event>';
                noneSelected = false;
            }
        }
        xml += '</events>';
        if (noneSelected) {
            alert("Select the event(s) to delete");
            return;
        }
        //alert(xml);return;
        if (window.confirm("Delete the event(s)?")) {
            var postData = 'provider_id=' + <?php echo $id; ?> +
                '&xml=' + encodeURIComponent(xml);

            var request = ajaxRequest('do.php?_action=delete_provider_events', postData);

            if (request.status == 200) {
                //changeColour();
                alert("Selected Event template(s) are removed ");
                location.reload();
            } else {
                alert(request.responseText);
            }
        } else
            return;

    }

    function save_referral_sources(form_name) {
        if (!confirm('Are you sure?')) {
            return;
        }

        var myForm = document.forms[form_name];

        var indexValue = myForm.elements['indexValue'].value;

        var new_record = myForm.elements['new_record'].value;

        if (indexValue == 0) {
            alert("There are no referral sources.");
            return;
        }

        var referral_sources = "<sources>";
        if (new_record == 0) {
            for (var i = 0; i < indexValue; i++) {
                if (myForm.elements['description' + i].value != '') {
                    referral_sources += '<source>';

                    referral_sources += '<id>';
                    referral_sources += myForm.elements['rid' + i].value;
                    referral_sources += '</id>';

                    referral_sources += '<description>';
                    referral_sources += myForm.elements['description' + i].value;
                    referral_sources += '</description>';

                    referral_sources += '</source>';
                }
            }
        } else {
            var myForm = document.forms[form_name];
            var message = "";
            if (myForm.elements['description'].value == '')
                message = "Please enter the description";
            if (message != "") {
                alert(message);
                return false;
            }

            referral_sources += '<source>';

            referral_sources += '<description>';
            referral_sources += myForm.elements['description'].value;
            referral_sources += '</description>';

            referral_sources += '</source>';
        }
        referral_sources += "</sources>";

        var postData = 'training_provider_id=' + <?php echo $id; ?> +
            '&indexValue=' + indexValue +
            '&new_record=' + new_record +
            '&referral_sources=' + encodeURIComponent(referral_sources);

        var request = ajaxRequest('do.php?_action=ajax_save_lookup_ref_sources', postData);

        if (request) {
            alert("Lookup Referral Source Saved.");
            window.location.reload(true);
        }

    }


    function changeRefSource(id) {
        var postData = 'id=' + id;

        var request = ajaxRequest('do.php?_action=ajax_edit_lookup_ref_sources', postData);

        if (request) {
            alert("Record updated.");
            window.location.reload(true);
        }

    }
    </script>
    <script>
    $(function() {
        $("#dialog").dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            width: 700
        });

        $("#opener").click(function() {
            $("#dialog").dialog("open");
        });

        $("#closer").click(function() {
            save_referral_sources('frm_ref_sources_new'),
                $("#dialog").dialog("close");
        });
    });
    </script>
    <style type="text/css">
    .ygtvitem {}

    dl.accordion-menu dd.a-m-d .bd {
        padding: 0.5em;
        border: none 1px #ffc5ef;
        background-color: transparent;
        margin: 0px 5px 10px 5px;
        font-style: italic;
        color: navy;
        text-align: justify;

    }

    #unitCanvas {
        width: 0px;
        height: 0px;
        border: 1px solid black;
        margin-left: 10px;
        padding-top: 10px;
        overflow: scroll;

    }

    #fieldsBox {
        width: 650px;
        min-height: 200px;
        border: 1px solid black;
        margin: 5px 0px 10px 10px;
    }

    #elementFields {
        width: 650px;
        min-height: 200px;
        border: 1px solid black;
        margin: 10px 10px 10px 10px;
        overflow: scroll;
    }

    #unitFields,
    #unitsFields {
        display: none;
        padding: 10px;
    }

    #unitFields>h3,
    #unitsFields>h3 {
        margin-top: 5px;
    }

    div.Units {
        margin: 3px 10px 3px 20px;
        border: 1px orange dotted;
        padding: 1px 1px 10px 1px;
        background-color: white;

        min-height: 100px;
    }

    div.elementsContainer {
        width: 650px;
        min-height: 200px;
        border: 1px solid black;
        margin: 10px 10px 10px 10px;
    }


    div.Elements {
        margin: 3px 10px 3px 20px;
        border: 1px orange dotted;
        padding: 1px 1px 10px 1px;
        background-color: white;

        min-height: 100px;
    }

    div.evidence {
        margin: 3px 10px 3px 20px;
        padding: 1px 1px 10px 1px;
        background-color: white;
    }

    div.elementsBox {
        margin: 3px 10px 3px 20px;
        border: 2px orange dotted;
        padding: 1px 1px 10px 1px;
        background-color: white;
        margin: 10px 10px 10px 10px;
        min-height: 100px;
    }

    div.UnitsTitle {
        font-size: 12pt;
        font-weight: bold;
        color: #395596;
        cursor: default;
        padding: 2px;
        margin: 0px;
    }

    div.ElementsTitle {
        font-size: 12pt;
        font-weight: bold;
        color: #395596;
        cursor: default;
        padding: 2px;
        margin: 0px;
    }


    div.Root {
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

    div.UnitGroup {
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

    div.Unit {
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

    div.ElementGroup {
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

    div.Element {
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

    div.Evidence {
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

    div.UnitTitle {
        margin: 2px;
        padding: 2px;
        cursor: default;
        font-weight: bold;
        /* background-color: #FDE3C1; */
        -moz-border-radius: 5pt;
    }

    div.UnitDetail {
        margin-left: 5px;
        margin-bottom: 5px;
        display: none;
        /*width: 500px;*/
    }

    div.UnitDetail p {
        margin: 0px 5px 10px 5px;
        font-style: italic;
        color: navy;
        text-align: justify;
    }

    .bdx {
        margin: 0px 5px 10px 5px;
        font-style: italic;
        color: navy;
        /*		text-align: justify; */
        /*		padding: 0px; */
        border-style: none;
    }

    div.UnitDetail p.owner {
        text-align: right;
        font-style: normal;
        font-weight: bold;
    }

    td.greenl {
        background-image: url('/images/trafficlight-green.jpg');
        background-color: white;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.2;
        filter: alpha(opacity=20);
    }

    td.redl {
        background-image: url('/images/trafficlight-red.jpg');
        background-color: white;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.2;
        filter: alpha(opacity=20);
    }

    td.yellowl {
        background-image: url('/images/trafficlight-yellow.jpg');
        background-color: white;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.2;
        filter: alpha(opacity=20);
    }

    td.greend {
        background-image: url('/images/trafficlight-green.jpg');
        background-color: white;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    td.redd {
        background-image: url('/images/trafficlight-red.jpg');
        background-color: white;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    td.yellowd {
        background-image: url('/images/trafficlight-yellow.jpg');
        background-color: white;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 1;
        filter: alpha(opacity=100);
    }
    </style>

</head>

<body style="font-size: 75%" class="yui-skin-sam">
    <div class="banner">
        <div class="Title"><?php echo $page_title ?></div>
        <div class="ButtonBar">
            <button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';">Close</button>
            <?php if ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == 8) { ?>
            <button
                onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_trainingprovider');">Edit</button>
            <?php } ?>
            <?php if ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == 11) { ?>
            <!-- 			<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_health_and_safety&back=read_trainingprovider');">Health & Safety</button> -->
            <?php } ?>
            <?php if ($_SESSION['user']->isAdmin()) { ?>
            <button onclick="deleteRecord();">Delete</button>
            <?php } ?>

            <?php if (DB_NAME == 'sunesis' || DB_NAME == 'am_midkent') { ?>
            <button onclick="window.open('sub_er_form.docx'); return false;">Review Form</button>
            <?php } ?>
        </div>
        <div class="ActionIconBar">
            <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16"
                    height="16" style="vertical-align:text-bottom" /></button>
            <button onclick="window.location.reload(false);"
                title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif"
                    width="16" height="16" style="vertical-align:text-bottom" /></button>
        </div>
    </div>

    <?php $_SESSION['bc']->render($link); ?>

    <div id="demo" class="yui-navset">
        <div align="left"
            style="font-size: 50px;padding: 15px;height: 50px;text-align: left;text-shadow: -4px 4px 3px #999, 1px -1px 2px #000;margin-top: 0;margin-bottom: 0;color: #395596;">
            <?php echo substr(htmlspecialchars((string)$vo->legal_name), 0, 39); ?>
        </div>
        <ul class="yui-nav">
            <li class="selected"><a href="#tab1"><em>Provider</em></a></li>
            <?php if (DB_NAME == 'am_lewisham')
				echo  '<li><a href="#tab2"><em>Department</em></a></li>';
			else
				echo  '<li><a href="#tab2"><em>Locations</em></a></li>';
			?>
            <li><a href="#tab3"><em>CRM Notes</em></a></li>
            <li><a href="#tab4"><em>System Users</em></a></li>
            <!--    <li><a href="#tab5"><em>Training Records</em></a></li> -->

            <?php if (SystemConfig::getEntityValue($link, "compliance") && ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_MANAGER)) {
				echo '<li><a href="#tab6"><em>Compliance Template</em></a></li>';
			}
			?>

            <li><a href="#tab7"><em>Qualifications</em></a></li>

            <?php if (SystemConfig::getEntityValue($link, "repository_employer")) {
				echo '<li><a href="#tab8"><em>File Repository</em></a></li>';
			}
			?>

            <?php if ((DB_NAME == "am_reed_demo" || DB_NAME == "am_reed") && $_SESSION['user']->isAdmin()) { ?>
            <li><a href="#tab9"><em>Referral Sources</em></a></li>
            <?php } ?>


        </ul>


        <div class="yui-content" style='background: white'>
            <div id="tab1">
                <p>

                <h3>Name</h3>
                <table border="0" cellspacing="4" cellpadding="4">
                    <col width="150" />
                    <tr>
                        <td class="fieldLabel">Legal name:</td>
                        <td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name); ?></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Trading name:</td>
                        <td class="fieldValue"><?php echo htmlspecialchars((string)$vo->trading_name); ?></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Abbreviation:</td>
                        <td class="fieldValue"><?php echo htmlspecialchars((string)$vo->short_name); ?></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Category:</td>
                        <td class="fieldValue">
                            <?php //echo htmlspecialchars((string)$lookup_org_type[$vo->org_type_id]); 
							?></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Company Number:</td>
                        <td class="fieldValue"><?php echo htmlspecialchars((string)$vo->company_number); ?></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">VAT Number:</td>
                        <td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vat_number); ?></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel"><abbr title="UK Provider Reference Number">UKPRN</abbr>:</td>
                        <td class="fieldValue"><?php if ($vo->ukprn != '') { ?><a href=""
                                onclick="document.forms['display_UKRLP_record'].submit();return false;"
                                title="Display provider's record in the UKRLP online database"><?php echo htmlspecialchars((string)$vo->ukprn); ?></a>
                            <img src="/images/external.png" /><?php } ?>
                        </td>
                    </tr>
                    <!-- 	<tr><td class="fieldLabel">UPIN:</td><td class="fieldValue"><?php //echo htmlspecialchars((string)$vo->upin); 
																							?></td></tr>
<tr>
		<td class="fieldLabel_compulsory" valign="top">Type:</td>
		<td class="fieldValue"><?php //echo HTML::checkboxGrid('level', $type_checkboxes, null, 3, false); 
								?></td>
	</tr> -->
                </table>

                <!-- Hidden form for displaying a provider's UKRLP record -->
                <form name="display_UKRLP_record" method="post"
                    action="http://www.ukrlp.co.uk/ukrlp/ukrlp_provider.page_pls_searchProviders" target="_blank">
                    <input type="hidden" name="pn_ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn); ?>" />
                    <input type="hidden" name="x" value="" />
                </form>

                </p>
            </div>

            <div id="tab2">
                <p>

                    <?php if (DB_NAME == 'am_lewisham')
						echo '<h3>Departments</h3>';
					else
						echo '<h3>Locations</h3>';
					?>
                    <?php if ($_SESSION['user']->type != 9 && $_SESSION['user']->type != 11 && $_SESSION['user']->type != 13) { ?>
                    <span class="button" style="margin-bottom: 15px;"
                        onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=<?php echo "trainingprovider"; ?>'">
                        Add new location </span>
                    <?php }
					$locations->render($link, 'read_trainingprovider'); ?>

                </p>
            </div>

            <div id="tab3">
                <p>

                <h3>CRM Notes</h3>
                <?php if ($_SESSION['user']->type != 13) { ?>
                <span class="button" style="margin-bottom: 15px;"
                    onclick="window.location.href='do.php?_action=edit_crm_note&organisations_id=<?php echo $vo->id; ?>&organisation_type=read_workplace'">
                    Add New Note </span>
                <?php }
				$view2->render($link, 'read_trainingprovider'); ?>


                </p>
            </div>


            <div id="tab4">
                <p>


                <h3>System Users</h3>
                <?php if (DB_NAME != "am_duplex") { ?>
                <?php if (SystemConfig::getEntityValue($link, "manager") && ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_MANAGER)) { ?>
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Manager"; ?>&people_type=<?php echo 8; ?> '">
                    Add manager </span>
                <?php } ?>
                <?php if ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_MANAGER) { ?>
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Admin"; ?>&people_type=<?php echo 1; ?> '">
                    Add administrator </span>
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Organisation Viewer"; ?>&people_type=<?php echo 13; ?> '">
                    Add organisation viewer </span>
                <?php if (!in_array(DB_NAME, ["am_presentation", "am_lead"])) { ?>
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Apprentice Coordinator"; ?>&people_type=<?php echo 20; ?> '">
                    Add Apprentice Coordinator </span>
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Supervisor"; ?>&people_type=<?php echo 9; ?> '">
                    Add supervisor </span>
                <?php } ?>
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "FS Tutor"; ?>&people_type=<?php echo 2; ?> '">
                    Add FS Tutor </span>
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Assessor"; ?>&people_type=<?php echo 3; ?> '">
                    Add assessor </span>
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "IQA"; ?>&people_type=<?php echo 4; ?> '">
                    Add IQA </span>
                <!-- <span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php //echo "Salesman"; 
																																							?>&people_type=<?php //echo 7; 
																																																	?> '"> Add sales person </span> -->
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "CRM User"; ?>&people_type=<?php echo 30; ?> '">
                    Add CRM User</span>

                <?php if (SystemConfig::getEntityValue($link, "workplace")) { ?>
                <span class="button"
                    onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Work Based Coordinator"; ?>&people_type=<?php echo 6; ?> '">
                    Add Work Experience Coordinator </span>
                <?php }
					}

					$vo5->render($link);
				} // endif(DB_NAME != "am_duplex")
				?>

                <?php if (DB_NAME == "am_duplex") {
					echo '<span class="button" onclick="window.location.href=\'do.php?_action=edit_user&organisations_id=' . $vo->id . '&people=Admin&people_type=1\'"> Add administrator </span>';
					$vo5->render($link);
				}
				?>

                </p>
            </div>

            <!-- <div id="tab5">
<p>

<h3>Training Records</h3>
<?php //$vo4->render($link); 
?>



</p>
</div>
-->

            <?php if (
				SystemConfig::getEntityValue($link, "compliance")
				&& ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_MANAGER)
			) { ?>

            <div id="tab6">
                <p>
                <h3>Event Templates</h3>
                <div style="margin: 5px 0;">
                    <span class="button" onclick="addNew()"> Add New </span>&nbsp;
                    <span class="button" onclick="save()"> &nbsp; Save &nbsp; </span>&nbsp;
                    <span class="button" onclick="delete_events()"> &nbsp; Delete &nbsp; </span>
                </div>

                <?php
					// Get marked events
					$sql = "SELECT event_id 
                    FROM student_events 
                    INNER JOIN events_template ON student_events.event_id = events_template.id 
                    GROUP BY student_events.event_id;";
					$st = $link->query($sql);
					$markedEvents = [];
					while ($row = $st->fetch()) {
						$markedEvents[] = $row['event_id'];
					}

					// Compliance data
					$sql = "SELECT * FROM events_template WHERE provider_id = '$id'";
					if ($eventsResultSet) {
						echo '<table id="events" class="resultset" border="0" cellspacing="0" cellpadding="6">';
						echo '<thead><tr><th>&nbsp;</th><th>Event Title</th><th>Course</th><th colspan=2>Programme Type</th><th>Marked for Learners</th></tr></thead>';
						echo '<tbody>';

						while ($row = $eventsResultSet->fetch()) {
							$did = $row['id'];
							$app = '';
							$non_app = '';

							echo in_array($row['id'], $markedEvents) ? '<tr>' : '<tr bgcolor="orange">';

							// Checkbox
							if (!in_array($row['id'], $markedEvents)) {
								echo '<td align="center"><input type="checkbox" name="selectEvent" value="' . $row['id'] . '" /></td>';
							} else {
								echo '<td>&nbsp;</td>';
							}

							// Title
							echo '<td align="left"><input onchange="invertColour(this)" size="50" name="txt" id="' . $did . '" type="text" value="' . $row['title'] . '" /></td>';

							// Course dropdown
							echo '<td align="center" width="40">' . HTML::select('course' . $did, $course_select, $row['course_id'], true, false) . '</td>';

							// Programme type
							if ($row['programme_type'] == '2') {
								$app = "checked";
							} else {
								$non_app = "checked";
							}
							echo '<td align="center" width="40">App <input type="radio" ' . $app . ' value="2" name="traffic' . $did . '" title="Apprenticeship" /></td>';
							echo '<td align="center" width="40">Other <input type="radio" ' . $non_app . ' value="2" name="traffic' . $did . '" title="NonApp" /></td>';

							// Marked for learners
							echo in_array($row['id'], $markedEvents)
								? '<td align="center">Yes</td>'
								: '<td align="center">No</td>';

							echo '</tr>';
						}

						echo '</tbody></table>';
					}
					?>
                </p>
            </div>
            <?php } ?>

            <div id="tab7">
                <p>

                    <?php if (SystemConfig::getEntityValue($link, "manager")) { ?>
                <h3>Qualification Database</h3>
                <?php if ($_SESSION['user']->isAdmin()) { ?>
                <span class="button"
                    onclick="window.location.href='do.php?_action=assign_qualifications&organisation_id=<?php echo $vo->id; ?>'">
                    Grant/ Revoke Qualifications </span>
                <?php } ?>
                <?php $qualifications->render($link); ?>
                <?php } ?>



                </p>
            </div>
            <div id="tab8">
                <p>

                    <?php
					// re - 29/02/2012 - change this to be a configuration table element
					// ---
					if (SystemConfig::getEntityValue($link, "repository_employer") && isset($html2)) {
						// if(DB_NAME=='am_lewisham' || DB_NAME=='ams' || DB_NAME=='am_motorvation' || DB_NAME=='am_pathway' || DB_NAME=='sunesis' ) {

					?>

                <h3>File Repository</h3>
                <?php echo $html2; ?>
                <div>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository"
                        ENCTYPE="multipart/form-data">
                        <input type="hidden" name="_action" value="save_employer_repository" />
                        <input type="hidden" name="emp_id" value="<?php echo $vo->id; ?>" />

                        <table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
                            <col width="150" />
                            <tr>
                                <td class="fieldLabel_compulsory">File to upload:</td>
                                <?php
								// re - 01/03/2012 - changed the form element name #22414
								//    - there are too many things called uploadFile around here
								//    - for clarity.  Also removed camelcase and replaced with
								//    - underscored word separation as above support request
								//    - was caused by camelcase issue.
								?>
                                <td><input class="compulsory" type="file" name="uploaded_employer_file" />&nbsp;
                                    <span id="uploadFileButton" class="button"
                                        onclick="uploadFile()">&nbsp;Upload&nbsp;</span>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                </p>
            </div>
            <?php } ?>
            <?php if ((DB_NAME == "am_reed_demo" || DB_NAME == "am_reed") && $_SESSION['user']->isAdmin()) { ?>
            <div id="tab9">
                <span id="refsourcesavebutton" class="button"
                    onclick="save_referral_sources('frm_ref_sources');">&nbsp;Save&nbsp;</span><span><img id="globe2"
                        src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>
                <span id="opener" class="button">&nbsp;Add New &nbsp;</span><span><img id="globe2"
                        src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>
                <div align="left">
                    <table>
                        <tr>
                            <td>
                                <?php
								if ($id != '') {
									$st = DAO::getResultset($link, "SELECT id, description FROM lookup_referral_source WHERE provider_id = " . $id . " AND active = 1");
									echo '<form name="frm_ref_sources" action="ajax_save_lookup_ref_source">';
									echo '<input type="hidden" name="new_record" value="0" />';
									echo '<input type="hidden" name="indexValue" value="' . count($st) . '" />';
									echo "<table class='resultset' cellspacing='4' cellpadding='4' >";
									echo "<thead><th>Description</th></thead>";
									$index = 0;
									foreach ($st as $row) {
										echo "<tr><td><input type='hidden' name='rid" . $index . "' id='rid" . $index . "' value = '" . $row[0] . "' /><input type='text' name='description" . $index . "' id='description" . $index . "' value = '" . $row[1] . "' /></td></tr>";
										$index++;
									}
									echo "</table>";
									echo '</form>';
								}
								?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>

                            <td valign="top">
                                <?php
								$all_sources = "SELECT id, lookup_referral_source.`description`,IF(lookup_referral_source.active = 1, 'Yes', 'No') AS active FROM lookup_referral_source WHERE provider_id = " . $id;
								$s = new SQLStatement($all_sources);

								$returnHTML = "";

								$st = $link->query($s);
								if ($st) {
									$returnHTML .= '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
									$returnHTML .= '<thead><tr><th>Description</th><th>Active</th><th>Update</th></tr></thead>';
									$counter = 1;
									$returnHTML .= '<tbody>';
									while ($row = $st->fetch()) {
										$returnHTML .= '<tr>';
										$returnHTML .= '<td align="left">' . HTML::cell($row['description']) . "</td>";
										$returnHTML .= '<td align="left">' . HTML::cell($row['active']) . "</td>";
										$returnHTML .= '<td align="center"><span class="button" onclick="changeRefSource(' . $row['id'] . ')">Change Status</span>';

										$returnHTML .= '</tr>';
									}
									$returnHTML .= '</tbody></table></div>';
								} else {
									throw new DatabaseException($link, $this->getSQL());
								}

								echo $returnHTML;
								?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="dialog" title="Add new referral source for this training provider">
                    <form name="frm_ref_sources_new" action="ajax_save_lookup_ref_source">
                        <table id="tbl_ref_sources" border="0" class="resultset" cellspacing="4" cellpadding="4"
                            style="margin-top:10px">
                            <tr>
                                <td>Description: * </td>
                                <td><input type="text" name="description" id="description" value="" size="80" /></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center"><input type="button" id="closer" value="Save" /></td>
                            </tr>
                        </table>
                        <input type="hidden" name="indexValue" value="1" />
                        <input type="hidden" name="new_record" value="1" />
                    </form>
                </div>


            </div>
            <?php } ?>
        </div>
    </div>



</body>

</html>