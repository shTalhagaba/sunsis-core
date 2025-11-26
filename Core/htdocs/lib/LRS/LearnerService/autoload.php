<?php


 function autoload_bdc8d08b3b8d0781684f5f6fec191e14($class)
{
    $classes = array(
        'LearnerServiceProxy' => __DIR__ .'/LearnerServiceProxy.php',
        'LearnerByULNRqst' => __DIR__ .'/LearnerByULNRqst.php',
        'BaseFindLearnerServiceRqst' => __DIR__ .'/BaseFindLearnerServiceRqst.php',
        'BaseLearnerServiceRequestPart' => __DIR__ .'/BaseLearnerServiceRequestPart.php',
        'FindLearnerResp' => __DIR__ .'/FindLearnerResp.php',
        'LearnerServiceWrappedResponse' => __DIR__ .'/LearnerServiceWrappedResponse.php',
        'LearnerByDemographicsRqst' => __DIR__ .'/LearnerByDemographicsRqst.php',
        'Learner' => __DIR__ .'/Learner.php',
        'ArrayOfString' => __DIR__ .'/ArrayOfString.php',
        'LearnerToRegister' => __DIR__ .'/LearnerToRegister.php',
        'LearnerToUpdate' => __DIR__ .'/LearnerToUpdate.php',
        'BatchLearner' => __DIR__ .'/BatchLearner.php',
        'OutputBatchLearner' => __DIR__ .'/OutputBatchLearner.php',
        'MIAPBatchLearnerToVerify' => __DIR__ .'/MIAPBatchLearnerToVerify.php',
        'MIAPVerifiedBatchLearner' => __DIR__ .'/MIAPVerifiedBatchLearner.php',
        'MIAPRetrievedULN' => __DIR__ .'/MIAPRetrievedULN.php',
        'MIAPLearnerToVerify' => __DIR__ .'/MIAPLearnerToVerify.php',
        'MIAPVerifiedLearner' => __DIR__ .'/MIAPVerifiedLearner.php',
        'MIAPAPIException' => __DIR__ .'/MIAPAPIException.php',
        'RegisterSingleLearnerRqst' => __DIR__ .'/RegisterSingleLearnerRqst.php',
        'BaseLearnerServiceRqst' => __DIR__ .'/BaseLearnerServiceRqst.php',
        'RegisterSingleLearnerResp' => __DIR__ .'/RegisterSingleLearnerResp.php',
        'UpdateLearnerRqst' => __DIR__ .'/UpdateLearnerRqst.php',
        'UpdateLearnerResp' => __DIR__ .'/UpdateLearnerResp.php',
        'BatchRegistrationRqst' => __DIR__ .'/BatchRegistrationRqst.php',
        'BatchRegistrationResp' => __DIR__ .'/BatchRegistrationResp.php',
        'BatchOutputRqst' => __DIR__ .'/BatchOutputRqst.php',
        'BatchOutputResp' => __DIR__ .'/BatchOutputResp.php',
        'VerifyBatchRqst' => __DIR__ .'/VerifyBatchRqst.php',
        'VerifyBatchResp' => __DIR__ .'/VerifyBatchResp.php',
        'VerifyBatchOutputRqst' => __DIR__ .'/VerifyBatchOutputRqst.php',
        'VerifyBatchOutputResp' => __DIR__ .'/VerifyBatchOutputResp.php',
        'RetrieveULNsRqst' => __DIR__ .'/RetrieveULNsRqst.php',
        'RetrieveULNsResp' => __DIR__ .'/RetrieveULNsResp.php',
        'ArrayOfMIAPRetrievedULN' => __DIR__ .'/ArrayOfMIAPRetrievedULN.php',
        'VerifyLearnerRqst' => __DIR__ .'/VerifyLearnerRqst.php',
        'VerifyLearnerResp' => __DIR__ .'/VerifyLearnerResp.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_bdc8d08b3b8d0781684f5f6fec191e14');

// Do nothing. The rest is just leftovers from the code generation.
{
}
