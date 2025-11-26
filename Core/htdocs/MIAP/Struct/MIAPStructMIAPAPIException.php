<?php
/**
 * File for class MIAPStructMIAPAPIException
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPAPIException originally named MIAPAPIException
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//fault.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPAPIException extends MIAPWsdlClass
{
    /**
     * The ErrorCode
     * @var string
     */
    public $ErrorCode;
    /**
     * The ErrorActor
     * @var string
     */
    public $ErrorActor;
    /**
     * The Description
     * @var string
     */
    public $Description;
    /**
     * The FurtherDetails
     * @var string
     */
    public $FurtherDetails;
    /**
     * The ErrorTimestamp
     * @var string
     */
    public $ErrorTimestamp;
    /**
     * Constructor method for MIAPAPIException
     * @see parent::__construct()
     * @param string $_errorCode
     * @param string $_errorActor
     * @param string $_description
     * @param string $_furtherDetails
     * @param string $_errorTimestamp
     * @return MIAPStructMIAPAPIException
     */
    public function __construct($_errorCode = NULL,$_errorActor = NULL,$_description = NULL,$_furtherDetails = NULL,$_errorTimestamp = NULL)
    {
        parent::__construct(array('ErrorCode'=>$_errorCode,'ErrorActor'=>$_errorActor,'Description'=>$_description,'FurtherDetails'=>$_furtherDetails,'ErrorTimestamp'=>$_errorTimestamp),false);
    }
    /**
     * Get ErrorCode value
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->ErrorCode;
    }
    /**
     * Set ErrorCode value
     * @param string $_errorCode the ErrorCode
     * @return string
     */
    public function setErrorCode($_errorCode)
    {
        return ($this->ErrorCode = $_errorCode);
    }
    /**
     * Get ErrorActor value
     * @return string|null
     */
    public function getErrorActor()
    {
        return $this->ErrorActor;
    }
    /**
     * Set ErrorActor value
     * @param string $_errorActor the ErrorActor
     * @return string
     */
    public function setErrorActor($_errorActor)
    {
        return ($this->ErrorActor = $_errorActor);
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
     * Get FurtherDetails value
     * @return string|null
     */
    public function getFurtherDetails()
    {
        return $this->FurtherDetails;
    }
    /**
     * Set FurtherDetails value
     * @param string $_furtherDetails the FurtherDetails
     * @return string
     */
    public function setFurtherDetails($_furtherDetails)
    {
        return ($this->FurtherDetails = $_furtherDetails);
    }
    /**
     * Get ErrorTimestamp value
     * @return string|null
     */
    public function getErrorTimestamp()
    {
        return $this->ErrorTimestamp;
    }
    /**
     * Set ErrorTimestamp value
     * @param string $_errorTimestamp the ErrorTimestamp
     * @return string
     */
    public function setErrorTimestamp($_errorTimestamp)
    {
        return ($this->ErrorTimestamp = $_errorTimestamp);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPAPIException
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
