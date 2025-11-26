<?php

class ServiceResponseR9
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
     * @return ServiceResponseR9
     */
    public function setResponseCode($ResponseCode)
    {
      $this->ResponseCode = $ResponseCode;
      return $this;
    }

}
