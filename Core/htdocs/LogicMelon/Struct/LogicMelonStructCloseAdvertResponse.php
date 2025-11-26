<?php
/**
 * File for class LogicMelonStructCloseAdvertResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructCloseAdvertResponse originally named CloseAdvertResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructCloseAdvertResponse extends LogicMelonWsdlClass
{
    /**
     * The CloseAdvertResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIAdvertWithPostings
     */
    public $CloseAdvertResult;
    /**
     * Constructor method for CloseAdvertResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithPostings $_closeAdvertResult
     * @return LogicMelonStructCloseAdvertResponse
     */
    public function __construct($_closeAdvertResult = NULL)
    {
        parent::__construct(array('CloseAdvertResult'=>$_closeAdvertResult),false);
    }
    /**
     * Get CloseAdvertResult value
     * @return LogicMelonStructAPIAdvertWithPostings|null
     */
    public function getCloseAdvertResult()
    {
        return $this->CloseAdvertResult;
    }
    /**
     * Set CloseAdvertResult value
     * @param LogicMelonStructAPIAdvertWithPostings $_closeAdvertResult the CloseAdvertResult
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function setCloseAdvertResult($_closeAdvertResult)
    {
        return ($this->CloseAdvertResult = $_closeAdvertResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructCloseAdvertResponse
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
