<?php
/**
 * File for class LogicMelonStructUnarchiveAdvertWithFiltersResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructUnarchiveAdvertWithFiltersResponse originally named UnarchiveAdvertWithFiltersResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructUnarchiveAdvertWithFiltersResponse extends LogicMelonWsdlClass
{
    /**
     * The UnarchiveAdvertWithFiltersResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIAdvertWithPostings
     */
    public $UnarchiveAdvertWithFiltersResult;
    /**
     * Constructor method for UnarchiveAdvertWithFiltersResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithPostings $_unarchiveAdvertWithFiltersResult
     * @return LogicMelonStructUnarchiveAdvertWithFiltersResponse
     */
    public function __construct($_unarchiveAdvertWithFiltersResult = NULL)
    {
        parent::__construct(array('UnarchiveAdvertWithFiltersResult'=>$_unarchiveAdvertWithFiltersResult),false);
    }
    /**
     * Get UnarchiveAdvertWithFiltersResult value
     * @return LogicMelonStructAPIAdvertWithPostings|null
     */
    public function getUnarchiveAdvertWithFiltersResult()
    {
        return $this->UnarchiveAdvertWithFiltersResult;
    }
    /**
     * Set UnarchiveAdvertWithFiltersResult value
     * @param LogicMelonStructAPIAdvertWithPostings $_unarchiveAdvertWithFiltersResult the UnarchiveAdvertWithFiltersResult
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function setUnarchiveAdvertWithFiltersResult($_unarchiveAdvertWithFiltersResult)
    {
        return ($this->UnarchiveAdvertWithFiltersResult = $_unarchiveAdvertWithFiltersResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructUnarchiveAdvertWithFiltersResponse
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
