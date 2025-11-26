<?php
/**
 * File for class LogicMelonStructGetApplicationsPagedResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetApplicationsPagedResponse originally named GetApplicationsPagedResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetApplicationsPagedResponse extends LogicMelonWsdlClass
{
    /**
     * The ApplicationPaged
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var LogicMelonStructAPIApplicationPaged
     */
    public $ApplicationPaged;
    /**
     * Constructor method for GetApplicationsPagedResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIApplicationPaged $_applicationPaged
     * @return LogicMelonStructGetApplicationsPagedResponse
     */
    public function __construct($_applicationPaged)
    {
        parent::__construct(array('ApplicationPaged'=>$_applicationPaged),false);
    }
    /**
     * Get ApplicationPaged value
     * @return LogicMelonStructAPIApplicationPaged
     */
    public function getApplicationPaged()
    {
        return $this->ApplicationPaged;
    }
    /**
     * Set ApplicationPaged value
     * @param LogicMelonStructAPIApplicationPaged $_applicationPaged the ApplicationPaged
     * @return LogicMelonStructAPIApplicationPaged
     */
    public function setApplicationPaged($_applicationPaged)
    {
        return ($this->ApplicationPaged = $_applicationPaged);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetApplicationsPagedResponse
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
