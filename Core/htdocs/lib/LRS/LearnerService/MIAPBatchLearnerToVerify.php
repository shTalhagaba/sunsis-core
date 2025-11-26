<?php

class MIAPBatchLearnerToVerify extends BaseLearnerServiceRequestPart
{

    /**
     * @var string $MISIdentifier
     */
    protected $MISIdentifier = null;

    /**
     * @var string $ULN
     */
    protected $ULN = null;

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
    public function getMISIdentifier()
    {
      return $this->MISIdentifier;
    }

    /**
     * @param string $MISIdentifier
     * @return MIAPBatchLearnerToVerify
     */
    public function setMISIdentifier($MISIdentifier)
    {
      $this->MISIdentifier = $MISIdentifier;
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
     * @return MIAPBatchLearnerToVerify
     */
    public function setULN($ULN)
    {
      $this->ULN = $ULN;
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
     * @return MIAPBatchLearnerToVerify
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
     * @return MIAPBatchLearnerToVerify
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
     * @return MIAPBatchLearnerToVerify
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
     * @return MIAPBatchLearnerToVerify
     */
    public function setGender($Gender)
    {
      $this->Gender = $Gender;
      return $this;
    }

}
