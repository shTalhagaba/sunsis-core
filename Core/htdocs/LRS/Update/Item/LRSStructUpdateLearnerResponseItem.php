<?php
/**
 * File for class LRSStructUpdateLearnerResponseItem
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructUpdateLearnerResponseItem originally named UpdateLearnerResponseItem
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructUpdateLearnerResponseItem extends LRSWsdlClass
{
    /**
     * The FieldCodes
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfstring
     */
    public $FieldCodes;
    /**
     * The ResponseCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ResponseCode;
    /**
     * Constructor method for UpdateLearnerResponseItem
     * @see parent::__construct()
     * @param LRSStructArrayOfstring $_fieldCodes
     * @param string $_responseCode
     * @return LRSStructUpdateLearnerResponseItem
     */
    public function __construct($_fieldCodes = NULL,$_responseCode = NULL)
    {
        parent::__construct(array('FieldCodes'=>($_fieldCodes instanceof LRSStructArrayOfstring)?$_fieldCodes:new LRSStructArrayOfstring($_fieldCodes),'ResponseCode'=>$_responseCode),false);
    }
    /**
     * Get FieldCodes value
     * @return LRSStructArrayOfstring|null
     */
    public function getFieldCodes()
    {
        return $this->FieldCodes;
    }
    /**
     * Set FieldCodes value
     * @param LRSStructArrayOfstring $_fieldCodes the FieldCodes
     * @return LRSStructArrayOfstring
     */
    public function setFieldCodes($_fieldCodes)
    {
        return ($this->FieldCodes = $_fieldCodes);
    }
    /**
     * Get ResponseCode value
     * @return string|null
     */
    public function getResponseCode()
    {
        return $this->ResponseCode;
    }
    /**
     * Set ResponseCode value
     * @param string $_responseCode the ResponseCode
     * @return string
     */
    public function setResponseCode($_responseCode)
    {
        return ($this->ResponseCode = $_responseCode);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructUpdateLearnerResponseItem
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
