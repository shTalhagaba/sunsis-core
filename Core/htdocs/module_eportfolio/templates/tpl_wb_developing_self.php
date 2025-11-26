<?php /* @var $wb WBDevelopingSelf */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Developing self workbook</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="module_eportfolio/assets/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
	<link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>

		html,
		body {
			padding-top: 30px;
			height: 100%;
		}

		.step-content {
			border:2px groove blue;
			min-height: 640px;
			border-radius: 10px;
		}



		.topLine {
			position: absolute; top: 0;
			margin-bottom: 5px;
		}

		.bottomLine {
			position: absolute; bottom: 0;
		}

		h2 {
			color: #C71585;
		}

		.navbar-fixed-top {
			min-height: 50px;
			max-height: 50px;
			background: #ffffff url("module_eportfolio/assets/images/pp.png") center center;
		}

		@media (min-width: 768px) {
			.navbar-custom {
				/*padding: 5px 0;*/
				-webkit-transition: padding 0.3s;
				-moz-transition: padding 0.3s;
				transition: padding 0.3s;
			}
			.navbar-custom.affix {
				padding: 0;
			}
		}

		textarea {
			border:1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
		}

		.sigbox {
			border-radius: 15px;
			border: 1px solid #EEE;
			cursor: pointer;
		}
		.sigboxselected {
			border-radius: 25px;
			border: 2px solid #EEE;
			cursor: pointer;
			background-color: #d3d3d3;
		}

		.ui-dialog-titlebar-close {
			visibility: hidden;
		}

		textarea:disabled {
			opacity:1;
		}

	</style>
</head>

<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#"><img height="35px" class="headerlogo" src="images/logos/<?php echo $wb->getHeaderLogo($link); ?>" /></a>
		</div>
	</div>
	<div class="pull-right" id="clock"></div>
	<div class="text-center" style="margin-top: 5px;"><h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3></div>
</nav>

<form name="frm_wb_developing_self" id="frm_wb_developing_self" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_developing_self" />
<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
<input type="hidden" name="wb_status" id="wb_status" value="" />
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />
<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">De<span class="text-red">v</span>eloping self</h1></section>' : '<section class="content-header"><h1>Developing self</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Developing self</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">De<span class="text-red">v</span>eloping self</h2>' : '<h2 class="text-bold">Developing self</h2>' ?>
		<p class="text-center" >Module 2</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 1 ends-->

<h1>Developing self</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-6">

			<p>This module is about developing oneself. It includes gaining skills and having key behaviours as well as taking ownership for keeping your service knowledge and skills up to date.</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Knowledge - points you need to know when undertaking any work activities</li>
				<li>Skills - those you are expected to have to perform any tasks</li>
				<li>Behaviours - the key ways in which you are expected to act towards others when you are undertaking your work activities</li>
			</ul>
			<p>This module will help you to:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Identify what knowledge, skills and behaviours you need</li>
				<li>Consider personal goals</li>
				<li>Propose development to help you achieve your goals</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<img src="module_eportfolio/assets/images/wb2_pg1_img1.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p>You will look at the tools and techniques that can help you in developing yourself. These include:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>SWOT analysis</li>
				<li>PDP</li>
				<li>Learning styles questionnaire</li>
				<li>Review process</li>
			</ul>
			<p>You will also learn how to:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Seek feedback</li>
				<li>Act on feedback</li>
			</ul>
		</div>

		<div class="col-lg-6">
			<img src="module_eportfolio/assets/images/wb2_pg1_img2.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12" align="center">
			<img src="module_eportfolio/assets/images/wb2_pg1_img3.png" />
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 2 ends-->

<h1>Developing self</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-6">

			<p class="text-bold">How to identify own learning needs and improve own performance and identity</p>
			<p>Identifying your development needs can be challenging.  Often, we find ourselves looking at what training courses are available and deciding which of those would be most helpful. In fact, it is better to try and identify what the development need is and then to work out ways of meeting that need, which may or may not be a training course.</p>
			<p class="text-bold">There are 3 stages to identifying your needs:</p>
			<p class="text-bold">1. Identify what skills, knowledge and behaviours are ‘required' for you to do your job well.</p>
			<p>Every role in customer service has a job description. A job description will list the things that you are expected to do. A person specification will identify the skills, experience, knowledge and behaviours that you need to do that job well.</p>
			<p>At this stage, it's also worth thinking about the skills, knowledge and behaviours that you may need to develop in the future in your current job. You may know, for example, that you are interested in a working your way up from a sales assistant to a store manager.</p>
			<p><img src="module_eportfolio/assets/images/wb2_pg2_img1.png" /></p>
		</div>
		<div class="col-sm-6">
			<img src="module_eportfolio/assets/images/wb2_pg2_img2.png" />
			<p class="text-bold">2. Look at the skills, knowledge and behaviours you actually have now. </p>
			<p>Once you have identified what you are required to do, ask yourself how effectively you match against your job description. You could consider talking this through with a friend / colleague or with your manager.</p>
			<p>It's important to ask yourself some questions at this stage and answer honestly.  Are there areas of your work, for example, where developing more confidence would make a real difference to your success in your job?  Are there knowledge, skills and behaviours that you only need on occasion that would benefit from some development?  Can you identify areas where you feel confident and believe you perform well that could be an even greater strength for you with some development?</p>
			<p class="text-bold">3. Compare ‘actual' with ‘required' to identify the gaps. These are your development needs. </p>
			<p>Finally, try and be as specific as possible about what you need to do differently. This will really help you when you are deciding how to best address your development needs. It will also help you review and measure your success.</p>
			<p>Think about what you want to do but also why. Why do you want to achieve? What is the end result you are looking for? How are you going to do it? This will all help you when deciding on the best solution to address your needs. It will also help you to look back and review what you have done to see if it was a success.</p>
		</div>
	</div>
	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 3 ends-->

<h1>Self-assessment</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SelfAssessment->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SelfAssessment', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SelfAssessment', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Self-assessment</p>
			<p>A self -assessment is a great tool to identify your development needs. To enable you to self-assess you first need to visit your job description which you will find below:</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8 <?php echo $wb->savers_or_sp == 'savers' ? 'bg-blue' : 'bg-purple'; ?>">
			<h3 class="text-center ">Apprentice Sales Assistant - Job description</h3>
			<h4>Reporting to - Store manager</h4>
			<h4>Duties and responsibilities</h4>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Understand what the <?php echo ucfirst($wb->savers_or_sp); ?> customer wants</li>
				<li>Demonstrate exceptional customer service</li>
				<li>Understand the business and maintaining the brand reputation</li>
				<li>Resolve customer queries</li>
				<li>Use knowledge to promote products to customers</li>
				<li>Highlight promotional offers to work towards achieving sales targets</li>
				<li>Support promotion changes and stock rotation</li>
				<li>Understand how to increase sales through product placement</li>
				<li>Adhere to all policies and procedures</li>
				<li>Support delivery of store KPIs</li>
			</ul>
		</div>
		<div class="col-sm-2"></div>
	</div>
	<div class="row">
		<div class="col-sm-10 text-bold text-center"><img src="module_eportfolio/assets/images/wb2_img1.png" />Look at your job description and consider whether you can or cannot perform these duties and responsibilities competently.  Please complete the table with a YES/NO answer. </div>
		<div class="col-sm-2"></div>
	</div>
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8 table-responsive" <?php echo $feedback->SelfAssessment->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<?php
			$saved_self_assessment = array();
			if(isset($answers->SelfAssessment))
				$saved_self_assessment = explode(',', $answers->SelfAssessment->__toString());
			?>
			<table class="table row-border">
				<tr><th>Duties and responsibilities</th><th>YES/NO</th></tr>
				<tr><td>Understand what the <?php echo ucfirst($wb->savers_or_sp); ?> customer wants</td><td><input type="checkbox" name="self_assessment[]" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('1', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td>Demonstrate exceptional customer service</td><td><input type="checkbox" name="self_assessment[]" value="2" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('2', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td>Understand the business and maintaining the brand reputation</td><td><input type="checkbox" name="self_assessment[]" value="3" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('3', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td>Resolve customer queries</td><td><input type="checkbox" name="self_assessment[]" value="4" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('4', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td>Use knowledge to promote products to customers</td><td><input type="checkbox" name="self_assessment[]" value="5" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('5', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td>Support promotion changes and stock rotation</td><td><input type="checkbox" name="self_assessment[]" value="6" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('6', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td>Understand how to increase sales through product placement</td><td><input type="checkbox" name="self_assessment[]" value="7" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('7', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td>Adhere to all policies and procedures</td><td><input type="checkbox" name="self_assessment[]" value="8" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('8', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td>Support delivery of store KPIs</td><td><input type="checkbox" name="self_assessment[]" value="9" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('9', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td>Highlight promotional offers to work towards achieving sales targets</td><td><input type="checkbox" name="self_assessment[]" value="10" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo in_array('10', $saved_self_assessment)?'checked="checked"':''; ?> /></td></tr>
				<tr><td></td><td><img src="module_eportfolio/assets/images/wb2_img2.png" /></td></tr>
			</table>
		</div>
		<div class="col-sm-2"></div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->SelfAssessment->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_SelfAssessment" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_SelfAssessment', $answer_status, $feedback->SelfAssessment->Status->__toString() == 'A'?$feedback->SelfAssessment->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_SelfAssessment" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_SelfAssessment" rows="7" style="width: 100%;"><?php echo $feedback->SelfAssessment->Comments->__toString(); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SelfAssessment->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SelfAssessment', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SelfAssessment', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 4 ends-->

<h1>Learning Styles Questionnaire</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->LearningStyles->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('LearningStyles', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('LearningStyles', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Learning Styles Questionnaire</p>
			<p>We all learn differently. To meet your learning needs it is beneficial to understand your own learning style. This will assist you when putting together a training plan.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-10"><img src="module_eportfolio/assets/images/wb2_img1.png" />The questionnaire should take you about 10 - 15 minutes.  The accuracy of the results depends on how honest you can be.  There is no right or wrong answers.  Try and go with your first instinct.</div>
		<div class="col-sm-2">

		</div>
	</div>
	<div class="row" <?php echo $feedback->LearningStyles->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-lg-6 col-md-6 col-sm-6 table-responsive">
			<table class="table row-border no-padding">
				<tr><th colspan="2">Select the statements you agree</th></tr>
				<?php
				$learn_styles_questions = DAO::getResultset($link, "SELECT * FROM lookup_wb_dev_self_learn_styles ORDER BY id", DAO::FETCH_ASSOC);
				$saved_learn_styles = array();
				if(isset($answers->LearningStyles))
					$saved_learn_styles = explode(',', $answers->LearningStyles->__toString());
				foreach($learn_styles_questions AS $q)
				{
					$checked = in_array($q['id'], $saved_learn_styles)?'checked="checked"':'';
					echo '<tr>';
					echo '<td align="right"><input class type="checkbox" name="learn_styles[]" value="'.$q['id'].'" ' . $checked . ' /></td>';
					echo '<td>' . $q['description'] . '</td>';
					echo '</tr>';
				}
				?>
			</table>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 text-center table-responsive" style="border-style: groove; border-width: medium">
			<h3 class="text-center">LEARNING STYLES QUESTIONNAIRE: SCORE SHEET</h3>
			<table id="tblLearnStyleScoreSheet" class="table table-bordered no-padding">
				<tr class="text-bold"><td>Activist</td><td>Reflector</td><td>Theorist</td><td>Pragmatist</td></tr>
				<tr><td>2</td><td>7</td><td>1</td><td>5</td></tr>
				<tr><td>4</td><td>13</td><td>3</td><td>9</td></tr>
				<tr><td>6</td><td>15</td><td>8</td><td>11</td></tr>
				<tr><td>10</td><td>16</td><td>12</td><td>19</td></tr>
				<tr><td>17</td><td>25</td><td>14</td><td>21</td></tr>
				<tr><td>23</td><td>28</td><td>18</td><td>27</td></tr>
				<tr><td>24</td><td>29</td><td>20</td><td>35</td></tr>
				<tr><td>32</td><td>31</td><td>22</td><td>37</td></tr>
				<tr><td>34</td><td>33</td><td>26</td><td>44</td></tr>
				<tr><td>38</td><td>36</td><td>30</td><td>49</td></tr>
				<tr><td>40</td><td>39</td><td>42</td><td>50</td></tr>
				<tr><td>43</td><td>41</td><td>47</td><td>53</td></tr>
				<tr><td>45</td><td>46</td><td>51</td><td>54</td></tr>
				<tr><td>48</td><td>52</td><td>57</td><td>56</td></tr>
				<tr><td>58</td><td>55</td><td>61</td><td>59</td></tr>
				<tr><td>64</td><td>60</td><td>63</td><td>65</td></tr>
				<tr><td>71</td><td>62</td><td>68</td><td>69</td></tr>
				<tr><td>72</td><td>66</td><td>75</td><td>70</td></tr>
				<tr><td>74</td><td>67</td><td>77</td><td>73</td></tr>
				<tr><td>79</td><td>76</td><td>78</td><td>80</td></tr>
				<tr><td colspan="4" class="text-bold">Totals</td></tr>
				<tr><td><h3 id="lblActivist">0</h3></td><td><h3 id="lblReflector">0</h3></td><td><h3 id="lblTheorist">0</h3></td><td><h3 id="lblPragmatist">0</h3></td></tr>
			</table>
			<hr>
			<h3 class="text-center">LEARNING STYLES QUESTIONNAIRE PROFILE</h3>
			<table class="table"  id="tblLearnStyleProfile">
				<tr class="text-bold"><td>ACTIVIST</td><td>REFLECTOR</td><td>THEORIST</td><td>PRAGMATIST</td><td></td></tr>
				<tr><td>20</td><td>20</td><td>20</td><td>20</td><td rowspan="8" class="bg-green-active" valign="center">Very<br>Strong<br>Preference</td></tr>
				<tr><td>19</td><td></td><td>19</td><td></td></tr>
				<tr><td>18</td><td></td><td></td><td>19</td></tr>
				<tr><td>17</td><td>19</td><td>18</td><td></td></tr>
				<tr><td>16</td><td></td><td></td><td>18</td></tr>
				<tr><td>15</td><td></td><td>17</td><td></td></tr>
				<tr><td>14</td><td></td><td></td><td></td></tr>
				<tr><td>13</td><td>18</td><td>16</td><td>17</td></tr>
				<tr><td colspan="5"></td></tr>
				<tr><td>12</td><td>17</td><td>15</td><td>16</td><td rowspan="3" class="bg-green" valign="center">Strong<br>Preference</td></tr>
				<tr><td></td><td>16</td><td></td><td></td></tr>
				<tr><td>11</td><td>15</td><td>14</td><td>15</td></tr>
				<tr><td colspan="5"></td></tr>
				<tr><td>10</td><td>14</td><td>13</td><td>14</td><td rowspan="4" class="bg-fuchsia-active" valign="center">Moderate<br>Preference</td></tr>
				<tr><td>9</td><td></td><td></td><td></td></tr>
				<tr><td>8</td><td>13</td><td>12</td><td>13</td></tr>
				<tr><td>7</td><td>12</td><td>11</td><td>12</td></tr>
				<tr><td colspan="5"></td></tr>
				<tr><td>6</td><td>11</td><td>10</td><td>11</td><td rowspan="3" class="bg-fuchsia" valign="center">Low<br>Preference</td></tr>
				<tr><td>5</td><td>10</td><td>9</td><td>10</td></tr>
				<tr><td>4</td><td>9</td><td>8</td><td>9</td></tr>
				<tr><td colspan="5"></td></tr>
				<tr><td>3</td><td>8</td><td>7</td><td>8</td><td rowspan="9" class="bg-orange" valign="center">Very<br>Low<br>Preference</td></tr>
				<tr><td></td><td>7</td><td>6</td><td>7</td></tr>
				<tr><td></td><td>6</td><td>5</td><td>6</td></tr>
				<tr><td>2</td><td>5</td><td>4</td><td>5</td></tr>
				<tr><td></td><td>4</td><td></td><td>4</td></tr>
				<tr><td>1</td><td>3</td><td>3</td><td>3</td></tr>
				<tr><td></td><td>2</td><td>2</td><td>2</td></tr>
				<tr><td></td><td>1</td><td>1</td><td>1</td></tr>
				<tr><td class="bg-aqua-gradient">0</td><td class="bg-aqua-gradient">0</td><td class="bg-aqua-gradient">0</td><td class="bg-aqua-gradient">0</td></tr>

			</table>
		</div>
	</div>


	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->LearningStyles->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_LearningStyles" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php echo HTML::selectChosen('status_LearningStyles', $answer_status, $feedback->LearningStyles->Status->__toString() == 'A'?$feedback->LearningStyles->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_LearningStyles" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_LearningStyles" rows="7" style="width: 100%;"><?php echo $feedback->LearningStyles->Comments->__toString(); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->LearningStyles->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('LearningStyles', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('LearningStyles', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 5,6,7,8 end-->

<h1>Activists</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<h1>LEARNING STYLES - ACTIVISTS</h1>
			<p>Activists involve themselves fully and without bias in new experiences.  They enjoy the here and now and are happy to be dominated by immediate experiences.  They are open-minded, not sceptical and this tends to make them enthusiastic about anything new.  Their philosophy is “I’ll try anything once”.  They tend to act first and consider the consequences afterwards.  Their days are filled with activity.  As soon as the excitement from one activity has died down they are busy looking for the next.  They tend to thrive on the challenge of new experiences but are bored with implementation.  They are gregarious people constantly involving themselves with others, but in doing so; they seek to centre all activities on themselves.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p><strong>STRENGTHS</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Flexible and open minded </li>
				<li>Happy to have a go</li>
				<li>Optimistic about anything new and therefore unlikely to resist change</li>
			</ul>
			<p><strong>WEAKNESSES</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Tendency to take the immediately obvious action without thinking</li>
				<li>Often take unnecessary risks</li>
				<li>Tendency to do too much themselves and hog the limelight</li>
				<li>Rush into action without sufficient preparation</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<img src="module_eportfolio/assets/images/wb2_pg8_img1.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p><strong>LEARN BEST FROM ACTIVITIES WHERE:</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>There are new experiences/problems/opportunities from which to learn</li>
				<li>They can engross themselves in short ‘here and now’ activities such as competitive teamwork tasks and role playing exercises</li>
				<li>There is excitement/drama/crisis and things chop and change with a range of diverse activities to tackle</li>
				<li>They have a lot of limelight/high visibility; i.e. they can ‘chair’ meetings, lead discussions, and give presentations</li>
				<li>They are thrown in at the deep end with a task they think is difficult</li>
				<li>They are involved with other people, i.e. bouncing ideas off them, solving problems as part of a team</li>
			</ul>
			<p><strong>LEARN LEAST FROM, AND MAY REACT AGAINST, ACTIVITIES WHERE:</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Learning involves a passive role, i.e. listening to lectures, monologues, statements of how things should be done, reading, and watching</li>
				<li>They are asked to stand back and not be involved</li>
				<li>They are required to assimilate, analyse and interpret messy data</li>
				<li>They are required to do solitary work, i.e. reading, writing</li>
				<li>They are asked to repeat the same activity over and over again, i.e. practising</li>
				<li>They are asked to do a thorough job, i.e. attend to detail, and tie up loose ends</li>
				<li>They have precise instructions to follow</li>
			</ul>
		</div>
	</div>
	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 9 ends-->

<h1>Reflectors</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<h1>LEARNING STYLES - REFLECTORS</h1>
			<p>Reflectors like to stand back to ponder experiences and observe them from many different perspectives.  They collect data, both first hand and from others, and prefer to think about it thoroughly before coming to any conclusion.</p>
			<p>Their philosophy is to be cautious.  The thorough collection and analysis of data is what counts so they tend to postpone reaching definitive conclusions for as long as possible.  They are thoughtful people who like to consider all possible angles and implications before making a move.  They prefer to take a back seat in meetings and discussions before making their own points.  They enjoy observing other people in action.  They tend to adopt a low profile and have a slightly distant, unruffled air about them.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p><strong>STRENGTHS</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Careful</li>
				<li>Thorough and methodical</li>
				<li>Thoughtful</li>
				<li>Good at listening to others and assimilating information </li>
				<li>Rarely jump to conclusions</li>
			</ul>
			<p><strong>WEAKNESSES</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Tendencies to hold back from direct participation</li>
				<li>Slow to make up their minds and reach a decision</li>
				<li>Tendency to be too cautious and not take enough risks</li>
				<li>Not assertive – they are not particularly forthcoming</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<img src="module_eportfolio/assets/images/wb2_pg9_img1.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p><strong>LEARN BEST FROM ACTIVITIES WHERE:</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>They are encouraged to watch/think/ponder over activities</li>
				<li>They are able to stand back from events and listen/observe</li>
				<li>They are able to think before acting, i.e. time to prepare, and chance to read in advance</li>
				<li>They can carry out painstaking research</li>
				<li>They have the opportunity to review what has happened, what they have learned</li>
				<li>They are asked to produce carefully considered analysis and reports</li>
				<li>They can reach a decision in their own time without pressure and tight deadlines</li>
			</ul>
			<p><strong>LEARN LEAST FROM, AND MAY REACT AGAINST, ACTIVITIES WHERE:</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>They are ‘forced’ into the limelight, i.e. to act as leader or to role-play</li>
				<li>They are involved in situations that require action without planning</li>
				<li>They are given insufficient data on which to base a conclusion</li>
				<li>They are given cut and dried instructions of how things should be done</li>
				<li>They are worried by time pressures or rushed from activity to another</li>
				<li>They may have to take short cuts or do a superficial job</li>
				<li>They are thrown into something without warning, i.e. to produce an instant reaction, to produce ideas off the top of their head</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<img src="module_eportfolio/assets/images/wb2_pg9_img2.png" />
		</div>
	</div>
	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 10 ends-->

<h1>Theorists</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<h1>LEARNING STYLES - THEORISTS</h1>
			<p>Theorists adapt and integrate observations into complex but logically sound theories.  They think problems through in a vertical, step by step logical way.  They tend to be perfectionists who don’t rest easy until things are tidy and fit into a rational scheme.  They like to analyse and synthesise.  They are keen on basic assumptions, principles and theories.  Their philosophy prizes rationality and logic. Questions they frequently ask “Does it make sense?” “How does this fit with that?”  Their approach to problems is consistently logical.  This is their ‘mental set’ and they rigidly reject anything that doesn’t fit with it.  They prefer to maximise certainty and feel uncomfortable with subjective judgements, lateral thinking and anything flippant.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p><strong>STRENGTHS</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Logical ‘vertical’ thinkers</li>
				<li>Rational and objective</li>
				<li>Good at asking probing questions </li>
				<li>Disciplines approach</li>
			</ul>
			<p><strong>WEAKNESSES</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Restricted in lateral thinking</li>
				<li>Low tolerance for uncertainty, disorder and ambiguity</li>
				<li>Intolerant of anything subjective or intuitive</li>
				<li>Full of ‘shoulds, oughts and musts’</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<img src="module_eportfolio/assets/images/wb2_pg10_img1.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p><strong>LEARN BEST FROM ACTIVITIES WHERE:</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Part of a system, model, concept, or theory</li>
				<li>They have time to explore methodically ideas, events and situations</li>
				<li>They have the chance to question and probe the basic methodology, assumptions or logic behind something</li>
				<li>They are in structured situations with a clear purpose</li>
				<li>They can listen to or read about ideas and concepts that emphasis rationality or logic</li>
				<li>They can analyse and then generalise the reasons for success or failure</li>
				<li>They are required to understand and participate in complex situations</li>
			</ul>
			<p><strong>LEARN LEAST FROM, AND MAY REACT AGAINST, ACTIVITIES WHERE:</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>They have to participate in situations emphasising emotions and feelings</li>
				<li>They are involved in unstructured activities where ambiguity and uncertainty are high</li>
				<li>They are asked to act or decide without a basis in policy, principle or concept</li>
				<li>They find the subject matter shallow or gimmicky</li>
				<li>They are pitch forked into doing something without a context or purpose</li>
				<li>They doubt the subject matter is methodologically sound, i.e. A questionnaire hasn’t been validated </li>
			</ul>
		</div>
	</div>
	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 11 ends-->

<h1>Pragmatists</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<h1>LEARNING STYLES - PRAGMATISTS</h1>
			<p>Pragmatists are keen on trying out ideas, theories and techniques to see if they work in practice.  They positively search out new ideas and take the first opportunity to experiment with applications.  They are the sort of people who return from development workshops brimming with new ideas they want to try out.  They like to get on with things and act quickly and confidently on ideas that attract them.  They are essentially practical, down to earth people who like making practical decisions and solving problems.  They respond to problems and opportunities ‘as a challenge’.  They tend to be impatient with open-ended discussions.  Their philosophy is: ‘There is always a better way’.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p><strong>STRENGTHS</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Keen to test things out in practice</li>
				<li>Practical, down to earth, realistic </li>
				<li>Gets straight to the point</li>
				<li>Technique orientated</li>
			</ul>
			<p><strong>WEAKNESSES</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Tendency to reject anything without an obvious explanation</li>
				<li>Not very interested in theory or basic principles</li>
				<li>Impatient with waffle</li>
				<li>On balance, task orientated not people orientated</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<img src="module_eportfolio/assets/images/wb2_pg11_img1.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p><strong>LEARN BEST FROM ACTIVITIES WHERE:</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>There is an obvious link between the subject matter and a problem or opportunity on the job</li>
				<li>They are shown techniques for doing things with obvious practical advantages, i.e. how to save time</li>
				<li>They have opportunity to try out and practice techniques with coaching/feedback from a credible expert</li>
				<li>They are given immediate opportunities to implement what they have learned</li>
				<li>They can concentrate on practical issues, i.e. drawing up action plans</li>
				<li>They are given techniques currently applicable to their own job</li>
			</ul>
			<p><strong>LEARN LEAST FROM, AND MAY REACT AGAINST, ACTIVITIES WHERE:</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>The learning is not related to an immediate need they recognise</li>
				<li>The learning event seems distant from reality, i.e. all theory and principles</li>
				<li>There is no practice or clear guidelines on how to do it</li>
				<li>There are political, managerial or personal obstacles to implementation</li>
				<li>There is no apparent reward from the learning activity, i.e. more sales, streamlined processes</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<img class="text-center" src="module_eportfolio/assets/images/wb2_pg11_img2.png" />
		</div>
	</div>
	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 12 ends-->

<h1>Feedback</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-6">
			<h1>Feedback</h1>
			<p>Feedback is a very powerful tool. Used correctly it can help develop and maintain personal service skills and knowledge. Feedback can come in many forms and not all feedback is formal. In your role you will need to show that you seek and value discussing your performance and development needs with other people in your organisation. Their opinions can be very useful.</p>
		</div>
		<div class="col-sm-6">
			<img src="module_eportfolio/assets/images/wb2_pg13_img1.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>If you are asking others for feedback, you will need to show that you can reflect on what you want to improve:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Do you want general feedback because you are new to the organisation or role?</li>
				<li>Do you want feedback on how you are dealing with particular aspects of your job and want to know what needs to be improved?</li>
				<li>Do you want feedback on how well you are managing work relationships or dealing with customers?</li>
			</ul>
			<p>When seeking feedback, you will need to show how you decide who is the most appropriate person or persons to obtain feedback from. Different people in the organisation will have different opinions because they work with you in different capacities.</p>
			<p>You will get a greater insight into how well you are performing by asking a range of people for feedback. This is called 360-degree feedback. This system is considered to be useful for developing an accurate picture of your performance because it allows a variety of people who you come into contact with you to offer the feedback and not just your line manager.</p>
			<p>Feedback will be given at your performance review. This is known as formal feedback as it is planned measured against your job description.  At this point you should have had at least one review with your line manager. If not, please make sure you ask your manager when they plan to do this. This will be a great opportunity to discuss how you are getting on so far.</p>
			<p>Not all feedback is formal and it is important to not overlook the value of this. Informal feedback often happens during routine activities and interactions. This could be from an internal or external customer.</p>
			<p>Constructive feedback is a valuable way of gaining a different perspective and insight into your performance. The people offering this kind of feedback recognise and reinforce the things you do well, offer ideas about how you could do something better and point out skills, knowledge and the attitude you need to develop. With this understanding, you can show that you can use the feedback responsibly to help maintain and develop your customer service skills and knowledge.</p>
			<p>Start thinking about the feedback you have received. Was this sufficient to help you with your development? If not, start to ask for feedback from the relevant people about your performance at work as you will need this later in the workbook.</p>
		</div>
	</div>
	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 13 ends-->

<h1>SWOT Analysis</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<h1>SWOT analysis</h1>
			<p>A  SWOT analysis is a planning tool; it stands for strengths, weaknesses, opportunities and threats. It is often used in business as part of the planning process and is equally useful for assessing your training needs.</p>
			<p>Conducting a SWOT analysis involves gathering information from various sources. These include:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Your job description and if you can perform tasks competently</li>
				<li>One to one meetings</li>
				<li>Reviews</li>
				<li>Outcomes of any training sessions</li>
				<li>Feedback from colleagues and management</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>When completing a SWOT analysis ask yourself the questions below:</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p><strong>Strengths</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>What skills and qualifications do you have?</li>
				<li>What does your line manager see as your strengths?</li>
				<li>What personal resource do you have access to?</li>
				<li>What feedback have you had from colleagues?</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<p><strong>Opportunities</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>How are your developing skills and knowledge making a difference to your performance?</li>
				<li>Could you ask to perform some different tasks if the opportunity arose?</li>
				<li>Are there any courses, training you can complete or attend?</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<img src="module_eportfolio/assets/images/wb2_pg14_img1.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p><strong>Weaknesses (areas for improvement)</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>What knowledge, skills and behaviours do you feel you still need?</li>
				<li>What does your line manager see as areas for improvement?</li>
				<li>What feedback have you had from colleagues?</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<p><strong>Threats</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Do you have any personality traits that hold you back? E.G. Public speaking, shy, unconfident</li>
				<li>Do you have any special requirements for learning?</li>
			</ul>
		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 14 ends-->

<h1>SWOT Questions</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SWOT->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SWOT', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SWOT', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-10 text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> Based on the previous exercises, please complete the SWOT analysis below:</div>
	</div>
	<div class="row" <?php echo $feedback->SWOT->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-6 text-center">
			<p><strong>Strength</strong></p>
			<textarea name="swot_strength" id="swot_strength" rows="10" cols="50" style="width: 100%"><?php echo isset($answers->SWOT->Strength)?$answers->SWOT->Strength->__toString():''; ?></textarea>
		</div>
		<div class="col-sm-6 text-center">
			<p><strong>Weaknesses</strong></p>
			<textarea name="swot_weakness" id="swot_weakness"  rows="10" cols="50"  style="width: 100%"><?php echo isset($answers->SWOT->Weakness)?$answers->SWOT->Weakness->__toString():''; ?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<p><h2>SWOT ANALYSIS</h2> <img src="module_eportfolio/assets/images/wb2_img2.png" /> </p>
		</div>
	</div>
	<div class="row" <?php echo $feedback->SWOT->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-6 text-center">
			<p><strong>Opportunities</strong></p>
			<textarea name="swot_opportunity" id="swot_opportunity" rows="10" cols="50"  style="width: 100%"><?php echo isset($answers->SWOT->Opportunity)?$answers->SWOT->Opportunity->__toString():''; ?></textarea>
		</div>
		<div class="col-sm-6 text-center">
			<p><strong>Threats</strong></p>
			<textarea name="swot_threat" id="swot_threat" rows="10" cols="50" style="width: 100%"><?php echo isset($answers->SWOT->Threat)?$answers->SWOT->Threat->__toString():''; ?></textarea>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->SWOT->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_SWOT" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php echo HTML::selectChosen('status_SWOT', $answer_status, $feedback->SWOT->Status->__toString() == 'A'?$feedback->SWOT->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_SWOT" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_SWOT" rows="7" style="width: 100%;"><?php echo $feedback->SWOT->Comments->__toString() ;?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SWOT->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SWOT', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SWOT', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 15 ends-->

<h1>PDP</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p><strong>Personal Development Plan</strong></p>
			<p>Your next step is to look at the benefits of completing a personal development plan.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center" style="letter-spacing: 2px; border: 1px solid #000000; padding: 10px; background-color: <?php echo $wb->savers_or_sp == 'savers' ? '#0000ff' : '#ff69b4'; ?>; font-weight: bolder;">
			<p><strong>Personal Development Plan (PDP)</strong></p>
			<p style="color: #ffffff; font-style: italic;">Personal development planning is the process of creating an action plan for personal development within the context of a career, education, relationship or for self-improvement</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p><br>A personal development plan is a tool that can be used to support you to achieve your learning and development goals. It is a record of your agreed development needs and a contract between yourself and your line manager on how you are going to develop yourself.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<p>A Personal Development Plan (PDP) is completed with a line manager and is usually broken down into columns to show:</p>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr><th>Objectives – What needs to be achieved?</th><th>How will I do this? – What resources are required?</th><th>Target – when will I complete this development?</th><th>Review – how have I used what I learned?</th></tr>
					<tr><td>E.G. Learn how to use the HHT</td><td>Training to be given by Mary using the HHT </td><td>(Date/time)</td><td>Used HHT to process price checking </td></tr>
					<tr><td>E.G. Learn the process for implementing planograms</td><td>Shadow Paul completing a planogram update</td><td>(Date/time)</td><td>Assist store team in store moves of sections/plans  pre- Christmas</td></tr>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>As with work objectives, personal objectives should also be SMART (Specific, Measurable, Achievable, Realistic and Timely).  If not then the chances of achieving the objectives is reduced and the individual could become demotivated. The objective could be suggested by a manager but the individual needs to buy in to and believe they can achieve or it could be a wasted exercise.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p><strong>Benefits of a PDP to the individuals</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>It can help plan their development</li>
				<li>It can help agree what they need from the business in order to develop</li>
				<li>They can learn new skills that will be useful in their work and help with career progression</li>
				<li>They will feel valued by their employer</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<p><strong>Benefits of a PDP to the retail business</strong></p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>They can identify personal objectives for individuals which will help meet the organisations business objectives.</li>
				<li>Employees will have improved and wide ranging skills.</li>
				<li>Employees will have an increased commitment to the business.</li>
				<li>Employees will be loyal as they will see that they have a career rather than a job.</li>
			</ul>
		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 16 ends-->

<h1>PDP Questions</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->PersonalDevelopmentPlan->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('PersonalDevelopmentPlan', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('PersonalDevelopmentPlan', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-10">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; With your line manager and using all the information you have collected about yourself complete the PDP (Personal development plan) below: &nbsp; <img src="module_eportfolio/assets/images/wb2_img2.png" /></p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 table-responsive" <?php echo $feedback->PersonalDevelopmentPlan->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<table class="table table-responsive">
				<tr><th>Objectives – What needs to be achieved?</th><th>How will I do this? – What resources are required?</th><th>Target – when will I complete this development?</th><th>Review – how have I used what I learned?</th></tr>
				<?php

				for($i = 1; $i <= 6; $i++)
				{
					$key = 'Set'.$i;
					$f1 = 'pdp_set'.$i.'_obj';
					$f2 = 'pdp_set'.$i.'_res';
					$f3 = 'pdp_set'.$i.'_target';
					$f4 = 'pdp_set'.$i.'_review';
					$f1_val = isset($answers->PersonalDevelopmentPlan->$key->Objective)?$answers->PersonalDevelopmentPlan->$key->Objective:'';
					$f2_val = isset($answers->PersonalDevelopmentPlan->$key->Resource)?$answers->PersonalDevelopmentPlan->$key->Resource:'';
					$f3_val = isset($answers->PersonalDevelopmentPlan->$key->Target)?$answers->PersonalDevelopmentPlan->$key->Target:'';
					$f4_val = isset($answers->PersonalDevelopmentPlan->$key->Review)?$answers->PersonalDevelopmentPlan->$key->Review:'';
					echo '<tr>';
					echo '<td><textarea name="pdp_set'.$i.'_obj" cols="30" rows="10" style="width: 100%">' . $f1_val . '</textarea></td>';
					echo '<td><textarea name="pdp_set'.$i.'_res" cols="30" rows="10" style="width: 100%">' . $f2_val . '</textarea></td>';
					echo '<td><textarea name="pdp_set'.$i.'_target" cols="30" rows="10" style="width: 100%">' . $f3_val . '</textarea></td>';
					echo '<td><textarea name="pdp_set'.$i.'_review" cols="30" rows="10" style="width: 100%">' . $f4_val . '</textarea></td>';
					echo '</tr>';
				}
				?>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p><strong>Changes - </strong> throughout your career you will come across changes to the business or to the role that will highlight things you need to learn. It might be something as simple as new products being introduced. You will need to learn about them so you can sell them to the customers. This additional learning will enable you do your job better and give you more confidence in your role.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p><strong>Problems - </strong> there will be occasions when problems at work will bring your learning needs to light. You may identify the problem but not know how to deal with it as it isn’t something you have dealt with before. You can then use this as an opportunity to increase your knowledge and improve your performance and avoid similar problems in the future.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p><strong>Reviews - </strong>  During the course of this qualification you will receive formal reviews of your performance from your manager and assessor. You will need to use this review process to inform your PDP of any changes.
				<img src="module_eportfolio/assets/images/wb2_img2.png" /></p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p>It is good practice to add any new learning needs to your personal development plan (PDP) as soon as you identify them so they don’t get forgotten and you can plan in how and when you are going to address them.</p>
			<p>Remember, your PDP is a live document. It will be your responsibility to manage this plan which will include reviewing, updating and deciding if it has been effective.</p>
		</div>
		<div class="col-sm-6">
			<img src="module_eportfolio/assets/images/wb2_pg17_img1.png" /></p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->PersonalDevelopmentPlan->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_PersonalDevelopmentPlan" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php echo HTML::selectChosen('status_PersonalDevelopmentPlan', $answer_status, $feedback->PersonalDevelopmentPlan->Status->__toString() == 'A'?$feedback->PersonalDevelopmentPlan->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_PersonalDevelopmentPlan" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_PersonalDevelopmentPlan" rows="7" style="width: 100%;"><?php echo $feedback->PersonalDevelopmentPlan->Comments->__toString(); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->PersonalDevelopmentPlan->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('PersonalDevelopmentPlan', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('PersonalDevelopmentPlan', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 17 ends-->

<h1>Signature</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<p><br><strong>Finally</strong></p>
	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_pg18_img1.png" /> &nbsp; You may want to do/have done some independent learning for this module. Which websites have you used to research the topics in this module? Record them below.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 table-responsive">
			<table class="table table-responsive">
				<tr><th>Website name</th><th>Topic</th><th>Date completed</th><th>Time taken</th></tr>
				<?php
				for($i = 1; $i <= 5; $i++)
				{
					$key = 'Set'.$i;
					$f1 = 'rsrch_set'.$i.'_website';
					$f2 = 'rsrch_set'.$i.'_topic';
					$f3 = 'rsrch_set'.$i.'_date_completed';
					$f4 = 'rsrch_set'.$i.'_time_taken';
					$f1_val = isset($answers->Research->$key->Website)?$answers->Research->$key->Website:'';
					$f2_val = isset($answers->Research->$key->Topic)?$answers->Research->$key->Topic:'';
					$f3_val = isset($answers->Research->$key->DateCompleted)?$answers->Research->$key->DateCompleted:'';
					$f4_val = isset($answers->Research->$key->TimeTaken)?$answers->Research->$key->TimeTaken:'';
					echo '<tr>';
					echo '<td><input class="form-control" name="rsrch_set'.$i.'_website" id="rsrch_set'.$i.'_website" size="50" value="'. $f1_val . '" /></td>';
					echo '<td><input class="form-control" name="rsrch_set'.$i.'_topic" id="rsrch_set'.$i.'_topic" size="50" value="'. $f2_val . '" /></td>';
					echo '<td>' . HTML::datebox("rsrch_set".$i."_date_completed", $f3_val) . '</td>';
					echo '<td>' . HTML::timebox("rsrch_set".$i."_time_taken", $f4_val) . '</td>';
					echo '</tr>';
				}
				?>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p><strong>Congratulations! You have completed this module. <img src="module_eportfolio/assets/images/wb2_pg18_img2.png" /> </p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 table-responsive">
			<table class="table row-border">
				<tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
				<tr>
					<td>Apprentice</td>
					<?php if($_SESSION['user']->type == User::TYPE_LEARNER) {?>
					<td><h2 class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></td>
					<td>
						<span class="btn btn-info" onclick="getSignature('learner');">
							<img id="img_user_signature" src="do.php?_action=generate_image&<?php echo $wb->learner_signature != ''?$wb->learner_signature:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
							<input type="hidden" name="user_signature" id="user_signature" value="<?php echo $wb->learner_signature; ?>" />
						</span>
					</td>
					<td><h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2></td>
					<?php } else { ?>
					<td><h2 class="content-max-width"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></h2> </td>
					<td><img src="do.php?_action=generate_image&<?php echo $wb->learner_signature; ?>&size=25" style="border: 2px solid;border-radius: 15px;" /></td>
					<td><h2 class="content-max-width"><?php echo Date::toShort($wb->learner_sign_date)  ; ?></h2></td>
					<?php } ?>
				</tr>
				<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR) {?>
				<tr>
					<td>Assessor</td>
					<td><h2 class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></td>
					<td>
						<span class="btn btn-info" onclick="getSignature('assessor');">
							<img id="img_user_signature" src="do.php?_action=generate_image&<?php echo $wb->assessor_signature != ''?$wb->assessor_signature:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
							<input type="hidden" name="user_signature" id="user_signature" value="<?php echo $wb->assessor_signature; ?>" />
						</span>
					</td>
					<td><h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2></td>
				</tr>
				<?php } if($_SESSION['user']->type != User::TYPE_ASSESSOR && $wb->assessor_signature != '') {?>
				<tr>
					<td>Assessor</td>
					<td><h2 class="content-max-width"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE id = '{$tr->assessor}'"); ?></h2> </td>
					<td><img src="do.php?_action=generate_image&<?php echo $wb->assessor_signature; ?>&size=25" style="border: 2px solid;border-radius: 15px;" /></td>
					<td><h2 class="content-max-width"><?php echo Date::toShort($wb->assessor_sign_date)  ; ?></h2></td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>

	<?php echo $wb->renderIVSection($link); ?>

	<p><br></p>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 18 ends-->

</div> <!--.wizards ends-->

</section> <!--.content ends-->

</div> <!--.content-wrapper ends-->

</div> <!--.wrapper ends-->
</div>
</form>

<div id = "loading"></div>

<div id="dialogPreview" title="Verify information before save">
	<p>Please verify your input information.</p>
	<div id="divPreview" class="small"></div>
</div>
<div class="row"><div class="col-sm-12"><span class="btn btn-xs btn-default" onclick="window.history.back();"><i class="fa fa-arrow-back"></i> Go Back</span></div></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="module_eportfolio/assets/jquery.steps.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/assets/js/autoresize.js"></script>

<script type="text/javascript">
var totalActivist = 0;
var totalReflector = 0;
var totalTheorist = 0;
var totalPragmatist = 0;

$(function () {

	<?php if($disable_answers){?>

	$("#frm_wb_developing_self :input").not(".assessorFeedback :input, #signature_text, #frm_wb_developing_self :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

	<?php } ?>

	$("#wizard").steps({
		transitionEffect:"fade",
		transitionEffectSpeed:500,
		//startIndex:14,
		enableAllSteps:true,
		enableKeyNavigation:true,
		onStepChanging:function (event, currentIndex, newIndex) {
			return true;
		},
		onStepChanged:function (event, currentIndex, priorIndex) {
			//window.scrollTo(0, 0);
			return true;
		},
		onFinishing:function (event, currentIndex) {
			if($("#user_signature").val() == '')
			//return alert('Your signature is required to complete the workbook, please sign the workbook');
				return custom_alert_OK_only('Your signature is required to complete the workbook, please sign the workbook');

			return true;
		},
		onFinished:function (event, currentIndex) {

		<?php if($_SESSION['user']->type == User::TYPE_LEARNER && !$wb->enableForUser()){ ?>
			return window.history.back();
			<?php } ?>
		<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR && !$wb->enableForUser()){ ?>
			return window.history.back();
			<?php } ?>


		<?php if($_SESSION['user']->type == User::TYPE_LEARNER){ ?>
			//if(!confirm('Are you sure, you want to save this workbook as COMPLETED and send it to your assessor?'))
			//	return false;
			$('<div></div>').html('Are you sure, you want to save this workbook as COMPLETED and send it to your assessor?').dialog({
				title:'Confirmation',
				resizable: false,
				modal:true,
				buttons:{
					"Yes":function () {
						var myForm = document.forms['frm_wb_developing_self'];
						myForm.elements['full_save'].value = 'Y';
						window.onbeforeunload = null;
						myForm.submit();
					},
					"No":function () {
						$(this).dialog("close");
						return false;
					},
					"Save And Come Back Later":function () {
						$(this).dialog("close");
						partialSave();
						window.onbeforeunload = null;
						window.location.href='do.php?_action=learner_home_page';
					}
				}
			});
			<?php } elseif($_SESSION['user']->type == User::TYPE_ASSESSOR) { ?>
			var myForm = document.forms['frm_wb_developing_self'];
			myForm.elements['full_save'].value = 'Y';
			return previewInputInformation();
			<?php } else {?>
			return window.history.back();
			<?php } ?>
		}

	});

	$('input[type=checkbox]').iCheck({
		checkboxClass: 'icheckbox_flat-red',
		radioClass: 'iradio_flat-green'
	});

	//if saved already
<?php if(isset($answers->LearningStyles) && $answers->LearningStyles->__tosTring() != ''){ ?>
	var saved_learn_styles = '<?php echo $answers->LearningStyles->__toString(); ?>';
	saved_learn_styles = saved_learn_styles.split(',');
	for(var i = 0; i <= saved_learn_styles.length; i++)
	{
		circleTblLearnStyleScoreSheet(saved_learn_styles[i]);
	}
	<?php } ?>

	$('#dialogPreview').dialog({
		modal: true,
		width: 'auto',
		maxWidth: 550,
		height: 'auto',
		maxHeight: 500,
		closeOnEscape: true,
		autoOpen: false,
		resizable: true,
		draggable: true,
		buttons: {
			'Cancel': function() {$(this).dialog('close');},
			'OK': function() {

				var myForm = document.forms['frm_wb_developing_self'];
				myForm.elements['full_save'].value = 'Y';
				myForm.elements['full_save_feedback'].value = 'Y';
				window.onbeforeunload = null;
				myForm.submit();

			},
			'Save And Come Back Later':function () {

				var myForm = document.forms['frm_wb_developing_self'];
				myForm.elements['full_save'].value = 'Y';
				myForm.elements['full_save_feedback'].value = 'N';
				window.onbeforeunload = null;
				myForm.submit();

			}
		}
	});
});

function partialSave()
{
	$('#frm_wb_developing_self :input[name=full_save]').val('N');
	$($('#frm_wb_developing_self').serializeArray()).each(function(i, field)
	{
		document.forms["frm_wb_developing_self"].elements[field.name].value = field.value.replace(/£/g, "GBP");
	});
	$.ajax({
		type:'POST',
		url:'do.php?_action=save_wb_developing_self',
		data: $('#frm_wb_developing_self').serialize(),
		async: false,
		beforeSend: function(){
			//$("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Busy ...</p>");
		},
		success: function(data, textStatus, xhr) {
			toastr.success('The information has been saved');
			reset();
			startInterval();
		},
		error: function(data, textStatus, xhr){
			var _msg = "";
			if(data.readyState == 0)
			{
				_msg = "No internet connection. The information has not been saved. \n";
				_msg += "Ready State: " + data.readyState + "\n";
				_msg += "Status Text: " + data.statusText + "\n";
				return alert(_msg);
			}
			var myxml = data.responseText,
				xmlDoc = $.parseXML( myxml ),
				$xml = $( xmlDoc );
			$(data.responseXML).find('error').each(function()
			{
				_msg = "Something went wrong, the information has not been saved. \n";
				_msg += "Error Message: " + $(this).find('message').text() + "\n";
				alert(_msg);
			});
		}
	});
}

function circleTblLearnStyleScoreSheet(val)
{
	$('#tblLearnStyleScoreSheet td').each(function(){
		var cell_value = parseInt($(this).html());
		if(cell_value == val)
			$(this).attr('class', 'bg-aqua-gradient');
	});

	countActivist();
	countReflector();
	countTheorist();
	countPragmatist();
}

function removeCircleTblLearnStyleScoreSheet(val)
{
	$('#tblLearnStyleScoreSheet td').each(function(){
		var cell_value = parseInt($(this).html());
		if(cell_value == val)
			$(this).attr('class', '');
	});
	countActivist();
	countReflector();
	countTheorist();
	countPragmatist();
}

$("input[name='learn_styles[]']").on('ifChecked', function(event){
	circleTblLearnStyleScoreSheet(this.value);
});

$("input[name='learn_styles[]']").on('ifUnchecked', function(event){
	removeCircleTblLearnStyleScoreSheet(this.value);
});

function countActivist()
{
	totalActivist = 0;
	$("#tblLearnStyleScoreSheet tr td:nth-child(1)").each(function(){
		var cls = $(this).attr('class');
		if( cls == 'bg-aqua-gradient' )
			totalActivist++;
	});
	$('#lblActivist').html(totalActivist);

	$('#tblLearnStyleProfile tr td:nth-child(1)').each(function(){
		var cell_value = parseInt($(this).html());
		if(cell_value == totalActivist)
			$(this).attr('class', 'bg-aqua-gradient');
		else
			$(this).attr('class', '');
	});
}

function countReflector()
{
	totalReflector = 0;
	$("#tblLearnStyleScoreSheet tr td:nth-child(2)").each(function(){
		var cls = $(this).attr('class');
		if( cls == 'bg-aqua-gradient' )
			totalReflector++;
	});
	$('#lblReflector').html(totalReflector);

	$('#tblLearnStyleProfile tr td:nth-child(2)').each(function(){
		var cell_value = parseInt($(this).html());
		if(cell_value == totalReflector)
			$(this).attr('class', 'bg-aqua-gradient');
		else
			$(this).attr('class', '');
	});
}

function countTheorist()
{
	totalTheorist = 0;
	$("#tblLearnStyleScoreSheet tr td:nth-child(3)").each(function(){
		var cls = $(this).attr('class');
		if( cls == 'bg-aqua-gradient' )
			totalTheorist++;
	});
	$('#lblTheorist').html(totalTheorist);

	$('#tblLearnStyleProfile tr td:nth-child(3)').each(function(){
		var cell_value = parseInt($(this).html());
		if(cell_value == totalTheorist)
			$(this).attr('class', 'bg-aqua-gradient');
		else
			$(this).attr('class', '');
	});
}

function countPragmatist()
{
	totalPragmatist = 0;
	$("#tblLearnStyleScoreSheet tr td:nth-child(4)").each(function(){
		var cls = $(this).attr('class');
		if( cls == 'bg-aqua-gradient' )
			totalPragmatist++;
	});
	$('#lblPragmatist').html(totalPragmatist);

	$('#tblLearnStyleProfile tr td:nth-child(4)').each(function(){
		var cell_value = parseInt($(this).html());
		if(cell_value == totalPragmatist)
			$(this).attr('class', 'bg-aqua-gradient');
		else
			$(this).attr('class', '');
	});
}

</script>

</body>

<script>
	var phpWorkbookID = '<?php echo $wb->id; ?>';
	var phpBookmarks = '<?php echo $wb_bookmarks; ?>';
	var phpStepsWithQuestions = '<?php echo $wb->getStepsWithQuestions(); ?>';
	var phpLearnerSignature = '<?php echo $learner_signature; ?>';
	var phpAssessorSignature = '<?php echo $assessor_signature; ?>';

</script>
<script src="module_eportfolio/assets/wb_common.js"></script>


<script>
	var counter = 0;
	var timer = null;

	function tictac(){
		counter++;
		if(counter >= 240)
			$("#clock").html('Time since last saved: <span class="text-bold">' + counter + '</span> seconds');
		if(counter == 300)
		{
			var html = '<p><span class="text-bold">It has been 5 minutes since you last saved your workbook. </span></p>';
			$("<div></div>").html(html).dialog({
				title: " Please save your information",
				resizable: false,
				modal: true,
				width: 'auto',
				maxWidth: 550,
				height: 'auto',
				maxHeight: 500,
				closeOnEscape: false,
				buttons: {
					'Save': function() {
						$(this).dialog('close');
						partialSave();
					}
				}
			}).css("background", "#FFF");
		}
	}

	function reset()
	{
		clearInterval(timer);
		counter=0;
		$("#clock").html('');
	}
	function startInterval()
	{
		timer= setInterval("tictac()", 1000);
	}
	function stopInterval()
	{
		clearInterval(timer);
	}
	<?php if($_SESSION['user']->type == User::TYPE_LEARNER && in_array($wb->wb_status, array(0,1,4))){ ?>
	startInterval();
	<?php } ?>

	<?php
	if($_SESSION['user']->type == User::TYPE_LEARNER)
	{
		if(in_array($wb->wb_status, array(2,3,5)))
			echo 'window.onbeforeunload = null;';
		else
			echo 'window.onbeforeunload = body_onbeforeunload;';
	}
	if($_SESSION['user']->type == User::TYPE_ASSESSOR)
	{
		if(in_array($wb->wb_status, array(0,1,4,5)))
			echo 'window.onbeforeunload = null;';
		else
			echo 'window.onbeforeunload = body_onbeforeunload;';
	}
	?>

	autosize(document.querySelectorAll('textarea'));

</script>

</html>
