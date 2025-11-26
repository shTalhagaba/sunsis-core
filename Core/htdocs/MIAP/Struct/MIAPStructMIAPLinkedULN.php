<?php
/**
 * File for class MIAPStructMIAPLinkedULN
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPLinkedULN originally named MIAPLinkedULN
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPLinkedULN extends MIAPWsdlClass
{
    /**
     * The ULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $ULN;
    /**
     * Constructor method for MIAPLinkedULN
     * @see parent::__construct()
     * @param string $_uLN
     * @return MIAPStructMIAPLinkedULN
     */
    public function __construct($_uLN = NULL)
    {
        parent::__construct(array('ULN'=>$_uLN),false);
    }
    /**
     * Get ULN value
     * @return string|null
     */
    public function getULN()
    {
        return $this->ULN;
    }
    /**
     * Set ULN value
     * @param string $_uLN the ULN
     * @return string
     */
    public function setULN($_uLN)
    {
        return ($this->ULN = $_uLN);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPLinkedULN
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
