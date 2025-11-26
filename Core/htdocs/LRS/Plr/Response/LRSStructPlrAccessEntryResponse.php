<?php
/**
 * File for class LRSStructPlrAccessEntryResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructPlrAccessEntryResponse originally named PlrAccessEntryResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructPlrAccessEntryResponse extends LRSStructBusinessObject
{
    /**
     * The Action
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Action;
    /**
     * The DateTime
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var dateTime
     */
    public $DateTime;
    /**
     * The Organisation
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Organisation;
    /**
     * The User
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $User;
    /**
     * Constructor method for PlrAccessEntryResponse
     * @see parent::__construct()
     * @param string $_action
     * @param dateTime $_dateTime
     * @param string $_organisation
     * @param string $_user
     * @return LRSStructPlrAccessEntryResponse
     */
    public function __construct($_action = NULL,$_dateTime = NULL,$_organisation = NULL,$_user = NULL)
    {
        LRSWsdlClass::__construct(array('Action'=>$_action,'DateTime'=>$_dateTime,'Organisation'=>$_organisation,'User'=>$_user),false);
    }
    /**
     * Get Action value
     * @return string|null
     */
    public function getAction()
    {
        return $this->Action;
    }
    /**
     * Set Action value
     * @param string $_action the Action
     * @return string
     */
    public function setAction($_action)
    {
        return ($this->Action = $_action);
    }
    /**
     * Get DateTime value
     * @return dateTime|null
     */
    public function getDateTime()
    {
        return $this->DateTime;
    }
    /**
     * Set DateTime value
     * @param dateTime $_dateTime the DateTime
     * @return dateTime
     */
    public function setDateTime($_dateTime)
    {
        return ($this->DateTime = $_dateTime);
    }
    /**
     * Get Organisation value
     * @return string|null
     */
    public function getOrganisation()
    {
        return $this->Organisation;
    }
    /**
     * Set Organisation value
     * @param string $_organisation the Organisation
     * @return string
     */
    public function setOrganisation($_organisation)
    {
        return ($this->Organisation = $_organisation);
    }
    /**
     * Get User value
     * @return string|null
     */
    public function getUser()
    {
        return $this->User;
    }
    /**
     * Set User value
     * @param string $_user the User
     * @return string
     */
    public function setUser($_user)
    {
        return ($this->User = $_user);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructPlrAccessEntryResponse
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
