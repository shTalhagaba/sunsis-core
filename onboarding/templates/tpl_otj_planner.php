
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OTJ Planner</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        input[type="text"] {font-weight:bold; font-size: x-large;}
    </style>

</head>
<body class="table-responsive">
<div class="row">
    <div class="col-sm-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">OTJ Planner</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <h5 class="lead text-bold">Adult Care Worker L2 Standard OTJ Planner</h5>
            <form name="frmProgOtj" method="POST" action="do.php?_action=otj_planner">
                <input type="hidden" name="subaction" value="save" />
                <div class="">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="bg-info">
                                <th></th>
                                <th>KSB</th>
                                <th>A</th>
                                <th>B</th>
                                <th>C</th>
                                <th>D</th>
                                <th>E</th>
                                <th>F</th>
                                <th>Behaviours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $otj_prog_template_sections = DAO::getResultset($link, "SELECT * FROM otj_prog_template_sections WHERE framework_id = '1' ORDER BY section_id", DAO::FETCH_ASSOC);

                            foreach($otj_prog_template_sections AS $otj_prog_template_section)
                            {
                                $subsections_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM otj_prog_template_subsections WHERE section_id = '{$otj_prog_template_section['section_id']}'");
                                if($subsections_count > 1)
                                {
                                    $row_span = (int)$subsections_count+1;
                                    echo '<tr>';

                                    echo '<td class="bg-info" rowspan="' . $row_span . '">' . $otj_prog_template_section['section_desc'] . '</td>';
            
                                    echo '</tr>';
                                }

                                $otj_prog_template_subsections = DAO::getResultset($link, "SELECT * FROM otj_prog_template_subsections WHERE section_id = '{$otj_prog_template_section['section_id']}' ORDER BY subsection_id", DAO::FETCH_ASSOC);
                                foreach($otj_prog_template_subsections AS $otj_prog_template_subsection)
                                {
                                    echo '<tr>';

                                    echo $subsections_count > 1 ? '' : '<td class="bg-info">' . $otj_prog_template_section['section_desc'] . '</td>';
                                    
                                    echo '<td>' . $otj_prog_template_subsection['subsection_desc'] . '</td>';
        
                                    $otj_prog_template_activities = DAO::getResultset($link, "SELECT * FROM otj_prog_template_activities WHERE subsection_id = '{$otj_prog_template_subsection['subsection_id']}' ORDER BY activity_id", DAO::FETCH_ASSOC);
                                    foreach($otj_prog_template_activities AS $otj_prog_template_activity)
                                    {
                                        echo '<td>' . $otj_prog_template_activity['activity_desc'] . '</td>';
                                    }
                                    echo '</tr>';
                                }

                                $col = 1;
                                echo '<tr class="bg-warning">';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td><input onkeypress="return numbersonly();" maxlength="2" name="txt_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="'.$otj_prog_template_section['col_'.$col.'_otj'].'" /></td>';
                                echo '<td><input onkeypress="return numbersonly();" maxlength="2" name="txt_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="'.$otj_prog_template_section['col_'.$col.'_otj'].'" /></td>';
                                echo '<td><input onkeypress="return numbersonly();" maxlength="2" name="txt_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="'.$otj_prog_template_section['col_'.$col.'_otj'].'" /></td>';
                                echo '<td><input onkeypress="return numbersonly();" maxlength="2" name="txt_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="'.$otj_prog_template_section['col_'.$col.'_otj'].'" /></td>';
                                echo '<td><input onkeypress="return numbersonly();" maxlength="2" name="txt_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="'.$otj_prog_template_section['col_'.$col.'_otj'].'" /></td>';
                                echo '<td><input onkeypress="return numbersonly();" maxlength="2" name="txt_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="'.$otj_prog_template_section['col_'.$col.'_otj'].'" /></td>';
                                echo '<td><input onkeypress="return numbersonly();" maxlength="2" name="txt_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="'.$otj_prog_template_section['col_'.$col.'_otj'].'" /></td>';
                                echo '</tr>';
                            }
                            $col = 1;
                            echo '<tr class="bg-black">';
                            echo '<td>Commulative OTJ</td>';
                            echo '<td></td>';
                            echo '<td><input readonly="true" name="total_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="0" /></td>';
                            echo '<td><input readonly="true" name="total_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="0" /></td>';
                            echo '<td><input readonly="true" name="total_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="0" /></td>';
                            echo '<td><input readonly="true" name="total_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="0" /></td>';
                            echo '<td><input readonly="true" name="total_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="0" /></td>';
                            echo '<td><input readonly="true" name="total_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="0" /></td>';
                            echo '<td><input readonly="true" name="total_section_' . $otj_prog_template_section['section_id'] . '_col_' . ++$col . '" class="form-control" type="text" value="0" /></td>';
                            echo '</tr>';
                            ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">
refreshGridTotals();

$('input[name^="txt_section_"]').on('change', function(){
    refreshGridTotals();
});

function refreshGridTotals()
{
    $('input[name^="total_section_"]').each(function(index, elem){
        var name_parts = elem.name.split('_');
        var col = name_parts[4];

        sum = 0;
        $("input[name$='_col_"+col+"']").not('input[name^="total_section_"]').each(function(){
            sum += Number( $(this).val() );
        });

        $(this).val( sum );
    }); 
}

</script>

</body>
</html>