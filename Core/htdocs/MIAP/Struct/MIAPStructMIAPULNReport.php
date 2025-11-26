<?php
/**
 * File for class MIAPStructMIAPULNReport
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPULNReport originally named MIAPULNReport
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPULNReport extends MIAPWsdlClass
{
    /**
     * The MergedLearners
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var MIAPStructMIAPMergedLearners
     */
    public $MergedLearners;
    /**
     * The UnmergedLearners
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var MIAPStructMIAPUnmergedLearners
     */
    public $UnmergedLearners;
    /**
     * The DeletedLearners
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var MIAPStructMIAPDeletedLearners
     */
    public $DeletedLearners;
    /**
     * Constructor method for MIAPULNReport
     * @see parent::__construct()
     * @param MIAPStructMIAPMergedLearners $_mergedLearners
     * @param MIAPStructMIAPUnmergedLearners $_unmergedLearners
     * @param MIAPStructMIAPDeletedLearners $_deletedLearners
     * @return MIAPStructMIAPULNReport
     */
    public function __construct($_mergedLearners = NULL,$_unmergedLearners = NULL,$_deletedLearners = NULL)
    {
        parent::__construct(array('MergedLearners'=>$_mergedLearners,'UnmergedLearners'=>$_unmergedLearners,'DeletedLearners'=>$_deletedLearners),false);
    }
    /**
     * Get MergedLearners value
     * @return MIAPStructMIAPMergedLearners|null
     */
    public function getMergedLearners()
    {
        return $this->MergedLearners;
    }
    /**
     * Set MergedLearners value
     * @param MIAPStructMIAPMergedLearners $_mergedLearners the MergedLearners
     * @return MIAPStructMIAPMergedLearners
     */
    public function setMergedLearners($_mergedLearners)
    {
        return ($this->MergedLearners = $_mergedLearners);
    }
    /**
     * Get UnmergedLearners value
     * @return MIAPStructMIAPUnmergedLearners|null
     */
    public function getUnmergedLearners()
    {
        return $this->UnmergedLearners;
    }
    /**
     * Set UnmergedLearners value
     * @param MIAPStructMIAPUnmergedLearners $_unmergedLearners the UnmergedLearners
     * @return MIAPStructMIAPUnmergedLearners
     */
    public function setUnmergedLearners($_unmergedLearners)
    {
        return ($this->UnmergedLearners = $_unmergedLearners);
    }
    /**
     * Get DeletedLearners value
     * @return MIAPStructMIAPDeletedLearners|null
     */
    public function getDeletedLearners()
    {
        return $this->DeletedLearners;
    }
    /**
     * Set DeletedLearners value
     * @param MIAPStructMIAPDeletedLearners $_deletedLearners the DeletedLearners
     * @return MIAPStructMIAPDeletedLearners
     */
    public function setDeletedLearners($_deletedLearners)
    {
        return ($this->DeletedLearners = $_deletedLearners);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPULNReport
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
