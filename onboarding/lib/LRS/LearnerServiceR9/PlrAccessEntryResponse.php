<?php

class PlrAccessEntryResponse
{

    /**
     * @var string $Action
     */
    protected $Action = null;

    /**
     * @var \DateTime $DateTime
     */
    protected $DateTime = null;

    /**
     * @var string $Organisation
     */
    protected $Organisation = null;

    /**
     * @var string $User
     */
    protected $User = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getAction()
    {
      return $this->Action;
    }

    /**
     * @param string $Action
     * @return PlrAccessEntryResponse
     */
    public function setAction($Action)
    {
      $this->Action = $Action;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
      if ($this->DateTime == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->DateTime);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $DateTime
     * @return PlrAccessEntryResponse
     */
    public function setDateTime(\DateTime $DateTime = null)
    {
      if ($DateTime == null) {
       $this->DateTime = null;
      } else {
        $this->DateTime = $DateTime->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return string
     */
    public function getOrganisation()
    {
      return $this->Organisation;
    }

    /**
     * @param string $Organisation
     * @return PlrAccessEntryResponse
     */
    public function setOrganisation($Organisation)
    {
      $this->Organisation = $Organisation;
      return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
      return $this->User;
    }

    /**
     * @param string $User
     * @return PlrAccessEntryResponse
     */
    public function setUser($User)
    {
      $this->User = $User;
      return $this;
    }

}
