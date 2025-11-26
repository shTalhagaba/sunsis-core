<?php
/**
 * File for class LRSStructGetOrganisation
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructGetOrganisation originally named GetOrganisation
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructGetOrganisation extends LRSWsdlClass
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
     * @var LRSEnumUserType
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
     * The ukPrn
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $ukPrn;
    /**
     * The orgRef
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $orgRef;
    /**
     * Constructor method for GetOrganisation
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param LRSEnumUserType $_userType
     * @param int $_vendorId
     * @param string $_language
     * @param string $_ukPrn
     * @param string $_orgRef
     * @return LRSStructGetOrganisation
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorId,$_language,$_ukPrn,$_orgRef)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorId'=>$_vendorId,'language'=>$_language,'ukPrn'=>$_ukPrn,'orgRef'=>$_orgRef),false);
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
     * @return LRSEnumUserType
     */
    public function getUserType()
    {
        return $this->userType;
    }
    /**
     * Set userType value
     * @uses LRSEnumUserType::valueIsValid()
     * @param LRSEnumUserType $_userType the userType
     * @return LRSEnumUserType
     */
    public function setUserType($_userType)
    {
        if(!LRSEnumUserType::valueIsValid($_userType))
        {
            return false;
        }
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
     * Get ukPrn value
     * @return string
     */
    public function getUkPrn()
    {
        return $this->ukPrn;
    }
    /**
     * Set ukPrn value
     * @param string $_ukPrn the ukPrn
     * @return string
     */
    public function setUkPrn($_ukPrn)
    {
        return ($this->ukPrn = $_ukPrn);
    }
    /**
     * Get orgRef value
     * @return string
     */
    public function getOrgRef()
    {
        return $this->orgRef;
    }
    /**
     * Set orgRef value
     * @param string $_orgRef the orgRef
     * @return string
     */
    public function setOrgRef($_orgRef)
    {
        return ($this->orgRef = $_orgRef);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructGetOrganisation
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
