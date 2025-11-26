<?php
/**
 * File for class LRSStructSaveRtaQuery
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructSaveRtaQuery originally named SaveRtaQuery
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructSaveRtaQuery extends LRSWsdlClass
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
     * The gender
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $gender;
    /**
     * The dateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $dateOfBirth;
    /**
     * The lastKnownPostcode
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $lastKnownPostcode;
    /**
     * The saveName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $saveName;
    /**
     * The accreditationNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $accreditationNumber;
    /**
     * The keyword
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $keyword;
    /**
     * The level
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $level;
    /**
     * The targetPostCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $targetPostCode;
    /**
     * The extraUnitsForCreditTowards
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var LRSStructArrayOfstring
     */
    public $extraUnitsForCreditTowards;
    /**
     * The includeLearnerUnits
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var boolean
     */
    public $includeLearnerUnits;
    /**
     * The ssas
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var LRSStructArrayOfstring
     */
    public $ssas;
    /**
     * The computeCreditsTowards
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var boolean
     */
    public $computeCreditsTowards;
    /**
     * The offeredInEngland
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var boolean
     */
    public $offeredInEngland;
    /**
     * The offeredInNorthernIreland
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var boolean
     */
    public $offeredInNorthernIreland;
    /**
     * The offeredInWales
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var boolean
     */
    public $offeredInWales;
    /**
     * The structureMustContain
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var LRSStructArrayOfstring
     */
    public $structureMustContain;
    /**
     * The size
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $size;
    /**
     * Constructor method for SaveRtaQuery
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param string $_userType
     * @param int $_vendorId
     * @param string $_language
     * @param string $_uln
     * @param string $_givenName
     * @param string $_familyName
     * @param string $_gender
     * @param dateTime $_dateOfBirth
     * @param string $_lastKnownPostcode
     * @param string $_saveName
     * @param string $_accreditationNumber
     * @param string $_keyword
     * @param string $_level
     * @param string $_targetPostCode
     * @param LRSStructArrayOfstring $_extraUnitsForCreditTowards
     * @param boolean $_includeLearnerUnits
     * @param LRSStructArrayOfstring $_ssas
     * @param boolean $_computeCreditsTowards
     * @param boolean $_offeredInEngland
     * @param boolean $_offeredInNorthernIreland
     * @param boolean $_offeredInWales
     * @param LRSStructArrayOfstring $_structureMustContain
     * @param string $_size
     * @return LRSStructSaveRtaQuery
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorId,$_language,$_uln,$_givenName,$_familyName,$_gender,$_dateOfBirth,$_lastKnownPostcode,$_saveName,$_accreditationNumber,$_keyword,$_level,$_targetPostCode,$_extraUnitsForCreditTowards,$_includeLearnerUnits,$_ssas,$_computeCreditsTowards,$_offeredInEngland,$_offeredInNorthernIreland,$_offeredInWales,$_structureMustContain,$_size)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorId'=>$_vendorId,'language'=>$_language,'uln'=>$_uln,'givenName'=>$_givenName,'familyName'=>$_familyName,'gender'=>$_gender,'dateOfBirth'=>$_dateOfBirth,'lastKnownPostcode'=>$_lastKnownPostcode,'saveName'=>$_saveName,'accreditationNumber'=>$_accreditationNumber,'keyword'=>$_keyword,'level'=>$_level,'targetPostCode'=>$_targetPostCode,'extraUnitsForCreditTowards'=>($_extraUnitsForCreditTowards instanceof LRSStructArrayOfstring)?$_extraUnitsForCreditTowards:new LRSStructArrayOfstring($_extraUnitsForCreditTowards),'includeLearnerUnits'=>$_includeLearnerUnits,'ssas'=>($_ssas instanceof LRSStructArrayOfstring)?$_ssas:new LRSStructArrayOfstring($_ssas),'computeCreditsTowards'=>$_computeCreditsTowards,'offeredInEngland'=>$_offeredInEngland,'offeredInNorthernIreland'=>$_offeredInNorthernIreland,'offeredInWales'=>$_offeredInWales,'structureMustContain'=>($_structureMustContain instanceof LRSStructArrayOfstring)?$_structureMustContain:new LRSStructArrayOfstring($_structureMustContain),'size'=>$_size),false);
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
     * Get lastKnownPostcode value
     * @return string
     */
    public function getLastKnownPostcode()
    {
        return $this->lastKnownPostcode;
    }
    /**
     * Set lastKnownPostcode value
     * @param string $_lastKnownPostcode the lastKnownPostcode
     * @return string
     */
    public function setLastKnownPostcode($_lastKnownPostcode)
    {
        return ($this->lastKnownPostcode = $_lastKnownPostcode);
    }
    /**
     * Get saveName value
     * @return string
     */
    public function getSaveName()
    {
        return $this->saveName;
    }
    /**
     * Set saveName value
     * @param string $_saveName the saveName
     * @return string
     */
    public function setSaveName($_saveName)
    {
        return ($this->saveName = $_saveName);
    }
    /**
     * Get accreditationNumber value
     * @return string
     */
    public function getAccreditationNumber()
    {
        return $this->accreditationNumber;
    }
    /**
     * Set accreditationNumber value
     * @param string $_accreditationNumber the accreditationNumber
     * @return string
     */
    public function setAccreditationNumber($_accreditationNumber)
    {
        return ($this->accreditationNumber = $_accreditationNumber);
    }
    /**
     * Get keyword value
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }
    /**
     * Set keyword value
     * @param string $_keyword the keyword
     * @return string
     */
    public function setKeyword($_keyword)
    {
        return ($this->keyword = $_keyword);
    }
    /**
     * Get level value
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }
    /**
     * Set level value
     * @param string $_level the level
     * @return string
     */
    public function setLevel($_level)
    {
        return ($this->level = $_level);
    }
    /**
     * Get targetPostCode value
     * @return string
     */
    public function getTargetPostCode()
    {
        return $this->targetPostCode;
    }
    /**
     * Set targetPostCode value
     * @param string $_targetPostCode the targetPostCode
     * @return string
     */
    public function setTargetPostCode($_targetPostCode)
    {
        return ($this->targetPostCode = $_targetPostCode);
    }
    /**
     * Get extraUnitsForCreditTowards value
     * @return LRSStructArrayOfstring
     */
    public function getExtraUnitsForCreditTowards()
    {
        return $this->extraUnitsForCreditTowards;
    }
    /**
     * Set extraUnitsForCreditTowards value
     * @param LRSStructArrayOfstring $_extraUnitsForCreditTowards the extraUnitsForCreditTowards
     * @return LRSStructArrayOfstring
     */
    public function setExtraUnitsForCreditTowards($_extraUnitsForCreditTowards)
    {
        return ($this->extraUnitsForCreditTowards = $_extraUnitsForCreditTowards);
    }
    /**
     * Get includeLearnerUnits value
     * @return boolean
     */
    public function getIncludeLearnerUnits()
    {
        return $this->includeLearnerUnits;
    }
    /**
     * Set includeLearnerUnits value
     * @param boolean $_includeLearnerUnits the includeLearnerUnits
     * @return boolean
     */
    public function setIncludeLearnerUnits($_includeLearnerUnits)
    {
        return ($this->includeLearnerUnits = $_includeLearnerUnits);
    }
    /**
     * Get ssas value
     * @return LRSStructArrayOfstring
     */
    public function getSsas()
    {
        return $this->ssas;
    }
    /**
     * Set ssas value
     * @param LRSStructArrayOfstring $_ssas the ssas
     * @return LRSStructArrayOfstring
     */
    public function setSsas($_ssas)
    {
        return ($this->ssas = $_ssas);
    }
    /**
     * Get computeCreditsTowards value
     * @return boolean
     */
    public function getComputeCreditsTowards()
    {
        return $this->computeCreditsTowards;
    }
    /**
     * Set computeCreditsTowards value
     * @param boolean $_computeCreditsTowards the computeCreditsTowards
     * @return boolean
     */
    public function setComputeCreditsTowards($_computeCreditsTowards)
    {
        return ($this->computeCreditsTowards = $_computeCreditsTowards);
    }
    /**
     * Get offeredInEngland value
     * @return boolean
     */
    public function getOfferedInEngland()
    {
        return $this->offeredInEngland;
    }
    /**
     * Set offeredInEngland value
     * @param boolean $_offeredInEngland the offeredInEngland
     * @return boolean
     */
    public function setOfferedInEngland($_offeredInEngland)
    {
        return ($this->offeredInEngland = $_offeredInEngland);
    }
    /**
     * Get offeredInNorthernIreland value
     * @return boolean
     */
    public function getOfferedInNorthernIreland()
    {
        return $this->offeredInNorthernIreland;
    }
    /**
     * Set offeredInNorthernIreland value
     * @param boolean $_offeredInNorthernIreland the offeredInNorthernIreland
     * @return boolean
     */
    public function setOfferedInNorthernIreland($_offeredInNorthernIreland)
    {
        return ($this->offeredInNorthernIreland = $_offeredInNorthernIreland);
    }
    /**
     * Get offeredInWales value
     * @return boolean
     */
    public function getOfferedInWales()
    {
        return $this->offeredInWales;
    }
    /**
     * Set offeredInWales value
     * @param boolean $_offeredInWales the offeredInWales
     * @return boolean
     */
    public function setOfferedInWales($_offeredInWales)
    {
        return ($this->offeredInWales = $_offeredInWales);
    }
    /**
     * Get structureMustContain value
     * @return LRSStructArrayOfstring
     */
    public function getStructureMustContain()
    {
        return $this->structureMustContain;
    }
    /**
     * Set structureMustContain value
     * @param LRSStructArrayOfstring $_structureMustContain the structureMustContain
     * @return LRSStructArrayOfstring
     */
    public function setStructureMustContain($_structureMustContain)
    {
        return ($this->structureMustContain = $_structureMustContain);
    }
    /**
     * Get size value
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }
    /**
     * Set size value
     * @param string $_size the size
     * @return string
     */
    public function setSize($_size)
    {
        return ($this->size = $_size);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructSaveRtaQuery
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
