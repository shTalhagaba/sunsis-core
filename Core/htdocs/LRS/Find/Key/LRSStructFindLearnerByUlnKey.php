<?php
/**
 * File for class LRSStructFindLearnerByUlnKey
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructFindLearnerByUlnKey originally named FindLearnerByUlnKey
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructFindLearnerByUlnKey extends LRSWsdlClass
{
    /**
     * The invokingOrganisation
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var LRSStructInvokingOrganisationR10
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
     * The vendorID
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $vendorID;
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
     * The keyType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $keyType;
    /**
     * The keyValue
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $keyValue;
    /**
     * The findType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $findType;
    /**
     * Constructor method for FindLearnerByUlnKey
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisationR10 $_invokingOrganisation
     * @param string $_userType
     * @param int $_vendorID
     * @param string $_language
     * @param string $_uln
     * @param string $_keyType
     * @param string $_keyValue
     * @param string $_findType
     * @return LRSStructFindLearnerByUlnKey
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorID,$_language,$_uln,$_keyType,$_keyValue,$_findType)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorID'=>$_vendorID,'language'=>$_language,'uln'=>$_uln,'keyType'=>$_keyType,'keyValue'=>$_keyValue,'findType'=>$_findType),false);
    }
    /**
     * Get invokingOrganisation value
     * @return LRSStructInvokingOrganisationR10
     */
    public function getInvokingOrganisation()
    {
        return $this->invokingOrganisation;
    }
    /**
     * Set invokingOrganisation value
     * @param LRSStructInvokingOrganisationR10 $_invokingOrganisation the invokingOrganisation
     * @return LRSStructInvokingOrganisationR10
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
     * Get vendorID value
     * @return int
     */
    public function getVendorID()
    {
        return $this->vendorID;
    }
    /**
     * Set vendorID value
     * @param int $_vendorID the vendorID
     * @return int
     */
    public function setVendorID($_vendorID)
    {
        return ($this->vendorID = $_vendorID);
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
     * Get keyType value
     * @return string
     */
    public function getKeyType()
    {
        return $this->keyType;
    }
    /**
     * Set keyType value
     * @param string $_keyType the keyType
     * @return string
     */
    public function setKeyType($_keyType)
    {
        return ($this->keyType = $_keyType);
    }
    /**
     * Get keyValue value
     * @return string
     */
    public function getKeyValue()
    {
        return $this->keyValue;
    }
    /**
     * Set keyValue value
     * @param string $_keyValue the keyValue
     * @return string
     */
    public function setKeyValue($_keyValue)
    {
        return ($this->keyValue = $_keyValue);
    }
    /**
     * Get findType value
     * @return string
     */
    public function getFindType()
    {
        return $this->findType;
    }
    /**
     * Set findType value
     * @param string $_findType the findType
     * @return string
     */
    public function setFindType($_findType)
    {
        return ($this->findType = $_findType);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructFindLearnerByUlnKey
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
