<?php


 function autoload_bf72edd1e142c90f1a56b21596697a49($class)
{
    $classes = array(
        'LearnerServiceR9Proxy' => __DIR__ .'/LearnerServiceR9Proxy.php',
        'LearnerByUln' => __DIR__ .'/LearnerByUln.php',
        'LearnerByUlnResponse' => __DIR__ .'/LearnerByUlnResponse.php',
        'GetLearnerLearningEvents' => __DIR__ .'/GetLearnerLearningEvents.php',
        'GetLearnerLearningEventsResponse' => __DIR__ .'/GetLearnerLearningEventsResponse.php',
        'UpdateLearnerSubsetFields' => __DIR__ .'/UpdateLearnerSubsetFields.php',
        'UpdateLearnerSubsetFieldsResponse' => __DIR__ .'/UpdateLearnerSubsetFieldsResponse.php',
        'ViewAudit' => __DIR__ .'/ViewAudit.php',
        'ViewAuditResponse' => __DIR__ .'/ViewAuditResponse.php',
        'GetOrganisation' => __DIR__ .'/GetOrganisation.php',
        'GetOrganisationResponse' => __DIR__ .'/GetOrganisationResponse.php',
        'InvokingOrganisation' => __DIR__ .'/InvokingOrganisation.php',
        'ChannelCode' => __DIR__ .'/ChannelCode.php',
        'ServiceResponseR9' => __DIR__ .'/ServiceResponseR9.php',
        'ArrayOfLearningEvent' => __DIR__ .'/ArrayOfLearningEvent.php',
        'LearningEvent' => __DIR__ .'/LearningEvent.php',
        'AbilityToShare' => __DIR__ .'/AbilityToShare.php',
        'PagedListOfPlrAccessEntryResponseN4dIIFC_S' => __DIR__ .'/PagedListOfPlrAccessEntryResponseN4dIIFC_S.php',
        'ArrayOfPlrAccessEntryResponse' => __DIR__ .'/ArrayOfPlrAccessEntryResponse.php',
        'PlrAccessEntryResponse' => __DIR__ .'/PlrAccessEntryResponse.php',
        'UserType' => __DIR__ .'/UserType.php',
        'Learner' => __DIR__ .'/Learner.php',
        'BusinessObject' => __DIR__ .'/BusinessObject.php',
        'ArrayOfstring' => __DIR__ .'/ArrayOfstring.php',
        'DomainFault' => __DIR__ .'/DomainFault.php',
        'InvokingOrganisationR10' => __DIR__ .'/InvokingOrganisationR10.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_bf72edd1e142c90f1a56b21596697a49');

// Do nothing. The rest is just leftovers from the code generation.
{
}
