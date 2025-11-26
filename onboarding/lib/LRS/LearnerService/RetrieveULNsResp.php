<?php

class RetrieveULNsResp extends LearnerServiceWrappedResponse
{

    /**
     * @var ArrayOfMIAPRetrievedULN $RetrievedULNs
     */
    protected $RetrievedULNs = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return ArrayOfMIAPRetrievedULN
     */
    public function getRetrievedULNs()
    {
      return $this->RetrievedULNs;
    }

    /**
     * @param ArrayOfMIAPRetrievedULN $RetrievedULNs
     * @return RetrieveULNsResp
     */
    public function setRetrievedULNs($RetrievedULNs)
    {
      $this->RetrievedULNs = $RetrievedULNs;
      return $this;
    }

}
