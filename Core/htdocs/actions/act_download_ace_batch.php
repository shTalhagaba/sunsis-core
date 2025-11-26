<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Khushnood
 * Date: 20/06/12
 * Time: 14:37
 * To change this template use File | Settings | File Templates.
 */
class download_ace_batch implements IAction
{
	public function execute(PDO $link)
	{

		$notown = Array();
		$sqlxl = "SELECT tr.firstnames, tr.surname, tr.dob, tr.ni, tr.uln, tr.home_address_line_1, tr.home_address_line_2, tr.home_address_line_3, tr.home_address_line_4, tr.home_postcode,
  tr.home_email,
  tr.home_telephone,
  tr.start_date,
  tr.closure_date,
  organisations.legal_name,
  locations.contact_name,
  locations.address_line_1,
  locations.address_line_2,
  locations.address_line_3,
  locations.address_line_4,
  locations.postcode,
  locations.contact_email,
  locations.telephone
FROM
  tr
  LEFT JOIN organisations
    ON organisations.id = tr.employer_id
  LEFT JOIN locations
    ON locations.id = tr.employer_location_id
WHERE tr.status_code!=2 and tr.status_code!= 3 and tr.id IN
  (SELECT DISTINCT
    tr_id
  FROM
    student_qualifications
  WHERE
      certificate_applied = '' or certificate_applied IS NULL
    )";

		$stxl = $link->query($sqlxl);
		if($stxl)
		{
			while($rowxl = $stxl->fetch())
			{
				if($rowxl['home_address_line_3']=='')
					$notown[] = $rowxl['firstnames'] . ' ' . $rowxl['surname'];
			}
		}


		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename="apprentices.csv"');

		// Internet Explorer requires two extra headers when downloading files over HTTPS
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
		{
			header('Pragma: public');
			header('Cache-Control: max-age=0');
		}

		echo "* Forename,* Surname, * Date Of Birth (DD/MM/YYYY), * NI Number, Unique Number, * Apprentice Street, * Apprentice Postcode, * Apprentice Town, * Apprentice Country (UK = 232), Apprentice Email, Apprentice Phone, Apprentice Start Date (DD/MM/YYYY), Apprentice End Date (DD/MM/YYYY), * Employer Name, Contact, Contact Position, Employer Street, Employer Postcode, Employer Town, Employer Email, Employer Phone";
		echo "\r\n";

		//$sqlxl = "SELECT tr.firstnames,tr.surname,tr.dob,tr.ni,tr.uln,tr.home_street_description,tr.home_postcode,tr.home_town,tr.home_email,tr.home_telephone,tr.start_date,tr.target_date,organisations.legal_name,locations.contact_name,locations.street_description, locations.postcode, locations.town, locations.contact_email, locations.telephone FROM tr LEFT JOIN organisations ON organisations.id = tr.employer_id LEFT JOIN locations ON locations.id = tr.employer_location_id WHERE status_code = 2 and tr.id = (SELECT DISTINCT tr_id FROM student_qualifications WHERE tr_id = tr.id AND (certificate_applied!='' OR certificate_applied IS NULL));";
		$sqlxl = <<<SQL
SELECT
  tr.firstnames,
  tr.surname,
  tr.dob,
  tr.ni,
  tr.uln,
  tr.home_address_line_1,
  tr.home_address_line_2,
  tr.home_address_line_3,
  tr.home_address_line_4,
  tr.home_postcode,
  tr.home_email,
  tr.home_telephone,
  tr.start_date,
  tr.closure_date,
  organisations.legal_name,
  locations.contact_name,
  locations.address_line_1,
  locations.address_line_2,
  locations.address_line_3,
  locations.address_line_4,
  locations.postcode,
  locations.contact_email,
  locations.telephone
FROM
  tr
  LEFT JOIN organisations
    ON organisations.id = tr.employer_id
  LEFT JOIN locations
    ON locations.id = tr.employer_location_id
WHERE tr.status_code!=2 and tr.status_code!= 3 and tr.id IN
  (SELECT DISTINCT
    tr_id
  FROM
    student_qualifications
  WHERE
      certificate_applied = '' or certificate_applied IS NULL
    )
SQL;

		$stxl = $link->query($sqlxl);
		if($stxl)
		{
			while($rowxl = $stxl->fetch())
			{
				echo '"' . $rowxl['firstnames'] . '"';
				echo ',"' . $rowxl['surname'] . '"';
				echo ',"' . Date::toShort($rowxl['dob']) . '"';
				echo ',"' . $rowxl['ni'] . '"';
				echo ',"' . $rowxl['uln'] . '"';
				echo ',"' . $rowxl['home_address_line_1'] . '"';
				echo ',"' . $rowxl['home_postcode'] . '"';
				echo ',"' . $rowxl['home_address_line_3'] . '"';
				echo ',"' . '232' . '"';
				echo ',"' . $rowxl['home_email'] . '"';
				echo ',"' . $rowxl['home_telephone'] . '"';
				echo ',"' . Date::toShort($rowxl['start_date']) . '"';
				echo ',"' . Date::toShort($rowxl['closure_date']) . '"';
				echo ',"' . $rowxl['legal_name'] . '"';
				echo ',"' . $rowxl['contact_name'] . '"';
				echo ',"' . '' . '"';
				echo ',"' . $rowxl['address_line_1'] . '"';
				echo ',"' . $rowxl['postcode'] . '"';
				echo ',"' . $rowxl['address_line_3'] . '"';
				echo ',"' . $rowxl['contact_email'] . '"';
				echo ',"' . $rowxl['telephone'] . '"';
				echo "\r\n";
			}
		}
	}
}

