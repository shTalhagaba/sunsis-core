<?php
/**
 * File for class LRSStructSnapshot
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructSnapshot originally named Snapshot
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.services.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructSnapshot extends LRSWsdlClass
{
    /**
     * The Accessed
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $Accessed;
    /**
     * The DateCreated
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var dateTime
     */
    public $DateCreated;
    /**
     * The Events
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfSnapshotEvent
     */
    public $Events;
    /**
     * The ExpiryDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var dateTime
     */
    public $ExpiryDate;
    /**
     * The Guid
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - pattern : [\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}
     * @var string
     */
    public $Guid;
    /**
     * The TargetEmail
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $TargetEmail;
    /**
     * The UserReference
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $UserReference;
    /**
     * Constructor method for Snapshot
     * @see parent::__construct()
     * @param boolean $_accessed
     * @param dateTime $_dateCreated
     * @param LRSStructArrayOfSnapshotEvent $_events
     * @param dateTime $_expiryDate
     * @param string $_guid
     * @param string $_targetEmail
     * @param string $_userReference
     * @return LRSStructSnapshot
     */
    public function __construct($_accessed = NULL,$_dateCreated = NULL,$_events = NULL,$_expiryDate = NULL,$_guid = NULL,$_targetEmail = NULL,$_userReference = NULL)
    {
        parent::__construct(array('Accessed'=>$_accessed,'DateCreated'=>$_dateCreated,'Events'=>($_events instanceof LRSStructArrayOfSnapshotEvent)?$_events:new LRSStructArrayOfSnapshotEvent($_events),'ExpiryDate'=>$_expiryDate,'Guid'=>$_guid,'TargetEmail'=>$_targetEmail,'UserReference'=>$_userReference),false);
    }
    /**
     * Get Accessed value
     * @return boolean|null
     */
    public function getAccessed()
    {
        return $this->Accessed;
    }
    /**
     * Set Accessed value
     * @param boolean $_accessed the Accessed
     * @return boolean
     */
    public function setAccessed($_accessed)
    {
        return ($this->Accessed = $_accessed);
    }
    /**
     * Get DateCreated value
     * @return dateTime|null
     */
    public function getDateCreated()
    {
        return $this->DateCreated;
    }
    /**
     * Set DateCreated value
     * @param dateTime $_dateCreated the DateCreated
     * @return dateTime
     */
    public function setDateCreated($_dateCreated)
    {
        return ($this->DateCreated = $_dateCreated);
    }
    /**
     * Get Events value
     * @return LRSStructArrayOfSnapshotEvent|null
     */
    public function getEvents()
    {
        return $this->Events;
    }
    /**
     * Set Events value
     * @param LRSStructArrayOfSnapshotEvent $_events the Events
     * @return LRSStructArrayOfSnapshotEvent
     */
    public function setEvents($_events)
    {
        return ($this->Events = $_events);
    }
    /**
     * Get ExpiryDate value
     * @return dateTime|null
     */
    public function getExpiryDate()
    {
        return $this->ExpiryDate;
    }
    /**
     * Set ExpiryDate value
     * @param dateTime $_expiryDate the ExpiryDate
     * @return dateTime
     */
    public function setExpiryDate($_expiryDate)
    {
        return ($this->ExpiryDate = $_expiryDate);
    }
    /**
     * Get Guid value
     * @return string|null
     */
    public function getGuid()
    {
        return $this->Guid;
    }
    /**
     * Set Guid value
     * @param string $_guid the Guid
     * @return string
     */
    public function setGuid($_guid)
    {
        return ($this->Guid = $_guid);
    }
    /**
     * Get TargetEmail value
     * @return string|null
     */
    public function getTargetEmail()
    {
        return $this->TargetEmail;
    }
    /**
     * Set TargetEmail value
     * @param string $_targetEmail the TargetEmail
     * @return string
     */
    public function setTargetEmail($_targetEmail)
    {
        return ($this->TargetEmail = $_targetEmail);
    }
    /**
     * Get UserReference value
     * @return string|null
     */
    public function getUserReference()
    {
        return $this->UserReference;
    }
    /**
     * Set UserReference value
     * @param string $_userReference the UserReference
     * @return string
     */
    public function setUserReference($_userReference)
    {
        return ($this->UserReference = $_userReference);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructSnapshot
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
