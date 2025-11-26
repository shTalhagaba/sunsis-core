<?php
class save_cs_review implements IAction
{
	public function execute(PDO $link)
	{
//		pre($_REQUEST);

		$id = isset($_REQUEST['id'])?$_REQUEST['id']:''; // cs review id
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$section_id = isset($_REQUEST['section_id'])?$_REQUEST['section_id']:'';
		$new_section_id = isset($_REQUEST['new_section_id'])?$_REQUEST['new_section_id']:'';
		$form_name = isset($_REQUEST['form_name'])?$_REQUEST['form_name']:'';

		$cs_review = CSReview::loadFromDatabase($link, $id);
		$cs_review->populate($_REQUEST);

		// Save questions checklist for reviews
		if($form_name == 'frm_review')
		{
			$saved_section = $cs_review->review->xpath('//Assessment/Section[@id="'.$section_id.'"]');

			if(count($saved_section) > 0)
			{
				$dom = dom_import_simplexml($saved_section[0]);
				$dom->parentNode->removeChild($dom);
			}

			$section = $cs_review->review->addChild('Section');
			$section->addAttribute('id', $section_id);
			$this->addReview($section_id, $section, 1);
			$this->addReview($section_id, $section, 2);
			$this->addReview($section_id, $section, 3);

			$cs_review->save($link);

		}
		if($form_name == 'frm_save_all')
		{
			for($section_id = 1; $section_id <= 18; $section_id++)
			{
				$saved_section = $cs_review->review->xpath('//Assessment/Section[@id="'.$section_id.'"]');

				if(count($saved_section) > 0)
				{
					$dom = dom_import_simplexml($saved_section[0]);
					$dom->parentNode->removeChild($dom);
				}

				$section = $cs_review->review->addChild('Section');
				$section->addAttribute('id', $section_id);
				$this->addReview($section_id, $section, 1);
				$this->addReview($section_id, $section, 2);
				$this->addReview($section_id, $section, 3);
			}
			$cs_review->save($link);
			$new_section_id = isset($_REQUEST['page_no_to_come_back'])?$_REQUEST['page_no_to_come_back']:'';
		}
		elseif($form_name == 'frm_action_plan')
		{
			$review_id = isset($_REQUEST['review_id'])?$_REQUEST['review_id']:'';
			$saved_review = $cs_review->action_plan->xpath('//ActionPlan/Review[@id="'.$review_id.'"]');
			if(count($saved_review) > 0)
			{
				$dom = dom_import_simplexml($saved_review[0]);
				$dom->parentNode->removeChild($dom);
			}
			$review = $cs_review->action_plan->addChild('Review');
			$review->addAttribute('id', $review_id);
			$this->addReviewInActionPlan($review_id, $review);

			$cs_review->save($link);

			// set the section so that the relevant tab is pre-selected on tpl_cs_review.php
			if($review_id == 1)
				$new_section_id = 19;
			elseif($review_id == 2)
				$new_section_id = 20;
			elseif($review_id == 3)
				$new_section_id = 21;
		}
		elseif($form_name == 'frm_signatures')
		{
			//pre($_REQUEST);
			$review_id = isset($_REQUEST['review_id'])?$_REQUEST['review_id']:'';
			$review_signature = $cs_review->signatures->xpath('//Signatures/Review[@id="'.$review_id.'"]');
			$review_signature = $review_signature[0];

			if($_SESSION['user']->type == User::TYPE_LEARNER)
			{
				$start_from = strpos($_REQUEST['learner_signature'], '&title=');
				$start_from++;
				$review_signature->Apprentice->SignText = substr($_REQUEST['learner_signature'], $start_from);
				$review_signature->Apprentice->SignDate = date('Y-m-d');
				$start_from = strpos($_REQUEST['manager_signature'], '&title=');
				$start_from++;
				$review_signature->Manager->SignText = substr($_REQUEST['manager_signature'], $start_from);
				$review_signature->Manager->SignDate = date('Y-m-d');
			}
			elseif($_SESSION['user']->type == User::TYPE_ASSESSOR)
			{
				$start_from = strpos($_REQUEST['assessor_signature'], '&title=');
				$start_from++;
				$review_signature->Assessor->SignText = substr($_REQUEST['assessor_signature'], $start_from);
				$review_signature->Assessor->SignDate = date('Y-m-d');
			}

			$cs_review->save($link);

			// set the section so that the relevant tab is pre-selected on tpl_cs_review.php
			$new_section_id = 22;
		}

		http_redirect('do.php?_action=cs_review&id='.$cs_review->id.'&tr_id='.$cs_review->tr_id.'&new_section_id='.$new_section_id);

	}

	private function addReviewInActionPlan($review_id, &$review)
	{
		$action_plan_rows = $review_id == 1 ? 5 : 10;
		for($i = 1; $i <= $action_plan_rows; $i++)
		{
			if(
				$_REQUEST['review'.$review_id.'_Module_row'.$i] == '' &&
				$_REQUEST['review'.$review_id.'_WhatDoYouNeedToDo_row'.$i] == '' &&
				$_REQUEST['review'.$review_id.'_WhenAreYouGoingToDoItBy_row'.$i] == '' &&
				$_REQUEST['review'.$review_id.'_HasItBeenAchieved_row'.$i] == ''
			)
				continue; // blank row so ignore it

			$set = $review->addChild('Set'.$i);
			$set->addChild('Module', htmlspecialchars((string)$_REQUEST['review'.$review_id.'_Module_row'.$i]));
			$set->addChild('WhatDoYouNeedToDo', htmlspecialchars((string)$_REQUEST['review'.$review_id.'_WhatDoYouNeedToDo_row'.$i]));
			$set->addChild('WhenAreYouGoingToDoItBy', htmlspecialchars((string)$_REQUEST['review'.$review_id.'_WhenAreYouGoingToDoItBy_row'.$i]));
			$set->addChild('HasItBeenAchieved', htmlspecialchars((string)$_REQUEST['review'.$review_id.'_HasItBeenAchieved_row'.$i]));
		}
		$review->addChild('AssessorComments', htmlspecialchars((string)$_REQUEST['review'.$review_id.'_AssessorComments']));
		$review->addChild('LearnerComments', htmlspecialchars((string)$_REQUEST['review'.$review_id.'_LearnerComments']));
		$review->addChild('ManagerComments', htmlspecialchars((string)$_REQUEST['review'.$review_id.'_ManagerComments']));
	}

	private function addReview($section_id, &$section, $review_number)
	{
		$review = $section->addChild('Review');
		$review->addAttribute('id', $review_number);
		$review->addAttribute('date', Date::toMySQL($_REQUEST['section'.$section_id.'_review'.$review_number.'_date']));
		if(isset($_REQUEST['section'.$section_id.'_review'.$review_number.'_question']))
			$review->addChild('Questions', implode(',', $_REQUEST['section'.$section_id.'_review'.$review_number.'_question']));
		else
			$review->addChild('Questions', '');
		$comments = $review->addChild('Comments');
/*		$comments->addChild('Apprentice', htmlspecialchars((string)$_REQUEST['section'.$section_id.'_review'.$review_number.'_apprentice_comments']));
		$comments->addChild('Manager', htmlspecialchars((string)$_REQUEST['section'.$section_id.'_review'.$review_number.'_manager_comments']));
		$comments->addChild('Assessor', htmlspecialchars((string)$_REQUEST['section'.$section_id.'_review'.$review_number.'_assessor_comments']));*/
	}
}