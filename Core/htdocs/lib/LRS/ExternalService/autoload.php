<?php


 function autoload_49da63ef0634a9329f57a5d83151e542($class)
{
    $classes = array(
        'ExternalServiceProxy' => __DIR__ .'/ExternalServiceProxy.php',
        'SubmitAchievementBatchJob' => __DIR__ .'/SubmitAchievementBatchJob.php',
        'ArrayOfAchievement' => __DIR__ .'/ArrayOfAchievement.php',
        'Achievement' => __DIR__ .'/Achievement.php',
        'SubmitAchievementBatchJobResponse' => __DIR__ .'/SubmitAchievementBatchJobResponse.php',
        'GetAchievementBatchJob' => __DIR__ .'/GetAchievementBatchJob.php',
        'GetAchievementBatchJobResponse' => __DIR__ .'/GetAchievementBatchJobResponse.php',
        'User' => __DIR__ .'/User.php',
        'BusinessObject' => __DIR__ .'/BusinessObject.php',
        'ServiceResponseR9' => __DIR__ .'/ServiceResponseR9.php',
        'AchievementBatchJobResponse' => __DIR__ .'/AchievementBatchJobResponse.php',
        'AchievementBatchJobStatus' => __DIR__ .'/AchievementBatchJobStatus.php',
        'DomainFault' => __DIR__ .'/DomainFault.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_49da63ef0634a9329f57a5d83151e542');

// Do nothing. The rest is just leftovers from the code generation.
{
}
