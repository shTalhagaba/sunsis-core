<?php
/**
 * File for class LogicMelonStructAddAdvertResult
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAddAdvertResult originally named AddAdvertResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAddAdvertResult extends LogicMelonWsdlClass
{
    /**
     * The AdvertID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $AdvertID;
    /**
     * The UserID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $UserID;
    /**
     * The OrganisationID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $OrganisationID;
    /**
     * The RedirectUrl
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $RedirectUrl;
    /**
     * Constructor method for AddAdvertResult
     * @see parent::__construct()
     * @param int $_advertID
     * @param int $_userID
     * @param int $_organisationID
     * @param string $_redirectUrl
     * @return LogicMelonStructAddAdvertResult
     */
    public function __construct($_advertID,$_userID,$_organisationID,$_redirectUrl = NULL)
    {
        parent::__construct(array('AdvertID'=>$_advertID,'UserID'=>$_userID,'OrganisationID'=>$_organisationID,'RedirectUrl'=>$_redirectUrl),false);
    }
    /**
     * Get AdvertID value
     * @return int
     */
    public function getAdvertID()
    {
        return $this->AdvertID;
    }
    /**
     * Set AdvertID value
     * @param int $_advertID the AdvertID
     * @return int
     */
    public function setAdvertID($_advertID)
    {
        return ($this->AdvertID = $_advertID);
    }
    /**
     * Get UserID value
     * @return int
     */
    public function getUserID()
    {
        return $this->UserID;
    }
    /**
     * Set UserID value
     * @param int $_userID the UserID
     * @return int
     */
    public function setUserID($_userID)
    {
        return ($this->UserID = $_userID);
    }
    /**
     * Get OrganisationID value
     * @return int
     */
    public function getOrganisationID()
    {
        return $this->OrganisationID;
    }
    /**
     * Set OrganisationID value
     * @param int $_organisationID the OrganisationID
     * @return int
     */
    public function setOrganisationID($_organisationID)
    {
        return ($this->OrganisationID = $_organisationID);
    }
    /**
     * Get RedirectUrl value
     * @return string|null
     */
    public function getRedirectUrl()
    {
        return $this->RedirectUrl;
    }
    /**
     * Set RedirectUrl value
     * @param string $_redirectUrl the RedirectUrl
     * @return string
     */
    public function setRedirectUrl($_redirectUrl)
    {
        return ($this->RedirectUrl = $_redirectUrl);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAddAdvertResult
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
