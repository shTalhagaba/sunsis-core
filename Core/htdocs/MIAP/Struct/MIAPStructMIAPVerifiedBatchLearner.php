<?php
/**
 * File for class MIAPStructMIAPVerifiedBatchLearner
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPVerifiedBatchLearner originally named MIAPVerifiedBatchLearner
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPVerifiedBatchLearner extends MIAPWsdlClass
{
    /**
     * The SearchedULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $SearchedULN;
    /**
     * The SearchedFamilyName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $SearchedFamilyName;
    /**
     * The SearchedGivenName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $SearchedGivenName;
    /**
     * The ResponseCode
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var string
     */
    public $ResponseCode;
    /**
     * The MISIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 128
     * @var string
     */
    public $MISIdentifier;
    /**
     * The SearchedDateOfBirth
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $SearchedDateOfBirth;
    /**
     * The SearchedGender
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 1
     * - pattern : 0|1|2|9
     * @var string
     */
    public $SearchedGender;
    /**
     * The ULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 10
     * - minLength : 10
     * - pattern : [1-9][0-9]{9}
     * @var string
     */
    public $ULN;
    /**
     * The FamilyName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $FamilyName;
    /**
     * The GivenName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $GivenName;
    /**
     * The DateOfBirth
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $DateOfBirth;
    /**
     * The Gender
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - maxLength : 1
     * - pattern : 0|1|2|9
     * @var string
     */
    public $Gender;
    /**
     * The FailureFlag
     * Meta informations extracted from the WSDL
     * - maxOccurs : 8
     * - minOccurs : 0
     * @var string
     */
    public $FailureFlag;
    /**
     * Constructor method for MIAPVerifiedBatchLearner
     * @see parent::__construct()
     * @param string $_searchedULN
     * @param string $_searchedFamilyName
     * @param string $_searchedGivenName
     * @param string $_responseCode
     * @param string $_mISIdentifier
     * @param string $_searchedDateOfBirth
     * @param string $_searchedGender
     * @param string $_uLN
     * @param string $_familyName
     * @param string $_givenName
     * @param string $_dateOfBirth
     * @param string $_gender
     * @param string $_failureFlag
     * @return MIAPStructMIAPVerifiedBatchLearner
     */
    public function __construct($_searchedULN,$_searchedFamilyName,$_searchedGivenName,$_responseCode,$_mISIdentifier = NULL,$_searchedDateOfBirth = NULL,$_searchedGender = NULL,$_uLN = NULL,$_familyName = NULL,$_givenName = NULL,$_dateOfBirth = NULL,$_gender = NULL,$_failureFlag = NULL)
    {
        parent::__construct(array('SearchedULN'=>$_searchedULN,'SearchedFamilyName'=>$_searchedFamilyName,'SearchedGivenName'=>$_searchedGivenName,'ResponseCode'=>$_responseCode,'MISIdentifier'=>$_mISIdentifier,'SearchedDateOfBirth'=>$_searchedDateOfBirth,'SearchedGender'=>$_searchedGender,'ULN'=>$_uLN,'FamilyName'=>$_familyName,'GivenName'=>$_givenName,'DateOfBirth'=>$_dateOfBirth,'Gender'=>$_gender,'FailureFlag'=>$_failureFlag),false);
    }
    /**
     * Get SearchedULN value
     * @return string
     */
    public function getSearchedULN()
    {
        return $this->SearchedULN;
    }
    /**
     * Set SearchedULN value
     * @param string $_searchedULN the SearchedULN
     * @return string
     */
    public function setSearchedULN($_searchedULN)
    {
        return ($this->SearchedULN = $_searchedULN);
    }
    /**
     * Get SearchedFamilyName value
     * @return string
     */
    public function getSearchedFamilyName()
    {
        return $this->SearchedFamilyName;
    }
    /**
     * Set SearchedFamilyName value
     * @param string $_searchedFamilyName the SearchedFamilyName
     * @return string
     */
    public function setSearchedFamilyName($_searchedFamilyName)
    {
        return ($this->SearchedFamilyName = $_searchedFamilyName);
    }
    /**
     * Get SearchedGivenName value
     * @return string
     */
    public function getSearchedGivenName()
    {
        return $this->SearchedGivenName;
    }
    /**
     * Set SearchedGivenName value
     * @param string $_searchedGivenName the SearchedGivenName
     * @return string
     */
    public function setSearchedGivenName($_searchedGivenName)
    {
        return ($this->SearchedGivenName = $_searchedGivenName);
    }
    /**
     * Get ResponseCode value
     * @return string
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
     * Get MISIdentifier value
     * @return string|null
     */
    public function getMISIdentifier()
    {
        return $this->MISIdentifier;
    }
    /**
     * Set MISIdentifier value
     * @param string $_mISIdentifier the MISIdentifier
     * @return string
     */
    public function setMISIdentifier($_mISIdentifier)
    {
        return ($this->MISIdentifier = $_mISIdentifier);
    }
    /**
     * Get SearchedDateOfBirth value
     * @return string|null
     */
    public function getSearchedDateOfBirth()
    {
        return $this->SearchedDateOfBirth;
    }
    /**
     * Set SearchedDateOfBirth value
     * @param string $_searchedDateOfBirth the SearchedDateOfBirth
     * @return string
     */
    public function setSearchedDateOfBirth($_searchedDateOfBirth)
    {
        return ($this->SearchedDateOfBirth = $_searchedDateOfBirth);
    }
    /**
     * Get SearchedGender value
     * @return string|null
     */
    public function getSearchedGender()
    {
        return $this->SearchedGender;
    }
    /**
     * Set SearchedGender value
     * @param string $_searchedGender the SearchedGender
     * @return string
     */
    public function setSearchedGender($_searchedGender)
    {
        return ($this->SearchedGender = $_searchedGender);
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
     * Get FailureFlag value
     * @return string|null
     */
    public function getFailureFlag()
    {
        return $this->FailureFlag;
    }
    /**
     * Set FailureFlag value
     * @param string $_failureFlag the FailureFlag
     * @return string
     */
    public function setFailureFlag($_failureFlag)
    {
        return ($this->FailureFlag = $_failureFlag);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPVerifiedBatchLearner
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
