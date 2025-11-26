<?php
/**
 * File for the class which returns the class map definition
 * @package LogicMelon
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * Class which returns the class map definition by the static method LogicMelonClassMap::classMap()
 * @package LogicMelon
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonClassMap
{
    /**
     * This method returns the array containing the mapping between WSDL structs and generated classes
     * This array is sent to the SoapClient when calling the WS
     * @return array
     */
    final public static function classMap()
    {
        return array (
  'APIAdvert' => 'LogicMelonStructAPIAdvert',
  'APIAdvertPaged' => 'LogicMelonStructAPIAdvertPaged',
  'APIAdvertValue' => 'LogicMelonStructAPIAdvertValue',
  'APIAdvertWithPostings' => 'LogicMelonStructAPIAdvertWithPostings',
  'APIAdvertWithValues' => 'LogicMelonStructAPIAdvertWithValues',
  'APIAdvertWithValuesPaged' => 'LogicMelonStructAPIAdvertWithValuesPaged',
  'APIApplication' => 'LogicMelonStructAPIApplication',
  'APIApplicationAttachment' => 'LogicMelonStructAPIApplicationAttachment',
  'APIApplicationPaged' => 'LogicMelonStructAPIApplicationPaged',
  'APIPosting' => 'LogicMelonStructAPIPosting',
  'AddAdvert' => 'LogicMelonStructAddAdvert',
  'AddAdvertCandidate' => 'LogicMelonStructAddAdvertCandidate',
  'AddAdvertCandidateResponse' => 'LogicMelonStructAddAdvertCandidateResponse',
  'AddAdvertCandidateWithFilters' => 'LogicMelonStructAddAdvertCandidateWithFilters',
  'AddAdvertCandidateWithFiltersResponse' => 'LogicMelonStructAddAdvertCandidateWithFiltersResponse',
  'AddAdvertResponse' => 'LogicMelonStructAddAdvertResponse',
  'AddAdvertResult' => 'LogicMelonStructAddAdvertResult',
  'AddAdvertValues' => 'LogicMelonStructAddAdvertValues',
  'AddAdvertValuesResponse' => 'LogicMelonStructAddAdvertValuesResponse',
  'AddAdvertWithValues' => 'LogicMelonStructAddAdvertWithValues',
  'AddAdvertWithValuesResponse' => 'LogicMelonStructAddAdvertWithValuesResponse',
  'ArchiveAdvert' => 'LogicMelonStructArchiveAdvert',
  'ArchiveAdvertResponse' => 'LogicMelonStructArchiveAdvertResponse',
  'ArchiveAdvertWithFilters' => 'LogicMelonStructArchiveAdvertWithFilters',
  'ArchiveAdvertWithFiltersResponse' => 'LogicMelonStructArchiveAdvertWithFiltersResponse',
  'ArrayOfAPIAdvert' => 'LogicMelonStructArrayOfAPIAdvert',
  'ArrayOfAPIAdvertValue' => 'LogicMelonStructArrayOfAPIAdvertValue',
  'ArrayOfAPIAdvertWithPostings' => 'LogicMelonStructArrayOfAPIAdvertWithPostings',
  'ArrayOfAPIAdvertWithValues' => 'LogicMelonStructArrayOfAPIAdvertWithValues',
  'ArrayOfAPIApplication' => 'LogicMelonStructArrayOfAPIApplication',
  'ArrayOfAPIPosting' => 'LogicMelonStructArrayOfAPIPosting',
  'ArrayOfCPostFeed' => 'LogicMelonStructArrayOfCPostFeed',
  'ArrayOfCQueryJobTitle' => 'LogicMelonStructArrayOfCQueryJobTitle',
  'ArrayOfCQueryLocation' => 'LogicMelonStructArrayOfCQueryLocation',
  'ArrayOfGetValue' => 'LogicMelonStructArrayOfGetValue',
  'ArrayOfInt' => 'LogicMelonStructArrayOfInt',
  'ArrayOfNameValue' => 'LogicMelonStructArrayOfNameValue',
  'ArrayOfString' => 'LogicMelonStructArrayOfString',
  'ArrayOfValidateFieldResult' => 'LogicMelonStructArrayOfValidateFieldResult',
  'CPostFeed' => 'LogicMelonStructCPostFeed',
  'CQueryJobTitle' => 'LogicMelonStructCQueryJobTitle',
  'CQueryLocation' => 'LogicMelonStructCQueryLocation',
  'CloseAdvert' => 'LogicMelonStructCloseAdvert',
  'CloseAdvertResponse' => 'LogicMelonStructCloseAdvertResponse',
  'CloseAdvertWithFilters' => 'LogicMelonStructCloseAdvertWithFilters',
  'CloseAdvertWithFiltersResponse' => 'LogicMelonStructCloseAdvertWithFiltersResponse',
  'DeliverAdvert' => 'LogicMelonStructDeliverAdvert',
  'DeliverAdvertResponse' => 'LogicMelonStructDeliverAdvertResponse',
  'ExpressPostAdvert' => 'LogicMelonStructExpressPostAdvert',
  'ExpressPostAdvertResponse' => 'LogicMelonStructExpressPostAdvertResponse',
  'ExpressPostAdvertWithFilters' => 'LogicMelonStructExpressPostAdvertWithFilters',
  'ExpressPostAdvertWithFiltersResponse' => 'LogicMelonStructExpressPostAdvertWithFiltersResponse',
  'GetAdvert' => 'LogicMelonStructGetAdvert',
  'GetAdvertPaged' => 'LogicMelonStructGetAdvertPaged',
  'GetAdvertPagedResponse' => 'LogicMelonStructGetAdvertPagedResponse',
  'GetAdvertResponse' => 'LogicMelonStructGetAdvertResponse',
  'GetAdvertWithFilters' => 'LogicMelonStructGetAdvertWithFilters',
  'GetAdvertWithFiltersPaged' => 'LogicMelonStructGetAdvertWithFiltersPaged',
  'GetAdvertWithFiltersPagedResponse' => 'LogicMelonStructGetAdvertWithFiltersPagedResponse',
  'GetAdvertWithFiltersResponse' => 'LogicMelonStructGetAdvertWithFiltersResponse',
  'GetAdvertWithValues' => 'LogicMelonStructGetAdvertWithValues',
  'GetAdvertWithValuesPaged' => 'LogicMelonStructGetAdvertWithValuesPaged',
  'GetAdvertWithValuesPagedResponse' => 'LogicMelonStructGetAdvertWithValuesPagedResponse',
  'GetAdvertWithValuesResponse' => 'LogicMelonStructGetAdvertWithValuesResponse',
  'GetAdvertWithValuesWithFilters' => 'LogicMelonStructGetAdvertWithValuesWithFilters',
  'GetAdvertWithValuesWithFiltersPaged' => 'LogicMelonStructGetAdvertWithValuesWithFiltersPaged',
  'GetAdvertWithValuesWithFiltersPagedResponse' => 'LogicMelonStructGetAdvertWithValuesWithFiltersPagedResponse',
  'GetAdvertWithValuesWithFiltersResponse' => 'LogicMelonStructGetAdvertWithValuesWithFiltersResponse',
  'GetApplications' => 'LogicMelonStructGetApplications',
  'GetApplicationsPaged' => 'LogicMelonStructGetApplicationsPaged',
  'GetApplicationsPagedResponse' => 'LogicMelonStructGetApplicationsPagedResponse',
  'GetApplicationsResponse' => 'LogicMelonStructGetApplicationsResponse',
  'GetApplicationsWithFilters' => 'LogicMelonStructGetApplicationsWithFilters',
  'GetApplicationsWithFiltersPaged' => 'LogicMelonStructGetApplicationsWithFiltersPaged',
  'GetApplicationsWithFiltersPagedResponse' => 'LogicMelonStructGetApplicationsWithFiltersPagedResponse',
  'GetApplicationsWithFiltersResponse' => 'LogicMelonStructGetApplicationsWithFiltersResponse',
  'GetCurrency' => 'LogicMelonStructGetCurrency',
  'GetCurrencyResponse' => 'LogicMelonStructGetCurrencyResponse',
  'GetValue' => 'LogicMelonStructGetValue',
  'GetValues' => 'LogicMelonStructGetValues',
  'GetValuesResponse' => 'LogicMelonStructGetValuesResponse',
  'NameValue' => 'LogicMelonStructNameValue',
  'QueryJobTitle' => 'LogicMelonStructQueryJobTitle',
  'QueryJobTitleResponse' => 'LogicMelonStructQueryJobTitleResponse',
  'QueryLocations' => 'LogicMelonStructQueryLocations',
  'QueryLocationsResponse' => 'LogicMelonStructQueryLocationsResponse',
  'TrackAdvert' => 'LogicMelonStructTrackAdvert',
  'TrackAdvertResponse' => 'LogicMelonStructTrackAdvertResponse',
  'TrackAdvertWithFilters' => 'LogicMelonStructTrackAdvertWithFilters',
  'TrackAdvertWithFiltersResponse' => 'LogicMelonStructTrackAdvertWithFiltersResponse',
  'UnarchiveAdvert' => 'LogicMelonStructUnarchiveAdvert',
  'UnarchiveAdvertResponse' => 'LogicMelonStructUnarchiveAdvertResponse',
  'UnarchiveAdvertWithFilters' => 'LogicMelonStructUnarchiveAdvertWithFilters',
  'UnarchiveAdvertWithFiltersResponse' => 'LogicMelonStructUnarchiveAdvertWithFiltersResponse',
  'UserFeedsAndQuota' => 'LogicMelonStructUserFeedsAndQuota',
  'UserFeedsAndQuotaResponse' => 'LogicMelonStructUserFeedsAndQuotaResponse',
  'UserFeedsAndQuotaWithDestinations' => 'LogicMelonStructUserFeedsAndQuotaWithDestinations',
  'UserFeedsAndQuotaWithDestinationsResponse' => 'LogicMelonStructUserFeedsAndQuotaWithDestinationsResponse',
  'ValidateAdvertResult' => 'LogicMelonStructValidateAdvertResult',
  'ValidateAdvertValid' => 'LogicMelonEnumValidateAdvertValid',
  'ValidateAdvertValues' => 'LogicMelonStructValidateAdvertValues',
  'ValidateAdvertValuesResponse' => 'LogicMelonStructValidateAdvertValuesResponse',
  'ValidateFieldResult' => 'LogicMelonStructValidateFieldResult',
  'mpats_PostJob_TrackResult' => 'LogicMelonStructMpats_PostJob_TrackResult',
);
    }
}
