<?php
/**
 * File for class LogicMelonStructArchiveAdvertWithFiltersResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArchiveAdvertWithFiltersResponse originally named ArchiveAdvertWithFiltersResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArchiveAdvertWithFiltersResponse extends LogicMelonWsdlClass
{
    /**
     * The ArchiveAdvertWithFiltersResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIAdvertWithPostings
     */
    public $ArchiveAdvertWithFiltersResult;
    /**
     * Constructor method for ArchiveAdvertWithFiltersResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithPostings $_archiveAdvertWithFiltersResult
     * @return LogicMelonStructArchiveAdvertWithFiltersResponse
     */
    public function __construct($_archiveAdvertWithFiltersResult = NULL)
    {
        parent::__construct(array('ArchiveAdvertWithFiltersResult'=>$_archiveAdvertWithFiltersResult),false);
    }
    /**
     * Get ArchiveAdvertWithFiltersResult value
     * @return LogicMelonStructAPIAdvertWithPostings|null
     */
    public function getArchiveAdvertWithFiltersResult()
    {
        return $this->ArchiveAdvertWithFiltersResult;
    }
    /**
     * Set ArchiveAdvertWithFiltersResult value
     * @param LogicMelonStructAPIAdvertWithPostings $_archiveAdvertWithFiltersResult the ArchiveAdvertWithFiltersResult
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function setArchiveAdvertWithFiltersResult($_archiveAdvertWithFiltersResult)
    {
        return ($this->ArchiveAdvertWithFiltersResult = $_archiveAdvertWithFiltersResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArchiveAdvertWithFiltersResponse
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
