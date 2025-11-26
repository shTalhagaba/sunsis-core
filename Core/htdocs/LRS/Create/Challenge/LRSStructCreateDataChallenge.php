<?php
/**
 * File for class LRSStructCreateDataChallenge
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructCreateDataChallenge originally named CreateDataChallenge
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructCreateDataChallenge extends LRSWsdlClass
{
    /**
     * The invokingOrganisation
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var LRSStructInvokingOrganisation
     */
    public $invokingOrganisation;
    /**
     * The userType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $userType;
    /**
     * The vendorId
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $vendorId;
    /**
     * The language
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $language;
    /**
     * The challengeType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $challengeType;
    /**
     * The uln
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $uln;
    /**
     * The givenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $givenName;
    /**
     * The familyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $familyName;
    /**
     * The dateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var dateTime
     */
    public $dateOfBirth;
    /**
     * The gender
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $gender;
    /**
     * The contactName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $contactName;
    /**
     * The telephone
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $telephone;
    /**
     * The emailAddress
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $emailAddress;
    /**
     * The additionalInformation
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $additionalInformation;
    /**
     * The learningEvent1Id
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $learningEvent1Id;
    /**
     * The source1
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $source1;
    /**
     * The originalAccreditationNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalAccreditationNumber;
    /**
     * The originalAwardingBody
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalAwardingBody;
    /**
     * The originalQualificationLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalQualificationLevel;
    /**
     * The originalAimTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalAimTitle;
    /**
     * The originalAchievedDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $originalAchievedDate;
    /**
     * The originalAchievedGrade
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalAchievedGrade;
    /**
     * The originalTotalCredits
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalTotalCredits;
    /**
     * The originalCreditValue
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalCreditValue;
    /**
     * The originalLanguageForAssessment
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalLanguageForAssessment;
    /**
     * The originalProvider
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalProvider;
    /**
     * The originalType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $originalType;
    /**
     * The originalStartDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $originalStartDate;
    /**
     * The originalEndDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $originalEndDate;
    /**
     * The learningEvent2Id
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $learningEvent2Id;
    /**
     * The source2
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $source2;
    /**
     * The suppliedAccreditationNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedAccreditationNumber;
    /**
     * The suppliedAwardingBody
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedAwardingBody;
    /**
     * The suppliedAimTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedAimTitle;
    /**
     * The suppliedAchievedDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $suppliedAchievedDate;
    /**
     * The suppliedAchievedGrade
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedAchievedGrade;
    /**
     * The suppliedTotalCredits
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedTotalCredits;
    /**
     * The suppliedCreditValue
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedCreditValue;
    /**
     * The suppliedLanguageForAssessment
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedLanguageForAssessment;
    /**
     * The suppliedProvider
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedProvider;
    /**
     * The suppliedType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedType;
    /**
     * The suppliedQualificationLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $suppliedQualificationLevel;
    /**
     * The suppliedStartDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $suppliedStartDate;
    /**
     * The suppliedEndDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $suppliedEndDate;
    /**
     * Constructor method for CreateDataChallenge
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param string $_userType
     * @param int $_vendorId
     * @param string $_language
     * @param string $_challengeType
     * @param string $_uln
     * @param string $_givenName
     * @param string $_familyName
     * @param dateTime $_dateOfBirth
     * @param string $_gender
     * @param string $_contactName
     * @param string $_telephone
     * @param string $_emailAddress
     * @param string $_additionalInformation
     * @param int $_learningEvent1Id
     * @param string $_source1
     * @param string $_originalAccreditationNumber
     * @param string $_originalAwardingBody
     * @param string $_originalQualificationLevel
     * @param string $_originalAimTitle
     * @param dateTime $_originalAchievedDate
     * @param string $_originalAchievedGrade
     * @param string $_originalTotalCredits
     * @param string $_originalCreditValue
     * @param string $_originalLanguageForAssessment
     * @param string $_originalProvider
     * @param string $_originalType
     * @param dateTime $_originalStartDate
     * @param dateTime $_originalEndDate
     * @param int $_learningEvent2Id
     * @param string $_source2
     * @param string $_suppliedAccreditationNumber
     * @param string $_suppliedAwardingBody
     * @param string $_suppliedAimTitle
     * @param dateTime $_suppliedAchievedDate
     * @param string $_suppliedAchievedGrade
     * @param string $_suppliedTotalCredits
     * @param string $_suppliedCreditValue
     * @param string $_suppliedLanguageForAssessment
     * @param string $_suppliedProvider
     * @param string $_suppliedType
     * @param string $_suppliedQualificationLevel
     * @param dateTime $_suppliedStartDate
     * @param dateTime $_suppliedEndDate
     * @return LRSStructCreateDataChallenge
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorId,$_language,$_challengeType,$_uln,$_givenName,$_familyName,$_dateOfBirth,$_gender,$_contactName,$_telephone,$_emailAddress,$_additionalInformation,$_learningEvent1Id,$_source1,$_originalAccreditationNumber,$_originalAwardingBody,$_originalQualificationLevel,$_originalAimTitle,$_originalAchievedDate,$_originalAchievedGrade,$_originalTotalCredits,$_originalCreditValue,$_originalLanguageForAssessment,$_originalProvider,$_originalType,$_originalStartDate,$_originalEndDate,$_learningEvent2Id,$_source2,$_suppliedAccreditationNumber,$_suppliedAwardingBody,$_suppliedAimTitle,$_suppliedAchievedDate,$_suppliedAchievedGrade,$_suppliedTotalCredits,$_suppliedCreditValue,$_suppliedLanguageForAssessment,$_suppliedProvider,$_suppliedType,$_suppliedQualificationLevel,$_suppliedStartDate,$_suppliedEndDate)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorId'=>$_vendorId,'language'=>$_language,'challengeType'=>$_challengeType,'uln'=>$_uln,'givenName'=>$_givenName,'familyName'=>$_familyName,'dateOfBirth'=>$_dateOfBirth,'gender'=>$_gender,'contactName'=>$_contactName,'telephone'=>$_telephone,'emailAddress'=>$_emailAddress,'additionalInformation'=>$_additionalInformation,'learningEvent1Id'=>$_learningEvent1Id,'source1'=>$_source1,'originalAccreditationNumber'=>$_originalAccreditationNumber,'originalAwardingBody'=>$_originalAwardingBody,'originalQualificationLevel'=>$_originalQualificationLevel,'originalAimTitle'=>$_originalAimTitle,'originalAchievedDate'=>$_originalAchievedDate,'originalAchievedGrade'=>$_originalAchievedGrade,'originalTotalCredits'=>$_originalTotalCredits,'originalCreditValue'=>$_originalCreditValue,'originalLanguageForAssessment'=>$_originalLanguageForAssessment,'originalProvider'=>$_originalProvider,'originalType'=>$_originalType,'originalStartDate'=>$_originalStartDate,'originalEndDate'=>$_originalEndDate,'learningEvent2Id'=>$_learningEvent2Id,'source2'=>$_source2,'suppliedAccreditationNumber'=>$_suppliedAccreditationNumber,'suppliedAwardingBody'=>$_suppliedAwardingBody,'suppliedAimTitle'=>$_suppliedAimTitle,'suppliedAchievedDate'=>$_suppliedAchievedDate,'suppliedAchievedGrade'=>$_suppliedAchievedGrade,'suppliedTotalCredits'=>$_suppliedTotalCredits,'suppliedCreditValue'=>$_suppliedCreditValue,'suppliedLanguageForAssessment'=>$_suppliedLanguageForAssessment,'suppliedProvider'=>$_suppliedProvider,'suppliedType'=>$_suppliedType,'suppliedQualificationLevel'=>$_suppliedQualificationLevel,'suppliedStartDate'=>$_suppliedStartDate,'suppliedEndDate'=>$_suppliedEndDate),false);
    }
    /**
     * Get invokingOrganisation value
     * @return LRSStructInvokingOrganisation
     */
    public function getInvokingOrganisation()
    {
        return $this->invokingOrganisation;
    }
    /**
     * Set invokingOrganisation value
     * @param LRSStructInvokingOrganisation $_invokingOrganisation the invokingOrganisation
     * @return LRSStructInvokingOrganisation
     */
    public function setInvokingOrganisation($_invokingOrganisation)
    {
        return ($this->invokingOrganisation = $_invokingOrganisation);
    }
    /**
     * Get userType value
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }
    /**
     * Set userType value
     * @param string $_userType the userType
     * @return string
     */
    public function setUserType($_userType)
    {
        return ($this->userType = $_userType);
    }
    /**
     * Get vendorId value
     * @return int
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }
    /**
     * Set vendorId value
     * @param int $_vendorId the vendorId
     * @return int
     */
    public function setVendorId($_vendorId)
    {
        return ($this->vendorId = $_vendorId);
    }
    /**
     * Get language value
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * Set language value
     * @param string $_language the language
     * @return string
     */
    public function setLanguage($_language)
    {
        return ($this->language = $_language);
    }
    /**
     * Get challengeType value
     * @return string
     */
    public function getChallengeType()
    {
        return $this->challengeType;
    }
    /**
     * Set challengeType value
     * @param string $_challengeType the challengeType
     * @return string
     */
    public function setChallengeType($_challengeType)
    {
        return ($this->challengeType = $_challengeType);
    }
    /**
     * Get uln value
     * @return string
     */
    public function getUln()
    {
        return $this->uln;
    }
    /**
     * Set uln value
     * @param string $_uln the uln
     * @return string
     */
    public function setUln($_uln)
    {
        return ($this->uln = $_uln);
    }
    /**
     * Get givenName value
     * @return string
     */
    public function getGivenName()
    {
        return $this->givenName;
    }
    /**
     * Set givenName value
     * @param string $_givenName the givenName
     * @return string
     */
    public function setGivenName($_givenName)
    {
        return ($this->givenName = $_givenName);
    }
    /**
     * Get familyName value
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }
    /**
     * Set familyName value
     * @param string $_familyName the familyName
     * @return string
     */
    public function setFamilyName($_familyName)
    {
        return ($this->familyName = $_familyName);
    }
    /**
     * Get dateOfBirth value
     * @return dateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
    /**
     * Set dateOfBirth value
     * @param dateTime $_dateOfBirth the dateOfBirth
     * @return dateTime
     */
    public function setDateOfBirth($_dateOfBirth)
    {
        return ($this->dateOfBirth = $_dateOfBirth);
    }
    /**
     * Get gender value
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }
    /**
     * Set gender value
     * @param string $_gender the gender
     * @return string
     */
    public function setGender($_gender)
    {
        return ($this->gender = $_gender);
    }
    /**
     * Get contactName value
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }
    /**
     * Set contactName value
     * @param string $_contactName the contactName
     * @return string
     */
    public function setContactName($_contactName)
    {
        return ($this->contactName = $_contactName);
    }
    /**
     * Get telephone value
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }
    /**
     * Set telephone value
     * @param string $_telephone the telephone
     * @return string
     */
    public function setTelephone($_telephone)
    {
        return ($this->telephone = $_telephone);
    }
    /**
     * Get emailAddress value
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
    /**
     * Set emailAddress value
     * @param string $_emailAddress the emailAddress
     * @return string
     */
    public function setEmailAddress($_emailAddress)
    {
        return ($this->emailAddress = $_emailAddress);
    }
    /**
     * Get additionalInformation value
     * @return string
     */
    public function getAdditionalInformation()
    {
        return $this->additionalInformation;
    }
    /**
     * Set additionalInformation value
     * @param string $_additionalInformation the additionalInformation
     * @return string
     */
    public function setAdditionalInformation($_additionalInformation)
    {
        return ($this->additionalInformation = $_additionalInformation);
    }
    /**
     * Get learningEvent1Id value
     * @return int
     */
    public function getLearningEvent1Id()
    {
        return $this->learningEvent1Id;
    }
    /**
     * Set learningEvent1Id value
     * @param int $_learningEvent1Id the learningEvent1Id
     * @return int
     */
    public function setLearningEvent1Id($_learningEvent1Id)
    {
        return ($this->learningEvent1Id = $_learningEvent1Id);
    }
    /**
     * Get source1 value
     * @return string
     */
    public function getSource1()
    {
        return $this->source1;
    }
    /**
     * Set source1 value
     * @param string $_source1 the source1
     * @return string
     */
    public function setSource1($_source1)
    {
        return ($this->source1 = $_source1);
    }
    /**
     * Get originalAccreditationNumber value
     * @return string
     */
    public function getOriginalAccreditationNumber()
    {
        return $this->originalAccreditationNumber;
    }
    /**
     * Set originalAccreditationNumber value
     * @param string $_originalAccreditationNumber the originalAccreditationNumber
     * @return string
     */
    public function setOriginalAccreditationNumber($_originalAccreditationNumber)
    {
        return ($this->originalAccreditationNumber = $_originalAccreditationNumber);
    }
    /**
     * Get originalAwardingBody value
     * @return string
     */
    public function getOriginalAwardingBody()
    {
        return $this->originalAwardingBody;
    }
    /**
     * Set originalAwardingBody value
     * @param string $_originalAwardingBody the originalAwardingBody
     * @return string
     */
    public function setOriginalAwardingBody($_originalAwardingBody)
    {
        return ($this->originalAwardingBody = $_originalAwardingBody);
    }
    /**
     * Get originalQualificationLevel value
     * @return string
     */
    public function getOriginalQualificationLevel()
    {
        return $this->originalQualificationLevel;
    }
    /**
     * Set originalQualificationLevel value
     * @param string $_originalQualificationLevel the originalQualificationLevel
     * @return string
     */
    public function setOriginalQualificationLevel($_originalQualificationLevel)
    {
        return ($this->originalQualificationLevel = $_originalQualificationLevel);
    }
    /**
     * Get originalAimTitle value
     * @return string
     */
    public function getOriginalAimTitle()
    {
        return $this->originalAimTitle;
    }
    /**
     * Set originalAimTitle value
     * @param string $_originalAimTitle the originalAimTitle
     * @return string
     */
    public function setOriginalAimTitle($_originalAimTitle)
    {
        return ($this->originalAimTitle = $_originalAimTitle);
    }
    /**
     * Get originalAchievedDate value
     * @return dateTime
     */
    public function getOriginalAchievedDate()
    {
        return $this->originalAchievedDate;
    }
    /**
     * Set originalAchievedDate value
     * @param dateTime $_originalAchievedDate the originalAchievedDate
     * @return dateTime
     */
    public function setOriginalAchievedDate($_originalAchievedDate)
    {
        return ($this->originalAchievedDate = $_originalAchievedDate);
    }
    /**
     * Get originalAchievedGrade value
     * @return string
     */
    public function getOriginalAchievedGrade()
    {
        return $this->originalAchievedGrade;
    }
    /**
     * Set originalAchievedGrade value
     * @param string $_originalAchievedGrade the originalAchievedGrade
     * @return string
     */
    public function setOriginalAchievedGrade($_originalAchievedGrade)
    {
        return ($this->originalAchievedGrade = $_originalAchievedGrade);
    }
    /**
     * Get originalTotalCredits value
     * @return string
     */
    public function getOriginalTotalCredits()
    {
        return $this->originalTotalCredits;
    }
    /**
     * Set originalTotalCredits value
     * @param string $_originalTotalCredits the originalTotalCredits
     * @return string
     */
    public function setOriginalTotalCredits($_originalTotalCredits)
    {
        return ($this->originalTotalCredits = $_originalTotalCredits);
    }
    /**
     * Get originalCreditValue value
     * @return string
     */
    public function getOriginalCreditValue()
    {
        return $this->originalCreditValue;
    }
    /**
     * Set originalCreditValue value
     * @param string $_originalCreditValue the originalCreditValue
     * @return string
     */
    public function setOriginalCreditValue($_originalCreditValue)
    {
        return ($this->originalCreditValue = $_originalCreditValue);
    }
    /**
     * Get originalLanguageForAssessment value
     * @return string
     */
    public function getOriginalLanguageForAssessment()
    {
        return $this->originalLanguageForAssessment;
    }
    /**
     * Set originalLanguageForAssessment value
     * @param string $_originalLanguageForAssessment the originalLanguageForAssessment
     * @return string
     */
    public function setOriginalLanguageForAssessment($_originalLanguageForAssessment)
    {
        return ($this->originalLanguageForAssessment = $_originalLanguageForAssessment);
    }
    /**
     * Get originalProvider value
     * @return string
     */
    public function getOriginalProvider()
    {
        return $this->originalProvider;
    }
    /**
     * Set originalProvider value
     * @param string $_originalProvider the originalProvider
     * @return string
     */
    public function setOriginalProvider($_originalProvider)
    {
        return ($this->originalProvider = $_originalProvider);
    }
    /**
     * Get originalType value
     * @return string
     */
    public function getOriginalType()
    {
        return $this->originalType;
    }
    /**
     * Set originalType value
     * @param string $_originalType the originalType
     * @return string
     */
    public function setOriginalType($_originalType)
    {
        return ($this->originalType = $_originalType);
    }
    /**
     * Get originalStartDate value
     * @return dateTime
     */
    public function getOriginalStartDate()
    {
        return $this->originalStartDate;
    }
    /**
     * Set originalStartDate value
     * @param dateTime $_originalStartDate the originalStartDate
     * @return dateTime
     */
    public function setOriginalStartDate($_originalStartDate)
    {
        return ($this->originalStartDate = $_originalStartDate);
    }
    /**
     * Get originalEndDate value
     * @return dateTime
     */
    public function getOriginalEndDate()
    {
        return $this->originalEndDate;
    }
    /**
     * Set originalEndDate value
     * @param dateTime $_originalEndDate the originalEndDate
     * @return dateTime
     */
    public function setOriginalEndDate($_originalEndDate)
    {
        return ($this->originalEndDate = $_originalEndDate);
    }
    /**
     * Get learningEvent2Id value
     * @return int
     */
    public function getLearningEvent2Id()
    {
        return $this->learningEvent2Id;
    }
    /**
     * Set learningEvent2Id value
     * @param int $_learningEvent2Id the learningEvent2Id
     * @return int
     */
    public function setLearningEvent2Id($_learningEvent2Id)
    {
        return ($this->learningEvent2Id = $_learningEvent2Id);
    }
    /**
     * Get source2 value
     * @return string
     */
    public function getSource2()
    {
        return $this->source2;
    }
    /**
     * Set source2 value
     * @param string $_source2 the source2
     * @return string
     */
    public function setSource2($_source2)
    {
        return ($this->source2 = $_source2);
    }
    /**
     * Get suppliedAccreditationNumber value
     * @return string
     */
    public function getSuppliedAccreditationNumber()
    {
        return $this->suppliedAccreditationNumber;
    }
    /**
     * Set suppliedAccreditationNumber value
     * @param string $_suppliedAccreditationNumber the suppliedAccreditationNumber
     * @return string
     */
    public function setSuppliedAccreditationNumber($_suppliedAccreditationNumber)
    {
        return ($this->suppliedAccreditationNumber = $_suppliedAccreditationNumber);
    }
    /**
     * Get suppliedAwardingBody value
     * @return string
     */
    public function getSuppliedAwardingBody()
    {
        return $this->suppliedAwardingBody;
    }
    /**
     * Set suppliedAwardingBody value
     * @param string $_suppliedAwardingBody the suppliedAwardingBody
     * @return string
     */
    public function setSuppliedAwardingBody($_suppliedAwardingBody)
    {
        return ($this->suppliedAwardingBody = $_suppliedAwardingBody);
    }
    /**
     * Get suppliedAimTitle value
     * @return string
     */
    public function getSuppliedAimTitle()
    {
        return $this->suppliedAimTitle;
    }
    /**
     * Set suppliedAimTitle value
     * @param string $_suppliedAimTitle the suppliedAimTitle
     * @return string
     */
    public function setSuppliedAimTitle($_suppliedAimTitle)
    {
        return ($this->suppliedAimTitle = $_suppliedAimTitle);
    }
    /**
     * Get suppliedAchievedDate value
     * @return dateTime
     */
    public function getSuppliedAchievedDate()
    {
        return $this->suppliedAchievedDate;
    }
    /**
     * Set suppliedAchievedDate value
     * @param dateTime $_suppliedAchievedDate the suppliedAchievedDate
     * @return dateTime
     */
    public function setSuppliedAchievedDate($_suppliedAchievedDate)
    {
        return ($this->suppliedAchievedDate = $_suppliedAchievedDate);
    }
    /**
     * Get suppliedAchievedGrade value
     * @return string
     */
    public function getSuppliedAchievedGrade()
    {
        return $this->suppliedAchievedGrade;
    }
    /**
     * Set suppliedAchievedGrade value
     * @param string $_suppliedAchievedGrade the suppliedAchievedGrade
     * @return string
     */
    public function setSuppliedAchievedGrade($_suppliedAchievedGrade)
    {
        return ($this->suppliedAchievedGrade = $_suppliedAchievedGrade);
    }
    /**
     * Get suppliedTotalCredits value
     * @return string
     */
    public function getSuppliedTotalCredits()
    {
        return $this->suppliedTotalCredits;
    }
    /**
     * Set suppliedTotalCredits value
     * @param string $_suppliedTotalCredits the suppliedTotalCredits
     * @return string
     */
    public function setSuppliedTotalCredits($_suppliedTotalCredits)
    {
        return ($this->suppliedTotalCredits = $_suppliedTotalCredits);
    }
    /**
     * Get suppliedCreditValue value
     * @return string
     */
    public function getSuppliedCreditValue()
    {
        return $this->suppliedCreditValue;
    }
    /**
     * Set suppliedCreditValue value
     * @param string $_suppliedCreditValue the suppliedCreditValue
     * @return string
     */
    public function setSuppliedCreditValue($_suppliedCreditValue)
    {
        return ($this->suppliedCreditValue = $_suppliedCreditValue);
    }
    /**
     * Get suppliedLanguageForAssessment value
     * @return string
     */
    public function getSuppliedLanguageForAssessment()
    {
        return $this->suppliedLanguageForAssessment;
    }
    /**
     * Set suppliedLanguageForAssessment value
     * @param string $_suppliedLanguageForAssessment the suppliedLanguageForAssessment
     * @return string
     */
    public function setSuppliedLanguageForAssessment($_suppliedLanguageForAssessment)
    {
        return ($this->suppliedLanguageForAssessment = $_suppliedLanguageForAssessment);
    }
    /**
     * Get suppliedProvider value
     * @return string
     */
    public function getSuppliedProvider()
    {
        return $this->suppliedProvider;
    }
    /**
     * Set suppliedProvider value
     * @param string $_suppliedProvider the suppliedProvider
     * @return string
     */
    public function setSuppliedProvider($_suppliedProvider)
    {
        return ($this->suppliedProvider = $_suppliedProvider);
    }
    /**
     * Get suppliedType value
     * @return string
     */
    public function getSuppliedType()
    {
        return $this->suppliedType;
    }
    /**
     * Set suppliedType value
     * @param string $_suppliedType the suppliedType
     * @return string
     */
    public function setSuppliedType($_suppliedType)
    {
        return ($this->suppliedType = $_suppliedType);
    }
    /**
     * Get suppliedQualificationLevel value
     * @return string
     */
    public function getSuppliedQualificationLevel()
    {
        return $this->suppliedQualificationLevel;
    }
    /**
     * Set suppliedQualificationLevel value
     * @param string $_suppliedQualificationLevel the suppliedQualificationLevel
     * @return string
     */
    public function setSuppliedQualificationLevel($_suppliedQualificationLevel)
    {
        return ($this->suppliedQualificationLevel = $_suppliedQualificationLevel);
    }
    /**
     * Get suppliedStartDate value
     * @return dateTime
     */
    public function getSuppliedStartDate()
    {
        return $this->suppliedStartDate;
    }
    /**
     * Set suppliedStartDate value
     * @param dateTime $_suppliedStartDate the suppliedStartDate
     * @return dateTime
     */
    public function setSuppliedStartDate($_suppliedStartDate)
    {
        return ($this->suppliedStartDate = $_suppliedStartDate);
    }
    /**
     * Get suppliedEndDate value
     * @return dateTime
     */
    public function getSuppliedEndDate()
    {
        return $this->suppliedEndDate;
    }
    /**
     * Set suppliedEndDate value
     * @param dateTime $_suppliedEndDate the suppliedEndDate
     * @return dateTime
     */
    public function setSuppliedEndDate($_suppliedEndDate)
    {
        return ($this->suppliedEndDate = $_suppliedEndDate);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructCreateDataChallenge
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
