<?php
/**
 * File for class LogicMelonServiceAdd
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonServiceAdd originally named Add
 * @package LogicMelon
 * @subpackage Services
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonServiceAdd extends LogicMelonWsdlClass
{
    /**
     * Method to call the operation originally named AddAdvert
     * Documentation : <a name='AddAdvert'></a><p>Create an advert on the system and return a redirect url for the user to complete the posting process.</p><ul><li><strong>eOnDuplicate</strong> What action to perform if this advert is already found under this user (duplicate/ignore/update/error) (create a duplicate / ignore and return the access url / update the advert with these values / throw an exception about a duplicate)</li><li><strong>sJobTitle</strong> The title of the job</li><li><strong>sJobType</strong> The job type (P)ermanent, (C)ontract, (T)emporary</li><li><strong>sJobHours</strong> The job hours (F)ull time, (P)art time</li><li><strong>sPrimaryLocation</strong> The primary location</li><li><strong>sIndustry</strong> The specific industry</li><li><strong>sSalaryFrom</strong> The minimum salary. Must be numeric.</li><li><strong>sSalaryTo</strong> The maximum salary. Must be numeric.</li><li><strong>sSalaryCurrency</strong> The salary currency in 3 letter ISO e.g. GBP, EUR, USD</li><li><strong>sSalaryPer</strong> The salary period (H)our, (D)ay, (W)eek, (M)onth, (Y)ear</li><li><strong>sSalaryBenefits</strong> and salary benefits</li><li><strong>sContactName</strong> Optionally a specific contact name (default to use the user the vacancy is stored under)</li><li><strong>sContactEmail</strong> Optionally a specific contact email (default to use the user the vacancy is stored under)</li><li><strong>sJobDescription</strong> The job description in plain text or HTML</li><li><strong>sApplicationURL</strong> Optionally an application URL to pass to the different media. Contact support for details.</li><li><strong>Destinations</strong> A list of job board destinations as either numeric id's or string identifiers</li><li><strong>sFuturePostDateTimeInUtc</strong> Future date for advert and posting data. Must parse to a date using </li><li><strong>sRedirectDomain</strong> The domain to redirect the user to on completion. Defaults to a system configured domain.</li><li><strong>AdvertValues</strong> Extra values keyed on numeric field id or field string identifier and the associated value</li><li><strong>sAdvertStatusID</strong> Adverts by default are marked as Created (0). The may also be marked as ToAuthorise (16) or Deleted (1)</li></ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructAddAdvert $_logicMelonStructAddAdvert
     * @return LogicMelonStructAddAdvertResponse
     */
    public function AddAdvert(LogicMelonStructAddAdvert $_logicMelonStructAddAdvert)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->AddAdvert($_logicMelonStructAddAdvert));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named AddAdvertWithValues
     * Documentation : <a name='AddAdvertWithValues'></a><p>Create an advert on the system and return a redirect url for the user to complete the posting process.</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructAddAdvertWithValues $_logicMelonStructAddAdvertWithValues
     * @return LogicMelonStructAddAdvertWithValuesResponse
     */
    public function AddAdvertWithValues(LogicMelonStructAddAdvertWithValues $_logicMelonStructAddAdvertWithValues)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->AddAdvertWithValues($_logicMelonStructAddAdvertWithValues));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named AddAdvertValues
     * Documentation : <a name='AddAdvertValues'></a><p>Update field values against a current advert.</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructAddAdvertValues $_logicMelonStructAddAdvertValues
     * @return LogicMelonStructAddAdvertValuesResponse
     */
    public function AddAdvertValues(LogicMelonStructAddAdvertValues $_logicMelonStructAddAdvertValues)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->AddAdvertValues($_logicMelonStructAddAdvertValues));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named AddAdvertCandidate
     * Documentation : <a name='AddAdvertCandidate'></a><p>Attaches a candidate to the application pipeline using their email address, information to locate and advert, and a specific feed identifier or feed id.</p><p>Optionally specify a username to limit the search</p><ul><li><strong>sFeedID</strong> If known our internal database key for the source. e.g. 108<li><strong>sFeedIdentifier</strong> If known our internal string identifier for the source. e.g Totaljobs<li><strong>sCandidateEmail</strong> The candidates email address (required)<li><strong>sCandidateFirstName</strong> The candidates first name (optional)<li><strong>sCandidateLastName</strong> The candidates last name (optional)<li><strong>sCandidateHomePhone</strong> The candidates home phone (optional)<li><strong>sCandidateWorkPhone</strong> The candidates work phone (optional)<li><strong>sCandidateMobilePhone</strong> The candidates mobile phone (optional)<li><strong>Filters</strong> Extra searching Filters for the advert (contact support for details)<li><strong>CandidateDetail</strong> Extra candidate details (contact support for details)</ul>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructAddAdvertCandidate $_logicMelonStructAddAdvertCandidate
     * @return LogicMelonStructAddAdvertCandidateResponse
     */
    public function AddAdvertCandidate(LogicMelonStructAddAdvertCandidate $_logicMelonStructAddAdvertCandidate)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->AddAdvertCandidate($_logicMelonStructAddAdvertCandidate));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Method to call the operation originally named AddAdvertCandidateWithFilters
     * Documentation : <a name='AddAdvertCandidateWithFilters'></a><p>Attach a candidate to the application pipeline using their email address, information to locate an advert, and a specific feed identifier or feed id are required.</p><p>Optionally specify a username to limit the search</p>
     * @uses LogicMelonWsdlClass::getSoapClient()
     * @uses LogicMelonWsdlClass::setResult()
     * @uses LogicMelonWsdlClass::saveLastError()
     * @param LogicMelonStructAddAdvertCandidateWithFilters $_logicMelonStructAddAdvertCandidateWithFilters
     * @return LogicMelonStructAddAdvertCandidateWithFiltersResponse
     */
    public function AddAdvertCandidateWithFilters(LogicMelonStructAddAdvertCandidateWithFilters $_logicMelonStructAddAdvertCandidateWithFilters)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->AddAdvertCandidateWithFilters($_logicMelonStructAddAdvertCandidateWithFilters));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see LogicMelonWsdlClass::getResult()
     * @return LogicMelonStructAddAdvertCandidateResponse|LogicMelonStructAddAdvertCandidateWithFiltersResponse|LogicMelonStructAddAdvertResponse|LogicMelonStructAddAdvertValuesResponse|LogicMelonStructAddAdvertWithValuesResponse
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
