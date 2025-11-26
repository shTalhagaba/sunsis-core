<?php
/**
 * File for class LogicMelonStructAPIAdvertWithPostings
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAPIAdvertWithPostings originally named APIAdvertWithPostings
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAPIAdvertWithPostings extends LogicMelonWsdlClass
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
     * The Postings
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfAPIPosting
     */
    public $Postings;
    /**
     * Constructor method for APIAdvertWithPostings
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvert $_advert
     * @param LogicMelonStructArrayOfAPIPosting $_postings
     * @return LogicMelonStructAPIAdvertWithPostings
     */
    public function __construct($_advert = NULL,$_postings = NULL)
    {
        parent::__construct(array('Advert'=>$_advert,'Postings'=>($_postings instanceof LogicMelonStructArrayOfAPIPosting)?$_postings:new LogicMelonStructArrayOfAPIPosting($_postings)),false);
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
     * Get Postings value
     * @return LogicMelonStructArrayOfAPIPosting|null
     */
    public function getPostings()
    {
        return $this->Postings;
    }
    /**
     * Set Postings value
     * @param LogicMelonStructArrayOfAPIPosting $_postings the Postings
     * @return LogicMelonStructArrayOfAPIPosting
     */
    public function setPostings($_postings)
    {
        return ($this->Postings = $_postings);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAPIAdvertWithPostings
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
