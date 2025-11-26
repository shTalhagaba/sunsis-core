<?php
/**
 * File for class StructApprenticeshipData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructApprenticeshipData originally named ApprenticeshipData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructApprenticeshipData extends WsdlClass
{
    /**
     * The Framework
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var string
     */
    public $Framework;
    /**
     * The Type
     * @var EnumVacancyApprenticeshipType
     */
    public $Type;
    /**
     * The TrainingToBeProvided
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $TrainingToBeProvided;
    /**
     * The ExpectedDuration
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ExpectedDuration;
    /**
     * Constructor method for ApprenticeshipData
     * @see parent::__construct()
     * @param string $_framework
     * @param EnumVacancyApprenticeshipType $_type
     * @param string $_trainingToBeProvided
     * @param string $_expectedDuration
     * @return StructApprenticeshipData
     */
    public function __construct($_framework = NULL,$_type = NULL,$_trainingToBeProvided = NULL,$_expectedDuration = NULL)
    {
        parent::__construct(array('Framework'=>$_framework,'Type'=>$_type,'TrainingToBeProvided'=>$_trainingToBeProvided,'ExpectedDuration'=>$_expectedDuration),false);
    }
    /**
     * Get Framework value
     * @return string|null
     */
    public function getFramework()
    {
        return $this->Framework;
    }
    /**
     * Set Framework value
     * @param string $_framework the Framework
     * @return string
     */
    public function setFramework($_framework)
    {
        return ($this->Framework = $_framework);
    }
    /**
     * Get Type value
     * @return EnumVacancyApprenticeshipType|null
     */
    public function getType()
    {
        return $this->Type;
    }
    /**
     * Set Type value
     * @uses EnumVacancyApprenticeshipType::valueIsValid()
     * @param EnumVacancyApprenticeshipType $_type the Type
     * @return EnumVacancyApprenticeshipType
     */
    public function setType($_type)
    {
        if(!EnumVacancyApprenticeshipType::valueIsValid($_type))
        {
            return false;
        }
        return ($this->Type = $_type);
    }
    /**
     * Get TrainingToBeProvided value
     * @return string|null
     */
    public function getTrainingToBeProvided()
    {
        return $this->TrainingToBeProvided;
    }
    /**
     * Set TrainingToBeProvided value
     * @param string $_trainingToBeProvided the TrainingToBeProvided
     * @return string
     */
    public function setTrainingToBeProvided($_trainingToBeProvided)
    {
        return ($this->TrainingToBeProvided = $_trainingToBeProvided);
    }
    /**
     * Get ExpectedDuration value
     * @return string|null
     */
    public function getExpectedDuration()
    {
        return $this->ExpectedDuration;
    }
    /**
     * Set ExpectedDuration value
     * @param string $_expectedDuration the ExpectedDuration
     * @return string
     */
    public function setExpectedDuration($_expectedDuration)
    {
        return ($this->ExpectedDuration = $_expectedDuration);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructApprenticeshipData
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
