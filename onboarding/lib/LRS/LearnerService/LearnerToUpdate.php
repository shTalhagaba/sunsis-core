<?php

class LearnerToUpdate extends LearnerToRegister
{

    /**
     * @var string $ULN
     */
    protected $ULN = null;

    /**
     * @var int $VersionNumber
     */
    protected $VersionNumber = null;

    /**
     * @param int $VersionNumber
     */
    public function __construct($VersionNumber)
    {
      parent::__construct();
      $this->VersionNumber = $VersionNumber;
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
     * @return LearnerToUpdate
     */
    public function setULN($ULN)
    {
      $this->ULN = $ULN;
      return $this;
    }

    /**
     * @return int
     */
    public function getVersionNumber()
    {
      return $this->VersionNumber;
    }

    /**
     * @param int $VersionNumber
     * @return LearnerToUpdate
     */
    public function setVersionNumber($VersionNumber)
    {
      $this->VersionNumber = $VersionNumber;
      return $this;
    }

}
