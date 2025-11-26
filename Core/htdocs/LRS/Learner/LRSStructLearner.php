<?php
/**
 * File for class LRSStructLearner
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructLearner originally named Learner
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/Amor.Qcf.MIAPModel.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructLearner extends LRSStructBusinessObject
{
    /**
     * The AbilityToShare
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $AbilityToShare;
    /**
     * The AtsLearnerPreference
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $AtsLearnerPreference;
    /**
     * The BirthDateVerification
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $BirthDateVerification;
    /**
     * The CountryOfAddress
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $CountryOfAddress;
    /**
     * The CountryOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $CountryOfBirth;
    /**
     * The CountryOfDomicle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $CountryOfDomicle;
    /**
     * The CountryOfLastKnownAddress
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $CountryOfLastKnownAddress;
    /**
     * The CreatedDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var dateTime
     */
    public $CreatedDate;
    /**
     * The CreatedViaChannel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $CreatedViaChannel;
    /**
     * The DateOfAddressCapture
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $DateOfAddressCapture;
    /**
     * The DateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var dateTime
     */
    public $DateOfBirth;
    /**
     * The DateOfDeath
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $DateOfDeath;
    /**
     * The DeathDateVerification
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $DeathDateVerification;
    /**
     * The Deceased
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $Deceased;
    /**
     * The DeceasedDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $DeceasedDate;
    /**
     * The DisabilityCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $DisabilityCode;
    /**
     * The EmailAddress
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $EmailAddress;
    /**
     * The EthnicityCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $EthnicityCode;
    /**
     * The EthnicityVerification
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $EthnicityVerification;
    /**
     * The EtlLoadId
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $EtlLoadId;
    /**
     * The FamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FamilyName;
    /**
     * The FamilyNameAt16
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FamilyNameAt16;
    /**
     * The FamilyNameFirst
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var boolean
     */
    public $FamilyNameFirst;
    /**
     * The FirstEstablismentId
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $FirstEstablismentId;
    /**
     * The Gender
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $Gender;
    /**
     * The GivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $GivenName;
    /**
     * The InReceiptOfDsa
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $InReceiptOfDsa;
    /**
     * The Initials
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Initials;
    /**
     * The LastCompiledLearnerPlanReport
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $LastCompiledLearnerPlanReport;
    /**
     * The LastCompiledLearnerRecordReport
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var dateTime
     */
    public $LastCompiledLearnerRecordReport;
    /**
     * The LastKnownAddressChanged
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $LastKnownAddressChanged;
    /**
     * The LastKnownAddressCountyOrCity
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressCountyOrCity;
    /**
     * The LastKnownAddressLine1
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressLine1;
    /**
     * The LastKnownAddressLine2
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressLine2;
    /**
     * The LastKnownAddressQualififer
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $LastKnownAddressQualififer;
    /**
     * The LastKnownAddressTown
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownAddressTown;
    /**
     * The LastKnownGender
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $LastKnownGender;
    /**
     * The LastKnownPostCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastKnownPostCode;
    /**
     * The LastUpdatedAction
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastUpdatedAction;
    /**
     * The LastUpdatedDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var dateTime
     */
    public $LastUpdatedDate;
    /**
     * The LastUpdatedLrbUsername
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastUpdatedLrbUsername;
    /**
     * The LastUpdatedLrsUsername
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LastUpdatedLrsUsername;
    /**
     * The LastUpdatedSearchKey
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $LastUpdatedSearchKey;
    /**
     * The LastUpdatedViaChannel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $LastUpdatedViaChannel;
    /**
     * The LearningDifficultyCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $LearningDifficultyCode;
    /**
     * The LinkedUlns
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfstring
     */
    public $LinkedUlns;
    /**
     * The ManualUpdate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $ManualUpdate;
    /**
     * The MaritalStatus
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $MaritalStatus;
    /**
     * The MaritalStatusVerification
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $MaritalStatusVerification;
    /**
     * The MasterUln
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $MasterUln;
    /**
     * The MiddleOtherName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $MiddleOtherName;
    /**
     * The NameSuffix
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $NameSuffix;
    /**
     * The NationalInsuranceNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $NationalInsuranceNumber;
    /**
     * The Nationality
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Nationality;
    /**
     * The NextDataChallengeNo
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $NextDataChallengeNo;
    /**
     * The NormalisedFamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $NormalisedFamilyName;
    /**
     * The NormalisedGivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $NormalisedGivenName;
    /**
     * The NormalisedPerferredGivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $NormalisedPerferredGivenName;
    /**
     * The NormalisedPreviousFamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $NormalisedPreviousFamilyName;
    /**
     * The Notes
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Notes;
    /**
     * The OtherVerificationDescription
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $OtherVerificationDescription;
    /**
     * The PlaceOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $PlaceOfBirth;
    /**
     * The PotentialDuplicateSearchKey
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $PotentialDuplicateSearchKey;
    /**
     * The PreferredFamilyFirstNameFirst
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var boolean
     */
    public $PreferredFamilyFirstNameFirst;
    /**
     * The PreferredFamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $PreferredFamilyName;
    /**
     * The PreferredGivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $PreferredGivenName;
    /**
     * The PreviousFamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $PreviousFamilyName;
    /**
     * The ReasonForDeletion
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ReasonForDeletion;
    /**
     * The RecordStatus
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $RecordStatus;
    /**
     * The ReferenceNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ReferenceNumber;
    /**
     * The RegisteredByOrganisationRef
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $RegisteredByOrganisationRef;
    /**
     * The RestrictedUse
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var boolean
     */
    public $RestrictedUse;
    /**
     * The SchoolAtAge16
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SchoolAtAge16;
    /**
     * The ScottishCandidateNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ScottishCandidateNumber;
    /**
     * The SecretQuestionId
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $SecretQuestionId;
    /**
     * The SecurityAnswer
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SecurityAnswer;
    /**
     * The SenType
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $SenType;
    /**
     * The SequentialVersionNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $SequentialVersionNumber;
    /**
     * The TelephoneNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $TelephoneNumber;
    /**
     * The TierLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $TierLevel;
    /**
     * The Title
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Title;
    /**
     * The Uln
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Uln;
    /**
     * The VerificationType
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $VerificationType;
    /**
     * The VersionNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $VersionNumber;
    /**
     * Constructor method for Learner
     * @see parent::__construct()
     * @param int $_abilityToShare
     * @param int $_atsLearnerPreference
     * @param int $_birthDateVerification
     * @param string $_countryOfAddress
     * @param string $_countryOfBirth
     * @param string $_countryOfDomicle
     * @param string $_countryOfLastKnownAddress
     * @param dateTime $_createdDate
     * @param int $_createdViaChannel
     * @param dateTime $_dateOfAddressCapture
     * @param dateTime $_dateOfBirth
     * @param dateTime $_dateOfDeath
     * @param int $_deathDateVerification
     * @param int $_deceased
     * @param dateTime $_deceasedDate
     * @param int $_disabilityCode
     * @param string $_emailAddress
     * @param string $_ethnicityCode
     * @param int $_ethnicityVerification
     * @param int $_etlLoadId
     * @param string $_familyName
     * @param string $_familyNameAt16
     * @param boolean $_familyNameFirst
     * @param int $_firstEstablismentId
     * @param int $_gender
     * @param string $_givenName
     * @param int $_inReceiptOfDsa
     * @param string $_initials
     * @param dateTime $_lastCompiledLearnerPlanReport
     * @param dateTime $_lastCompiledLearnerRecordReport
     * @param boolean $_lastKnownAddressChanged
     * @param string $_lastKnownAddressCountyOrCity
     * @param string $_lastKnownAddressLine1
     * @param string $_lastKnownAddressLine2
     * @param int $_lastKnownAddressQualififer
     * @param string $_lastKnownAddressTown
     * @param int $_lastKnownGender
     * @param string $_lastKnownPostCode
     * @param string $_lastUpdatedAction
     * @param dateTime $_lastUpdatedDate
     * @param string $_lastUpdatedLrbUsername
     * @param string $_lastUpdatedLrsUsername
     * @param int $_lastUpdatedSearchKey
     * @param int $_lastUpdatedViaChannel
     * @param int $_learningDifficultyCode
     * @param LRSStructArrayOfstring $_linkedUlns
     * @param boolean $_manualUpdate
     * @param int $_maritalStatus
     * @param int $_maritalStatusVerification
     * @param string $_masterUln
     * @param string $_middleOtherName
     * @param string $_nameSuffix
     * @param string $_nationalInsuranceNumber
     * @param string $_nationality
     * @param int $_nextDataChallengeNo
     * @param string $_normalisedFamilyName
     * @param string $_normalisedGivenName
     * @param string $_normalisedPerferredGivenName
     * @param string $_normalisedPreviousFamilyName
     * @param string $_notes
     * @param string $_otherVerificationDescription
     * @param string $_placeOfBirth
     * @param int $_potentialDuplicateSearchKey
     * @param boolean $_preferredFamilyFirstNameFirst
     * @param string $_preferredFamilyName
     * @param string $_preferredGivenName
     * @param string $_previousFamilyName
     * @param string $_reasonForDeletion
     * @param int $_recordStatus
     * @param string $_referenceNumber
     * @param string $_registeredByOrganisationRef
     * @param boolean $_restrictedUse
     * @param string $_schoolAtAge16
     * @param string $_scottishCandidateNumber
     * @param int $_secretQuestionId
     * @param string $_securityAnswer
     * @param int $_senType
     * @param int $_sequentialVersionNumber
     * @param string $_telephoneNumber
     * @param int $_tierLevel
     * @param string $_title
     * @param string $_uln
     * @param int $_verificationType
     * @param int $_versionNumber
     * @return LRSStructLearner
     */
    public function __construct($_abilityToShare = NULL,$_atsLearnerPreference = NULL,$_birthDateVerification = NULL,$_countryOfAddress = NULL,$_countryOfBirth = NULL,$_countryOfDomicle = NULL,$_countryOfLastKnownAddress = NULL,$_createdDate = NULL,$_createdViaChannel = NULL,$_dateOfAddressCapture = NULL,$_dateOfBirth = NULL,$_dateOfDeath = NULL,$_deathDateVerification = NULL,$_deceased = NULL,$_deceasedDate = NULL,$_disabilityCode = NULL,$_emailAddress = NULL,$_ethnicityCode = NULL,$_ethnicityVerification = NULL,$_etlLoadId = NULL,$_familyName = NULL,$_familyNameAt16 = NULL,$_familyNameFirst = NULL,$_firstEstablismentId = NULL,$_gender = NULL,$_givenName = NULL,$_inReceiptOfDsa = NULL,$_initials = NULL,$_lastCompiledLearnerPlanReport = NULL,$_lastCompiledLearnerRecordReport = NULL,$_lastKnownAddressChanged = NULL,$_lastKnownAddressCountyOrCity = NULL,$_lastKnownAddressLine1 = NULL,$_lastKnownAddressLine2 = NULL,$_lastKnownAddressQualififer = NULL,$_lastKnownAddressTown = NULL,$_lastKnownGender = NULL,$_lastKnownPostCode = NULL,$_lastUpdatedAction = NULL,$_lastUpdatedDate = NULL,$_lastUpdatedLrbUsername = NULL,$_lastUpdatedLrsUsername = NULL,$_lastUpdatedSearchKey = NULL,$_lastUpdatedViaChannel = NULL,$_learningDifficultyCode = NULL,$_linkedUlns = NULL,$_manualUpdate = NULL,$_maritalStatus = NULL,$_maritalStatusVerification = NULL,$_masterUln = NULL,$_middleOtherName = NULL,$_nameSuffix = NULL,$_nationalInsuranceNumber = NULL,$_nationality = NULL,$_nextDataChallengeNo = NULL,$_normalisedFamilyName = NULL,$_normalisedGivenName = NULL,$_normalisedPerferredGivenName = NULL,$_normalisedPreviousFamilyName = NULL,$_notes = NULL,$_otherVerificationDescription = NULL,$_placeOfBirth = NULL,$_potentialDuplicateSearchKey = NULL,$_preferredFamilyFirstNameFirst = NULL,$_preferredFamilyName = NULL,$_preferredGivenName = NULL,$_previousFamilyName = NULL,$_reasonForDeletion = NULL,$_recordStatus = NULL,$_referenceNumber = NULL,$_registeredByOrganisationRef = NULL,$_restrictedUse = NULL,$_schoolAtAge16 = NULL,$_scottishCandidateNumber = NULL,$_secretQuestionId = NULL,$_securityAnswer = NULL,$_senType = NULL,$_sequentialVersionNumber = NULL,$_telephoneNumber = NULL,$_tierLevel = NULL,$_title = NULL,$_uln = NULL,$_verificationType = NULL,$_versionNumber = NULL)
    {
        LRSWsdlClass::__construct(array('AbilityToShare'=>$_abilityToShare,'AtsLearnerPreference'=>$_atsLearnerPreference,'BirthDateVerification'=>$_birthDateVerification,'CountryOfAddress'=>$_countryOfAddress,'CountryOfBirth'=>$_countryOfBirth,'CountryOfDomicle'=>$_countryOfDomicle,'CountryOfLastKnownAddress'=>$_countryOfLastKnownAddress,'CreatedDate'=>$_createdDate,'CreatedViaChannel'=>$_createdViaChannel,'DateOfAddressCapture'=>$_dateOfAddressCapture,'DateOfBirth'=>$_dateOfBirth,'DateOfDeath'=>$_dateOfDeath,'DeathDateVerification'=>$_deathDateVerification,'Deceased'=>$_deceased,'DeceasedDate'=>$_deceasedDate,'DisabilityCode'=>$_disabilityCode,'EmailAddress'=>$_emailAddress,'EthnicityCode'=>$_ethnicityCode,'EthnicityVerification'=>$_ethnicityVerification,'EtlLoadId'=>$_etlLoadId,'FamilyName'=>$_familyName,'FamilyNameAt16'=>$_familyNameAt16,'FamilyNameFirst'=>$_familyNameFirst,'FirstEstablismentId'=>$_firstEstablismentId,'Gender'=>$_gender,'GivenName'=>$_givenName,'InReceiptOfDsa'=>$_inReceiptOfDsa,'Initials'=>$_initials,'LastCompiledLearnerPlanReport'=>$_lastCompiledLearnerPlanReport,'LastCompiledLearnerRecordReport'=>$_lastCompiledLearnerRecordReport,'LastKnownAddressChanged'=>$_lastKnownAddressChanged,'LastKnownAddressCountyOrCity'=>$_lastKnownAddressCountyOrCity,'LastKnownAddressLine1'=>$_lastKnownAddressLine1,'LastKnownAddressLine2'=>$_lastKnownAddressLine2,'LastKnownAddressQualififer'=>$_lastKnownAddressQualififer,'LastKnownAddressTown'=>$_lastKnownAddressTown,'LastKnownGender'=>$_lastKnownGender,'LastKnownPostCode'=>$_lastKnownPostCode,'LastUpdatedAction'=>$_lastUpdatedAction,'LastUpdatedDate'=>$_lastUpdatedDate,'LastUpdatedLrbUsername'=>$_lastUpdatedLrbUsername,'LastUpdatedLrsUsername'=>$_lastUpdatedLrsUsername,'LastUpdatedSearchKey'=>$_lastUpdatedSearchKey,'LastUpdatedViaChannel'=>$_lastUpdatedViaChannel,'LearningDifficultyCode'=>$_learningDifficultyCode,'LinkedUlns'=>($_linkedUlns instanceof LRSStructArrayOfstring)?$_linkedUlns:new LRSStructArrayOfstring($_linkedUlns),'ManualUpdate'=>$_manualUpdate,'MaritalStatus'=>$_maritalStatus,'MaritalStatusVerification'=>$_maritalStatusVerification,'MasterUln'=>$_masterUln,'MiddleOtherName'=>$_middleOtherName,'NameSuffix'=>$_nameSuffix,'NationalInsuranceNumber'=>$_nationalInsuranceNumber,'Nationality'=>$_nationality,'NextDataChallengeNo'=>$_nextDataChallengeNo,'NormalisedFamilyName'=>$_normalisedFamilyName,'NormalisedGivenName'=>$_normalisedGivenName,'NormalisedPerferredGivenName'=>$_normalisedPerferredGivenName,'NormalisedPreviousFamilyName'=>$_normalisedPreviousFamilyName,'Notes'=>$_notes,'OtherVerificationDescription'=>$_otherVerificationDescription,'PlaceOfBirth'=>$_placeOfBirth,'PotentialDuplicateSearchKey'=>$_potentialDuplicateSearchKey,'PreferredFamilyFirstNameFirst'=>$_preferredFamilyFirstNameFirst,'PreferredFamilyName'=>$_preferredFamilyName,'PreferredGivenName'=>$_preferredGivenName,'PreviousFamilyName'=>$_previousFamilyName,'ReasonForDeletion'=>$_reasonForDeletion,'RecordStatus'=>$_recordStatus,'ReferenceNumber'=>$_referenceNumber,'RegisteredByOrganisationRef'=>$_registeredByOrganisationRef,'RestrictedUse'=>$_restrictedUse,'SchoolAtAge16'=>$_schoolAtAge16,'ScottishCandidateNumber'=>$_scottishCandidateNumber,'SecretQuestionId'=>$_secretQuestionId,'SecurityAnswer'=>$_securityAnswer,'SenType'=>$_senType,'SequentialVersionNumber'=>$_sequentialVersionNumber,'TelephoneNumber'=>$_telephoneNumber,'TierLevel'=>$_tierLevel,'Title'=>$_title,'Uln'=>$_uln,'VerificationType'=>$_verificationType,'VersionNumber'=>$_versionNumber),false);
    }
    /**
     * Get AbilityToShare value
     * @return int|null
     */
    public function getAbilityToShare()
    {
        return $this->AbilityToShare;
    }
    /**
     * Set AbilityToShare value
     * @param int $_abilityToShare the AbilityToShare
     * @return int
     */
    public function setAbilityToShare($_abilityToShare)
    {
        return ($this->AbilityToShare = $_abilityToShare);
    }
    /**
     * Get AtsLearnerPreference value
     * @return int|null
     */
    public function getAtsLearnerPreference()
    {
        return $this->AtsLearnerPreference;
    }
    /**
     * Set AtsLearnerPreference value
     * @param int $_atsLearnerPreference the AtsLearnerPreference
     * @return int
     */
    public function setAtsLearnerPreference($_atsLearnerPreference)
    {
        return ($this->AtsLearnerPreference = $_atsLearnerPreference);
    }
    /**
     * Get BirthDateVerification value
     * @return int|null
     */
    public function getBirthDateVerification()
    {
        return $this->BirthDateVerification;
    }
    /**
     * Set BirthDateVerification value
     * @param int $_birthDateVerification the BirthDateVerification
     * @return int
     */
    public function setBirthDateVerification($_birthDateVerification)
    {
        return ($this->BirthDateVerification = $_birthDateVerification);
    }
    /**
     * Get CountryOfAddress value
     * @return string|null
     */
    public function getCountryOfAddress()
    {
        return $this->CountryOfAddress;
    }
    /**
     * Set CountryOfAddress value
     * @param string $_countryOfAddress the CountryOfAddress
     * @return string
     */
    public function setCountryOfAddress($_countryOfAddress)
    {
        return ($this->CountryOfAddress = $_countryOfAddress);
    }
    /**
     * Get CountryOfBirth value
     * @return string|null
     */
    public function getCountryOfBirth()
    {
        return $this->CountryOfBirth;
    }
    /**
     * Set CountryOfBirth value
     * @param string $_countryOfBirth the CountryOfBirth
     * @return string
     */
    public function setCountryOfBirth($_countryOfBirth)
    {
        return ($this->CountryOfBirth = $_countryOfBirth);
    }
    /**
     * Get CountryOfDomicle value
     * @return string|null
     */
    public function getCountryOfDomicle()
    {
        return $this->CountryOfDomicle;
    }
    /**
     * Set CountryOfDomicle value
     * @param string $_countryOfDomicle the CountryOfDomicle
     * @return string
     */
    public function setCountryOfDomicle($_countryOfDomicle)
    {
        return ($this->CountryOfDomicle = $_countryOfDomicle);
    }
    /**
     * Get CountryOfLastKnownAddress value
     * @return string|null
     */
    public function getCountryOfLastKnownAddress()
    {
        return $this->CountryOfLastKnownAddress;
    }
    /**
     * Set CountryOfLastKnownAddress value
     * @param string $_countryOfLastKnownAddress the CountryOfLastKnownAddress
     * @return string
     */
    public function setCountryOfLastKnownAddress($_countryOfLastKnownAddress)
    {
        return ($this->CountryOfLastKnownAddress = $_countryOfLastKnownAddress);
    }
    /**
     * Get CreatedDate value
     * @return dateTime|null
     */
    public function getCreatedDate()
    {
        return $this->CreatedDate;
    }
    /**
     * Set CreatedDate value
     * @param dateTime $_createdDate the CreatedDate
     * @return dateTime
     */
    public function setCreatedDate($_createdDate)
    {
        return ($this->CreatedDate = $_createdDate);
    }
    /**
     * Get CreatedViaChannel value
     * @return int|null
     */
    public function getCreatedViaChannel()
    {
        return $this->CreatedViaChannel;
    }
    /**
     * Set CreatedViaChannel value
     * @param int $_createdViaChannel the CreatedViaChannel
     * @return int
     */
    public function setCreatedViaChannel($_createdViaChannel)
    {
        return ($this->CreatedViaChannel = $_createdViaChannel);
    }
    /**
     * Get DateOfAddressCapture value
     * @return dateTime|null
     */
    public function getDateOfAddressCapture()
    {
        return $this->DateOfAddressCapture;
    }
    /**
     * Set DateOfAddressCapture value
     * @param dateTime $_dateOfAddressCapture the DateOfAddressCapture
     * @return dateTime
     */
    public function setDateOfAddressCapture($_dateOfAddressCapture)
    {
        return ($this->DateOfAddressCapture = $_dateOfAddressCapture);
    }
    /**
     * Get DateOfBirth value
     * @return dateTime|null
     */
    public function getDateOfBirth()
    {
        return $this->DateOfBirth;
    }
    /**
     * Set DateOfBirth value
     * @param dateTime $_dateOfBirth the DateOfBirth
     * @return dateTime
     */
    public function setDateOfBirth($_dateOfBirth)
    {
        return ($this->DateOfBirth = $_dateOfBirth);
    }
    /**
     * Get DateOfDeath value
     * @return dateTime|null
     */
    public function getDateOfDeath()
    {
        return $this->DateOfDeath;
    }
    /**
     * Set DateOfDeath value
     * @param dateTime $_dateOfDeath the DateOfDeath
     * @return dateTime
     */
    public function setDateOfDeath($_dateOfDeath)
    {
        return ($this->DateOfDeath = $_dateOfDeath);
    }
    /**
     * Get DeathDateVerification value
     * @return int|null
     */
    public function getDeathDateVerification()
    {
        return $this->DeathDateVerification;
    }
    /**
     * Set DeathDateVerification value
     * @param int $_deathDateVerification the DeathDateVerification
     * @return int
     */
    public function setDeathDateVerification($_deathDateVerification)
    {
        return ($this->DeathDateVerification = $_deathDateVerification);
    }
    /**
     * Get Deceased value
     * @return int|null
     */
    public function getDeceased()
    {
        return $this->Deceased;
    }
    /**
     * Set Deceased value
     * @param int $_deceased the Deceased
     * @return int
     */
    public function setDeceased($_deceased)
    {
        return ($this->Deceased = $_deceased);
    }
    /**
     * Get DeceasedDate value
     * @return dateTime|null
     */
    public function getDeceasedDate()
    {
        return $this->DeceasedDate;
    }
    /**
     * Set DeceasedDate value
     * @param dateTime $_deceasedDate the DeceasedDate
     * @return dateTime
     */
    public function setDeceasedDate($_deceasedDate)
    {
        return ($this->DeceasedDate = $_deceasedDate);
    }
    /**
     * Get DisabilityCode value
     * @return int|null
     */
    public function getDisabilityCode()
    {
        return $this->DisabilityCode;
    }
    /**
     * Set DisabilityCode value
     * @param int $_disabilityCode the DisabilityCode
     * @return int
     */
    public function setDisabilityCode($_disabilityCode)
    {
        return ($this->DisabilityCode = $_disabilityCode);
    }
    /**
     * Get EmailAddress value
     * @return string|null
     */
    public function getEmailAddress()
    {
        return $this->EmailAddress;
    }
    /**
     * Set EmailAddress value
     * @param string $_emailAddress the EmailAddress
     * @return string
     */
    public function setEmailAddress($_emailAddress)
    {
        return ($this->EmailAddress = $_emailAddress);
    }
    /**
     * Get EthnicityCode value
     * @return string|null
     */
    public function getEthnicityCode()
    {
        return $this->EthnicityCode;
    }
    /**
     * Set EthnicityCode value
     * @param string $_ethnicityCode the EthnicityCode
     * @return string
     */
    public function setEthnicityCode($_ethnicityCode)
    {
        return ($this->EthnicityCode = $_ethnicityCode);
    }
    /**
     * Get EthnicityVerification value
     * @return int|null
     */
    public function getEthnicityVerification()
    {
        return $this->EthnicityVerification;
    }
    /**
     * Set EthnicityVerification value
     * @param int $_ethnicityVerification the EthnicityVerification
     * @return int
     */
    public function setEthnicityVerification($_ethnicityVerification)
    {
        return ($this->EthnicityVerification = $_ethnicityVerification);
    }
    /**
     * Get EtlLoadId value
     * @return int|null
     */
    public function getEtlLoadId()
    {
        return $this->EtlLoadId;
    }
    /**
     * Set EtlLoadId value
     * @param int $_etlLoadId the EtlLoadId
     * @return int
     */
    public function setEtlLoadId($_etlLoadId)
    {
        return ($this->EtlLoadId = $_etlLoadId);
    }
    /**
     * Get FamilyName value
     * @return string|null
     */
    public function getFamilyName()
    {
        return $this->FamilyName;
    }
    /**
     * Set FamilyName value
     * @param string $_familyName the FamilyName
     * @return string
     */
    public function setFamilyName($_familyName)
    {
        return ($this->FamilyName = $_familyName);
    }
    /**
     * Get FamilyNameAt16 value
     * @return string|null
     */
    public function getFamilyNameAt16()
    {
        return $this->FamilyNameAt16;
    }
    /**
     * Set FamilyNameAt16 value
     * @param string $_familyNameAt16 the FamilyNameAt16
     * @return string
     */
    public function setFamilyNameAt16($_familyNameAt16)
    {
        return ($this->FamilyNameAt16 = $_familyNameAt16);
    }
    /**
     * Get FamilyNameFirst value
     * @return boolean|null
     */
    public function getFamilyNameFirst()
    {
        return $this->FamilyNameFirst;
    }
    /**
     * Set FamilyNameFirst value
     * @param boolean $_familyNameFirst the FamilyNameFirst
     * @return boolean
     */
    public function setFamilyNameFirst($_familyNameFirst)
    {
        return ($this->FamilyNameFirst = $_familyNameFirst);
    }
    /**
     * Get FirstEstablismentId value
     * @return int|null
     */
    public function getFirstEstablismentId()
    {
        return $this->FirstEstablismentId;
    }
    /**
     * Set FirstEstablismentId value
     * @param int $_firstEstablismentId the FirstEstablismentId
     * @return int
     */
    public function setFirstEstablismentId($_firstEstablismentId)
    {
        return ($this->FirstEstablismentId = $_firstEstablismentId);
    }
    /**
     * Get Gender value
     * @return int|null
     */
    public function getGender()
    {
        return $this->Gender;
    }
    /**
     * Set Gender value
     * @param int $_gender the Gender
     * @return int
     */
    public function setGender($_gender)
    {
        return ($this->Gender = $_gender);
    }
    /**
     * Get GivenName value
     * @return string|null
     */
    public function getGivenName()
    {
        return $this->GivenName;
    }
    /**
     * Set GivenName value
     * @param string $_givenName the GivenName
     * @return string
     */
    public function setGivenName($_givenName)
    {
        return ($this->GivenName = $_givenName);
    }
    /**
     * Get InReceiptOfDsa value
     * @return int|null
     */
    public function getInReceiptOfDsa()
    {
        return $this->InReceiptOfDsa;
    }
    /**
     * Set InReceiptOfDsa value
     * @param int $_inReceiptOfDsa the InReceiptOfDsa
     * @return int
     */
    public function setInReceiptOfDsa($_inReceiptOfDsa)
    {
        return ($this->InReceiptOfDsa = $_inReceiptOfDsa);
    }
    /**
     * Get Initials value
     * @return string|null
     */
    public function getInitials()
    {
        return $this->Initials;
    }
    /**
     * Set Initials value
     * @param string $_initials the Initials
     * @return string
     */
    public function setInitials($_initials)
    {
        return ($this->Initials = $_initials);
    }
    /**
     * Get LastCompiledLearnerPlanReport value
     * @return dateTime|null
     */
    public function getLastCompiledLearnerPlanReport()
    {
        return $this->LastCompiledLearnerPlanReport;
    }
    /**
     * Set LastCompiledLearnerPlanReport value
     * @param dateTime $_lastCompiledLearnerPlanReport the LastCompiledLearnerPlanReport
     * @return dateTime
     */
    public function setLastCompiledLearnerPlanReport($_lastCompiledLearnerPlanReport)
    {
        return ($this->LastCompiledLearnerPlanReport = $_lastCompiledLearnerPlanReport);
    }
    /**
     * Get LastCompiledLearnerRecordReport value
     * @return dateTime|null
     */
    public function getLastCompiledLearnerRecordReport()
    {
        return $this->LastCompiledLearnerRecordReport;
    }
    /**
     * Set LastCompiledLearnerRecordReport value
     * @param dateTime $_lastCompiledLearnerRecordReport the LastCompiledLearnerRecordReport
     * @return dateTime
     */
    public function setLastCompiledLearnerRecordReport($_lastCompiledLearnerRecordReport)
    {
        return ($this->LastCompiledLearnerRecordReport = $_lastCompiledLearnerRecordReport);
    }
    /**
     * Get LastKnownAddressChanged value
     * @return boolean|null
     */
    public function getLastKnownAddressChanged()
    {
        return $this->LastKnownAddressChanged;
    }
    /**
     * Set LastKnownAddressChanged value
     * @param boolean $_lastKnownAddressChanged the LastKnownAddressChanged
     * @return boolean
     */
    public function setLastKnownAddressChanged($_lastKnownAddressChanged)
    {
        return ($this->LastKnownAddressChanged = $_lastKnownAddressChanged);
    }
    /**
     * Get LastKnownAddressCountyOrCity value
     * @return string|null
     */
    public function getLastKnownAddressCountyOrCity()
    {
        return $this->LastKnownAddressCountyOrCity;
    }
    /**
     * Set LastKnownAddressCountyOrCity value
     * @param string $_lastKnownAddressCountyOrCity the LastKnownAddressCountyOrCity
     * @return string
     */
    public function setLastKnownAddressCountyOrCity($_lastKnownAddressCountyOrCity)
    {
        return ($this->LastKnownAddressCountyOrCity = $_lastKnownAddressCountyOrCity);
    }
    /**
     * Get LastKnownAddressLine1 value
     * @return string|null
     */
    public function getLastKnownAddressLine1()
    {
        return $this->LastKnownAddressLine1;
    }
    /**
     * Set LastKnownAddressLine1 value
     * @param string $_lastKnownAddressLine1 the LastKnownAddressLine1
     * @return string
     */
    public function setLastKnownAddressLine1($_lastKnownAddressLine1)
    {
        return ($this->LastKnownAddressLine1 = $_lastKnownAddressLine1);
    }
    /**
     * Get LastKnownAddressLine2 value
     * @return string|null
     */
    public function getLastKnownAddressLine2()
    {
        return $this->LastKnownAddressLine2;
    }
    /**
     * Set LastKnownAddressLine2 value
     * @param string $_lastKnownAddressLine2 the LastKnownAddressLine2
     * @return string
     */
    public function setLastKnownAddressLine2($_lastKnownAddressLine2)
    {
        return ($this->LastKnownAddressLine2 = $_lastKnownAddressLine2);
    }
    /**
     * Get LastKnownAddressQualififer value
     * @return int|null
     */
    public function getLastKnownAddressQualififer()
    {
        return $this->LastKnownAddressQualififer;
    }
    /**
     * Set LastKnownAddressQualififer value
     * @param int $_lastKnownAddressQualififer the LastKnownAddressQualififer
     * @return int
     */
    public function setLastKnownAddressQualififer($_lastKnownAddressQualififer)
    {
        return ($this->LastKnownAddressQualififer = $_lastKnownAddressQualififer);
    }
    /**
     * Get LastKnownAddressTown value
     * @return string|null
     */
    public function getLastKnownAddressTown()
    {
        return $this->LastKnownAddressTown;
    }
    /**
     * Set LastKnownAddressTown value
     * @param string $_lastKnownAddressTown the LastKnownAddressTown
     * @return string
     */
    public function setLastKnownAddressTown($_lastKnownAddressTown)
    {
        return ($this->LastKnownAddressTown = $_lastKnownAddressTown);
    }
    /**
     * Get LastKnownGender value
     * @return int|null
     */
    public function getLastKnownGender()
    {
        return $this->LastKnownGender;
    }
    /**
     * Set LastKnownGender value
     * @param int $_lastKnownGender the LastKnownGender
     * @return int
     */
    public function setLastKnownGender($_lastKnownGender)
    {
        return ($this->LastKnownGender = $_lastKnownGender);
    }
    /**
     * Get LastKnownPostCode value
     * @return string|null
     */
    public function getLastKnownPostCode()
    {
        return $this->LastKnownPostCode;
    }
    /**
     * Set LastKnownPostCode value
     * @param string $_lastKnownPostCode the LastKnownPostCode
     * @return string
     */
    public function setLastKnownPostCode($_lastKnownPostCode)
    {
        return ($this->LastKnownPostCode = $_lastKnownPostCode);
    }
    /**
     * Get LastUpdatedAction value
     * @return string|null
     */
    public function getLastUpdatedAction()
    {
        return $this->LastUpdatedAction;
    }
    /**
     * Set LastUpdatedAction value
     * @param string $_lastUpdatedAction the LastUpdatedAction
     * @return string
     */
    public function setLastUpdatedAction($_lastUpdatedAction)
    {
        return ($this->LastUpdatedAction = $_lastUpdatedAction);
    }
    /**
     * Get LastUpdatedDate value
     * @return dateTime|null
     */
    public function getLastUpdatedDate()
    {
        return $this->LastUpdatedDate;
    }
    /**
     * Set LastUpdatedDate value
     * @param dateTime $_lastUpdatedDate the LastUpdatedDate
     * @return dateTime
     */
    public function setLastUpdatedDate($_lastUpdatedDate)
    {
        return ($this->LastUpdatedDate = $_lastUpdatedDate);
    }
    /**
     * Get LastUpdatedLrbUsername value
     * @return string|null
     */
    public function getLastUpdatedLrbUsername()
    {
        return $this->LastUpdatedLrbUsername;
    }
    /**
     * Set LastUpdatedLrbUsername value
     * @param string $_lastUpdatedLrbUsername the LastUpdatedLrbUsername
     * @return string
     */
    public function setLastUpdatedLrbUsername($_lastUpdatedLrbUsername)
    {
        return ($this->LastUpdatedLrbUsername = $_lastUpdatedLrbUsername);
    }
    /**
     * Get LastUpdatedLrsUsername value
     * @return string|null
     */
    public function getLastUpdatedLrsUsername()
    {
        return $this->LastUpdatedLrsUsername;
    }
    /**
     * Set LastUpdatedLrsUsername value
     * @param string $_lastUpdatedLrsUsername the LastUpdatedLrsUsername
     * @return string
     */
    public function setLastUpdatedLrsUsername($_lastUpdatedLrsUsername)
    {
        return ($this->LastUpdatedLrsUsername = $_lastUpdatedLrsUsername);
    }
    /**
     * Get LastUpdatedSearchKey value
     * @return int|null
     */
    public function getLastUpdatedSearchKey()
    {
        return $this->LastUpdatedSearchKey;
    }
    /**
     * Set LastUpdatedSearchKey value
     * @param int $_lastUpdatedSearchKey the LastUpdatedSearchKey
     * @return int
     */
    public function setLastUpdatedSearchKey($_lastUpdatedSearchKey)
    {
        return ($this->LastUpdatedSearchKey = $_lastUpdatedSearchKey);
    }
    /**
     * Get LastUpdatedViaChannel value
     * @return int|null
     */
    public function getLastUpdatedViaChannel()
    {
        return $this->LastUpdatedViaChannel;
    }
    /**
     * Set LastUpdatedViaChannel value
     * @param int $_lastUpdatedViaChannel the LastUpdatedViaChannel
     * @return int
     */
    public function setLastUpdatedViaChannel($_lastUpdatedViaChannel)
    {
        return ($this->LastUpdatedViaChannel = $_lastUpdatedViaChannel);
    }
    /**
     * Get LearningDifficultyCode value
     * @return int|null
     */
    public function getLearningDifficultyCode()
    {
        return $this->LearningDifficultyCode;
    }
    /**
     * Set LearningDifficultyCode value
     * @param int $_learningDifficultyCode the LearningDifficultyCode
     * @return int
     */
    public function setLearningDifficultyCode($_learningDifficultyCode)
    {
        return ($this->LearningDifficultyCode = $_learningDifficultyCode);
    }
    /**
     * Get LinkedUlns value
     * @return LRSStructArrayOfstring|null
     */
    public function getLinkedUlns()
    {
        return $this->LinkedUlns;
    }
    /**
     * Set LinkedUlns value
     * @param LRSStructArrayOfstring $_linkedUlns the LinkedUlns
     * @return LRSStructArrayOfstring
     */
    public function setLinkedUlns($_linkedUlns)
    {
        return ($this->LinkedUlns = $_linkedUlns);
    }
    /**
     * Get ManualUpdate value
     * @return boolean|null
     */
    public function getManualUpdate()
    {
        return $this->ManualUpdate;
    }
    /**
     * Set ManualUpdate value
     * @param boolean $_manualUpdate the ManualUpdate
     * @return boolean
     */
    public function setManualUpdate($_manualUpdate)
    {
        return ($this->ManualUpdate = $_manualUpdate);
    }
    /**
     * Get MaritalStatus value
     * @return int|null
     */
    public function getMaritalStatus()
    {
        return $this->MaritalStatus;
    }
    /**
     * Set MaritalStatus value
     * @param int $_maritalStatus the MaritalStatus
     * @return int
     */
    public function setMaritalStatus($_maritalStatus)
    {
        return ($this->MaritalStatus = $_maritalStatus);
    }
    /**
     * Get MaritalStatusVerification value
     * @return int|null
     */
    public function getMaritalStatusVerification()
    {
        return $this->MaritalStatusVerification;
    }
    /**
     * Set MaritalStatusVerification value
     * @param int $_maritalStatusVerification the MaritalStatusVerification
     * @return int
     */
    public function setMaritalStatusVerification($_maritalStatusVerification)
    {
        return ($this->MaritalStatusVerification = $_maritalStatusVerification);
    }
    /**
     * Get MasterUln value
     * @return string|null
     */
    public function getMasterUln()
    {
        return $this->MasterUln;
    }
    /**
     * Set MasterUln value
     * @param string $_masterUln the MasterUln
     * @return string
     */
    public function setMasterUln($_masterUln)
    {
        return ($this->MasterUln = $_masterUln);
    }
    /**
     * Get MiddleOtherName value
     * @return string|null
     */
    public function getMiddleOtherName()
    {
        return $this->MiddleOtherName;
    }
    /**
     * Set MiddleOtherName value
     * @param string $_middleOtherName the MiddleOtherName
     * @return string
     */
    public function setMiddleOtherName($_middleOtherName)
    {
        return ($this->MiddleOtherName = $_middleOtherName);
    }
    /**
     * Get NameSuffix value
     * @return string|null
     */
    public function getNameSuffix()
    {
        return $this->NameSuffix;
    }
    /**
     * Set NameSuffix value
     * @param string $_nameSuffix the NameSuffix
     * @return string
     */
    public function setNameSuffix($_nameSuffix)
    {
        return ($this->NameSuffix = $_nameSuffix);
    }
    /**
     * Get NationalInsuranceNumber value
     * @return string|null
     */
    public function getNationalInsuranceNumber()
    {
        return $this->NationalInsuranceNumber;
    }
    /**
     * Set NationalInsuranceNumber value
     * @param string $_nationalInsuranceNumber the NationalInsuranceNumber
     * @return string
     */
    public function setNationalInsuranceNumber($_nationalInsuranceNumber)
    {
        return ($this->NationalInsuranceNumber = $_nationalInsuranceNumber);
    }
    /**
     * Get Nationality value
     * @return string|null
     */
    public function getNationality()
    {
        return $this->Nationality;
    }
    /**
     * Set Nationality value
     * @param string $_nationality the Nationality
     * @return string
     */
    public function setNationality($_nationality)
    {
        return ($this->Nationality = $_nationality);
    }
    /**
     * Get NextDataChallengeNo value
     * @return int|null
     */
    public function getNextDataChallengeNo()
    {
        return $this->NextDataChallengeNo;
    }
    /**
     * Set NextDataChallengeNo value
     * @param int $_nextDataChallengeNo the NextDataChallengeNo
     * @return int
     */
    public function setNextDataChallengeNo($_nextDataChallengeNo)
    {
        return ($this->NextDataChallengeNo = $_nextDataChallengeNo);
    }
    /**
     * Get NormalisedFamilyName value
     * @return string|null
     */
    public function getNormalisedFamilyName()
    {
        return $this->NormalisedFamilyName;
    }
    /**
     * Set NormalisedFamilyName value
     * @param string $_normalisedFamilyName the NormalisedFamilyName
     * @return string
     */
    public function setNormalisedFamilyName($_normalisedFamilyName)
    {
        return ($this->NormalisedFamilyName = $_normalisedFamilyName);
    }
    /**
     * Get NormalisedGivenName value
     * @return string|null
     */
    public function getNormalisedGivenName()
    {
        return $this->NormalisedGivenName;
    }
    /**
     * Set NormalisedGivenName value
     * @param string $_normalisedGivenName the NormalisedGivenName
     * @return string
     */
    public function setNormalisedGivenName($_normalisedGivenName)
    {
        return ($this->NormalisedGivenName = $_normalisedGivenName);
    }
    /**
     * Get NormalisedPerferredGivenName value
     * @return string|null
     */
    public function getNormalisedPerferredGivenName()
    {
        return $this->NormalisedPerferredGivenName;
    }
    /**
     * Set NormalisedPerferredGivenName value
     * @param string $_normalisedPerferredGivenName the NormalisedPerferredGivenName
     * @return string
     */
    public function setNormalisedPerferredGivenName($_normalisedPerferredGivenName)
    {
        return ($this->NormalisedPerferredGivenName = $_normalisedPerferredGivenName);
    }
    /**
     * Get NormalisedPreviousFamilyName value
     * @return string|null
     */
    public function getNormalisedPreviousFamilyName()
    {
        return $this->NormalisedPreviousFamilyName;
    }
    /**
     * Set NormalisedPreviousFamilyName value
     * @param string $_normalisedPreviousFamilyName the NormalisedPreviousFamilyName
     * @return string
     */
    public function setNormalisedPreviousFamilyName($_normalisedPreviousFamilyName)
    {
        return ($this->NormalisedPreviousFamilyName = $_normalisedPreviousFamilyName);
    }
    /**
     * Get Notes value
     * @return string|null
     */
    public function getNotes()
    {
        return $this->Notes;
    }
    /**
     * Set Notes value
     * @param string $_notes the Notes
     * @return string
     */
    public function setNotes($_notes)
    {
        return ($this->Notes = $_notes);
    }
    /**
     * Get OtherVerificationDescription value
     * @return string|null
     */
    public function getOtherVerificationDescription()
    {
        return $this->OtherVerificationDescription;
    }
    /**
     * Set OtherVerificationDescription value
     * @param string $_otherVerificationDescription the OtherVerificationDescription
     * @return string
     */
    public function setOtherVerificationDescription($_otherVerificationDescription)
    {
        return ($this->OtherVerificationDescription = $_otherVerificationDescription);
    }
    /**
     * Get PlaceOfBirth value
     * @return string|null
     */
    public function getPlaceOfBirth()
    {
        return $this->PlaceOfBirth;
    }
    /**
     * Set PlaceOfBirth value
     * @param string $_placeOfBirth the PlaceOfBirth
     * @return string
     */
    public function setPlaceOfBirth($_placeOfBirth)
    {
        return ($this->PlaceOfBirth = $_placeOfBirth);
    }
    /**
     * Get PotentialDuplicateSearchKey value
     * @return int|null
     */
    public function getPotentialDuplicateSearchKey()
    {
        return $this->PotentialDuplicateSearchKey;
    }
    /**
     * Set PotentialDuplicateSearchKey value
     * @param int $_potentialDuplicateSearchKey the PotentialDuplicateSearchKey
     * @return int
     */
    public function setPotentialDuplicateSearchKey($_potentialDuplicateSearchKey)
    {
        return ($this->PotentialDuplicateSearchKey = $_potentialDuplicateSearchKey);
    }
    /**
     * Get PreferredFamilyFirstNameFirst value
     * @return boolean|null
     */
    public function getPreferredFamilyFirstNameFirst()
    {
        return $this->PreferredFamilyFirstNameFirst;
    }
    /**
     * Set PreferredFamilyFirstNameFirst value
     * @param boolean $_preferredFamilyFirstNameFirst the PreferredFamilyFirstNameFirst
     * @return boolean
     */
    public function setPreferredFamilyFirstNameFirst($_preferredFamilyFirstNameFirst)
    {
        return ($this->PreferredFamilyFirstNameFirst = $_preferredFamilyFirstNameFirst);
    }
    /**
     * Get PreferredFamilyName value
     * @return string|null
     */
    public function getPreferredFamilyName()
    {
        return $this->PreferredFamilyName;
    }
    /**
     * Set PreferredFamilyName value
     * @param string $_preferredFamilyName the PreferredFamilyName
     * @return string
     */
    public function setPreferredFamilyName($_preferredFamilyName)
    {
        return ($this->PreferredFamilyName = $_preferredFamilyName);
    }
    /**
     * Get PreferredGivenName value
     * @return string|null
     */
    public function getPreferredGivenName()
    {
        return $this->PreferredGivenName;
    }
    /**
     * Set PreferredGivenName value
     * @param string $_preferredGivenName the PreferredGivenName
     * @return string
     */
    public function setPreferredGivenName($_preferredGivenName)
    {
        return ($this->PreferredGivenName = $_preferredGivenName);
    }
    /**
     * Get PreviousFamilyName value
     * @return string|null
     */
    public function getPreviousFamilyName()
    {
        return $this->PreviousFamilyName;
    }
    /**
     * Set PreviousFamilyName value
     * @param string $_previousFamilyName the PreviousFamilyName
     * @return string
     */
    public function setPreviousFamilyName($_previousFamilyName)
    {
        return ($this->PreviousFamilyName = $_previousFamilyName);
    }
    /**
     * Get ReasonForDeletion value
     * @return string|null
     */
    public function getReasonForDeletion()
    {
        return $this->ReasonForDeletion;
    }
    /**
     * Set ReasonForDeletion value
     * @param string $_reasonForDeletion the ReasonForDeletion
     * @return string
     */
    public function setReasonForDeletion($_reasonForDeletion)
    {
        return ($this->ReasonForDeletion = $_reasonForDeletion);
    }
    /**
     * Get RecordStatus value
     * @return int|null
     */
    public function getRecordStatus()
    {
        return $this->RecordStatus;
    }
    /**
     * Set RecordStatus value
     * @param int $_recordStatus the RecordStatus
     * @return int
     */
    public function setRecordStatus($_recordStatus)
    {
        return ($this->RecordStatus = $_recordStatus);
    }
    /**
     * Get ReferenceNumber value
     * @return string|null
     */
    public function getReferenceNumber()
    {
        return $this->ReferenceNumber;
    }
    /**
     * Set ReferenceNumber value
     * @param string $_referenceNumber the ReferenceNumber
     * @return string
     */
    public function setReferenceNumber($_referenceNumber)
    {
        return ($this->ReferenceNumber = $_referenceNumber);
    }
    /**
     * Get RegisteredByOrganisationRef value
     * @return string|null
     */
    public function getRegisteredByOrganisationRef()
    {
        return $this->RegisteredByOrganisationRef;
    }
    /**
     * Set RegisteredByOrganisationRef value
     * @param string $_registeredByOrganisationRef the RegisteredByOrganisationRef
     * @return string
     */
    public function setRegisteredByOrganisationRef($_registeredByOrganisationRef)
    {
        return ($this->RegisteredByOrganisationRef = $_registeredByOrganisationRef);
    }
    /**
     * Get RestrictedUse value
     * @return boolean|null
     */
    public function getRestrictedUse()
    {
        return $this->RestrictedUse;
    }
    /**
     * Set RestrictedUse value
     * @param boolean $_restrictedUse the RestrictedUse
     * @return boolean
     */
    public function setRestrictedUse($_restrictedUse)
    {
        return ($this->RestrictedUse = $_restrictedUse);
    }
    /**
     * Get SchoolAtAge16 value
     * @return string|null
     */
    public function getSchoolAtAge16()
    {
        return $this->SchoolAtAge16;
    }
    /**
     * Set SchoolAtAge16 value
     * @param string $_schoolAtAge16 the SchoolAtAge16
     * @return string
     */
    public function setSchoolAtAge16($_schoolAtAge16)
    {
        return ($this->SchoolAtAge16 = $_schoolAtAge16);
    }
    /**
     * Get ScottishCandidateNumber value
     * @return string|null
     */
    public function getScottishCandidateNumber()
    {
        return $this->ScottishCandidateNumber;
    }
    /**
     * Set ScottishCandidateNumber value
     * @param string $_scottishCandidateNumber the ScottishCandidateNumber
     * @return string
     */
    public function setScottishCandidateNumber($_scottishCandidateNumber)
    {
        return ($this->ScottishCandidateNumber = $_scottishCandidateNumber);
    }
    /**
     * Get SecretQuestionId value
     * @return int|null
     */
    public function getSecretQuestionId()
    {
        return $this->SecretQuestionId;
    }
    /**
     * Set SecretQuestionId value
     * @param int $_secretQuestionId the SecretQuestionId
     * @return int
     */
    public function setSecretQuestionId($_secretQuestionId)
    {
        return ($this->SecretQuestionId = $_secretQuestionId);
    }
    /**
     * Get SecurityAnswer value
     * @return string|null
     */
    public function getSecurityAnswer()
    {
        return $this->SecurityAnswer;
    }
    /**
     * Set SecurityAnswer value
     * @param string $_securityAnswer the SecurityAnswer
     * @return string
     */
    public function setSecurityAnswer($_securityAnswer)
    {
        return ($this->SecurityAnswer = $_securityAnswer);
    }
    /**
     * Get SenType value
     * @return int|null
     */
    public function getSenType()
    {
        return $this->SenType;
    }
    /**
     * Set SenType value
     * @param int $_senType the SenType
     * @return int
     */
    public function setSenType($_senType)
    {
        return ($this->SenType = $_senType);
    }
    /**
     * Get SequentialVersionNumber value
     * @return int|null
     */
    public function getSequentialVersionNumber()
    {
        return $this->SequentialVersionNumber;
    }
    /**
     * Set SequentialVersionNumber value
     * @param int $_sequentialVersionNumber the SequentialVersionNumber
     * @return int
     */
    public function setSequentialVersionNumber($_sequentialVersionNumber)
    {
        return ($this->SequentialVersionNumber = $_sequentialVersionNumber);
    }
    /**
     * Get TelephoneNumber value
     * @return string|null
     */
    public function getTelephoneNumber()
    {
        return $this->TelephoneNumber;
    }
    /**
     * Set TelephoneNumber value
     * @param string $_telephoneNumber the TelephoneNumber
     * @return string
     */
    public function setTelephoneNumber($_telephoneNumber)
    {
        return ($this->TelephoneNumber = $_telephoneNumber);
    }
    /**
     * Get TierLevel value
     * @return int|null
     */
    public function getTierLevel()
    {
        return $this->TierLevel;
    }
    /**
     * Set TierLevel value
     * @param int $_tierLevel the TierLevel
     * @return int
     */
    public function setTierLevel($_tierLevel)
    {
        return ($this->TierLevel = $_tierLevel);
    }
    /**
     * Get Title value
     * @return string|null
     */
    public function getTitle()
    {
        return $this->Title;
    }
    /**
     * Set Title value
     * @param string $_title the Title
     * @return string
     */
    public function setTitle($_title)
    {
        return ($this->Title = $_title);
    }
    /**
     * Get Uln value
     * @return string|null
     */
    public function getUln()
    {
        return $this->Uln;
    }
    /**
     * Set Uln value
     * @param string $_uln the Uln
     * @return string
     */
    public function setUln($_uln)
    {
        return ($this->Uln = $_uln);
    }
    /**
     * Get VerificationType value
     * @return int|null
     */
    public function getVerificationType()
    {
        return $this->VerificationType;
    }
    /**
     * Set VerificationType value
     * @param int $_verificationType the VerificationType
     * @return int
     */
    public function setVerificationType($_verificationType)
    {
        return ($this->VerificationType = $_verificationType);
    }
    /**
     * Get VersionNumber value
     * @return int|null
     */
    public function getVersionNumber()
    {
        return $this->VersionNumber;
    }
    /**
     * Set VersionNumber value
     * @param int $_versionNumber the VersionNumber
     * @return int
     */
    public function setVersionNumber($_versionNumber)
    {
        return ($this->VersionNumber = $_versionNumber);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructLearner
     */
    public static function __set_state(array $_array,$_className = __CLASS__)
    {
        return parent::__set_state($_array,$_className);
    }
    /**
     * Method returning the class name
     * @return string __CLASS__
     */
    public function __toString()
    {
        return __CLASS__;
    }
}
