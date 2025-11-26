<?php
/**
 * File for class LogicMelonStructUserFeedsAndQuotaWithDestinations
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructUserFeedsAndQuotaWithDestinations originally named UserFeedsAndQuotaWithDestinations
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructUserFeedsAndQuotaWithDestinations extends LogicMelonWsdlClass
{
    /**
     * The sCultureID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sCultureID;
    /**
     * The sAPIKey
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sAPIKey;
    /**
     * The sUsername
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sUsername;
    /**
     * The sPassword
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $sPassword;
    /**
     * The Destinations
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfString
     */
    public $Destinations;
    /**
     * Constructor method for UserFeedsAndQuotaWithDestinations
     * @see parent::__construct()
     * @param string $_sCultureID
     * @param string $_sAPIKey
     * @param string $_sUsername
     * @param string $_sPassword
     * @param LogicMelonStructArrayOfString $_destinations
     * @return LogicMelonStructUserFeedsAndQuotaWithDestinations
     */
    public function __construct($_sCultureID = NULL,$_sAPIKey = NULL,$_sUsername = NULL,$_sPassword = NULL,$_destinations = NULL)
    {
        parent::__construct(array('sCultureID'=>$_sCultureID,'sAPIKey'=>$_sAPIKey,'sUsername'=>$_sUsername,'sPassword'=>$_sPassword,'Destinations'=>($_destinations instanceof LogicMelonStructArrayOfString)?$_destinations:new LogicMelonStructArrayOfString($_destinations)),false);
    }
    /**
     * Get sCultureID value
     * @return string|null
     */
    public function getSCultureID()
    {
        return $this->sCultureID;
    }
    /**
     * Set sCultureID value
     * @param string $_sCultureID the sCultureID
     * @return string
     */
    public function setSCultureID($_sCultureID)
    {
        return ($this->sCultureID = $_sCultureID);
    }
    /**
     * Get sAPIKey value
     * @return string|null
     */
    public function getSAPIKey()
    {
        return $this->sAPIKey;
    }
    /**
     * Set sAPIKey value
     * @param string $_sAPIKey the sAPIKey
     * @return string
     */
    public function setSAPIKey($_sAPIKey)
    {
        return ($this->sAPIKey = $_sAPIKey);
    }
    /**
     * Get sUsername value
     * @return string|null
     */
    public function getSUsername()
    {
        return $this->sUsername;
    }
    /**
     * Set sUsername value
     * @param string $_sUsername the sUsername
     * @return string
     */
    public function setSUsername($_sUsername)
    {
        return ($this->sUsername = $_sUsername);
    }
    /**
     * Get sPassword value
     * @return string|null
     */
    public function getSPassword()
    {
        return $this->sPassword;
    }
    /**
     * Set sPassword value
     * @param string $_sPassword the sPassword
     * @return string
     */
    public function setSPassword($_sPassword)
    {
        return ($this->sPassword = $_sPassword);
    }
    /**
     * Get Destinations value
     * @return LogicMelonStructArrayOfString|null
     */
    public function getDestinations()
    {
        return $this->Destinations;
    }
    /**
     * Set Destinations value
     * @param LogicMelonStructArrayOfString $_destinations the Destinations
     * @return LogicMelonStructArrayOfString
     */
    public function setDestinations($_destinations)
    {
        return ($this->Destinations = $_destinations);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructUserFeedsAndQuotaWithDestinations
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
