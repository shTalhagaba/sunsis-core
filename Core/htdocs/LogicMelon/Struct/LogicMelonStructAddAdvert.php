<?php
/**
 * File for class LogicMelonStructAddAdvert
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAddAdvert originally named AddAdvert
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAddAdvert extends LogicMelonWsdlClass
{
    /**
     * The sCultureID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sCultureID;
    /**
     * The sAPIKey
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAPIKey;
    /**
     * The sUsername
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sUsername;
    /**
     * The sPassword
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sPassword;
    /**
     * The sAdvertIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAdvertIdentifier;
    /**
     * The sAdvertReference
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAdvertReference;
    /**
     * The sSearchDays
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sSearchDays;
    /**
     * The sOnDuplicate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sOnDuplicate;
    /**
     * The sJobTitle
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sJobTitle;
    /**
     * The sJobType
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sJobType;
    /**
     * The sJobHours
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sJobHours;
    /**
     * The sPrimaryLocation
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sPrimaryLocation;
    /**
     * The sIndustry
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sIndustry;
    /**
     * The sSalaryFrom
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sSalaryFrom;
    /**
     * The sSalaryTo
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sSalaryTo;
    /**
     * The sSalaryCurrency
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sSalaryCurrency;
    /**
     * The sSalaryPer
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sSalaryPer;
    /**
     * The sSalaryBenefits
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sSalaryBenefits;
    /**
     * The sContactName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sContactName;
    /**
     * The sContactEmail
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sContactEmail;
    /**
     * The sJobDescription
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sJobDescription;
    /**
     * The sApplicationURL
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sApplicationURL;
    /**
     * The DestinationsAsCSV
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $DestinationsAsCSV;
    /**
     * The sFuturePostDateTimeInUtc
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sFuturePostDateTimeInUtc;
    /**
     * The sRedirectDomain
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sRedirectDomain;
    /**
     * The sAdvertStatusID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAdvertStatusID;
    /**
     * Constructor method for AddAdvert
     * @see parent::__construct()
     * @param string $_sCultureID
     * @param string $_sAPIKey
     * @param string $_sUsername
     * @param string $_sPassword
     * @param string $_sAdvertIdentifier
     * @param string $_sAdvertReference
     * @param string $_sSearchDays
     * @param string $_sOnDuplicate
     * @param string $_sJobTitle
     * @param string $_sJobType
     * @param string $_sJobHours
     * @param string $_sPrimaryLocation
     * @param string $_sIndustry
     * @param string $_sSalaryFrom
     * @param string $_sSalaryTo
     * @param string $_sSalaryCurrency
     * @param string $_sSalaryPer
     * @param string $_sSalaryBenefits
     * @param string $_sContactName
     * @param string $_sContactEmail
     * @param string $_sJobDescription
     * @param string $_sApplicationURL
     * @param string $_destinationsAsCSV
     * @param string $_sFuturePostDateTimeInUtc
     * @param string $_sRedirectDomain
     * @param string $_sAdvertStatusID
     * @return LogicMelonStructAddAdvert
     */
    public function __construct($_sCultureID = NULL,$_sAPIKey = NULL,$_sUsername = NULL,$_sPassword = NULL,$_sAdvertIdentifier = NULL,$_sAdvertReference = NULL,$_sSearchDays = NULL,$_sOnDuplicate = NULL,$_sJobTitle = NULL,$_sJobType = NULL,$_sJobHours = NULL,$_sPrimaryLocation = NULL,$_sIndustry = NULL,$_sSalaryFrom = NULL,$_sSalaryTo = NULL,$_sSalaryCurrency = NULL,$_sSalaryPer = NULL,$_sSalaryBenefits = NULL,$_sContactName = NULL,$_sContactEmail = NULL,$_sJobDescription = NULL,$_sApplicationURL = NULL,$_destinationsAsCSV = NULL,$_sFuturePostDateTimeInUtc = NULL,$_sRedirectDomain = NULL,$_sAdvertStatusID = NULL)
    {
        parent::__construct(array('sCultureID'=>$_sCultureID,'sAPIKey'=>$_sAPIKey,'sUsername'=>$_sUsername,'sPassword'=>$_sPassword,'sAdvertIdentifier'=>$_sAdvertIdentifier,'sAdvertReference'=>$_sAdvertReference,'sSearchDays'=>$_sSearchDays,'sOnDuplicate'=>$_sOnDuplicate,'sJobTitle'=>$_sJobTitle,'sJobType'=>$_sJobType,'sJobHours'=>$_sJobHours,'sPrimaryLocation'=>$_sPrimaryLocation,'sIndustry'=>$_sIndustry,'sSalaryFrom'=>$_sSalaryFrom,'sSalaryTo'=>$_sSalaryTo,'sSalaryCurrency'=>$_sSalaryCurrency,'sSalaryPer'=>$_sSalaryPer,'sSalaryBenefits'=>$_sSalaryBenefits,'sContactName'=>$_sContactName,'sContactEmail'=>$_sContactEmail,'sJobDescription'=>$_sJobDescription,'sApplicationURL'=>$_sApplicationURL,'DestinationsAsCSV'=>$_destinationsAsCSV,'sFuturePostDateTimeInUtc'=>$_sFuturePostDateTimeInUtc,'sRedirectDomain'=>$_sRedirectDomain,'sAdvertStatusID'=>$_sAdvertStatusID),false);
    }
    /**
     * Get sCultureID value
     * @return string|null
     */
    public function getSCultureID()
    {
        return $this->sCultureID;
    }
    /**
     * Set sCultureID value
     * @param string $_sCultureID the sCultureID
     * @return string
     */
    public function setSCultureID($_sCultureID)
    {
        return ($this->sCultureID = $_sCultureID);
    }
    /**
     * Get sAPIKey value
     * @return string|null
     */
    public function getSAPIKey()
    {
        return $this->sAPIKey;
    }
    /**
     * Set sAPIKey value
     * @param string $_sAPIKey the sAPIKey
     * @return string
     */
    public function setSAPIKey($_sAPIKey)
    {
        return ($this->sAPIKey = $_sAPIKey);
    }
    /**
     * Get sUsername value
     * @return string|null
     */
    public function getSUsername()
    {
        return $this->sUsername;
    }
    /**
     * Set sUsername value
     * @param string $_sUsername the sUsername
     * @return string
     */
    public function setSUsername($_sUsername)
    {
        return ($this->sUsername = $_sUsername);
    }
    /**
     * Get sPassword value
     * @return string|null
     */
    public function getSPassword()
    {
        return $this->sPassword;
    }
    /**
     * Set sPassword value
     * @param string $_sPassword the sPassword
     * @return string
     */
    public function setSPassword($_sPassword)
    {
        return ($this->sPassword = $_sPassword);
    }
    /**
     * Get sAdvertIdentifier value
     * @return string|null
     */
    public function getSAdvertIdentifier()
    {
        return $this->sAdvertIdentifier;
    }
    /**
     * Set sAdvertIdentifier value
     * @param string $_sAdvertIdentifier the sAdvertIdentifier
     * @return string
     */
    public function setSAdvertIdentifier($_sAdvertIdentifier)
    {
        return ($this->sAdvertIdentifier = $_sAdvertIdentifier);
    }
    /**
     * Get sAdvertReference value
     * @return string|null
     */
    public function getSAdvertReference()
    {
        return $this->sAdvertReference;
    }
    /**
     * Set sAdvertReference value
     * @param string $_sAdvertReference the sAdvertReference
     * @return string
     */
    public function setSAdvertReference($_sAdvertReference)
    {
        return ($this->sAdvertReference = $_sAdvertReference);
    }
    /**
     * Get sSearchDays value
     * @return string|null
     */
    public function getSSearchDays()
    {
        return $this->sSearchDays;
    }
    /**
     * Set sSearchDays value
     * @param string $_sSearchDays the sSearchDays
     * @return string
     */
    public function setSSearchDays($_sSearchDays)
    {
        return ($this->sSearchDays = $_sSearchDays);
    }
    /**
     * Get sOnDuplicate value
     * @return string|null
     */
    public function getSOnDuplicate()
    {
        return $this->sOnDuplicate;
    }
    /**
     * Set sOnDuplicate value
     * @param string $_sOnDuplicate the sOnDuplicate
     * @return string
     */
    public function setSOnDuplicate($_sOnDuplicate)
    {
        return ($this->sOnDuplicate = $_sOnDuplicate);
    }
    /**
     * Get sJobTitle value
     * @return string|null
     */
    public function getSJobTitle()
    {
        return $this->sJobTitle;
    }
    /**
     * Set sJobTitle value
     * @param string $_sJobTitle the sJobTitle
     * @return string
     */
    public function setSJobTitle($_sJobTitle)
    {
        return ($this->sJobTitle = $_sJobTitle);
    }
    /**
     * Get sJobType value
     * @return string|null
     */
    public function getSJobType()
    {
        return $this->sJobType;
    }
    /**
     * Set sJobType value
     * @param string $_sJobType the sJobType
     * @return string
     */
    public function setSJobType($_sJobType)
    {
        return ($this->sJobType = $_sJobType);
    }
    /**
     * Get sJobHours value
     * @return string|null
     */
    public function getSJobHours()
    {
        return $this->sJobHours;
    }
    /**
     * Set sJobHours value
     * @param string $_sJobHours the sJobHours
     * @return string
     */
    public function setSJobHours($_sJobHours)
    {
        return ($this->sJobHours = $_sJobHours);
    }
    /**
     * Get sPrimaryLocation value
     * @return string|null
     */
    public function getSPrimaryLocation()
    {
        return $this->sPrimaryLocation;
    }
    /**
     * Set sPrimaryLocation value
     * @param string $_sPrimaryLocation the sPrimaryLocation
     * @return string
     */
    public function setSPrimaryLocation($_sPrimaryLocation)
    {
        return ($this->sPrimaryLocation = $_sPrimaryLocation);
    }
    /**
     * Get sIndustry value
     * @return string|null
     */
    public function getSIndustry()
    {
        return $this->sIndustry;
    }
    /**
     * Set sIndustry value
     * @param string $_sIndustry the sIndustry
     * @return string
     */
    public function setSIndustry($_sIndustry)
    {
        return ($this->sIndustry = $_sIndustry);
    }
    /**
     * Get sSalaryFrom value
     * @return string|null
     */
    public function getSSalaryFrom()
    {
        return $this->sSalaryFrom;
    }
    /**
     * Set sSalaryFrom value
     * @param string $_sSalaryFrom the sSalaryFrom
     * @return string
     */
    public function setSSalaryFrom($_sSalaryFrom)
    {
        return ($this->sSalaryFrom = $_sSalaryFrom);
    }
    /**
     * Get sSalaryTo value
     * @return string|null
     */
    public function getSSalaryTo()
    {
        return $this->sSalaryTo;
    }
    /**
     * Set sSalaryTo value
     * @param string $_sSalaryTo the sSalaryTo
     * @return string
     */
    public function setSSalaryTo($_sSalaryTo)
    {
        return ($this->sSalaryTo = $_sSalaryTo);
    }
    /**
     * Get sSalaryCurrency value
     * @return string|null
     */
    public function getSSalaryCurrency()
    {
        return $this->sSalaryCurrency;
    }
    /**
     * Set sSalaryCurrency value
     * @param string $_sSalaryCurrency the sSalaryCurrency
     * @return string
     */
    public function setSSalaryCurrency($_sSalaryCurrency)
    {
        return ($this->sSalaryCurrency = $_sSalaryCurrency);
    }
    /**
     * Get sSalaryPer value
     * @return string|null
     */
    public function getSSalaryPer()
    {
        return $this->sSalaryPer;
    }
    /**
     * Set sSalaryPer value
     * @param string $_sSalaryPer the sSalaryPer
     * @return string
     */
    public function setSSalaryPer($_sSalaryPer)
    {
        return ($this->sSalaryPer = $_sSalaryPer);
    }
    /**
     * Get sSalaryBenefits value
     * @return string|null
     */
    public function getSSalaryBenefits()
    {
        return $this->sSalaryBenefits;
    }
    /**
     * Set sSalaryBenefits value
     * @param string $_sSalaryBenefits the sSalaryBenefits
     * @return string
     */
    public function setSSalaryBenefits($_sSalaryBenefits)
    {
        return ($this->sSalaryBenefits = $_sSalaryBenefits);
    }
    /**
     * Get sContactName value
     * @return string|null
     */
    public function getSContactName()
    {
        return $this->sContactName;
    }
    /**
     * Set sContactName value
     * @param string $_sContactName the sContactName
     * @return string
     */
    public function setSContactName($_sContactName)
    {
        return ($this->sContactName = $_sContactName);
    }
    /**
     * Get sContactEmail value
     * @return string|null
     */
    public function getSContactEmail()
    {
        return $this->sContactEmail;
    }
    /**
     * Set sContactEmail value
     * @param string $_sContactEmail the sContactEmail
     * @return string
     */
    public function setSContactEmail($_sContactEmail)
    {
        return ($this->sContactEmail = $_sContactEmail);
    }
    /**
     * Get sJobDescription value
     * @return string|null
     */
    public function getSJobDescription()
    {
        return $this->sJobDescription;
    }
    /**
     * Set sJobDescription value
     * @param string $_sJobDescription the sJobDescription
     * @return string
     */
    public function setSJobDescription($_sJobDescription)
    {
        return ($this->sJobDescription = $_sJobDescription);
    }
    /**
     * Get sApplicationURL value
     * @return string|null
     */
    public function getSApplicationURL()
    {
        return $this->sApplicationURL;
    }
    /**
     * Set sApplicationURL value
     * @param string $_sApplicationURL the sApplicationURL
     * @return string
     */
    public function setSApplicationURL($_sApplicationURL)
    {
        return ($this->sApplicationURL = $_sApplicationURL);
    }
    /**
     * Get DestinationsAsCSV value
     * @return string|null
     */
    public function getDestinationsAsCSV()
    {
        return $this->DestinationsAsCSV;
    }
    /**
     * Set DestinationsAsCSV value
     * @param string $_destinationsAsCSV the DestinationsAsCSV
     * @return string
     */
    public function setDestinationsAsCSV($_destinationsAsCSV)
    {
        return ($this->DestinationsAsCSV = $_destinationsAsCSV);
    }
    /**
     * Get sFuturePostDateTimeInUtc value
     * @return string|null
     */
    public function getSFuturePostDateTimeInUtc()
    {
        return $this->sFuturePostDateTimeInUtc;
    }
    /**
     * Set sFuturePostDateTimeInUtc value
     * @param string $_sFuturePostDateTimeInUtc the sFuturePostDateTimeInUtc
     * @return string
     */
    public function setSFuturePostDateTimeInUtc($_sFuturePostDateTimeInUtc)
    {
        return ($this->sFuturePostDateTimeInUtc = $_sFuturePostDateTimeInUtc);
    }
    /**
     * Get sRedirectDomain value
     * @return string|null
     */
    public function getSRedirectDomain()
    {
        return $this->sRedirectDomain;
    }
    /**
     * Set sRedirectDomain value
     * @param string $_sRedirectDomain the sRedirectDomain
     * @return string
     */
    public function setSRedirectDomain($_sRedirectDomain)
    {
        return ($this->sRedirectDomain = $_sRedirectDomain);
    }
    /**
     * Get sAdvertStatusID value
     * @return string|null
     */
    public function getSAdvertStatusID()
    {
        return $this->sAdvertStatusID;
    }
    /**
     * Set sAdvertStatusID value
     * @param string $_sAdvertStatusID the sAdvertStatusID
     * @return string
     */
    public function setSAdvertStatusID($_sAdvertStatusID)
    {
        return ($this->sAdvertStatusID = $_sAdvertStatusID);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAddAdvert
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
