<?php
/**
 * File for class LogicMelonStructQueryJobTitle
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructQueryJobTitle originally named QueryJobTitle
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructQueryJobTitle extends LogicMelonWsdlClass
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
     * The Industry
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Industry;
    /**
     * Constructor method for QueryJobTitle
     * @see parent::__construct()
     * @param string $_sCultureID
     * @param string $_sAPIKey
     * @param string $_q
     * @param string $_industry
     * @return LogicMelonStructQueryJobTitle
     */
    public function __construct($_sCultureID = NULL,$_sAPIKey = NULL,$_q = NULL,$_industry = NULL)
    {
        parent::__construct(array('sCultureID'=>$_sCultureID,'sAPIKey'=>$_sAPIKey,'q'=>$_q,'Industry'=>$_industry),false);
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
     * Get Industry value
     * @return string|null
     */
    public function getIndustry()
    {
        return $this->Industry;
    }
    /**
     * Set Industry value
     * @param string $_industry the Industry
     * @return string
     */
    public function setIndustry($_industry)
    {
        return ($this->Industry = $_industry);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructQueryJobTitle
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
