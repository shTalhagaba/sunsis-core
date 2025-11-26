<?php
class WBPersonalTeamPerformance extends Workbook
{
    public function __construct($tr_id)
    {
        $this->wb_title = 'WBPersonalTeamPerformance';
        $this->tr_id = $tr_id;
        $this->wb_content = self::getBlankXML();
    }

    /**
     * @return xml
     * @param null
     */
    public static function getBlankXML()
    {
	    $xml = <<<XML
<Workbook title="personal_team_performance">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<Team></Team>
		<EffectiveTeam></EffectiveTeam>
		<TeamDynamics></TeamDynamics>
		<PersuationAndInfluencingSkills>
			<InsideWork></InsideWork>
			<OutsideWork></OutsideWork>
			<YourContributionInTeamMeetings></YourContributionInTeamMeetings>
			<ExamplesOfWorkingAndSupporting></ExamplesOfWorkingAndSupporting>
			<HowYourTeamSupportOthers></HowYourTeamSupportOthers>
		</PersuationAndInfluencingSkills>
		<ImplicationsOfNotWorkingTogether>
			<ExampleGap1></ExampleGap1>
			<ExampleGap2></ExampleGap2>
			<ExampleGap3></ExampleGap3>
			<DetailNotes></DetailNotes>
		</ImplicationsOfNotWorkingTogether>
		<PositiveInfluencing>
			<What></What>
			<Why></Why>
		</PositiveInfluencing>
		<RolesResp>
			<Roles>
				<SM></SM>
				<AM></AM>
				<TL></TL>
				<BS></BS>
				<SA></SA>
				<App></App>
				<Pha></Pha>
			</Roles>
			<HowToShowInterest></HowToShowInterest>
			<EqualOpportunitiesPolicy></EqualOpportunitiesPolicy>
			<TheHub></TheHub>
			<DailySalesTarget></DailySalesTarget>
			<StoreManager></StoreManager>
			<YourOwnRoles>
				<Set1>
					<Type></Type>
					<WhereLocated></WhereLocated>
				</Set1>
				<Set2>
					<Type></Type>
					<WhereLocated></WhereLocated>
				</Set2>
			</YourOwnRoles>
		</RolesResp>
		<Questions>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
			<Question5></Question5>
			<Question6></Question6>
			<Question7></Question7>
			<Question8></Question8>
		</Questions>
		<Objective>
			<DailyWorkObj></DailyWorkObj>
			<OtherWorkObj></OtherWorkObj>
			<BusinessWorkObj></BusinessWorkObj>
			<SMARTObjectives>
				<Objective1>
					<Type></Type>
					<Comments></Comments>
				</Objective1>
				<Objective2>
					<Type></Type>
					<Comments></Comments>
				</Objective2>
				<Objective3>
					<Type></Type>
					<Comments></Comments>
				</Objective3>
			</SMARTObjectives>
			<YourSMARTObjective></YourSMARTObjective>
			<ImpactOfNoWorkObjective></ImpactOfNoWorkObjective>
			<YourRoleAndResponsibilitiesImpactOnTeamGoal></YourRoleAndResponsibilitiesImpactOnTeamGoal>
			<OneMoreBenefit></OneMoreBenefit>
		</Objective>
		<PDP>
			<Q1></Q1>
			<Q2></Q2>
		</PDP>
		<QualificationQuestions>
			<Unit1_1></Unit1_1>
			<Unit1_2></Unit1_2>
			<Unit1_3></Unit1_3>
			<Unit2_1></Unit2_1>
			<Unit2_2></Unit2_2>
			<Unit2_3></Unit2_3>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<Team>
			<Status></Status>
			<Comments></Comments>
		</Team>
		<EffectiveTeam>
			<Status></Status>
			<Comments></Comments>
		</EffectiveTeam>
		<TeamDynamics>
			<Status></Status>
			<Comments></Comments>
		</TeamDynamics>
		<PersuationAndInfluencingSkills>
			<Status></Status>
			<Comments></Comments>
		</PersuationAndInfluencingSkills>
		<ImplicationsOfNotWorkingTogether>
			<Status></Status>
			<Comments></Comments>
		</ImplicationsOfNotWorkingTogether>
		<PositiveInfluencing>
			<Status></Status>
			<Comments></Comments>
		</PositiveInfluencing>
		<RolesResp>
			<Status></Status>
			<Comments></Comments>
		</RolesResp>
		<Questions>
			<Status></Status>
			<Comments></Comments>
		</Questions>
		<Objective>
			<Status></Status>
			<Comments></Comments>
		</Objective>
		<PDP>
			<Status></Status>
			<Comments></Comments>
		</PDP>
		<QualificationQuestions>
			<Status></Status>
			<Comments></Comments>
		</QualificationQuestions>
	</Feedback>
</Workbook>
XML;

	    return XML::loadSimpleXML($xml);
    }

    public function getCompletedPercentage()
    {
        return parent::getCompletedPercentage();
    }

    public function getSignOffPercentage()
    {
        $total = 0;
        $feedback = $this->wb_content->Feedback;
        if(isset($feedback->WorkActivitiesImpact->Status) && $feedback->WorkActivitiesImpact->Status->__toString() == 'A')
            $total += 33.33;
        if(isset($feedback->WorkActivitiesImpactImprove->Status) && $feedback->WorkActivitiesImpactImprove->Status->__toString() == 'A')
            $total += 33.33;
        if(isset($feedback->QualificationQuestions->Status) && $feedback->QualificationQuestions->Status->__toString() == 'A')
            $total += 33.33;

        return round($total);
    }

    public function showAssessorFeedback(PDO $link, $section)
    {
        return parent::showAssessorFeedback($link, $section);
    }

    public function showSectionHistory(PDO $link, $section)
    {
        $html = '';
        $results = DAO::getResultset($link, "SELECT wb_content, created FROM workbooks_log WHERE wb_id = '{$this->id}' AND user_type = '" . User::TYPE_LEARNER . "' ORDER BY created DESC LIMIT 10000 OFFSET 1", DAO::FETCH_ASSOC);
        if(count($results) == 0)
        {
            $html .= '<i>No records found</i>';
        }
        else
        {
            if($section == 'Team' || $section == 'EffectiveTeam' || $section == 'TeamDynamics')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="1">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
                    $html .= '<tr><td>' . $log_xml->Answers->$section->__toString() . '</td></tr>';
                    $html .= '</table><hr>';
                }
            }
            if($section == 'PersuationAndInfluencingSkills')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="1">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
                    $html .= '<tr><th>Inside Work</th></tr>';
                    $html .= '<tr><td>' . $log_xml->Answers->PersuationAndInfluencingSkills->InsideWork->__toString() . '</td></tr>';
                    $html .= '<tr><th>Outside Work</th></tr>';
                    $html .= '<tr><td>' . $log_xml->Answers->PersuationAndInfluencingSkills->OutsideWork->__toString() . '</td></tr>';
                    $html .= '<tr><th>When have you attended team briefings or meetings in your store?</th></tr>';
                    $html .= '<tr><td>' . $log_xml->Answers->PersuationAndInfluencingSkills->YourContributionInTeamMeetings->__toString() . '</td></tr>';
                    $html .= '<tr><th>Give some examples of how you work together and support each other...</th></tr>';
                    $html .= '<tr><td>' . $log_xml->Answers->PersuationAndInfluencingSkills->ExamplesOfWorkingAndSupporting->__toString() . '</td></tr>';
                    $html .= '<tr><th>Speak to your manager about wider team ...</th></tr>';
                    $html .= '<tr><td>' . $log_xml->Answers->PersuationAndInfluencingSkills->HowYourTeamSupportOthers->__toString() . '</td></tr>';
                    $html .= '</table><hr>';
                }
            }
	        if($section == 'ImplicationsOfNotWorkingTogether')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
                    $html .= '<tr><th>Q1</th>';
                    $html .= '<td>' . $log_xml->Answers->ImplicationsOfNotWorkingTogether->ExampleGap1->__toString() . '</td></tr>';
                    $html .= '<tr><th>Q2</th>';
                    $html .= '<td>' . $log_xml->Answers->ImplicationsOfNotWorkingTogether->ExampleGap2->__toString() . '</td></tr>';
                    $html .= '<tr><th>Q3</th>';
                    $html .= '<td>' . $log_xml->Answers->ImplicationsOfNotWorkingTogether->ExampleGap3->__toString() . '</td></tr>';
                    $html .= '<tr><th>Details Notes ...</th>';
                    $html .= '<td>' . $log_xml->Answers->ImplicationsOfNotWorkingTogether->DetailNotes->__toString() . '</td></tr>';
                    $html .= '</table><hr>';
                }
            }
	        if($section == 'PositiveInfluencing')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
                    $html .= '<tr><th>What ...</th>';
                    $html .= '<td>' . $log_xml->Answers->PositiveInfluencing->What->__toString() . '</td></tr>';
                    $html .= '<tr><th>Why ...</th>';
                    $html .= '<td>' . $log_xml->Answers->PositiveInfluencing->Why->__toString() . '</td></tr>';
                    $html .= '</table><hr>';
                }
            }
	        if($section == 'Questions')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
	                for($i = 1; $i <= 8; $i++)
	                {
		                $q = 'Question'.$i;
		                $html .= '<tr><th>'.$q.'</th>';
		                $html .= '<td>' . $log_xml->Answers->Questions->$q->__toString() . '</td></tr>';
	                }
                    $html .= '</table><hr>';
                }
            }
	        if($section == 'PDP')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
	                $html .= '<tr><th>How do you think you personally ...</th>';
	                $html .= '<td>' . $log_xml->Answers->PDP->Q1->__toString() . '</td></tr>';
	                $html .= '<tr><th>Give some examples ...</th>';
	                $html .= '<td>' . $log_xml->Answers->PDP->Q2->__toString() . '</td></tr>';
                    $html .= '</table><hr>';
                }
            }
	        if($section == 'Objective')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="1">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
	                $html .= '<tr><th>List 3 daily objectives</th></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->Objective->DailyWorkObj->__toString() . '</td></tr>';
	                $html .= '<tr><th>Other work objectives ...</th></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->Objective->OtherWorkObj->__toString() . '</td></tr>';
	                $html .= '<tr><th>Objectives relating to business targets ...</th></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->Objective->BusinessWorkObj->__toString() . '</td></tr>';
                    $html .= '</table>';
	                $html .= '<table class="table table-bordered">';
	                $html .= '<tr><th>Objective</th><th>SMART/ Not&nbsp;SMART</th><th>Comments</th></tr>';
	                for($i = 1; $i <= 3; $i++)
	                {
		                $s = 'Objective'.$i;
		                $html .= '<tr><th>'.$s.'</th>';
		                $html .= $log_xml->Answers->Objective->SMARTObjectives->$s->Type->__toString() == 'S' ? '<td>SMART</td>' : '<td>Not SMART</td>';
		                $html .= '<td>' . $log_xml->Answers->Objective->SMARTObjectives->$s->Comments->__toString() . '</td>';
	                }
                    $html .= '</table>';
	                $html .= '<table>';
	                $html .= '<tr><th>SMART objectives of your own ...</th></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->Objective->YourSMARTObjective->__toString() . '</td></tr>';
	                $html .= '<tr><th>What would happen if you didn\'t have work objectives</th></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->Objective->ImpactOfNoWorkObjective->__toString() . '</td></tr>';
	                $html .= '<tr><th>Think about your own role and responsibilities ...</th></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->Objective->YourRoleAndResponsibilitiesImpactOnTeamGoal->__toString() . '</td></tr>';
	                $html .= '<tr><th>Can you think of one more benefit ...</th></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->Objective->OneMoreBenefit->__toString() . '</td></tr>';
                    $html .= '</table><hr>';
                }
            }
	        if($section == 'RolesResp')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
                    $html .= '<tr><th>Store Manager</th>';
                    $html .= '<td>' . $log_xml->Answers->RolesResp->Roles->SM->__toString() . '</td></tr>';
                    $html .= '<tr><th>Assistant Manager</th>';
                    $html .= '<td>' . $log_xml->Answers->RolesResp->Roles->AM->__toString() . '</td></tr>';
                    $html .= '<tr><th>Team Leader</th>';
                    $html .= '<td>' . $log_xml->Answers->RolesResp->Roles->TL->__toString() . '</td></tr>';
                    $html .= '<tr><th>Beauty Specialist</th>';
                    $html .= '<td>' . $log_xml->Answers->RolesResp->Roles->BS->__toString() . '</td></tr>';
                    $html .= '<tr><th>Sales Assistant</th>';
                    $html .= '<td>' . $log_xml->Answers->RolesResp->Roles->SA->__toString() . '</td></tr>';
                    $html .= '<tr><th>Apprentice</th>';
                    $html .= '<td>' . $log_xml->Answers->RolesResp->Roles->App->__toString() . '</td></tr>';
                    $html .= '<tr><th>Pharmacist</th>';
                    $html .= '<td>' . $log_xml->Answers->RolesResp->Roles->Pha->__toString() . '</td></tr>';
	                $html .= '</table>';
	                $html .= '<table class="table small">';
	                $html .= '<tr><th>How do you demonstrate an interest in other team members roles?</th></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->RolesResp->HowToShowInterest->__toString() . '</td></tr>';
	                $html .= '</table>';
	                $html .= '<table class="table small">';
	                $html .= '<tr><th>Equal opportunities policy</th>';
	                $html .= '<td>' . $log_xml->Answers->RolesResp->EqualOpportunitiesPolicy->__toString() . '</td></tr>';
	                $html .= '<tr><th>The Hub</th>';
	                $html .= '<td>' . $log_xml->Answers->RolesResp->TheHub->__toString() . '</td></tr>';
	                $html .= '<tr><th>Daily sales target</th>';
	                $html .= '<td>' . $log_xml->Answers->RolesResp->DailySalesTarget->__toString() . '</td></tr>';
	                $html .= '<tr><th>Store Manager</th>';
	                $html .= '<td>' . $log_xml->Answers->RolesResp->StoreManager->__toString() . '</td></tr>';
	                $html .= '</table>';
	                $html .= '<table class="table small">';
	                $html .= '<tr><th colspan="2">Your own roles</th></tr>';
	                $html .= '<tr><th>Type of information</th><th>Where it is located / who can ask</th></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->RolesResp->YourOwnRoles->Set1->Type->__toString() . '</td><td>' . $log_xml->Answers->RolesResp->YourOwnRoles->Set1->WhereLocated->__toString() . '</td></tr>';
	                $html .= '<tr><td>' . $log_xml->Answers->RolesResp->YourOwnRoles->Set2->Type->__toString() . '</td><td>' . $log_xml->Answers->RolesResp->YourOwnRoles->Set2->WhereLocated->__toString() . '</td></tr>';
	                $html .= '</table><hr>';
                }
            }
            if($section == 'QualificationQuestions')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
                    $html .= '<tr><th>Unit 6 1.1</th><td>'.$log_xml->Answers->QualificationQuestions->Unit1_1->__toString().'</td></tr>';
                    $html .= '<tr><th>Unit 6 1.2</th><td>'.$log_xml->Answers->QualificationQuestions->Unit1_2->__toString().'</td></tr>';
                    $html .= '<tr><th>Unit 6 1.3</th><td>'.$log_xml->Answers->QualificationQuestions->Unit1_3->__toString().'</td></tr>';
                    $html .= '<tr><th>Unit 6 2.1</th><td>'.$log_xml->Answers->QualificationQuestions->Unit2_1->__toString().'</td></tr>';
                    $html .= '<tr><th>Unit 6 2.2</th><td>'.$log_xml->Answers->QualificationQuestions->Unit2_2->__toString().'</td></tr>';
                    $html .= '<tr><th>Unit 6 2.3</th><td>'.$log_xml->Answers->QualificationQuestions->Unit2_3->__toString().'</td></tr>';
                    $html .= '</table><hr>';
                }
            }
        }
        return $html;
    }

    public function getUnitReference()
    {
        return 'Unit 05';
    }

    public function getStepsWithQuestions()
    {
        return '1,2,3,4,5,6,7,8,9,10,11';
    }

    public function updateLearnerProgress(PDO $link)
    {
        parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
    }

    public function getPageTopLine()
    {
        if($this->savers_or_sp == 'savers')
            return '<p class="topLine"><span style="color: blue;">Personal Team Performance</span> <span style="color: red;">Personal Team Performance</span> </p>';
        else
            return '<p class="topLine"><span style="color: pink;">Personal Team Performance</span> <span style="color: gray;">Personal Team Performance</span> </p>';
    }

    public function getPageBottomLine()
    {
        if($this->savers_or_sp == 'savers')
            return '<p class="bottomLine"><span style="color: blue;">Personal Team Performance</span> <span style="color: red;">Personal Team Performance</span> </p>';
        else
            return '<p class="bottomLine"><span style="color: pink;">Personal Team Performance</span> <span style="color: gray;">Personal Team Performance</span> </p>';
    }

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>