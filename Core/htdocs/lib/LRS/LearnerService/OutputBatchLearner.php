<?php

class OutputBatchLearner extends BatchLearner
{

    /**
     * @var string $ReturnCode
     */
    protected $ReturnCode = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return string
     */
    public function getReturnCode()
    {
      return $this->ReturnCode;
    }

    /**
     * @param string $ReturnCode
     * @return OutputBatchLearner
     */
    public function setReturnCode($ReturnCode)
    {
      $this->ReturnCode = $ReturnCode;
      return $this;
    }

}
