<?php
class PublicKey
{
	public function __construct($key = '')
	{
		if($key != '')
		{
			$this->parse($key);
		}
	}
	
	
	public function parse($key)
	{
		if(preg_match('/^' . PublicKey::SSH2_BEGIN . '/', $key))
		{
			$this->parseSshSecureShell($key);
		}
		elseif(preg_match('/^ssh-rsa|ssh-dss/', $key))
		{
			$this->parseOpenSSH($key);
		}
		else
		{
			throw new Exception("Unknown public key format. Please use openSSH or SSH Secure Shell (RFC 4716) formats.");
		}		
	}
	
	
	public function toOpenSSH($options = '')
	{
		if(is_null($this->type))
		{
			$msg = 'Cannot write the public key in openSSH format because the key-type is unknown. '
				. 'This is commonly caused by parsing a key in SSH Secure Shell (RFC 4716) format that does '
				. 'not contain the key type in the comment header.'; 
			throw new Exception($msg);
		}
		
		switch($this->type)
		{
			case 'dsa':
				$beginning_marker = 'ssh-dss';
				break;
				
			case 'rsa':
				$beginning_marker = 'ssh-rsa';
				break;
				
			default:
				throw new Exception("Unknown key type '{$this->type}'");
				break;
		}
		
		// Massage data
		$options = trim($options);
		$comment = str_replace(' ', '_', $this->comment);
		
		// Build OpenSSH line for authorized_keys file
		$line = '';
		if(strlen($options) > 0)
		{
			$line .= $options . ' ';
		}
		$line .= $beginning_marker.' '.$this->key;
		if(strlen($comment) > 0)
		{
			$line .= ' '.$comment;
		}
		
		return $line;
	}
	
	
	public function toSshSecureShell()
	{
		throw new Exception("Not implemented yet");
	}
	
	
	public function getType()
	{
		return $this->type;
	}
	
	
	public function setType($type)
	{
		if($type !== 'rsa' && $type !== 'dsa')
		{
			throw new Exception("Invalid key type '$type'. Accepted values 'dsa' and 'rsa' only.");
		}
		$this->type = $type;
	}
	
	
	public function getKey()
	{
		return $this->key;
	}
	
	
	public function setKey($key)
	{
		$this->key = $key;
	}
	
	
	public function getComment()
	{
		return $this->comment;
	}
	
	
	public function setComment($comment)
	{
		$this->comment = $comment;
	}
	
	
	public function getFingerprint()
	{
		$hash = strtolower(md5($this->key));
		$fingerprint = chunk_split($hash, 2, ':');
		
		return trim($fingerprint, ':');
	}
	
	
	private function parseOpenSSH($key)
	{
		$key = trim($key);
		
		if(preg_match('/[\r\n]/', $key))
		{
			throw new Exception("openSSH key data must be contained within one line");
		}
		
		// Parse and validate
		$tokens = explode(' ', $key);
		$num_tokens = count($tokens);
		if($num_tokens < 2 || $num_tokens > 3)
		{
			throw new Exception("Incorrect openSSH key format. Correct format: {ssh-dsa|ssh-rsa} {key} [comment]");
		}
		
		// Read type
		switch(strtolower(trim($tokens[0])))
		{
			case 'ssh-rsa':
				$this->type = 'rsa';
				break;
			
			case 'ssh-dss':
				$this->type = 'dsa';
				break;
			
			default:
				throw new Exception("Incorrect openSSH key format. Correct format: {ssh-dsa|ssh-rsa} {key} [comment]");
				break;
		}
		
		// Read key
		$this->key = trim($tokens[1]);
		
		// Read comment
		if($num_tokens == 3)
		{
			$this->comment = trim($tokens[2]);
			
			// Remove matching delimiting quotes
			if(preg_match("/^'.*'$/", $this->comment))
			{
				$this->comment = trim($this->comment, "'");
			}
			else if(preg_match("/^\".*\"$/", $this->comment))
			{
				$this->comment = trim($this->comment, "\"");
			}
			
			$this->headers['comment'] = $this->comment;
		}

	}
	
	
	private function parseSshSecureShell($key)
	{
		// Trim surplus whitespace
		$key = trim($key);
		
		// Line terminator conversions (convert all to UNIX)
		$key = preg_replace('/\r\n/', "\n", $key); // Windows to UNIX
		$key = preg_replace('/\r/', "\n", $key); // MAC(?) to UNIX
		
		// Create an array, one line per array element
		$lines = explode("\n", $key);
		
		$line = reset($lines); // get first line (first array element)
		
		if($line != PublicKey::SSH2_BEGIN)
		{
			throw new Exception("Incorrect beginning marker for SSH Secure Shell public key");
		}
		
		// Read headers
		while(strpos($line = next($lines), ': ') !== FALSE)
		{
			// Read header
			$tokens = explode(':', $line);
			$name = strtolower(trim($tokens[0]));
			$value = trim($tokens[1]);
			
			// Multi-line value
			while($value[strlen($value) - 1] === "\\")
			{
				$value = $line = substr($value, 0, strlen($value) - 1) . next($lines);
			}
			
			// Add to headers array
			$this->headers[$name] = $value;
			
			if($name === 'comment')
			{
				$this->comment = $value;
				
				// Remove matching delimiting quotes
				if(preg_match("/^'.*'$/", $this->comment))
				{
					$this->comment = trim($this->comment, "'");
				}
				else if(preg_match("/^\".*\"$/", $this->comment))
				{
					$this->comment = trim($this->comment, "\"");
				}
			}
		}
		
		
		// Attempt to determine the type value from the 'comment' header
		if(preg_match('/\b(rsa|RSA)\b/', $this->comment))
		{
			$this->type = "rsa";
		}
		else if(preg_match('/\b(dsa|DSA)\b/', $this->comment))
		{
			$this->type = 'dsa';
		}
		
		
		// Read key (we already have the first line at this point in the code)
		do
		{
			$this->key .= $line;
		}while(($line = next($lines)) !== FALSE && $line !== PublicKey::SSH2_END);
		
		// Look for terminating marker
		if($line !== PublicKey::SSH2_END)
		{
			throw new Exception("No end marker found for SSH2 Secure Shell public key");
		}
	}
	
	
	private $key = null;
	private $type = null;
	private $comment = null;
	
	// Headers
	private $headers = array();
	
	
	const SSH2_BEGIN = 	'---- BEGIN SSH2 PUBLIC KEY ----';
	const SSH2_END = 		'---- END SSH2 PUBLIC KEY ----';
	
}
?>