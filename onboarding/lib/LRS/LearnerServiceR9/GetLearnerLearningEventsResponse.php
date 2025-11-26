<?php

class GetLearnerLearningEventsResponse extends ServiceResponseR9
{

    /**
     * @var string $FoundULN
     */
    public $FoundULN = null;

    /**
     * @var string $IncomingULN
     */
    protected $IncomingULN = null;

    /**
     * @var ArrayOfLearningEvent $LearnerRecord
     */
    protected $LearnerRecord = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return string
     */
    public function getFoundULN()
    {
      return $this->FoundULN;
    }

    /**
     * @param string $FoundULN
     * @return GetLearnerLearningEventsResponse
     */
    public function setFoundULN($FoundULN)
    {
      $this->FoundULN = $FoundULN;
      return $this;
    }

    /**
     * @return string
     */
    public function getIncomingULN()
    {
      return $this->IncomingULN;
    }

    /**
     * @param string $IncomingULN
     * @return GetLearnerLearningEventsResponse
     */
    public function setIncomingULN($IncomingULN)
    {
      $this->IncomingULN = $IncomingULN;
      return $this;
    }

    /**
     * @return ArrayOfLearningEvent
     */
    public function getLearnerRecord()
    {
      return $this->LearnerRecord;
    }

    /**
     * @param ArrayOfLearningEvent $LearnerRecord
     * @return GetLearnerLearningEventsResponse
     */
    public function setLearnerRecord($LearnerRecord)
    {
      $this->LearnerRecord = $LearnerRecord;
      return $this;
    }

}
