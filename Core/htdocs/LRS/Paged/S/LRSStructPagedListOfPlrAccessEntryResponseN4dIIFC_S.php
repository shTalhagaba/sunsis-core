<?php
/**
 * File for class LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S originally named PagedListOfPlrAccessEntryResponseN4dIIFC_S
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S extends LRSWsdlClass
{
    /**
     * The List
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfPlrAccessEntryResponse
     */
    public $List;
    /**
     * The PageCount
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $PageCount;
    /**
     * The PageIndex
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $PageIndex;
    /**
     * The PageSize
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $PageSize;
    /**
     * The SortIndex
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $SortIndex;
    /**
     * The SortOrder
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $SortOrder;
    /**
     * The TotalItems
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $TotalItems;
    /**
     * Constructor method for PagedListOfPlrAccessEntryResponseN4dIIFC_S
     * @see parent::__construct()
     * @param LRSStructArrayOfPlrAccessEntryResponse $_list
     * @param int $_pageCount
     * @param int $_pageIndex
     * @param int $_pageSize
     * @param int $_sortIndex
     * @param int $_sortOrder
     * @param int $_totalItems
     * @return LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function __construct($_list = NULL,$_pageCount = NULL,$_pageIndex = NULL,$_pageSize = NULL,$_sortIndex = NULL,$_sortOrder = NULL,$_totalItems = NULL)
    {
        parent::__construct(array('List'=>($_list instanceof LRSStructArrayOfPlrAccessEntryResponse)?$_list:new LRSStructArrayOfPlrAccessEntryResponse($_list),'PageCount'=>$_pageCount,'PageIndex'=>$_pageIndex,'PageSize'=>$_pageSize,'SortIndex'=>$_sortIndex,'SortOrder'=>$_sortOrder,'TotalItems'=>$_totalItems),false);
    }
    /**
     * Get List value
     * @return LRSStructArrayOfPlrAccessEntryResponse|null
     */
    public function getList()
    {
        return $this->List;
    }
    /**
     * Set List value
     * @param LRSStructArrayOfPlrAccessEntryResponse $_list the List
     * @return LRSStructArrayOfPlrAccessEntryResponse
     */
    public function setList($_list)
    {
        return ($this->List = $_list);
    }
    /**
     * Get PageCount value
     * @return int|null
     */
    public function getPageCount()
    {
        return $this->PageCount;
    }
    /**
     * Set PageCount value
     * @param int $_pageCount the PageCount
     * @return int
     */
    public function setPageCount($_pageCount)
    {
        return ($this->PageCount = $_pageCount);
    }
    /**
     * Get PageIndex value
     * @return int|null
     */
    public function getPageIndex()
    {
        return $this->PageIndex;
    }
    /**
     * Set PageIndex value
     * @param int $_pageIndex the PageIndex
     * @return int
     */
    public function setPageIndex($_pageIndex)
    {
        return ($this->PageIndex = $_pageIndex);
    }
    /**
     * Get PageSize value
     * @return int|null
     */
    public function getPageSize()
    {
        return $this->PageSize;
    }
    /**
     * Set PageSize value
     * @param int $_pageSize the PageSize
     * @return int
     */
    public function setPageSize($_pageSize)
    {
        return ($this->PageSize = $_pageSize);
    }
    /**
     * Get SortIndex value
     * @return int|null
     */
    public function getSortIndex()
    {
        return $this->SortIndex;
    }
    /**
     * Set SortIndex value
     * @param int $_sortIndex the SortIndex
     * @return int
     */
    public function setSortIndex($_sortIndex)
    {
        return ($this->SortIndex = $_sortIndex);
    }
    /**
     * Get SortOrder value
     * @return int|null
     */
    public function getSortOrder()
    {
        return $this->SortOrder;
    }
    /**
     * Set SortOrder value
     * @param int $_sortOrder the SortOrder
     * @return int
     */
    public function setSortOrder($_sortOrder)
    {
        return ($this->SortOrder = $_sortOrder);
    }
    /**
     * Get TotalItems value
     * @return int|null
     */
    public function getTotalItems()
    {
        return $this->TotalItems;
    }
    /**
     * Set TotalItems value
     * @param int $_totalItems the TotalItems
     * @return int
     */
    public function setTotalItems($_totalItems)
    {
        return ($this->TotalItems = $_totalItems);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructPagedListOfPlrAccessEntryResponseN4dIIFC_S
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
