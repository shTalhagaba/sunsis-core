<?php
/**
 * File for class LogicMelonStructQueryJobTitleResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructQueryJobTitleResponse originally named QueryJobTitleResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructQueryJobTitleResponse extends LogicMelonWsdlClass
{
    /**
     * The QueryJobTitleResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfCQueryJobTitle
     */
    public $QueryJobTitleResult;
    /**
     * Constructor method for QueryJobTitleResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfCQueryJobTitle $_queryJobTitleResult
     * @return LogicMelonStructQueryJobTitleResponse
     */
    public function __construct($_queryJobTitleResult = NULL)
    {
        parent::__construct(array('QueryJobTitleResult'=>($_queryJobTitleResult instanceof LogicMelonStructArrayOfCQueryJobTitle)?$_queryJobTitleResult:new LogicMelonStructArrayOfCQueryJobTitle($_queryJobTitleResult)),false);
    }
    /**
     * Get QueryJobTitleResult value
     * @return LogicMelonStructArrayOfCQueryJobTitle|null
     */
    public function getQueryJobTitleResult()
    {
        return $this->QueryJobTitleResult;
    }
    /**
     * Set QueryJobTitleResult value
     * @param LogicMelonStructArrayOfCQueryJobTitle $_queryJobTitleResult the QueryJobTitleResult
     * @return LogicMelonStructArrayOfCQueryJobTitle
     */
    public function setQueryJobTitleResult($_queryJobTitleResult)
    {
        return ($this->QueryJobTitleResult = $_queryJobTitleResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructQueryJobTitleResponse
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
