<?php
/**
 * File for class LogicMelonStructCloseAdvertWithFiltersResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructCloseAdvertWithFiltersResponse originally named CloseAdvertWithFiltersResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructCloseAdvertWithFiltersResponse extends LogicMelonWsdlClass
{
    /**
     * The CloseAdvertWithFiltersResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIAdvertWithPostings
     */
    public $CloseAdvertWithFiltersResult;
    /**
     * Constructor method for CloseAdvertWithFiltersResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithPostings $_closeAdvertWithFiltersResult
     * @return LogicMelonStructCloseAdvertWithFiltersResponse
     */
    public function __construct($_closeAdvertWithFiltersResult = NULL)
    {
        parent::__construct(array('CloseAdvertWithFiltersResult'=>$_closeAdvertWithFiltersResult),false);
    }
    /**
     * Get CloseAdvertWithFiltersResult value
     * @return LogicMelonStructAPIAdvertWithPostings|null
     */
    public function getCloseAdvertWithFiltersResult()
    {
        return $this->CloseAdvertWithFiltersResult;
    }
    /**
     * Set CloseAdvertWithFiltersResult value
     * @param LogicMelonStructAPIAdvertWithPostings $_closeAdvertWithFiltersResult the CloseAdvertWithFiltersResult
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function setCloseAdvertWithFiltersResult($_closeAdvertWithFiltersResult)
    {
        return ($this->CloseAdvertWithFiltersResult = $_closeAdvertWithFiltersResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructCloseAdvertWithFiltersResponse
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
