<?php
/**
 * File for class LogicMelonStructAPIAdvertWithValues
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAPIAdvertWithValues originally named APIAdvertWithValues
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAPIAdvertWithValues extends LogicMelonWsdlClass
{
    /**
     * The Advert
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIAdvert
     */
    public $Advert;
    /**
     * The AdvertValues
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIAdvertValue
     */
    public $AdvertValues;
    /**
     * Constructor method for APIAdvertWithValues
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvert $_advert
     * @param LogicMelonStructArrayOfAPIAdvertValue $_advertValues
     * @return LogicMelonStructAPIAdvertWithValues
     */
    public function __construct($_advert = NULL,$_advertValues = NULL)
    {
        parent::__construct(array('Advert'=>$_advert,'AdvertValues'=>($_advertValues instanceof LogicMelonStructArrayOfAPIAdvertValue)?$_advertValues:new LogicMelonStructArrayOfAPIAdvertValue($_advertValues)),false);
    }
    /**
     * Get Advert value
     * @return LogicMelonStructAPIAdvert|null
     */
    public function getAdvert()
    {
        return $this->Advert;
    }
    /**
     * Set Advert value
     * @param LogicMelonStructAPIAdvert $_advert the Advert
     * @return LogicMelonStructAPIAdvert
     */
    public function setAdvert($_advert)
    {
        return ($this->Advert = $_advert);
    }
    /**
     * Get AdvertValues value
     * @return LogicMelonStructArrayOfAPIAdvertValue|null
     */
    public function getAdvertValues()
    {
        return $this->AdvertValues;
    }
    /**
     * Set AdvertValues value
     * @param LogicMelonStructArrayOfAPIAdvertValue $_advertValues the AdvertValues
     * @return LogicMelonStructArrayOfAPIAdvertValue
     */
    public function setAdvertValues($_advertValues)
    {
        return ($this->AdvertValues = $_advertValues);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAPIAdvertWithValues
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
