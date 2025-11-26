<?php
/**
 * File for class StructElementErrorData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructElementErrorData originally named ElementErrorData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructElementErrorData extends WsdlClass
{
    /**
     * The ErrorCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $ErrorCode;
    /**
     * Constructor method for ElementErrorData
     * @see parent::__construct()
     * @param int $_errorCode
     * @return StructElementErrorData
     */
    public function __construct($_errorCode = NULL)
    {
        parent::__construct(array('ErrorCode'=>$_errorCode),false);
    }
    /**
     * Get ErrorCode value
     * @return int|null
     */
    public function getErrorCode()
    {
        return $this->ErrorCode;
    }
    /**
     * Set ErrorCode value
     * @param int $_errorCode the ErrorCode
     * @return int
     */
    public function setErrorCode($_errorCode)
    {
        return ($this->ErrorCode = $_errorCode);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructElementErrorData
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
