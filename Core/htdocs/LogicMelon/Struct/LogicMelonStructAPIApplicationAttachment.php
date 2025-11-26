<?php
/**
 * File for class LogicMelonStructAPIApplicationAttachment
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAPIApplicationAttachment originally named APIApplicationAttachment
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAPIApplicationAttachment extends LogicMelonWsdlClass
{
    /**
     * The Document
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Document;
    /**
     * The DocumentBytes
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var base64Binary
     */
    public $DocumentBytes;
    /**
     * The FileName
     * @var string
     */
    public $FileName;
    /**
     * The DocumentType
     * @var string
     */
    public $DocumentType;
    /**
     * The EncodingType
     * @var string
     */
    public $EncodingType;
    /**
     * The DocumentSource
     * @var string
     */
    public $DocumentSource;
    /**
     * The DocumentFormat
     * @var string
     */
    public $DocumentFormat;
    /**
     * Constructor method for APIApplicationAttachment
     * @see parent::__construct()
     * @param string $_document
     * @param base64Binary $_documentBytes
     * @param string $_fileName
     * @param string $_documentType
     * @param string $_encodingType
     * @param string $_documentSource
     * @param string $_documentFormat
     * @return LogicMelonStructAPIApplicationAttachment
     */
    public function __construct($_document = NULL,$_documentBytes = NULL,$_fileName = NULL,$_documentType = NULL,$_encodingType = NULL,$_documentSource = NULL,$_documentFormat = NULL)
    {
        parent::__construct(array('Document'=>$_document,'DocumentBytes'=>$_documentBytes,'FileName'=>$_fileName,'DocumentType'=>$_documentType,'EncodingType'=>$_encodingType,'DocumentSource'=>$_documentSource,'DocumentFormat'=>$_documentFormat),false);
    }
    /**
     * Get Document value
     * @return string|null
     */
    public function getDocument()
    {
        return $this->Document;
    }
    /**
     * Set Document value
     * @param string $_document the Document
     * @return string
     */
    public function setDocument($_document)
    {
        return ($this->Document = $_document);
    }
    /**
     * Get DocumentBytes value
     * @return base64Binary|null
     */
    public function getDocumentBytes()
    {
        return $this->DocumentBytes;
    }
    /**
     * Set DocumentBytes value
     * @param base64Binary $_documentBytes the DocumentBytes
     * @return base64Binary
     */
    public function setDocumentBytes($_documentBytes)
    {
        return ($this->DocumentBytes = $_documentBytes);
    }
    /**
     * Get FileName value
     * @return string|null
     */
    public function getFileName()
    {
        return $this->FileName;
    }
    /**
     * Set FileName value
     * @param string $_fileName the FileName
     * @return string
     */
    public function setFileName($_fileName)
    {
        return ($this->FileName = $_fileName);
    }
    /**
     * Get DocumentType value
     * @return string|null
     */
    public function getDocumentType()
    {
        return $this->DocumentType;
    }
    /**
     * Set DocumentType value
     * @param string $_documentType the DocumentType
     * @return string
     */
    public function setDocumentType($_documentType)
    {
        return ($this->DocumentType = $_documentType);
    }
    /**
     * Get EncodingType value
     * @return string|null
     */
    public function getEncodingType()
    {
        return $this->EncodingType;
    }
    /**
     * Set EncodingType value
     * @param string $_encodingType the EncodingType
     * @return string
     */
    public function setEncodingType($_encodingType)
    {
        return ($this->EncodingType = $_encodingType);
    }
    /**
     * Get DocumentSource value
     * @return string|null
     */
    public function getDocumentSource()
    {
        return $this->DocumentSource;
    }
    /**
     * Set DocumentSource value
     * @param string $_documentSource the DocumentSource
     * @return string
     */
    public function setDocumentSource($_documentSource)
    {
        return ($this->DocumentSource = $_documentSource);
    }
    /**
     * Get DocumentFormat value
     * @return string|null
     */
    public function getDocumentFormat()
    {
        return $this->DocumentFormat;
    }
    /**
     * Set DocumentFormat value
     * @param string $_documentFormat the DocumentFormat
     * @return string
     */
    public function setDocumentFormat($_documentFormat)
    {
        return ($this->DocumentFormat = $_documentFormat);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAPIApplicationAttachment
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
