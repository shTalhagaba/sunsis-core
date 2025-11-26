<?php
/**
 * File for class LogicMelonStructUserFeedsAndQuotaResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructUserFeedsAndQuotaResponse originally named UserFeedsAndQuotaResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructUserFeedsAndQuotaResponse extends LogicMelonWsdlClass
{
    /**
     * The UserFeedsAndQuotaResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfCPostFeed
     */
    public $UserFeedsAndQuotaResult;
    /**
     * Constructor method for UserFeedsAndQuotaResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfCPostFeed $_userFeedsAndQuotaResult
     * @return LogicMelonStructUserFeedsAndQuotaResponse
     */
    public function __construct($_userFeedsAndQuotaResult = NULL)
    {
        parent::__construct(array('UserFeedsAndQuotaResult'=>($_userFeedsAndQuotaResult instanceof LogicMelonStructArrayOfCPostFeed)?$_userFeedsAndQuotaResult:new LogicMelonStructArrayOfCPostFeed($_userFeedsAndQuotaResult)),false);
    }
    /**
     * Get UserFeedsAndQuotaResult value
     * @return LogicMelonStructArrayOfCPostFeed|null
     */
    public function getUserFeedsAndQuotaResult()
    {
        return $this->UserFeedsAndQuotaResult;
    }
    /**
     * Set UserFeedsAndQuotaResult value
     * @param LogicMelonStructArrayOfCPostFeed $_userFeedsAndQuotaResult the UserFeedsAndQuotaResult
     * @return LogicMelonStructArrayOfCPostFeed
     */
    public function setUserFeedsAndQuotaResult($_userFeedsAndQuotaResult)
    {
        return ($this->UserFeedsAndQuotaResult = $_userFeedsAndQuotaResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructUserFeedsAndQuotaResponse
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
