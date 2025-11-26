<?php

class PagedListOfPlrAccessEntryResponseN4dIIFC_S
{

    /**
     * @var ArrayOfPlrAccessEntryResponse $List
     */
    protected $List = null;

    /**
     * @var int $PageCount
     */
    protected $PageCount = null;

    /**
     * @var int $PageIndex
     */
    protected $PageIndex = null;

    /**
     * @var int $PageSize
     */
    protected $PageSize = null;

    /**
     * @var int $SortIndex
     */
    protected $SortIndex = null;

    /**
     * @var int $SortOrder
     */
    protected $SortOrder = null;

    /**
     * @var int $TotalItems
     */
    protected $TotalItems = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return ArrayOfPlrAccessEntryResponse
     */
    public function getList()
    {
      return $this->List;
    }

    /**
     * @param ArrayOfPlrAccessEntryResponse $List
     * @return PagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function setList($List)
    {
      $this->List = $List;
      return $this;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
      return $this->PageCount;
    }

    /**
     * @param int $PageCount
     * @return PagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function setPageCount($PageCount)
    {
      $this->PageCount = $PageCount;
      return $this;
    }

    /**
     * @return int
     */
    public function getPageIndex()
    {
      return $this->PageIndex;
    }

    /**
     * @param int $PageIndex
     * @return PagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function setPageIndex($PageIndex)
    {
      $this->PageIndex = $PageIndex;
      return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
      return $this->PageSize;
    }

    /**
     * @param int $PageSize
     * @return PagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function setPageSize($PageSize)
    {
      $this->PageSize = $PageSize;
      return $this;
    }

    /**
     * @return int
     */
    public function getSortIndex()
    {
      return $this->SortIndex;
    }

    /**
     * @param int $SortIndex
     * @return PagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function setSortIndex($SortIndex)
    {
      $this->SortIndex = $SortIndex;
      return $this;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
      return $this->SortOrder;
    }

    /**
     * @param int $SortOrder
     * @return PagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function setSortOrder($SortOrder)
    {
      $this->SortOrder = $SortOrder;
      return $this;
    }

    /**
     * @return int
     */
    public function getTotalItems()
    {
      return $this->TotalItems;
    }

    /**
     * @param int $TotalItems
     * @return PagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function setTotalItems($TotalItems)
    {
      $this->TotalItems = $TotalItems;
      return $this;
    }

}
