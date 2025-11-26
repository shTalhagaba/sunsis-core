<?php
/**
 * File for class LRSStructInvokingOrganisationR10
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructInvokingOrganisationR10 originally named InvokingOrganisationR10
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/Amor.Qcf.Service.Interface.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructInvokingOrganisationR10 extends LRSWsdlClass
{
    /**
     * The OrganisationRef
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $OrganisationRef;
    /**
     * The Password
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Password;
    /**
     * The Ukprn
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Ukprn;
    /**
     * The Username
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Username;
    /**
     * Constructor method for InvokingOrganisationR10
     * @see parent::__construct()
     * @param string $_organisationRef
     * @param string $_password
     * @param string $_ukprn
     * @param string $_username
     * @return LRSStructInvokingOrganisationR10
     */
    public function __construct($_organisationRef = NULL,$_password = NULL,$_ukprn = NULL,$_username = NULL)
    {
        parent::__construct(array('OrganisationRef'=>$_organisationRef,'Password'=>$_password,'Ukprn'=>$_ukprn,'Username'=>$_username),false);
    }
    /**
     * Get OrganisationRef value
     * @return string|null
     */
    public function getOrganisationRef()
    {
        return $this->OrganisationRef;
    }
    /**
     * Set OrganisationRef value
     * @param string $_organisationRef the OrganisationRef
     * @return string
     */
    public function setOrganisationRef($_organisationRef)
    {
        return ($this->OrganisationRef = $_organisationRef);
    }
    /**
     * Get Password value
     * @return string|null
     */
    public function getPassword()
    {
        return $this->Password;
    }
    /**
     * Set Password value
     * @param string $_password the Password
     * @return string
     */
    public function setPassword($_password)
    {
        return ($this->Password = $_password);
    }
    /**
     * Get Ukprn value
     * @return string|null
     */
    public function getUkprn()
    {
        return $this->Ukprn;
    }
    /**
     * Set Ukprn value
     * @param string $_ukprn the Ukprn
     * @return string
     */
    public function setUkprn($_ukprn)
    {
        return ($this->Ukprn = $_ukprn);
    }
    /**
     * Get Username value
     * @return string|null
     */
    public function getUsername()
    {
        return $this->Username;
    }
    /**
     * Set Username value
     * @param string $_username the Username
     * @return string
     */
    public function setUsername($_username)
    {
        return ($this->Username = $_username);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructInvokingOrganisationR10
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
