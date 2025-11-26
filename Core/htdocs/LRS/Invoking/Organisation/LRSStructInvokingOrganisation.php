<?php
/**
 * File for class LRSStructInvokingOrganisation
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructInvokingOrganisation originally named InvokingOrganisation
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/Amor.Qcf.Common.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructInvokingOrganisation extends LRSWsdlClass
{
    /**
     * The ChannelCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var LRSEnumChannel
     */
    public $ChannelCode;
    /**
     * The Password
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Password;
    /**
     * The Reference
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Reference;
    /**
     * The Ukprn
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Ukprn;
    /**
     * The Username
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Username;
    /**
     * Constructor method for InvokingOrganisation
     * @see parent::__construct()
     * @param LRSEnumChannel $_channelCode
     * @param string $_password
     * @param string $_reference
     * @param string $_ukprn
     * @param string $_username
     * @return LRSStructInvokingOrganisation
     */
    public function __construct($_channelCode = NULL,$_password = NULL,$_reference = NULL,$_ukprn = NULL,$_username = NULL)
    {
        parent::__construct(array('ChannelCode'=>$_channelCode,'Password'=>$_password,'Reference'=>$_reference,'Ukprn'=>$_ukprn,'Username'=>$_username),false);
    }
    /**
     * Get ChannelCode value
     * @return LRSEnumChannel|null
     */
    public function getChannelCode()
    {
        return $this->ChannelCode;
    }
    /**
     * Set ChannelCode value
     * @uses LRSEnumChannel::valueIsValid()
     * @param LRSEnumChannel $_channelCode the ChannelCode
     * @return LRSEnumChannel
     */
    public function setChannelCode($_channelCode)
    {
        if(!LRSEnumChannel::valueIsValid($_channelCode))
        {
            return false;
        }
        return ($this->ChannelCode = $_channelCode);
    }
    /**
     * Get Password value
     * @return string|null
     */
    public function getPassword()
    {
        return $this->Password;
    }
    /**
     * Set Password value
     * @param string $_password the Password
     * @return string
     */
    public function setPassword($_password)
    {
        return ($this->Password = $_password);
    }
    /**
     * Get Reference value
     * @return string|null
     */
    public function getReference()
    {
        return $this->Reference;
    }
    /**
     * Set Reference value
     * @param string $_reference the Reference
     * @return string
     */
    public function setReference($_reference)
    {
        return ($this->Reference = $_reference);
    }
    /**
     * Get Ukprn value
     * @return string|null
     */
    public function getUkprn()
    {
        return $this->Ukprn;
    }
    /**
     * Set Ukprn value
     * @param string $_ukprn the Ukprn
     * @return string
     */
    public function setUkprn($_ukprn)
    {
        return ($this->Ukprn = $_ukprn);
    }
    /**
     * Get Username value
     * @return string|null
     */
    public function getUsername()
    {
        return $this->Username;
    }
    /**
     * Set Username value
     * @param string $_username the Username
     * @return string
     */
    public function setUsername($_username)
    {
        return ($this->Username = $_username);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructInvokingOrganisation
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
