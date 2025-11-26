<?php
/**
 * File for class LogicMelonStructArchiveAdvertResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArchiveAdvertResponse originally named ArchiveAdvertResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArchiveAdvertResponse extends LogicMelonWsdlClass
{
    /**
     * The ArchiveAdvertResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIAdvertWithPostings
     */
    public $ArchiveAdvertResult;
    /**
     * Constructor method for ArchiveAdvertResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithPostings $_archiveAdvertResult
     * @return LogicMelonStructArchiveAdvertResponse
     */
    public function __construct($_archiveAdvertResult = NULL)
    {
        parent::__construct(array('ArchiveAdvertResult'=>$_archiveAdvertResult),false);
    }
    /**
     * Get ArchiveAdvertResult value
     * @return LogicMelonStructAPIAdvertWithPostings|null
     */
    public function getArchiveAdvertResult()
    {
        return $this->ArchiveAdvertResult;
    }
    /**
     * Set ArchiveAdvertResult value
     * @param LogicMelonStructAPIAdvertWithPostings $_archiveAdvertResult the ArchiveAdvertResult
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function setArchiveAdvertResult($_archiveAdvertResult)
    {
        return ($this->ArchiveAdvertResult = $_archiveAdvertResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArchiveAdvertResponse
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
