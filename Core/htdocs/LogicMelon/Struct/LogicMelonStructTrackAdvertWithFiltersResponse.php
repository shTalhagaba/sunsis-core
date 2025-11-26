<?php
/**
 * File for class LogicMelonStructTrackAdvertWithFiltersResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructTrackAdvertWithFiltersResponse originally named TrackAdvertWithFiltersResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructTrackAdvertWithFiltersResponse extends LogicMelonWsdlClass
{
    /**
     * The TrackAdvertWithFiltersResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIAdvertWithPostings
     */
    public $TrackAdvertWithFiltersResult;
    /**
     * Constructor method for TrackAdvertWithFiltersResponse
     * @see parent::__construct()
     * @param LogicMelonStructArrayOfAPIAdvertWithPostings $_trackAdvertWithFiltersResult
     * @return LogicMelonStructTrackAdvertWithFiltersResponse
     */
    public function __construct($_trackAdvertWithFiltersResult = NULL)
    {
        parent::__construct(array('TrackAdvertWithFiltersResult'=>($_trackAdvertWithFiltersResult instanceof LogicMelonStructArrayOfAPIAdvertWithPostings)?$_trackAdvertWithFiltersResult:new LogicMelonStructArrayOfAPIAdvertWithPostings($_trackAdvertWithFiltersResult)),false);
    }
    /**
     * Get TrackAdvertWithFiltersResult value
     * @return LogicMelonStructArrayOfAPIAdvertWithPostings|null
     */
    public function getTrackAdvertWithFiltersResult()
    {
        return $this->TrackAdvertWithFiltersResult;
    }
    /**
     * Set TrackAdvertWithFiltersResult value
     * @param LogicMelonStructArrayOfAPIAdvertWithPostings $_trackAdvertWithFiltersResult the TrackAdvertWithFiltersResult
     * @return LogicMelonStructArrayOfAPIAdvertWithPostings
     */
    public function setTrackAdvertWithFiltersResult($_trackAdvertWithFiltersResult)
    {
        return ($this->TrackAdvertWithFiltersResult = $_trackAdvertWithFiltersResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructTrackAdvertWithFiltersResponse
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
