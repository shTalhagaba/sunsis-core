<?php
/**
 * File for class LogicMelonStructGetAdvertPagedResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetAdvertPagedResponse originally named GetAdvertPagedResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetAdvertPagedResponse extends LogicMelonWsdlClass
{
    /**
     * The AdvertPaged
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var LogicMelonStructAPIAdvertPaged
     */
    public $AdvertPaged;
    /**
     * Constructor method for GetAdvertPagedResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertPaged $_advertPaged
     * @return LogicMelonStructGetAdvertPagedResponse
     */
    public function __construct($_advertPaged)
    {
        parent::__construct(array('AdvertPaged'=>$_advertPaged),false);
    }
    /**
     * Get AdvertPaged value
     * @return LogicMelonStructAPIAdvertPaged
     */
    public function getAdvertPaged()
    {
        return $this->AdvertPaged;
    }
    /**
     * Set AdvertPaged value
     * @param LogicMelonStructAPIAdvertPaged $_advertPaged the AdvertPaged
     * @return LogicMelonStructAPIAdvertPaged
     */
    public function setAdvertPaged($_advertPaged)
    {
        return ($this->AdvertPaged = $_advertPaged);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetAdvertPagedResponse
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
