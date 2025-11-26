<?php
class Diceware
{
	public function generatePassphrase(PDO $link, $words = 4, $min_length = 0, $max_length=9999)
	{
		$loop_counter = 0;
		do
		{
			$indexes = $this->getRandomIndexes($words);
			$sql = "SELECT `word` FROM testdata.diceware8k WHERE id IN(".implode(',', $indexes).")";
			$password = implode(' ', DAO::getSingleColumn($link, $sql));
		} while((strlen($password) < $min_length) || (strlen($password) > $max_length) || (++$loop_counter > 100));
		
		if($loop_counter > 100)
		{
			$password = null;
		}

		return $password;
	}
	
	
	private function getRandomIndexes($words)
	{
		$result = array();
		while(count($result) < $words)
		{
			do
			{
				$throw = rand(1, 8192);
			} while(in_array($throw, $result));
			
			$result[] = $throw;
		}
		sort($result);
		
		return $result;
	}
}
?>