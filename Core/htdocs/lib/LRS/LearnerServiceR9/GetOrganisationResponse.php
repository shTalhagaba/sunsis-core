<?php

class GetOrganisationResponse extends ServiceResponseR9
{

    /**
     * @var string $DisplayName
     */
    protected $DisplayName = null;

    /**
     * @var string $OrgRef
     */
    protected $OrgRef = null;

    /**
     * @var string $Status
     */
    protected $Status = null;

    /**
     * @var string $Ukprn
     */
    protected $Ukprn = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
      return $this->DisplayName;
    }

    /**
     * @param string $DisplayName
     * @return GetOrganisationResponse
     */
    public function setDisplayName($DisplayName)
    {
      $this->DisplayName = $DisplayName;
      return $this;
    }

    /**
     * @return string
     */
    public function getOrgRef()
    {
      return $this->OrgRef;
    }

    /**
     * @param string $OrgRef
     * @return GetOrganisationResponse
     */
    public function setOrgRef($OrgRef)
    {
      $this->OrgRef = $OrgRef;
      return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
      return $this->Status;
    }

    /**
     * @param string $Status
     * @return GetOrganisationResponse
     */
    public function setStatus($Status)
    {
      $this->Status = $Status;
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
     * @return GetOrganisationResponse
     */
    public function setUkprn($Ukprn)
    {
      $this->Ukprn = $Ukprn;
      return $this;
    }

}
