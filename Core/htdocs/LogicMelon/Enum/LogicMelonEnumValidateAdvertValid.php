<?php
/**
 * File for class LogicMelonEnumValidateAdvertValid
 * @package LogicMelon
 * @subpackage Enumerations
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonEnumValidateAdvertValid originally named ValidateAdvertValid
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Enumerations
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonEnumValidateAdvertValid extends LogicMelonWsdlClass
{
    /**
     * Constant for value 'Valid'
     * @return string 'Valid'
     */
    const VALUE_VALID = 'Valid';
    /**
     * Constant for value 'Warnings'
     * @return string 'Warnings'
     */
    const VALUE_WARNINGS = 'Warnings';
    /**
     * Constant for value 'Errors'
     * @return string 'Errors'
     */
    const VALUE_ERRORS = 'Errors';
    /**
     * Return true if value is allowed
     * @uses LogicMelonEnumValidateAdvertValid::VALUE_VALID
     * @uses LogicMelonEnumValidateAdvertValid::VALUE_WARNINGS
     * @uses LogicMelonEnumValidateAdvertValid::VALUE_ERRORS
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LogicMelonEnumValidateAdvertValid::VALUE_VALID,LogicMelonEnumValidateAdvertValid::VALUE_WARNINGS,LogicMelonEnumValidateAdvertValid::VALUE_ERRORS));
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
