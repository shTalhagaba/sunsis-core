<?php
/**
 * File for class LRSStructGroupBase
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructGroupBase originally named GroupBase
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructGroupBase extends LRSStructReplicatedBusinessObject
{
    /**
     * The Description
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Description;
    /**
     * The GetCalcEffectiveMinCredits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $GetCalcEffectiveMinCredits;
    /**
     * The GetCalcEffectiveMinSubcomponents
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $GetCalcEffectiveMinSubcomponents;
    /**
     * The Label
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Label;
    /**
     * The MaxCreditValue
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $MaxCreditValue;
    /**
     * The MaxSubcomponents
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $MaxSubcomponents;
    /**
     * The MinCreditValue
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $MinCreditValue;
    /**
     * The MinSubcomponents
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $MinSubcomponents;
    /**
     * The Name
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Name;
    /**
     * The WithinParent
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $WithinParent;
    /**
     * Constructor method for GroupBase
     * @see parent::__construct()
     * @param string $_description
     * @param int $_getCalcEffectiveMinCredits
     * @param int $_getCalcEffectiveMinSubcomponents
     * @param string $_label
     * @param int $_maxCreditValue
     * @param int $_maxSubcomponents
     * @param int $_minCreditValue
     * @param int $_minSubcomponents
     * @param string $_name
     * @param boolean $_withinParent
     * @return LRSStructGroupBase
     */
    public function __construct($_description = NULL,$_getCalcEffectiveMinCredits = NULL,$_getCalcEffectiveMinSubcomponents = NULL,$_label = NULL,$_maxCreditValue = NULL,$_maxSubcomponents = NULL,$_minCreditValue = NULL,$_minSubcomponents = NULL,$_name = NULL,$_withinParent = NULL)
    {
        LRSWsdlClass::__construct(array('Description'=>$_description,'GetCalcEffectiveMinCredits'=>$_getCalcEffectiveMinCredits,'GetCalcEffectiveMinSubcomponents'=>$_getCalcEffectiveMinSubcomponents,'Label'=>$_label,'MaxCreditValue'=>$_maxCreditValue,'MaxSubcomponents'=>$_maxSubcomponents,'MinCreditValue'=>$_minCreditValue,'MinSubcomponents'=>$_minSubcomponents,'Name'=>$_name,'WithinParent'=>$_withinParent),false);
    }
    /**
     * Get Description value
     * @return string|null
     */
    public function getDescription()
    {
        return $this->Description;
    }
    /**
     * Set Description value
     * @param string $_description the Description
     * @return string
     */
    public function setDescription($_description)
    {
        return ($this->Description = $_description);
    }
    /**
     * Get GetCalcEffectiveMinCredits value
     * @return int|null
     */
    public function getGetCalcEffectiveMinCredits()
    {
        return $this->GetCalcEffectiveMinCredits;
    }
    /**
     * Set GetCalcEffectiveMinCredits value
     * @param int $_getCalcEffectiveMinCredits the GetCalcEffectiveMinCredits
     * @return int
     */
    public function setGetCalcEffectiveMinCredits($_getCalcEffectiveMinCredits)
    {
        return ($this->GetCalcEffectiveMinCredits = $_getCalcEffectiveMinCredits);
    }
    /**
     * Get GetCalcEffectiveMinSubcomponents value
     * @return int|null
     */
    public function getGetCalcEffectiveMinSubcomponents()
    {
        return $this->GetCalcEffectiveMinSubcomponents;
    }
    /**
     * Set GetCalcEffectiveMinSubcomponents value
     * @param int $_getCalcEffectiveMinSubcomponents the GetCalcEffectiveMinSubcomponents
     * @return int
     */
    public function setGetCalcEffectiveMinSubcomponents($_getCalcEffectiveMinSubcomponents)
    {
        return ($this->GetCalcEffectiveMinSubcomponents = $_getCalcEffectiveMinSubcomponents);
    }
    /**
     * Get Label value
     * @return string|null
     */
    public function getLabel()
    {
        return $this->Label;
    }
    /**
     * Set Label value
     * @param string $_label the Label
     * @return string
     */
    public function setLabel($_label)
    {
        return ($this->Label = $_label);
    }
    /**
     * Get MaxCreditValue value
     * @return int|null
     */
    public function getMaxCreditValue()
    {
        return $this->MaxCreditValue;
    }
    /**
     * Set MaxCreditValue value
     * @param int $_maxCreditValue the MaxCreditValue
     * @return int
     */
    public function setMaxCreditValue($_maxCreditValue)
    {
        return ($this->MaxCreditValue = $_maxCreditValue);
    }
    /**
     * Get MaxSubcomponents value
     * @return int|null
     */
    public function getMaxSubcomponents()
    {
        return $this->MaxSubcomponents;
    }
    /**
     * Set MaxSubcomponents value
     * @param int $_maxSubcomponents the MaxSubcomponents
     * @return int
     */
    public function setMaxSubcomponents($_maxSubcomponents)
    {
        return ($this->MaxSubcomponents = $_maxSubcomponents);
    }
    /**
     * Get MinCreditValue value
     * @return int|null
     */
    public function getMinCreditValue()
    {
        return $this->MinCreditValue;
    }
    /**
     * Set MinCreditValue value
     * @param int $_minCreditValue the MinCreditValue
     * @return int
     */
    public function setMinCreditValue($_minCreditValue)
    {
        return ($this->MinCreditValue = $_minCreditValue);
    }
    /**
     * Get MinSubcomponents value
     * @return int|null
     */
    public function getMinSubcomponents()
    {
        return $this->MinSubcomponents;
    }
    /**
     * Set MinSubcomponents value
     * @param int $_minSubcomponents the MinSubcomponents
     * @return int
     */
    public function setMinSubcomponents($_minSubcomponents)
    {
        return ($this->MinSubcomponents = $_minSubcomponents);
    }
    /**
     * Get Name value
     * @return string|null
     */
    public function getName()
    {
        return $this->Name;
    }
    /**
     * Set Name value
     * @param string $_name the Name
     * @return string
     */
    public function setName($_name)
    {
        return ($this->Name = $_name);
    }
    /**
     * Get WithinParent value
     * @return boolean|null
     */
    public function getWithinParent()
    {
        return $this->WithinParent;
    }
    /**
     * Set WithinParent value
     * @param boolean $_withinParent the WithinParent
     * @return boolean
     */
    public function setWithinParent($_withinParent)
    {
        return ($this->WithinParent = $_withinParent);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructGroupBase
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
