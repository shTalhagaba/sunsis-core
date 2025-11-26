<?php
/**
 * File for class LogicMelonStructTrackAdvertResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructTrackAdvertResponse originally named TrackAdvertResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructTrackAdvertResponse extends LogicMelonWsdlClass
{
    /**
     * The TrackAdvertResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIAdvertWithPostings
     */
    public $TrackAdvertResult;
    /**
     * Constructor method for TrackAdvertResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfAPIAdvertWithPostings $_trackAdvertResult
     * @return LogicMelonStructTrackAdvertResponse
     */
    public function __construct($_trackAdvertResult = NULL)
    {
        parent::__construct(array('TrackAdvertResult'=>($_trackAdvertResult instanceof LogicMelonStructArrayOfAPIAdvertWithPostings)?$_trackAdvertResult:new LogicMelonStructArrayOfAPIAdvertWithPostings($_trackAdvertResult)),false);
    }
    /**
     * Get TrackAdvertResult value
     * @return LogicMelonStructArrayOfAPIAdvertWithPostings|null
     */
    public function getTrackAdvertResult()
    {
        return $this->TrackAdvertResult;
    }
    /**
     * Set TrackAdvertResult value
     * @param LogicMelonStructArrayOfAPIAdvertWithPostings $_trackAdvertResult the TrackAdvertResult
     * @return LogicMelonStructArrayOfAPIAdvertWithPostings
     */
    public function setTrackAdvertResult($_trackAdvertResult)
    {
        return ($this->TrackAdvertResult = $_trackAdvertResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructTrackAdvertResponse
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
