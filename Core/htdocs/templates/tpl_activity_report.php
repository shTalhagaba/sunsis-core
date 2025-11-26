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

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<?php // #186 {0000000204} - removed separate file references ?>
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
    $(document).ready(function()
            {
                $('.sortData').tablesorter();
            }
    );

</script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
    <?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
    var calPop = new CalendarPopup();
    calPop.showNavigationDropdowns();
        <?php } else { ?>
    var calPop = new CalendarPopup("calPop1");
    calPop.showNavigationDropdowns();
    document.write(getCalendarStyles());
        <?php } ?>
</script>

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

<style type="text/css">
    .icon-ppt { padding-left: 20px; background: transparent url(/images/icons.png) 0 0px no-repeat; width:50px; height:50px}
    .icon-dmg { padding-left: 20px; background: transparent url(/images/icons.png) 0 -36px no-repeat; width:50px; height:50px}
    .icon-prv { padding-left: 20px; background: transparent url(/images/icons.png) 0 -72px no-repeat; width:50px; height:50px}
    .icon-gen { padding-left: 20px; background: transparent url(/images/icons.png) 0 -108px no-repeat; width:50px; height:50px}
    .icon-doc { padding-left: 20px; background: transparent url(/images/icons.png) 0 -144px no-repeat; width:50px; height:50px}
    .icon-jar { padding-left: 20px; background: transparent url(/images/icons.png) 0 -180px no-repeat; width:50px; height:50px}
    .icon-zip { padding-left: 20px; background: transparent url(/images/icons.png) 0 -216px no-repeat; width:50px; height:50px}
</style>


<script type="text/javascript">
    YAHOO.namespace("am.scope");



    function treeInit() {


        myTabs = new YAHOO.widget.TabView("demo");
    }


    YAHOO.util.Event.onDOMReady(treeInit);



</script>


<!-- Initialise calendar popup -->
<script language="JavaScript">
    var calPop = new CalendarPopup("calPop1");
    calPop.showNavigationDropdowns();
    document.write(getCalendarStyles());
</script>

<script language="JavaScript">
    var elements_counter = 0;
    var oldReference = '';
    var unitTitleElement = '';

    evidence_methods = new Array();
    evidence_types = new Array();
    evidence_categories = new Array();

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

    background-image:url('/images/paper-background-orange.jpg');
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

</style>


</head>
<body class="yui-skin-sam">
<div class="banner">
    <div class="Title">Activity Report</div>
    <div class="ActionIconBar">
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.location.href='do.php?_action=activity_report_export&contract=<?php echo $contracts; ?>&submission=<?php echo htmlspecialchars((string)$submission); ?>'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Aims Started</em></a></li>
        <li class=""><a href="#tab2"><em>Aims Achieved</em></a></li>
        <li class=""><a href="#tab3"><em>Aims Withdrawn</em></a></li>
        <li class=""><a href="#tab4"><em>Aims Temporarily Withdrawn</em></a></li>
    <!--    <li class=""><a href="#tab5"><em>Employer</em></a></li>
        <li class=""><a href="#tab6"><em>Yearly</em></a></li>
        <li class=""><a href="#tab7"><em>Learners</em></a></li>
        <li class=""><a href="#tab7"><em>Report 7</em></a></li>
      <li class=""><a href="#tab8"><em>Report 8</em></a></li>
      <li class=""><a href="#tab9"><em>Report 9</em></a></li>
      <li class=""><a href="#tab10"><em>Report 10</em></a></li>
      <li class=""><a href="#tab11"><em>Report 11</em></a></li>
      <li class=""><a href="#tab12"><em>Report 12</em></a></li>
  -->
    </ul>

	<div class="yui-content" style='background: white'>
        <div id="tab1"><p>
            <div align="center" style="margin-top:50px;">
                <?php
                echo "<h3>Aims Started</h3>";
            if(isset($report1))
                echo $report1->to('HTML');
                ?>
            </div>
            </p>
        </div>

        <div id="tab2">
            <p>
            <div align="center" style="margin-top:50px;">
                <?php
                echo "<h3>Aims Achieved</h3>";
            if(isset($report2))
                echo $report2->to('HTML');
                ?>
            </div>
            </p>
        </div>

        <div id="tab3">
            <p>
            <div align="center" style="margin-top:50px;">
                <?php
                echo "<h3>Aims Withdrawn</h3>";
            if(isset($report3))
                echo $report3->to('HTML');
                ?>
            </div>
            </p>
        </div>

        <div id="tab4">
            <p>
            <div align="center" style="margin-top:50px;">
                <?php
                echo "<h3>Aims Temporarily Withdrawn</h3>";
                if(isset($report4))
                    echo $report4->to('HTML');
                ?>
            </div>
            </p>
        </div>



        <!--
  <div id="tab7">
      <p>
      <div align="center" style="margin-top:50px;">



      </div>
      </p>
  </div>

  <div id="tab8">
      <p>
      <div align="center" style="margin-top:50px;">


      </div>
      </p>
  </div>


  <div id="tab9">
      <p>
      <div align="center" style="margin-top:50px;">


      </div>
      </p>
  </div>

  <div id="tab10">
  <p>
  <div align="center" style="margin-top:50px;">


  </div>
  </p>
  </div>


  <div id="tab11">
      <p>
      <div align="center" style="margin-top:50px;">


      </div>
      </p>
  </div>

  <div id="tab12">
      <p>
      <div align="center" style="margin-top:50px;">

      </div>
      </p>
  </div>

  -->

   </div>
</div>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>
