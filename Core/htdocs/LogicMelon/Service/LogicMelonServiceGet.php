<?php
/**
 * File for class LogicMelonServiceGet
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceGet originally named Get
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceGet extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named GetApplicationsPaged
     * Documentation : <a name='GetApplicationsPaged'></a><p>Paged version of GetApplications. Provides a mechanism to search for applications by job, job board or time on the database and return some basic information along with the ability to include source documents and parsed data.</p><ul><li>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</li><li>At a minimum 1 search filter must be specified to find (1) either a job or (2) an application time period.</li><li><strong>Destinations</strong> are either numeric (FeedID) or string (FeedIdentifier)</li><li><strong>ApplicationDateTime</strong> A variety of date formats are supported but the safest may be YYYY-MM-DD hh:mm:ss.</li><li><strong>Ranking</strong> can be string or numeric Unranked (0), Unsuitable (1), MaybeSuitable (2), Suitable (4)</li><li><strong>ProgressID</strong> must be numeric and values can be requested from support or determined from the main recruiter interface</li> <li>Default <strong>SortOrder</strong>: ScorePreSort asc, Score desc, ScoreExtra desc, Favourite desc, ApplicationDate desc, AdvertCandidateID desc</li><li><strong>CurrentPage</strong> and <strong>RowsPerPage</strong> must always be supplied</li><li><strong>IncludeParsed</strong> You can choose to include parsed data (if you have parsing activated on your account) which is generally in an HRXML format</li><li><strong>IncludeEmailBody, IncludeAttachment, IncludeEmail</strong> You can choose to include parts of the actual application email. From the full email (IncludeEmail) to just the body content (IncludeEmailBody) and the probable most appropriate attachment (IncludeAttachment).</li><li>Extra documents are either returned in the string Document property (email body) or the byte array (byte[]) DocumentBytes (email, parsed, attachment) as appropriate.</li><li><strong>AdvertCandidateIDAsCSV</strong> You may want to select which applications to import and then re-request the data using <strong>AdvertCandidateIDAsCSV</strong> to retrieve the exact parsed data, attachments or emails for the specific applications you need.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetApplicationsPaged $_logicMelonStructGetApplicationsPaged
     * @return LogicMelonStructGetApplicationsPagedResponse
     */
    public function GetApplicationsPaged(LogicMelonStructGetApplicationsPaged $_logicMelonStructGetApplicationsPaged)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetApplicationsPaged($_logicMelonStructGetApplicationsPaged));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetApplications
     * Documentation : <a name='GetApplications'></a><p>Provides a mechanism to search for applications by job, job board or time on the database and return some basic information along with the ability to include source documents and parsed data.</p><ul><li>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</li><li>At a minimum 1 search filter must be specified to find (1) either a job or (2) an application time period.</li><li><strong>Destinations</strong> are either numeric (FeedID) or string (FeedIdentifier)</li><li><strong>ApplicationDateTime</strong> A variety of date formats are supported but the safest may be YYYY-MM-DD hh:mm:ss.</li><li><strong>Ranking</strong> can be string or numeric Unranked (0), Unsuitable (1), MaybeSuitable (2), Suitable (4)</li><li><strong>ProgressID</strong> must be numeric and values can be requested from support or determined from the main recruiter interface</li> <li>Default <strong>SortOrder</strong>: ApplicationDate asc, AdvertCandidateID asc</li><li><strong>IncludeParsed</strong> You can choose to include parsed data (if you have parsing activated on your account) which is generally in an HRXML format</li><li><strong>IncludeEmailBody, IncludeAttachment, IncludeEmail</strong> You can choose to include parts of the actual application email. From the full email (IncludeEmail) to just the body content (IncludeEmailBody) and the probable most appropriate attachment (IncludeAttachment).</li><li>Extra documents are either returned in the string Document property (email body) or the byte array (byte[]) DocumentBytes (email, parsed, attachment) as appropriate.</li><li><strong>AdvertCandidateIDAsCSV</strong> You may want to select which applications to import and then re-request the data using <strong>AdvertCandidateIDAsCSV</strong> to retrieve the exact parsed data, attachments or emails for the specific applications you need.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetApplications $_logicMelonStructGetApplications
     * @return LogicMelonStructGetApplicationsResponse
     */
    public function GetApplications(LogicMelonStructGetApplications $_logicMelonStructGetApplications)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetApplications($_logicMelonStructGetApplications));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetApplicationsWithFiltersPaged
     * Documentation : <a name='GetApplicationsWithFiltersPaged'></a><p>Paged version of GetApplications. Provides a mechanism to search for applications by job, job board or time on the database and return some basic information along with the ability to include source documents and parsed data.</p><ul><li>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</li><li>At a minimum 1 search filter must be specified to find (1) either a job or (2) an application time period.</li><li><strong>Destinations</strong> are either numeric (FeedID) or string (FeedIdentifier)</li><li><strong>ApplicationDateTime</strong> A variety of date formats are supported but the safest may be YYYY-MM-DD hh:mm:ss.</li><li><strong>Ranking</strong> can be string or numeric Unranked (0), Unsuitable (1), MaybeSuitable (2), Suitable (4)</li><li><strong>ProgressID</strong> must be numeric and values can be requested from support or determined from the main recruiter interface</li> <li>Default <strong>SortOrder</strong>: ScorePreSort asc, Score desc, ScoreExtra desc, Favourite desc, ApplicationDate desc, AdvertCandidateID desc</li><li><strong>CurrentPage</strong> and <strong>RowsPerPage</strong> must always be supplied</li><li><strong>IncludeParsed</strong> You can choose to include parsed data (if you have parsing activated on your account) which is generally in an HRXML format</li><li><strong>IncludeEmailBody, IncludeAttachment, IncludeEmail</strong> You can choose to include parts of the actual application email. From the full email (IncludeEmail) to just the body content (IncludeEmailBody) and the probable most appropriate attachment (IncludeAttachment).</li><li>Extra documents are either returned in the string Document property (email body) or the byte array (byte[]) DocumentBytes (email, parsed, attachment) as appropriate.</li><li><strong>AdvertCandidateIDAsCSV</strong> You may want to select which applications to import and then re-request the data using <strong>AdvertCandidateIDAsCSV</strong> to retrieve the exact parsed data, attachments or emails for the specific applications you need.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetApplicationsWithFiltersPaged $_logicMelonStructGetApplicationsWithFiltersPaged
     * @return LogicMelonStructGetApplicationsWithFiltersPagedResponse
     */
    public function GetApplicationsWithFiltersPaged(LogicMelonStructGetApplicationsWithFiltersPaged $_logicMelonStructGetApplicationsWithFiltersPaged)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetApplicationsWithFiltersPaged($_logicMelonStructGetApplicationsWithFiltersPaged));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetApplicationsWithFilters
     * Documentation : <a name='GetApplicationsWithFilters'></a><p>Provides a mechanism to search for applications by job, job board or time on the database and return some basic information along with the ability to include source documents and parsed data.</p><ul><li>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</li><li>At a minimum 1 search filter must be specified to find (1) either a job or (2) an application time period.</li><li><strong>Destinations</strong> are either numeric (FeedID) or string (FeedIdentifier)</li><li><strong>ApplicationDateTime</strong> A variety of date formats are supported but the safest may be YYYY-MM-DD hh:mm:ss.</li><li><strong>Ranking</strong> can be string or numeric Unranked (0), Unsuitable (1), MaybeSuitable (2), Suitable (4)</li><li><strong>ProgressID</strong> must be numeric and values can be requested from support or determined from the main recruiter interface</li> <li>Default <strong>SortOrder</strong>: ApplicationDate asc, AdvertCandidateID asc</li><li><strong>IncludeParsed</strong> You can choose to include parsed data (if you have parsing activated on your account) which is generally in an HRXML format</li><li><strong>IncludeEmailBody, IncludeAttachment, IncludeEmail</strong> You can choose to include parts of the actual application email. From the full email (IncludeEmail) to just the body content (IncludeEmailBody) and the probable most appropriate attachment (IncludeAttachment).</li><li>Extra documents are either returned in the string Document property (email body) or the byte array (byte[]) DocumentBytes (email, parsed, attachment) as appropriate.</li><li><strong>AdvertCandidateIDAsCSV</strong> You may want to select which applications to import and then re-request the data using <strong>AdvertCandidateIDAsCSV</strong> to retrieve the exact parsed data, attachments or emails for the specific applications you need.</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetApplicationsWithFilters $_logicMelonStructGetApplicationsWithFilters
     * @return LogicMelonStructGetApplicationsWithFiltersResponse
     */
    public function GetApplicationsWithFilters(LogicMelonStructGetApplicationsWithFilters $_logicMelonStructGetApplicationsWithFilters)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetApplicationsWithFilters($_logicMelonStructGetApplicationsWithFilters));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetAdvertPaged
     * Documentation : <a name='GetAdvertPaged'></a><p>Paged version of GetAdvert. Provides a mechanism to search for a specific advert (with possibly multiple instances) on the database and return some basic information.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetAdvertPaged $_logicMelonStructGetAdvertPaged
     * @return LogicMelonStructGetAdvertPagedResponse
     */
    public function GetAdvertPaged(LogicMelonStructGetAdvertPaged $_logicMelonStructGetAdvertPaged)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetAdvertPaged($_logicMelonStructGetAdvertPaged));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetAdvert
     * Documentation : <a name='GetAdvert'></a><p>Provides a mechanism to search for a specific advert (with possibly multiple instances) on the database and return some basic information.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetAdvert $_logicMelonStructGetAdvert
     * @return LogicMelonStructGetAdvertResponse
     */
    public function GetAdvert(LogicMelonStructGetAdvert $_logicMelonStructGetAdvert)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetAdvert($_logicMelonStructGetAdvert));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetAdvertWithFiltersPaged
     * Documentation : <a name='GetAdvertWithFiltersPaged'></a><p>Paged version of GetAdvert. Provides a mechanism to search for a specific advert (with possibly multiple instances) on the database and return some basic information.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetAdvertWithFiltersPaged $_logicMelonStructGetAdvertWithFiltersPaged
     * @return LogicMelonStructGetAdvertWithFiltersPagedResponse
     */
    public function GetAdvertWithFiltersPaged(LogicMelonStructGetAdvertWithFiltersPaged $_logicMelonStructGetAdvertWithFiltersPaged)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetAdvertWithFiltersPaged($_logicMelonStructGetAdvertWithFiltersPaged));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetAdvertWithFilters
     * Documentation : <a name='GetAdvertWithFilters'></a><p>Provides a mechanism to search for a specific advert (with possibly multiple instances) on the database and return some basic information.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetAdvertWithFilters $_logicMelonStructGetAdvertWithFilters
     * @return LogicMelonStructGetAdvertWithFiltersResponse
     */
    public function GetAdvertWithFilters(LogicMelonStructGetAdvertWithFilters $_logicMelonStructGetAdvertWithFilters)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetAdvertWithFilters($_logicMelonStructGetAdvertWithFilters));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetAdvertWithValues
     * Documentation : <a name='GetAdvertWithValues'></a><p>Provides a mechanism to search for a specific advert (with possibly multiple instances) on the database and return some basic information with extra field values included. The extra field values can be filtered by a list of Field identifiers or for Feed specific values by Feed identifiers.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetAdvertWithValues $_logicMelonStructGetAdvertWithValues
     * @return LogicMelonStructGetAdvertWithValuesResponse
     */
    public function GetAdvertWithValues(LogicMelonStructGetAdvertWithValues $_logicMelonStructGetAdvertWithValues)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetAdvertWithValues($_logicMelonStructGetAdvertWithValues));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetAdvertWithValuesPaged
     * Documentation : <a name='GetAdvertWithValuesPaged'></a><p>Paged version of GetAdvert. Provides a mechanism to search for a specific advert (with possibly multiple instances) on the database and return some basic information with extra field values included. The extra field values can be filtered by a list of Field identifiers or for Feed specific values by Feed identifiers.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetAdvertWithValuesPaged $_logicMelonStructGetAdvertWithValuesPaged
     * @return LogicMelonStructGetAdvertWithValuesPagedResponse
     */
    public function GetAdvertWithValuesPaged(LogicMelonStructGetAdvertWithValuesPaged $_logicMelonStructGetAdvertWithValuesPaged)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetAdvertWithValuesPaged($_logicMelonStructGetAdvertWithValuesPaged));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetAdvertWithValuesWithFilters
     * Documentation : <a name='GetAdvertWithValuesWithFilters'></a><p>Provides a mechanism to search for a specific advert (with possibly multiple instances) on the database and return some basic information with extra field values included. The extra field values can be filtered by a list of Field identifiers or for Feed specific values by Feed identifiers.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetAdvertWithValuesWithFilters $_logicMelonStructGetAdvertWithValuesWithFilters
     * @return LogicMelonStructGetAdvertWithValuesWithFiltersResponse
     */
    public function GetAdvertWithValuesWithFilters(LogicMelonStructGetAdvertWithValuesWithFilters $_logicMelonStructGetAdvertWithValuesWithFilters)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetAdvertWithValuesWithFilters($_logicMelonStructGetAdvertWithValuesWithFilters));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetAdvertWithValuesWithFiltersPaged
     * Documentation : <a name='GetAdvertWithValuesWithFiltersPaged'></a><p>Paged version of GetAdvert. Provides a mechanism to search for a specific advert (with possibly multiple instances) on the database and return some basic information with extra field values included. The extra field values can be filtered by a list of Field identifiers or for Feed specific values by Feed identifiers.</p><p>Optionally specify a sUsername or sStartOrganisation to limit the search to a specific user (sUsername or sUserIdentifier) or specific portion of the tree (sStartOrganisation).</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetAdvertWithValuesWithFiltersPaged $_logicMelonStructGetAdvertWithValuesWithFiltersPaged
     * @return LogicMelonStructGetAdvertWithValuesWithFiltersPagedResponse
     */
    public function GetAdvertWithValuesWithFiltersPaged(LogicMelonStructGetAdvertWithValuesWithFiltersPaged $_logicMelonStructGetAdvertWithValuesWithFiltersPaged)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetAdvertWithValuesWithFiltersPaged($_logicMelonStructGetAdvertWithValuesWithFiltersPaged));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetValues
     * Documentation : <a name='GetValues'></a><p>Retrieve lookup data for a specific drop down or radio button or check box</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetValues $_logicMelonStructGetValues
     * @return LogicMelonStructGetValuesResponse
     */
    public function GetValues(LogicMelonStructGetValues $_logicMelonStructGetValues)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetValues($_logicMelonStructGetValues));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named GetCurrency
     * Documentation : <a name='GetCurrency'></a><p>Retrieve lookup data for a specific drop down or radio button or check box</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructGetCurrency $_logicMelonStructGetCurrency
     * @return LogicMelonStructGetCurrencyResponse
     */
    public function GetCurrency(LogicMelonStructGetCurrency $_logicMelonStructGetCurrency)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->GetCurrency($_logicMelonStructGetCurrency));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructGetAdvertPagedResponse|LogicMelonStructGetAdvertResponse|LogicMelonStructGetAdvertWithFiltersPagedResponse|LogicMelonStructGetAdvertWithFiltersResponse|LogicMelonStructGetAdvertWithValuesPagedResponse|LogicMelonStructGetAdvertWithValuesResponse|LogicMelonStructGetAdvertWithValuesWithFiltersPagedResponse|LogicMelonStructGetAdvertWithValuesWithFiltersResponse|LogicMelonStructGetApplicationsPagedResponse|LogicMelonStructGetApplicationsResponse|LogicMelonStructGetApplicationsWithFiltersPagedResponse|LogicMelonStructGetApplicationsWithFiltersResponse|LogicMelonStructGetCurrencyResponse|LogicMelonStructGetValuesResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
    /**
     * Method returning the class name
     * @return string __CLASS__
     */
    public function __toString()
    {
        return __CLASS__;
    }
}
