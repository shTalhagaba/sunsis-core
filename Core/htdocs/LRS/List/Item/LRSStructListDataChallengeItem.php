<?php
/**
 * File for class LRSStructListDataChallengeItem
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructListDataChallengeItem originally named ListDataChallengeItem
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructListDataChallengeItem extends LRSWsdlClass
{
    /**
     * The CreatedDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var dateTime
     */
    public $CreatedDate;
    /**
     * The ReferenceNo
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ReferenceNo;
    /**
     * The ResolvedDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $ResolvedDate;
    /**
     * The Status
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Status;
    /**
     * The Type
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Type;
    /**
     * Constructor method for ListDataChallengeItem
     * @see parent::__construct()
     * @param dateTime $_createdDate
     * @param string $_referenceNo
     * @param dateTime $_resolvedDate
     * @param string $_status
     * @param string $_type
     * @return LRSStructListDataChallengeItem
     */
    public function __construct($_createdDate = NULL,$_referenceNo = NULL,$_resolvedDate = NULL,$_status = NULL,$_type = NULL)
    {
        parent::__construct(array('CreatedDate'=>$_createdDate,'ReferenceNo'=>$_referenceNo,'ResolvedDate'=>$_resolvedDate,'Status'=>$_status,'Type'=>$_type),false);
    }
    /**
     * Get CreatedDate value
     * @return dateTime|null
     */
    public function getCreatedDate()
    {
        return $this->CreatedDate;
    }
    /**
     * Set CreatedDate value
     * @param dateTime $_createdDate the CreatedDate
     * @return dateTime
     */
    public function setCreatedDate($_createdDate)
    {
        return ($this->CreatedDate = $_createdDate);
    }
    /**
     * Get ReferenceNo value
     * @return string|null
     */
    public function getReferenceNo()
    {
        return $this->ReferenceNo;
    }
    /**
     * Set ReferenceNo value
     * @param string $_referenceNo the ReferenceNo
     * @return string
     */
    public function setReferenceNo($_referenceNo)
    {
        return ($this->ReferenceNo = $_referenceNo);
    }
    /**
     * Get ResolvedDate value
     * @return dateTime|null
     */
    public function getResolvedDate()
    {
        return $this->ResolvedDate;
    }
    /**
     * Set ResolvedDate value
     * @param dateTime $_resolvedDate the ResolvedDate
     * @return dateTime
     */
    public function setResolvedDate($_resolvedDate)
    {
        return ($this->ResolvedDate = $_resolvedDate);
    }
    /**
     * Get Status value
     * @return string|null
     */
    public function getStatus()
    {
        return $this->Status;
    }
    /**
     * Set Status value
     * @param string $_status the Status
     * @return string
     */
    public function setStatus($_status)
    {
        return ($this->Status = $_status);
    }
    /**
     * Get Type value
     * @return string|null
     */
    public function getType()
    {
        return $this->Type;
    }
    /**
     * Set Type value
     * @param string $_type the Type
     * @return string
     */
    public function setType($_type)
    {
        return ($this->Type = $_type);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructListDataChallengeItem
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
