<?php
/**
 * File for class LogicMelonStructAPIAdvertValue
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAPIAdvertValue originally named APIAdvertValue
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAPIAdvertValue extends LogicMelonWsdlClass
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
     * The FeedID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $FeedID;
    /**
     * The FieldID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $FieldID;
    /**
     * The SchemaIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SchemaIdentifier;
    /**
     * The FieldIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FieldIdentifier;
    /**
     * The Values
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfString
     */
    public $Values;
    /**
     * The Value
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Value;
    /**
     * Constructor method for APIAdvertValue
     * @see parent::__construct()
     * @param int $_advertID
     * @param int $_feedID
     * @param int $_fieldID
     * @param string $_schemaIdentifier
     * @param string $_fieldIdentifier
     * @param LogicMelonStructArrayOfString $_values
     * @param string $_value
     * @return LogicMelonStructAPIAdvertValue
     */
    public function __construct($_advertID,$_feedID,$_fieldID,$_schemaIdentifier = NULL,$_fieldIdentifier = NULL,$_values = NULL,$_value = NULL)
    {
        parent::__construct(array('AdvertID'=>$_advertID,'FeedID'=>$_feedID,'FieldID'=>$_fieldID,'SchemaIdentifier'=>$_schemaIdentifier,'FieldIdentifier'=>$_fieldIdentifier,'Values'=>($_values instanceof LogicMelonStructArrayOfString)?$_values:new LogicMelonStructArrayOfString($_values),'Value'=>$_value),false);
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
     * Get FeedID value
     * @return int
     */
    public function getFeedID()
    {
        return $this->FeedID;
    }
    /**
     * Set FeedID value
     * @param int $_feedID the FeedID
     * @return int
     */
    public function setFeedID($_feedID)
    {
        return ($this->FeedID = $_feedID);
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
     * Get SchemaIdentifier value
     * @return string|null
     */
    public function getSchemaIdentifier()
    {
        return $this->SchemaIdentifier;
    }
    /**
     * Set SchemaIdentifier value
     * @param string $_schemaIdentifier the SchemaIdentifier
     * @return string
     */
    public function setSchemaIdentifier($_schemaIdentifier)
    {
        return ($this->SchemaIdentifier = $_schemaIdentifier);
    }
    /**
     * Get FieldIdentifier value
     * @return string|null
     */
    public function getFieldIdentifier()
    {
        return $this->FieldIdentifier;
    }
    /**
     * Set FieldIdentifier value
     * @param string $_fieldIdentifier the FieldIdentifier
     * @return string
     */
    public function setFieldIdentifier($_fieldIdentifier)
    {
        return ($this->FieldIdentifier = $_fieldIdentifier);
    }
    /**
     * Get Values value
     * @return LogicMelonStructArrayOfString|null
     */
    public function getValues()
    {
        return $this->Values;
    }
    /**
     * Set Values value
     * @param LogicMelonStructArrayOfString $_values the Values
     * @return LogicMelonStructArrayOfString
     */
    public function setValues($_values)
    {
        return ($this->Values = $_values);
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
     * @return LogicMelonStructAPIAdvertValue
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
