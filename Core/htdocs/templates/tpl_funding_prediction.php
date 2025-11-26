<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Funding Predictions</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<?php // #186 {0000000204} - removed separate file references ?>

<!-- CSS for Controls -->
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/treeview/assets/skins/sam/treeview.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/calendar/assets/skins/sam/calendar.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/container/assets/container.css">

<!-- CSS for Menu -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/menu/assets/skins/sam/menu.css">

<!-- CSS for TabView -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
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

<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.css" />


<script type="text/javascript">
    YAHOO.namespace("am.scope");
    //var oTreeView,      // The YAHOO.widget.TreeView instance
    //var oContextMenu,       // The YAHOO.widget.ContextMenu instance
    //oTextNodeMap = {},      // Hash of YAHOO.widget.TextNode instances in the tree
    //oCurrentTextNode = null;    // The YAHOO.widget.TextNode instance whose "contextmenu" DOM event triggered the display of the context menu
    oTextNodeMap = {};
    tree=null;
    root=null;
    mytabs=null;
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
    if(request.status == 200)
    {
        var xml = request.responseXML;
        var xmlDoc = xml.documentElement;

        if(xmlDoc.tagName != 'error')
        {
            for(var i = 0; i < xmlDoc.childNodes.length; i++)
            {
                arr[i+1] = xmlDoc.childNodes[i].childNodes[0].nodeValue;
            }
        }
    }


    /**
     * Create a new Document object. If no arguments are specified,
     * the document will be empty. If a root tag is specified, the document
     * will contain that single root tag. If the root tag has a namespace
     * prefix, the second argument must specify the URL that identifies the
     * namespace.
     */

    function treeInit() {


        myTabs = new YAHOO.widget.TabView("demo");
        graph();
    }


    YAHOO.util.Event.onDOMReady(treeInit);
</script>

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
    <div class="Title">Funding Predictions</div>
    <div class="ButtonBar">
    </div>
    <div class="ActionIconBar">
        <button onclick="window.location='<?php echo str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1)) . '&output=XLS'; ?>'" title="Export to .XLS file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div id="demo" class="yui-navset">
<ul class="yui-nav">
    <li class="selected"><a href="#tab1"><em>Total</em></a></li>
    <li><a href="#tab2"><em>19-24 Traineeship NP</em></a></li>
    <li><a href="#tab3"><em>19-24 Traineeship P Nov 17</em></a></li>
    <li><a href="#tab4"><em>AEB Other NP</em></a></li>
    <li><a href="#tab7"><em>AEB Other P Nov 17</em></a></li>
    <li><a href="#tab8"><em>16-18 Apps</em></a></li>
    <li><a href="#tab9"><em>19-23 Apps</em></a></li>
    <li><a href="#tab5"><em>24 Apps</em></a></li>
    <li><a href="#tab6"><em>16-18 Apps Levy May 17</em></a></li>
    <li><a href="#tab7"><em>16-18 Apps NLNP May 17</em></a></li>
    <li><a href="#tab8"><em>16-18 Apps NLP May17</em></a></li>
    <li><a href="#tab9"><em>19 Apps Levy May 17</em></a></li>
    <li><a href="#tab10"><em>19 Apps NLNP May 17</em></a></li>
    <li><a href="#tab11"><em>19 Apps NLP May 17</em></a></li>
</ul>

<div class="yui-content" style='background: white'>
<div id="tab1"><p>
    <?php
        echo $dataHTMLTotal;
    ?>
    </p>
</div>

<div id="tab2">
    <p>
        <?php
        if(empty($period))
            echo $dataHTML1924TraineeshipNP;
        ?>
    </p>
</div>

<div id="tab3">
    <p>
        <?php
        if(empty($period))
            echo $dataHTML1924TraineeshipPNov17;
        ?>
    </p>
</div>

<div id="tab4">
    <p>
        <?php
        if(empty($period))
            echo $dataHTMLAEBOtherNP;
        ?>
    </p>
</div>

<div id="tab5">
    <p>
        <?php
        if(empty($period))
            echo $dataHTMLAEBOtherPNov17;
        ?>
    </p>
</div>

<div id="tab6">
    <p>
        <?php
        if(empty($period))
            echo $dataHTML1618Apps;
        ?>
    </p>
</div>

<div id="tab7">
    <p>
        <?php
        if(empty($period))
            echo $dataHTML1923Apps;
        ?>
    </p>
</div>

<div id="tab8">
<p>

    <?php
    if(empty($period))
        echo $dataHTML24Apps;
    ?>
</p>
</div>

<div id="tab9">
<p>
    <?php
    if(empty($period))
        echo $dataHTML1618AppsLevyMay17;
    ?>
</p>
</div>

<div id="tab10">
    <p>
        <?php
        if(empty($period))
            echo $dataHTML1618AppsNLNPMay17;
        ?>
    </p>
</div>

<div id="tab11">
    <p>
        <?php
        if(empty($period))
            echo $dataHTML1618AppsNLPMay17;
        ?>
    </p>
</div>

<div id="tab12">
    <p>
        <?php
        if(empty($period))
            echo $dataHTML19AppsLevyMay17;
        ?>
    </p>
</div>

<div id="tab13">
    <p>
        <?php
        if(empty($period))
            echo $dataHTML19AppsNLNPMay17;
        ?>
    </p>
</div>

<div id="tab14">
    <p>
        <?php
        if(empty($period))
            echo $dataHTML19AppsNLPMay17;
        ?>
    </p>
</div>

</div>
</div>



</body>
</html>