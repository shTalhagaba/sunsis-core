<?php


class EmployerSchedule1 extends Entity
{
    /**
     * @static
     * @param PDO $link
     * @param $id
     * @return EmployerSchedule1
     * @throws DatabaseException
     */
    public static function loadFromDatabase(PDO $link, $id)
    {
        $schedule = null;
        if($id != '' && is_numeric($id))
        {
            $query = "SELECT * FROM employer_agreement_schedules WHERE id = " . addslashes($id) . ";";
            $st = $link->query($query);

            if($st)
            {
                $row = $st->fetch();
                if($row)
                {
                    $schedule = new EmployerSchedule1();
                    $schedule->populate($row);
                }
            }
            else
            {
                throw new DatabaseException($link, $query);
            }
        }

        return $schedule;
    }

    public function save(PDO $link)
    {
        $this->created_at = $this->id == '' ? date('Y-m-d H:i:s') : $this->created_at;
        $this->created_by = $this->id == '' ? $_SESSION['user']->id : $this->created_by;
        $this->updated_at = date('Y-m-d H:i:s');
        
        return DAO::saveObjectToTable($link, 'employer_agreement_schedules', $this);
    }

    public static function generateCompletionPage(PDO $link, $tr_id = '')
    {
        if($tr_id == '')
        {
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        }
        else
        {
            $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations INNER JOIN ob_tr ON organisations.id = ob_tr.provider_id WHERE ob_tr.id = '{$tr_id}'");
            if($logo == '')
                $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        }

        $page_bg_img = '/images/Employer_Schedule_Thanks.png';
        if(DB_NAME == "am_onboarding")
            $page_bg_img = '/images/logos/SUNlogo.jpg';

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Employer Schedule 1</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

<body>

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: #BD1730;background-image: linear-gradient(to left, #BD1730, #9D8D8F)">
        <div class="container-float">
            <div class="navbar-header page-scroll">
                <a class="navbar-brand" href="#">
                    <img height="35px" class="headerlogo" src="$logo" />
                </a>
            </div>
        </div>
        <div class="text-center" style="margin-top: 5px;"><h3 style="color: white" class="text-bold">Employer Schedule 1</h3></div>
    </nav>

    <content id="completionPage">
        <div class="jumbotron" 
            style="background-position: center; 
                background-size: 75%;
                background-image: url('$page_bg_img');
                background-repeat: no-repeat;
                background-attachment: fixed; height: 80%;">
        </div>
    </content>
    
    <footer class="">
        <div class="pull-left">
            <img width="230px" src="$logo" />
        </div>
        <div class="pull-right">
            <img src="images/logos/SUNlogo.png" />
        </div>
    </footer>
    
</body>
</html>
HTML;

    }

    public function generatePdf(PDO $link)
    {
        if($this->tp_sign == '' || $this->emp_sign == '')
        {
            return;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $this->tr_id); /* @var $tr TrainingRecord */
        $ob_learner = $tr->getObLearnerRecord($link); /* @var $ob_learner OnboardingLearner */
        $employer = Employer::loadFromDatabase($link, $tr->employer_id);
        $mainLocation = $employer->getMainLocation($link);
        $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);
        $schedule = $this;
        $detail = json_decode($schedule->detail);

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
        $mpdf->setAutoBottomMargin = 'stretch';

        $sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "30%" align="left" style="font-size: 10px">{$date}</td>
					<td width = "35%" align="left" style="font-size: 10px">App2b-Employer Schedule 1-v2 2122 Sep 09 2021</td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $dob = Date::toShort($ob_learner->dob);
        $age_at_start_of_app = Date::dateDiff(date("Y-m-d"), $ob_learner->dob);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Employer Apprenticeship Agreement Schedule 1</strong></h2>
    <img width="200px;" class="img-responsive" src="$logo" />
</div>
HTML;
        echo <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">Section 1 - Employer and Apprentice Details</th></tr>
        <tr>
            <th>1.1</th>
            <td>
                <strong>Name of Employer: </strong>$employer->legal_name <br>
                <strong>Contact Name: </strong>$mainLocation->contact_name <br>
                <strong>Contact Tel No.: </strong>$mainLocation->contact_telephone <br>
                <strong>Contact Email: </strong>$mainLocation->contact_email
            </td>
        </tr>
        <tr>
            <th>1.2</th>
            <td>
                <strong>Name of Apprentice: </strong>$ob_learner->firstnames $ob_learner->surname <br>
                <strong>Date of Birth: </strong>$dob <br>
                <strong>Age at start of apprenticeship: </strong>$age_at_start_of_app <br>
                <strong>ULN: </strong>$ob_learner->uln <br>
                <strong>Cohort: </strong>$framework->title
            </td>
        </tr>
    </table>
</div>
HTML;

        $apprentice_job_title = (isset($detail->apprentice_job_title) && $detail->apprentice_job_title != '') ? $detail->apprentice_job_title : $tr->job_title;
        $level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
        $title_of_app = $framework->getStandardCodeDesc($link);
        $proposed_sd = Date::toShort($tr->practical_period_start_date);
        $proposed_ed = Date::toShort($tr->practical_period_end_date);
        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 2 - Apprenticeship Programme</th></tr>
        <tr>
            <th>2.1</th>
            <th>Apprentice Job Title</th>
            <td>{$apprentice_job_title}</td>
        </tr>
        <tr>
            <th>2.2</th>
            <th>Standard</th>
            <td>{$framework->title}</td>
        </tr>
        <tr>
            <th>2.3</th>
            <th>Level of Apprenticeship</th>
            <td>{$level}</td>
        </tr>
        <tr>
            <th>2.4</th>
            <th>Title of Apprenticeship</th>
            <td>{$title_of_app}</td>
        </tr>
        <tr>
            <th>2.5</th>
            <th>Location of Training</th>
            <td>
                $employer_location->address_line_1,
                $employer_location->address_line_2 
                $employer_location->address_line_3, 
                $employer_location->address_line_4,
                $employer_location->postcode
            </td>
        </tr>
        <tr>
            <th>2.6</th>
            <th>Proposed Start Date</th>
            <td>{$proposed_sd}</td>
        </tr>
        <tr>
            <th>2.7</th>
            <th>Proposed End Date<br><small>(for practical training)</th>
            <td>{$proposed_ed}</td>
        </tr>
    </table>
</div>
HTML;

        $trainers_ids = explode(",", $tr->trainers);
        $_trainers = '';
        foreach($trainers_ids AS $_t_id)
            $_trainers .= DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_t_id}'") . '<br>';
        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 3 - Training Provider Actions</th></tr>
        <tr>
                <th>3.1</th>
                <th>Training to be delivered by the<br>Training Provider</th>
                <td>{$detail->training_by_provider}</td>
            </tr>
            <tr>
                <th>3.2</th>
                <th>Trainer</th>
                <td>{$_trainers}</td>
            </tr>
            <tr>
                <th>3.3</th>
                <th>Training Provider Equipment</th>
                <td>{$detail->provider_equipment}</td>
            </tr>
    </table>
</div>
HTML;

        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 4 - Employer Actions</th></tr>
        <tr>
            <th>4.1</th>
            <th>Training to be delivered by the<br>Employer</th>
            <td>{$detail->training_by_employer}</td>
        </tr>
        <tr>
            <th>4.2</th>
            <th>Employer Equipment</th>
            <td>{$detail->employer_equipment}</td>
        </tr>
    </table>
</div>
HTML;

        $epa_org_name = $tr->getEpaOrgName($link);
        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 5 - End-Point Assessment (EPA) Organisation - Standards Only</th></tr>
        <tr>
            <th>5.1</th>
            <th>Name of EPA Organisation</th>
            <td>{$epa_org_name}</td>
        </tr>
    </table>
</div>
HTML;

        $subcontractor_name = $tr->getSubcontractorLegalName($link);
        $subcon_ukprn = DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '{$tr->subcontractor_id}'");
        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 6 - Subcontracting</th></tr>
        <tr>
            <th>6.1</th>
            <th>Name of Subcontractor</th>
            <td>{$subcontractor_name}</td>
        </tr>
        <tr>
            <th>6.2</th>
            <th>Training to be delivered by<br>Subcontractor</th>
            <td>{$detail->training_by_subcontractor}</td>
        </tr>
        <tr>
            <th>6.3</th>
            <th>UKPRN</th>
            <td>{$subcon_ukprn}</td>
        </tr>
    </table>
</div>
HTML;

        $_e = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%english%';");
        $_m = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%math%';");
        $_ict = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%ict%';");
        $_e = $_e > 0 ? 'Yes' : 'No';
        $_m = $_m > 0 ? 'Yes' : 'No';
        $_ict = $_ict > 0 ? 'Yes' : 'No';
        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 7 - Functional Skills required for this Apprenticeship (not the individual)</th></tr>
        <tr>
            <th>7.1</th>
            <th>Maths</th>
            <td>$_e</td>
        </tr>
        <tr>
            <th>7.2</th>
            <th>English</th>
            <td>$_m</td>
        </tr>
        <tr>
            <th>7.3</th>
            <th>ICT</th>
            <td>$_ict</td>
        </tr>
    </table>
</div>
HTML;

        $max_funding_band = $framework->getFundingBandMax($link);
        echo <<<HTML
<p></p>
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th colspan="2" style="color: #000; background-color: #d2d6de !important">
                Section 8 - Proposed Cost of Training Per Apprentice<br>
                <i>the maximum funding band for this standard is &pound; $max_funding_band</i>
            </th>
        </tr>
HTML;
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">TNP 1</th></tr>';
        $tnp1_prices = is_null($tr->tnp1) ? [] : json_decode($tr->tnp1);
	    $tnp1_costs = array_map(function ($ar) {return $ar->cost;}, $tnp1_prices);
        $tnp1_total = array_sum(array_map('floatval', $tnp1_costs));
        $tnp_total = ceil($tnp1_total + $tr->epa_price);
        foreach($tnp1_prices AS $tnp1)
        {
            echo '<tr>';
            echo '<th>'.$tnp1->description.'</th>';
            echo '<td>&pound;'.$tnp1->cost.'</td>';
            echo '<tr>';
        }
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">TNP 2</th></tr>';
        echo '<tr><th>EPA Cost</th><td>' . $tr->epa_price . '</td></tr>';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">TNP</th></tr>';
        echo '<tr><th>TNP 1 + TNP 2</th><td>' . $tnp_total . '</td></tr>';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">Additional Prices</th></tr>';
        $additional_prices = (is_null($tr->additional_prices) || $tr->additional_prices == 0) ? [] : json_decode($tr->additional_prices);
        foreach($additional_prices AS $additional_price)
        {
            echo '<tr>';
            echo '<th>'.$additional_price->description.'</th>';
            echo '<td>&pound;'.$additional_price->cost.'</td>';
            echo '<tr>';
        }

        echo <<<HTML
    </table>
</div>
HTML;

        $cost1 = '';
        $cost2 = '';
        $cost3 = '';
        $cost4 = '';

        $learner_age_sql = <<<SQL
SELECT 
    ((DATE_FORMAT('{$tr->practical_period_start_date}','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT('{$tr->practical_period_start_date}','00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
        $learner_age = DAO::getSingleValue($link, $learner_age_sql);

        if($employer->funding_type == 'L')
        {
            $cost1 = '&pound;' . $tnp_total;
        }
        else
        {
            if(in_array($employer->code, [3, 4]) || $learner_age >= 19) // then show 2nd and 3rd box
            {
                $cost2 = '&pound;' . ceil(($tnp_total*5)/100);
                $cost3 = '&pound;' . ceil(($tnp_total*95)/100);
            }
            elseif(in_array($employer->code, [1, 2]) && $learner_age < 19) // then show 4th box
            {
                $cost4 = '&pound;' . $tnp_total;
            }
        }
        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="4" style="color: #000; background-color: #d2d6de !important">Section 9 - Total Cost of Training Paid to the Training Provider</th></tr>
        <tr class="text-center">
            <th>Levy Paying Employers</th>
            <th>Co-Funded/Non Levy Employers</th>
            <th>Government Contribution</th>
            <th>Government Contribution - SME</th>
        </tr>
        <tr class="text-center">
            <td>Maximum Employer Contribution via Levy - 100%</td>
            <td>0% or 5% Employer Contribution</td>
            <td>95%</td>
            <td>100%</td>
        </tr>
        <tr class="text-center">
            <td>{$cost1}</td>
            <td>{$cost2}</td>
            <td>{$cost3}</td>
            <td>{$cost4}</td>
        </tr>
    </table>
</div>
HTML;


        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 10 - Additional Details (details supporting the negotiated costs / reduced rates)</th></tr>
        <tr>
            <td>The negotiated price will be confirmed with the Employer after the Skills Analysis has taken place, together with the first visit from the trainer.</td>
        </tr>
        <tr>
            <td>{$detail->section11_additional_details}</textarea></td>
        </tr>
    </table>
</div>
HTML;

        $section12Option1 = (isset($detail->section12) && is_array($detail->section12) && in_array(1, $detail->section12)) ? '<img width="15px;" height="15px;" src="./images/check.jpg" /> ' : '';
        $section12Option2 = (isset($detail->section12) && is_array($detail->section12) && in_array(2, $detail->section12)) ? '<img width="15px;" height="15px;" src="./images/check.jpg" /> ' : '';
        $section12Option3 = (isset($detail->section12) && is_array($detail->section12) && in_array(3, $detail->section12)) ? '<img width="15px;" height="15px;" src="./images/check.jpg" /> ' : '';
        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 11 - Additional Payments</th></tr>
        <tr>
            <td>
                <p style="font-weight: 700;">16-18 Employer Incentive / 19-24 Education Health Care Plan</p>
                <p>
                    The training provider and employer will receive a payment towards the additional cost associated with training
                    if, at the start of the apprenticeship, the apprentice is:
                </p>
                <ul style="margin-left: 5px;">
                    <li style="font-weight: 700;">
                        Aged between 16 and 18 years old (or 15 years of age if the apprentice's 16th birthday
                        is between the last Friday of June and 31 August).
                    </li>
                    <li style="font-weight: 700;">
                        Aged between 19 and 24 years old and has either an Education, Health and Care (EHC) plan
                        provided by their local authority or has been in the care of thier local authority.
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <label>
                        {$section12Option1}
                        I (the Employer) confirm I am eligible for the &pound;1,000 16-18 Employer Incentive
                        for the Apprentice detailed within this schedule.
                    </label>
                </p>
                <p>
                    <label>
                        {$section12Option2}
                        I (the Employer) confirm I am eligible for the &pound;1,000 19-24 Education Health Care plan or care leaver
                        employer incentive for the Apprentice detailed within this schedule.
                        (Relevant evidence will be required at the beginning of the apprenticeship)
                    </label>
                </p>
                <p>
                    <label>
                        {$section12Option3}
                        Not Applicable
                    </label>
                </p>
            </td>
        </tr>
    </table>
</div>
HTML;

        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 12 - Payment Schedule</th></tr>
        <tr>
            <td>
                <p style="font-weight: 700;"><strong>Levy Paying Employers</strong></p>
                <ul style="margin-left: 5px;">
                    <li>
                        80% of the total price will be taken from your Apprenticeship Service
                        account on a monthly basis, over the duration of the apprentice's programme.
                    </li>
                    <li style="font-weight: 700;">
                        20% of the total cost will be retained for achievement and/or End Point
                        Assessment costs and will be taken from your Apprenticeship Service Account.
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-weight: 700;"><strong>Co-Investor Employers</strong></p>
                <ul style="margin-left: 5px;">
                    <li>
                        Where your 5% Employer Contribution is &pound;250 or less, you will be
                        invoiced in full at the start of the apprenticeship programme.
                    </li>
                    <li style="font-weight: 700;">
                        Where your 5% Employer Contribution is over &pound;250, you will be invoiced in full,
                        and payments will be obtained on 4 equal instalments at months 1, 4, 7 and 9.
                    </li>
                    <li style="font-weight: 700;">
                        Invoices are to be paid within 30 days from the date of invoice.
                    </li>
                </ul>
            </td>
        </tr>
    </table>
</div>
HTML;

        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 13 - Mandatory Policies</th></tr>
        <tr>
            <td>
                <p>Training Provider policies available to learner:</p>
                <ul style="margin-left: 5px;">
                    <li>Safeguarding</li>
                    <li>Health & Safety</li>
                    <li>Equality & Diversity</li>
                    <li>GDPR</li>
                    <li>Complaints</li>
                </ul>
            </td>
        </tr>
    </table>
</div>
HTML;

        $section15radio_option1 = (isset($detail->section15radio) && $detail->section15radio == '1') ? '<img width="15px;" height="15px;" src="./images/check.jpg" />' : '';
        $section15radio_option2 = (isset($detail->section15radio) && $detail->section15radio == '2') ? '<img width="15px;" height="15px;" src="./images/check.jpg" />' : '';

        $_v1 = '';
        if(!$tr->postJuly25Start()) 
        {
            $_v1 = "20% off-the-job training is the equivalent of 1 day per week based on a 5 day working week.";
        }

        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 14 - Employer Declarations</th></tr>
        <tr>
            <td>
                <span style="font-weight: 700;" style="margin-left: 5px;"> {$section15radio_option1} Option 1 - </span>
                I confirm that apprentice(s) named in this Schedule 1 has/have been issued with a contract of
                employment and is/will be employed for at least 30 hours per week. The minimum
                duration of each apprenticeship is based on the apprentice working at least 30 hours a week.
                <p style="font-weight: 700;">OR</p>
                <span style="font-weight: 700; margin-left: 5px;"> {$section15radio_option2} Option 2 - </span>
                I confirm that apprentice(s) named in this Schedule 1 has/have been issued with a contract of
                employment and is/will be employed for at least 16 hours per week. I am aware that
                the duration of the apprenticeship will be extended accordingly to take account of this.
            </td>
        </tr>
        <tr>
            <td>
                <img width="15px;" height="15px;" src="./images/check.jpg" /> Off-the-job training has been discussed and I am aware of the requirements for this.
                {$_v1}
            </td>
        </tr>
        <tr>
            <td>
                <img width="15px;" height="15px;" src="./images/check.jpg" /> The cost of this Apprenticeship has been discussed with us in detail, 
                    we fully understand the negotiated price for training and associated costs (TNP1) and we have negotiated the EPA price (TNP2). 
                    I understand that this is an indicative price at this point and is subject to change after the Skills Analysis has taken place.
            </td>
        </tr>
        <tr>
            <td>
                <img width="15px;" height="15px;" src="./images/check.jpg" /> I confirm that all apprentices listed in this schedule will spend at least
                50% of their working hours in England over the duration of the apprenticeship.
            </td>
        </tr>
        <tr>
            <td>
                <img width="15px;" height="15px;" src="./images/check.jpg" /> I confirm as part of our recruitment process we have check the named apprentice(s) right
                     to work in the UK and have checked and hold copies of the relevant documentation which will be made
                      available to the main provider when requested.
            </td>
        </tr>
    </table>
</div>
HTML;

        $schedule_directory = $tr->getDirectoryPath() . 'schedule1/';
        $emp_signature_file = $schedule_directory . 'emp_sign_image.png';
        $tp_signature_file = $schedule_directory . 'tp_sign_image.png';
        $emp_sign_date = isset($schedule->emp_sign_date) ? Date::toShort($schedule->emp_sign_date) : '';
        $tp_sign_date = isset($schedule->tp_sign_date) ? Date::toShort($schedule->tp_sign_date) : '';
        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="4" class="bg-blue">Signatures</th></tr>
        <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
        <tr>
            <td>Employer</td>
            <td>{$schedule->emp_sign_name}</td>
            <td><img id="img_tp_sign" src="$emp_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$emp_sign_date}</td>
        </tr>
        <tr>
            <td>Training Provider</td>
            <td>{$detail->tp_sign_name}</td>
            <td><img id="img_tp_sign" src="{$tp_signature_file}" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$tp_sign_date}</td>
        </tr>
    </table>
</div>
HTML;

        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        $mpdf->WriteHTML($html);

//        $mpdf->Output('InitialContract.pdf', 'D');

        $schedule_file = $schedule_directory.EmployerSchedule1::SCH_PDF_NAME;
        if(is_file($schedule_file))
            $schedule_file =  $schedule_directory.'InitialContract_'.uniqid().'.pdf';

        $mpdf->Output($schedule_file, 'F');
    }

    public $id = NULL;
    public $tr_id = NULL;
    public $employer_id = NULL;
    public $detail = NULL;
    public $emp_sign_name = NULL;
    public $emp_sign = NULL;
    public $emp_sign_date = NULL;
    public $tp_sign_name = NULL;
    public $tp_sign = NULL;
    public $tp_sign_date = NULL;
    public $sent_to_employer = NULL;
    public $created_at = NULL;
    public $created_by = NULL;
    public $updated_at = NULL;

    const SCH_PDF_NAME = 'InitialContract.pdf';
}