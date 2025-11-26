<?php
/**
 * File for class LRSStructGetOrganisationResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructGetOrganisationResponse originally named GetOrganisationResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructGetOrganisationResponse extends LRSStructServiceResponseR9
{
    /**
     * The DisplayName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DisplayName;
    /**
     * The OrgRef
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $OrgRef;
    /**
     * The Ukprn
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Ukprn;
    /**
     * The GetOrganisationResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $GetOrganisationResult;
    /**
     * Constructor method for GetOrganisationResponse
     * @see parent::__construct()
     * @param string $_displayName
     * @param string $_orgRef
     * @param string $_ukprn
     * @param ServiceResponseR9 $_getOrganisationResult
     * @return LRSStructGetOrganisationResponse
     */
    public function __construct($_displayName = NULL,$_orgRef = NULL,$_ukprn = NULL,$_getOrganisationResult = NULL)
    {
        LRSWsdlClass::__construct(array('DisplayName'=>$_displayName,'OrgRef'=>$_orgRef,'Ukprn'=>$_ukprn,'GetOrganisationResult'=>$_getOrganisationResult),false);
    }
    /**
     * Get DisplayName value
     * @return string|null
     */
    public function getDisplayName()
    {
        return $this->DisplayName;
    }
    /**
     * Set DisplayName value
     * @param string $_displayName the DisplayName
     * @return string
     */
    public function setDisplayName($_displayName)
    {
        return ($this->DisplayName = $_displayName);
    }
    /**
     * Get OrgRef value
     * @return string|null
     */
    public function getOrgRef()
    {
        return $this->OrgRef;
    }
    /**
     * Set OrgRef value
     * @param string $_orgRef the OrgRef
     * @return string
     */
    public function setOrgRef($_orgRef)
    {
        return ($this->OrgRef = $_orgRef);
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
     * Get GetOrganisationResult value
     * @return ServiceResponseR9|null
     */
    public function getGetOrganisationResult()
    {
        return $this->GetOrganisationResult;
    }
    /**
     * Set GetOrganisationResult value
     * @param ServiceResponseR9 $_getOrganisationResult the GetOrganisationResult
     * @return ServiceResponseR9
     */
    public function setGetOrganisationResult($_getOrganisationResult)
    {
        return ($this->GetOrganisationResult = $_getOrganisationResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructGetOrganisationResponse
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
