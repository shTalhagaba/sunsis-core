<?php
/**
 * File for the class which returns the class map definition
 * @package
 * @date 2016-12-22
 */
/**
 * Class which returns the class map definition by the static method WSDLClassMap::classMap()
 * @package
 * @date 2016-12-22
 */
class WSDLClassMap
{
	/**
	 * This method returns the array containing the mapping between WSDL structs and generated classes
	 * This array is sent to the SoapClient when calling the WS
	 * @return array
	 */
	final public static function classMap()
	{
		return array(
			'AddressData' => 'StructAddressData',
			'ApplicationData' => 'StructApplicationData',
			'ApplicationType' => 'EnumApplicationType',
			'ApprenticeshipData' => 'StructApprenticeshipData',
			'ArrayOfElementErrorData' => 'StructArrayOfElementErrorData',
			'ArrayOfSiteVacancyData' => 'StructArrayOfSiteVacancyData',
			'ArrayOfVacancyUploadData' => 'StructArrayOfVacancyUploadData',
			'ArrayOfVacancyUploadResultData' => 'StructArrayOfVacancyUploadResultData',
			'ElementErrorData' => 'StructElementErrorData',
			'EmployerData' => 'StructEmployerData',
			'SiteVacancyData' => 'StructSiteVacancyData',
			'SystemFaultContract' => 'StructSystemFaultContract',
			'VacancyApprenticeshipType' => 'EnumVacancyApprenticeshipType',
			'VacancyData' => 'StructVacancyData',
			'VacancyLocationType' => 'EnumVacancyLocationType',
			'VacancyUploadData' => 'StructVacancyUploadData',
			'VacancyUploadRequest' => 'StructVacancyUploadRequest',
			'VacancyUploadResponse' => 'StructVacancyUploadResponse',
			'VacancyUploadResult' => 'EnumVacancyUploadResult',
			'VacancyUploadResultData' => 'StructVacancyUploadResultData',
			'WageType' => 'EnumWageType',
		);
	}
}
