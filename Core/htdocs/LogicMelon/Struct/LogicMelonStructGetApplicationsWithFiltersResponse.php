<?php
/**
 * File for class LogicMelonStructGetApplicationsWithFiltersResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetApplicationsWithFiltersResponse originally named GetApplicationsWithFiltersResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetApplicationsWithFiltersResponse extends LogicMelonWsdlClass
{
    /**
     * The GetApplicationsWithFiltersResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIApplication
     */
    public $GetApplicationsWithFiltersResult;
    /**
     * Constructor method for GetApplicationsWithFiltersResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfAPIApplication $_getApplicationsWithFiltersResult
     * @return LogicMelonStructGetApplicationsWithFiltersResponse
     */
    public function __construct($_getApplicationsWithFiltersResult = NULL)
    {
        parent::__construct(array('GetApplicationsWithFiltersResult'=>($_getApplicationsWithFiltersResult instanceof LogicMelonStructArrayOfAPIApplication)?$_getApplicationsWithFiltersResult:new LogicMelonStructArrayOfAPIApplication($_getApplicationsWithFiltersResult)),false);
    }
    /**
     * Get GetApplicationsWithFiltersResult value
     * @return LogicMelonStructArrayOfAPIApplication|null
     */
    public function getGetApplicationsWithFiltersResult()
    {
        return $this->GetApplicationsWithFiltersResult;
    }
    /**
     * Set GetApplicationsWithFiltersResult value
     * @param LogicMelonStructArrayOfAPIApplication $_getApplicationsWithFiltersResult the GetApplicationsWithFiltersResult
     * @return LogicMelonStructArrayOfAPIApplication
     */
    public function setGetApplicationsWithFiltersResult($_getApplicationsWithFiltersResult)
    {
        return ($this->GetApplicationsWithFiltersResult = $_getApplicationsWithFiltersResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetApplicationsWithFiltersResponse
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
