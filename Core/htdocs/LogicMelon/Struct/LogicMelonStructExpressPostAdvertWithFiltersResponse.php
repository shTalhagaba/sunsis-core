<?php
/**
 * File for class LogicMelonStructExpressPostAdvertWithFiltersResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructExpressPostAdvertWithFiltersResponse originally named ExpressPostAdvertWithFiltersResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructExpressPostAdvertWithFiltersResponse extends LogicMelonWsdlClass
{
    /**
     * The ExpressPostAdvertWithFiltersResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIAdvertWithPostings
     */
    public $ExpressPostAdvertWithFiltersResult;
    /**
     * Constructor method for ExpressPostAdvertWithFiltersResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithPostings $_expressPostAdvertWithFiltersResult
     * @return LogicMelonStructExpressPostAdvertWithFiltersResponse
     */
    public function __construct($_expressPostAdvertWithFiltersResult = NULL)
    {
        parent::__construct(array('ExpressPostAdvertWithFiltersResult'=>$_expressPostAdvertWithFiltersResult),false);
    }
    /**
     * Get ExpressPostAdvertWithFiltersResult value
     * @return LogicMelonStructAPIAdvertWithPostings|null
     */
    public function getExpressPostAdvertWithFiltersResult()
    {
        return $this->ExpressPostAdvertWithFiltersResult;
    }
    /**
     * Set ExpressPostAdvertWithFiltersResult value
     * @param LogicMelonStructAPIAdvertWithPostings $_expressPostAdvertWithFiltersResult the ExpressPostAdvertWithFiltersResult
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function setExpressPostAdvertWithFiltersResult($_expressPostAdvertWithFiltersResult)
    {
        return ($this->ExpressPostAdvertWithFiltersResult = $_expressPostAdvertWithFiltersResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructExpressPostAdvertWithFiltersResponse
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
