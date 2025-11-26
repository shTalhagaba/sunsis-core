<?php

class MIAPRetrievedULN
{

    /**
     * @var string $ULN
     */
    protected $ULN = null;

    /**
     * @var string $MasterULN
     */
    protected $MasterULN = null;

    /**
     * @var string $ResponseCode
     */
    protected $ResponseCode = null;

    /**
     * @var string $FamilyName
     */
    protected $FamilyName = null;

    /**
     * @var string $GivenName
     */
    protected $GivenName = null;

    /**
     * @var string $DateOfBirth
     */
    protected $DateOfBirth = null;

    /**
     * @var string $Gender
     */
    protected $Gender = null;

    
    public function __construct()
    {
    
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
     * @return MIAPRetrievedULN
     */
    public function setULN($ULN)
    {
      $this->ULN = $ULN;
      return $this;
    }

    /**
     * @return string
     */
    public function getMasterULN()
    {
      return $this->MasterULN;
    }

    /**
     * @param string $MasterULN
     * @return MIAPRetrievedULN
     */
    public function setMasterULN($MasterULN)
    {
      $this->MasterULN = $MasterULN;
      return $this;
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
     * @return MIAPRetrievedULN
     */
    public function setResponseCode($ResponseCode)
    {
      $this->ResponseCode = $ResponseCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
      return $this->FamilyName;
    }

    /**
     * @param string $FamilyName
     * @return MIAPRetrievedULN
     */
    public function setFamilyName($FamilyName)
    {
      $this->FamilyName = $FamilyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
      return $this->GivenName;
    }

    /**
     * @param string $GivenName
     * @return MIAPRetrievedULN
     */
    public function setGivenName($GivenName)
    {
      $this->GivenName = $GivenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getDateOfBirth()
    {
      return $this->DateOfBirth;
    }

    /**
     * @param string $DateOfBirth
     * @return MIAPRetrievedULN
     */
    public function setDateOfBirth($DateOfBirth)
    {
      $this->DateOfBirth = $DateOfBirth;
      return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
      return $this->Gender;
    }

    /**
     * @param string $Gender
     * @return MIAPRetrievedULN
     */
    public function setGender($Gender)
    {
      $this->Gender = $Gender;
      return $this;
    }

}
