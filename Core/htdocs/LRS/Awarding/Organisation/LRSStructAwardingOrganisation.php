<?php
/**
 * File for class LRSStructAwardingOrganisation
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructAwardingOrganisation originally named AwardingOrganisation
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructAwardingOrganisation extends LRSStructReplicatedBusinessObject
{
    /**
     * The Acronym
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Acronym;
    /**
     * The Name
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Name;
    /**
     * The Number
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Number;
    /**
     * The Status
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var LRSEnumAwardingOrganisationStatus
     */
    public $Status;
    /**
     * The TradingName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $TradingName;
    /**
     * The Ukprn
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Ukprn;
    /**
     * The Website
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Website;
    /**
     * Constructor method for AwardingOrganisation
     * @see parent::__construct()
     * @param string $_acronym
     * @param string $_name
     * @param string $_number
     * @param LRSEnumAwardingOrganisationStatus $_status
     * @param string $_tradingName
     * @param string $_ukprn
     * @param string $_website
     * @return LRSStructAwardingOrganisation
     */
    public function __construct($_acronym = NULL,$_name = NULL,$_number = NULL,$_status = NULL,$_tradingName = NULL,$_ukprn = NULL,$_website = NULL)
    {
        LRSWsdlClass::__construct(array('Acronym'=>$_acronym,'Name'=>$_name,'Number'=>$_number,'Status'=>$_status,'TradingName'=>$_tradingName,'Ukprn'=>$_ukprn,'Website'=>$_website),false);
    }
    /**
     * Get Acronym value
     * @return string|null
     */
    public function getAcronym()
    {
        return $this->Acronym;
    }
    /**
     * Set Acronym value
     * @param string $_acronym the Acronym
     * @return string
     */
    public function setAcronym($_acronym)
    {
        return ($this->Acronym = $_acronym);
    }
    /**
     * Get Name value
     * @return string|null
     */
    public function getName()
    {
        return $this->Name;
    }
    /**
     * Set Name value
     * @param string $_name the Name
     * @return string
     */
    public function setName($_name)
    {
        return ($this->Name = $_name);
    }
    /**
     * Get Number value
     * @return string|null
     */
    public function getNumber()
    {
        return $this->Number;
    }
    /**
     * Set Number value
     * @param string $_number the Number
     * @return string
     */
    public function setNumber($_number)
    {
        return ($this->Number = $_number);
    }
    /**
     * Get Status value
     * @return LRSEnumAwardingOrganisationStatus|null
     */
    public function getStatus()
    {
        return $this->Status;
    }
    /**
     * Set Status value
     * @uses LRSEnumAwardingOrganisationStatus::valueIsValid()
     * @param LRSEnumAwardingOrganisationStatus $_status the Status
     * @return LRSEnumAwardingOrganisationStatus
     */
    public function setStatus($_status)
    {
        if(!LRSEnumAwardingOrganisationStatus::valueIsValid($_status))
        {
            return false;
        }
        return ($this->Status = $_status);
    }
    /**
     * Get TradingName value
     * @return string|null
     */
    public function getTradingName()
    {
        return $this->TradingName;
    }
    /**
     * Set TradingName value
     * @param string $_tradingName the TradingName
     * @return string
     */
    public function setTradingName($_tradingName)
    {
        return ($this->TradingName = $_tradingName);
    }
    /**
     * Get Ukprn value
     * @return string|null
     */
    public function getUkprn()
    {
        return $this->Ukprn;
    }
    /**
     * Set Ukprn value
     * @param string $_ukprn the Ukprn
     * @return string
     */
    public function setUkprn($_ukprn)
    {
        return ($this->Ukprn = $_ukprn);
    }
    /**
     * Get Website value
     * @return string|null
     */
    public function getWebsite()
    {
        return $this->Website;
    }
    /**
     * Set Website value
     * @param string $_website the Website
     * @return string
     */
    public function setWebsite($_website)
    {
        return ($this->Website = $_website);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructAwardingOrganisation
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
