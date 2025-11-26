<?php

class RetrieveULNsRqst extends BaseLearnerServiceRqst
{

    /**
     * @var ArrayOfString $ULNsToRetrieve
     */
    protected $ULNsToRetrieve = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return ArrayOfString
     */
    public function getULNsToRetrieve()
    {
      return $this->ULNsToRetrieve;
    }

    /**
     * @param ArrayOfString $ULNsToRetrieve
     * @return RetrieveULNsRqst
     */
    public function setULNsToRetrieve($ULNsToRetrieve)
    {
      $this->ULNsToRetrieve = $ULNsToRetrieve;
      return $this;
    }

}
