<?php
/**
 * File for class LRSStructLearnerByUlnResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructLearnerByUlnResponse originally named LearnerByUlnResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructLearnerByUlnResponse extends LRSStructServiceResponseR9
{
    /**
     * The FamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FamilyName;
    /**
     * The GivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $GivenName;
    /**
     * The Learner
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructLearner
     */
    public $Learner;
    /**
     * The Uln
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Uln;
    /**
     * The LearnerByUlnResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $LearnerByUlnResult;
    /**
     * Constructor method for LearnerByUlnResponse
     * @see parent::__construct()
     * @param string $_familyName
     * @param string $_givenName
     * @param LRSStructLearner $_learner
     * @param string $_uln
     * @param ServiceResponseR9 $_learnerByUlnResult
     * @return LRSStructLearnerByUlnResponse
     */
    public function __construct($_familyName = NULL,$_givenName = NULL,$_learner = NULL,$_uln = NULL,$_learnerByUlnResult = NULL)
    {
        LRSWsdlClass::__construct(array('FamilyName'=>$_familyName,'GivenName'=>$_givenName,'Learner'=>$_learner,'Uln'=>$_uln,'LearnerByUlnResult'=>$_learnerByUlnResult),false);
    }
    /**
     * Get FamilyName value
     * @return string|null
     */
    public function getFamilyName()
    {
        return $this->FamilyName;
    }
    /**
     * Set FamilyName value
     * @param string $_familyName the FamilyName
     * @return string
     */
    public function setFamilyName($_familyName)
    {
        return ($this->FamilyName = $_familyName);
    }
    /**
     * Get GivenName value
     * @return string|null
     */
    public function getGivenName()
    {
        return $this->GivenName;
    }
    /**
     * Set GivenName value
     * @param string $_givenName the GivenName
     * @return string
     */
    public function setGivenName($_givenName)
    {
        return ($this->GivenName = $_givenName);
    }
    /**
     * Get Learner value
     * @return LRSStructLearner|null
     */
    public function getLearner()
    {
        return $this->Learner;
    }
    /**
     * Set Learner value
     * @param LRSStructLearner $_learner the Learner
     * @return LRSStructLearner
     */
    public function setLearner($_learner)
    {
        return ($this->Learner = $_learner);
    }
    /**
     * Get Uln value
     * @return string|null
     */
    public function getUln()
    {
        return $this->Uln;
    }
    /**
     * Set Uln value
     * @param string $_uln the Uln
     * @return string
     */
    public function setUln($_uln)
    {
        return ($this->Uln = $_uln);
    }
    /**
     * Get LearnerByUlnResult value
     * @return ServiceResponseR9|null
     */
    public function getLearnerByUlnResult()
    {
        return $this->LearnerByUlnResult;
    }
    /**
     * Set LearnerByUlnResult value
     * @param ServiceResponseR9 $_learnerByUlnResult the LearnerByUlnResult
     * @return ServiceResponseR9
     */
    public function setLearnerByUlnResult($_learnerByUlnResult)
    {
        return ($this->LearnerByUlnResult = $_learnerByUlnResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructLearnerByUlnResponse
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
