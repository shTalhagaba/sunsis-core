<?php
/**
 * File for class LogicMelonStructGetApplicationsResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetApplicationsResponse originally named GetApplicationsResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetApplicationsResponse extends LogicMelonWsdlClass
{
    /**
     * The GetApplicationsResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIApplication
     */
    public $GetApplicationsResult;
    /**
     * Constructor method for GetApplicationsResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfAPIApplication $_getApplicationsResult
     * @return LogicMelonStructGetApplicationsResponse
     */
    public function __construct($_getApplicationsResult = NULL)
    {
        parent::__construct(array('GetApplicationsResult'=>($_getApplicationsResult instanceof LogicMelonStructArrayOfAPIApplication)?$_getApplicationsResult:new LogicMelonStructArrayOfAPIApplication($_getApplicationsResult)),false);
    }
    /**
     * Get GetApplicationsResult value
     * @return LogicMelonStructArrayOfAPIApplication|null
     */
    public function getGetApplicationsResult()
    {
        return $this->GetApplicationsResult;
    }
    /**
     * Set GetApplicationsResult value
     * @param LogicMelonStructArrayOfAPIApplication $_getApplicationsResult the GetApplicationsResult
     * @return LogicMelonStructArrayOfAPIApplication
     */
    public function setGetApplicationsResult($_getApplicationsResult)
    {
        return ($this->GetApplicationsResult = $_getApplicationsResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetApplicationsResponse
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
