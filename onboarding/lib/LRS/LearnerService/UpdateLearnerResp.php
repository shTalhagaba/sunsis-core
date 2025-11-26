<?php

class UpdateLearnerResp extends LearnerServiceWrappedResponse
{

    /**
     * @var string $ResponseCode
     */
    protected $ResponseCode = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getResponseCode()
    {
      return $this->ResponseCode;
    }

    /**
     * @param string $ResponseCode
     * @return UpdateLearnerResp
     */
    public function setResponseCode($ResponseCode)
    {
      $this->ResponseCode = $ResponseCode;
      return $this;
    }

}
