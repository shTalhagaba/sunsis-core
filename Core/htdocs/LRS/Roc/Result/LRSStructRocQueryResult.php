<?php
/**
 * File for class LRSStructRocQueryResult
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructRocQueryResult originally named RocQueryResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructRocQueryResult extends LRSStructBusinessObject
{
    /**
     * The CreditsTowards
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $CreditsTowards;
    /**
     * The Groups
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfRocQueryResultGroup
     */
    public $Groups;
    /**
     * The Qualification
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructQualification
     */
    public $Qualification;
    /**
     * The Units
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfRocQueryResultUnit
     */
    public $Units;
    /**
     * Constructor method for RocQueryResult
     * @see parent::__construct()
     * @param int $_creditsTowards
     * @param LRSStructArrayOfRocQueryResultGroup $_groups
     * @param LRSStructQualification $_qualification
     * @param LRSStructArrayOfRocQueryResultUnit $_units
     * @return LRSStructRocQueryResult
     */
    public function __construct($_creditsTowards = NULL,$_groups = NULL,$_qualification = NULL,$_units = NULL)
    {
        LRSWsdlClass::__construct(array('CreditsTowards'=>$_creditsTowards,'Groups'=>($_groups instanceof LRSStructArrayOfRocQueryResultGroup)?$_groups:new LRSStructArrayOfRocQueryResultGroup($_groups),'Qualification'=>$_qualification,'Units'=>($_units instanceof LRSStructArrayOfRocQueryResultUnit)?$_units:new LRSStructArrayOfRocQueryResultUnit($_units)),false);
    }
    /**
     * Get CreditsTowards value
     * @return int|null
     */
    public function getCreditsTowards()
    {
        return $this->CreditsTowards;
    }
    /**
     * Set CreditsTowards value
     * @param int $_creditsTowards the CreditsTowards
     * @return int
     */
    public function setCreditsTowards($_creditsTowards)
    {
        return ($this->CreditsTowards = $_creditsTowards);
    }
    /**
     * Get Groups value
     * @return LRSStructArrayOfRocQueryResultGroup|null
     */
    public function getGroups()
    {
        return $this->Groups;
    }
    /**
     * Set Groups value
     * @param LRSStructArrayOfRocQueryResultGroup $_groups the Groups
     * @return LRSStructArrayOfRocQueryResultGroup
     */
    public function setGroups($_groups)
    {
        return ($this->Groups = $_groups);
    }
    /**
     * Get Qualification value
     * @return LRSStructQualification|null
     */
    public function getQualification()
    {
        return $this->Qualification;
    }
    /**
     * Set Qualification value
     * @param LRSStructQualification $_qualification the Qualification
     * @return LRSStructQualification
     */
    public function setQualification($_qualification)
    {
        return ($this->Qualification = $_qualification);
    }
    /**
     * Get Units value
     * @return LRSStructArrayOfRocQueryResultUnit|null
     */
    public function getUnits()
    {
        return $this->Units;
    }
    /**
     * Set Units value
     * @param LRSStructArrayOfRocQueryResultUnit $_units the Units
     * @return LRSStructArrayOfRocQueryResultUnit
     */
    public function setUnits($_units)
    {
        return ($this->Units = $_units);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructRocQueryResult
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
