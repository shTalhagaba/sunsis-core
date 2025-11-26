<?php
/**
 * File for class MIAPStructLearnerByULNRqst
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructLearnerByULNRqst originally named LearnerByULNRqst
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//findlearner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructLearnerByULNRqst extends MIAPWsdlClass
{
    /**
     * The FindType
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * - pattern : FUL|CHK
     * @var string
     */
    public $FindType;
    /**
     * The OrgPassword
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
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
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $UserName;
    /**
     * The ULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
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
     * - minOccurs : 1
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
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * - maxLength : 35
     * - minLength : 1
     * - pattern : [\s]*[\S][\s\S]*
     * @var string
     */
    public $GivenName;
    /**
     * The OrganisationRef
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - nillable : true
     * - pattern : [0-9a-zA-Z]{6}
     * @var string
     */
    public $OrganisationRef;
    /**
     * The UKPRN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - nillable : true
     * - pattern : [0-9a-zA-Z]{8}
     * @var string
     */
    public $UKPRN;
    /**
     * Constructor method for LearnerByULNRqst
     * @see parent::__construct()
     * @param string $_findType
     * @param string $_orgPassword
     * @param string $_userName
     * @param string $_uLN
     * @param string $_familyName
     * @param string $_givenName
     * @param string $_organisationRef
     * @param string $_uKPRN
     * @return MIAPStructLearnerByULNRqst
     */
    public function __construct($_findType,$_orgPassword,$_userName,$_uLN,$_familyName,$_givenName,$_organisationRef = NULL,$_uKPRN = NULL)
    {
        parent::__construct(array('FindType'=>$_findType,'OrgPassword'=>$_orgPassword,'UserName'=>$_userName,'ULN'=>$_uLN,'FamilyName'=>$_familyName,'GivenName'=>$_givenName,'OrganisationRef'=>$_organisationRef,'UKPRN'=>$_uKPRN),false);
    }
    /**
     * Get FindType value
     * @return string
     */
    public function getFindType()
    {
        return $this->FindType;
    }
    /**
     * Set FindType value
     * @param string $_findType the FindType
     * @return string
     */
    public function setFindType($_findType)
    {
        return ($this->FindType = $_findType);
    }
    /**
     * Get OrgPassword value
     * @return string
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
     * @return string
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
     * Get FamilyName value
     * @return string
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
     * @return string
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
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructLearnerByULNRqst
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

	public function getXML(MIAPStructLearnerByULNRqst $obj)
	{
		$xml = <<<HEREDOC
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns="http://schemas.datacontract.org/2004/07/Amor.Qcf.Service.Interface" xmlns:tem="http://tempuri.org/">
   <soapenv:Header />
   <soapenv:Body>
      <tem:FindLearnerByULN>
         <tem:invokingOrganisation>
            <OrganisationRef>TEST05</OrganisationRef>
            <Password>P3rsp3ctiv358303</Password>
            <UKPRN>TEST0005</UKPRN>
         </tem:invokingOrganisation>
         <Username>TEST05</Username>
         <FindType>CHK</FindType>
         <OrgPassword>P3rsp3ctiv358303</OrgPassword>
         <ULN>1026893096</ULN>
         <FamilyName>Tucker</FamilyName>
         <GivenName>Darcie</GivenName>
      </tem:FindLearnerByULN>
   </soapenv:Body>
</soapenv:Envelope>
HEREDOC;
		return $xml;
	}
}
