<?php
/**
 * File for class MIAPStructULNReportRqst
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructULNReportRqst originally named ULNReportRqst
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learnerreport.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructULNReportRqst extends MIAPWsdlClass
{
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
     * The FromDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $FromDate;
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
     * The ToDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - nillable : false
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $ToDate;
    /**
     * Constructor method for ULNReportRqst
     * @see parent::__construct()
     * @param string $_orgPassword
     * @param string $_userName
     * @param string $_fromDate
     * @param string $_organisationRef
     * @param string $_uKPRN
     * @param string $_toDate
     * @return MIAPStructULNReportRqst
     */
    public function __construct($_orgPassword,$_userName,$_fromDate,$_organisationRef = NULL,$_uKPRN = NULL,$_toDate = NULL)
    {
        parent::__construct(array('OrgPassword'=>$_orgPassword,'UserName'=>$_userName,'FromDate'=>$_fromDate,'OrganisationRef'=>$_organisationRef,'UKPRN'=>$_uKPRN,'ToDate'=>$_toDate),false);
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
     * Get FromDate value
     * @return string
     */
    public function getFromDate()
    {
        return $this->FromDate;
    }
    /**
     * Set FromDate value
     * @param string $_fromDate the FromDate
     * @return string
     */
    public function setFromDate($_fromDate)
    {
        return ($this->FromDate = $_fromDate);
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
     * Get ToDate value
     * @return string|null
     */
    public function getToDate()
    {
        return $this->ToDate;
    }
    /**
     * Set ToDate value
     * @param string $_toDate the ToDate
     * @return string
     */
    public function setToDate($_toDate)
    {
        return ($this->ToDate = $_toDate);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructULNReportRqst
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
