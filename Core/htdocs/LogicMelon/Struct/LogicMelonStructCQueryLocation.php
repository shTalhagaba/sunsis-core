<?php
/**
 * File for class LogicMelonStructCQueryLocation
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructCQueryLocation originally named CQueryLocation
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructCQueryLocation extends LogicMelonWsdlClass
{
    /**
     * The LocationID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $LocationID;
    /**
     * The Score
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $Score;
    /**
     * The Display
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Display;
    /**
     * The LocationDescription
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $LocationDescription;
    /**
     * The LocationIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $LocationIdentifier;
    /**
     * The LocationValue
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $LocationValue;
    /**
     * Constructor method for CQueryLocation
     * @see parent::__construct()
     * @param int $_locationID
     * @param int $_score
     * @param string $_display
     * @param string $_locationDescription
     * @param string $_locationIdentifier
     * @param string $_locationValue
     * @return LogicMelonStructCQueryLocation
     */
    public function __construct($_locationID,$_score,$_display = NULL,$_locationDescription = NULL,$_locationIdentifier = NULL,$_locationValue = NULL)
    {
        parent::__construct(array('LocationID'=>$_locationID,'Score'=>$_score,'Display'=>$_display,'LocationDescription'=>$_locationDescription,'LocationIdentifier'=>$_locationIdentifier,'LocationValue'=>$_locationValue),false);
    }
    /**
     * Get LocationID value
     * @return int
     */
    public function getLocationID()
    {
        return $this->LocationID;
    }
    /**
     * Set LocationID value
     * @param int $_locationID the LocationID
     * @return int
     */
    public function setLocationID($_locationID)
    {
        return ($this->LocationID = $_locationID);
    }
    /**
     * Get Score value
     * @return int
     */
    public function getScore()
    {
        return $this->Score;
    }
    /**
     * Set Score value
     * @param int $_score the Score
     * @return int
     */
    public function setScore($_score)
    {
        return ($this->Score = $_score);
    }
    /**
     * Get Display value
     * @return string|null
     */
    public function getDisplay()
    {
        return $this->Display;
    }
    /**
     * Set Display value
     * @param string $_display the Display
     * @return string
     */
    public function setDisplay($_display)
    {
        return ($this->Display = $_display);
    }
    /**
     * Get LocationDescription value
     * @return string|null
     */
    public function getLocationDescription()
    {
        return $this->LocationDescription;
    }
    /**
     * Set LocationDescription value
     * @param string $_locationDescription the LocationDescription
     * @return string
     */
    public function setLocationDescription($_locationDescription)
    {
        return ($this->LocationDescription = $_locationDescription);
    }
    /**
     * Get LocationIdentifier value
     * @return string|null
     */
    public function getLocationIdentifier()
    {
        return $this->LocationIdentifier;
    }
    /**
     * Set LocationIdentifier value
     * @param string $_locationIdentifier the LocationIdentifier
     * @return string
     */
    public function setLocationIdentifier($_locationIdentifier)
    {
        return ($this->LocationIdentifier = $_locationIdentifier);
    }
    /**
     * Get LocationValue value
     * @return string|null
     */
    public function getLocationValue()
    {
        return $this->LocationValue;
    }
    /**
     * Set LocationValue value
     * @param string $_locationValue the LocationValue
     * @return string
     */
    public function setLocationValue($_locationValue)
    {
        return ($this->LocationValue = $_locationValue);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructCQueryLocation
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
