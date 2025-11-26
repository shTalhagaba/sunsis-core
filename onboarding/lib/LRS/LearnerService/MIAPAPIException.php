<?php

class MIAPAPIException
{

    /**
     * @var string $ErrorCode
     */
    protected $ErrorCode = null;

    /**
     * @var string $ErrorActor
     */
    protected $ErrorActor = null;

    /**
     * @var string $Description
     */
    protected $Description = null;

    /**
     * @var string $FurtherDetails
     */
    protected $FurtherDetails = null;

    /**
     * @var string $ErrorTimestamp
     */
    protected $ErrorTimestamp = null;

    
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
     * @return MIAPAPIException
     */
    public function setErrorCode($ErrorCode)
    {
      $this->ErrorCode = $ErrorCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getErrorActor()
    {
      return $this->ErrorActor;
    }

    /**
     * @param string $ErrorActor
     * @return MIAPAPIException
     */
    public function setErrorActor($ErrorActor)
    {
      $this->ErrorActor = $ErrorActor;
      return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
      return $this->Description;
    }

    /**
     * @param string $Description
     * @return MIAPAPIException
     */
    public function setDescription($Description)
    {
      $this->Description = $Description;
      return $this;
    }

    /**
     * @return string
     */
    public function getFurtherDetails()
    {
      return $this->FurtherDetails;
    }

    /**
     * @param string $FurtherDetails
     * @return MIAPAPIException
     */
    public function setFurtherDetails($FurtherDetails)
    {
      $this->FurtherDetails = $FurtherDetails;
      return $this;
    }

    /**
     * @return string
     */
    public function getErrorTimestamp()
    {
      return $this->ErrorTimestamp;
    }

    /**
     * @param string $ErrorTimestamp
     * @return MIAPAPIException
     */
    public function setErrorTimestamp($ErrorTimestamp)
    {
      $this->ErrorTimestamp = $ErrorTimestamp;
      return $this;
    }

}
