<?php
/**
 * File for class MIAPStructVerifyLearnerRqst
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructVerifyLearnerRqst originally named VerifyLearnerRqst
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learnerreport.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructVerifyLearnerRqst extends MIAPWsdlClass
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
     * The LearnerToVerify
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * @var MIAPStructMIAPLearnerToVerify
     */
    public $LearnerToVerify;
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
     * Constructor method for VerifyLearnerRqst
     * @see parent::__construct()
     * @param string $_orgPassword
     * @param string $_userName
     * @param MIAPStructMIAPLearnerToVerify $_learnerToVerify
     * @param string $_organisationRef
     * @param string $_uKPRN
     * @return MIAPStructVerifyLearnerRqst
     */
    public function __construct($_orgPassword,$_userName,$_learnerToVerify,$_organisationRef = NULL,$_uKPRN = NULL)
    {
        parent::__construct(array('OrgPassword'=>$_orgPassword,'UserName'=>$_userName,'LearnerToVerify'=>$_learnerToVerify,'OrganisationRef'=>$_organisationRef,'UKPRN'=>$_uKPRN),false);
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
     * Get LearnerToVerify value
     * @return MIAPStructMIAPLearnerToVerify
     */
    public function getLearnerToVerify()
    {
        return $this->LearnerToVerify;
    }
    /**
     * Set LearnerToVerify value
     * @param MIAPStructMIAPLearnerToVerify $_learnerToVerify the LearnerToVerify
     * @return MIAPStructMIAPLearnerToVerify
     */
    public function setLearnerToVerify($_learnerToVerify)
    {
        return ($this->LearnerToVerify = $_learnerToVerify);
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
     * @return MIAPStructVerifyLearnerRqst
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
