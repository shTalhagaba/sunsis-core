<?php
/**
 * File for class LogicMelonStructExpressPostAdvertResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructExpressPostAdvertResponse originally named ExpressPostAdvertResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructExpressPostAdvertResponse extends LogicMelonWsdlClass
{
    /**
     * The ExpressPostAdvertResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIAdvertWithPostings
     */
    public $ExpressPostAdvertResult;
    /**
     * Constructor method for ExpressPostAdvertResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithPostings $_expressPostAdvertResult
     * @return LogicMelonStructExpressPostAdvertResponse
     */
    public function __construct($_expressPostAdvertResult = NULL)
    {
        parent::__construct(array('ExpressPostAdvertResult'=>$_expressPostAdvertResult),false);
    }
    /**
     * Get ExpressPostAdvertResult value
     * @return LogicMelonStructAPIAdvertWithPostings|null
     */
    public function getExpressPostAdvertResult()
    {
        return $this->ExpressPostAdvertResult;
    }
    /**
     * Set ExpressPostAdvertResult value
     * @param LogicMelonStructAPIAdvertWithPostings $_expressPostAdvertResult the ExpressPostAdvertResult
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function setExpressPostAdvertResult($_expressPostAdvertResult)
    {
        return ($this->ExpressPostAdvertResult = $_expressPostAdvertResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructExpressPostAdvertResponse
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
