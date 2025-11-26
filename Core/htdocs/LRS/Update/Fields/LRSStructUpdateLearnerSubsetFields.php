<?php
/**
 * File for class LRSStructUpdateLearnerSubsetFields
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructUpdateLearnerSubsetFields originally named UpdateLearnerSubsetFields
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructUpdateLearnerSubsetFields extends LRSWsdlClass
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
     * The versionNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var int
     */
    public $versionNumber;
    /**
     * The emailAddress
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $emailAddress;
    /**
     * The telephoneNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $telephoneNumber;
    /**
     * The abilityToShare
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * @var LRSEnumAbilityToShare
     */
    public $abilityToShare;
    /**
     * The schoolAtAge16
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $schoolAtAge16;
    /**
     * The preferredGivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 1
     * - nillable : true
     * @var string
     */
    public $preferredGivenName;
    /**
     * Constructor method for UpdateLearnerSubsetFields
     * @see parent::__construct()
     * @param LRSStructInvokingOrganisation $_invokingOrganisation
     * @param string $_userType
     * @param int $_vendorId
     * @param string $_language
     * @param string $_uln
     * @param int $_versionNumber
     * @param string $_emailAddress
     * @param string $_telephoneNumber
     * @param LRSEnumAbilityToShare $_abilityToShare
     * @param string $_schoolAtAge16
     * @param string $_preferredGivenName
     * @return LRSStructUpdateLearnerSubsetFields
     */
    public function __construct($_invokingOrganisation,$_userType,$_vendorId,$_language,$_uln,$_versionNumber,$_emailAddress,$_telephoneNumber,$_abilityToShare,$_schoolAtAge16,$_preferredGivenName)
    {
        parent::__construct(array('invokingOrganisation'=>$_invokingOrganisation,'userType'=>$_userType,'vendorId'=>$_vendorId,'language'=>$_language,'uln'=>$_uln,'versionNumber'=>$_versionNumber,'emailAddress'=>$_emailAddress,'telephoneNumber'=>$_telephoneNumber,'abilityToShare'=>$_abilityToShare,'schoolAtAge16'=>$_schoolAtAge16,'preferredGivenName'=>$_preferredGivenName),false);
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
     * Get versionNumber value
     * @return int
     */
    public function getVersionNumber()
    {
        return $this->versionNumber;
    }
    /**
     * Set versionNumber value
     * @param int $_versionNumber the versionNumber
     * @return int
     */
    public function setVersionNumber($_versionNumber)
    {
        return ($this->versionNumber = $_versionNumber);
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
     * Get telephoneNumber value
     * @return string
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }
    /**
     * Set telephoneNumber value
     * @param string $_telephoneNumber the telephoneNumber
     * @return string
     */
    public function setTelephoneNumber($_telephoneNumber)
    {
        return ($this->telephoneNumber = $_telephoneNumber);
    }
    /**
     * Get abilityToShare value
     * @return LRSEnumAbilityToShare
     */
    public function getAbilityToShare()
    {
        return $this->abilityToShare;
    }
    /**
     * Set abilityToShare value
     * @uses LRSEnumAbilityToShare::valueIsValid()
     * @param LRSEnumAbilityToShare $_abilityToShare the abilityToShare
     * @return LRSEnumAbilityToShare
     */
    public function setAbilityToShare($_abilityToShare)
    {
        if(!LRSEnumAbilityToShare::valueIsValid($_abilityToShare))
        {
            return false;
        }
        return ($this->abilityToShare = $_abilityToShare);
    }
    /**
     * Get schoolAtAge16 value
     * @return string
     */
    public function getSchoolAtAge16()
    {
        return $this->schoolAtAge16;
    }
    /**
     * Set schoolAtAge16 value
     * @param string $_schoolAtAge16 the schoolAtAge16
     * @return string
     */
    public function setSchoolAtAge16($_schoolAtAge16)
    {
        return ($this->schoolAtAge16 = $_schoolAtAge16);
    }
    /**
     * Get preferredGivenName value
     * @return string
     */
    public function getPreferredGivenName()
    {
        return $this->preferredGivenName;
    }
    /**
     * Set preferredGivenName value
     * @param string $_preferredGivenName the preferredGivenName
     * @return string
     */
    public function setPreferredGivenName($_preferredGivenName)
    {
        return ($this->preferredGivenName = $_preferredGivenName);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructUpdateLearnerSubsetFields
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
