<?php
/**
 * File for class LRSStructDeleteSnapshotResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructDeleteSnapshotResponse originally named DeleteSnapshotResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructDeleteSnapshotResponse extends LRSStructServiceResponseR9
{
    /**
     * The DeleteSnapshotResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $DeleteSnapshotResult;
    /**
     * Constructor method for DeleteSnapshotResponse
     * @see parent::__construct()
     * @param ServiceResponseR9 $_deleteSnapshotResult
     * @return LRSStructDeleteSnapshotResponse
     */
    public function __construct($_deleteSnapshotResult = NULL)
    {
        LRSWsdlClass::__construct(array('DeleteSnapshotResult'=>$_deleteSnapshotResult),false);
    }
    /**
     * Get DeleteSnapshotResult value
     * @return ServiceResponseR9|null
     */
    public function getDeleteSnapshotResult()
    {
        return $this->DeleteSnapshotResult;
    }
    /**
     * Set DeleteSnapshotResult value
     * @param ServiceResponseR9 $_deleteSnapshotResult the DeleteSnapshotResult
     * @return ServiceResponseR9
     */
    public function setDeleteSnapshotResult($_deleteSnapshotResult)
    {
        return ($this->DeleteSnapshotResult = $_deleteSnapshotResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructDeleteSnapshotResponse
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
