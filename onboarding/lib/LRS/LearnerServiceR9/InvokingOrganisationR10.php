<?php

class InvokingOrganisationR10
{

    /**
     * @var string $OrganisationRef
     */
    protected $OrganisationRef = null;

    /**
     * @var string $Password
     */
    protected $Password = null;

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
     * @return string
     */
    public function getOrganisationRef()
    {
      return $this->OrganisationRef;
    }

    /**
     * @param string $OrganisationRef
     * @return InvokingOrganisationR10
     */
    public function setOrganisationRef($OrganisationRef)
    {
      $this->OrganisationRef = $OrganisationRef;
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
     * @return InvokingOrganisationR10
     */
    public function setPassword($Password)
    {
      $this->Password = $Password;
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
     * @return InvokingOrganisationR10
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
     * @return InvokingOrganisationR10
     */
    public function setUsername($Username)
    {
      $this->Username = $Username;
      return $this;
    }

}
