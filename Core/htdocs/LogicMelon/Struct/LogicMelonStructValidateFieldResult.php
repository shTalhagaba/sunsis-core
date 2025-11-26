<?php
/**
 * File for class LogicMelonStructValidateFieldResult
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructValidateFieldResult originally named ValidateFieldResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructValidateFieldResult extends LogicMelonWsdlClass
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
     * The Valid
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $Valid;
    /**
     * The FieldIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FieldIdentifier;
    /**
     * The FieldLabel
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FieldLabel;
    /**
     * The FieldComments
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FieldComments;
    /**
     * The Comments
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Comments;
    /**
     * The CultureID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $CultureID;
    /**
     * The Message
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Message;
    /**
     * Constructor method for ValidateFieldResult
     * @see parent::__construct()
     * @param int $_fieldID
     * @param boolean $_valid
     * @param string $_fieldIdentifier
     * @param string $_fieldLabel
     * @param string $_fieldComments
     * @param string $_comments
     * @param string $_cultureID
     * @param string $_message
     * @return LogicMelonStructValidateFieldResult
     */
    public function __construct($_fieldID,$_valid,$_fieldIdentifier = NULL,$_fieldLabel = NULL,$_fieldComments = NULL,$_comments = NULL,$_cultureID = NULL,$_message = NULL)
    {
        parent::__construct(array('FieldID'=>$_fieldID,'Valid'=>$_valid,'FieldIdentifier'=>$_fieldIdentifier,'FieldLabel'=>$_fieldLabel,'FieldComments'=>$_fieldComments,'Comments'=>$_comments,'CultureID'=>$_cultureID,'Message'=>$_message),false);
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
     * Get Valid value
     * @return boolean
     */
    public function getValid()
    {
        return $this->Valid;
    }
    /**
     * Set Valid value
     * @param boolean $_valid the Valid
     * @return boolean
     */
    public function setValid($_valid)
    {
        return ($this->Valid = $_valid);
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
     * Get FieldLabel value
     * @return string|null
     */
    public function getFieldLabel()
    {
        return $this->FieldLabel;
    }
    /**
     * Set FieldLabel value
     * @param string $_fieldLabel the FieldLabel
     * @return string
     */
    public function setFieldLabel($_fieldLabel)
    {
        return ($this->FieldLabel = $_fieldLabel);
    }
    /**
     * Get FieldComments value
     * @return string|null
     */
    public function getFieldComments()
    {
        return $this->FieldComments;
    }
    /**
     * Set FieldComments value
     * @param string $_fieldComments the FieldComments
     * @return string
     */
    public function setFieldComments($_fieldComments)
    {
        return ($this->FieldComments = $_fieldComments);
    }
    /**
     * Get Comments value
     * @return string|null
     */
    public function getComments()
    {
        return $this->Comments;
    }
    /**
     * Set Comments value
     * @param string $_comments the Comments
     * @return string
     */
    public function setComments($_comments)
    {
        return ($this->Comments = $_comments);
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
     * Get Message value
     * @return string|null
     */
    public function getMessage()
    {
        return $this->Message;
    }
    /**
     * Set Message value
     * @param string $_message the Message
     * @return string
     */
    public function setMessage($_message)
    {
        return ($this->Message = $_message);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructValidateFieldResult
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
