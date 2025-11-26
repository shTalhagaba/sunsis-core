<?php
/**
 * File for class MIAPStructLearnerRecordRqst
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructLearnerRecordRqst originally named LearnerRecordRqst
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learnerrecord.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructLearnerRecordRqst extends MIAPWsdlClass
{
    /**
     * The OrganisationRef
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - pattern : [0-9a-zA-Z]{6}
     * @var string
     */
    public $OrganisationRef;
    /**
     * The UKPRN
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - pattern : [0-9a-zA-Z]{8}
     * @var string
     */
    public $UKPRN;
    /**
     * The OrgPassword
     * Meta informations extracted from the WSDL
     * - nillable : false
     * - maxLength : 16
     * - minLength : 16
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $OrgPassword;
    /**
     * The UserName
     * Meta informations extracted from the WSDL
     * - nillable : false
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $UserName;
    /**
     * The UserType
     * Meta informations extracted from the WSDL
     * - nillable : false
     * - pattern : LNR|ORG|SER
     * @var string
     */
    public $UserType;
    /**
     * The LNRContactType
     * Meta informations extracted from the WSDL
     * - nillable : false
     * - pattern : FTF|TEL|PST|WEB|EML|NKN
     * @var string
     */
    public $LNRContactType;
    /**
     * The ULN
     * Meta informations extracted from the WSDL
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $ULN;
    /**
     * The FamilyName
     * Meta informations extracted from the WSDL
     * - nillable : false
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $FamilyName;
    /**
     * The GivenName
     * Meta informations extracted from the WSDL
     * - nillable : false
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $GivenName;
    /**
     * The DateOfBirth
     * Meta informations extracted from the WSDL
     * - nillable : false
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $DateOfBirth;
    /**
     * The Gender
     * Meta informations extracted from the WSDL
     * - nillable : false
     * - maxLength : 1
     * - pattern : 0|1|2|9
     * @var string
     */
    public $Gender;
    /**
     * The LastKnownPostCode
     * Meta informations extracted from the WSDL
     * - nillable : false
     * - maxLength : 9
     * - pattern : [bB][fF][pP][oO] ?[0-9]{1,4} ? ? ? ?
     * @var string
     */
    public $LastKnownPostCode;
    /**
     * Constructor method for LearnerRecordRqst
     * @see parent::__construct()
     * @param string $_organisationRef
     * @param string $_uKPRN
     * @param string $_orgPassword
     * @param string $_userName
     * @param string $_userType
     * @param string $_lNRContactType
     * @param string $_uLN
     * @param string $_familyName
     * @param string $_givenName
     * @param string $_dateOfBirth
     * @param string $_gender
     * @param string $_lastKnownPostCode
     * @return MIAPStructLearnerRecordRqst
     */
    public function __construct($_organisationRef = NULL,$_uKPRN = NULL,$_orgPassword = NULL,$_userName = NULL,$_userType = NULL,$_lNRContactType = NULL,$_uLN = NULL,$_familyName = NULL,$_givenName = NULL,$_dateOfBirth = NULL,$_gender = NULL,$_lastKnownPostCode = NULL)
    {
        parent::__construct(array('OrganisationRef'=>$_organisationRef,'UKPRN'=>$_uKPRN,'OrgPassword'=>$_orgPassword,'UserName'=>$_userName,'UserType'=>$_userType,'LNRContactType'=>$_lNRContactType,'ULN'=>$_uLN,'FamilyName'=>$_familyName,'GivenName'=>$_givenName,'DateOfBirth'=>$_dateOfBirth,'Gender'=>$_gender,'LastKnownPostCode'=>$_lastKnownPostCode),false);
    }
    /**
     * Get OrganisationRef value
     * @return string|null
     */
    public function getOrganisationRef()
    {
        return $this->OrganisationRef;
    }
    /**
     * Set OrganisationRef value
     * @param string $_organisationRef the OrganisationRef
     * @return string
     */
    public function setOrganisationRef($_organisationRef)
    {
        return ($this->OrganisationRef = $_organisationRef);
    }
    /**
     * Get UKPRN value
     * @return string|null
     */
    public function getUKPRN()
    {
        return $this->UKPRN;
    }
    /**
     * Set UKPRN value
     * @param string $_uKPRN the UKPRN
     * @return string
     */
    public function setUKPRN($_uKPRN)
    {
        return ($this->UKPRN = $_uKPRN);
    }
    /**
     * Get OrgPassword value
     * @return string|null
     */
    public function getOrgPassword()
    {
        return $this->OrgPassword;
    }
    /**
     * Set OrgPassword value
     * @param string $_orgPassword the OrgPassword
     * @return string
     */
    public function setOrgPassword($_orgPassword)
    {
        return ($this->OrgPassword = $_orgPassword);
    }
    /**
     * Get UserName value
     * @return string|null
     */
    public function getUserName()
    {
        return $this->UserName;
    }
    /**
     * Set UserName value
     * @param string $_userName the UserName
     * @return string
     */
    public function setUserName($_userName)
    {
        return ($this->UserName = $_userName);
    }
    /**
     * Get UserType value
     * @return string|null
     */
    public function getUserType()
    {
        return $this->UserType;
    }
    /**
     * Set UserType value
     * @param string $_userType the UserType
     * @return string
     */
    public function setUserType($_userType)
    {
        return ($this->UserType = $_userType);
    }
    /**
     * Get LNRContactType value
     * @return string|null
     */
    public function getLNRContactType()
    {
        return $this->LNRContactType;
    }
    /**
     * Set LNRContactType value
     * @param string $_lNRContactType the LNRContactType
     * @return string
     */
    public function setLNRContactType($_lNRContactType)
    {
        return ($this->LNRContactType = $_lNRContactType);
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
     * Get DateOfBirth value
     * @return string|null
     */
    public function getDateOfBirth()
    {
        return $this->DateOfBirth;
    }
    /**
     * Set DateOfBirth value
     * @param string $_dateOfBirth the DateOfBirth
     * @return string
     */
    public function setDateOfBirth($_dateOfBirth)
    {
        return ($this->DateOfBirth = $_dateOfBirth);
    }
    /**
     * Get Gender value
     * @return string|null
     */
    public function getGender()
    {
        return $this->Gender;
    }
    /**
     * Set Gender value
     * @param string $_gender the Gender
     * @return string
     */
    public function setGender($_gender)
    {
        return ($this->Gender = $_gender);
    }
    /**
     * Get LastKnownPostCode value
     * @return string|null
     */
    public function getLastKnownPostCode()
    {
        return $this->LastKnownPostCode;
    }
    /**
     * Set LastKnownPostCode value
     * @param string $_lastKnownPostCode the LastKnownPostCode
     * @return string
     */
    public function setLastKnownPostCode($_lastKnownPostCode)
    {
        return ($this->LastKnownPostCode = $_lastKnownPostCode);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructLearnerRecordRqst
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
