<?php
/**
 * File for class LRSStructCreateOrModifySnapshotResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructCreateOrModifySnapshotResponse originally named CreateOrModifySnapshotResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructCreateOrModifySnapshotResponse extends LRSStructServiceResponseR9
{
    /**
     * The Guid
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - pattern : [\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}
     * @var string
     */
    public $Guid;
    /**
     * The CreateOrModifySnapshotResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $CreateOrModifySnapshotResult;
    /**
     * Constructor method for CreateOrModifySnapshotResponse
     * @see parent::__construct()
     * @param string $_guid
     * @param ServiceResponseR9 $_createOrModifySnapshotResult
     * @return LRSStructCreateOrModifySnapshotResponse
     */
    public function __construct($_guid = NULL,$_createOrModifySnapshotResult = NULL)
    {
        LRSWsdlClass::__construct(array('Guid'=>$_guid,'CreateOrModifySnapshotResult'=>$_createOrModifySnapshotResult),false);
    }
    /**
     * Get Guid value
     * @return string|null
     */
    public function getGuid()
    {
        return $this->Guid;
    }
    /**
     * Set Guid value
     * @param string $_guid the Guid
     * @return string
     */
    public function setGuid($_guid)
    {
        return ($this->Guid = $_guid);
    }
    /**
     * Get CreateOrModifySnapshotResult value
     * @return ServiceResponseR9|null
     */
    public function getCreateOrModifySnapshotResult()
    {
        return $this->CreateOrModifySnapshotResult;
    }
    /**
     * Set CreateOrModifySnapshotResult value
     * @param ServiceResponseR9 $_createOrModifySnapshotResult the CreateOrModifySnapshotResult
     * @return ServiceResponseR9
     */
    public function setCreateOrModifySnapshotResult($_createOrModifySnapshotResult)
    {
        return ($this->CreateOrModifySnapshotResult = $_createOrModifySnapshotResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructCreateOrModifySnapshotResponse
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
