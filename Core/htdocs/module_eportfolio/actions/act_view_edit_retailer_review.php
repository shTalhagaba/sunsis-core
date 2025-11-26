<?php
class view_edit_retailer_review implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction == 'getPrevReviews')
		{
			echo $this->getPrevReviews($link);
			exit;
		}

		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
			throw new Exception('Training record not found');

		if($_SESSION['user']->type == User::TYPE_LEARNER && $_SESSION['user']->username != $tr->username)
		{
			throw new UnauthorizedException();
		}

		$_SESSION['bc']->add($link, "do.php?_action=view_edit_retailer_review&id={$id}&tr_id={$tr->id}", "View/Edit Retailer Review");

		if($id == '')
		{
			$review = new RtReview($tr->id);
		}
		else
		{
			$review = RtReview::loadFromDatabase($link, $id);
		}

		$learner_signature = DAO::getSingleValue($link, "SELECT users.signature FROM users WHERE users.username = '{$tr->username}'");
		$assessor_signature = DAO::getSingleValue($link, "SELECT users.signature FROM users WHERE users.id = '{$tr->assessor}'");

		if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] == 'export')
	    {
		    $details = [
		        'learner_signature' => $learner_signature,
				'assessor_signature' => $assessor_signature,
		    ];
		    $this->export_to_pdf($link, $review, $tr, $details);
		    exit;
	    }

		require_once('tpl_view_edit_retailer_review.php');
	}

	private function renderForm(PDO $link, RtReview $reviewObj)
	{
		$review = $reviewObj->review;

		$areas = array(
			'customer' => array(
				'Know' => 'Know the customer profile of the business, appropriate methods for communicating with customers e.g. face to face and remotely, what customers’ purchasing habits are, how to support and increase sales, encourage customer loyalty and achieve repeat business'
				,'Show' => 'Positively interact with customers, using business relevant methods for example face to face or on-line, to support and increase sales by providing useful information and service.'
				,'Live' => 'Adopt an approachable and friendly manner, interacting with customers in line with the style of the business, showing a genuine interest in meeting their needs and actively seeking feedback to improve own quality of service provision'
			),
			'communication' => array(
				'Know' => 'Know how to identify and determine individuals’ situation and how to respond in the most appropriate way in line with the business culture (for example; the difference in how a branded goods retailer would communicate to their customers would be very different from an individual that retails a funeral service, or someone that needs to convey technical product information)'
				,'Show' => 'Use effective methods of communication that achieve the desired result, according to the purchasing process e.g. face to face, via the telephone or on-line.'
				,'Live' => 'Take a positive interest in customers, actively listening or taking due care to understand written or online communications and respond appropriately.'
			),
			'technical' => array(
				'Know' => 'Know how to operate technology such as customer payments and understand how changing technology, for example social media, digital and multichannel tools, support the sale of products and facilitates an effective and efficient service to customers.'
				,'Show' => 'Use technology appropriately and efficiently in line with company policy, to support sales and service ensuring that maintenance issues are dealt with promptly'
				,'Live' => 'Embrace the use of technology, use it responsibly and take an interest in new developments. For example in social media, that could support the business.'
			),
			'performance' => array(
				'Know' => 'Understand how personal performance contributes to the success of the business for example the sale of products and services, increasing sales and achieving customer loyalty.'
				,'Show' => 'Challenge personal methods of working and actively implement improvements.'
				,'Live' => 'Take responsibility for own performance, learning and development, striving to accomplish the best results and take a flexible and adaptable approach to work.'
			),
			'team' => array(
				'Know' => 'Know how to support and influence the team positively, recognising how all colleagues and teams are dependent on each other to meet business objectives.'
				,'Show' => 'Support team members to ensure that the services provided are of a high quality, delivered on time and as required.'
				,'Live' => 'Demonstrate pride in own role through a consistently positive and professional approach, and be aware of the impact of personal behaviour within the team.'
			),
			'product_and_service' => array(
				'Know' => 'Know information on the brands, products and services as required by the business (for example in large retailers a general knowledge of a range of products and services may be needed, but in specialist outlets a detailed knowledge on the technical specification of a product and the aftercare service may be necessary).'
				,'Show' => 'Help match products and services to customers’ needs and increase the amount they spend for example through the sale of associated products and services.'
				,'Live' => 'Confidently demonstrate a belief in the products and services the business offers.'
			),
			'business' => array(
				'Know' => 'Know the vision, objectives and brand standards of the business and how to contribute towards their success.'
				,'Show' => 'Establish a good rapport with customers, serve them in line with brand standards and promote the values of the business in all work activities.'
				,'Live' => 'Demonstrate personal drive and a positive regard for the reputation and aim of the business.'
			),
			'brand_reputation' => array(
				'Know' => 'Know and understand the importance of brand and business reputation and what can affect it.'
				,'Show' => 'Respond to situations that threaten brand and business reputation in line with company policy and alert the relevant person if a threat is Identified.'
				,'Live' => 'Uphold and personally demonstrate a positive brand and business reputation at all times.'
			),
			'marketing' => array(
				'Know' => 'Know how the business positions itself in order to increase its market share and compete against its main competitors for example its unique selling points, its straplines, promotions and advertising campaigns.'
				,'Show' => 'Influence customers’ purchasing decisions by providing accurate guidance on product and price comparisons and sharing knowledge on local offers and variances'
				,'Live' => 'Take an interest in the position of the Business within the wider industry.'
			),
			'stock' => array(
				'Know' => 'Know how to maintain appropriate levels of the right stock to meet customer demand, taking into account planned marketing activities and expected seasonal variations and the conditions they must be stored in.'
				,'Show' => 'Maintain appropriate levels of the right stock to meet customer demand, ensure it is kept in the correct condition (for example correct temperature, environment, packaging), and minimise stock loss through accurate administration, minimising wastage and theft.'
				,'Live' => 'Take ownership and responsibility to identify stock issues and take action to address them.'
			),
			'sales_and_promotion' => array(
				'Know' => 'Understand the sales opportunities that exist across the year within the business and industry and the need to know customers’ buying habits during these periods, seasonal product / service knowledge, and stock requirements at different times of the year.'
				,'Show' => 'Use a variety of sales techniques when providing customers with information that are appropriate to the business and actively sell the benefits of seasonal offers for example, through in-store or on-line promotions.'
				,'Live' => 'Pro-actively seek ways of enhancing sales whilst being sensitive to the needs of the customer and encourage team members to do the same.'
			),
			'marchandising' => array(
				'Know' => 'Understand how to increase sales through product placement by utilising ‘hot spots’ and recognizing the relationship between sales and space.'
				,'Show' => 'Actively use techniques to optimise sales through effective product placement, ensuring product displays remain attractive, appealing and safe to customers.'
				,'Live' => 'Make recommendations for merchandising as necessary to enhance sales and customer satisfaction.'
			),
			'legal_and_governance' => array(
				'Know' => 'Recognise and understand legislative responsibilities relating to the business and the products, the importance of protecting peoples’ health, safety and security, and the consequences of not following legal guidelines.'
				,'Show' => 'Comply with legal requirements to minimise risk and inspire customer confidence; minimising disruption to the business and maintaining the safety and security of people at all times'
				,'Live' => 'Work with integrity in an honest and trustworthy manner putting personal safety and that of others first.'
			),
			'diversity' => array(
				'Know' => 'Understand how to work with people from a wide range of backgrounds and cultures and recognize how local demographics can impact on the product range of the businesses'
				,'Show' => 'Put people at ease in all matters helping them to feel welcome and supported and provide them with information that is relevant to their needs.'
				,'Live' => 'Operate in an empathic, fair and professional manner.'
			),
			'financial' => array(
				'Know' => 'Understand the principles of operating commercially and supporting the overall financial performance of the business for example by aiming to exceed targeted sales and reduce wastage and returns.'
				,'Show' => 'Deliver a sales service that meets customers’ needs and balances the financial performance of the business for example working towards sales targets, following procedures relating to packing of goods and dealing with returned products.'
				,'Live' => 'Act credibly and with integrity on all matters that affect financial performance.'
			),
			'environment' => array(
				'Know' => 'Know how to take responsible decisions to minimise negative effects on the environment in all work activities.'
				,'Show' => 'Minimise the effect of work activities on the environment through managing wastage and loss according to business procedures.'
				,'Live' => 'Demonstrate personal commitment to minimising the effect of work activities on the environment and make recommendations for improvement if identified.'
			)
		);

		$rows = '';
		foreach($areas AS $area => $detail)
		{
			$area_xml = $review->xpath('//Area[@name="'.$area.'"]');
			$area_xml = isset($area_xml[0])?$area_xml[0]:null;
			if(is_null($area_xml))
				continue;
			$rows .= <<<HEADER_ROW
<tr class="bg-gray">
	<th rowspan="2" style="vertical-align: middle; width: 5%;">Area of standard</th>
	<th rowspan="2" style="vertical-align: middle; width: 20%;">Key learning goals What did you want to achieve by today?</th>
	<th colspan="2" class="text-center">Progress towards achievement</th>
	<th rowspan="2" style="vertical-align: middle; width: 20%;"">Supporting evidence</th>
	<th rowspan="2" style="vertical-align: middle; width: 10%;">Status</th>
	<th rowspan="2" style="vertical-align: middle;">Date</th>
</tr>
<tr class="bg-gray">
	<th style=" width: 20%;">What have you achieved?</th>
	<th style=" width: 20%;">What do you still need to work on or what new goals do you have?</th>
</tr>
HEADER_ROW;
			$rows .= '<tr>';
			if($area == 'marchandising')
				$rows .= '<td class="bg-green text-center" style="cursor: pointer; vertical-align: middle;" onclick="showPrevReviews(\''.$reviewObj->id.'\', \''.$reviewObj->tr_id.'\', \''.$area.'\');"><h4 class="text-bold">'. ucfirst(str_replace('_', '&nbsp;', 'Merchandising')) .'</h4></td>';
			else
				$rows .= '<td class="bg-green text-center" style="cursor: pointer; vertical-align: middle;" onclick="showPrevReviews(\''.$reviewObj->id.'\', \''.$reviewObj->tr_id.'\', \''.$area.'\');"><h4 class="text-bold">'. ucfirst(str_replace('_', '&nbsp;', $area)) .'</h4></td>';
			$rows .= '<td><textarea class="small" name="'.$area.'KeyLearningGoals" style="width: 100%;" rows="10">'.$area_xml->KeyLearningGoals->__toString().'</textarea> </td>';
			$rows .= '<td><textarea class="small" name="'.$area.'WhatHaveYouAchieved" style="width: 100%;" rows="10">'.$area_xml->WhatHaveYouAchieved->__toString().'</textarea> </td>';
			$rows .= '<td><textarea class="small" name="'.$area.'NewGoals" style="width: 100%;" rows="10">'.$area_xml->NewGoals->__toString().'</textarea> </td>';
			$rows .= '<td><textarea class="small" name="'.$area.'SupportingEvidence" style="width: 100%;" rows="10">'.$area_xml->SupportingEvidence->__toString().'</textarea> </td>';
			$rows .= '<td style="vertical-align: middle;">';
			if($area_xml->Status->__toString() == "B")
				$rows .= '<div class="form-check"><input type="radio" class="form-check-input" id="'.$area.'StatusBehind" name="'.$area.'Status[]" value="B" checked="checked"><label class="form-check-label" for="'.$area.'StatusBehind"> &nbsp; Behind</label></div>';
			else
				$rows .= '<div class="form-check"><input type="radio" class="form-check-input" id="'.$area.'StatusBehind" name="'.$area.'Status[]" value="B"><label class="form-check-label" for="'.$area.'StatusBehind"> &nbsp; Behind</label></div>';
			if($area_xml->Status->__toString() == "O")
				$rows .= '<div class="form-check"><input type="radio" class="form-check-input" id="'.$area.'StatusOnTrack" name="'.$area.'Status[]" value="O" checked="checked"><label class="form-check-label" for="'.$area.'StatusOnTrack"> &nbsp; On Track</label></div>';
			else
				$rows .= '<div class="form-check"><input type="radio" class="form-check-input" id="'.$area.'StatusOnTrack" name="'.$area.'Status[]" value="O"><label class="form-check-label" for="'.$area.'StatusOnTrack"> &nbsp; On Track</label></div>';
			if($area_xml->Status->__toString() == "A")
				$rows .= '<div class="form-check"><input type="radio" class="form-check-input" id="'.$area.'StatusAhead" name="'.$area.'Status[]" value="A" checked="checked"><label class="form-check-label" for="'.$area.'StatusAhead"> &nbsp; Ahead</label></div>';
			else
				$rows .= '<div class="form-check"><input type="radio" class="form-check-input" id="'.$area.'StatusAhead" name="'.$area.'Status[]" value="A"><label class="form-check-label" for="'.$area.'StatusAhead"> &nbsp; Ahead</label></div>';
			if($area_xml->Status->__toString() == "C")
				$rows .= '<div class="form-check"><input type="radio" class="form-check-input" id="'.$area.'StatusComplete" name="'.$area.'Status[]" value="C" checked="checked"><label class="form-check-label" for="'.$area.'StatusComplete"> &nbsp; Complete</label></div>';
			else
				$rows .= '<div class="form-check"><input type="radio" class="form-check-input" id="'.$area.'StatusComplete" name="'.$area.'Status[]" value="C"><label class="form-check-label" for="'.$area.'StatusComplete"> &nbsp; Complete</label></div>';
			$rows .= '</td>';
			$rows .= '<td style="vertical-align: middle;">'. HTML::datebox($area.'Date', $area_xml->Date->__toString()) . '</td>';
			$rows .= '</tr>';
			$rows .= '<tr class="bg-info"><th colspan="2" class="text-bold ">Know it</th><th colspan="2" class="text-bold ">Show it</th><th colspan="3" class="text-bold ">Live it</th></tr>';
			$rows .= '<tr class="small"><td colspan="2">'.$detail['Know'].'</td><td colspan="2">'.$detail['Show'].'</td><td colspan="3">'.$detail['Live'].'</td></tr>';
			$rows .= '<tr><td class="text-center bg-light-blue-gradient" colspan="7" style="line-height: 1px;"></td></tr>';
		}

		$rows .= '<tr><th>&nbsp;</th><th colspan="5">Comments on current status and actions/support needed before next review</th><th>Date</th></tr>';
		$rows .= '<tr><th style="cursor: pointer; vertical-align: middle;" onclick="showPrevReviews(\''.$reviewObj->id.'\', \''.$reviewObj->tr_id.'\', \'comments1\');">English / Maths</th><td colspan="5" class="small"><textarea rows="5" name="comments1" style="width: 100%;">' . $reviewObj->comments1 . '</textarea></td><td style="vertical-align: middle;">'. HTML::datebox('comments1_date', $reviewObj->comments1_date) . '</td></tr>';
		$rows .= '<tr><th style="cursor: pointer; vertical-align: middle;" onclick="showPrevReviews(\''.$reviewObj->id.'\', \''.$reviewObj->tr_id.'\', \'comments2\');">Preparation  for end assessment</th><td colspan="5" class="small"><textarea rows="5" name="comments2" style="width: 100%;">' . $reviewObj->comments2 . '</textarea></td><td style="vertical-align: middle;">'. HTML::datebox('comments2_date', $reviewObj->comments2_date) . '</td></tr>';

		echo <<<HTML
<table class="table table-bordered">
	$rows
</table>
HTML;


	}

	private function getPrevReviews(PDO $link)
	{
		$review_id = isset($_REQUEST['review_id'])?$_REQUEST['review_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$section_name = isset($_REQUEST['section_name'])?$_REQUEST['section_name']:'';

		if($section_name == 'comments1' || $section_name == 'comments2')
		{
			$result = DAO::getResultset($link, "SELECT `{$section_name}`, comments1_date, comments2_date, modified FROM retailer_reviews WHERE tr_id = '{$tr_id}' AND id != '{$review_id}' ORDER BY id DESC", DAO::FETCH_ASSOC);
			if(count($result) == 0)
			{
				return "No records found";
			}
			$html = '<div class="table-responsive"><table class="table table-bordered small"> ';
			foreach($result AS $row)
			{
				$html .= '<tr><th colspan="3" class="text-bold bg-gray">Last Modified DateTime: ' . Date::to($row['modified'], Date::DATETIME) . '</th></tr>';
				$html .= '<tr>';
				$html .= $section_name == 'comments1' ? '<th>English / Maths</th>' : '<th>Preparation for end assessment</th>';
				$html .= '<td>' . $row[$section_name] . '</td>';
				$html .= '<td>' . Date::toShort($row[$section_name.'_date']) . '</td>';
				$html .= '</tr>';
			}
			$html .= '</table></div>';
			return $html;
		}

		$result = DAO::getResultset($link, "SELECT review, modified FROM retailer_reviews WHERE tr_id = '{$tr_id}' AND id != '{$review_id}' ORDER BY id DESC ", DAO::FETCH_ASSOC);
		if(count($result) == 0)
		{
			return "No records found";
		}
		$status = array(
			'B' => 'Behind'
			,'O' => 'On Track'
			,'A' => 'Ahead'
			,'C' => 'Complete'
			,'' => ''
		);
		$html = '<div class="table-responsive"><table class="table table-bordered small"> ';
		$html .= '<tr><th colspan="2" class="text-bold text-center">' . ucfirst(str_replace('_', '&nbsp;', $section_name)) . '</th></tr>';
		foreach($result AS $row)
		{
			$review_xml = XML::loadSimpleXML($row['review']);
			$area_xml = $review_xml->review->xpath('//Review/Area[@name="'.$section_name.'"]');
			if(!isset($area_xml[0]))
				continue;

			$html .= '<tr><th colspan="2" class="text-bold bg-gray">Last Modified DateTime: ' . Date::to($row['modified'], Date::DATETIME) . '</th></tr>';
			$html .= '<tr><th>Key Learning Goals</th><td>' . $area_xml[0]->KeyLearningGoals->__toString() . '</td></tr>';
			$html .= '<tr><th>What have you achieved</th><td>' . $area_xml[0]->WhatHaveYouAchieved->__toString() . '</td></tr>';
			$html .= '<tr><th>New Goals</th><td>' . $area_xml[0]->NewGoals->__toString() . '</td></tr>';
			$html .= '<tr><th>Supporting Evidence</th><td>' . $area_xml[0]->SupportingEvidence->__toString() . '</td></tr>';
			$html .= isset($status[$area_xml[0]->Status->__toString()]) ? '<tr><th>Status</th><td>' . $status[$area_xml[0]->Status->__toString()] . '</td></tr>' : '<tr><th>Status</th><td></td></tr>';
			$html .= $area_xml[0]->Date->__toString() != '' ? '<tr><th>Date</th><td>' . Date::toShort($area_xml[0]->Date->__toString()) . '</td></tr>' : '<tr><th>Date</th><td></td></tr>';
		}
		$html .= '</table></div>';
		return $html;
	}

	public function export_to_pdf(PDO $link, RtReview $reviewObj, TrainingRecord $tr, $details)
	{
		$review = $reviewObj->review;

		include_once("./MPDF57/mpdf.php");

		$mpdf=new mPDF('','A4-L','9','',5,5,22,5,5,5);

		$mpdf->setAutoBottomMargin = 'stretch';
		
		$logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

		$header = <<<HEADER
        <div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "30%" align="left">
						<h3 style="color: blue;">{$tr->firstnames} {$tr->surname}</h3>
					</td>
					<td width = "30%" align="left">
						<h2>Retailer Diploma Review</h2>
					</td>
					<td width = "40%" align="right"><img class="img-responsive" src="$logo" height="1.50cm" width="5cm"  /></td>
				</tr>
			</table>
		</div>

HEADER;

            $mpdf->SetHTMLHeader($header);

		$sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
		$sunesis_stamp = substr($sunesis_stamp, 0, 10);
		$date = date('d/m/Y H:i:s');
		$footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "50%" align="left">{$date}</td>
					<td width = "50%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

		//Beginning Buffer to save PHP variables and HTML tags
		ob_start();

		$areas = array(
			'customer' => array(
				'Know' => 'Know the customer profile of the business, appropriate methods for communicating with customers e.g. face to face and remotely, what customers purchasing habits are, how to support and increase sales, encourage customer loyalty and achieve repeat business'
				,'Show' => 'Positively interact with customers, using business relevant methods for example face to face or on-line, to support and increase sales by providing useful information and service.'
				,'Live' => 'Adopt an approachable and friendly manner, interacting with customers in line with the style of the business, showing a genuine interest in meeting their needs and actively seeking feedback to improve own quality of service provision'
			),
			'communication' => array(
				'Know' => 'Know how to identify and determine individuals situation and how to respond in the most appropriate way in line with the business culture (for example; the difference in how a branded goods retailer would communicate to their customers would be very different from an individual that retails a funeral service, or someone that needs to convey technical product information)'
				,'Show' => 'Use effective methods of communication that achieve the desired result, according to the purchasing process e.g. face to face, via the telephone or on-line.'
				,'Live' => 'Take a positive interest in customers, actively listening or taking due care to understand written or online communications and respond appropriately.'
			),
			'technical' => array(
				'Know' => 'Know how to operate technology such as customer payments and understand how changing technology, for example social media, digital and multichannel tools, support the sale of products and facilitates an effective and efficient service to customers.'
				,'Show' => 'Use technology appropriately and efficiently in line with company policy, to support sales and service ensuring that maintenance issues are dealt with promptly'
				,'Live' => 'Embrace the use of technology, use it responsibly and take an interest in new developments. For example in social media, that could support the business.'
			),
			'performance' => array(
				'Know' => 'Understand how personal performance contributes to the success of the business for example the sale of products and services, increasing sales and achieving customer loyalty.'
				,'Show' => 'Challenge personal methods of working and actively implement improvements.'
				,'Live' => 'Take responsibility for own performance, learning and development, striving to accomplish the best results and take a flexible and adaptable approach to work.'
			),
			'team' => array(
				'Know' => 'Know how to support and influence the team positively, recognising how all colleagues and teams are dependent on each other to meet business objectives.'
				,'Show' => 'Support team members to ensure that the services provided are of a high quality, delivered on time and as required.'
				,'Live' => 'Demonstrate pride in own role through a consistently positive and professional approach, and be aware of the impact of personal behaviour within the team.'
			),
			'product_and_service' => array(
				'Know' => 'Know information on the brands, products and services as required by the business (for example in large retailers a general knowledge of a range of products and services may be needed, but in specialist outlets a detailed knowledge on the technical specification of a product and the aftercare service may be necessary).'
				,'Show' => 'Help match products and services to customers needs and increase the amount they spend for example through the sale of associated products and services.'
				,'Live' => 'Confidently demonstrate a belief in the products and services the business offers.'
			),
			'business' => array(
				'Know' => 'Know the vision, objectives and brand standards of the business and how to contribute towards their success.'
				,'Show' => 'Establish a good rapport with customers, serve them in line with brand standards and promote the values of the business in all work activities.'
				,'Live' => 'Demonstrate personal drive and a positive regard for the reputation and aim of the business.'
			),
			'brand_reputation' => array(
				'Know' => 'Know and understand the importance of brand and business reputation and what can affect it.'
				,'Show' => 'Respond to situations that threaten brand and business reputation in line with company policy and alert the relevant person if a threat is Identified.'
				,'Live' => 'Uphold and personally demonstrate a positive brand and business reputation at all times.'
			),
			'marketing' => array(
				'Know' => 'Know how the business positions itself in order to increase its market share and compete against its main competitors for example its unique selling points, its straplines, promotions and advertising campaigns.'
				,'Show' => 'Influence customers purchasing decisions by providing accurate guidance on product and price comparisons and sharing knowledge on local offers and variances'
				,'Live' => 'Take an interest in the position of the Business within the wider industry.'
			),
			'stock' => array(
				'Know' => 'Know how to maintain appropriate levels of the right stock to meet customer demand, taking into account planned marketing activities and expected seasonal variations and the conditions they must be stored in.'
				,'Show' => 'Maintain appropriate levels of the right stock to meet customer demand, ensure it is kept in the correct condition (for example correct temperature, environment, packaging), and minimise stock loss through accurate administration, minimising wastage and theft.'
				,'Live' => 'Take ownership and responsibility to identify stock issues and take action to address them.'
			),
			'sales_and_promotion' => array(
				'Know' => 'Understand the sales opportunities that exist across the year within the business and industry and the need to know customers buying habits during these periods, seasonal product / service knowledge, and stock requirements at different times of the year.'
				,'Show' => 'Use a variety of sales techniques when providing customers with information that are appropriate to the business and actively sell the benefits of seasonal offers for example, through in-store or on-line promotions.'
				,'Live' => 'Pro-actively seek ways of enhancing sales whilst being sensitive to the needs of the customer and encourage team members to do the same.'
			),
			'marchandising' => array(
				'Know' => 'Understand how to increase sales through product placement by utilising hot spots and recognizing the relationship between sales and space.'
				,'Show' => 'Actively use techniques to optimise sales through effective product placement, ensuring product displays remain attractive, appealing and safe to customers.'
				,'Live' => 'Make recommendations for merchandising as necessary to enhance sales and customer satisfaction.'
			),
			'legal_and_governance' => array(
				'Know' => 'Recognise and understand legislative responsibilities relating to the business and the products, the importance of protecting peoples health, safety and security, and the consequences of not following legal guidelines.'
				,'Show' => 'Comply with legal requirements to minimise risk and inspire customer confidence; minimising disruption to the business and maintaining the safety and security of people at all times'
				,'Live' => 'Work with integrity in an honest and trustworthy manner putting personal safety and that of others first.'
			),
			'diversity' => array(
				'Know' => 'Understand how to work with people from a wide range of backgrounds and cultures and recognize how local demographics can impact on the product range of the businesses'
				,'Show' => 'Put people at ease in all matters helping them to feel welcome and supported and provide them with information that is relevant to their needs.'
				,'Live' => 'Operate in an empathic, fair and professional manner.'
			),
			'financial' => array(
				'Know' => 'Understand the principles of operating commercially and supporting the overall financial performance of the business for example by aiming to exceed targeted sales and reduce wastage and returns.'
				,'Show' => 'Deliver a sales service that meets customers needs and balances the financial performance of the business for example working towards sales targets, following procedures relating to packing of goods and dealing with returned products.'
				,'Live' => 'Act credibly and with integrity on all matters that affect financial performance.'
			),
			'environment' => array(
				'Know' => 'Know how to take responsible decisions to minimise negative effects on the environment in all work activities.'
				,'Show' => 'Minimise the effect of work activities on the environment through managing wastage and loss according to business procedures.'
				,'Live' => 'Demonstrate personal commitment to minimising the effect of work activities on the environment and make recommendations for improvement if identified.'
			)
		);

		$rows = '';
		foreach($areas AS $area => $detail)
		{
			$area_xml = $review->xpath('//Area[@name="'.$area.'"]');
			$area_xml = isset($area_xml[0])?$area_xml[0]:null;
			if(is_null($area_xml))
				continue;
			$rows .= <<<HEADER_ROW
<tr  style="color: #000; background-color: #d2d6de !important">
	<th rowspan="2" style="vertical-align: middle; width: 5%;">Area of standard</th>
	<th rowspan="2" style="vertical-align: middle; ">Key learning goals What did you want to achieve by today?</th>
	<th colspan="2" class="text-center">Progress towards achievement</th>
	<th rowspan="2" style="vertical-align: middle; ">Supporting evidence</th>
	<th rowspan="2" style="vertical-align: middle; width: 10%;">Status</th>
	<th rowspan="2" style="vertical-align: middle;">Date</th>
</tr>
<tr  style="color: #000; background-color: #d2d6de !important">
	<th>What have you achieved?</th>
	<th>What do you still need to work on or what new goals do you have?</th>
</tr>
HEADER_ROW;
			$rows .= '<tr>';
			if($area == 'marchandising')
				$rows .= '<td style="color: #000; background-color: #ADD8E6 !important"><h4>'. ucfirst(str_replace('_', '&nbsp;', 'Merchandising')) .'</h4></td>';
			else
				$rows .= '<td style="color: #000; background-color: #ADD8E6 !important"><h4>'. ucfirst(str_replace('_', '&nbsp;', $area)) .'</h4></td>';
			$rows .= '<td>'.$area_xml->KeyLearningGoals->__toString().'</td>';
			$rows .= '<td>'.$area_xml->WhatHaveYouAchieved->__toString().'</td>';
			$rows .= '<td>'.$area_xml->NewGoals->__toString().'</td>';
			$rows .= '<td>'.$area_xml->SupportingEvidence->__toString().'</td>';
			$rows .= '<td style="vertical-align: middle;">';
			if($area_xml->Status->__toString() == "B")
				$rows .= '<div class="form-check"> &nbsp; Behind</div>';
			else
				$rows .= '<div class="form-check"></div>';
			if($area_xml->Status->__toString() == "O")
				$rows .= '<div class="form-check"> &nbsp; On Track</div>';
			else
				$rows .= '<div class="form-check"></div>';
			if($area_xml->Status->__toString() == "A")
				$rows .= '<div class="form-check"> &nbsp; Ahead</div>';
			else
				$rows .= '<div class="form-check"></div>';
			if($area_xml->Status->__toString() == "C")
				$rows .= '<div class="form-check"> &nbsp; Complete</div>';
			else
				$rows .= '<div class="form-check"></div>';
			$rows .= '</td>';
			$rows .= '<td style="vertical-align: middle;">'. $area_xml->Date->__toString() . '</td>';
			$rows .= '</tr>';
			$rows .= '<tr  style="color: #000; background-color: #d2d6de !important"><th colspan="2" class="text-bold ">Know it</th><th colspan="2" class="text-bold ">Show it</th><th colspan="3" class="text-bold ">Live it</th></tr>';
			$rows .= '<tr class="small"><td colspan="2">'.$detail['Know'].'</td><td colspan="2">'.$detail['Show'].'</td><td colspan="3">'.$detail['Live'].'</td></tr>';
			
		}

		$rows .= '<tr><th>&nbsp;</th><th colspan="5">Comments on current status and actions/support needed before next review</th><th>Date</th></tr>';
		$rows .= '<tr><th style="vertical-align: middle;">English / Maths</th><td colspan="5" class="small">' . $reviewObj->comments1 . '</td><td style="vertical-align: middle;">'. Date::toShort($reviewObj->comments1_date) . '</td></tr>';
		$rows .= '<tr><th style="vertical-align: middle;">Preparation  for end assessment</th><td colspan="5" class="small">' . $reviewObj->comments2 . '</td><td style="vertical-align: middle;">'. Date::toShort($reviewObj->comments2_date) . '</td></tr>';

		$learner_sign = '';
		if($reviewObj->learner_signature != '')
		{
			$signature_parts = explode('&', $reviewObj->learner_signature);
			$title = explode('=', $signature_parts[0]);
			$font = explode('=', $signature_parts[1]);
			$size = explode('=', $signature_parts[2]);
			$signature1 = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
			$learner_sign = tempnam(sys_get_temp_dir(), 'TMP_');
			imagepng($signature1, $learner_sign, 0, NULL);
			$learner_sign = '<img id="img_a_sign" src="'.$learner_sign.'" style="border: 2px solid;border-radius: 15px;" />';
		}
		$reviewObj->l_sign_date = Date::toShort($reviewObj->l_sign_date);

		$assessor_signature = '';
		if($reviewObj->assessor_signature != '')
		{
			$signature_parts = explode('&', $reviewObj->assessor_signature);
			$title = explode('=', $signature_parts[0]);
			$font = explode('=', $signature_parts[1]);
			$size = explode('=', $signature_parts[2]);
			$signature1 = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
			$assessor_signature = tempnam(sys_get_temp_dir(), 'TMP_');
			imagepng($signature1, $assessor_signature, 0, NULL);
			$assessor_signature = '<img id="img_a_sign" src="'.$assessor_signature.'" style="border: 2px solid;border-radius: 15px;" />';
		}
		$reviewObj->a_sign_date = Date::toShort($reviewObj->a_sign_date);
		$assessor_name = $assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->assessor}'");

		echo <<<HTML
<table border="1" style="width: 100%;" cellpadding="6">
	$rows
</table>
<hr>
<table border="1" style="width: 100%;" cellpadding="6">
<caption style="background-color: #90ee90;"><h3>Signatures</h3></caption>
	<tr>
		<td style="width: 10%;">Learner</td>
		<td style="width: 30%;">{$tr->firstnames} {$tr->surname}</td>
		<td style="width: 40%;">{$learner_sign}</td>
		<td style="width: 10%;">{$reviewObj->l_sign_date}</td>
	</tr>
	<tr>
		<td style="width: 10%;">Assessor</td>
		<td style="width: 30%;">$assessor_name</td>
		<td style="width: 40%;">{$assessor_signature}</td>
		<td style="width: 10%;">{$reviewObj->a_sign_date}</td>
	</tr>

</table>
HTML;

		


		$html = ob_get_contents();

		$mpdf->SetHTMLFooter($footer);
		ob_end_clean();

		$mpdf->WriteHTML($html);

		//$mpdf->Output('asd', 'I');
		$mpdf->Output('RetailerDiplomaReview.pdf', 'D');
	}
}