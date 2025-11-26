<?php
/**
 * File for class MIAPStructMIAPUnmergedLearner
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPUnmergedLearner originally named MIAPUnmergedLearner
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPUnmergedLearner extends MIAPWsdlClass
{
    /**
     * The MasterULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $MasterULN;
    /**
     * The MasterFamilyName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * @var string
     */
    public $MasterFamilyName;
    /**
     * The MasterGivenName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * @var string
     */
    public $MasterGivenName;
    /**
     * The MasterDateOfBirth
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $MasterDateOfBirth;
    /**
     * The UnmergedULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $UnmergedULN;
    /**
     * The UnmergedFamilyName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * @var string
     */
    public $UnmergedFamilyName;
    /**
     * The UnmergedGivenName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * @var string
     */
    public $UnmergedGivenName;
    /**
     * The UnmergedDateOfBirth
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $UnmergedDateOfBirth;
    /**
     * The UnmergedDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $UnmergedDate;
    /**
     * Constructor method for MIAPUnmergedLearner
     * @see parent::__construct()
     * @param string $_masterULN
     * @param string $_masterFamilyName
     * @param string $_masterGivenName
     * @param string $_masterDateOfBirth
     * @param string $_unmergedULN
     * @param string $_unmergedFamilyName
     * @param string $_unmergedGivenName
     * @param string $_unmergedDateOfBirth
     * @param string $_unmergedDate
     * @return MIAPStructMIAPUnmergedLearner
     */
    public function __construct($_masterULN,$_masterFamilyName,$_masterGivenName,$_masterDateOfBirth,$_unmergedULN,$_unmergedFamilyName,$_unmergedGivenName,$_unmergedDateOfBirth,$_unmergedDate)
    {
        parent::__construct(array('MasterULN'=>$_masterULN,'MasterFamilyName'=>$_masterFamilyName,'MasterGivenName'=>$_masterGivenName,'MasterDateOfBirth'=>$_masterDateOfBirth,'UnmergedULN'=>$_unmergedULN,'UnmergedFamilyName'=>$_unmergedFamilyName,'UnmergedGivenName'=>$_unmergedGivenName,'UnmergedDateOfBirth'=>$_unmergedDateOfBirth,'UnmergedDate'=>$_unmergedDate),false);
    }
    /**
     * Get MasterULN value
     * @return string
     */
    public function getMasterULN()
    {
        return $this->MasterULN;
    }
    /**
     * Set MasterULN value
     * @param string $_masterULN the MasterULN
     * @return string
     */
    public function setMasterULN($_masterULN)
    {
        return ($this->MasterULN = $_masterULN);
    }
    /**
     * Get MasterFamilyName value
     * @return string
     */
    public function getMasterFamilyName()
    {
        return $this->MasterFamilyName;
    }
    /**
     * Set MasterFamilyName value
     * @param string $_masterFamilyName the MasterFamilyName
     * @return string
     */
    public function setMasterFamilyName($_masterFamilyName)
    {
        return ($this->MasterFamilyName = $_masterFamilyName);
    }
    /**
     * Get MasterGivenName value
     * @return string
     */
    public function getMasterGivenName()
    {
        return $this->MasterGivenName;
    }
    /**
     * Set MasterGivenName value
     * @param string $_masterGivenName the MasterGivenName
     * @return string
     */
    public function setMasterGivenName($_masterGivenName)
    {
        return ($this->MasterGivenName = $_masterGivenName);
    }
    /**
     * Get MasterDateOfBirth value
     * @return string
     */
    public function getMasterDateOfBirth()
    {
        return $this->MasterDateOfBirth;
    }
    /**
     * Set MasterDateOfBirth value
     * @param string $_masterDateOfBirth the MasterDateOfBirth
     * @return string
     */
    public function setMasterDateOfBirth($_masterDateOfBirth)
    {
        return ($this->MasterDateOfBirth = $_masterDateOfBirth);
    }
    /**
     * Get UnmergedULN value
     * @return string
     */
    public function getUnmergedULN()
    {
        return $this->UnmergedULN;
    }
    /**
     * Set UnmergedULN value
     * @param string $_unmergedULN the UnmergedULN
     * @return string
     */
    public function setUnmergedULN($_unmergedULN)
    {
        return ($this->UnmergedULN = $_unmergedULN);
    }
    /**
     * Get UnmergedFamilyName value
     * @return string
     */
    public function getUnmergedFamilyName()
    {
        return $this->UnmergedFamilyName;
    }
    /**
     * Set UnmergedFamilyName value
     * @param string $_unmergedFamilyName the UnmergedFamilyName
     * @return string
     */
    public function setUnmergedFamilyName($_unmergedFamilyName)
    {
        return ($this->UnmergedFamilyName = $_unmergedFamilyName);
    }
    /**
     * Get UnmergedGivenName value
     * @return string
     */
    public function getUnmergedGivenName()
    {
        return $this->UnmergedGivenName;
    }
    /**
     * Set UnmergedGivenName value
     * @param string $_unmergedGivenName the UnmergedGivenName
     * @return string
     */
    public function setUnmergedGivenName($_unmergedGivenName)
    {
        return ($this->UnmergedGivenName = $_unmergedGivenName);
    }
    /**
     * Get UnmergedDateOfBirth value
     * @return string
     */
    public function getUnmergedDateOfBirth()
    {
        return $this->UnmergedDateOfBirth;
    }
    /**
     * Set UnmergedDateOfBirth value
     * @param string $_unmergedDateOfBirth the UnmergedDateOfBirth
     * @return string
     */
    public function setUnmergedDateOfBirth($_unmergedDateOfBirth)
    {
        return ($this->UnmergedDateOfBirth = $_unmergedDateOfBirth);
    }
    /**
     * Get UnmergedDate value
     * @return string
     */
    public function getUnmergedDate()
    {
        return $this->UnmergedDate;
    }
    /**
     * Set UnmergedDate value
     * @param string $_unmergedDate the UnmergedDate
     * @return string
     */
    public function setUnmergedDate($_unmergedDate)
    {
        return ($this->UnmergedDate = $_unmergedDate);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPUnmergedLearner
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
