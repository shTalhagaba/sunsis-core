<?php
/**
 * File for class LRSStructCreateDataChallengeResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructCreateDataChallengeResponse originally named CreateDataChallengeResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructCreateDataChallengeResponse extends LRSStructServiceResponseR9
{
    /**
     * The DataChallengeReference
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DataChallengeReference;
    /**
     * The ResponseDescription
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ResponseDescription;
    /**
     * The CreateDataChallengeResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $CreateDataChallengeResult;
    /**
     * Constructor method for CreateDataChallengeResponse
     * @see parent::__construct()
     * @param string $_dataChallengeReference
     * @param string $_responseDescription
     * @param ServiceResponseR9 $_createDataChallengeResult
     * @return LRSStructCreateDataChallengeResponse
     */
    public function __construct($_dataChallengeReference = NULL,$_responseDescription = NULL,$_createDataChallengeResult = NULL)
    {
        LRSWsdlClass::__construct(array('DataChallengeReference'=>$_dataChallengeReference,'ResponseDescription'=>$_responseDescription,'CreateDataChallengeResult'=>$_createDataChallengeResult),false);
    }
    /**
     * Get DataChallengeReference value
     * @return string|null
     */
    public function getDataChallengeReference()
    {
        return $this->DataChallengeReference;
    }
    /**
     * Set DataChallengeReference value
     * @param string $_dataChallengeReference the DataChallengeReference
     * @return string
     */
    public function setDataChallengeReference($_dataChallengeReference)
    {
        return ($this->DataChallengeReference = $_dataChallengeReference);
    }
    /**
     * Get ResponseDescription value
     * @return string|null
     */
    public function getResponseDescription()
    {
        return $this->ResponseDescription;
    }
    /**
     * Set ResponseDescription value
     * @param string $_responseDescription the ResponseDescription
     * @return string
     */
    public function setResponseDescription($_responseDescription)
    {
        return ($this->ResponseDescription = $_responseDescription);
    }
    /**
     * Get CreateDataChallengeResult value
     * @return ServiceResponseR9|null
     */
    public function getCreateDataChallengeResult()
    {
        return $this->CreateDataChallengeResult;
    }
    /**
     * Set CreateDataChallengeResult value
     * @param ServiceResponseR9 $_createDataChallengeResult the CreateDataChallengeResult
     * @return ServiceResponseR9
     */
    public function setCreateDataChallengeResult($_createDataChallengeResult)
    {
        return ($this->CreateDataChallengeResult = $_createDataChallengeResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructCreateDataChallengeResponse
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
