<?php

class DomainFault
{

    /**
     * @var string $ErrorCode
     */
    protected $ErrorCode = null;

    /**
     * @var base64Binary $ErrorDetail
     */
    protected $ErrorDetail = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
      return $this->ErrorCode;
    }

    /**
     * @param string $ErrorCode
     * @return DomainFault
     */
    public function setErrorCode($ErrorCode)
    {
      $this->ErrorCode = $ErrorCode;
      return $this;
    }

    /**
     * @return base64Binary
     */
    public function getErrorDetail()
    {
      return $this->ErrorDetail;
    }

    /**
     * @param base64Binary $ErrorDetail
     * @return DomainFault
     */
    public function setErrorDetail($ErrorDetail)
    {
      $this->ErrorDetail = $ErrorDetail;
      return $this;
    }

}
