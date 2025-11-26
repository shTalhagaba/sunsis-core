<?php
/**
 * File for class LRSStructGetMyLearningEventsResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructGetMyLearningEventsResponse originally named GetMyLearningEventsResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructGetMyLearningEventsResponse extends LRSStructServiceResponseR9
{
    /**
     * The FoundULN
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FoundULN;
    /**
     * The IncomingULN
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $IncomingULN;
    /**
     * The LearnerRecord
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfLearningEvent
     */
    public $LearnerRecord;
    /**
     * The GetMyLearningEventsResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $GetMyLearningEventsResult;
    /**
     * Constructor method for GetMyLearningEventsResponse
     * @see parent::__construct()
     * @param string $_foundULN
     * @param string $_incomingULN
     * @param LRSStructArrayOfLearningEvent $_learnerRecord
     * @param ServiceResponseR9 $_getMyLearningEventsResult
     * @return LRSStructGetMyLearningEventsResponse
     */
    public function __construct($_foundULN = NULL,$_incomingULN = NULL,$_learnerRecord = NULL,$_getMyLearningEventsResult = NULL)
    {
        LRSWsdlClass::__construct(array('FoundULN'=>$_foundULN,'IncomingULN'=>$_incomingULN,'LearnerRecord'=>($_learnerRecord instanceof LRSStructArrayOfLearningEvent)?$_learnerRecord:new LRSStructArrayOfLearningEvent($_learnerRecord),'GetMyLearningEventsResult'=>$_getMyLearningEventsResult),false);
    }
    /**
     * Get FoundULN value
     * @return string|null
     */
    public function getFoundULN()
    {
        return $this->FoundULN;
    }
    /**
     * Set FoundULN value
     * @param string $_foundULN the FoundULN
     * @return string
     */
    public function setFoundULN($_foundULN)
    {
        return ($this->FoundULN = $_foundULN);
    }
    /**
     * Get IncomingULN value
     * @return string|null
     */
    public function getIncomingULN()
    {
        return $this->IncomingULN;
    }
    /**
     * Set IncomingULN value
     * @param string $_incomingULN the IncomingULN
     * @return string
     */
    public function setIncomingULN($_incomingULN)
    {
        return ($this->IncomingULN = $_incomingULN);
    }
    /**
     * Get LearnerRecord value
     * @return LRSStructArrayOfLearningEvent|null
     */
    public function getLearnerRecord()
    {
        return $this->LearnerRecord;
    }
    /**
     * Set LearnerRecord value
     * @param LRSStructArrayOfLearningEvent $_learnerRecord the LearnerRecord
     * @return LRSStructArrayOfLearningEvent
     */
    public function setLearnerRecord($_learnerRecord)
    {
        return ($this->LearnerRecord = $_learnerRecord);
    }
    /**
     * Get GetMyLearningEventsResult value
     * @return ServiceResponseR9|null
     */
    public function getGetMyLearningEventsResult()
    {
        return $this->GetMyLearningEventsResult;
    }
    /**
     * Set GetMyLearningEventsResult value
     * @param ServiceResponseR9 $_getMyLearningEventsResult the GetMyLearningEventsResult
     * @return ServiceResponseR9
     */
    public function setGetMyLearningEventsResult($_getMyLearningEventsResult)
    {
        return ($this->GetMyLearningEventsResult = $_getMyLearningEventsResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructGetMyLearningEventsResponse
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
