<?php
/**
 * File for class MIAPStructLearnerRecordResp
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructLearnerRecordResp originally named LearnerRecordResp
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learnerrecord.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructLearnerRecordResp extends MIAPWsdlClass
{
    /**
     * The ResponseCode
     * @var string
     */
    public $ResponseCode;
    /**
     * The IncomingULN
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $IncomingULN;
    /**
     * The IncomingFamilyName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $IncomingFamilyName;
    /**
     * The IncomingGivenName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $IncomingGivenName;
    /**
     * The IncomingDateOfBirth
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $IncomingDateOfBirth;
    /**
     * The IncomingGender
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - maxLength : 1
     * - pattern : 0|1|2|9
     * @var string
     */
    public $IncomingGender;
    /**
     * The IncomingLastKnownPostCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - maxLength : 9
     * - pattern : [bB][fF][pP][oO] ?[0-9]{1,4} ? ? ? ?
     * @var string
     */
    public $IncomingLastKnownPostCode;
    /**
     * The LearnerRecord
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var MIAPStructMIAPLearnerRecord
     */
    public $LearnerRecord;
    /**
     * Constructor method for LearnerRecordResp
     * @see parent::__construct()
     * @param string $_responseCode
     * @param string $_incomingULN
     * @param string $_incomingFamilyName
     * @param string $_incomingGivenName
     * @param string $_incomingDateOfBirth
     * @param string $_incomingGender
     * @param string $_incomingLastKnownPostCode
     * @param MIAPStructMIAPLearnerRecord $_learnerRecord
     * @return MIAPStructLearnerRecordResp
     */
    public function __construct($_responseCode = NULL,$_incomingULN = NULL,$_incomingFamilyName = NULL,$_incomingGivenName = NULL,$_incomingDateOfBirth = NULL,$_incomingGender = NULL,$_incomingLastKnownPostCode = NULL,$_learnerRecord = NULL)
    {
        parent::__construct(array('ResponseCode'=>$_responseCode,'IncomingULN'=>$_incomingULN,'IncomingFamilyName'=>$_incomingFamilyName,'IncomingGivenName'=>$_incomingGivenName,'IncomingDateOfBirth'=>$_incomingDateOfBirth,'IncomingGender'=>$_incomingGender,'IncomingLastKnownPostCode'=>$_incomingLastKnownPostCode,'LearnerRecord'=>$_learnerRecord),false);
    }
    /**
     * Get ResponseCode value
     * @return string|null
     */
    public function getResponseCode()
    {
        return $this->ResponseCode;
    }
    /**
     * Set ResponseCode value
     * @param string $_responseCode the ResponseCode
     * @return string
     */
    public function setResponseCode($_responseCode)
    {
        return ($this->ResponseCode = $_responseCode);
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
     * Get IncomingFamilyName value
     * @return string|null
     */
    public function getIncomingFamilyName()
    {
        return $this->IncomingFamilyName;
    }
    /**
     * Set IncomingFamilyName value
     * @param string $_incomingFamilyName the IncomingFamilyName
     * @return string
     */
    public function setIncomingFamilyName($_incomingFamilyName)
    {
        return ($this->IncomingFamilyName = $_incomingFamilyName);
    }
    /**
     * Get IncomingGivenName value
     * @return string|null
     */
    public function getIncomingGivenName()
    {
        return $this->IncomingGivenName;
    }
    /**
     * Set IncomingGivenName value
     * @param string $_incomingGivenName the IncomingGivenName
     * @return string
     */
    public function setIncomingGivenName($_incomingGivenName)
    {
        return ($this->IncomingGivenName = $_incomingGivenName);
    }
    /**
     * Get IncomingDateOfBirth value
     * @return string|null
     */
    public function getIncomingDateOfBirth()
    {
        return $this->IncomingDateOfBirth;
    }
    /**
     * Set IncomingDateOfBirth value
     * @param string $_incomingDateOfBirth the IncomingDateOfBirth
     * @return string
     */
    public function setIncomingDateOfBirth($_incomingDateOfBirth)
    {
        return ($this->IncomingDateOfBirth = $_incomingDateOfBirth);
    }
    /**
     * Get IncomingGender value
     * @return string|null
     */
    public function getIncomingGender()
    {
        return $this->IncomingGender;
    }
    /**
     * Set IncomingGender value
     * @param string $_incomingGender the IncomingGender
     * @return string
     */
    public function setIncomingGender($_incomingGender)
    {
        return ($this->IncomingGender = $_incomingGender);
    }
    /**
     * Get IncomingLastKnownPostCode value
     * @return string|null
     */
    public function getIncomingLastKnownPostCode()
    {
        return $this->IncomingLastKnownPostCode;
    }
    /**
     * Set IncomingLastKnownPostCode value
     * @param string $_incomingLastKnownPostCode the IncomingLastKnownPostCode
     * @return string
     */
    public function setIncomingLastKnownPostCode($_incomingLastKnownPostCode)
    {
        return ($this->IncomingLastKnownPostCode = $_incomingLastKnownPostCode);
    }
    /**
     * Get LearnerRecord value
     * @return MIAPStructMIAPLearnerRecord|null
     */
    public function getLearnerRecord()
    {
        return $this->LearnerRecord;
    }
    /**
     * Set LearnerRecord value
     * @param MIAPStructMIAPLearnerRecord $_learnerRecord the LearnerRecord
     * @return MIAPStructMIAPLearnerRecord
     */
    public function setLearnerRecord($_learnerRecord)
    {
        return ($this->LearnerRecord = $_learnerRecord);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructLearnerRecordResp
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
