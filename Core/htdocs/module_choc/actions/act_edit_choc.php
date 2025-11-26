<?php
class edit_choc implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';
		$from = isset($_GET['from']) ? $_GET['from'] : '';
		
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid tr_id");        
        }

		$_SESSION['bc']->add($link, "do.php?_action=edit_choc&id=" . $id, "Add/ Edit CHOC Entry");

		if($id == '')
		{
			$choc = new Choc();
		}
		else
		{
			$choc = Choc::loadFromDatabase($link, $id);
		}

        $choc_types = [
            ['Break in Learning', 'Break in Learning'],
            ['Change of Employer', 'Change of Employer'],
            ['Change of Learner Details', 'Change of Learner Details'],
            ['Change of LLDD', 'Change of LLDD'],
        ];

        $employers_list = DAO::getResultset($link, "SELECT id, legal_name FROM organisations WHERE organisations.organisation_type = 2 AND organisations.id != '{$tr->employer_id}' ORDER BY legal_name");

        $ddlLldd = [
			['1', 'Yes'],
			['2', 'No'],
			['3', 'Prefer not to say']
		];
		$ddlLlddCat = array(
			'4' => 'Visual impairment',
			'5' => 'Hearing impairment',
			'6' => 'Disability affecting mobility',
			'7' => 'Profound complex disabilities',
			'8' => 'Social and emotional difficulties',
			'9' => 'Mental health difficulty',
			'10' => 'Moderate learning difficulty',
			'11' => 'Severe learning difficulty',
			'12' => 'Dyslexia',
			'13' => 'Dyscalculia',
			'14' => 'Autism spectrum disorder',
			'15' => 'Asperger\'s syndrome',
			'16' => 'Temporary disability after illness (for example post-viral) or accident',
			'17' => 'Speech, Language and Communication Needs',
			'93' => 'Other physical disability',
			'94' => 'Other specific learning difficulty (e.g. Dyspraxia)',
			'95' => 'Other medical condition (for example epilepsy, asthma, diabetes)',
			'96' => 'Other learning difficulty',
			'97' => 'Other disability',
			'98' => 'Prefer not to say'
		);

		if($_SESSION['user']->type == User::TYPE_LEARNER || in_array($choc->choc_status, ["CREATED BY LEARNER", "REFERRED TO LEARNER"]))
		{
			if($from == 'create')
			{
				include('tpl_edit_choc.php');
			}
			else
			{
				include('tpl_request_choc.php');
			}
		}
		else
		{
			include('tpl_edit_choc.php');
		}
    }
}