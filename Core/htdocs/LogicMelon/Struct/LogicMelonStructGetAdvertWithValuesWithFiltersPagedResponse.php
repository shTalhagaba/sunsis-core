<?php
/**
 * File for class LogicMelonStructGetAdvertWithValuesWithFiltersPagedResponse
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructGetAdvertWithValuesWithFiltersPagedResponse originally named GetAdvertWithValuesWithFiltersPagedResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructGetAdvertWithValuesWithFiltersPagedResponse extends LogicMelonWsdlClass
{
    /**
     * The AdvertWithValuesPaged
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var LogicMelonStructAPIAdvertWithValuesPaged
     */
    public $AdvertWithValuesPaged;
    /**
     * Constructor method for GetAdvertWithValuesWithFiltersPagedResponse
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithValuesPaged $_advertWithValuesPaged
     * @return LogicMelonStructGetAdvertWithValuesWithFiltersPagedResponse
     */
    public function __construct($_advertWithValuesPaged)
    {
        parent::__construct(array('AdvertWithValuesPaged'=>$_advertWithValuesPaged),false);
    }
    /**
     * Get AdvertWithValuesPaged value
     * @return LogicMelonStructAPIAdvertWithValuesPaged
     */
    public function getAdvertWithValuesPaged()
    {
        return $this->AdvertWithValuesPaged;
    }
    /**
     * Set AdvertWithValuesPaged value
     * @param LogicMelonStructAPIAdvertWithValuesPaged $_advertWithValuesPaged the AdvertWithValuesPaged
     * @return LogicMelonStructAPIAdvertWithValuesPaged
     */
    public function setAdvertWithValuesPaged($_advertWithValuesPaged)
    {
        return ($this->AdvertWithValuesPaged = $_advertWithValuesPaged);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructGetAdvertWithValuesWithFiltersPagedResponse
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
