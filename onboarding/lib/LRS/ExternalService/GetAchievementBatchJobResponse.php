<?php

class GetAchievementBatchJobResponse
{

    /**
     * @var AchievementBatchJobResponse $GetAchievementBatchJobResult
     */
    protected $GetAchievementBatchJobResult = null;

    /**
     * @param AchievementBatchJobResponse $GetAchievementBatchJobResult
     */
    public function __construct($GetAchievementBatchJobResult)
    {
      $this->GetAchievementBatchJobResult = $GetAchievementBatchJobResult;
    }

    /**
     * @return AchievementBatchJobResponse
     */
    public function getGetAchievementBatchJobResult()
    {
      return $this->GetAchievementBatchJobResult;
    }

    /**
     * @param AchievementBatchJobResponse $GetAchievementBatchJobResult
     * @return GetAchievementBatchJobResponse
     */
    public function setGetAchievementBatchJobResult($GetAchievementBatchJobResult)
    {
      $this->GetAchievementBatchJobResult = $GetAchievementBatchJobResult;
      return $this;
    }

}
