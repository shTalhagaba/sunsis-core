<?php
/**
 * File for class LRSStructListSnapshotsResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructListSnapshotsResponse originally named ListSnapshotsResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructListSnapshotsResponse extends LRSWsdlClass
{
    /**
     * The ListSnapshotsResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructListSnapshotResponse
     */
    public $ListSnapshotsResult;
    /**
     * Constructor method for ListSnapshotsResponse
     * @see parent::__construct()
     * @param LRSStructListSnapshotResponse $_listSnapshotsResult
     * @return LRSStructListSnapshotsResponse
     */
    public function __construct($_listSnapshotsResult = NULL)
    {
        parent::__construct(array('ListSnapshotsResult'=>$_listSnapshotsResult),false);
    }
    /**
     * Get ListSnapshotsResult value
     * @return LRSStructListSnapshotResponse|null
     */
    public function getListSnapshotsResult()
    {
        return $this->ListSnapshotsResult;
    }
    /**
     * Set ListSnapshotsResult value
     * @param LRSStructListSnapshotResponse $_listSnapshotsResult the ListSnapshotsResult
     * @return LRSStructListSnapshotResponse
     */
    public function setListSnapshotsResult($_listSnapshotsResult)
    {
        return ($this->ListSnapshotsResult = $_listSnapshotsResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructListSnapshotsResponse
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
