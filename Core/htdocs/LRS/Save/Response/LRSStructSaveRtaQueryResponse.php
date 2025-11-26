<?php
/**
 * File for class LRSStructSaveRtaQueryResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructSaveRtaQueryResponse originally named SaveRtaQueryResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructSaveRtaQueryResponse extends LRSWsdlClass
{
    /**
     * The SaveRtaQueryResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SaveRtaQueryResult;
    /**
     * Constructor method for SaveRtaQueryResponse
     * @see parent::__construct()
     * @param string $_saveRtaQueryResult
     * @return LRSStructSaveRtaQueryResponse
     */
    public function __construct($_saveRtaQueryResult = NULL)
    {
        parent::__construct(array('SaveRtaQueryResult'=>$_saveRtaQueryResult),false);
    }
    /**
     * Get SaveRtaQueryResult value
     * @return string|null
     */
    public function getSaveRtaQueryResult()
    {
        return $this->SaveRtaQueryResult;
    }
    /**
     * Set SaveRtaQueryResult value
     * @param string $_saveRtaQueryResult the SaveRtaQueryResult
     * @return string
     */
    public function setSaveRtaQueryResult($_saveRtaQueryResult)
    {
        return ($this->SaveRtaQueryResult = $_saveRtaQueryResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructSaveRtaQueryResponse
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
