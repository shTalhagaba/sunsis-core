<?php
class PasswordUtilities
{
	public static function generateDicewarePassword(PDO $link, $num_words=4, $min_length=8, $max_length=9999)
	{
		$NUM_DICEWARE_WORDS = 8192;

		if(!is_numeric($num_words) || !is_numeric($min_length) || !is_numeric($max_length))
		{
			throw new Exception("Illegal argument");
		}

		$loop_counter = 0;
		do
		{
			$loop_counter++;
			$indexes = PasswordUtilities::getRandomSet($num_words, 1, $NUM_DICEWARE_WORDS);
			$sql = "SELECT `word` FROM testdata.diceware8k WHERE id IN(".implode(',', $indexes).")";
			$password = implode(' ', DAO::getSingleColumn($link, $sql));
		} while((strlen($password) < $min_length) || (strlen($password) > $max_length) || ($loop_counter > 100));

		if($loop_counter > 100)
		{
			$password = '';
		}

		return $password;
	}


	public static function randomCapitalisation($pwd, $limit=1)
	{
		$pwd = strtolower($pwd);

		// Count letters
		$num_letters = 0;
		for($i = 0; $i < strlen($pwd); $i++)
		{
			if(PasswordUtilities::is_char($pwd[$i]))
			{
				$num_letters++;
			}
		}

		if($num_letters <= 1)
		{
			return $pwd;
		}

		if($limit >= $num_letters)
		{
			$limit = $num_letters - 1;
		}

		$substitutions = 0;
		while($substitutions < $limit)
		{
			do
			{
				$index = rand(0, strlen($pwd) - 1);
			} while(!PasswordUtilities::is_char($pwd[$index]));

			$pwd[$index] = strtoupper($pwd[$index]);
			$substitutions++;
		}

		return $pwd;
	}


	public static function replaceSpacesWithNumbers($pwd)
	{
		while($index = strpos($pwd, ' '))
		{
			$pwd[$index] = rand(0,9);
		}

		return $pwd;
	}


	public static function generateDatePassword()
	{
		$day = rand(1,28);
		$month = rand(1,12);
		$year = rand(1000,2000);
		$dt = date_create($year.'-'.$month.'-'.$day);

		return $dt->format('dMY');
	}

	/**
	 * Checks password strength
	 * Note that the format of the messages take their lead from the validation messages
	 * of cracklib. Write your messages as if prefixed by "Password unsuitable because "
	 *
	 * @param $pwd String
	 * @return array {'code'=>integer, 'message'=>string}
	 */
	public static function checkPasswordStrength($pwd, array $extra_words)
	{
		$return = array('code'=>1, 'message'=>'');

		// Password length
		if(strlen($pwd) < 8)
		{
			$return['code'] = 0;
			$return['message'] = "it must be 8 or more characters in length";
			return $return;
		}

		// Mixture of numbers and upper/lowercase letters
		if(!(preg_match('/[A-Z]/', $pwd)
			&& preg_match('/[a-z]/', $pwd)
			&& preg_match('/[0-9]/', $pwd)) )
		{
			$return['code'] = 0;
			$return['message'] = "it must contain a mixture of numbers and letters (in lower and upper case)";
			return $return;
		}

		// Check the extra words list
		// Ignore words of two letters or less, which in the context of organisations
		// are usually abbreviations of Road or Saint
		for($i = 0; $i < count($extra_words); $i++)
		{
			$word = strtolower(trim($extra_words[$i]));

			// Skip words that include numbers
			// We cannot be too restrictive (e.g. 6th)
			if(preg_match('/[0-9]/', $word)){
				continue;
			}

			// remove anything that isn't alphanumeric
			$word = preg_replace('/[^A-Za-z0-9]/', "", $word);
			$word_rev = strrev($word);

			// Skip words of three letters or less
			if(strlen($word) < 4){
				continue;
			}

			if(preg_match('/(\b|[0-9])'.$word.'(\b|[0-9])/i', $pwd) > 0
				|| preg_match('/(\b|[0-9])'.$word_rev.'(\b|[0-9])/i', $pwd) > 0 )
			{
				$return['code'] = 0;
				$return['message'] = "it uses the word '$word' or its reverse '$word_rev'";
				return $return;
			}
		}


		// Run it through cracklib (PECL extension)
		if(function_exists("crack_opendict"))
		{
			$dictionary = @crack_opendict(ini_get("crack.default_dictionary"));
			if($dictionary)
			{
				$return['code'] = crack_check($pwd) ? '1':'0'; // returns true if strong password
				$return['message'] = $return['code'] ? "":crack_getlastmessage();
				crack_closedict($dictionary);
			}
		}

		return $return;
	}

	private static function getRandomSet($num, $min, $max)
	{
		$result = array();

		while(count($result) < $num)
		{
			do
			{
				$candidate = rand($min, $max);
			} while(in_array($candidate, $result));

			$result[] = $candidate;
		}

		sort($result);
		return $result;
	}

	private static function is_char($char)
	{
		$ascii = ord($char);
		return ($ascii >= 65 && $ascii <= 90) || ($ascii >= 97 && $ascii <= 122);
	}

	public static function getIllegalWords()
	{
		return array('password');
	}
}
?>