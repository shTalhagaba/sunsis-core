<?php

class ViewAuditResponse
{

    /**
     * @var PagedListOfPlrAccessEntryResponseN4dIIFC_S $ViewAuditResult
     */
    protected $ViewAuditResult = null;

    /**
     * @param PagedListOfPlrAccessEntryResponseN4dIIFC_S $ViewAuditResult
     */
    public function __construct($ViewAuditResult)
    {
      $this->ViewAuditResult = $ViewAuditResult;
    }

    /**
     * @return PagedListOfPlrAccessEntryResponseN4dIIFC_S
     */
    public function getViewAuditResult()
    {
      return $this->ViewAuditResult;
    }

    /**
     * @param PagedListOfPlrAccessEntryResponseN4dIIFC_S $ViewAuditResult
     * @return ViewAuditResponse
     */
    public function setViewAuditResult($ViewAuditResult)
    {
      $this->ViewAuditResult = $ViewAuditResult;
      return $this;
    }

}
