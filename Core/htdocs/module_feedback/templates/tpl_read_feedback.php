<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Feedback</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Feedback</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
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
    <div class="col-sm-12">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="panel-body fieldValue">
                <span class="text-bold"><?php echo $feedback->learner_name; ?></span><br>
                <span class="text-info"><?php echo 'Training From: ' . Date::toShort($schedule->training_date) . ' to ' . Date::toShort($schedule->training_end_date); ?></span><br>
                <span class="text-info"><?php echo 'Level: ' . $schedule->level; ?></span><br>
                <span class="text-info"><?php echo 'Venue: ' . $schedule->venue; ?></span><br>
            </div>
		</div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Score Given</th>
                    <th>Answer / Comments</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>1. How was the booking process for your electric vehicle training course?</th>
                    <td class="text-center lead text-info text-bold"><?php echo $feedback->q1; ?></td>
                    <td><?php echo $feedback->q1_comments; ?></td>
                </tr>
                <tr>
                    <th>2. Did the Joining instructions provide all the information you required to get you to your training course?</th>
                    <td class="text-center lead text-info text-bold"><?php echo $feedback->q2; ?></td>
                    <td><?php echo $feedback->q2_comments; ?></td>
                </tr>
                <tr>
                    <th>3. How easy was the process of getting set up on to the IMI Vocanto Platform?</th>
                    <td class="text-center lead text-info text-bold"><?php echo $feedback->q3; ?></td>
                    <td><?php echo $feedback->q3_comments; ?></td>
                </tr>
                <tr>
                    <th>4. Once you got on to Vocanto- how did you find the quality of the online training material?</th>
                    <td class="text-center lead text-info text-bold"><?php echo $feedback->q4; ?></td>
                    <td><?php echo $feedback->q4_comments; ?></td>
                </tr>
                <tr>
                    <th>5. Please rate the face to face training facilities including the workshops and equipment provided for the training.</th>
                    <td class="text-center lead text-info text-bold"><?php echo $feedback->q5; ?></td>
                    <td><?php echo $feedback->q5_comments; ?></td>
                </tr>
                <tr>
                    <th>6. Please rate your course trainer on their knowledge and delivery of the material during the week.</th>
                    <td class="text-center lead text-info text-bold"><?php echo $feedback->q6; ?></td>
                    <td><?php echo $feedback->q6_comments; ?></td>
                </tr>
                <tr class="bg-info">
                    <th class="text-right">TOTAL</th>
                    <td class="text-center lead text-info text-bold">
                        <?php echo $feedback->q1+$feedback->q2+$feedback->q3+$feedback->q4+$feedback->q5+$feedback->q6;?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th>7. Please provide a few words to describe how you found the whole experience:</th>
                    <td></td>
                    <td><?php echo $feedback->q7_comments; ?></td>
                </tr>
                <tr>
                    <th>8. Where did you first hear about our courses?</th>
                    <td></td>
                    <td>
                        <?php
                        $hearUs = [
							1 => 'Current Employer',
							2 => 'Job Center / Work Coach / DWP',
							3 => 'Social Media',
							4 => 'Friends / Family',
							5 => 'FE college / training provider',
							6 => 'THE National Careers Service',
							7 => 'Gov.uk website',
							8 => 'Other (e.g. search engine, local media press)',
						];
                        foreach(explode(',', $feedback->q8) AS $selectedKey)
                        {
                            echo isset($hearUs[$selectedKey]) ? $hearUs[$selectedKey] . '<br>' : '';
                        } 
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>9. Would you book a training course with us again?</th>
                    <td></td>
                    <td><?php echo $feedback->q9; ?></td>
                </tr>
                <tr>
                    <th>10. Are there any ways you think the course could be improved?</th>
                    <td></td>
                    <td><?php echo $feedback->q10_comments; ?></td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">


</script>

</body>
</html>