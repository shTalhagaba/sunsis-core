<?php

class UpdateLearnerSubsetFieldsResponse
{

    /**
     * @var string $UpdateLearnerSubsetFieldsResult
     */
    protected $UpdateLearnerSubsetFieldsResult = null;

    /**
     * @param string $UpdateLearnerSubsetFieldsResult
     */
    public function __construct($UpdateLearnerSubsetFieldsResult)
    {
      $this->UpdateLearnerSubsetFieldsResult = $UpdateLearnerSubsetFieldsResult;
    }

    /**
     * @return string
     */
    public function getUpdateLearnerSubsetFieldsResult()
    {
      return $this->UpdateLearnerSubsetFieldsResult;
    }

    /**
     * @param string $UpdateLearnerSubsetFieldsResult
     * @return UpdateLearnerSubsetFieldsResponse
     */
    public function setUpdateLearnerSubsetFieldsResult($UpdateLearnerSubsetFieldsResult)
    {
      $this->UpdateLearnerSubsetFieldsResult = $UpdateLearnerSubsetFieldsResult;
      return $this;
    }

}
