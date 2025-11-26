<?php
/**
 * File for class LogicMelonStructAddAdvertWithValuesResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAddAdvertWithValuesResponse originally named AddAdvertWithValuesResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAddAdvertWithValuesResponse extends LogicMelonWsdlClass
{
    /**
     * The AddAdvertResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var LogicMelonStructAddAdvertResult
     */
    public $AddAdvertResult;
    /**
     * Constructor method for AddAdvertWithValuesResponse
     * @see parent::__construct()
     * @param LogicMelonStructAddAdvertResult $_addAdvertResult
     * @return LogicMelonStructAddAdvertWithValuesResponse
     */
    public function __construct($_addAdvertResult)
    {
        parent::__construct(array('AddAdvertResult'=>$_addAdvertResult),false);
    }
    /**
     * Get AddAdvertResult value
     * @return LogicMelonStructAddAdvertResult
     */
    public function getAddAdvertResult()
    {
        return $this->AddAdvertResult;
    }
    /**
     * Set AddAdvertResult value
     * @param LogicMelonStructAddAdvertResult $_addAdvertResult the AddAdvertResult
     * @return LogicMelonStructAddAdvertResult
     */
    public function setAddAdvertResult($_addAdvertResult)
    {
        return ($this->AddAdvertResult = $_addAdvertResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAddAdvertWithValuesResponse
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
