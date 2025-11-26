<?php

use GuzzleHttp\Promise\Is;

class LearnerByDemographicsRqst extends BaseFindLearnerServiceRqst
{

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

    /**
     * @var string $LastKnownPostCode
     */
    protected $LastKnownPostCode = null;

    /**
     * @var string $PreviousFamilyName
     */
    protected $PreviousFamilyName = null;

    /**
     * @var string $SchoolAtAge16
     */
    protected $SchoolAtAge16 = null;

    /**
     * @var string $PlaceOfBirth
     */
    protected $PlaceOfBirth = null;

    /**
     * @var string $EmailAddress
     */
    protected $EmailAddress = null;

    
    public function __construct($args = null)
    {
      if (is_array($args)) {
        foreach ($args as $key => $val) {
          $setter = 'set' . $key;
          if (method_exists($this, $setter)) {
            $this->$setter($val);
          }
        }
      }
      parent::__construct();
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
     * @return LearnerByDemographicsRqst
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
     * @return LearnerByDemographicsRqst
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
     * @return LearnerByDemographicsRqst
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
     * @return LearnerByDemographicsRqst
     */
    public function setGender($Gender)
    {
      $this->Gender = $Gender;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastKnownPostCode()
    {
      return $this->LastKnownPostCode;
    }

    /**
     * @param string $LastKnownPostCode
     * @return LearnerByDemographicsRqst
     */
    public function setLastKnownPostCode($LastKnownPostCode)
    {
      $this->LastKnownPostCode = $LastKnownPostCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getPreviousFamilyName()
    {
      return $this->PreviousFamilyName;
    }

    /**
     * @param string $PreviousFamilyName
     * @return LearnerByDemographicsRqst
     */
    public function setPreviousFamilyName($PreviousFamilyName)
    {
      $this->PreviousFamilyName = $PreviousFamilyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getSchoolAtAge16()
    {
      return $this->SchoolAtAge16;
    }

    /**
     * @param string $SchoolAtAge16
     * @return LearnerByDemographicsRqst
     */
    public function setSchoolAtAge16($SchoolAtAge16)
    {
      $this->SchoolAtAge16 = $SchoolAtAge16;
      return $this;
    }

    /**
     * @return string
     */
    public function getPlaceOfBirth()
    {
      return $this->PlaceOfBirth;
    }

    /**
     * @param string $PlaceOfBirth
     * @return LearnerByDemographicsRqst
     */
    public function setPlaceOfBirth($PlaceOfBirth)
    {
      $this->PlaceOfBirth = $PlaceOfBirth;
      return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
      return $this->EmailAddress;
    }

    /**
     * @param string $EmailAddress
     * @return LearnerByDemographicsRqst
     */
    public function setEmailAddress($EmailAddress)
    {
      $this->EmailAddress = $EmailAddress;
      return $this;
    }

}
