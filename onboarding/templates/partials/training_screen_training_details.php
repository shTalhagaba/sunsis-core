<?php 
$training_screen_training_details_selected_llddcat = $tr->llddcat 
    ? explode(',', $tr->llddcat) 
    : [];

$training_screen_training_details_LLDD = [
    'Y' => 'Yes',
    'N' => 'No',
    'P' => 'Prefer not to say',
];
$training_screen_training_details_LLDDCat = [
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
];
$LOE_dropdown = array('1' => 'Up to 3 months', '2' => '4-6 months', '3' => '7-12 months', '4' => 'more than 12 months');
$EII_dropdown = array('5' => '0-10 hours per week', '6' => '11-20 hours per week', '7' => '21-30 hours per week', '8' => '31+ hours or more per week');
$LOU_dropdown = array('1' => 'unemployed for less than 6 months', '2' => 'unemployed for 6-11 months', '3' => 'unemployed for 12-23 months', '4' => 'unemployed for 24-35 months', '5' => 'unemployed for over 36 months');
$BSI_dropdown = array('1' => 'JSA', '2' => 'ESA WRAG', '3' => 'Another state benefit', '4' => 'Universal Credit');

$t_wks_on_prog = SkillsAnalysis::calculateTotalWeeksOnProgramme($link, $tr->practical_period_start_date, $tr->practical_period_end_date);
$anl_lve_f_t_wks_on_prog = SkillsAnalysis::calculateAnnualLeaveForTotalWeeksOnProgramme($t_wks_on_prog);
$a_wks_on_prog = $t_wks_on_prog-$anl_lve_f_t_wks_on_prog;

$app_label = $framework->fund_model == Framework::FUNDING_STREAM_APP ? 'apprenticeship' : '';
?>
<div class="table-responsive">
    <table class="table table-bordered">
        <tr class="bg-gray">
            <th>Training Provider</th>
            <th>Employer</th>
			<?php if($framework->fund_model != Framework::FUNDING_STREAM_99){?>
            <th>EPA Organisation</th>
			<?php } ?>
            <th>Subcontractor</th>
        </tr>
        <tr>
            <td>
                <?php
                echo '<span class="text-bold">' . $provider->legal_name . '</span><br>';
                echo $provider_location->address_line_1 != '' ? $provider_location->address_line_1 . '<br>' : '';
                echo $provider_location->address_line_2 != '' ? $provider_location->address_line_2 . '<br>' : '';
                echo $provider_location->address_line_3 != '' ? $provider_location->address_line_3 . '<br>' : '';
                echo $provider_location->address_line_4 != '' ? $provider_location->address_line_4 . '<br>' : '';
                echo $provider_location->postcode != '' ? $provider_location->postcode . '<br>' : '';
                echo $provider_location->telephone != '' ? $provider_location->telephone . '<br>' : '';
                ?>
            </td>
            <td>
                <?php
                echo '<span class="text-bold">' . $employer->legal_name . '</span><br>';
                echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : '';
                echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : '';
                echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : '';
                echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : '';
                echo $location->postcode != '' ? $location->postcode . '<br>' : '';
                echo $location->telephone != '' ? $location->telephone . '<br>' : '';
                ?>
            </td>
			<?php if($framework->fund_model != Framework::FUNDING_STREAM_99){?>
            <td>
                <?php
                echo DAO::getSingleValue($link, "SELECT EP_Assessment_Organisations FROM central.`epa_organisations` WHERE EPA_ORG_ID = '{$tr->epa_organisation}'");
                ?>
            </td>
			<?php } ?>
            <td>
                <?php
                if (!is_null($subcontractor)) {
                    echo '<span class="text-bold">' . $subcontractor->legal_name . '</span><br>';
                    echo $subcontractor_location->address_line_1 != '' ? $subcontractor_location->address_line_1 . '<br>' : '';
                    echo $subcontractor_location->address_line_2 != '' ? $subcontractor_location->address_line_2 . '<br>' : '';
                    echo $subcontractor_location->address_line_3 != '' ? $subcontractor_location->address_line_3 . '<br>' : '';
                    echo $subcontractor_location->address_line_4 != '' ? $subcontractor_location->address_line_4 . '<br>' : '';
                    echo $subcontractor_location->postcode != '' ? $subcontractor_location->postcode . '<br>' : '';
                    echo $subcontractor_location->telephone != '' ? $subcontractor_location->telephone . '<br>' : '';
                }
                ?>
            </td>
        </tr>
    </table>
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <td>
                <span class="text-bold">Standard/ Programme: </span><span class="text-info text-bold"><?php echo $framework->title; ?></span><br>
		<?php if(!$tr->isNonApp($link)) { ?>
                <span class="text-bold">Apprenticeship Title: </span><span class="text-info text-bold"><?php echo $framework->getStandardCodeDesc($link); ?></span><br>
                <span class="text-bold">Level: </span><span class="text-info text-bold"><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}'"); ?></span><br>
                <span class="text-bold">Funding Band Max.: </span><span class="text-info text-bold"><?php echo '&pound;' . $framework->getFundingBandMax($link); ?></span><br>
                <span class="text-bold">Recommended Duration: </span><span class="text-info text-bold"><?php echo $framework->getRecommendedDuration($link); ?> months</span><br>
		<?php } ?>
            </td>
            <td>
                <span class="text-bold">Practical Period Start Date: </span><span class="text-info text-bold"><?php echo Date::toShort($tr->practical_period_start_date); ?></span><br>
                <span class="text-bold">Practical Period End Date: </span><span class="text-info text-bold"><?php echo Date::toShort($tr->practical_period_end_date); ?></span><br>
		   <?php if(!$tr->isNonApp($link)) { ?>
		   <span class="text-bold">Practical Period Duration: </span><span class="text-info text-bold"><?php echo $tr->duration_practical_period; ?> months</span><br>
		   <span class="text-bold">Apprenticeship Start Date: </span><span class="text-info text-bold"><?php echo Date::toShort($tr->apprenticeship_start_date); ?></span><br>
                <span class="text-bold">Apprenticeship End Date (including EPA): </span><span class="text-info text-bold"><?php echo Date::toShort($tr->apprenticeship_end_date_inc_epa); ?></span><br>
                <span class="text-bold">Apprenticeship Duration (including EPA): </span><span class="text-info text-bold"><?php echo $tr->apprenticeship_duration_inc_epa; ?> months</span><br>
                <span class="text-bold">Planned EPA Date: </span><span class="text-info text-bold"><?php echo Date::toShort($tr->planned_epa_date); ?></span><br>
                <?php } ?>
            </td>
            <td style="display: <?php echo $tr->isNonApp($link) ? 'none' : 'block'; ?>">
                <span class="text-bold">Contracted Hours per Week: </span><span class="text-info text-bold"><?php echo $tr->contracted_hours_per_week; ?></span><br>
                <span class="text-bold">Weeks to be worked per Year: </span><span class="text-info text-bold"><?php echo $tr->weeks_to_be_worked_per_year; ?></span><br>
                <span class="text-bold">Total Contracted Hours per Year: </span><span class="text-info text-bold"><?php echo $tr->total_contracted_hours_per_year; ?></span><br>
                <span class="text-bold">Total Contracted Hours full <?php echo ucfirst($app_label); ?>: </span><span class="text-info text-bold"><?php echo $tr->contracted_hours_per_week >= 30 ? $tr->total_contracted_hours_full_apprenticeship : $tr->part_time_total_contracted_hours_full_apprenticeship; ?></span><br>
                <span class="text-bold">OTJ hours: </span>
                <span class="text-info text-bold">
                    <?php
                    if($tr->otj_overwritten != '')
                    {
                        echo $tr->otj_overwritten;
                    } 
                    else
                    {
                        echo $tr->contracted_hours_per_week >= 30 ? $tr->off_the_job_hours_based_on_duration : $tr->part_time_otj_hours;
                    }
                    ?>
                </span><br>
                <?php if($tr->postJuly25Start() && $tr->otj_duration_pw != ''){ ?>
                    <span class="text-bold">OTJ Hours per week: </span><span class="text-info text-bold"><?php echo $tr->otjPW(); ?></span><br>
                <?php } ?>
                <?php if($_SESSION['user']->isAdmin() && $tr->practical_period_start_date >= '2023-08-01'){?>
                <span id="btnOverwriteOtj" class="pull-right btn btn-xs btn-primary" title="Update OTJ Hours" onclick="$('#modalOverwriteOtj').modal('show');"><i class="fa fa-edit"></i></span>
                <?php } ?>
                <br>
		<span class="text-bold">Total weeks on <?php echo $app_label; ?>: </span><span class="text-info text-bold"><?php echo round($t_wks_on_prog); ?></span><br>
                <span class="text-bold">Actual weeks on programme: </span><span class="text-info text-bold"><?php echo round($a_wks_on_prog); ?></span><br>
            </td>
        </tr>
    </table>
</div>

<?php if(!$tr->isNonApp($link)){?>
<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <td>
                <span class="text-bold text-info">TNP1 Prices</span><br>
                <?php
                $tnp1_total = 0;
				//$tnp1_prices = (is_null($tr->tnp1) || $tr->tnp1 == 0 ) ? [] : json_decode($tr->tnp1);
				$tnp1_prices = is_null($tr->tnp1) ? [] : json_decode($tr->tnp1);
                foreach ($tnp1_prices as $tnp1_price) {
                    $info_icon = $tnp1_price->reduce == 1 ? '<i class="fa fa-info-circle text-info" title="This price item is reduced based on Skills Scan result"></i>' : '';
                    echo '<span class="text-bold">' . $tnp1_price->description . ': </span><span class="text-info text-bold">' . $tnp1_price->cost . '</span> &nbsp; ' . $info_icon . '<br>';
                    $cost = is_numeric($tnp1_price->cost) ? (float)$tnp1_price->cost : 0;
                    $tnp1_total += $cost;
                }				
                echo '<span class="text-bold">TNP1 Total: </span><span class="text-info text-bold">' . ceil($tnp1_total) . '</span><br>';
                ?>
                <br><span class="text-bold text-info">TNP2 Price</span><br>
                <span class="text-bold">EPA Cost: </span><span class="text-info text-bold"><?php echo $tr->epa_price; ?></span><br>
                <br><span class="text-bold text-info">TNP</span><br>
                <span class="text-bold">TNP1 + TNP2: </span><span class="text-info text-bold"><?php echo ceil($tnp1_total + $tr->epa_price); ?></span>
				<?php 
                if(DB_NAME == "am_ela") 
                {
                    echo '<br><br><span class="text-bold text-info">Type of Funding</span><br>';
                    echo '<span class="text-bold">' . $tr->type_of_funding . '</span>';
                }
                ?>
            </td>
            <td>
                <span class="text-bold text-info">Additional Prices</span><br>
                <?php
                $additional_prices = is_null($tr->additional_prices) ? [] : json_decode($tr->additional_prices);
                if (is_array($additional_prices)) {
                    foreach ($additional_prices as $additional_price) {
                        echo '<span class="text-bold">' . $additional_price->description . ': </span><span class="text-info text-bold">' . $additional_price->cost . '</span><br>';
                    }
                }
                ?>
            </td>
        </tr>
    </table>
</div>
<?php } ?>

<?php
        $order_by = DB_NAME == "am_ela" ? " ORDER BY ob_learner_quals.qual_sequence, ob_learner_quals.qual_start_date " : " ORDER BY ob_learner_quals.qual_start_date ";
        $offset_months = DB_NAME == "am_ela" ? " ,ob_learner_quals.qual_offset_months " : "";
        $eet_fields = (in_array(DB_NAME, ["am_eet", "am_puzzled"]) || $framework->fund_model == Framework::FUNDING_STREAM_99) ? ", ob_learner_quals.qual_level, ob_learner_quals.qual_dh, ob_learner_quals.qual_delivery_postcode, (SELECT description FROM lookup_qual_level WHERE lookup_qual_level.id = ob_learner_quals.qual_level) AS level_desc " : "";  
        $sql = <<<SQL
SELECT
 (SELECT description FROM lookup_qual_type WHERE id = qual_type) AS qual_type,
 qual_id, qual_title, qual_start_date, qual_end_date, qual_exempt, ob_learner_quals.id  {$offset_months} {$eet_fields}
FROM
  ob_learner_quals WHERE tr_id = '{$tr->id}' $order_by ;                                
SQL;
        $records_ob_quals = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
?>
<?php if(is_null($framework->fund_model) || $framework->fund_model == Framework::FUNDING_STREAM_APP) { ?>
<div class="table-responsive">
    <table class="table table-bordered">
        <caption class="text-bold text-center bg-gray">Qualifications (<?php echo count($records_ob_quals); ?>)</caption>
        <tr>
            <td colspan="6">
                <form name="frm_add_tr_qualification" method="post" action="do.php?_action=ajax_helper">
                    <input type="hidden" name="subaction" value="add_tr_qualification" />
                    <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                    <span class="text-bold">Add Qualification: </span> &nbsp;
                    <?php echo HTML::select('qualification', DAO::getResultset($link, "SELECT auto_id, CONCAT(id, ' - ', title), null FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND auto_id NOT IN (SELECT framework_qual_auto_id FROM ob_learner_quals WHERE tr_id = '{$tr->id}') ORDER BY main_aim DESC, title"), '', true); ?> &nbsp;
                    <span class="btn btn-primary btn-xs" onclick="add_tr_qualification();"><i class="fa fa-plus"></i> Add Qualification</span>
                </form>
            </td>
        </tr>
        <tr>
            <th>Type</th>
            <th>Number</th>
            <th>Title</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Duration (months)</th>
	        <?php echo DB_NAME == "am_ela" ? "<th>Offset (months)</th>" : ""; ?>
            <th>Exempt</th>
            <th></th>
        </tr>
        <?php
        foreach ($records_ob_quals as $row_ob_qual) {
            echo '<tr>';
            echo '<td>' . $row_ob_qual['qual_type'] . '</td>';
            echo '<td>' . $row_ob_qual['qual_id'] . '</td>';
            echo '<td>' . $row_ob_qual['qual_title'] . '</td>';
            echo '<td>' . Date::toShort($row_ob_qual['qual_start_date']) . '</td>';
            echo '<td>' . Date::toShort($row_ob_qual['qual_end_date']) . '</td>';
            //echo '<td>' . DAO::getSingleValue($link, "SELECT duration_in_months FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND REPLACE(framework_qualifications.id, '/', '') = '{$row_ob_qual['qual_id']}' AND framework_qualifications.title = '{$row_ob_qual['qual_title']}'") . '</td>';
	        echo '<td>' . DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '{$row_ob_qual['qual_start_date']}', DATE_ADD('{$row_ob_qual['qual_end_date']}', INTERVAL 1 DAY));") . '</td>';
	        echo DB_NAME == "am_ela" ? '<td>' . $row_ob_qual['qual_offset_months'] . '</td>' : "";
            if($row_ob_qual['qual_exempt'] == 0)
            {
                echo '<td>No</td>';    
            }
            elseif($row_ob_qual['qual_exempt'] == 1)
            {
                echo '<td>Yes</td>';    
            }
            elseif($row_ob_qual['qual_exempt'] == 2)
            {
                echo '<td>Pending</td>';    
            }
            else
            {
                echo '<td></td>';    
            }
            echo '<td>';
            echo '<span class="btn btn-info btn-xs" title="Click to edit details for this aim, e.g: exemption status, dates etc."><i class="fa fa-edit" onclick="window.location.replace(\'do.php?_action=edit_training_qualification_details&tr_id='.$tr->id.'&ob_learner_qual_id='.$row_ob_qual['id'].'\');"></i></span> &nbsp; ';
            echo '<span class="btn btn-danger btn-xs" title="Click to remove this qualification from this record."><i class="fa fa-trash" onclick="remove_tr_qualification(\'' . $row_ob_qual['id'] . '\');"></i></span>';
            echo '</td>';
            echo '</tr>';
        }

        ?>
    </table>
</div>
<?php } ?>

<?php if(in_array($framework->fund_model, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_99])) { ?>
<div class="table-responsive">
    <table class="table table-bordered">
        <caption class="text-bold text-center bg-gray">Qualifications (<?php echo count($records_ob_quals); ?>)</caption>
        <tr>
            <td colspan="6">
                <form name="frm_add_tr_qualification" method="post" action="do.php?_action=ajax_helper">
                    <input type="hidden" name="subaction" value="add_tr_qualification" />
                    <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                    <span class="text-bold">Add Qualification: </span> &nbsp;
                    <?php echo HTML::select('qualification', DAO::getResultset($link, "SELECT auto_id, CONCAT(id, ' - ', title), null FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND auto_id NOT IN (SELECT framework_qual_auto_id FROM ob_learner_quals WHERE tr_id = '{$tr->id}') ORDER BY main_aim DESC, title"), '', true); ?> &nbsp;
                    <span class="btn btn-primary btn-xs" onclick="add_tr_qualification();"><i class="fa fa-plus"></i> Add Qualification</span>
                </form>
            </td>
        </tr>
        <tr>
            <th>Number</th>
            <th>Title</th>
            <th>Level</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>GLH</th>
	        <th>Delivery Postcode</th>
            <th></th>
        </tr>
        <?php
        foreach ($records_ob_quals as $row_ob_qual) {
            echo '<tr>';
            echo '<td>' . ($row_ob_qual['qual_id'] ?? '') . '</td>';
            echo '<td>' . ($row_ob_qual['qual_title'] ?? '') . '</td>';
            echo '<td>' . ($row_ob_qual['qual_level'] ?? '') . ' ' . ($row_ob_qual['level_desc'] ?? '') . '</td>';
            echo '<td>' . Date::toShort($row_ob_qual['qual_start_date'] ?? '') . '</td>';
            echo '<td>' . Date::toShort($row_ob_qual['qual_end_date'] ?? '') . '</td>';
            echo '<td>' . ($row_ob_qual['qual_dh'] ?? '') . '</td>';
            echo '<td>' . ($row_ob_qual['qual_delivery_postcode'] ?? '') . '</td>';
            echo '<td>';
            echo '<span class="btn btn-info btn-xs" title="Click to edit details for this aim, e.g: exemption status, dates etc."><i class="fa fa-edit" onclick="window.location.replace(\'do.php?_action=edit_training_qualification_details&tr_id='.$tr->id.'&ob_learner_qual_id='.$row_ob_qual['id'].'\');"></i></span> &nbsp; ';
            echo '<span class="btn btn-danger btn-xs" title="Click to remove this qualification from this record."><i class="fa fa-trash" onclick="remove_tr_qualification(\'' . $row_ob_qual['id'] . '\');"></i></span>';
            echo '</td>';
            echo '</tr>';
        }

        ?>
    </table>
</div>
<?php } ?>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr class="bg-gray">
            <th>LLDD</th>
            <th>Employment Status</th>
        </tr>
        <tr>
            <td>
                <span class="text-bold">Does learner have a learning difficulty, health problem or disability?: </span><span class="text-info text-bold"><?php echo isset($training_screen_training_details_LLDD[$tr->LLDD]) ? $training_screen_training_details_LLDD[$tr->LLDD]: ''; ?></span><br>
                <?php 
                if($tr->LLDD == 'Y')
                {
                    echo '<span class="text-bold">LLDD Categories: </span><br>';
                    echo '<span class="text-info text-bold">';
                    foreach($training_screen_training_details_selected_llddcat AS $_selected_lldd)
                    {
                        echo isset($training_screen_training_details_LLDDCat[$_selected_lldd]) ? $training_screen_training_details_LLDDCat[$_selected_lldd] : '';
                        echo $_selected_lldd == $tr->primary_lldd ? ' <label class="label label-primary">Primary LLDD</label>' : '';
                        echo '<br>';
                    }
                    echo '</span>';
                }
                ?>
            </td>
            <td>
                <span class="text-bold">
                    What learner did prior to starting <?php echo ucfirst($app_label); ?> Programme on the <label><?php echo Date::toLong($tr->practical_period_start_date); ?></label>: 
                </span>
                <span class="text-info text-bold">
                    <?php 
                    if($tr->EmploymentStatus == '10')
                        echo 'In paid employment';
                    elseif($tr->EmploymentStatus == '11')
                        echo 'Not in paid employment, looking for work and available to start work';
                    elseif($tr->EmploymentStatus == '12')
                        echo 'Not in paid employment, not looking for work and/or not available to start work';
                    elseif($tr->EmploymentStatus == '98')
                        echo 'Not known / don\'t want to provide';
                    else
                        echo '';    
                    ?>
                </span><br>
                <?php 
                if($tr->EmploymentStatus == '10')
                {
                    echo '<span class="text-bold">Was the learner employed with current employer prior to starting ' . ucfirst($app_label) . ' Programme? </span>';
                    echo $tr->work_curr_emp == '1' ? '<span class="text-info text-bold">Yes</span>' : '<span class="text-info text-bold">No</span>';
                    echo '<br>';
                    echo '<span class="text-bold">If not, was the learner self-employed? </span>';
                    echo $tr->SEI == '1' ? '<span class="text-info text-bold">Yes</span>' : '<span class="text-info text-bold">No</span>';
                    echo '<br>';
                    echo '<span class="text-bold">Employer Name? </span>';
                    echo '<span class="text-info text-bold">' . $tr->empStatusEmployer . '</span>';
                    echo '<br>';
                    echo '<span class="text-bold">How long the learner was employed? </span>';
                    echo isset($LOE_dropdown[$tr->LOE])  ? '<span class="text-info text-bold">' . $LOE_dropdown[$tr->LOE] . '</span>' : '<span class="text-info text-bold"></span>';
                    echo '<br>';
                    echo '<span class="text-bold">How many hours did learner work each week? </span>';
                    echo isset($EII_dropdown[$tr->EII])  ? '<span class="text-info text-bold">' . $EII_dropdown[$tr->EII] . '</span>' : '<span class="text-info text-bold"></span>';
                    echo '<br>';
                }
                if($tr->EmploymentStatus == '11' || $tr->EmploymentStatus == '12')
                {
                    echo '<span class="text-bold">How long the learner was un-employed before ' . Date::toLong($tr->apprenticeship_start_date) . '</label>? </span>';
                    echo isset($LOU_dropdown[$tr->LOU])  ? '<span class="text-info text-bold">' . $LOU_dropdown[$tr->LOU] . '</span>' : '<span class="text-info text-bold"></span>';
                    echo '<br>';
                    echo '<span class="text-bold">Did learner receive any of these benefits? </span>';
                    echo isset($BSI_dropdown[$tr->BSI])  ? '<span class="text-info text-bold">' . $BSI_dropdown[$tr->BSI] . '</span>' : '<span class="text-info text-bold"></span>';
                    echo '<br>';
                    echo '<span class="text-bold">Was the learner in Full Time Education or Training prior to ' . Date::toLong($tr->apprenticeship_start_date) . '? </span> ';
                    echo $tr->PEI == '1' ? '<span class="text-info text-bold">Yes</span>' : '<span class="text-info text-bold">No</span>';
                    echo '<br>';
                }
                ?>
            </td>
        </tr>
    </table>
</div>

<?php 
if(in_array(DB_NAME, ["am_ela"]) && $framework->fund_model == Framework::FUNDING_STREAM_APP)
{
    $extra_info = DAO::getObject($link, "SELECT * FROM ob_learner_extra_details WHERE tr_id = '{$tr->id}'");
    if(!isset($extra_info->tr_id))
    {
        $extra_info = new stdClass();
        $ob_learner_extra_details_fields = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_extra_details");
        foreach($ob_learner_extra_details_fields AS $extra_info_key => $extra_info_value)
            $extra_info->$extra_info_value = null;

    }
?>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr class="bg-gray">
                <th>Company induction - a full company induction has been provided or is planned to cover </th><th>Date Completed / Planned</th>
            </tr>
            <tr>
                <td>A full workplace induction with the company that you will be completing your <?php echo ucfirst($app_label); ?> with</td>
                <td><?php echo Date::toShort($extra_info->induction_f1); ?></td>
            <tr>
                <td>Information about legislations and regulations which affect your job role including:
                    <ul style="margin-left: 10px;">
                        <li>Health and Safety</li>
                        <li>Data Protection</li>
                        <li>Prohibitions and restrictions as applicable</li>
                    </ul>
                </td>
                <td><?php echo Date::toShort($extra_info->induction_f2); ?></td>
            <tr>
                <td>Company Disciplinary and Grievance procedures including who you should talk to if you have a problem at work</td>
                <td><?php echo Date::toShort($extra_info->induction_f3); ?></td>
            <tr>
                <td>Information about your rights and responsibilities when you are working including:
                    <ul style="margin-left: 10px;">
                        <li>Holiday entitlement</li>
                        <li>Salary Information</li>
                        <li>Absence reporting</li>
                        <li>Attendance and Professional Codes of Conduct which are applicable</li>
                    </ul>
                </td>
                <td><?php echo Date::toShort($extra_info->induction_f4); ?></td>
            </tr>
            <tr>
                <td>Employment Contract</td>
                <td><?php echo $extra_info->employment_contract; ?></td>
            </tr>
            <tr>
                <td>Employment Start Date</td>
                <td><?php echo Date::toShort($extra_info->employment_start_date); ?></td>
            </tr>
        </table>
    </div>
</div>
<?php } ?>

<?php if($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN) { ?>
    <div class="row">
        <div class="col-sm-6">
            <table class="table table-responsive">
                <tr>
                    <th>Commercial Fee</th>
                    <td><?php echo $tr->commercial_fee; ?></td>
                </tr>
                <tr>
                    <th>Employer paying any part of fee</th>
                    <td><?php echo $tr->commercial_fee_emp_cont; ?></td>
                </tr>
                <tr>
                    <th>Advanced Learner Loan Amount</th>
                    <td><?php echo $tr->all_amount; ?></td>
                </tr>
                <tr>
                    <th>Learner had Advanced Learner Loan before</th>
                    <td><?php echo $tr->all_before; ?></td>
                </tr>
            </table>
        </div>
    </div>
<?php } ?>

<?php if($framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL) { ?>
    <div class="row">
        <div class="col-sm-6">
            <table class="table table-responsive">
                <tr>
                    <th>Commercial Fee</th>
                    <td><?php echo $tr->commercial_fee; ?></td>
                </tr>
                <tr>
                    <th>Employer paying any part of fee</th>
                    <td><?php echo $tr->commercial_fee_emp_cont; ?></td>
                </tr>
                <tr>
                    <th>Purchase Order No.</th>
                    <td><?php echo $tr->purchase_order_no; ?></td>
                </tr>
            </table>
        </div>
    </div>
<?php } ?>