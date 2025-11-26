<?php
/**
 * File for class MIAPStructMIAPDeletedLearner
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPDeletedLearner originally named MIAPDeletedLearner
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPDeletedLearner extends MIAPWsdlClass
{
    /**
     * The ULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $ULN;
    /**
     * The DeletedDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $DeletedDate;
    /**
     * Constructor method for MIAPDeletedLearner
     * @see parent::__construct()
     * @param string $_uLN
     * @param string $_deletedDate
     * @return MIAPStructMIAPDeletedLearner
     */
    public function __construct($_uLN,$_deletedDate)
    {
        parent::__construct(array('ULN'=>$_uLN,'DeletedDate'=>$_deletedDate),false);
    }
    /**
     * Get ULN value
     * @return string
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
     * Get DeletedDate value
     * @return string
     */
    public function getDeletedDate()
    {
        return $this->DeletedDate;
    }
    /**
     * Set DeletedDate value
     * @param string $_deletedDate the DeletedDate
     * @return string
     */
    public function setDeletedDate($_deletedDate)
    {
        return ($this->DeletedDate = $_deletedDate);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPDeletedLearner
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
