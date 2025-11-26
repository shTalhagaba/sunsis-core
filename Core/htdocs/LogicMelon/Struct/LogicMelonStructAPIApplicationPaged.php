<?php
/**
 * File for class LogicMelonStructAPIApplicationPaged
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAPIApplicationPaged originally named APIApplicationPaged
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAPIApplicationPaged extends LogicMelonWsdlClass
{
    /**
     * The PageCount
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $PageCount;
    /**
     * The PageIndex
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $PageIndex;
    /**
     * The TotalItemCount
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $TotalItemCount;
    /**
     * The Applications
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIApplication
     */
    public $Applications;
    /**
     * Constructor method for APIApplicationPaged
     * @see parent::__construct()
     * @param int $_pageCount
     * @param int $_pageIndex
     * @param int $_totalItemCount
     * @param LogicMelonStructArrayOfAPIApplication $_applications
     * @return LogicMelonStructAPIApplicationPaged
     */
    public function __construct($_pageCount,$_pageIndex,$_totalItemCount,$_applications = NULL)
    {
        parent::__construct(array('PageCount'=>$_pageCount,'PageIndex'=>$_pageIndex,'TotalItemCount'=>$_totalItemCount,'Applications'=>($_applications instanceof LogicMelonStructArrayOfAPIApplication)?$_applications:new LogicMelonStructArrayOfAPIApplication($_applications)),false);
    }
    /**
     * Get PageCount value
     * @return int
     */
    public function getPageCount()
    {
        return $this->PageCount;
    }
    /**
     * Set PageCount value
     * @param int $_pageCount the PageCount
     * @return int
     */
    public function setPageCount($_pageCount)
    {
        return ($this->PageCount = $_pageCount);
    }
    /**
     * Get PageIndex value
     * @return int
     */
    public function getPageIndex()
    {
        return $this->PageIndex;
    }
    /**
     * Set PageIndex value
     * @param int $_pageIndex the PageIndex
     * @return int
     */
    public function setPageIndex($_pageIndex)
    {
        return ($this->PageIndex = $_pageIndex);
    }
    /**
     * Get TotalItemCount value
     * @return int
     */
    public function getTotalItemCount()
    {
        return $this->TotalItemCount;
    }
    /**
     * Set TotalItemCount value
     * @param int $_totalItemCount the TotalItemCount
     * @return int
     */
    public function setTotalItemCount($_totalItemCount)
    {
        return ($this->TotalItemCount = $_totalItemCount);
    }
    /**
     * Get Applications value
     * @return LogicMelonStructArrayOfAPIApplication|null
     */
    public function getApplications()
    {
        return $this->Applications;
    }
    /**
     * Set Applications value
     * @param LogicMelonStructArrayOfAPIApplication $_applications the Applications
     * @return LogicMelonStructArrayOfAPIApplication
     */
    public function setApplications($_applications)
    {
        return ($this->Applications = $_applications);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAPIApplicationPaged
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
