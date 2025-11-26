<?php
/**
 * File for class MIAPStructVerifyBatchRqst
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructVerifyBatchRqst originally named VerifyBatchRqst
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//verifybatchlearner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructVerifyBatchRqst extends MIAPWsdlClass
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
     * The LearnerRecordCount
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * - maxInclusive : 5000
     * - minInclusive : 1
     * @var int
     */
    public $LearnerRecordCount;
    /**
     * The Learner
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 1
     * - nillable : false
     * @var MIAPStructMIAPBatchLearnerToVerify
     */
    public $Learner;
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
     * The OrgEmail
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - nillable : true
     * - maxLength : 254
     * - pattern : [a-zA-Z0-9!#$%'\*\+\-/=\?\^_`\{\|\}~]+(\.[a-zA-Z0-9!#$%'\*\+\-/=\?\^_`\{\|\}~]+)*@[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9](\.[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])*\.[a-zA-Z]{2,6}
     * @var string
     */
    public $OrgEmail;
    /**
     * Constructor method for VerifyBatchRqst
     * @see parent::__construct()
     * @param string $_orgPassword
     * @param string $_userName
     * @param int $_learnerRecordCount
     * @param MIAPStructMIAPBatchLearnerToVerify $_learner
     * @param string $_organisationRef
     * @param string $_uKPRN
     * @param string $_orgEmail
     * @return MIAPStructVerifyBatchRqst
     */
    public function __construct($_orgPassword,$_userName,$_learnerRecordCount,$_learner,$_organisationRef = NULL,$_uKPRN = NULL,$_orgEmail = NULL)
    {
        parent::__construct(array('OrgPassword'=>$_orgPassword,'UserName'=>$_userName,'LearnerRecordCount'=>$_learnerRecordCount,'Learner'=>$_learner,'OrganisationRef'=>$_organisationRef,'UKPRN'=>$_uKPRN,'OrgEmail'=>$_orgEmail),false);
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
     * Get LearnerRecordCount value
     * @return int
     */
    public function getLearnerRecordCount()
    {
        return $this->LearnerRecordCount;
    }
    /**
     * Set LearnerRecordCount value
     * @param int $_learnerRecordCount the LearnerRecordCount
     * @return int
     */
    public function setLearnerRecordCount($_learnerRecordCount)
    {
        return ($this->LearnerRecordCount = $_learnerRecordCount);
    }
    /**
     * Get Learner value
     * @return MIAPStructMIAPBatchLearnerToVerify
     */
    public function getLearner()
    {
        return $this->Learner;
    }
    /**
     * Set Learner value
     * @param MIAPStructMIAPBatchLearnerToVerify $_learner the Learner
     * @return MIAPStructMIAPBatchLearnerToVerify
     */
    public function setLearner($_learner)
    {
        return ($this->Learner = $_learner);
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
     * Get OrgEmail value
     * @return string|null
     */
    public function getOrgEmail()
    {
        return $this->OrgEmail;
    }
    /**
     * Set OrgEmail value
     * @param string $_orgEmail the OrgEmail
     * @return string
     */
    public function setOrgEmail($_orgEmail)
    {
        return ($this->OrgEmail = $_orgEmail);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructVerifyBatchRqst
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
