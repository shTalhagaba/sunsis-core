<?php
class edit_ilr2024 implements IAction
{
    public function execute(PDO $link)
    {
        $submission = isset($_REQUEST['submission']) ? $_REQUEST['submission'] : '';
        $contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '0';
        $L03 = isset($_REQUEST['L03']) ? $_REQUEST['L03'] : '';
        $pdf = isset($_REQUEST['pdf']) ? $_REQUEST['pdf'] : '';
        $template = isset($_REQUEST['template']) ? $_REQUEST['template'] : '';




        $default_tab = '';
        if (isset($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], "action=view_ilr_log")))
            $default_tab = "data-options='{\"defaultTab\": \"tab4\"}'";

        $_SESSION['bc']->add($link, "do.php?_action=edit_ilr2024&submission=" . $submission . "&contract_id=" . $contract_id . "&tr_id=" . $tr_id . "&L03=" . $L03, "Add/ Edit ILR Form");

        $max_submission = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date AND contract_year IN (SELECT contract_year FROM contracts WHERE id = $contract_id);");

        $how_many = DAO::getSingleValue($link, "select count(*) from ilr where tr_id = '$tr_id'");

        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);

        if ($how_many > 1)
            $how_many = 0;
        else
            $how_many = 1;

        if ($submission == '' || $contract_id == '' || $tr_id == '0') {
            $vo = XML::loadSimpleXML("<Learner></Learner>");
        } else {
            $vo = Ilr2024::loadFromDatabase($link, $submission, $contract_id, $tr_id, $L03);
        }

        if ($template != 1)
            $is_active = DAO::getSingleValue($link, "select is_active from ilr where submission = '$submission' and tr_id = '$tr_id' and contract_id = '$contract_id'");
        elseif ($template == 1)
            $is_active = DAO::getSingleValue($link, "select active from contracts where id = '$contract_id'");
        $is_approved = DAO::getSingleValue($link, "select is_approved from ilr where submission = '$submission' and tr_id = '$tr_id' and contract_id = '$contract_id'");
        $funding_type = Ilr2018::FundingType($vo);

        // --- *
        // dynamic field displays
        // ---

        $dynamic_field_sql = 'select xml_fieldname, level, historic_al_code, readable_fieldname, fieldname_description, programme_aims, 1 as required from central.lookup_ilr_2024_fields';

        $dynamic_funding_type = strtolower($funding_type);

        $dynamic_table_types = DAO::getTableFields($link, 'central.lookup_ilr_2024_fields');
        if (isset($dynamic_table_types[$dynamic_funding_type])) {
            $dynamic_field_sql = 'select xml_fieldname, level, historic_al_code, readable_fieldname, fieldname_description, programme_aims, ' . $dynamic_funding_type . ' as required from central.lookup_ilr_2024_fields';
        }

        $this->build_dynamic_fields(DAO::getResultset($link, $dynamic_field_sql, DAO::FETCH_ASSOC));

        // --- *

        // If this is template
        if ($template == 1) {
            $xml = DAO::getSingleValue($link, "select template from contracts where id = '$contract_id'");

            if ($xml != '') {
                $vo = XML::loadSimpleXML($xml);
            } else {
                //pre("data missing");
                $vo = XML::loadSimpleXML("<Learner><LearningDelivery><AimType>1</AimType><LearnAimRef>ZPROG001</LearnAimRef><FundModel>36</FundModel></LearningDelivery></Learner>");
            }
        } else {
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
        if ($vo == null) {
            throw new Exception("Could not load from database");
        }

        // Drop down list arrays
        $UKPRN_name = DAO::getSingleValue($link, "SELECT Name from lis201415.providers where UKPRN = '" . $con->ukprn . "' ");

        // Drop down list arrays

        $aimtype_dropdown = DAO::getResultset($link, "select distinct AimType, CONCAT(AimType, ' ',AimType_Desc), null from lis201415.ilr_aimtype order by AimType;", DAO::FETCH_NUM, "ILR2014 Aim Type dropdown");


        // Drop down list arrays
        $UKPRN_dropdown = DAO::getResultset($link, "SELECT distinct UKPRN, CONCAT(UKPRN,' ',Name), null from lis201415.providers order by UKPRN;", DAO::FETCH_NUM, "ILR2014 UKPRN dropdown2");
        $Ethnicity_dropdown = DAO::getResultset($link, "SELECT distinct Ethnicity, CONCAT(Ethnicity, ' ', Ethnicity_Desc), null from lis201415.ilr_ethnicity order by Ethnicity;", DAO::FETCH_NUM, "ILR2014 Ethnicity dropdown2");
        $PriorAttain_dropdown = DAO::getResultset($link, "SELECT distinct PriorAttain, CONCAT(PriorAttain, ' ', PriorAttainDesc), null from lis201415.ilr_priorattain order by PriorAttain;", DAO::FETCH_NUM, "ILR2014 PriorAttain dropdown2");
        $PriorAttain_dropdown2 = DAO::getResultset($link, "SELECT distinct PriorAttain, CONCAT(PriorAttain, ' ', PriorAttainDesc), null from lis201415.ilr_priorattain2 order by PriorAttain;", DAO::FETCH_NUM, "ILR2014 PriorAttain dropdown3");
        $LLDDHealthProb_dropdown = DAO::getResultset($link, "SELECT distinct LLDDInd, CONCAT(LLDDInd, ' ', LLDDInd_Desc), null from lis201415.ilr_llddind order by LLDDInd;", DAO::FETCH_NUM, "ILR2014 LLDDInd dropdown2");
        $LLDDDS_dropdown = DAO::getResultset($link, "SELECT distinct LLDDCode, CONCAT(LLDDCode, ' ', LLDDCode_Desc), null from lis201415.ilr_llddcode where LLDDType='DS' order by LLDDCode;", DAO::FETCH_NUM, "ILR2014 LLDDDS dropdown2");
        $LLDDLD_dropdown = DAO::getResultset($link, "SELECT distinct LLDDCode, CONCAT(LLDDCode, ' ', LLDDCode_Desc), null from lis201415.ilr_llddcode where LLDDType='LD' order by LLDDCode;", DAO::FETCH_NUM, "ILR2014 LLDDLD dropdown2");
        $EmpStat_dropdown = DAO::getResultset($link, "SELECT distinct EmpStatCode, CONCAT(EmpStatCode, ' ', EmpStaCode_Desc), null from lis201415.ilr_empstatcode  order by EmpStatCode;", DAO::FETCH_NUM, "ILR2014 EmpStat dropdown2");
        $StdCode_dropdown = DAO::getResultset($link, "SELECT DISTINCT StandardCode, CONCAT(StandardCode, ' - ', StandardName) ,LEFT(StandardName, 1) FROM lars201718.Core_LARS_Standard ORDER BY StandardName;", DAO::FETCH_NUM, "ILR2017 SC dropdown1");
        //$FundModel_dropdown = DAO::getResultset($link,"SELECT distinct FundModel, CONCAT(FundModel, ' ', FundModel_Desc), null from lis201415.ilr_fundmodel where Valid_To >= '2013-08-01' OR Valid_To = '0000-00-00' order by FundModel;", DAO::FETCH_NUM, "ILR2014 FundModel dropdown2");
        $ContOrg_dropdown = DAO::getResultset($link, "SELECT distinct ContOrgCode, CONCAT(ContOrgCode, ' ', ContOrgCode_Desc), null from lis201415.ilr_contorgcode order by ContOrgCode;", DAO::FETCH_NUM, "ILR2014 ContOrg dropdown2");
        //$ProgType_dropdown = DAO::getResultset($link,"SELECT distinct ProgType, CONCAT(ProgType, ' ', ProgType_Desc), null from lis201415.ilr_progtype order by ProgType;", DAO::FETCH_NUM, "ILR20142 ProgType dropdown2");
        $FworkCode2_dropdown = DAO::getResultset($link, "SELECT DISTINCT FworkCode, CONCAT(FworkCode, ' ', IssuingAuthorityTitle) ,NULL FROM lars201516.Core_LARS_Framework WHERE PathwayName !='' AND ProgType = '2' ORDER BY FworkCode;", DAO::FETCH_NUM, "ILR2014 FworkCode2 dropdown6");
        $FworkCode3_dropdown = DAO::getResultset($link, "SELECT DISTINCT FworkCode, CONCAT(FworkCode, ' ', IssuingAuthorityTitle) ,NULL FROM lars201516.Core_LARS_Framework WHERE ProgType = '3' ORDER BY FworkCode;", DAO::FETCH_NUM, "ILR2014 FworkCode3 dropdown7");
        $FworkCode4_dropdown = DAO::getResultset($link, "SELECT DISTINCT FworkCode, CONCAT(FworkCode, ' ', IssuingAuthorityTitle) ,NULL FROM lars201516.Core_LARS_Framework WHERE ProgType = '21' ORDER BY FworkCode;", DAO::FETCH_NUM, "ILR2014 FworkCode4 dropdown8");
        $FworkCode5_dropdown = DAO::getResultset($link, "SELECT DISTINCT FworkCode, CONCAT(FworkCode, ' ', IssuingAuthorityTitle) ,NULL FROM lars201516.Core_LARS_Framework WHERE ProgType = '20' ORDER BY FworkCode;", DAO::FETCH_NUM, "ILR2014 FworkCode5 dropdown9");
        $FworkCode_dropdown = DAO::getResultset($link, "SELECT DISTINCT FworkCode, CONCAT(FworkCode, ' ', IssuingAuthorityTitle) ,NULL FROM lars201516.Core_LARS_Framework WHERE PathwayName !='' ORDER BY FworkCode;", DAO::FETCH_NUM, "ILR2014 FworkCode dropdown10");
        $PwayCode_dropdown = DAO::getResultset($link, "SELECT DISTINCT PwayCode, CONCAT(PwayCode, ' ', PathwayName) ,NULL FROM lars201516.Core_LARS_Framework;", DAO::FETCH_NUM, "ILR2014 PwayCode dropdown11");
        $ProgEntRoute_dropdown = DAO::getResultset($link, "SELECT distinct ProgEntRoute, CONCAT(ProgEntRoute, ' ', ProgEntRoute_Desc), null from lis201415.ilr_progentroute order by ProgEntRoute;", DAO::FETCH_NUM, "ILR2014 ProgEntRoute dropdown3");
        $SOF_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'SOF' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2014 SOF dropdown2");
        $NSA_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'NSA' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2014 NSA dropdown2");
        $LDM_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'LDM' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2014 LDM dropdown555");
        $EEF_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'EEF' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2014 EEF dropdown3");
        $EFE_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'EFE' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2014 EFE dropdown2");
        $RES_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'RES' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2014 RES dropdown2");
        $SSP_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'SSP' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2014 SSP dropdown2");
        $SPP_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'SPP' order by LearnDelFAMCode ;", DAO::FETCH_NUM, "ILR2014 SPP dropdown3");
        $CVE_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'CVE' order by CAST(LearnDelFAMCode AS SIGNED);", DAO::FETCH_NUM, "ILR2014 CVE dropdown2");
        $CompStatus_dropdown = DAO::getResultset($link, "SELECT distinct CompStatus, CONCAT(CompStatus, ' ', CompStatus_Desc), null from lis201415.ilr_compstatus order by CompStatus;", DAO::FETCH_NUM, "ILR2014 CompStatus dropdown21");
        $WithdrawReason_dropdown = DAO::getResultset($link, "SELECT distinct WithdrawReason, CONCAT(WithdrawReason, ' ', WithdrawReason_Desc), null from lis201415.ilr_withdrawreason order by WithdrawReason;", DAO::FETCH_NUM, "ILR2014 WithdrawReason dropdown3");
        $ActProgRoute_dropdown = DAO::getResultset($link, "SELECT distinct ActProgRoute, CONCAT(ActProgRoute, ' ', ActProgRoute_Desc), null from lis201415.ilr_actprogroute order by ActProgRoute;", DAO::FETCH_NUM, "ILR2014 ActProgRoute dropdown2");
        $MainDelMeth_dropdown = DAO::getResultset($link, "SELECT distinct MainDelMeth, CONCAT(MainDelMeth, ' ', MainDelMeth_Desc), null from lis201415.ilr_maindelmeth order by MainDelMeth;", DAO::FETCH_NUM, "ILR2014 MainDelMeth dropdown2");
        $PartnerUKPRN_dropdown = DAO::getResultset($link, "SELECT distinct UKPRN, CONCAT(UKPRN,' ',Name), null from lis201415.providers order by Name;", DAO::FETCH_NUM, "ILR2014 PartnerUKPRN dropdown2");
        $FFI_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'FFI' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2014 FFI dropdown2");
        $WPL_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'WPL' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2014 WPL dropdown2");
        $LSF_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'LSF' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2014 LSF dropdown2");
        $ALB_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ALB' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2014 ALB dropdown2");
        $ALN_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ALN' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2014 ALN dropdown2");
        $ADL_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ADL' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2014 ADL dropdown2");
        $ASN_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ASN' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2014 ASN dropdown2");
        $ASL_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'ASL' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2014 ASL dropdown2");
        $RET_dropdown = DAO::getResultset($link, "SELECT distinct LearnDelFAMCode, CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc), null from lis201415.ilr_learndelfamtypefamcode where LearnDelFAMType = 'RET' order by LearnDelFAMCode;", DAO::FETCH_NUM, "ILR2014 RET dropdown2");
        $OutGrade_dropdown = DAO::getResultset($link, "SELECT distinct OutGrade, CONCAT(OutGrade, ' ', OutGrade_Desc), null from lis201415.ilr_outgrade;", DAO::FETCH_NUM, "ILR2014 OutGrade dropdown4");
        $MainDelMeth_dropdown = DAO::getResultset($link, "SELECT distinct MainDelMeth, CONCAT(MainDelMeth, ' ', MainDelMeth_Desc), null from lis201415.ilr_maindelmeth order by MainDelMeth;", DAO::FETCH_NUM, "ILR2014 MainDelMeth dropdown2");
        $DelMode_dropdown = DAO::getResultset($link, "SELECT distinct DelMode, CONCAT(DelMode, ' ', DelMode_Desc), null from lis201415.ilr_delmode order by DelMode;", DAO::FETCH_NUM, "ILR2014 DelMode dropdown2");
        $FeeSource_dropdown = DAO::getResultset($link, "SELECT distinct FeeSource, CONCAT(FeeSource, ' ', FeeSource_Desc), null from lis201415.ilr_feesource order by FeeSource;", DAO::FETCH_NUM, "ILR2014 FeeSource dropdown2");
        $EmpRole_dropdown = DAO::getResultset($link, "SELECT distinct EmpRole, CONCAT(EmpRole, ' ', EmpRole_Desc), null from lis201415.ilr_emprole order by EmpRole;", DAO::FETCH_NUM, "ILR2014 EmpRole dropdown2");
        $EmpOutcome_dropdown = DAO::getResultset($link, "SELECT distinct EmpOutcome, CONCAT(EmpOutcome, ' ', EmpOutcome_Desc), null from lis201415.ilr_empoutcome order by EmpOutcome;", DAO::FETCH_NUM, "ILR2014 EmpOutcome dropdown2");
        $Accom_dropdown = DAO::getResultset($link, "SELECT distinct Accom, CONCAT(Accom, ' ', Accom_Desc), null from lis201415.ilr_accom order by Accom;", DAO::FETCH_NUM, "ILR2014 Accom dropdown2");
        $QUALENT3_dropdown = DAO::getResultset($link, "SELECT distinct QualEnt3, CONCAT(QualEnt3, ' ', QualEnt3_Desc), null from lis201415.ilr_qualent3 order by QualEnt3;", DAO::FETCH_NUM, "ILR2014 QualEnt3 dropdown3");
        $SOC2000_dropdown = DAO::getResultset($link, "SELECT distinct SOC2000, CONCAT(SOC2000, ' ', SOC2000_Code_Desc), null from lis201415.ilr_soc2000 order by SOC2000;", DAO::FETCH_NUM, "ILR2014 SOC2000 dropdown2");
        $SEC_dropdown = DAO::getResultset($link, "SELECT distinct SEC, CONCAT(SEC, ' ', Sec_Desc), null from lis201415.ilr_sec order by SEC;", DAO::FETCH_NUM, "ILR2014 SEC dropdown2");
        $TypeYr_dropdown = DAO::getResultset($link, "SELECT distinct TypeYr, CONCAT(TypeYr, ' ', TypeYr_Desc), null from lis201415.ilr_typeyr order by TypeYr;", DAO::FETCH_NUM, "ILR2014 TypeYr dropdown2");
        $ModeStud_dropdown = DAO::getResultset($link, "SELECT distinct ModeStud, CONCAT(ModeStud, ' ', ModeStud_Desc), null from lis201415.ilr_modestud order by ModeStud;", DAO::FETCH_NUM, "ILR2014 ModeStud dropdown2");
        $FundLev_dropdown = DAO::getResultset($link, "SELECT distinct FundLev, CONCAT(FundLev, ' ', FundLev_Desc), null from lis201415.ilr_fundlev order by FundLev;", DAO::FETCH_NUM, "ILR2014 FundLev dropdown2");
        $FundComp_dropdown = DAO::getResultset($link, "SELECT distinct FundComp, CONCAT(FundComp, ' ', FundComp_Desc), null from lis201415.ilr_fundcomp order by FundComp;", DAO::FETCH_NUM, "ILR2014 FundComp dropdown2");
        $MSTuFee_dropdown = DAO::getResultset($link, "SELECT distinct MSTuFee, CONCAT(MSTuFee, ' ', MSTuFee_Desc), null from lis201415.ilr_mstufee order by MSTuFee;", DAO::FETCH_NUM, "ILR2014 MSTuFee dropdown2");
        $SpecFee_dropdown = DAO::getResultset($link, "SELECT distinct SpecFee, CONCAT(SpecFee, ' ', SpecFee_Desc), null from lis201415.ilr_specfee order by SpecFee;", DAO::FETCH_NUM, "ILR2014 SpecFee dropdown2");
        $Domicile_dropdown = DAO::getResultset($link, "SELECT distinct Domicile, CONCAT(Domicile, ' - ', Domicile_Desc), null from lis201415.ilr_domicile order by Domicile;", DAO::FETCH_NUM, "ILR2014 Domicile dropdown2");
        $ELQ_dropdown = DAO::getResultset($link, "SELECT distinct ELQ, CONCAT(ELQ, ' ', ELQ_Desc), null from lis201415.ilr_elq order by ELQ;", DAO::FETCH_NUM, "ILR2014 ELQ dropdown2");
        $TTACCOM_dropdown = DAO::getResultset($link, "SELECT distinct TTACCOM, CONCAT(TTACCOM, ' ', TTACCOM_Desc), null from lis201415.ilr_ttaccom WHERE TTACCOM!=3 order by TTACCOM;", DAO::FETCH_NUM, "ILR2014 TTACCOM dropdown3");
        $Outcome_dropdown = array(
            array('1', '1 Achieved'),
            array('2', '2 Partial achievement'),
            array('3', '3 No achievement'),
            array('6', '6 Achieved but uncashed (AS-levels only)'),
            array('7', '7 Achieved and cashed (AS-levels only))'),
            array('8', '8 Learning activities are complete but the outcome is not yet known))')
        );

        $ACT_dropdown = array(
            array('1', '1 A levy or non-levy paying employer on the apprenticeship service and is funded through a contract for services with the employer'),
            array('2', '2 An employer that is not on the apprenticeship service and is funded through a contract for services with the ESFA')
        );

        if (DB_NAME == "am_ray_recruit" || DB_NAME == "am_demo")
            $internal_validation_questions = DAO::getResultset($link, "SELECT lookup_ilr_internal_validation.id,lookup_ilr_internal_validation.`description`,ilr_internal_validation.tr_id,ilr_internal_validation.`q_id`,ilr_internal_validation.`q_reply` FROM lookup_ilr_internal_validation LEFT JOIN ilr_internal_validation ON lookup_ilr_internal_validation.id = ilr_internal_validation.`q_id` AND lookup_ilr_internal_validation.`year` = {$con->contract_year} AND ilr_internal_validation.`submission` = '{$max_submission}' AND ilr_internal_validation.tr_id = {$tr_id}", DAO::FETCH_ASSOC);


        $employers = DAO::getResultset($link, "SELECT organisations.id, legal_name, lookup_org_type.org_type FROM organisations LEFT JOIN lookup_org_type  ON organisations.`organisation_type` = lookup_org_type.`id` WHERE organisation_type IN (2, 6) ORDER BY lookup_org_type.org_type, legal_name;");


        //throw new exception (pre($funding_type));

        require_once('tpl_edit_ilr2024.php');
    }

    private function build_dynamic_fields($data_fields)
    {
        foreach ($data_fields as $field_id => $field_values) {
            $this->dynamic_funding_fields[$field_values['xml_fieldname']] = $field_values;
        }
    }

    public function dynamic_field_display($field, $input_mask = '', $additionalHTML = '')
    {
        if (isset($this->dynamic_funding_fields[$field])) {
            if ($this->dynamic_funding_fields[$field]['required'] == 1) {
                if ($this->dynamic_funding_fields[$field]['readable_fieldname'] != "") {
                    echo '<td title="' . $this->dynamic_funding_fields[$field]['fieldname_description'] . '" class="fieldLabel_compulsory tooltip" ' . $additionalHTML . '>' . $this->dynamic_funding_fields[$field]['readable_fieldname'] . '<span>&nbsp;*&nbsp;</span><br/>';
                } else {
                    echo '<td title="' . $this->dynamic_funding_fields[$field]['fieldname_description'] . '" class="fieldLabel_compulsory tooltip" ' . $additionalHTML . '>' . $field . '<span>&nbsp;*&nbsp;</span><br/>';
                }
                $input_mask = str_replace("class=''", "class='compulsory'", $input_mask);
                echo $input_mask;
                echo '</td>';
            } else if ($this->dynamic_funding_fields[$field]['required'] == 2) {

                if ($this->dynamic_funding_fields[$field]['readable_fieldname'] != "") {
                    echo '<td title="' . $this->dynamic_funding_fields[$field]['fieldname_description'] . '" class="fieldLabel_optional tooltip" ' . $additionalHTML . '>' . $this->dynamic_funding_fields[$field]['readable_fieldname'] . '<br/>';
                } else {
                    echo '<td title="' . $this->dynamic_funding_fields[$field]['fieldname_description'] . '" class="fieldLabel_optional tooltip" ' . $additionalHTML . '>' . $field . '<br/>';
                }
                $input_mask = str_replace("class=''", "class='optional'", $input_mask);
                echo $input_mask;
                echo '</td>';
            } else {
                //echo '<td>field1:'.$field.'</td>';

            }
        } else {
            echo '<td>field2:' . $field . '</td>';
        }
    }

    public $dynamic_funding_fields = array();
}
