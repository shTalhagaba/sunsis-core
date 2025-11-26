<?php
/**
 * File for class LRSStructListSnapshotResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructListSnapshotResponse originally named ListSnapshotResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructListSnapshotResponse extends LRSStructServiceResponseR9
{
    /**
     * The Snapshots
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfSnapshot
     */
    public $Snapshots;
    /**
     * Constructor method for ListSnapshotResponse
     * @see parent::__construct()
     * @param LRSStructArrayOfSnapshot $_snapshots
     * @return LRSStructListSnapshotResponse
     */
    public function __construct($_snapshots = NULL)
    {
        LRSWsdlClass::__construct(array('Snapshots'=>($_snapshots instanceof LRSStructArrayOfSnapshot)?$_snapshots:new LRSStructArrayOfSnapshot($_snapshots)),false);
    }
    /**
     * Get Snapshots value
     * @return LRSStructArrayOfSnapshot|null
     */
    public function getSnapshots()
    {
        return $this->Snapshots;
    }
    /**
     * Set Snapshots value
     * @param LRSStructArrayOfSnapshot $_snapshots the Snapshots
     * @return LRSStructArrayOfSnapshot
     */
    public function setSnapshots($_snapshots)
    {
        return ($this->Snapshots = $_snapshots);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructListSnapshotResponse
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
