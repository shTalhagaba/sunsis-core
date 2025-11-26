<?php
/**
 * File for class LogicMelonStructUnarchiveAdvertResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructUnarchiveAdvertResponse originally named UnarchiveAdvertResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructUnarchiveAdvertResponse extends LogicMelonWsdlClass
{
    /**
     * The UnarchiveAdvertResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIAdvertWithPostings
     */
    public $UnarchiveAdvertResult;
    /**
     * Constructor method for UnarchiveAdvertResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithPostings $_unarchiveAdvertResult
     * @return LogicMelonStructUnarchiveAdvertResponse
     */
    public function __construct($_unarchiveAdvertResult = NULL)
    {
        parent::__construct(array('UnarchiveAdvertResult'=>$_unarchiveAdvertResult),false);
    }
    /**
     * Get UnarchiveAdvertResult value
     * @return LogicMelonStructAPIAdvertWithPostings|null
     */
    public function getUnarchiveAdvertResult()
    {
        return $this->UnarchiveAdvertResult;
    }
    /**
     * Set UnarchiveAdvertResult value
     * @param LogicMelonStructAPIAdvertWithPostings $_unarchiveAdvertResult the UnarchiveAdvertResult
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function setUnarchiveAdvertResult($_unarchiveAdvertResult)
    {
        return ($this->UnarchiveAdvertResult = $_unarchiveAdvertResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructUnarchiveAdvertResponse
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
