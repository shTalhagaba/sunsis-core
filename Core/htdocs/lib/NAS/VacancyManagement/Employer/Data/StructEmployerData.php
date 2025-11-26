<?php
/**
 * File for class StructEmployerData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructEmployerData originally named EmployerData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructEmployerData extends WsdlClass
{
    /**
     * The EdsUrn
     * @var int
     */
    public $EdsUrn;
    /**
     * The Description
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Description;
    /**
     * The AnonymousName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AnonymousName;
    /**
     * The ContactName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ContactName;
    /**
     * The Website
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Website;
    /**
     * The Image
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var base64Binary
     */
    public $Image;
    /**
     * Constructor method for EmployerData
     * @see parent::__construct()
     * @param int $_edsUrn
     * @param string $_description
     * @param string $_anonymousName
     * @param string $_contactName
     * @param string $_website
     * @param base64Binary $_image
     * @return StructEmployerData
     */
    public function __construct($_edsUrn = NULL,$_description = NULL,$_anonymousName = NULL,$_contactName = NULL,$_website = NULL,$_image = NULL)
    {
        parent::__construct(array('EdsUrn'=>$_edsUrn,'Description'=>$_description,'AnonymousName'=>$_anonymousName,'ContactName'=>$_contactName,'Website'=>$_website,'Image'=>$_image),false);
    }
    /**
     * Get EdsUrn value
     * @return int|null
     */
    public function getEdsUrn()
    {
        return $this->EdsUrn;
    }
    /**
     * Set EdsUrn value
     * @param int $_edsUrn the EdsUrn
     * @return int
     */
    public function setEdsUrn($_edsUrn)
    {
        return ($this->EdsUrn = $_edsUrn);
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
     * Get AnonymousName value
     * @return string|null
     */
    public function getAnonymousName()
    {
        return $this->AnonymousName;
    }
    /**
     * Set AnonymousName value
     * @param string $_anonymousName the AnonymousName
     * @return string
     */
    public function setAnonymousName($_anonymousName)
    {
        return ($this->AnonymousName = $_anonymousName);
    }
    /**
     * Get ContactName value
     * @return string|null
     */
    public function getContactName()
    {
        return $this->ContactName;
    }
    /**
     * Set ContactName value
     * @param string $_contactName the ContactName
     * @return string
     */
    public function setContactName($_contactName)
    {
        return ($this->ContactName = $_contactName);
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
     * Get Image value
     * @return base64Binary|null
     */
    public function getImage()
    {
        return $this->Image;
    }
    /**
     * Set Image value
     * @param base64Binary $_image the Image
     * @return base64Binary
     */
    public function setImage($_image)
    {
        return ($this->Image = $_image);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructEmployerData
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
