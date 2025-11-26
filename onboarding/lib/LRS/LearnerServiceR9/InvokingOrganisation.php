<?php

class InvokingOrganisation
{

    /**
     * @var ChannelCode $ChannelCode
     */
    protected $ChannelCode = null;

    /**
     * @var int $Estab
     */
    protected $Estab = null;

    /**
     * @var int $LaNumber
     */
    protected $LaNumber = null;

    /**
     * @var string $Password
     */
    protected $Password = null;

    /**
     * @var string $Reference
     */
    protected $Reference = null;

    /**
     * @var string $Ukprn
     */
    protected $Ukprn = null;

    /**
     * @var string $Username
     */
    protected $Username = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return ChannelCode
     */
    public function getChannelCode()
    {
      return $this->ChannelCode;
    }

    /**
     * @param ChannelCode $ChannelCode
     * @return InvokingOrganisation
     */
    public function setChannelCode($ChannelCode)
    {
      $this->ChannelCode = $ChannelCode;
      return $this;
    }

    /**
     * @return int
     */
    public function getEstab()
    {
      return $this->Estab;
    }

    /**
     * @param int $Estab
     * @return InvokingOrganisation
     */
    public function setEstab($Estab)
    {
      $this->Estab = $Estab;
      return $this;
    }

    /**
     * @return int
     */
    public function getLaNumber()
    {
      return $this->LaNumber;
    }

    /**
     * @param int $LaNumber
     * @return InvokingOrganisation
     */
    public function setLaNumber($LaNumber)
    {
      $this->LaNumber = $LaNumber;
      return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
      return $this->Password;
    }

    /**
     * @param string $Password
     * @return InvokingOrganisation
     */
    public function setPassword($Password)
    {
      $this->Password = $Password;
      return $this;
    }

    /**
     * @return string
     */
    public function getReference()
    {
      return $this->Reference;
    }

    /**
     * @param string $Reference
     * @return InvokingOrganisation
     */
    public function setReference($Reference)
    {
      $this->Reference = $Reference;
      return $this;
    }

    /**
     * @return string
     */
    public function getUkprn()
    {
      return $this->Ukprn;
    }

    /**
     * @param string $Ukprn
     * @return InvokingOrganisation
     */
    public function setUkprn($Ukprn)
    {
      $this->Ukprn = $Ukprn;
      return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
      return $this->Username;
    }

    /**
     * @param string $Username
     * @return InvokingOrganisation
     */
    public function setUsername($Username)
    {
      $this->Username = $Username;
      return $this;
    }

}
