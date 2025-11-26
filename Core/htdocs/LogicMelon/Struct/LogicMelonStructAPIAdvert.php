<?php
/**
 * File for class LogicMelonStructAPIAdvert
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAPIAdvert originally named APIAdvert
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAPIAdvert extends LogicMelonWsdlClass
{
    /**
     * The AdvertID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $AdvertID;
    /**
     * The UserID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $UserID;
    /**
     * The OrganisationID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $OrganisationID;
    /**
     * The LastPostDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var dateTime
     */
    public $LastPostDate;
    /**
     * The AdvertStatusID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var short
     */
    public $AdvertStatusID;
    /**
     * The Applications
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $Applications;
    /**
     * The Viewed
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $Viewed;
    /**
     * The Suitable
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $Suitable;
    /**
     * The MaybeSuitable
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $MaybeSuitable;
    /**
     * The Unsuitable
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $Unsuitable;
    /**
     * The SalaryHide
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $SalaryHide;
    /**
     * The latitude
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $latitude;
    /**
     * The longitude
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $longitude;
    /**
     * The InterviewConfirmed
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $InterviewConfirmed;
    /**
     * The InterviewPending
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $InterviewPending;
    /**
     * The InterviewCannotAttend
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $InterviewCannotAttend;
    /**
     * The InterviewDeclined
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $InterviewDeclined;
    /**
     * The WithStatus
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $WithStatus;
    /**
     * The WithStatusNew
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $WithStatusNew;
    /**
     * The SchemaIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SchemaIdentifier;
    /**
     * The AdvertIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $AdvertIdentifier;
    /**
     * The AdvertReference
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $AdvertReference;
    /**
     * The AdvertTitle
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $AdvertTitle;
    /**
     * The AdvertType
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $AdvertType;
    /**
     * The AdvertHours
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $AdvertHours;
    /**
     * The PrimaryLocation
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $PrimaryLocation;
    /**
     * The Industry
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Industry;
    /**
     * The SalaryFrom
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SalaryFrom;
    /**
     * The SalaryTo
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SalaryTo;
    /**
     * The SalaryCurrency
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SalaryCurrency;
    /**
     * The SalaryPer
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SalaryPer;
    /**
     * The SalaryBenefits
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SalaryBenefits;
    /**
     * The ContactName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $ContactName;
    /**
     * The ContactEmail
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $ContactEmail;
    /**
     * The Source
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Source;
    /**
     * The JobDescription
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $JobDescription;
    /**
     * Constructor method for APIAdvert
     * @see parent::__construct()
     * @param int $_advertID
     * @param int $_userID
     * @param int $_organisationID
     * @param dateTime $_lastPostDate
     * @param short $_advertStatusID
     * @param int $_applications
     * @param int $_viewed
     * @param int $_suitable
     * @param int $_maybeSuitable
     * @param int $_unsuitable
     * @param boolean $_salaryHide
     * @param decimal $_latitude
     * @param decimal $_longitude
     * @param int $_interviewConfirmed
     * @param int $_interviewPending
     * @param int $_interviewCannotAttend
     * @param int $_interviewDeclined
     * @param int $_withStatus
     * @param int $_withStatusNew
     * @param string $_schemaIdentifier
     * @param string $_advertIdentifier
     * @param string $_advertReference
     * @param string $_advertTitle
     * @param string $_advertType
     * @param string $_advertHours
     * @param string $_primaryLocation
     * @param string $_industry
     * @param string $_salaryFrom
     * @param string $_salaryTo
     * @param string $_salaryCurrency
     * @param string $_salaryPer
     * @param string $_salaryBenefits
     * @param string $_contactName
     * @param string $_contactEmail
     * @param string $_source
     * @param string $_jobDescription
     * @return LogicMelonStructAPIAdvert
     */
    public function __construct($_advertID,$_userID,$_organisationID,$_lastPostDate,$_advertStatusID,$_applications,$_viewed,$_suitable,$_maybeSuitable,$_unsuitable,$_salaryHide,$_latitude,$_longitude,$_interviewConfirmed,$_interviewPending,$_interviewCannotAttend,$_interviewDeclined,$_withStatus,$_withStatusNew,$_schemaIdentifier = NULL,$_advertIdentifier = NULL,$_advertReference = NULL,$_advertTitle = NULL,$_advertType = NULL,$_advertHours = NULL,$_primaryLocation = NULL,$_industry = NULL,$_salaryFrom = NULL,$_salaryTo = NULL,$_salaryCurrency = NULL,$_salaryPer = NULL,$_salaryBenefits = NULL,$_contactName = NULL,$_contactEmail = NULL,$_source = NULL,$_jobDescription = NULL)
    {
        parent::__construct(array('AdvertID'=>$_advertID,'UserID'=>$_userID,'OrganisationID'=>$_organisationID,'LastPostDate'=>$_lastPostDate,'AdvertStatusID'=>$_advertStatusID,'Applications'=>$_applications,'Viewed'=>$_viewed,'Suitable'=>$_suitable,'MaybeSuitable'=>$_maybeSuitable,'Unsuitable'=>$_unsuitable,'SalaryHide'=>$_salaryHide,'latitude'=>$_latitude,'longitude'=>$_longitude,'InterviewConfirmed'=>$_interviewConfirmed,'InterviewPending'=>$_interviewPending,'InterviewCannotAttend'=>$_interviewCannotAttend,'InterviewDeclined'=>$_interviewDeclined,'WithStatus'=>$_withStatus,'WithStatusNew'=>$_withStatusNew,'SchemaIdentifier'=>$_schemaIdentifier,'AdvertIdentifier'=>$_advertIdentifier,'AdvertReference'=>$_advertReference,'AdvertTitle'=>$_advertTitle,'AdvertType'=>$_advertType,'AdvertHours'=>$_advertHours,'PrimaryLocation'=>$_primaryLocation,'Industry'=>$_industry,'SalaryFrom'=>$_salaryFrom,'SalaryTo'=>$_salaryTo,'SalaryCurrency'=>$_salaryCurrency,'SalaryPer'=>$_salaryPer,'SalaryBenefits'=>$_salaryBenefits,'ContactName'=>$_contactName,'ContactEmail'=>$_contactEmail,'Source'=>$_source,'JobDescription'=>$_jobDescription),false);
    }
    /**
     * Get AdvertID value
     * @return int
     */
    public function getAdvertID()
    {
        return $this->AdvertID;
    }
    /**
     * Set AdvertID value
     * @param int $_advertID the AdvertID
     * @return int
     */
    public function setAdvertID($_advertID)
    {
        return ($this->AdvertID = $_advertID);
    }
    /**
     * Get UserID value
     * @return int
     */
    public function getUserID()
    {
        return $this->UserID;
    }
    /**
     * Set UserID value
     * @param int $_userID the UserID
     * @return int
     */
    public function setUserID($_userID)
    {
        return ($this->UserID = $_userID);
    }
    /**
     * Get OrganisationID value
     * @return int
     */
    public function getOrganisationID()
    {
        return $this->OrganisationID;
    }
    /**
     * Set OrganisationID value
     * @param int $_organisationID the OrganisationID
     * @return int
     */
    public function setOrganisationID($_organisationID)
    {
        return ($this->OrganisationID = $_organisationID);
    }
    /**
     * Get LastPostDate value
     * @return dateTime
     */
    public function getLastPostDate()
    {
        return $this->LastPostDate;
    }
    /**
     * Set LastPostDate value
     * @param dateTime $_lastPostDate the LastPostDate
     * @return dateTime
     */
    public function setLastPostDate($_lastPostDate)
    {
        return ($this->LastPostDate = $_lastPostDate);
    }
    /**
     * Get AdvertStatusID value
     * @return short
     */
    public function getAdvertStatusID()
    {
        return $this->AdvertStatusID;
    }
    /**
     * Set AdvertStatusID value
     * @param short $_advertStatusID the AdvertStatusID
     * @return short
     */
    public function setAdvertStatusID($_advertStatusID)
    {
        return ($this->AdvertStatusID = $_advertStatusID);
    }
    /**
     * Get Applications value
     * @return int
     */
    public function getApplications()
    {
        return $this->Applications;
    }
    /**
     * Set Applications value
     * @param int $_applications the Applications
     * @return int
     */
    public function setApplications($_applications)
    {
        return ($this->Applications = $_applications);
    }
    /**
     * Get Viewed value
     * @return int
     */
    public function getViewed()
    {
        return $this->Viewed;
    }
    /**
     * Set Viewed value
     * @param int $_viewed the Viewed
     * @return int
     */
    public function setViewed($_viewed)
    {
        return ($this->Viewed = $_viewed);
    }
    /**
     * Get Suitable value
     * @return int
     */
    public function getSuitable()
    {
        return $this->Suitable;
    }
    /**
     * Set Suitable value
     * @param int $_suitable the Suitable
     * @return int
     */
    public function setSuitable($_suitable)
    {
        return ($this->Suitable = $_suitable);
    }
    /**
     * Get MaybeSuitable value
     * @return int
     */
    public function getMaybeSuitable()
    {
        return $this->MaybeSuitable;
    }
    /**
     * Set MaybeSuitable value
     * @param int $_maybeSuitable the MaybeSuitable
     * @return int
     */
    public function setMaybeSuitable($_maybeSuitable)
    {
        return ($this->MaybeSuitable = $_maybeSuitable);
    }
    /**
     * Get Unsuitable value
     * @return int
     */
    public function getUnsuitable()
    {
        return $this->Unsuitable;
    }
    /**
     * Set Unsuitable value
     * @param int $_unsuitable the Unsuitable
     * @return int
     */
    public function setUnsuitable($_unsuitable)
    {
        return ($this->Unsuitable = $_unsuitable);
    }
    /**
     * Get SalaryHide value
     * @return boolean
     */
    public function getSalaryHide()
    {
        return $this->SalaryHide;
    }
    /**
     * Set SalaryHide value
     * @param boolean $_salaryHide the SalaryHide
     * @return boolean
     */
    public function setSalaryHide($_salaryHide)
    {
        return ($this->SalaryHide = $_salaryHide);
    }
    /**
     * Get latitude value
     * @return decimal
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    /**
     * Set latitude value
     * @param decimal $_latitude the latitude
     * @return decimal
     */
    public function setLatitude($_latitude)
    {
        return ($this->latitude = $_latitude);
    }
    /**
     * Get longitude value
     * @return decimal
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    /**
     * Set longitude value
     * @param decimal $_longitude the longitude
     * @return decimal
     */
    public function setLongitude($_longitude)
    {
        return ($this->longitude = $_longitude);
    }
    /**
     * Get InterviewConfirmed value
     * @return int
     */
    public function getInterviewConfirmed()
    {
        return $this->InterviewConfirmed;
    }
    /**
     * Set InterviewConfirmed value
     * @param int $_interviewConfirmed the InterviewConfirmed
     * @return int
     */
    public function setInterviewConfirmed($_interviewConfirmed)
    {
        return ($this->InterviewConfirmed = $_interviewConfirmed);
    }
    /**
     * Get InterviewPending value
     * @return int
     */
    public function getInterviewPending()
    {
        return $this->InterviewPending;
    }
    /**
     * Set InterviewPending value
     * @param int $_interviewPending the InterviewPending
     * @return int
     */
    public function setInterviewPending($_interviewPending)
    {
        return ($this->InterviewPending = $_interviewPending);
    }
    /**
     * Get InterviewCannotAttend value
     * @return int
     */
    public function getInterviewCannotAttend()
    {
        return $this->InterviewCannotAttend;
    }
    /**
     * Set InterviewCannotAttend value
     * @param int $_interviewCannotAttend the InterviewCannotAttend
     * @return int
     */
    public function setInterviewCannotAttend($_interviewCannotAttend)
    {
        return ($this->InterviewCannotAttend = $_interviewCannotAttend);
    }
    /**
     * Get InterviewDeclined value
     * @return int
     */
    public function getInterviewDeclined()
    {
        return $this->InterviewDeclined;
    }
    /**
     * Set InterviewDeclined value
     * @param int $_interviewDeclined the InterviewDeclined
     * @return int
     */
    public function setInterviewDeclined($_interviewDeclined)
    {
        return ($this->InterviewDeclined = $_interviewDeclined);
    }
    /**
     * Get WithStatus value
     * @return int
     */
    public function getWithStatus()
    {
        return $this->WithStatus;
    }
    /**
     * Set WithStatus value
     * @param int $_withStatus the WithStatus
     * @return int
     */
    public function setWithStatus($_withStatus)
    {
        return ($this->WithStatus = $_withStatus);
    }
    /**
     * Get WithStatusNew value
     * @return int
     */
    public function getWithStatusNew()
    {
        return $this->WithStatusNew;
    }
    /**
     * Set WithStatusNew value
     * @param int $_withStatusNew the WithStatusNew
     * @return int
     */
    public function setWithStatusNew($_withStatusNew)
    {
        return ($this->WithStatusNew = $_withStatusNew);
    }
    /**
     * Get SchemaIdentifier value
     * @return string|null
     */
    public function getSchemaIdentifier()
    {
        return $this->SchemaIdentifier;
    }
    /**
     * Set SchemaIdentifier value
     * @param string $_schemaIdentifier the SchemaIdentifier
     * @return string
     */
    public function setSchemaIdentifier($_schemaIdentifier)
    {
        return ($this->SchemaIdentifier = $_schemaIdentifier);
    }
    /**
     * Get AdvertIdentifier value
     * @return string|null
     */
    public function getAdvertIdentifier()
    {
        return $this->AdvertIdentifier;
    }
    /**
     * Set AdvertIdentifier value
     * @param string $_advertIdentifier the AdvertIdentifier
     * @return string
     */
    public function setAdvertIdentifier($_advertIdentifier)
    {
        return ($this->AdvertIdentifier = $_advertIdentifier);
    }
    /**
     * Get AdvertReference value
     * @return string|null
     */
    public function getAdvertReference()
    {
        return $this->AdvertReference;
    }
    /**
     * Set AdvertReference value
     * @param string $_advertReference the AdvertReference
     * @return string
     */
    public function setAdvertReference($_advertReference)
    {
        return ($this->AdvertReference = $_advertReference);
    }
    /**
     * Get AdvertTitle value
     * @return string|null
     */
    public function getAdvertTitle()
    {
        return $this->AdvertTitle;
    }
    /**
     * Set AdvertTitle value
     * @param string $_advertTitle the AdvertTitle
     * @return string
     */
    public function setAdvertTitle($_advertTitle)
    {
        return ($this->AdvertTitle = $_advertTitle);
    }
    /**
     * Get AdvertType value
     * @return string|null
     */
    public function getAdvertType()
    {
        return $this->AdvertType;
    }
    /**
     * Set AdvertType value
     * @param string $_advertType the AdvertType
     * @return string
     */
    public function setAdvertType($_advertType)
    {
        return ($this->AdvertType = $_advertType);
    }
    /**
     * Get AdvertHours value
     * @return string|null
     */
    public function getAdvertHours()
    {
        return $this->AdvertHours;
    }
    /**
     * Set AdvertHours value
     * @param string $_advertHours the AdvertHours
     * @return string
     */
    public function setAdvertHours($_advertHours)
    {
        return ($this->AdvertHours = $_advertHours);
    }
    /**
     * Get PrimaryLocation value
     * @return string|null
     */
    public function getPrimaryLocation()
    {
        return $this->PrimaryLocation;
    }
    /**
     * Set PrimaryLocation value
     * @param string $_primaryLocation the PrimaryLocation
     * @return string
     */
    public function setPrimaryLocation($_primaryLocation)
    {
        return ($this->PrimaryLocation = $_primaryLocation);
    }
    /**
     * Get Industry value
     * @return string|null
     */
    public function getIndustry()
    {
        return $this->Industry;
    }
    /**
     * Set Industry value
     * @param string $_industry the Industry
     * @return string
     */
    public function setIndustry($_industry)
    {
        return ($this->Industry = $_industry);
    }
    /**
     * Get SalaryFrom value
     * @return string|null
     */
    public function getSalaryFrom()
    {
        return $this->SalaryFrom;
    }
    /**
     * Set SalaryFrom value
     * @param string $_salaryFrom the SalaryFrom
     * @return string
     */
    public function setSalaryFrom($_salaryFrom)
    {
        return ($this->SalaryFrom = $_salaryFrom);
    }
    /**
     * Get SalaryTo value
     * @return string|null
     */
    public function getSalaryTo()
    {
        return $this->SalaryTo;
    }
    /**
     * Set SalaryTo value
     * @param string $_salaryTo the SalaryTo
     * @return string
     */
    public function setSalaryTo($_salaryTo)
    {
        return ($this->SalaryTo = $_salaryTo);
    }
    /**
     * Get SalaryCurrency value
     * @return string|null
     */
    public function getSalaryCurrency()
    {
        return $this->SalaryCurrency;
    }
    /**
     * Set SalaryCurrency value
     * @param string $_salaryCurrency the SalaryCurrency
     * @return string
     */
    public function setSalaryCurrency($_salaryCurrency)
    {
        return ($this->SalaryCurrency = $_salaryCurrency);
    }
    /**
     * Get SalaryPer value
     * @return string|null
     */
    public function getSalaryPer()
    {
        return $this->SalaryPer;
    }
    /**
     * Set SalaryPer value
     * @param string $_salaryPer the SalaryPer
     * @return string
     */
    public function setSalaryPer($_salaryPer)
    {
        return ($this->SalaryPer = $_salaryPer);
    }
    /**
     * Get SalaryBenefits value
     * @return string|null
     */
    public function getSalaryBenefits()
    {
        return $this->SalaryBenefits;
    }
    /**
     * Set SalaryBenefits value
     * @param string $_salaryBenefits the SalaryBenefits
     * @return string
     */
    public function setSalaryBenefits($_salaryBenefits)
    {
        return ($this->SalaryBenefits = $_salaryBenefits);
    }
    /**
     * Get ContactName value
     * @return string|null
     */
    public function getContactName()
    {
        return $this->ContactName;
    }
    /**
     * Set ContactName value
     * @param string $_contactName the ContactName
     * @return string
     */
    public function setContactName($_contactName)
    {
        return ($this->ContactName = $_contactName);
    }
    /**
     * Get ContactEmail value
     * @return string|null
     */
    public function getContactEmail()
    {
        return $this->ContactEmail;
    }
    /**
     * Set ContactEmail value
     * @param string $_contactEmail the ContactEmail
     * @return string
     */
    public function setContactEmail($_contactEmail)
    {
        return ($this->ContactEmail = $_contactEmail);
    }
    /**
     * Get Source value
     * @return string|null
     */
    public function getSource()
    {
        return $this->Source;
    }
    /**
     * Set Source value
     * @param string $_source the Source
     * @return string
     */
    public function setSource($_source)
    {
        return ($this->Source = $_source);
    }
    /**
     * Get JobDescription value
     * @return string|null
     */
    public function getJobDescription()
    {
        return $this->JobDescription;
    }
    /**
     * Set JobDescription value
     * @param string $_jobDescription the JobDescription
     * @return string
     */
    public function setJobDescription($_jobDescription)
    {
        return ($this->JobDescription = $_jobDescription);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAPIAdvert
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
