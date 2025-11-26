<?php
/**
 * File for class LogicMelonStructGetValue
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetValue originally named GetValue
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetValue extends LogicMelonWsdlClass
{
    /**
     * The FieldID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $FieldID;
    /**
     * The OrganisationID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $OrganisationID;
    /**
     * The SortOrder
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var int
     */
    public $SortOrder;
    /**
     * The CultureID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $CultureID;
    /**
     * The Description
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Description;
    /**
     * The Value
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Value;
    /**
     * Constructor method for GetValue
     * @see parent::__construct()
     * @param int $_fieldID
     * @param int $_organisationID
     * @param int $_sortOrder
     * @param string $_cultureID
     * @param string $_description
     * @param string $_value
     * @return LogicMelonStructGetValue
     */
    public function __construct($_fieldID,$_organisationID,$_sortOrder,$_cultureID = NULL,$_description = NULL,$_value = NULL)
    {
        parent::__construct(array('FieldID'=>$_fieldID,'OrganisationID'=>$_organisationID,'SortOrder'=>$_sortOrder,'CultureID'=>$_cultureID,'Description'=>$_description,'Value'=>$_value),false);
    }
    /**
     * Get FieldID value
     * @return int
     */
    public function getFieldID()
    {
        return $this->FieldID;
    }
    /**
     * Set FieldID value
     * @param int $_fieldID the FieldID
     * @return int
     */
    public function setFieldID($_fieldID)
    {
        return ($this->FieldID = $_fieldID);
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
     * Get SortOrder value
     * @return int
     */
    public function getSortOrder()
    {
        return $this->SortOrder;
    }
    /**
     * Set SortOrder value
     * @param int $_sortOrder the SortOrder
     * @return int
     */
    public function setSortOrder($_sortOrder)
    {
        return ($this->SortOrder = $_sortOrder);
    }
    /**
     * Get CultureID value
     * @return string|null
     */
    public function getCultureID()
    {
        return $this->CultureID;
    }
    /**
     * Set CultureID value
     * @param string $_cultureID the CultureID
     * @return string
     */
    public function setCultureID($_cultureID)
    {
        return ($this->CultureID = $_cultureID);
    }
    /**
     * Get Description value
     * @return string|null
     */
    public function getDescription()
    {
        return $this->Description;
    }
    /**
     * Set Description value
     * @param string $_description the Description
     * @return string
     */
    public function setDescription($_description)
    {
        return ($this->Description = $_description);
    }
    /**
     * Get Value value
     * @return string|null
     */
    public function getValue()
    {
        return $this->Value;
    }
    /**
     * Set Value value
     * @param string $_value the Value
     * @return string
     */
    public function setValue($_value)
    {
        return ($this->Value = $_value);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetValue
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
