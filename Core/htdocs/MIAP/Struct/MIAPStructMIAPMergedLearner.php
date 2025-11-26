<?php
/**
 * File for class MIAPStructMIAPMergedLearner
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPMergedLearner originally named MIAPMergedLearner
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPMergedLearner extends MIAPWsdlClass
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
     * The MergedULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $MergedULN;
    /**
     * The MergedFamilyName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * @var string
     */
    public $MergedFamilyName;
    /**
     * The MergedGivenName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * @var string
     */
    public $MergedGivenName;
    /**
     * The MergedDateOfBirth
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $MergedDateOfBirth;
    /**
     * The MergedDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $MergedDate;
    /**
     * Constructor method for MIAPMergedLearner
     * @see parent::__construct()
     * @param string $_masterULN
     * @param string $_masterFamilyName
     * @param string $_masterGivenName
     * @param string $_masterDateOfBirth
     * @param string $_mergedULN
     * @param string $_mergedFamilyName
     * @param string $_mergedGivenName
     * @param string $_mergedDateOfBirth
     * @param string $_mergedDate
     * @return MIAPStructMIAPMergedLearner
     */
    public function __construct($_masterULN,$_masterFamilyName,$_masterGivenName,$_masterDateOfBirth,$_mergedULN,$_mergedFamilyName,$_mergedGivenName,$_mergedDateOfBirth,$_mergedDate)
    {
        parent::__construct(array('MasterULN'=>$_masterULN,'MasterFamilyName'=>$_masterFamilyName,'MasterGivenName'=>$_masterGivenName,'MasterDateOfBirth'=>$_masterDateOfBirth,'MergedULN'=>$_mergedULN,'MergedFamilyName'=>$_mergedFamilyName,'MergedGivenName'=>$_mergedGivenName,'MergedDateOfBirth'=>$_mergedDateOfBirth,'MergedDate'=>$_mergedDate),false);
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
     * Get MergedULN value
     * @return string
     */
    public function getMergedULN()
    {
        return $this->MergedULN;
    }
    /**
     * Set MergedULN value
     * @param string $_mergedULN the MergedULN
     * @return string
     */
    public function setMergedULN($_mergedULN)
    {
        return ($this->MergedULN = $_mergedULN);
    }
    /**
     * Get MergedFamilyName value
     * @return string
     */
    public function getMergedFamilyName()
    {
        return $this->MergedFamilyName;
    }
    /**
     * Set MergedFamilyName value
     * @param string $_mergedFamilyName the MergedFamilyName
     * @return string
     */
    public function setMergedFamilyName($_mergedFamilyName)
    {
        return ($this->MergedFamilyName = $_mergedFamilyName);
    }
    /**
     * Get MergedGivenName value
     * @return string
     */
    public function getMergedGivenName()
    {
        return $this->MergedGivenName;
    }
    /**
     * Set MergedGivenName value
     * @param string $_mergedGivenName the MergedGivenName
     * @return string
     */
    public function setMergedGivenName($_mergedGivenName)
    {
        return ($this->MergedGivenName = $_mergedGivenName);
    }
    /**
     * Get MergedDateOfBirth value
     * @return string
     */
    public function getMergedDateOfBirth()
    {
        return $this->MergedDateOfBirth;
    }
    /**
     * Set MergedDateOfBirth value
     * @param string $_mergedDateOfBirth the MergedDateOfBirth
     * @return string
     */
    public function setMergedDateOfBirth($_mergedDateOfBirth)
    {
        return ($this->MergedDateOfBirth = $_mergedDateOfBirth);
    }
    /**
     * Get MergedDate value
     * @return string
     */
    public function getMergedDate()
    {
        return $this->MergedDate;
    }
    /**
     * Set MergedDate value
     * @param string $_mergedDate the MergedDate
     * @return string
     */
    public function setMergedDate($_mergedDate)
    {
        return ($this->MergedDate = $_mergedDate);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPMergedLearner
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
