<?php

class User extends BusinessObject
{

    /**
     * @var string $OrgPassword
     */
    protected $OrgPassword = null;

    /**
     * @var string $OrganisationRef
     */
    protected $OrganisationRef = null;

    /**
     * @var string $UserName
     */
    protected $UserName = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getOrgPassword()
    {
      return $this->OrgPassword;
    }

    /**
     * @param string $OrgPassword
     * @return User
     */
    public function setOrgPassword($OrgPassword)
    {
      $this->OrgPassword = $OrgPassword;
      return $this;
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
     * @return User
     */
    public function setOrganisationRef($OrganisationRef)
    {
      $this->OrganisationRef = $OrganisationRef;
      return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
      return $this->UserName;
    }

    /**
     * @param string $UserName
     * @return User
     */
    public function setUserName($UserName)
    {
      $this->UserName = $UserName;
      return $this;
    }

}
