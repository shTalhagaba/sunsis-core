<?php
/**
 * An interface to Richard Zybert's Z1 Administration Daemon.
 * For clues on how to use the daemon, see the Perl file:
 * /usr/local/Z1/httpd/cgi-bin/Z1Admin/Z1W_User.pl
 * 
 */
class Z1Admin
{
	/**
	 * Constructor
	 *
	 * @param string $host
	 * @param integer $port
	 */
	public function __construct($username, $password, $host = "localhost", $port = 950)
	{
		$this->username = $username;
		$this->password = $password;
		$this->host = $host;
		$this->port = $port;
	}
	
	
	/**
	 * Returns true if the code is running on a Zybert Z1 or GEM
	 *
	 * @return boolean
	 */
	public static function onZ1()
	{
		return is_dir('/etc/Z1');
	}
	
	
	/**
	 *  Runs a shell command as root.
	 * 
	 * @param string $command the command line to execute
	 * @param integer $timeout the time limit for execution
	 */
	public function shellCommand($command, $timeout = 120)
	{
		return $this->run('EXECUTECOMMAND', $command, $timeout);
	}
	
	/**
	 * Create a new Linux user
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $fullname
	 * @param string $group
	 * @return boolean success flag
	 */
	public function newUser($username, $password, $fullname, $group = "users")
	{
		return $this->run('NEWUSER', $username, $password, $fullname, $group);
	}

	
	/**
	 * Modify an existing Linux user
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $fullname
	 * @param string $group
	 * @return boolean success flag
	 */
	public function modifyUser($username, $password, $fullname, $group = "users")
	{
		return $this->run('MODUSER', $username, $password, $fullname, $group);
	}
	
	
	/**
	 * Delete a Linux user
	 *
	 * @param string $username
	 * @param boolean $removeUserDirectory Optional
	 * @return boolean success flag
	 */
	public function deleteUser($username, $removeUserDirectory = true)
	{
		return $this->run('DELUSER', $username, $removeUserDirectory?'-r':'');
	}
	
	
	public function getUserNames($include_system_accounts = true)
	{
		$safe_mode = (ini_get("safe_mode") != '');
		
		if($include_system_accounts = true)
		{
			if(!$safe_mode)
			{
				$response = `cat /etc/passwd | cut -d: -f1`;
			}
			else
			{
				$response = $this->shellCommand('cat /etc/passwd | cut -d: -f1');
			}
		}
		else
		{
			// List users with directories only
			if(!$safe_mode)
			{
				$response = `ls -1 /home/users`;
			}
			else
			{
				$response = $this->shellCommand('ls -1 /home/users');
			}
		}
		
		// make list
		$users = explode("\n", $response);
		array_pop($users); // final element is always empty because of trailing \n

		return $users;
	}
	
	
	public function isSystemUser($username)
	{
		$all_users = $this->getUserNames(true);
		$normal_users = $this->getUserNames(false);
		
		return in_array($username, $all_users) && !(in_array($username, $normal_users));
	}
	
	
	public function isHumanUser($username)
	{
		$normal_users = $this->getUserNames(false);
		return in_array($username, $normal_users);
	}
	
	
	public function isUser($username)
	{
		$all_users = $this->getUserNames(true);
		return in_array($username, $all_users);
	}

	
	public function checkPermission($command)
	{
		if(is_null($this->key))
		{
			return false;
		}
		
		// Call private executeCommand() method directly
		$response = $this->executeCommand(array('PERMISSION', $command, $this->username) );
		$map = $this->mapValues($response);
	
		return $this->isOK($response);
	}

	
	/**
	 * Inserts an OpenSSH public key into the user's ~/.ssh/authorized_keys file
	 *
	 * @param string $username User
	 * @param string $key OpenSSH key in format {'ssh-rsa'|'ssh-dss'} {key} [comment]
	 * @param boolean $appendKey Whether to append the key or overwrite all existing keys
	 */
	public function registerSSHPublicKey($username, $key, $appendKey=false)
	{
		if(ini_get("safe_mode") != '')
		{
			throw new Exception('Z1Admin::registerSSHPublicKey() will not work when PHP safe mode is enabled.');
		}
		
		if(!$this->isHumanUser($username))
		{
			throw new Exception("No home directory found for user $username.");
		}
		
		$key = trim($key);
				
		$file_temp = '/var/tmp/'.$_COOKIE['PHPSESSID'].'_addSSHPublicKey.tmp';
		$file_authorized_keys = "/home/users/$username/.ssh/authorized_keys";
		
		// Write the key to a temporary file
		// (safe mode must be off)
		$fp = fopen($file_temp, 'w');
		fwrite($fp, "$key\n");
		fclose($fp);

		// Write/append the temporary file to the user's authorized_keys file
		// (requires root privileges)
		$operator = $appendKey?'>>':'>';
		$response = $this->shellCommand("cat $file_temp $operator $file_authorized_keys");
		
		// Change permissions on the authorized keys file
		// (requires root privileges)
		$this->shellCommand("chown $username:users $file_authorized_keys");
		$this->shellCommand("chmod u=rwx,g=,o= $file_authorized_keys");
		
		// Delete the temporary file
		// (safe mode must be off)
		shell_exec("rm $file_temp");		
	}
	
	
	public function revokeSSHPublicKey($username)
	{
		if(ini_get("safe_mode") != '')
		{
			throw new Exception('Z1Admin::revokeSSHPublicKey() will not work when PHP safe mode is enabled.');
		}
		
		if(!$this->isHumanUser($username))
		{
			throw new Exception("No home directory found for user $username.");
		}
		
		$file_authorized_keys = "/home/users/$username/.ssh/authorized_keys";

		if(file_exists($file_authorized_keys))
		{
			$response = $this->shellCommand("echo > $file_authorized_keys");
		}
	}

	
	/**
	 * Run any Z1 Admin Daemon command.  See Richard Zybert for a full list of
	 * commands and their arguments.  This method will accept any number of
	 * arguments and requires a knowledge of the command and its arguments.
	 * 
	 * This method acts as a wrapper around the private executeCommand()
	 * method, and ensures that authentication and authorisation have
	 * occurred.
	 *
	 * @return array mapped return values from the Z1 Admin Daemon
	 */
	public function run()
	{
		$argList = func_get_args();
		$numargs = func_num_args();

		$response = $this->executeCommand($argList);
		
		if($this->isNotAuthorized($response))
		{
			// Re-authenticate and try again
			if($this->authenticate())
			{
				if($this->checkPermission($argList[0]))
				{
					$response = $this->executeCommand($argList);
				}
				else
				{
					throw new UnauthorizedException("User {$this->username} does not have permission to execute command {$argList[0]}");
				}
			}
			else
			{
				throw new UnauthenticatedException("User {$this->username} cannot authenticate with the Z1 administration daemon");
			}
		}
		
		if($this->isError($response))
		{
			$msg = '';
			foreach($response as $key=>$value)
			{
				$msg .= "$key = '$value', ";
			}
			throw new Z1AdministrationException($msg, 0, $response);
		}
		
		return $response;
	}	
	

		
	
	////////////////////////////////////////////////////////////////////////////

	
	private function executeCommand(array $args)
	{
		$numargs = count($args);
		
		if($numargs == 0)
		{
			throw new Exception("No arguments supplied");
		}
		
		// Build command
		if(!is_null($this->key))
		{
			$in = strtoupper($args[0])."\t".$this->key."\n";
		}
		else
		{
			$in = strtoupper($args[0])."\n";
		}
		
		// Add arguments
		for($i = 1; $i < $numargs; $i++)
		{
			$in .= $args[$i]."\n";
		}
		
		$socket = $this->getSocket();
		socket_write($socket, $in, strlen($in));
		$rawResponse = $this->readResponse($socket);
		socket_close($socket);
		
		// Return the response, mapped to an array unless the response
		// is redirected STDOUT from a system command
		if($args[0] == 'EXECUTECOMMAND')
		{
			if(preg_match('/^Status\tNOTAUTHORIZED\n/', $rawResponse))
			{
				return $this->mapValues($rawResponse);
			}
			else
			{
				return $rawResponse; // return system command's raw response
			}
		}
		else
		{
			return $this->mapValues($rawResponse);
		}
	}
	

	/**
	 * Authenticate with the Z1 administration daemon. Authentication is not
	 * directly included in the private executeCommand() method because
	 * authentication requires the execution of a Z1 command.
	 *
	 * @param string $username
	 * @param string $password
	 * @return boolean success flag
	 */
	private function authenticate()
	{
		// Clear existing key
		$this->key = null;
		
		// Call private executeCommand() method directly
		$response = $this->executeCommand(array('AUTH', $this->username, $this->password) );
		
		if($this->isOK($response))
		{
			$this->key = $response['key'];
			return true;
		}
		else
		{
			$this->key = null;
			return false;
		}
	}
		
	
	private function readResponse($socket)
	{
		$buffer = "";
		$response = "";
		while($buffer = socket_read($socket, 2048))
		{
			$response .= $buffer;
		}

		// socket_read returns FALSE on error (empty string "" when EOF)
		if($buffer === false)
		{
			throw new Exception("Error reading response from Z1 Admin daemon. Reason: (" . socket_last_error($socket) . "): " . socket_strerror(socket_last_error($socket)));
		}

		return $response;
	}


	
	
	private function mapValues($response)
	{
		// Load response into associative array 
		$map = array();
		$lines = explode("\n", $response);
		$lineIndex = 0;
		
		foreach($lines as $line)
		{
			// Ignore blank lines
			if($line == '') continue;
			
			$lineIndex++;
			
			// Each line *should* be in the format "name\tvalue"
			$nameValue = explode("\t", $line);
			
			// Add name and value to a map
			if(count($nameValue) == 2)
			{
				$map[$nameValue[0]] = $nameValue[1];
			}
			else
			{
				$map['line'.$lineIndex] = $nameValue[0];
			}
		
		}
		
		return $map;		
	}

	
	private function getSocket()
	{
		$ip_address = gethostbyname($this->host);

		$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if($socket == false)
		{
			throw new Exception('Cannot create Socket. ' . socket_strerror(socket_last_error()));
		}

		$result = @socket_connect($socket, $ip_address, $this->port);
		if($result == false)
		{
			throw new Exception("Socket could not connect to {$this->host} on port {$this->port}. Reason: (" . socket_last_error($socket) . "): " . socket_strerror(socket_last_error($socket)));
		}

		return $socket;
	}
	
	
	private function isOK($map)
	{
		if(is_array($map))
		{
			return array_key_exists('Status', $map) && ($map['Status'] == 'OK');
		}
		else
		{
			return true;
		}
	}
	
	
	private function isNotAuthorized($map)
	{
		if(is_array($map))
		{
			return array_key_exists('Status', $map) && ($map['Status'] == 'NOTAUTHORIZED');
		}
		else
		{
			return false;
		}
	}
	
	
	private function isError($map)
	{
		if(is_array($map))
		{
			return array_key_exists('Error', $map)
				|| (array_key_exists('Status', $map) && ($map['Status'] != 'OK'));
		}
		else
		{
			return false;
		}
	}	
	
	private $key = null;
	private $username = null;
	private $password = null;
	private $host = null;
	private $port = null;
	
}
?>
