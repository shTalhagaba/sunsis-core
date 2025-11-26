<?php
/**
 * File for class StructApplicationData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructApplicationData originally named ApplicationData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructApplicationData extends WsdlClass
{
    /**
     * The ClosingDate
     * @var dateTime
     */
    public $ClosingDate;
    /**
     * The InterviewStartDate
     * @var dateTime
     */
    public $InterviewStartDate;
    /**
     * The PossibleStartDate
     * @var dateTime
     */
    public $PossibleStartDate;
    /**
     * The Type
     * @var EnumApplicationType
     */
    public $Type;
    /**
     * The Instructions
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Instructions;
    /**
     * Constructor method for ApplicationData
     * @see parent::__construct()
     * @param dateTime $_closingDate
     * @param dateTime $_interviewStartDate
     * @param dateTime $_possibleStartDate
     * @param EnumApplicationType $_type
     * @param string $_instructions
     * @return StructApplicationData
     */
    public function __construct($_closingDate = NULL,$_interviewStartDate = NULL,$_possibleStartDate = NULL,$_type = NULL,$_instructions = NULL)
    {
        parent::__construct(array('ClosingDate'=>$_closingDate,'InterviewStartDate'=>$_interviewStartDate,'PossibleStartDate'=>$_possibleStartDate,'Type'=>$_type,'Instructions'=>$_instructions),false);
    }
    /**
     * Get ClosingDate value
     * @return dateTime|null
     */
    public function getClosingDate()
    {
        return $this->ClosingDate;
    }
    /**
     * Set ClosingDate value
     * @param dateTime $_closingDate the ClosingDate
     * @return dateTime
     */
    public function setClosingDate($_closingDate)
    {
        return ($this->ClosingDate = $_closingDate);
    }
    /**
     * Get InterviewStartDate value
     * @return dateTime|null
     */
    public function getInterviewStartDate()
    {
        return $this->InterviewStartDate;
    }
    /**
     * Set InterviewStartDate value
     * @param dateTime $_interviewStartDate the InterviewStartDate
     * @return dateTime
     */
    public function setInterviewStartDate($_interviewStartDate)
    {
        return ($this->InterviewStartDate = $_interviewStartDate);
    }
    /**
     * Get PossibleStartDate value
     * @return dateTime|null
     */
    public function getPossibleStartDate()
    {
        return $this->PossibleStartDate;
    }
    /**
     * Set PossibleStartDate value
     * @param dateTime $_possibleStartDate the PossibleStartDate
     * @return dateTime
     */
    public function setPossibleStartDate($_possibleStartDate)
    {
        return ($this->PossibleStartDate = $_possibleStartDate);
    }
    /**
     * Get Type value
     * @return EnumApplicationType|null
     */
    public function getType()
    {
        return $this->Type;
    }
    /**
     * Set Type value
     * @uses EnumApplicationType::valueIsValid()
     * @param EnumApplicationType $_type the Type
     * @return EnumApplicationType
     */
    public function setType($_type)
    {
        if(!EnumApplicationType::valueIsValid($_type))
        {
            return false;
        }
        return ($this->Type = $_type);
    }
    /**
     * Get Instructions value
     * @return string|null
     */
    public function getInstructions()
    {
        return $this->Instructions;
    }
    /**
     * Set Instructions value
     * @param string $_instructions the Instructions
     * @return string
     */
    public function setInstructions($_instructions)
    {
        return ($this->Instructions = $_instructions);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructApplicationData
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
