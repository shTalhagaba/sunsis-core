<?php
/**
 * File for class LogicMelonStructUserFeedsAndQuotaWithDestinationsResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructUserFeedsAndQuotaWithDestinationsResponse originally named UserFeedsAndQuotaWithDestinationsResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructUserFeedsAndQuotaWithDestinationsResponse extends LogicMelonWsdlClass
{
    /**
     * The UserFeedsAndQuotaWithDestinationsResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfCPostFeed
     */
    public $UserFeedsAndQuotaWithDestinationsResult;
    /**
     * Constructor method for UserFeedsAndQuotaWithDestinationsResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfCPostFeed $_userFeedsAndQuotaWithDestinationsResult
     * @return LogicMelonStructUserFeedsAndQuotaWithDestinationsResponse
     */
    public function __construct($_userFeedsAndQuotaWithDestinationsResult = NULL)
    {
        parent::__construct(array('UserFeedsAndQuotaWithDestinationsResult'=>($_userFeedsAndQuotaWithDestinationsResult instanceof LogicMelonStructArrayOfCPostFeed)?$_userFeedsAndQuotaWithDestinationsResult:new LogicMelonStructArrayOfCPostFeed($_userFeedsAndQuotaWithDestinationsResult)),false);
    }
    /**
     * Get UserFeedsAndQuotaWithDestinationsResult value
     * @return LogicMelonStructArrayOfCPostFeed|null
     */
    public function getUserFeedsAndQuotaWithDestinationsResult()
    {
        return $this->UserFeedsAndQuotaWithDestinationsResult;
    }
    /**
     * Set UserFeedsAndQuotaWithDestinationsResult value
     * @param LogicMelonStructArrayOfCPostFeed $_userFeedsAndQuotaWithDestinationsResult the UserFeedsAndQuotaWithDestinationsResult
     * @return LogicMelonStructArrayOfCPostFeed
     */
    public function setUserFeedsAndQuotaWithDestinationsResult($_userFeedsAndQuotaWithDestinationsResult)
    {
        return ($this->UserFeedsAndQuotaWithDestinationsResult = $_userFeedsAndQuotaWithDestinationsResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructUserFeedsAndQuotaWithDestinationsResponse
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
