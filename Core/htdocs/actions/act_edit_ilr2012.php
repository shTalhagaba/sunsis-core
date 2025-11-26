<?php
class edit_ilr2012 implements IAction
{
	public function execute(PDO $link)
	{
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$L03 = isset($_REQUEST['L03'])?$_REQUEST['L03']:'';
		$pdf = isset($_REQUEST['pdf'])?$_REQUEST['pdf']:'';
		$template = isset($_REQUEST['template'])?$_REQUEST['template']:'';

		$_SESSION['bc']->add($link, "do.php?_action=edit_ilr2012&submission=" . $submission . "&contract_id=" . $contract_id . "&tr_id=" . $tr_id . "&L03=" . $L03, "Add/ Edit ILR Form");

		$max_submission = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date AND contract_year IN (SELECT contract_year FROM contracts WHERE id = $contract_id);");

		$how_many = DAO::getSingleValue($link, "select count(*) from ilr where tr_id = '$tr_id'");

		if($how_many>1)
			$how_many = 0;
		else
			$how_many = 1;

		if($submission == '' || $contract_id=='' || $tr_id=='')
		{
			$vo = XML::loadSimpleXML("<Learner></Learner>");
		}
		else
		{
			$vo = Ilr2012::loadFromDatabase($link, $submission, $contract_id, $tr_id, $L03);
		}

        $is_active = DAO::getSingleValue($link, "select is_active from ilr where submission = '$submission' and tr_id = '$tr_id' and contract_id = '$contract_id'");
        $is_approved = DAO::getSingleValue($link, "select is_approved from ilr where submission = '$submission' and tr_id = '$tr_id' and contract_id = '$contract_id'");
        $funding_type = Ilr2012::FundingType($vo);

		// If this is template
		if($template==1)
		{
			$xml = DAO::getSingleValue($link, "select template from contracts where id = '$contract_id'");
			$submission = "W01";
			$max_submission = "W01";
			$tr_id = 0;

			if($xml!='')
			{
				$vo = XML::loadSimpleXML($xml);
			}
			else
			{
				$vo = XML::loadSimpleXML("<Learner></Learner>");
			}
		}
		else
		{
			$template = 2;
		}

		$con = Contract::loadFromDatabase($link, $contract_id);

/*		if($template!=1)
		{
			if($submission!='W01')
			{
				$previous_submission = (int)substr($submission,1,2);
				$previous_submission--;
				if($previous_submission<=9)
					$previous_submission = "ER0" . $previous_submission;
				else
					$previous_submission = "ER" . $previous_submission;

				$previous_vo = Ilr2012::loadFromDatabase($link, $previous_submission, $contract_id, $tr_id, $L03);
			}
			else
			{
				$previous_vo = $vo;
			}
		}
*/

		$previous_vo = $vo;
		if($vo==null)
		{
			throw new Exception("Could not load from database");
		}

		// Drop down list arrays
		$UKPRN_dropdown = DAO::getResultset($link,"SELECT distinct UKPRN, LEFT(CONCAT(UKPRN,' ',Name),50), null from lis201213.providers order by UKPRN;", DAO::FETCH_NUM, "ILR2012 UKPRN dropdown");
		$Domicile_dropdown = DAO::getResultset($link,"SELECT distinct Domicile, LEFT(CONCAT(Domicile, ' ', Domicile_Desc),40), null from lis201213.ilr_domicile where Valid_To >= '2012-08-01' or Valid_To = '0000-00-00' order by Domicile;", DAO::FETCH_NUM, "ILR2012 Domicile dropdown");
		$Ethnicity_dropdown = DAO::getResultset($link,"SELECT distinct Ethnicity, LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60), null from lis201213.ilr_ethnicity order by Ethnicity;", DAO::FETCH_NUM, "ILR2012 Ethnicity dropdown");
		$PriorAttain_dropdown = DAO::getResultset($link,"SELECT distinct PriorAttain, LEFT(CONCAT(PriorAttain, ' ', PriorAttainDesc),50), null from lis201213.ilr_priorattain order by PriorAttain;", DAO::FETCH_NUM, "ILR2012 PriorAttain dropdown");
		$LLDDHealthProb_dropdown = DAO::getResultset($link,"SELECT distinct LLDDInd, LEFT(CONCAT(LLDDInd, ' ', LLDDInd_Desc),50), null from lis201213.ilr_llddind order by LLDDInd;", DAO::FETCH_NUM, "ILR2012 LLDDInd dropdown");
		$LLDDDS_dropdown = DAO::getResultset($link,"SELECT distinct LLDDCode, LEFT(CONCAT(LLDDCode, ' ', LLDDCode_Desc),50), null from lis201213.ilr_llddcode where LLDDType='DS' order by LLDDCode;", DAO::FETCH_NUM, "ILR2012 LLDDDS dropdown");
		$LLDDLD_dropdown = DAO::getResultset($link,"SELECT distinct LLDDCode, LEFT(CONCAT(LLDDCode, ' ', LLDDCode_Desc),50), null from lis201213.ilr_llddcode where LLDDType='LD' order by LLDDCode;", DAO::FETCH_NUM, "ILR2012 LLDDLD dropdown");
		$LSR_dropdown = DAO::getResultset($link,"SELECT distinct LearnFAMCode, LEFT(CONCAT(LearnFAMCode, ' ', LearnFAMTypeCode_Desc),50), null from lis201213.ilr_learnfamtypefamcode where LearnFAMType='LSR' and (Valid_To >= '2012-08-01' OR Valid_To = '0000-00-00') order by LearnFAMCode;", DAO::FETCH_NUM, "ILR2012 LSR dropdown");
		$NLM_dropdown = DAO::getResultset($link,"SELECT distinct LearnFAMCode, LEFT(CONCAT(LearnFAMCode, ' ', LearnFAMTypeCode_Desc),50), null from lis201213.ilr_learnfamtypefamcode where LearnFAMType='NLM' and (Valid_To >='2012-08-01' OR Valid_To = '0000-00-00') order by LearnFAMCode;", DAO::FETCH_NUM, "ILR2012 NLM dropdown");
		$LDA_dropdown = DAO::getResultset($link,"SELECT distinct LearnFAMCode, LEFT(CONCAT(LearnFAMCode, ' ', LearnFAMTypeCode_Desc),50), null from lis201213.ilr_learnfamtypefamcode where LearnFAMType='LDA' and (Valid_To >='2012-08-01' OR Valid_To = '0000-00-00') order by LearnFAMCode;", DAO::FETCH_NUM, "ILR2012 LDA dropdown");
		$ALS_dropdown = DAO::getResultset($link,"SELECT distinct LearnFAMCode, LEFT(CONCAT(LearnFAMCode, ' ', LearnFAMTypeCode_Desc),50), null from lis201213.ilr_learnfamtypefamcode where LearnFAMType='ALS' and (Valid_To >='2012-08-01' OR Valid_To = '0000-00-00') order by LearnFAMCode;", DAO::FETCH_NUM, "ILR2012 ALS dropdown");
        $EFE_dropdown = DAO::getResultset($link,"SELECT distinct LearnFAMCode, LEFT(CONCAT(LearnFAMCode, ' ', LearnFAMTypeCode_Desc),50), null from lis201213.ilr_learnfamtypefamcode where LearnFAMType='EFE' and (Valid_To >='2012-08-01' OR Valid_To = '0000-00-00') order by LearnFAMCode;", DAO::FETCH_NUM, "ILR2012 EFE dropdown");
		$DUE_dropdown = DAO::getResultset($link,"SELECT distinct LearnFAMCode, LEFT(CONCAT(LearnFAMCode, ' ', LearnFAMTypeCode_Desc),50), null from lis201213.ilr_learnfamtypefamcode where LearnFAMType='DUE' and (Valid_To >='2012-08-01' OR Valid_To = '0000-00-00') order by LearnFAMCode;", DAO::FETCH_NUM, "ILR2012 DUE dropdown");
		$DSF_dropdown = DAO::getResultset($link,"SELECT distinct LearnFAMCode, LEFT(CONCAT(LearnFAMCode, ' ', LearnFAMTypeCode_Desc),50), null from lis201213.ilr_learnfamtypefamcode where LearnFAMType='DSF' and (Valid_To >='2012-08-01' OR Valid_To = '0000-00-00') order by LearnFAMCode;", DAO::FETCH_NUM, "ILR2012 DSF dropdown");
		$EmpStat_dropdown = DAO::getResultset($link,"SELECT distinct EmpStatCode, LEFT(CONCAT(EmpStatCode, ' ', EmpStaCode_Desc),50), null from lis201213.ilr_empstatcode  order by EmpStatCode;", DAO::FETCH_NUM, "ILR2012 EmpStat dropdown");
		$Dest_dropdown = DAO::getResultset($link,"SELECT distinct Dest, LEFT(CONCAT(Dest, ' ', Dest_Desc),50), null from lis201213.ilr_dest order by Dest;", DAO::FETCH_NUM, "ILR2012 Dest dropdown");
		$FundModel_dropdown = DAO::getResultset($link,"SELECT distinct FundModel, LEFT(CONCAT(FundModel, ' ', FundModel_Desc),50), null from lis201213.ilr_fundmodel where Valid_To >= '2012-08-01' OR Valid_To = '0000-00-00' order by FundModel;", DAO::FETCH_NUM, "ILR2012 FundModel dropdown");
		$ContOrg_dropdown = DAO::getResultset($link,"SELECT distinct ContOrgCode, LEFT(CONCAT(ContOrgCode, ' ', ContOrgCode_Desc),50), null from lis201213.ilr_contorgcode order by ContOrgCode;", DAO::FETCH_NUM, "ILR2012 ContOrg dropdown");
		$ProgType_dropdown = DAO::getResultset($link,"SELECT distinct ProgType, LEFT(CONCAT(ProgType, ' ', ProgType_Desc),50), null from lis201213.ilr_progtype where Valid_To >= '2012-08-01' OR Valid_To = '0000-00-00' order by ProgType;", DAO::FETCH_NUM, "ILR2012 ProgType dropdown");
		$FworkCode2_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),50) ,null from lad201213.frameworks where FRAMEWORK_TYPE_CODE = '2' order by Framework_Code;", DAO::FETCH_NUM, "ILR2012 FworkCode2 dropdown");
		$FworkCode3_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),50) ,null from lad201213.frameworks where FRAMEWORK_TYPE_CODE = '3' order by Framework_Code;", DAO::FETCH_NUM, "ILR2012 FworkCode3 dropdown");
		$FworkCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),50) ,null from lad201213.frameworks order by Framework_Code;", DAO::FETCH_NUM, "ILR2012 FworkCode dropdown");
		$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Pathway_Code, LEFT(CONCAT(Framework_Pathway_Code, ' ', Framework_Pathway_Desc),50) ,null from lad201213.frameworks order by Framework_Code;", DAO::FETCH_NUM, "ILR2011 PwayCode dropdown");
		$ProgEntRoute_dropdown = DAO::getResultset($link,"SELECT distinct ProgEntRoute, LEFT(CONCAT(ProgEntRoute, ' ', ProgEntRoute_Desc), 50), null from lis201213.ilr_progentroute order by ProgEntRoute;", DAO::FETCH_NUM, "ILR2011 ProgEntRoute dropdown");
		$SOF_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),50), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'SOF' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2012 SOF dropdown");
		$NSA_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),50), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'NSA' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2012 NSA dropdown");
		$LDM_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),50), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'LDM' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2012 LDM dropdown");
		$EEF_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),50), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'EEF' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2012 EEF dropdown");
        $RES_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'RES' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2012 RES dropdown");
		$SSP_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'SSP' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2012 SSP dropdown");
		$SPP_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'SPP' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2012 SPP dropdown");
		$CVE_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'CVE' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2012 CVE dropdown");
		$CompStatus_dropdown = DAO::getResultset($link,"SELECT distinct CompStatus, LEFT(CONCAT(CompStatus, ' ', CompStatus_Desc),50), null from lis201213.ilr_compstatus order by CompStatus;", DAO::FETCH_NUM, "ILR2012 CompStatus dropdown");
		$Outcome_dropdown = DAO::getResultset($link,"SELECT distinct OutcomeInd, LEFT(CONCAT(OutcomeInd, ' ', OutcomeInd_Desc),50), null from lis201213.ilr_outcomeind order by OutcomeInd;", DAO::FETCH_NUM, "ILR2012 Outcome dropdown");
		$WithdrawReason_dropdown = DAO::getResultset($link,"SELECT distinct WithdrawReason, LEFT(CONCAT(WithdrawReason, ' ', WithdrawReason_Desc),50), null from lis201213.ilr_withdrawreason order by WithdrawReason;", DAO::FETCH_NUM, "ILR2012 WithdrawReason dropdown");
		$ActProgRoute_dropdown = DAO::getResultset($link,"SELECT distinct ActProgRoute, LEFT(CONCAT(ActProgRoute, ' ', ActProgRoute_Desc),50), null from lis201213.ilr_actprogroute order by ActProgRoute;", DAO::FETCH_NUM, "ILR2012 ActProgRoute dropdown");
		$MainDelMeth_dropdown = DAO::getResultset($link,"SELECT distinct MainDelMeth, LEFT(CONCAT(MainDelMeth, ' ', MainDelMeth_Desc),50), null from lis201213.ilr_maindelmeth order by MainDelMeth;", DAO::FETCH_NUM, "ILR2012 MainDelMeth dropdown");
		$PartnerUKPRN_dropdown = DAO::getResultset($link,"SELECT distinct UKPRN, LEFT(CONCAT(UKPRN,' ',Name),40), null from lis201213.providers order by Name;", DAO::FETCH_NUM, "ILR2012 PartnerUKPRN dropdown");
		$FFI_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'FFI' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2012 FFI dropdown");
		$ALN_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ALN' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2012 ALN dropdown");
		$ASN_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ASN' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2012 ASN dropdown");
		$ASL_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ASL' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2012 ASL dropdown");
		$RET_dropdown = DAO::getResultset($link,"SELECT distinct LearnDelFAMCode, LEFT(CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc),40), null from lis201213.ilr_learndelfamtypefamcode where LearnDelFAMType = 'RET' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2012 RET dropdown");
		$OutGrade_dropdown = DAO::getResultset($link,"SELECT distinct OutGrade, LEFT(CONCAT(OutGrade, ' ', OutGrade_Desc),50), null from lis201213.ilr_outgrade order by OutGrade;", DAO::FETCH_NUM, "ILR2012 OutGrade dropdown");
		$MainDelMeth_dropdown = DAO::getResultset($link,"SELECT distinct MainDelMeth, LEFT(CONCAT(MainDelMeth, ' ', MainDelMeth_Desc),50), null from lis201213.ilr_maindelmeth order by MainDelMeth;", DAO::FETCH_NUM, "ILR2012 MainDelMeth dropdown");
		$DelMode_dropdown = DAO::getResultset($link,"SELECT distinct DelMode, LEFT(CONCAT(DelMode, ' ', DelMode_Desc),50), null from lis201213.ilr_delmode order by DelMode;", DAO::FETCH_NUM, "ILR2012 DelMode dropdown");
		$FeeSource_dropdown = DAO::getResultset($link,"SELECT distinct FeeSource, LEFT(CONCAT(FeeSource, ' ', FeeSource_Desc),50), null from lis201213.ilr_feesource order by FeeSource;", DAO::FETCH_NUM, "ILR2012 FeeSource dropdown");
		$EmpRole_dropdown = DAO::getResultset($link,"SELECT distinct EmpRole, LEFT(CONCAT(EmpRole, ' ', EmpRole_Desc),50), null from lis201213.ilr_emprole order by EmpRole;", DAO::FETCH_NUM, "ILR2012 EmpRole dropdown");
		$EmpOutcome_dropdown = DAO::getResultset($link,"SELECT distinct EmpOutcome, LEFT(CONCAT(EmpOutcome, ' ', EmpOutcome_Desc),50), null from lis201213.ilr_empoutcome order by EmpOutcome;", DAO::FETCH_NUM, "ILR2012 EmpOutcome dropdown");
		$Accom_dropdown = DAO::getResultset($link,"SELECT distinct Accom, LEFT(CONCAT(Accom, ' ', Accom_Desc),50), null from lis201213.ilr_accom order by Accom;", DAO::FETCH_NUM, "ILR2012 Accom dropdown");


		require_once('tpl_edit_ilr2012_xml.php');
	}
}
?>