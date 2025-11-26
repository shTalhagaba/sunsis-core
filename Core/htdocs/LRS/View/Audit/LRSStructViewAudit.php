<?php
/**
 * File for class LRSStructViewAudit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructViewAudit originally named ViewAudit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructViewAudit extends LRSWsdlClass
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
     * The learnerUln
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $learnerUln;
    /**
     * The learnerGivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $learnerGivenName;
    /**
     * The learnerFamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $learnerFamilyName;
    /**
     * The page
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $page;
    /**
     * Constructor method for ViewAudit
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param string $_userType
     * @param int $_vendorId
     * @param string $_language
     * @param string $_learnerUln
     * @param string $_learnerGivenName
     * @param string $_learnerFamilyName
     * @param int $_page
     * @return LRSStructViewAudit
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorId,$_language,$_learnerUln,$_learnerGivenName,$_learnerFamilyName,$_page)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorId'=>$_vendorId,'language'=>$_language,'learnerUln'=>$_learnerUln,'learnerGivenName'=>$_learnerGivenName,'learnerFamilyName'=>$_learnerFamilyName,'page'=>$_page),false);
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
     * Get learnerUln value
     * @return string
     */
    public function getLearnerUln()
    {
        return $this->learnerUln;
    }
    /**
     * Set learnerUln value
     * @param string $_learnerUln the learnerUln
     * @return string
     */
    public function setLearnerUln($_learnerUln)
    {
        return ($this->learnerUln = $_learnerUln);
    }
    /**
     * Get learnerGivenName value
     * @return string
     */
    public function getLearnerGivenName()
    {
        return $this->learnerGivenName;
    }
    /**
     * Set learnerGivenName value
     * @param string $_learnerGivenName the learnerGivenName
     * @return string
     */
    public function setLearnerGivenName($_learnerGivenName)
    {
        return ($this->learnerGivenName = $_learnerGivenName);
    }
    /**
     * Get learnerFamilyName value
     * @return string
     */
    public function getLearnerFamilyName()
    {
        return $this->learnerFamilyName;
    }
    /**
     * Set learnerFamilyName value
     * @param string $_learnerFamilyName the learnerFamilyName
     * @return string
     */
    public function setLearnerFamilyName($_learnerFamilyName)
    {
        return ($this->learnerFamilyName = $_learnerFamilyName);
    }
    /**
     * Get page value
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }
    /**
     * Set page value
     * @param int $_page the page
     * @return int
     */
    public function setPage($_page)
    {
        return ($this->page = $_page);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructViewAudit
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
