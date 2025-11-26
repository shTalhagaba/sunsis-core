<?php
/**
 * File for class LogicMelonStructQueryLocations
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructQueryLocations originally named QueryLocations
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructQueryLocations extends LogicMelonWsdlClass
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
     * The q
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $q;
    /**
     * The prefix
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $prefix;
    /**
     * The priority
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $priority;
    /**
     * The LocationValue
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $LocationValue;
    /**
     * Constructor method for QueryLocations
     * @see parent::__construct()
     * @param string $_sCultureID
     * @param string $_sAPIKey
     * @param string $_q
     * @param string $_prefix
     * @param string $_priority
     * @param string $_locationValue
     * @return LogicMelonStructQueryLocations
     */
    public function __construct($_sCultureID = NULL,$_sAPIKey = NULL,$_q = NULL,$_prefix = NULL,$_priority = NULL,$_locationValue = NULL)
    {
        parent::__construct(array('sCultureID'=>$_sCultureID,'sAPIKey'=>$_sAPIKey,'q'=>$_q,'prefix'=>$_prefix,'priority'=>$_priority,'LocationValue'=>$_locationValue),false);
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
     * Get q value
     * @return string|null
     */
    public function getQ()
    {
        return $this->q;
    }
    /**
     * Set q value
     * @param string $_q the q
     * @return string
     */
    public function setQ($_q)
    {
        return ($this->q = $_q);
    }
    /**
     * Get prefix value
     * @return string|null
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
    /**
     * Set prefix value
     * @param string $_prefix the prefix
     * @return string
     */
    public function setPrefix($_prefix)
    {
        return ($this->prefix = $_prefix);
    }
    /**
     * Get priority value
     * @return string|null
     */
    public function getPriority()
    {
        return $this->priority;
    }
    /**
     * Set priority value
     * @param string $_priority the priority
     * @return string
     */
    public function setPriority($_priority)
    {
        return ($this->priority = $_priority);
    }
    /**
     * Get LocationValue value
     * @return string|null
     */
    public function getLocationValue()
    {
        return $this->LocationValue;
    }
    /**
     * Set LocationValue value
     * @param string $_locationValue the LocationValue
     * @return string
     */
    public function setLocationValue($_locationValue)
    {
        return ($this->LocationValue = $_locationValue);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructQueryLocations
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
