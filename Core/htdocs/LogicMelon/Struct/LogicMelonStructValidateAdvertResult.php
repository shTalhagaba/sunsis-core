<?php
/**
 * File for class LogicMelonStructValidateAdvertResult
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructValidateAdvertResult originally named ValidateAdvertResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructValidateAdvertResult extends LogicMelonWsdlClass
{
    /**
     * The Valid
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var LogicMelonEnumValidateAdvertValid
     */
    public $Valid;
    /**
     * The MarkedForDelivery
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $MarkedForDelivery;
    /**
     * The FieldIDsValid
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfInt
     */
    public $FieldIDsValid;
    /**
     * The FieldIDsWarning
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfInt
     */
    public $FieldIDsWarning;
    /**
     * The FieldIDsInvalid
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfInt
     */
    public $FieldIDsInvalid;
    /**
     * The Destinations
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfInt
     */
    public $Destinations;
    /**
     * The ValidationResults
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfValidateFieldResult
     */
    public $ValidationResults;
    /**
     * Constructor method for ValidateAdvertResult
     * @see parent::__construct()
     * @param LogicMelonEnumValidateAdvertValid $_valid
     * @param boolean $_markedForDelivery
     * @param LogicMelonStructArrayOfInt $_fieldIDsValid
     * @param LogicMelonStructArrayOfInt $_fieldIDsWarning
     * @param LogicMelonStructArrayOfInt $_fieldIDsInvalid
     * @param LogicMelonStructArrayOfInt $_destinations
     * @param LogicMelonStructArrayOfValidateFieldResult $_validationResults
     * @return LogicMelonStructValidateAdvertResult
     */
    public function __construct($_valid,$_markedForDelivery,$_fieldIDsValid = NULL,$_fieldIDsWarning = NULL,$_fieldIDsInvalid = NULL,$_destinations = NULL,$_validationResults = NULL)
    {
        parent::__construct(array('Valid'=>$_valid,'MarkedForDelivery'=>$_markedForDelivery,'FieldIDsValid'=>($_fieldIDsValid instanceof LogicMelonStructArrayOfInt)?$_fieldIDsValid:new LogicMelonStructArrayOfInt($_fieldIDsValid),'FieldIDsWarning'=>($_fieldIDsWarning instanceof LogicMelonStructArrayOfInt)?$_fieldIDsWarning:new LogicMelonStructArrayOfInt($_fieldIDsWarning),'FieldIDsInvalid'=>($_fieldIDsInvalid instanceof LogicMelonStructArrayOfInt)?$_fieldIDsInvalid:new LogicMelonStructArrayOfInt($_fieldIDsInvalid),'Destinations'=>($_destinations instanceof LogicMelonStructArrayOfInt)?$_destinations:new LogicMelonStructArrayOfInt($_destinations),'ValidationResults'=>($_validationResults instanceof LogicMelonStructArrayOfValidateFieldResult)?$_validationResults:new LogicMelonStructArrayOfValidateFieldResult($_validationResults)),false);
    }
    /**
     * Get Valid value
     * @return LogicMelonEnumValidateAdvertValid
     */
    public function getValid()
    {
        return $this->Valid;
    }
    /**
     * Set Valid value
     * @uses LogicMelonEnumValidateAdvertValid::valueIsValid()
     * @param LogicMelonEnumValidateAdvertValid $_valid the Valid
     * @return LogicMelonEnumValidateAdvertValid
     */
    public function setValid($_valid)
    {
        if(!LogicMelonEnumValidateAdvertValid::valueIsValid($_valid))
        {
            return false;
        }
        return ($this->Valid = $_valid);
    }
    /**
     * Get MarkedForDelivery value
     * @return boolean
     */
    public function getMarkedForDelivery()
    {
        return $this->MarkedForDelivery;
    }
    /**
     * Set MarkedForDelivery value
     * @param boolean $_markedForDelivery the MarkedForDelivery
     * @return boolean
     */
    public function setMarkedForDelivery($_markedForDelivery)
    {
        return ($this->MarkedForDelivery = $_markedForDelivery);
    }
    /**
     * Get FieldIDsValid value
     * @return LogicMelonStructArrayOfInt|null
     */
    public function getFieldIDsValid()
    {
        return $this->FieldIDsValid;
    }
    /**
     * Set FieldIDsValid value
     * @param LogicMelonStructArrayOfInt $_fieldIDsValid the FieldIDsValid
     * @return LogicMelonStructArrayOfInt
     */
    public function setFieldIDsValid($_fieldIDsValid)
    {
        return ($this->FieldIDsValid = $_fieldIDsValid);
    }
    /**
     * Get FieldIDsWarning value
     * @return LogicMelonStructArrayOfInt|null
     */
    public function getFieldIDsWarning()
    {
        return $this->FieldIDsWarning;
    }
    /**
     * Set FieldIDsWarning value
     * @param LogicMelonStructArrayOfInt $_fieldIDsWarning the FieldIDsWarning
     * @return LogicMelonStructArrayOfInt
     */
    public function setFieldIDsWarning($_fieldIDsWarning)
    {
        return ($this->FieldIDsWarning = $_fieldIDsWarning);
    }
    /**
     * Get FieldIDsInvalid value
     * @return LogicMelonStructArrayOfInt|null
     */
    public function getFieldIDsInvalid()
    {
        return $this->FieldIDsInvalid;
    }
    /**
     * Set FieldIDsInvalid value
     * @param LogicMelonStructArrayOfInt $_fieldIDsInvalid the FieldIDsInvalid
     * @return LogicMelonStructArrayOfInt
     */
    public function setFieldIDsInvalid($_fieldIDsInvalid)
    {
        return ($this->FieldIDsInvalid = $_fieldIDsInvalid);
    }
    /**
     * Get Destinations value
     * @return LogicMelonStructArrayOfInt|null
     */
    public function getDestinations()
    {
        return $this->Destinations;
    }
    /**
     * Set Destinations value
     * @param LogicMelonStructArrayOfInt $_destinations the Destinations
     * @return LogicMelonStructArrayOfInt
     */
    public function setDestinations($_destinations)
    {
        return ($this->Destinations = $_destinations);
    }
    /**
     * Get ValidationResults value
     * @return LogicMelonStructArrayOfValidateFieldResult|null
     */
    public function getValidationResults()
    {
        return $this->ValidationResults;
    }
    /**
     * Set ValidationResults value
     * @param LogicMelonStructArrayOfValidateFieldResult $_validationResults the ValidationResults
     * @return LogicMelonStructArrayOfValidateFieldResult
     */
    public function setValidationResults($_validationResults)
    {
        return ($this->ValidationResults = $_validationResults);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructValidateAdvertResult
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
