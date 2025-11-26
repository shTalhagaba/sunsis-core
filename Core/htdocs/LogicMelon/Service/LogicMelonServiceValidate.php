<?php
/**
 * File for class LogicMelonServiceValidate
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceValidate originally named Validate
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceValidate extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named ValidateAdvertValues
     * Documentation : <a name='ValidateAdvertValues'></a><p>Validate a supplied set of fields against the selected destinations.</p><ul><li><strong>sUsername</strong> Optionally specify a username to collect per user settings.</li><li><strong>Destinations</strong> Required list of job board destinations as either numeric id's or string identifiers.</li><li><strong>AdvertValues</strong> Required (except when an sAdvertID is specified) a set of field data in the form of name value pairs (with multiple values specified in the Values node).</li><li><strong>sValidateSuppliedFieldsOnly</strong> Validate all feed fields, or only validate the data supplied (true or false, default false).</li><li><strong>sAdvertID, sAdvertReference, sAdvertIdentifier</strong> Optionally validate advert data already stored in the database.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructValidateAdvertValues $_logicMelonStructValidateAdvertValues
     * @return LogicMelonStructValidateAdvertValuesResponse
     */
    public function ValidateAdvertValues(LogicMelonStructValidateAdvertValues $_logicMelonStructValidateAdvertValues)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->ValidateAdvertValues($_logicMelonStructValidateAdvertValues));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructValidateAdvertValuesResponse
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
