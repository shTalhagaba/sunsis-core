<?php
/**
 * File for class LRSStructPersistedBusinessObject
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructPersistedBusinessObject originally named PersistedBusinessObject
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructPersistedBusinessObject extends LRSStructBusinessObject
{
    /**
     * The Id
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $Id;
    /**
     * Constructor method for PersistedBusinessObject
     * @see parent::__construct()
     * @param int $_id
     * @return LRSStructPersistedBusinessObject
     */
    public function __construct($_id = NULL)
    {
        LRSWsdlClass::__construct(array('Id'=>$_id),false);
    }
    /**
     * Get Id value
     * @return int|null
     */
    public function getId()
    {
        return $this->Id;
    }
    /**
     * Set Id value
     * @param int $_id the Id
     * @return int
     */
    public function setId($_id)
    {
        return ($this->Id = $_id);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructPersistedBusinessObject
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
