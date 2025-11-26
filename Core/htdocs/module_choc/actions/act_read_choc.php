<?php
class read_choc implements IAction
{
	public function execute(PDO $link)
	{
        $id = isset($_GET['id']) ? $_GET['id'] : '';
		$tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';
		
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid tr_id");        
        }
        $choc = Choc::loadFromDatabase($link, $id);
        if(is_null($choc))
        {
            throw new Exception("Invalid CHOC id");        
        }

		if($choc->choc_status == "CREATED BY LEARNER")
		{
			http_redirect("do.php?_action=edit_choc&id={$choc->id}&tr_id={$tr->id}");
		}

		$_SESSION['bc']->add($link, "do.php?_action=read_choc&id=" . $id, "View CHOC Entry");

        $choc_details = json_decode($choc->choc_details);

        $choc_status_ddl = Choc::getChocDdl(); //[["In Progress", "In Progress"], ["Accepted", "Accepted"], ["Rejected", "Rejected"]];
        // if($choc->choc_status == "In Progress")
        // {
        //     $choc_status_ddl = [["Accepted", "Accepted"], ["Rejected", "Rejected"]];
        // }

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

		$choc_types = [
            ['Break in Learning', 'Break in Learning'],
            ['Change of Employer', 'Change of Employer'],
            ['Change of Learner Details', 'Change of Learner Details'],
            ['Change of LLDD', 'Change of LLDD'],
        ];

		if($_SESSION['user']->type == User::TYPE_LEARNER || in_array($choc->choc_status, ["CREATED BY LEARNER", "REFERRED TO LEARNER"]))
		{
			include('tpl_request_choc.php');
		}
		else
		{
			include('tpl_read_choc.php');
		}
    }
}