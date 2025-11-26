<?php
class GeoLocation
{
	/**
	 * @param GeoLocation $loc
	 * @return int
	 */
	public function distanceBetween(GeoLocation $loc)
	{
		return $this->distanceHaversine($this, $loc);
		//return $this->distanceSphericalLawOfCosines($loc1, $loc2);
	}
	
	/**
	 * @return float
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}
	
	/**
	 * @return float
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}
	
	/**
	 * @param float $lat
	 * @param float $long
	 */
	public function setLatitudeLongitude($lat, $long)
	{
		$this->latitude = (float)$lat;
		$this->longitude = (float)$long;

		$ll = new LatLng($this->latitude, $this->longitude);
		$os = $ll->toOSRef();
		$this->easting = round($os->easting, 0);
		$this->northing = round($os->northing, 0);		
	}

	/**
	 * @return int
	 */
	public function getEasting()
	{
		return $this->easting;
	}
	
	/**
	 * @return int
	 */
	public function getNorthing()
	{
		return $this->northing;
	}

	/**
	 * @param int $easting
	 * @param int $northing
	 */
	public function setEastingNorthing($easting, $northing)
	{
		$this->easting = $easting;
		$this->northing = $northing;
		
		$os = new OSRef($this->easting, $this->northing);
		$ll = $os->toLatLng();
		$this->latitude = $ll->lat;
		$this->longitude = $ll->lng;
	}
	
	/**
	 * RE - I've added in the PDO $link parameter to allow for storage of geolocation data
	 * This function previously relied on multimap everytime
	 * - now we use www.npemap.com data stored in the central 
	 * - database, which is then updated when we get a new postcode
	 * - via multimap.  This prevents us 'spamming' multimap.
	 * @param string $postcode
	 * @param PDO $link
	 */
	/*public function setPostcode($postcode, PDO $link = NULL) {
		// central.lookup_postcode_geolocation check
		$postcode_elements = preg_split("/ /", $postcode);
		
		if ( isset($postcode_elements[1]) ) {
			$check_postcode_sql = 'select easting, northing, latitude, longitude from central.lookup_postcode_geolocation where outward = "'.$postcode_elements[0].'" and inward = "'.$postcode_elements[1].'"';
			$st = $link->query($check_postcode_sql);
			if( $st ) {				
				$row = $st->fetch();
				if( $row ) {
					$this->longitude = $row['longitude'];
					$this->latitude = $row['latitude'];
					$this->easting = $row['easting'];
					$this->northing = $row['northing'];			
				}
				else {
					$host = 'clients.multimap.com';
					$pc = strtoupper(str_replace(' ', '%20', $postcode));
	
					// Full Multimap URL below (deliveryID and identifier seem to be optional)
					// http://mmw.multimap.com/API/geocode/1.2/public_api?output=json&callback=MMGeocoder.prototype._GeneralJSONCallback&qs=b17%200bx&countryCode=GB&deliveryID=2007071687140918293&identifier=0
					$url = "/API/geocode/1.2/public_api?output=xml&callback=MMGeocoder.prototype._GeneralJSONCallback&qs=$pc&countryCode=GB";
					$html = $this->getPage($host, $url);
		
					$index = strpos($html, '<?xml'); // strip HTTP headers from XML payload
					if( $index === false ) {
						$this->errorcode = 1;
					}
					else {	
		
						$xml = substr($html, $index);
						$Results = new SimpleXMLElement($xml);
							
						$locationCount = (int) $Results['locationCount'];
						
						if($locationCount > 1) {
							$this->errorcode = 1;
						}
						else {
							if( isset($Results->Location[0]) ) {	
								$this->setLatitudeLongitude($Results->Location[0]->Point[0]->Lat[0], $Results->Location[0]->Point[0]->Lon[0]);
							
								// add the postcode to the lookup table
								$set_postcode_sql = 'insert into central.lookup_postcode_geolocation (outward, inward, easting, northing, latitude, longitude, source ) ';
								$set_postcode_sql .= 'values ("'.$postcode_elements[0].'", "'.$postcode_elements[1].'", "'.$this->easting.'", "'.$this->northing.'", "'.$this->latitude.'", "'.$this->longitude.'", "Sunesis")';
								$st = $link->query($set_postcode_sql);
							}
							else {
								$this->errorcode = 1;
							}
						}
					}
				}
			}
			else {
				$this->errorcode = 1;
			}
		}
		// only a partial postcode - set errorcode so we test just the outward bit
		else {
			$this->errorcode = 1;		
		}
		
		// all attempts to find the postcode have so far failed
		// use the top level (outward) part of the postcode only
		if ( $this->errorcode == 1 ) {
			$check_postcode_sql = 'select easting, northing, latitude, longitude from central.lookup_postcode_geolocation where outward = "'.$postcode_elements[0].'"';
			$st = $link->query($check_postcode_sql);
			if( $st ) {				
				$row = $st->fetch();
				if( $row ) {
					$this->longitude = $row['longitude'];
					$this->latitude = $row['latitude'];
					$this->easting = $row['easting'];
					$this->northing = $row['northing'];	

					$this->errorcode = NULL;
					
					// add the postcode to the lookup table with top level data only
					if ( isset($postcode_elements[1]) ) {
						$set_postcode_sql = 'insert into central.lookup_postcode_geolocation (outward, inward, easting, northing, latitude, longitude, source ) ';
						$set_postcode_sql .= 'values ("'.$postcode_elements[0].'", "'.$postcode_elements[1].'", "'.$this->easting.'", "'.$this->northing.'", "'.$this->latitude.'", "'.$this->longitude.'", "Sunesis - outward only")';
						$st = $link->query($set_postcode_sql);
					}
				}	
			}
		}	
	}*/
	
	/**
	 * Sets the postcode of this object if geolocation data can be found for the postcode.
	 * @param string $postcode preferably with a space separating outward and inward codes, but works equally well without
	 * @param PDO $link (optional) if not specified, only streetmap.co.uk will be checked
	 * @return bool true if the postcode could be set, false if not
	 * @author iss
	 */
	public function setPostcode($postcode, PDO $link = null)
	{
		$postcode = trim($postcode);
		if(!$postcode){
			//throw new Exception("No postcode specified");
			return false;
		}
		
		// Determine outward and inward code blocks
		// If there's a space, identify the first block as the outward code (work from left to right)
		// If there isn't a space, identify the last three characters as the inward code (work from right to left)
		if(preg_match('/([a-z0-9]+)\\s+([a-z0-9]+)^/i', $postcode, $matches))
		{
			// Use the included space as a guide
			$outward = $matches[1]; // We can be certain this was meant to the be outward code
			if(strlen($outward) < 2 || strlen($outward) > 4){
				//throw new Exception("Invalid outward code in postcode '$postcode'"); // We need at least a valid outward code for this to work
				return false;
			}
			$inward = $matches[2]; // We need to validate this (must be three characters long to be usable)
			if(strlen($inward) != 3){
				$inward = ''; // partial inward code, so it's no use to us. We can still continue with the outward code though.
			}
			$postcode = $outward.$inward; // recombine without the space, we don't need the space from hereon
		}
		else
		{
			// There either isn't an included space or the format is odd
			$postcode = str_replace(" ","",$postcode); // Remove absolutely all spaces
			if(strlen($postcode) < 2 || strlen($postcode) > 7)
			{
				//throw new Exception("Invalid postcode length: ".$postcode);
				return false;
			}
			if(strlen($postcode) > 4)
			{
				$inward = substr($postcode, -3); // inward code is always the last three characters
				$outward = substr($postcode, 0, strlen($postcode) - 3);
			}
			else
			{
				$inward = ""; 
				$outward = $postcode; // with 4 characters or less, the postcode can only be an outward code
			}			
		}
		
		// If no database connection is supplied, we can only use StreetMap
		if(!$link)
		{
			$os = $this->convertPostcodeByStreetMap($postcode);
			if(!$os){
				//throw new Exception("Cannot find postcode $postcode");
				return false;
			}
			$this->setEastingNorthing($os['easting'], $os['northing']);
			$this->postcode = $postcode;
			return true;
		}
		
		// Try postcodes database first
		if(DAO::schemaEntityExists($link, "postcodes"))
		{
			if(!$inward)
			{
				// Approximate match
				$os = DAO::getObject($link, "SELECT AVG(longitude) AS 'longitude', AVG(latitude) AS 'latitude' FROM postcodes.ukpostcodes WHERE postcode_outward='".addslashes($outward)."' GROUP BY postcode_outward");
			}
			else
			{
				// Precise match
				$os = DAO::getObject($link, "SELECT longitude,latitude FROM postcodes.ukpostcodes WHERE postcode='".addslashes($postcode)."'");
			}
			if($os){
				$this->setLatitudeLongitude($os->latitude, $os->longitude);
				$this->postcode = $postcode;
				return true;
			}
		}
		
		// Try central.lookup_postcode_geolocation next
		if(DAO::schemaEntityExists($link, "central", "lookup_postcode_geolocation"))
		{
			if(!$inward)
			{
				// Approximate match
				$os = DAO::getObject($link, "SELECT ROUND(AVG(easting)) AS 'easting', ROUND(AVG(northing)) AS 'northing' FROM central.lookup_postcode_geolocation WHERE outward='".addslashes($outward)."' GROUP BY outward");
			}
			else
			{
				// Precise match
				if(DAO::schemaEntityExists($link, "central", "lookup_postcodes_geolocation", "postcode"))
				{
					$os = DAO::getObject($link, "SELECT easting,northing FROM central.lookup_postcode_geolocation WHERE postcode='".addslashes($postcode)."'");
				}
				else
				{
					$os = DAO::getObject($link, "SELECT easting,northing FROM central.lookup_postcode_geolocation WHERE outward='".addslashes($outward)."' AND inward='".addslashes($inward)."'");
				}
			}
			if($os){
				$this->setEastingNorthing($os->easting, $os->northing);
				$this->postcode = $postcode;
				return true;
			}				
		}
		
		// Try the Internet (supports searching by full and outward postcodes)
		$os = $this->convertPostcodeByStreetMap($postcode);
		if($os)
		{
			$this->setEastingNorthing($os['easting'], $os['northing']);
			$this->postcode = $postcode;
			
			// Write this to central.lookup_postcodes_geolocation for next time
			if(DAO::schemaEntityExists($link, "central", "lookup_postcode_geolocation"))
			{
				$data = array();
				$data['postcode'] = $postcode;
				$data['outward'] = $outward;
				$data['inward'] = $inward;
				$data['easting'] = $this->easting;
				$data['northing'] = $this->northing;
				$data['latitude'] = $this->latitude;
				$data['longitude'] = $this->longitude;
				$data['source'] = 'streetmap.co.uk';
				DAO::saveObjectToTable($link, "central.lookup_postcode_geolocation", $data);					
			}
			
			return true;
		}
		
		// Now we're really running out of options. Perform an approximate match on the outward code only.
		if(DAO::schemaEntityExists($link, "postcodes"))
		{
			$os = DAO::getObject($link, "SELECT AVG(longitude) AS 'longitude', AVG(latitude) AS 'latitude' FROM postcodes.ukpostcodes WHERE postcode_outward='".addslashes($outward)."' GROUP BY postcode_outward");
			if($os){
				$this->setLatitudeLongitude($os->latitude, $os->longitude);
				$this->postcode = $postcode;
				return true;
			}
		}
		elseif(DAO::schemaEntityExists($link, "central", "lookup_postcodes_geolocation"))
		{
			$os = DAO::getObject($link, "SELECT ROUND(AVG(easting)) AS 'easting', ROUND(AVG(northing)) AS 'northing' FROM central.lookup_postcodes_geolocation WHERE outward='".addslashes($outward)."' GROUP BY outward");
			if($os){
				$this->setEastingNorthing($os->easting, $os->northing);
				$this->postcode = $postcode;
				return true;
			}			
		}
		
		// Throw an exception as a last resort
		//throw new Exception("Postcode $postcode could not be found");
		return false;
	}
	
	/**
	 * @return string
	 */
	public function __toString()
	{
		return "Lon: $this->longitude, Lat: $this->latitude, E: $this->easting, N: $this->northing, ";
	}

	/**
	 * @param GeoLocation $loc1
	 * @param GeoLocation $loc2
	 * @return float
	 */
	private function distanceSphericalLawOfCosines(GeoLocation $loc1, GeoLocation $loc2)
	{
		$lat1 = $loc1->latitude;
		$lon1 = $loc1->longitude;
		$lat2 = $loc2->latitude;
		$lon2 = $loc2->longitude;
		
		$R = 6371; // earth's mean radius in km
		$d = acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon2-$lon1)) ) * $R;
			
		return $d;		
	}
	
	/**
	 * @param GeoLocation $loc1
	 * @param GeoLocation $loc2
	 * @return float
	 */
	private function distanceHaversine(GeoLocation $loc1, GeoLocation $loc2)
	{
		$R = 6371; // earth's mean radius in km
		$dLat = deg2rad($loc2->latitude - $loc1->latitude);
		$dLon = deg2rad($loc2->longitude - $loc1->longitude);
		$lat1 = deg2rad($loc1->latitude);
		$lat2 = deg2rad($loc2->latitude);
		
		$a = sin($dLat/2) * sin($dLat/2) +
		        cos($lat1) * cos($lat2) * 
		        sin($dLon/2) * sin($dLon/2);
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		$d = $R * $c;
		
		return $d;		
	}


	/*private function getPage($host, $url)
	{
		$socket = $this->getSocket($host, 80);
		$in =  "GET $url HTTP/1.0\r\n";
		$in .= "Host: $host\r\n";
		$in .= "Accept-Charset: iso-8859-1\r\n";
		$in .= "Connection: Close\r\n\r\n";
		socket_write($socket, $in, strlen($in));
		$doc = $this->readResponse($socket);
		socket_close($socket);

		return $doc;
	}

	
	private function getSocket($host, $port)
	{
		$ip_address = gethostbyname($host);

		$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if($socket == false)
		{
			throw new Exception("Cannot create Socket. " . socket_strerror(socket_last_error()));
		}

		$result = @socket_connect($socket, $ip_address, $port);
		if($result == false)
		{
			throw new Exception("Socket could not connect to server. Reason: (" . socket_last_error($socket) . "): " . socket_strerror(socket_last_error($socket)));
		}

		return $socket;
	}*/
		

/*	private function readResponse($socket)
	{
		$out = "";
		$doc = "";
		while($out = socket_read($socket, 10240)) // Read page in 10KB at a time
		{
			$doc .= $out;
		}

		// socket_read return FALSE on error (empty string "" when EOF)
		if($out === false)
		{
			throw new Exception("Problem reading response from OpenQuals. Reason: (" . socket_last_error($socket) . "): " . socket_strerror(socket_last_error($socket)));
		}

		return $doc;
	}*/
	
	/**
	 * Uses streetmap.co.uk to convert a postcode to an Easting and Northing
	 * @param string $postcode
	 * @return array Easting and Northing in the form array('easting'=>val, 'northing'=>val) or null if the postcode could not be found
	 * @throws Exception
	 */
	private function convertPostcodeByStreetMap($postcode)
	{
		$url = "http://www.streetmap.co.uk/ids.srf?";
		
		// POST data
		$post_fields = "Submit2=GO";
		$post_fields .= "&mapp=idgc";
		$post_fields .= "&name=".str_replace(" ","+",$postcode);
		$post_fields .= "&searchp=ids";
		
		// Initialise cURL
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // return a string (don't output to the browser directly)
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3); // seconds
		curl_setopt($curl, CURLOPT_TIMEOUT, 10); // seconds
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false); // very important, we just want the Location header
		curl_setopt($curl, CURLOPT_HEADER, true); // include the headers in the output
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2) Gecko/20100115 Firefox/3.6");
		
		// Connect (retry up to five times if we experience DNS issues)
		$result = curl_exec($curl);
		if(curl_error($curl) == CURLE_COULDNT_RESOLVE_HOST)
		{
			$tries = 0;
			do{
				$tries++;
				sleep(1);
				$result = curl_exec($curl);
			} while(curl_error($curl) == CURLE_COULDNT_RESOLVE_HOST && $tries < 5);
			if(curl_error($curl)){
				curl_close($curl);
				throw new Exception(curl_error($curl), curl_errno($curl));
			}
		}

		curl_close($curl);
		
		// Extract the easting and northing from the Location HTTP header
		$reg = '/^Location: idgc\\.srf\\?x=([0-9]+)&y=([0-9]+)/m';
		if(preg_match($reg, $result, $matches))
		{
			return array('easting'=>$matches[1], 'northing'=>$matches[2]);
		}
		
		// Return null by default
		return null;
	}
	
	private $longitude;
	private $latitude;
	private $easting;
	private $northing;
	private $postcode;
	public $errorcode;
}
