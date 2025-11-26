<?php
/**
 * File for class MIAPStructOutputBatchLearner
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructOutputBatchLearner originally named OutputBatchLearner
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructOutputBatchLearner extends MIAPStructBatchLearner
{
    /**
     * The ReturnCode
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var string
     */
    public $ReturnCode;
    /**
     * Constructor method for OutputBatchLearner
     * @see parent::__construct()
     * @param string $_returnCode
     * @return MIAPStructOutputBatchLearner
     */
    public function __construct($_returnCode)
    {
        MIAPWsdlClass::__construct(array('ReturnCode'=>$_returnCode),false);
    }
    /**
     * Get ReturnCode value
     * @return string
     */
    public function getReturnCode()
    {
        return $this->ReturnCode;
    }
    /**
     * Set ReturnCode value
     * @param string $_returnCode the ReturnCode
     * @return string
     */
    public function setReturnCode($_returnCode)
    {
        return ($this->ReturnCode = $_returnCode);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructOutputBatchLearner
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
