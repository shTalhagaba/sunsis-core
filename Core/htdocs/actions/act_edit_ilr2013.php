<?php
class edit_ilr2013 implements IAction
{
	public function execute(PDO $link)
	{
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'0';
		$L03 = isset($_REQUEST['L03'])?$_REQUEST['L03']:'';
		$pdf = isset($_REQUEST['pdf'])?$_REQUEST['pdf']:'';
		$template = isset($_REQUEST['template'])?$_REQUEST['template']:'';

		$_SESSION['bc']->add($link, "do.php?_action=edit_ilr2013&submission=" . $submission . "&contract_id=" . $contract_id . "&tr_id=" . $tr_id . "&L03=" . $L03, "Add/ Edit ILR Form");

		$max_submission = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date AND contract_year IN (SELECT contract_year FROM contracts WHERE id = $contract_id);");

		$how_many = DAO::getSingleValue($link, "select count(*) from ilr where tr_id = '$tr_id'");

        $ilrmigrated = DAO::getSingleValue($link, "select tr_id from ilr where tr_id = '$tr_id' and contract_id in (select id from contracts where contract_year = 2014)");

		if($how_many>1)
			$how_many = 0;
		else
			$how_many = 1;

		if($submission == '' || $contract_id=='' || $tr_id=='0')
		{
			$vo = XML::loadSimpleXML("<Learner></Learner>");
		}
		else
		{
			$vo = Ilr2013::loadFromDatabase($link, $submission, $contract_id, $tr_id, $L03);
		}

		$is_active = DAO::getSingleValue($link, "select is_active from ilr where submission = '$submission' and tr_id = '$tr_id' and contract_id = '$contract_id'");
		$is_approved = DAO::getSingleValue($link, "select is_approved from ilr where submission = '$submission' and tr_id = '$tr_id' and contract_id = '$contract_id'");
		$funding_type = Ilr2013::FundingType($vo);

		// --- *
		// dynamic field displays
		// ---

		$dynamic_field_sql = 'select xml_fieldname, level, historic_al_code, readable_fieldname, fieldname_description, programme_aims, 1 as required from central.lookup_ilr_2013_fields';

		$dynamic_funding_type = strtolower($funding_type);

		$dynamic_table_types = DAO::getTableFields($link, 'central.lookup_ilr_2013_fields');
		if ( isset($dynamic_table_types[$dynamic_funding_type]) ) {
			$dynamic_field_sql = 'select xml_fieldname, level, historic_al_code, readable_fieldname, fieldname_description, programme_aims, '.$dynamic_funding_type.' as required from central.lookup_ilr_2013_fields';
		}

		$this->build_dynamic_fields(DAO::getResultset($link, $dynamic_field_sql, DAO::FETCH_ASSOC));

		// --- *

		// If this is template
		if($template==1)
		{
			$xml = DAO::getSingleValue($link, "select template from contracts where id = '$contract_id'");

			if($xml!='')
			{
				$vo = XML::loadSimpleXML($xml);
			}
			else
			{
				//pre("data missing");
				$vo = XML::loadSimpleXML("<Learner><LearningDelivery><AimType>1</AimType><LearnAimRef>ZPROG001</LearnAimRef><FundModel>35</FundModel></LearningDelivery></Learner>");
			}
		}
		else
		{
			$template = 2;
		}

		$con = Contract::loadFromDatabase($link, $contract_id);

		/*		if($template!=1)
						{
							$previous_submission = (int)substr($submission,1,2);
							$previous_submission--;
							if($previous_submission<=9)
								$previous_submission = "ER0" . $previous_submission;
							else
								$previous_submission = "ER" . $previous_submission;

							$previous_vo = Ilr2012::loadFromDatabase($link, $previous_submission, $contract_id, $tr_id, $L03);
						}
				*/

		$previous_vo = $vo;
		if($vo==null)
		{
			throw new Exception("Could not load from database");
		}

		// Drop down list arrays
		$UKPRN_name = DAO::getSingleValue($link,"SELECT Name from lis201314.providers where UKPRN = '".$con->ukprn."' ");

		// Drop down list arrays

		$aimtype_dropdown = DAO::getResultset($link, "select distinct AimType, CONCAT(AimType, ' ',AimType_Desc), null from lis201314.ilr_aimtype order by AimType;", DAO::FETCH_NUM, "ILR2013 Aim Type dropdown");


		// Drop down list arrays
		$UKPRN_dropdown = DAO::getResultset($link,"SELECT distinct UKPRN, LEFT(CONCAT(UKPRN,' ',Name),50), null from lis201314.providers order by UKPRN;", DAO::FETCH_NUM, "ILR2013 UKPRN dropdown2");
		$Ethnicity_dropdown = DAO::getResultset($link,"SELECT distinct Ethnicity, LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60), null from lis201314.ilr_ethnicity order by Ethnicity;", DAO::FETCH_NUM, "ILR2013 Ethnicity dropdown2");
		$PriorAttain_dropdown = DAO::getResultset($link,"SELECT distinct PriorAttain, LEFT(CONCAT(PriorAttain, ' ', PriorAttainDesc),50), null from lis201314.ilr_priorattain order by PriorAttain;", DAO::FETCH_NUM, "ILR2013 PriorAttain dropdown2");
		$LLDDHealthProb_dropdown = DAO::getResultset($link,"SELECT distinct LLDDInd, LEFT(CONCAT(LLDDInd, ' ', LLDDInd_Desc),50), null from lis201314.ilr_llddind order by LLDDInd;", DAO::FETCH_NUM, "ILR2013 LLDDInd dropdown2");
		$LLDDDS_dropdown = DAO::getResultset($link,"SELECT distinct LLDDCode, LEFT(CONCAT(LLDDCode, ' ', LLDDCode_Desc),50), null from lis201314.ilr_llddcode where LLDDType='DS' order by LLDDCode;", DAO::FETCH_NUM, "ILR2013 LLDDDS dropdown2");
		$LLDDLD_dropdown = DAO::getResultset($link,"SELECT distinct LLDDCode, LEFT(CONCAT(LLDDCode, ' ', LLDDCode_Desc),50), null from lis201314.ilr_llddcode where LLDDType='LD' order by LLDDCode;", DAO::FETCH_NUM, "ILR2013 LLDDLD dropdown2");
		$EmpStat_dropdown = DAO::getResultset($link,"SELECT distinct EmpStatCode, LEFT(CONCAT(EmpStatCode, ' ', EmpStaCode_Desc),50), null from lis201314.ilr_empstatcode  order by EmpStatCode;", DAO::FETCH_NUM, "ILR2013 EmpStat dropdown2");
		$Dest_dropdown = DAO::getResultset($link,"SELECT distinct Dest, LEFT(CONCAT(Dest, ' ', Dest_Desc),50), null from lis201314.ilr_dest order by Dest;", DAO::FETCH_NUM, "ILR2013 Dest dropdown2");
		$FundModel_dropdown = DAO::getResultset($link,"SELECT distinct FundModel, LEFT(CONCAT(FundModel, ' ', FundModel_Desc),50), null from lis201314.ilr_fundmodel where Valid_To >= '2013-08-01' OR Valid_To = '0000-00-00' order by FundModel;", DAO::FETCH_NUM, "ILR2013 FundModel dropdown2");
		$ContOrg_dropdown = DAO::getResultset($link,"SELECT distinct ContOrgCode, LEFT(CONCAT(ContOrgCode, ' ', ContOrgCode_Desc),50), null from lis201314.ilr_contorgcode order by ContOrgCode;", DAO::FETCH_NUM, "ILR2013 ContOrg dropdown2");
		$ProgType_dropdown = DAO::getResultset($link,"SELECT distinct ProgType, LEFT(CONCAT(ProgType, ' ', ProgType_Desc),50), null from lis201314.ilr_progtype where Valid_To >= '2013-08-01' OR Valid_To = '0000-00-00' order by ProgType;", DAO::FETCH_NUM, "ILR2013 ProgType dropdown2");
		$FworkCode2_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),50) ,null from lad201314.frameworks where FRAMEWORK_TYPE_CODE = '2' order by Framework_Code;", DAO::FETCH_NUM, "ILR2013 FworkCode2 dropdown3");
		$FworkCode3_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),50) ,null from lad201314.frameworks where FRAMEWORK_TYPE_CODE = '3' order by Framework_Code;", DAO::FETCH_NUM, "ILR2013 FworkCode3 dropdown3");
		$FworkCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),50) ,null from lad201314.frameworks order by Framework_Code;", DAO::FETCH_NUM, "ILR2013 FworkCode dropdown5");
		$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Pathway_Code, LEFT(CONCAT(Framework_Pathway_Code, ' ', Framework_Pathway_Desc),50) ,null from lad201314.frameworks order by Framework_Code;", DAO::FETCH_NUM, "ILR2013 PwayCode dropdown3");
		$ProgEntRoute_dropdown = DAO::getResultset($link,"SELECT distinct ProgEntRoute, LEFT(CONCAT(ProgEntRoute, ' ', ProgEntRoute_Desc), 50), null from lis201314.ilr_progentroute order by ProgEntRoute;", DAO::FETCH_NUM, "ILR2013 ProgEntRoute dropdown3");
		$SOF_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),50), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'SOF' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2013 SOF dropdown2");
		$NSA_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),50), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'NSA' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2013 NSA dropdown2");
		$LDM_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),50), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'LDM' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2013 LDM dropdown3");
		$EEF_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),50), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'EEF' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2013 EEF dropdown2");
		$EFE_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),50), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'EFE' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2013 EFE dropdown2");
		$RES_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'RES' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2013 RES dropdown2");
		$SSP_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'SSP' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2013 SSP dropdown2");
		$SPP_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'SPP' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2013 SPP dropdown2");
		$CVE_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'CVE' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2013 CVE dropdown2");
		$CompStatus_dropdown = DAO::getResultset($link,"SELECT distinct CompStatus, LEFT(CONCAT(CompStatus, ' ', CompStatus_Desc),50), null from lis201314.ilr_compstatus order by CompStatus;", DAO::FETCH_NUM, "ILR2013 CompStatus dropdown2");
		$Outcome_dropdown = DAO::getResultset($link,"SELECT distinct OutcomeInd, LEFT(CONCAT(OutcomeInd, ' ', OutcomeInd_Desc),50), null from lis201314.ilr_outcomeind order by OutcomeInd;", DAO::FETCH_NUM, "ILR2013 Outcome dropdown2");
		$WithdrawReason_dropdown = DAO::getResultset($link,"SELECT distinct WithdrawReason, LEFT(CONCAT(WithdrawReason, ' ', WithdrawReason_Desc),50), null from lis201314.ilr_withdrawreason order by WithdrawReason;", DAO::FETCH_NUM, "ILR2013 WithdrawReason dropdown2");
		$ActProgRoute_dropdown = DAO::getResultset($link,"SELECT distinct ActProgRoute, LEFT(CONCAT(ActProgRoute, ' ', ActProgRoute_Desc),50), null from lis201314.ilr_actprogroute order by ActProgRoute;", DAO::FETCH_NUM, "ILR2013 ActProgRoute dropdown2");
		$MainDelMeth_dropdown = DAO::getResultset($link,"SELECT distinct MainDelMeth, LEFT(CONCAT(MainDelMeth, ' ', MainDelMeth_Desc),50), null from lis201314.ilr_maindelmeth order by MainDelMeth;", DAO::FETCH_NUM, "ILR2013 MainDelMeth dropdown2");
		$PartnerUKPRN_dropdown = DAO::getResultset($link,"SELECT distinct UKPRN, LEFT(CONCAT(UKPRN,' ',Name),40), null from lis201314.providers order by Name;", DAO::FETCH_NUM, "ILR2013 PartnerUKPRN dropdown2");
		$FFI_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'FFI' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2013 FFI dropdown2");
		$WPL_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'WPL' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2013 WPL dropdown2");
		$LSF_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'LSF' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2013 LSF dropdown2");
		$ALB_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ALB' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2013 ALB dropdown2");
		$ALN_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ALN' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2013 ALN dropdown2");
		$ADL_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ADL' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2013 ADL dropdown2");
		$ASN_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ASN' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2013 ASN dropdown2");
		$ASL_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ASL' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2013 ASL dropdown2");
		$RET_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201314.ilr_learndelfamtypefamcode where LearnDelFAMType = 'RET' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2013 RET dropdown2");
		$OutGrade_dropdown = DAO::getResultset($link,"SELECT distinct OutGrade, LEFT(CONCAT(OutGrade, ' ', OutGrade_Desc),50), null from lis201314.ilr_outgrade order by OutGrade;", DAO::FETCH_NUM, "ILR2013 OutGrade dropdown2");
		$MainDelMeth_dropdown = DAO::getResultset($link,"SELECT distinct MainDelMeth, LEFT(CONCAT(MainDelMeth, ' ', MainDelMeth_Desc),50), null from lis201314.ilr_maindelmeth order by MainDelMeth;", DAO::FETCH_NUM, "ILR2013 MainDelMeth dropdown2");
		$DelMode_dropdown = DAO::getResultset($link,"SELECT distinct DelMode, LEFT(CONCAT(DelMode, ' ', DelMode_Desc),50), null from lis201314.ilr_delmode order by DelMode;", DAO::FETCH_NUM, "ILR2013 DelMode dropdown2");
		$FeeSource_dropdown = DAO::getResultset($link,"SELECT distinct FeeSource, LEFT(CONCAT(FeeSource, ' ', FeeSource_Desc),50), null from lis201314.ilr_feesource order by FeeSource;", DAO::FETCH_NUM, "ILR2013 FeeSource dropdown2");
		$EmpRole_dropdown = DAO::getResultset($link,"SELECT distinct EmpRole, LEFT(CONCAT(EmpRole, ' ', EmpRole_Desc),50), null from lis201314.ilr_emprole order by EmpRole;", DAO::FETCH_NUM, "ILR2013 EmpRole dropdown2");
		$EmpOutcome_dropdown = DAO::getResultset($link,"SELECT distinct EmpOutcome, LEFT(CONCAT(EmpOutcome, ' ', EmpOutcome_Desc),50), null from lis201314.ilr_empoutcome order by EmpOutcome;", DAO::FETCH_NUM, "ILR2013 EmpOutcome dropdown2");
		$Accom_dropdown = DAO::getResultset($link,"SELECT distinct Accom, LEFT(CONCAT(Accom, ' ', Accom_Desc),50), null from lis201314.ilr_accom order by Accom;", DAO::FETCH_NUM, "ILR2013 Accom dropdown2");


		//throw new exception (pre($funding_type));


		require_once('tpl_edit_ilr2013.php');
	}

	private function build_dynamic_fields($data_fields) {
		foreach ( $data_fields as $field_id => $field_values ) {
			$this->dynamic_funding_fields[$field_values['xml_fieldname']] = $field_values;
		}

	}

	public function dynamic_field_display($field, $input_mask = '' ) {
		if ( isset($this->dynamic_funding_fields[$field]) ) {
			if ( $this->dynamic_funding_fields[$field]['required'] == 1 ) {
				if ( $this->dynamic_funding_fields[$field]['readable_fieldname'] != "" ) {
					echo '<td class="fieldLabel_compulsory">'.$this->dynamic_funding_fields[$field]['readable_fieldname'].'<span>&nbsp;*&nbsp;</span><br/>';
				}
				else {
					echo '<td class="fieldLabel_compulsory">'.$field.'<span>&nbsp;*&nbsp;</span><br/>';
				}
				$input_mask = str_replace("class=''", "class='compulsory'", $input_mask);
				echo $input_mask;
				echo '</td>';
			}
			else if( $this->dynamic_funding_fields[$field]['required'] == 2 ) {

				if ( $this->dynamic_funding_fields[$field]['readable_fieldname'] != "" ) {
					echo '<td class="fieldLabel_optional">'.$this->dynamic_funding_fields[$field]['readable_fieldname'].'<br/>';
				}
				else {
					echo '<td class="fieldLabel_optional">'.$field.'<br/>';
				}
				$input_mask = str_replace("class=''", "class='optional'", $input_mask);
				echo $input_mask;
				echo '</td>';
			}
			else {
				//echo '<td>field1:'.$field.'</td>';

			}
		}
		else {
			echo '<td>field2:'.$field.'</td>';
		}
	}

	public $dynamic_funding_fields = array();
}
?>