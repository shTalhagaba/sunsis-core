<?php
/**
 * File for class ServiceUpload
 * @package
 * @subpackage Services
 * @date 2016-12-22
 */
/**
 * This class stands for ServiceUpload originally named Upload
 * @package
 * @subpackage Services
 * @date 2016-12-22
 */
class ServiceUpload extends WsdlClass
{
    /**
     * Sets the ExternalSystemId SoapHeader param
     * @uses WsdlClass::setSoapHeader()
     * @param guid $_externalSystemId
     * @param string $_nameSpace http://services.imservices.org.uk/AVMS/Interfaces/5.1
     * @param bool $_mustUnderstand
     * @param string $_actor
     * @return bool true|false
     */
    public function setSoapHeaderExternalSystemId($_externalSystemId,$_nameSpace = 'http://services.imservices.org.uk/AVMS/Interfaces/5.1',$_mustUnderstand = false,$_actor = null)
    {
        return $this->setSoapHeader($_nameSpace,'ExternalSystemId',$_externalSystemId,$_mustUnderstand,$_actor);
    }
    /**
     * Sets the MessageId SoapHeader param
     * @uses WsdlClass::setSoapHeader()
     * @param guid $_messageId
     * @param string $_nameSpace http://services.imservices.org.uk/AVMS/Interfaces/5.1
     * @param bool $_mustUnderstand
     * @param string $_actor
     * @return bool true|false
     */
    public function setSoapHeaderMessageId($_messageId,$_nameSpace = 'http://services.imservices.org.uk/AVMS/Interfaces/5.1',$_mustUnderstand = false,$_actor = null)
    {
        return $this->setSoapHeader($_nameSpace,'MessageId',$_messageId,$_mustUnderstand,$_actor);
    }
    /**
     * Sets the PublicKey SoapHeader param
     * @uses WsdlClass::setSoapHeader()
     * @param string $_publicKey
     * @param string $_nameSpace http://services.imservices.org.uk/AVMS/Interfaces/5.1
     * @param bool $_mustUnderstand
     * @param string $_actor
     * @return bool true|false
     */
    public function setSoapHeaderPublicKey($_publicKey,$_nameSpace = 'http://services.imservices.org.uk/AVMS/Interfaces/5.1',$_mustUnderstand = false,$_actor = null)
    {
        return $this->setSoapHeader($_nameSpace,'PublicKey',$_publicKey,$_mustUnderstand,$_actor);
    }
    /**
     * Method to call the operation originally named UploadVacancies
     * Meta informations extracted from the WSDL
     * - SOAPHeaderNames : ExternalSystemId,MessageId,PublicKey
     * - SOAPHeaderNamespaces : http://services.imservices.org.uk/AVMS/Interfaces/5.1,http://services.imservices.org.uk/AVMS/Interfaces/5.1,http://services.imservices.org.uk/AVMS/Interfaces/5.1
     * - SOAPHeaderTypes : guid,guid,string
     * - SOAPHeaders : required,required,required
     * @uses WsdlClass::getSoapClient()
     * @uses WsdlClass::setResult()
     * @uses WsdlClass::saveLastError()
     * @param StructVacancyUploadRequest $_structVacancyUploadRequest
     * @return StructVacancyUploadResponse
     */
    public function UploadVacancies(StructVacancyUploadRequest $_structVacancyUploadRequest)
    {
        try
        {
            //return $this->setResult(self::getSoapClient()->UploadVacancies(array('parameters'=>array($_structVacancyUploadRequest)))->parameters);
            return $this->setResult(self::getSoapClient()->UploadVacancies($_structVacancyUploadRequest));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see WsdlClass::getResult()
     * @return StructVacancyUploadResponse
     */
    public function getResult()
    {
        return parent::getResult();
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
