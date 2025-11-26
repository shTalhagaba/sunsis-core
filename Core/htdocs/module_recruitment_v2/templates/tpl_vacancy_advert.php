<?php /* @var $vo RecVacancy */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Vacancy Advert</title>
	<link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
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
			<div class="Title" style="margin-left: 6px;">Vacnacy Advert</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-print" onclick="window.location.href='do.php?_action=vacancy_advert&subaction=print'" title="Print-friendly view"></span>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<div class="container bg-gray-light" style="margin-top: 15px; padding: 15px;">

	<div class="jumbotron bg-blue-gradient" style=" padding: 1px;">
		<div class="row">
			<div class="col-sm-2"><img src="images/logos/SUNlogo.png" class="img-responsive" /> </div>
			<div class="col-sm-10">
				<h2 class="text-bold text-center">VACANCY - Proposals Coordinator / Bid Writer</h2>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 ">
			<div style="">
				<p>To work under the supervision of the Pre-construction Director and assist the Estimating Team's in Cambridge, Nottingham and Ossett in developing winning bids. The role requires the review and editing of written content whilst working with other bid team members to produce written bid responses in line with deadlines. The role will not only involve writing and editing content for use in bids, but also presentations, marketing material and all supporting documentation, ensuring a consistent and high-quality approach. The Proposals Co-ordinator will also be responsible for the co-ordination of bids. They will work closely with the Estimating Manager to identify the deliverables for each bid in order to create a structured bid team that will utilise the appropriate skills in order to produce suitable responses. </p>
				<p>The ability to use own initiative is a must in order to enhance a brief to achieve the highest standard of work that not only meets the guidelines set out but also maximises the impact of the message being conveyed within the bid. It would be essential to have experience of working within brand guidelines to produce high quality, consistent materials which form a cohesive bid.</p>
			</div>
		</div>
	</div>

	<div class="jumbotron bg-blue-gradient no-margin" style=" padding: 1px;">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-bold">Main Responsibilities and Key Outcomes</h4>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<ol>
				<li>Organise bid matrix and ensure compliance with client requirements</li>
				<li>Work with the bid team members to produce factual and technical bid responses which are consistent and concise</li>
				<li>Assist in the support team selection and co-ordination process and identify key bid team members based on skills, knowledge and experience</li>
				<li>Create and maintain a Central bid library for use across the region</li>
				<li>Organise and support the bid procedures and tools across the region</li>
				<li>Support the Business Development team in creating high quality marketing material, presentations and other required documentation to present to our customers</li>
			</ol>
		</div>
	</div>

	<div class="jumbotron bg-blue-gradient no-margin" style=" padding: 1px;">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-bold">Key Responsibilities</h4>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">Bid Organisation:</h4>
			<ul>
				<li>Work closely with Bid Managers and Estimating Managers to identify deliverables for each bid</li>
				<li>Prepare and maintain a bid responsibility matrix in-line with the client requirements</li>
				<li>Identify key bid team members and their roles and responsibilities within the team</li>
				<li>Ensure compliance with the client&rsquo;s requirements for bid return and company Branding guidelines to ensure appropriate response format etc.</li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">Bid Writing and Editing:</h4>
			<ul>
				<li>Work with bid team members to produce factual and technical bid responses</li>
				<li>Draft sections of text and edit material provided by key bid team members for inclusion in bid response</li>
				<li>Structure bid responses that appropriately reflect the clients key drivers and requirements</li>
				<li>Proof read bid responses to ensure consistency, both internally and with client documentation</li>
				<li>Ensure quality review procedures are applied by all bid team members</li>
				<li>Monitor and chase bid responses from bid team members according to the responsibility matrix, bid strategy and tender timescale</li>
				<li>Ensure document management and version control is maintained</li>
				<li>Ensure bid responses and material is consistently backed up and secure on the central bid library</li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">Support Team Selection:</h4>
			<ul>
				<li>Assist the Bid Manager in structuring and organising the bid team</li>
				<li>Identify key bid team members with the relevant skills, knowledge and experience to best support the bid</li>
				<li>Build 'Skills And Knowledge Profiles' of key people within the business to assist in appointing the relevant people to support future bids</li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">Document Development:</h4>
			<ul>
				<li>Develop a high quality bid document for submissions across the region consistent with company branding guidelines</li>
				<li>Ensure total compliance with the branding guidelines in order to best represent the business consistently across all 3 offices</li>
				<li>Reflect our brand identity clearly throughout bid documents, presentations, marketing material and all supporting documentation</li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">Bid Library Management:</h4>
			<ul>
				<li>Own, develop and maintain the Central bid library for use across the Central region</li>
				<li>Write, edit and store generic bid content for use in bids, PQQ's, presentations, marketing material and all supporting documentation.</li>
				<li>Create CV&rsquo;s and Project Experience pages for future use in-line with brand guidelines</li>
				<li>Ensure the bid library remains relevant and up to date at all times</li>
				<li>Incorporate lessons learnt and successful bid inclusions for future reference on similar bids</li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">Team Organisation:</h4>
			<ul>
				<li>Organise and support the bid procedures and tools across the region</li>
				<li>Work closely with the Winning Work and Regional Directors, Bid Managers and Estimating Managers to streamline bid procedures and tools across the region</li>
				<li>Produce comprehensive guidance regarding procedures and tools as a point of reference for the regional Winning Work teams</li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">Business Development:</h4>
			<ul>
				<li>Support the Central BD team in the creation of high quality marketing material, presentations and any other relevant documentation for presenting to our customers</li>
				<li>Develop, maintain and store appropriate marketing material / presentation templates in line with branding guidelines for use across the region</li>

			</ul>
		</div>
	</div>

	<div class="jumbotron bg-blue-gradient no-margin" style=" padding: 1px;">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-bold">Person Qualities</h4>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<ul>

				<li>Builds and maintains excellent working relationships</li>
				<li>Balanced judgement</li>
				<li>Acceptance of responsibility and accountability</li>
				<li>Buy-in to and become an example of company values</li>
				<li>Remain calm under pressure</li>
				<li>A positive, proactive approach with a determination to succeed</li>
				<li>Thrive in a busy working environment</li>
			</ul>
		</div>
	</div>

	<div class="jumbotron bg-blue-gradient no-margin" style=" padding: 1px;">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-bold">Skills Required</h4>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">Knowledge</h4>
			<ul>
				<li>Experience as a Proposals Coordinator / Bid Writer in a fast paced organisation</li>
				<li>Experience working within the construction industry</li>
				<li>Proven previous bid team involvement</li>
				<li>Experience of working to brand guidelines</li>
			</ul>
			<h4 class="text-bold">Skills</h4>
			<ul>
				<li>Excellent communicator</li>
				<li>A high attention to detail with outstanding editing and proofing abilities</li>
				<li>Strong literacy skills, with an extensive vocabulary</li>
				<li>Deadline and task driven with effective time management skills</li>
				<li>Ability to multitask, prioritise and manage multiple projects simultaneously</li>
				<li>Confident and accomplished in the use of a variety of graphics and</li>
				<li>Microsoft packages (InDesign, PowerPoint, Word, Excel, Publisher)</li>
			</ul>
		</div>
	</div>

	<div class="jumbotron bg-blue-gradient no-margin" style=" padding: 1px;">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-bold">Qualifications Required</h4>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<ul>
				<li>Level <strong>A</strong> in <strong>Creative Thinking</strong></li>
				<li>Level <strong>A</strong> in <strong>Adaptability</strong></li>
				<li>Level <strong>A</strong> in <strong>Decision Making</strong></li>
				<li>Level <strong>A</strong> in <strong>Interpersonal Skills</strong></li>
				<li>Level <strong>A</strong> in <strong>Quality Focus</strong></li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<div style="box-shadow: 5px 5px 20px 0px rgba(255,105,180,0.95); padding: 25px;">
				<div class="row">
					<div class="col-sm-4">
						<img src="images/logos/SUNlogo.png" class="img-responsive" />
					</div>
					<div class="col-sm-8">
						<dl>
							<dt><span class="text-bold" style="font-size: 18px;"> Micro Engineering Services - London & South</span></dt>
							<dt>33-35 Woodthorpe Road</dt>
							<dt>Ashford</dt>
							<dt>Middlesex</dt>
							<dt>TW15 2RP</dt>
						</dl>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-2"></div>
	</div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.js"></script>

<script>
</script>
</body>
</html>
