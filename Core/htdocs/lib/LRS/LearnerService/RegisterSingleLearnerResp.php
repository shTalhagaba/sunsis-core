<?php

class RegisterSingleLearnerResp extends LearnerServiceWrappedResponse
{

    /**
     * @var string $ResponseCode
     */
    protected $ResponseCode = null;

    /**
     * @var string $ULN
     */
    protected $ULN = null;

    
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
     * @return RegisterSingleLearnerResp
     */
    public function setResponseCode($ResponseCode)
    {
      $this->ResponseCode = $ResponseCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getULN()
    {
      return $this->ULN;
    }

    /**
     * @param string $ULN
     * @return RegisterSingleLearnerResp
     */
    public function setULN($ULN)
    {
      $this->ULN = $ULN;
      return $this;
    }

}
