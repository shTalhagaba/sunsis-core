<?php
/**
 * File for class LogicMelonStructCQueryJobTitle
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructCQueryJobTitle originally named CQueryJobTitle
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructCQueryJobTitle extends LogicMelonWsdlClass
{
    /**
     * The Score
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $Score;
    /**
     * The CultureID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $CultureID;
    /**
     * The Description
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Description;
    /**
     * The Industry
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Industry;
    /**
     * The JobTitle
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $JobTitle;
    /**
     * Constructor method for CQueryJobTitle
     * @see parent::__construct()
     * @param int $_score
     * @param string $_cultureID
     * @param string $_description
     * @param string $_industry
     * @param string $_jobTitle
     * @return LogicMelonStructCQueryJobTitle
     */
    public function __construct($_score,$_cultureID = NULL,$_description = NULL,$_industry = NULL,$_jobTitle = NULL)
    {
        parent::__construct(array('Score'=>$_score,'CultureID'=>$_cultureID,'Description'=>$_description,'Industry'=>$_industry,'JobTitle'=>$_jobTitle),false);
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
     * Get CultureID value
     * @return string|null
     */
    public function getCultureID()
    {
        return $this->CultureID;
    }
    /**
     * Set CultureID value
     * @param string $_cultureID the CultureID
     * @return string
     */
    public function setCultureID($_cultureID)
    {
        return ($this->CultureID = $_cultureID);
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
     * Get Industry value
     * @return string|null
     */
    public function getIndustry()
    {
        return $this->Industry;
    }
    /**
     * Set Industry value
     * @param string $_industry the Industry
     * @return string
     */
    public function setIndustry($_industry)
    {
        return ($this->Industry = $_industry);
    }
    /**
     * Get JobTitle value
     * @return string|null
     */
    public function getJobTitle()
    {
        return $this->JobTitle;
    }
    /**
     * Set JobTitle value
     * @param string $_jobTitle the JobTitle
     * @return string
     */
    public function setJobTitle($_jobTitle)
    {
        return ($this->JobTitle = $_jobTitle);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructCQueryJobTitle
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
