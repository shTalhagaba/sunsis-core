<?php
/**
 * File for class StructVacancyUploadResultData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructVacancyUploadResultData originally named VacancyUploadResultData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructVacancyUploadResultData extends WsdlClass
{
    /**
     * The VacancyId
     * Meta informations extracted from the WSDL
     * - pattern : [\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}
     * @var string
     */
    public $VacancyId;
    /**
     * The Status
     * @var EnumVacancyUploadResult
     */
    public $Status;
    /**
     * The ErrorCodes
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var StructArrayOfElementErrorData
     */
    public $ErrorCodes;
    /**
     * The ReferenceNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $ReferenceNumber;
    /**
     * Constructor method for VacancyUploadResultData
     * @see parent::__construct()
     * @param string $_vacancyId
     * @param EnumVacancyUploadResult $_status
     * @param StructArrayOfElementErrorData $_errorCodes
     * @param int $_referenceNumber
     * @return StructVacancyUploadResultData
     */
    public function __construct($_vacancyId = NULL,$_status = NULL,$_errorCodes = NULL,$_referenceNumber = NULL)
    {
        parent::__construct(array('VacancyId'=>$_vacancyId,'Status'=>$_status,'ErrorCodes'=>($_errorCodes instanceof StructArrayOfElementErrorData)?$_errorCodes:new StructArrayOfElementErrorData($_errorCodes),'ReferenceNumber'=>$_referenceNumber),false);
    }
    /**
     * Get VacancyId value
     * @return string|null
     */
    public function getVacancyId()
    {
        return $this->VacancyId;
    }
    /**
     * Set VacancyId value
     * @param string $_vacancyId the VacancyId
     * @return string
     */
    public function setVacancyId($_vacancyId)
    {
        return ($this->VacancyId = $_vacancyId);
    }
    /**
     * Get Status value
     * @return EnumVacancyUploadResult|null
     */
    public function getStatus()
    {
        return $this->Status;
    }
    /**
     * Set Status value
     * @uses EnumVacancyUploadResult::valueIsValid()
     * @param EnumVacancyUploadResult $_status the Status
     * @return EnumVacancyUploadResult
     */
    public function setStatus($_status)
    {
        if(!EnumVacancyUploadResult::valueIsValid($_status))
        {
            return false;
        }
        return ($this->Status = $_status);
    }
    /**
     * Get ErrorCodes value
     * @return StructArrayOfElementErrorData|null
     */
    public function getErrorCodes()
    {
        return $this->ErrorCodes;
    }
    /**
     * Set ErrorCodes value
     * @param StructArrayOfElementErrorData $_errorCodes the ErrorCodes
     * @return StructArrayOfElementErrorData
     */
    public function setErrorCodes($_errorCodes)
    {
        return ($this->ErrorCodes = $_errorCodes);
    }
    /**
     * Get ReferenceNumber value
     * @return int|null
     */
    public function getReferenceNumber()
    {
        return $this->ReferenceNumber;
    }
    /**
     * Set ReferenceNumber value
     * @param int $_referenceNumber the ReferenceNumber
     * @return int
     */
    public function setReferenceNumber($_referenceNumber)
    {
        return ($this->ReferenceNumber = $_referenceNumber);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructVacancyUploadResultData
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
