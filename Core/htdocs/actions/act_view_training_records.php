<?php
class view_training_records implements IAction
{
    public function execute(PDO $link)
    {
	    $cert_received = isset($_GET['type'])?$_GET['type']:'';
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_training_records", "View Training Records");

        $view = ViewTrainingRecords::getInstance($link);
        $view->refresh($link, $_REQUEST);

	    if($cert_received != '')
	    {
			$this->generateCertificatesFile($link, $view);
	    }
        require_once('tpl_view_training_records.php');
    }

	public function generateCertificatesFile(PDO $link, ViewTrainingRecords $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');//$statement->setClause()
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=file.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			$line = '';
			$line .= 'Prefix, Gender, Forename, Surname, Middle name, Date of Birth, Ethnic Group, NI Number, Unique Number, Apprentice Street, Apprentice Postcode, Apprentice Town, Apprentice Country(UK = 232), Apprentice Email, Apprentice Phone, Apprentice Start Date, Employer Name, Contact, Employer Size, Contact Position, Employer Street, Employer Postcode, Employer Town, Employer Email, Employer Phone, Employer Sector, PO Number, Awarding Body Number, Apprentice Funding, Cost Center, Notes';
			echo $line . "\r\n";
			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$line = ',';// prefix
				$line .= $row['gender'] . ', ';//gender
				$line .= str_replace(',','; ', $row['firstnames']) . ', ';//forename
				$line .= str_replace(',','; ', $row['surname']) . ', ';//surname
				$line .= ',';//middle name
				$line .= $row['dob'] . ', ';//date of birth
				$line .= $row['ethnicity'] . ', ';//ethnic group
				$line .= $row['national_insurance'] . ', ';//ni number
				$line .= $row['uln'] . ', ';//unique number
				$line .= str_replace(',','; ', $row['home_address_1']) . ', ';//apprentice street
				$line .= $row['home_postcode'] . ', ';//aprentice postcode
				$line .= str_replace(',','; ', $row['home_address_3']) . ', ';//apprentice town
				$line .= ', ';//apprentice country
				$line .= $row['home_email'] . ', ';//apprentice email
				$line .= $row['home_telephone'] . ', ';//apprentice phone
				$line .= $row['start_date'] . ', ';// apprentice start date
				$line .= $row['employer'] . ', ';//employer name
				$line .= $row['contact_name'] . ', ';//contact
				if(isset($row['employer_id']) && $row['employer_id'] != '')
					$employer_size = DAO::getSingleValue($link, "select description from lookup_employer_size where code = (SELECT code FROM organisations WHERE id = {$row['employer_id']} )");
				else
					$employer_size = '';
				$line .=  $employer_size . ', ';//employer size
				$line .= ', ';//contact position
				$line .= str_replace(',','; ', $row['work_address_line_1']) . ', ';//employer street
				$line .= $row['work_postcode'] . ', ';//employer postcode
				$line .= str_replace(',','; ', $row['work_address_line_2']) . ', ';//employer town
				$line .= $row['contact_email'] . ', ';//employer email
				$line .= $row['contact_telephone'] . ', ';//employer phone
				$line .= $row['sector'] . ', '; //employer sector
				$line .= ', ';//po number
				$line .= ', ';//awarding body number
				$line .= ', ';//apprentice funding
				$line .= ', ';//cost center
				$line .= ', ';//notes
				echo $line . "\r\n";
			}
		}

		exit;
	}

}
?>