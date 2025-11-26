<?php
/**
 * File for class LRSStructListSnapshots
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructListSnapshots originally named ListSnapshots
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructListSnapshots extends LRSWsdlClass
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
     * The vendorId
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $vendorId;
    /**
     * The userType
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $userType;
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
     * The dateCreatedStart
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $dateCreatedStart;
    /**
     * The dateCreatedEnd
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var dateTime
     */
    public $dateCreatedEnd;
    /**
     * The guid
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - pattern : [\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}
     * @var string
     */
    public $guid;
    /**
     * Constructor method for ListSnapshots
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param int $_vendorId
     * @param string $_userType
     * @param string $_language
     * @param string $_learnerUln
     * @param dateTime $_dateCreatedStart
     * @param dateTime $_dateCreatedEnd
     * @param string $_guid
     * @return LRSStructListSnapshots
     */
    public function __construct($_invokingOrganisation,$_vendorId,$_userType,$_language,$_learnerUln,$_dateCreatedStart,$_dateCreatedEnd,$_guid)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'vendorId'=>$_vendorId,'userType'=>$_userType,'language'=>$_language,'learnerUln'=>$_learnerUln,'dateCreatedStart'=>$_dateCreatedStart,'dateCreatedEnd'=>$_dateCreatedEnd,'guid'=>$_guid),false);
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
     * Get dateCreatedStart value
     * @return dateTime
     */
    public function getDateCreatedStart()
    {
        return $this->dateCreatedStart;
    }
    /**
     * Set dateCreatedStart value
     * @param dateTime $_dateCreatedStart the dateCreatedStart
     * @return dateTime
     */
    public function setDateCreatedStart($_dateCreatedStart)
    {
        return ($this->dateCreatedStart = $_dateCreatedStart);
    }
    /**
     * Get dateCreatedEnd value
     * @return dateTime
     */
    public function getDateCreatedEnd()
    {
        return $this->dateCreatedEnd;
    }
    /**
     * Set dateCreatedEnd value
     * @param dateTime $_dateCreatedEnd the dateCreatedEnd
     * @return dateTime
     */
    public function setDateCreatedEnd($_dateCreatedEnd)
    {
        return ($this->dateCreatedEnd = $_dateCreatedEnd);
    }
    /**
     * Get guid value
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }
    /**
     * Set guid value
     * @param string $_guid the guid
     * @return string
     */
    public function setGuid($_guid)
    {
        return ($this->guid = $_guid);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructListSnapshots
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
