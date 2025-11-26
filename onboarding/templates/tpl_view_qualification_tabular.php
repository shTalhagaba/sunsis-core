<?php /* @var $vo Qualification */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Qualification Tabular View</title>
    <link rel="stylesheet" href="/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        body {

        }
    </style>
</head>
<body onload="load_evidence_lookups();">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Qualification Tabular View</div>
            <div class="ButtonBar">
                <button class="btn btn-default btn-xs" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';"><i class="fa fa-arrow-circle-left"></i> Back</button>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=qualification_export&id=<?php echo $vo->id ?>&clients=<?php echo $clients; ?>&auto_id=<?php echo $vo->auto_id; ?>'" title="Export to .CSV file"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<div style="margin-top: 20px"></div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <th>Number (QAN)</th><td class="bg-success"><?php echo $vo->id; ?></td>
                <th>Title</th><td class="bg-success"><?php echo $vo->title; ?></td>
            </tr>
        </table>
    </div>
</div>

<div style="margin-top: 20px"></div>

<dic class="row">
    <div class="col-sm-12">
        <div id="tre" style="margin-top: 20px"></div>
    </div>
</dic>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">

    var t = '<table class="table table-bordered"><thead class="bg-success"><tr><th class="topRow">Title</th><th>Reference</th><th>Delivery&nbsp;Hours</th>' +
        '<th>Assessment&nbsp;Method</th><th>Evidence&nbsp;Type</th><th>Evidence&nbsp;Category</th></tr></thead>';

    function getData()
    {

        if(<?php echo '"' . $qualification_id . '"' ?>!='')
        {
            var request = ajaxBuildRequestObject();
            request.open("GET", expandURI('do.php?_action=ajax_get_qualification_xml&id=' + <?php echo '"' . $qualification_id . '"';?> + '&clients=' + <?php echo '"' . $clients . '"' ?> + '&internaltitle=' + <?php echo  '"' . htmlspecialchars($internaltitle) . '"';?> + '&auto_id=' + <?php echo $vo->auto_id; ?>), false);
            request.setRequestHeader("x-ajax", "1");
            request.send(null);

            if(request.status == 200)
            {
                var xml = request.responseXML;
                var xmlDoc = xml.documentElement;

                if(xmlDoc.tagName != 'error')
                {
                    populateFields(xml);
                }
            }
            else
            {
                ajaxErrorHandler(request);
            }
        }
    }

    var elements_counter = 0;
    var oldReference = '';
    var unitTitleElement = '';

    evidence_methods = new Array();
    evidence_types = new Array();
    evidence_categories = new Array();

    function load_evidence_lookups()
    {
        <?php 	foreach($evidence as $evi)
        {
        ?> evidence_methods[<?php echo $evi[0]; ?>] = <?php echo '"' . $evi[1] . '";'; } ?>

    <?php 	foreach($evidence2 as $evi2)
        {
        ?> evidence_types[<?php echo $evi2[0]; ?>] = <?php echo '"' . $evi2[1] . '";'; } ?>

    <?php 	foreach($evidence3 as $evi3)
        {
        ?> evidence_categories[<?php echo $evi3[0]; ?>] = <?php echo '"' . $evi3[1] . '";'; } ?>

        getData();
    }

    function populateFields(xmlDoc)
    {
        xmlQual = xmlDoc.documentElement;

        var xmlUnits = null;
        var t;

        for(var i = 0; i < xmlQual.childNodes.length; i++)
        {
            if(xmlQual.childNodes[i].tagName == 'root')
            {
                xmlUnits = xmlQual.childNodes[i];
                break;
            }
        }

        if(xmlUnits != null)
        {
            showTree(xmlUnits);
        }
    }


    function showTree(xmlUnits)
    {

        tags = new Array();
        tagcount = 0;
        traverserecurse(xmlUnits);

        t += '</table>';

        document.getElementById("tre").innerHTML = t;

    }

    function traverserecurse(xmlUnits)
    {
        if(xmlUnits.hasChildNodes())
        {
            for(var i=0; i<xmlUnits.childNodes.length; i++)
            {

                if(xmlUnits.childNodes[i].tagName=='unit')
                {

                    t += "<tr><td colspan='7'  style='background-color: #C2D69B'><b class='text-blue'>UNIT DETAILS:</b> ";
                    t += "<b>Title:</b> " + xmlUnits.childNodes[i].getAttribute('title');
                    t += ' | <b>Owner Reference: </b>' + xmlUnits.childNodes[i].getAttribute('owner_reference');
                    t += ' | <b>Reference: </b>' + xmlUnits.childNodes[i].getAttribute('reference');
                    t += ' | <b>Credits: </b>' + xmlUnits.childNodes[i].getAttribute('credits');
                    t += ' | <b>Guided Learning Hours: </b>' + xmlUnits.childNodes[i].getAttribute('glh');

				if(xmlUnits.childNodes[i].getAttribute('mandatory')=='true' || xmlUnits.childNodes[i].getAttribute('mandatory')==true)
					t += " | <b>Status: </b>" + "Mandatory" + "</td></tr>";
				else
					t += " | <b>Status: </b>" + "Optional" + "</td></tr>";

                }

                if(xmlUnits.childNodes[i].tagName=='elements')
                {
                    t += "<tr><td colspan='7'  style='background-color: #E8FB77'><b class='text-blue'>ELEMENT GROUP:</b> " + xmlUnits.childNodes[i].getAttribute('title') + "</td></tr>";
                }

                if(xmlUnits.childNodes[i].tagName=='element')
                {
                    t += "<tr><td colspan='7'  style='background-color: lightgrey'><b class='text-blue'>ELEMENT:</b> " + xmlUnits.childNodes[i].getAttribute('title') + "</td></tr>";
                }

                if(xmlUnits.childNodes[i].tagName=='evidence')
                {

                    t += "<tr>" +
                        "<td>" + xmlUnits.childNodes[i].getAttribute('title') + "</td>" +
                        "<td title='Evidence Reference'>" + xmlUnits.childNodes[i].getAttribute('reference') + "</td>" +
                        "<td title='Evidence Delivery Hours'>" + xmlUnits.childNodes[i].getAttribute('delhours') + "</td>" +
                        "<td title='Evidence Assessment Method'>" + evidence_methods[xmlUnits.childNodes[i].getAttribute('method')] + "</td>" +
                        "<td title='Evidence Type'>" + evidence_types[xmlUnits.childNodes[i].getAttribute('etype')] + "</td>" +
                        "<td title='Evidence Category'>" + evidence_categories[xmlUnits.childNodes[i].getAttribute('cat')] + "</td>" +
                        "</tr>";

                }

                traverserecurse(xmlUnits.childNodes[i]);
            }
        }
    }

    function exportExcel()
    {
        qual = <?php echo '"' . $vo->id . '"' ?>;
        var postData = 'id=' + qual;
        var request = ajaxRequest('do.php?_action=qualification_export', postData);
    }

</script>

</body>
</html>