<?php
class edit_training implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        if($id == '')
            throw new Exception("Missing querystring argument: id");

        $vo = TrainingRecord::loadFromDatabase($link, $id);
        if(is_null($vo))
            throw new Exception("Invalid id");

        $_SESSION['bc']->add($link, "do.php?_action=edit_training&id={$vo->id}", "Edit Training Details");

        $ob_learner = $vo->getObLearnerRecord($link);
	{
          if($_SESSION['user']->learners_caseload == 0)
          {
              // do nothing
          }
          elseif($_SESSION['user']->learners_caseload != $ob_learner->caseload_org_id)
          {
              throw new UnauthorizedException("You are not authorised to view this record.");
          }
      }

        $provider_org_type = Organisation::TYPE_TRAINING_PROVIDER;
        $employer_id = $vo->employer_id;
        $sqlProviders = <<<SQL
SELECT
  locations.id,
  CONCAT(UPPER(organisations.`short_name`), ' | ', COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_4`,''),', ',COALESCE(`postcode`,''), ')') AS detail,
  organisations.`legal_name`
FROM
  locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE
  organisations.`organisation_type` = '{$provider_org_type}'
ORDER BY legal_name, full_name ;
SQL;
        $ddlTrainingProvidersLocations = DAO::getResultset($link, $sqlProviders);

        $subcontractor_org_type = Organisation::TYPE_SUB_CONTRACTOR;
        $sqlSubcontractors = <<<SQL
SELECT
  locations.id,
  CONCAT(organisations.`short_name`, ' ', COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`postcode`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  organisations.`legal_name`
FROM
  locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE
  organisations.`organisation_type` = '{$subcontractor_org_type}'
ORDER BY legal_name, full_name ;
SQL;
        $ddlSubcontractorsLocations = DAO::getResultset($link, $sqlSubcontractors);
        array_unshift($ddlSubcontractorsLocations, array('','NA',''));
        $ddlEmployers = DAO::getResultset($link, "SELECT id, legal_name, LEFT(legal_name, 1) FROM organisations WHERE (organisation_type = '" . Organisation::TYPE_EMPLOYER . "') ORDER BY legal_name");
        $ddlEmployersLocations = [
            ['', 'Select an employer to populate locations']
        ];

        if($employer_id != '')
        {
            $sql = <<<SQL
SELECT
  locations.id,
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  null
FROM
  locations
WHERE
	locations.organisations_id = '$employer_id'
ORDER BY full_name ;
SQL;
            $ddlEmployersLocations = DAO::getResultset($link, $sql);
        }
	$ddlEmployersLineManagers = [
        ['', 'Select an employer to populate line managers']
      ];

      if ($employer_id != '') {
        $sql = <<<SQL
SELECT
  contact_id,
  contact_name,
  null
FROM
  organisation_contacts
WHERE
  organisation_contacts.org_id = '$employer_id' AND job_role IN ('2', '28')  
ORDER BY contact_name ;
SQL;
        $ddlEmployersLineManagers = DAO::getResultset($link, $sql);
      }

        $ddlFrameworks = DAO::getResultset($link, "SELECT id, title, framework_code FROM frameworks WHERE active = 1 AND framework_type = 25 ORDER BY framework_code, title;");
        if(in_array(DB_NAME, ["am_demo", "am_barnsley"]))
            $ddlJobTitles = DAO::getResultset($link, "SELECT id, description, null FROM lookup_job_titles ORDER BY description;");

        $ddlFrameworks = DAO::getResultset($link, "SELECT id, title, null FROM frameworks WHERE active = '1' ORDER BY title");
        $ddlEpaOrgs = DAO::getResultset($link, "SELECT EPA_ORG_ID, EP_Assessment_Organisations, NULL FROM central.`epa_organisations` ORDER BY EP_Assessment_Organisations;");
        $ddlTrainers = DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname), (SELECT DISTINCT description FROM `lookup_user_types` WHERE id = users.type) AS _type FROM users WHERE users.employer_id = '{$vo->provider_id}' AND users.`active` = 1 AND users.type NOT IN (5) ORDER BY _type DESC, firstnames");

        $framework = Framework::loadFromDatabase($link, $vo->framework_id);

	$LLDD = array(array('Y', 'Yes'), array('N', 'No'), array('P', 'Prefer not to say'));
      $LLDDCat = array(
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
      $selected_llddcat = $vo->llddcat != '' ? explode(',', $vo->llddcat) : [];

      $titlesDdl = [
        ['Mr', 'Mr'],
        ['Mrs', 'Mrs'],
        ['Miss', 'Miss'],
        ['Ms', 'Ms']
      ];

      $LOE_dropdown = array(array('1', 'Up to 3 months'), array('2', '4-6 months'), array('3', '7-12 months'), array('4', 'more than 12 months'));
      array_unshift($LOE_dropdown, array('', 'Length of employment', ''));
      $EII_dropdown = array(array('5', '0-10 hours per week'), array('6', '11-20 hours per week'), array('7', '21-30 hours per week'), array('8', 'Learner is employed for 31+ hours per week'));
      array_unshift($EII_dropdown, array('', 'Hours/week', ''));
      $LOU_dropdown = array(array('1', 'unemployed for less than 6 months'), array('2', 'unemployed for 6-11 months'), array('3', 'unemployed for 12-23 months'), array('4', 'unemployed for 24-35 months'), array('5', 'unemployed for over 36 months'));
      array_unshift($LOU_dropdown, array('', 'Length of unemployment', ''));
      $BSI_dropdown = array(array('1', 'JSA'), array('2', 'ESA WRAG'), array('3', 'Another state benefit'), array('4', 'Universal Credit'));
      array_unshift($BSI_dropdown, array('', 'Select benefit type if applicable', ''));

      $saved_eligibility_list = $vo->EligibilityList != '' ? explode(',', $vo->EligibilityList) : [];

      $care_leaver_details = $vo->getCareLeaverDetails($link);
      $criminal_conviction_details = $vo->getCriminalConvictionDetails($link);
      $countries = DAO::getResultset($link, "SELECT id, country_name FROM lookup_countries WHERE id = 76 UNION ALL SELECT id, country_name FROM lookup_countries WHERE id != 76;");
      $nationalities = DAO::getResultset($link, "SELECT code, description FROM lookup_country_list ORDER BY description;");

	$employer = Organisation::loadFromDatabase($link, $vo->employer_id);

        if(in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_99]))
      {
        include_once('tpl_edit_training_non_app.php');
      }
      else
      {
        include_once('tpl_edit_training.php');
      }
    }
}
