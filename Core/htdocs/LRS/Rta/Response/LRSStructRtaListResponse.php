<?php
/**
 * File for class LRSStructRtaListResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructRtaListResponse originally named RtaListResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructRtaListResponse extends LRSStructServiceResponseR9
{
    /**
     * The SavedRtaQueries
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfRtaOneQuery
     */
    public $SavedRtaQueries;
    /**
     * Constructor method for RtaListResponse
     * @see parent::__construct()
     * @param LRSStructArrayOfRtaOneQuery $_savedRtaQueries
     * @return LRSStructRtaListResponse
     */
    public function __construct($_savedRtaQueries = NULL)
    {
        LRSWsdlClass::__construct(array('SavedRtaQueries'=>($_savedRtaQueries instanceof LRSStructArrayOfRtaOneQuery)?$_savedRtaQueries:new LRSStructArrayOfRtaOneQuery($_savedRtaQueries)),false);
    }
    /**
     * Get SavedRtaQueries value
     * @return LRSStructArrayOfRtaOneQuery|null
     */
    public function getSavedRtaQueries()
    {
        return $this->SavedRtaQueries;
    }
    /**
     * Set SavedRtaQueries value
     * @param LRSStructArrayOfRtaOneQuery $_savedRtaQueries the SavedRtaQueries
     * @return LRSStructArrayOfRtaOneQuery
     */
    public function setSavedRtaQueries($_savedRtaQueries)
    {
        return ($this->SavedRtaQueries = $_savedRtaQueries);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructRtaListResponse
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
