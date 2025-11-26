<?php
/**
 * File for class LRSStructViewAuditResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructViewAuditResponse originally named ViewAuditResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructViewAuditResponse extends LRSWsdlClass
{
    /**
     * The ViewAuditResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public $ViewAuditResult;
    /**
     * Constructor method for ViewAuditResponse
     * @see parent::__construct()
     * @param LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S $_viewAuditResult
     * @return LRSStructViewAuditResponse
     */
    public function __construct($_viewAuditResult = NULL)
    {
        parent::__construct(array('ViewAuditResult'=>$_viewAuditResult),false);
    }
    /**
     * Get ViewAuditResult value
     * @return LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S|null
     */
    public function getViewAuditResult()
    {
        return $this->ViewAuditResult;
    }
    /**
     * Set ViewAuditResult value
     * @param LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S $_viewAuditResult the ViewAuditResult
     * @return LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function setViewAuditResult($_viewAuditResult)
    {
        return ($this->ViewAuditResult = $_viewAuditResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructViewAuditResponse
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
