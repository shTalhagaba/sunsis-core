<?php
class Prospect extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
	
		if($id == '')
		{
			return null;
		}

		$key = addslashes((string)$id);
$query = <<<HEREDOC
SELECT
	*
FROM
	prospects
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$vacany = null;
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$vacancy = new Prospect();
				$vacancy->populate($row);
				$vacancy->id = $id;
			}
			
		}
		else
		{
			throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $st->errorCode());
		}

		return $vacancy;	
	}
	
	public function save(PDO $link)
	{
		return DAO::saveObjectToTable($link, 'prospects', $this);
	}
	
	public function delete(PDO $link)
	{

		$qan = addslashes((string)$this->id);
		
		// Delete the qualification's structure and the qualification
		$sql = <<<HEREDOC
DELETE FROM
	vacancies
WHERE
	id = '$qan';
HEREDOC;
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		return false;
	}
	
             public $id; 
             public $firstname;      
             public $surname;         
             public $address;             
             public $address2;         
             public $address3;           
             public $address4;             
             public $dob;                  
             public $gender;                
             public $natno;             
             public $postcode;             
             public $telno;               
             public $alt_phone;           
             public $email;              
             public $choice1;                  
             public $choice2;                 
             public $choice3;                  
             public $choice4;             
             public $choice5;                 
             public $schoolattended;      
             public $leaving_date;              
             public $exams;              
             public $scheme;                 
             public $nameofscheme;         
             public $datestart;                   
             public $datefinish;                  
             public $workexpdet;         
             public $leisure;           
             public $dataprotection;           
             public $disability;             
             public $disabilitydets;    
             public $crime;                     
             public $ethnic;                  
             public $centrename_field;   
             public $prefarea_choice;    
             public $Nationality;       
             public $creditno;          
             public $workexp_choice;     	
	
}
?>