<?php /* @var $choc Choc */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>CHOC</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
	</style>
</head>
<body>

<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">CHOC</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-xs btn-default" onclick="saveFrmChoc();"><i class="fa fa-save"></i> Save</span>
                <?php if($_SESSION['user']->type != User::TYPE_LEARNER && isset($choc)) {?>
				<span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=edit_choc&id=<?php echo $choc->id; ?>&tr_id=<?php echo $choc->tr_id; ?>&from=create'"><i class="fa fa-plus"></i> Create CHOC Entry</span>
                <?php } ?>
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
<br>


<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="callout callout-default">
            <span class="text-bold">Learner:</span> <?php echo $tr->firstnames . ' ' . $tr->surname; ?><br>
            <span class="text-bold">Learner's Contact:</span> <?php echo $tr->home_address_line_1 . ' ' . $tr->home_postcode; ?><br>
            <?php echo $tr->home_email; ?><br>
            <?php echo $tr->home_telephone; ?><br>            
            <span class="text-bold">Programme:</span> <?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr_id}'"); ?><br>
            <span class="text-bold">Start Date:</span> <?php echo Date::toShort($tr->start_date); ?><br>
            <span class="text-bold">Planned End Date:</span> <?php echo Date::toShort($tr->target_date); ?><br>
            <span class="text-bold">Training Status:</span> 
            <?php 
                if($tr->status_code == 1)
                {
                    echo "1 The learner is continuing or intending to continue the learning activities.";
                }
                elseif($tr->status_code == 2)
                {
                    echo "2 The learner has completed the learning activities.";
                }
                elseif($tr->status_code == 3)
                {
                    echo "3 The learner has withdrawn from the learning activities.";
                }
                elseif($tr->status_code == 4)
                {
                    echo "4 The learner has transferred to a new learning.";
                }
                elseif($tr->status_code == 5)
                {
                    echo "5 Changes in learning within the same programme.";
                }
                elseif($tr->status_code == 6)
                {
                    echo "6 Learner has temporarily withdrawn due to an agreed break in learning.";
                }
                else
                {
                    echo htmlspecialchars((string)$record_status);
                }               
                ?>
                <br>
                <span class="text-bold">Employer:</span> 
                <?php 
                if($choc->id == '')
                {
                    echo $tr->legal_name; 
                }
                else
                {
                    echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE organisations.id = '{$choc->old_employer}'");
                }
                ?><br>
                <span class="text-bold">Employer Address:</span> 
                <?php
                if($choc->id == '')
                {
                    $_location = Location::loadFromDatabase($link, $tr->employer_location_id);
                } 
                else
                {
                    $_location = Location::loadFromDatabase($link, $choc->old_employer_location);
                }
                echo $_location->address_line_1 . ', ' .  $_location->postcode . '<br>';
                echo $_location->telephone;
                ?><br>
                <span class="text-bold">Assessor:</span> <?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->assessor}'"); ?><br>
        </div>
    </div>
    <div class="col-sm-2"></div>
</div>
<div class="row">
    <div class="col-sm-8">
    <hr>
        <div style="margin-left: 15px;">
            <span class="lead text-bold text-info">Comments</span>
            <?php 
            if($choc->comments != '')
            {
                echo '<ul class="timeline">';
                $comments = XML::loadSimpleXML($choc->comments);
                foreach($comments AS $comment)
                {
                    // $date = 
                    echo '<li class="time-label"><span class="bg-green">' . Date::toMedium($comment->DateTime->__toString()) . '</span></li>';

                    echo '<li>';
                    echo '<i class="fa fa-comments bg-blue"></i>';
                    echo '<div class="timeline-item">';
                    echo '<span class="time"><i class="fa fa-clock-o"></i> ' . Date::to($comment->DateTime->__toString(), 'H:i') . '</span>';
                    echo '<h5 class="timeline-header"><a href="#">' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$comment->CreatedBy->__toString()}'") . '</a></h5>';
                    echo '<div class="timeline-body">';
                    echo nl2br($comment->Note->__toString());
                    echo '</div>';
                    echo '</div>';
                    echo '</li>';
                }
                echo '</ul>';
            }
            ?>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-1"></div>
	<form class="form-horizontal" name="frmChoc" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="_action" value="save_choc" />
		<input type="hidden" name="id" value="<?php echo $choc->id ?>" />
		<input type="hidden" name="tr_id" value="<?php echo $tr->id ?>" />
		<input type="hidden" name="request_choc" value="learner" />
		<input type="hidden" name="choc_status" value="CREATED BY LEARNER" />
		<div class="col-sm-8">

			<div class="box box-primary">
                <div class="box-header with-border">
                    <span class="box-title"><h5 class="text-bold">Change of circumstances details</h5></span>
                </div>
				<div class="box-body">
                    <div class="form-group">
                        <label for="choc_type" class="col-sm-4 control-label fieldLabel_compulsory">Select Type:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('choc_type', $choc_types, $choc->choc_type, true); ?>
                        </div>
                    </div>
                    <?php if($_SESSION['user']->type != User::TYPE_LEARNER) { ?>
                    <div class="form-group">
                        <label for="choc_status" class="col-sm-4 control-label fieldLabel_compulsory">Select Status:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('choc_status', [["REFERRED TO LEARNER", "REFERRED TO LEARNER"]], $choc->choc_status, true); ?>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="comments" class="col-sm-4 control-label fieldLabel_compulsory">Comments:</label>
                        <div class="col-sm-8">
                            <textarea name="comments" id="comments" style="width: 100%" rows="10"></textarea>
                        </div>
                    </div>
				</div>

			</div>

		</div>
		

	</form>
</div>



<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

	$(function() {

		
	});


	function saveFrmChoc()
	{
		var frmChoc = document.forms["frmChoc"];
		if(validateForm(frmChoc) == false)
		{
			return false;
		}
		frmChoc.submit();
	}

    

</script>

</body>
</html>