
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Programs Capacity Matrix</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body class="table-responsive">
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Programs Capacity Matrix</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-xs btn-default" onclick="saveFrmProgramCapacityMatrix();"><i class="fa fa-save"></i> Save</span>
			</div>
			<div class="ActionIconBar">
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-12">
            <form class="form-horizontal" name="frmProgramCapacityMatrix" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=edit_program_capacity_matrix" method="POST">
				<input type="hidden" name="_action" value="edit_program_capacity_matrix" />
				<input type="hidden" name="subaction" value="save" />
				<input type="hidden" name="formName" value="frmProgramCapacityMatrix" />
				<div class="box-header with-border"><h2 class="box-title">Induction Capacity </h2></div>
				<div class="box-body">
                    <table class="table table-bordered table-striped">
                        <tr class="small">
                            <th>Month</th>
                            <?php 
                            foreach($titles AS $title)
                            {
                                echo '<th>' . str_replace('Level 3 ICT Technician', 'Level 3 ICT Support Technician', $title) . '</th>';
                            }
                            ?>
                        </tr>
                        <?php
                        $start_date = new Date(date("Y-m-d", strtotime("-6 months")));
                        for($i = 1; $i <= 13; $i++)
                        {
                            echo '<tr>';
                            echo '<td>' . $start_date->format('M Y') . '</td>';
                            foreach($titles AS $title_id => $title_value)
                            {
								$_name = 'month_'.$start_date->format('MY').'_'.$title_id;
								$saved_value = DAO::getSingleValue($link, "SELECT capacity FROM program_capacity_matrix WHERE month_name = '" . $start_date->format('MY') . "' AND ap_title_id = '{$title_id}'");
                                echo '<td title="'.$start_date->format('M Y').' - '.$title_value.'">';
                                echo '<input type="text" class="optional" name="'.$_name.'" maxlength="5" size="5" onkeypress="return numbersonly();" value="'.$saved_value.'" />';
                                echo '</td>';
                            }
                            echo '</tr>';
                            $start_date->addMonths(1);
                        }
                        ?>
                    </table>
				</div>
			</form>
        </div>
	</div>
</div>


<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script>
	$(function(){
		<?php if(isset($_SESSION['edit_program_capacity_matrix_saved'])) { echo "alert('" . $_SESSION['edit_program_capacity_matrix_saved'] . "')"; } ?>
	});

	function saveFrmProgramCapacityMatrix()
	{
		var myForm = document.forms["frmProgramCapacityMatrix"];

		myForm.submit();
	}
</script>
</body>
</html>

<?php 

if( isset($_SESSION['edit_program_capacity_matrix_saved']) )
{
	unset($_SESSION['edit_program_capacity_matrix_saved']);
}

?>