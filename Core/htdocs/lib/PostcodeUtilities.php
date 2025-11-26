<?php
class PostcodeUtilities
{
	public function getLocation($postcode)
	{
		$url = '/map/browse.cgi?db=pc&pc='.str_replace(' ', '', $postcode);
		$html = $this->getPage($url);

		$location = array();
		
		// Get longitude and latitude
		$reg = '/<meta name="geo.position" content="([^"]*)"/';
		if(preg_match($reg, $html, $matches) > 0)
		{
			$geoPosition = explode(';', $matches[1]);
			$location['lat'] = $geoPosition[0];
			$location['lon'] = $geoPosition[1];
		}
		else
		{
			$location['lat'] = '';
			$location['lon'] = '';
		}
		
		return $location;
	}
	
	
	public function distanceBetween($postcode1, $postcode2)
	{
		$loc1 = $this->getLocation($postcode1);
		$loc2 = $this->getLocation($postcode2);
		
		return $this->distanceHaversine($loc1, $loc2);
		//return $this->distanceSphericalLawOfCosines($loc1, $loc2);
	}
	
	
	private function distanceSphericalLawOfCosines(array $loc1, array $loc2)
	{
		$lat1 = $loc1['lat'];
		$lon1 = $loc1['lon'];
		$lat2 = $loc2['lat'];
		$lon2 = $loc2['lon'];
		
		$R = 6371; // earth's mean radius in km
		$d = acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon2-$lon1)) ) * $R;
			
		return $d;		
	}
	
	
	private function distanceHaversine(array $loc1, array $loc2)
	{
		$R = 6371; // earth's mean radius in km
		$dLat = deg2rad($loc2['lat'] - $loc1['lat']);
		$dLon = deg2rad($loc2['lon'] - $loc1['lon']);
		$lat1 = deg2rad($loc1['lat']);
		$lat2 = deg2rad($loc2['lat']);
		
		$a = sin($dLat/2) * sin($dLat/2) +
		        cos($lat1) * cos($lat2) * 
		        sin($dLon/2) * sin($dLon/2);
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		$d = $R * $c;
		
		return $d;		
	}
	
	private function getPage($url)
	{
		$host = 'www.multimap.com';
		
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
	}
		
	
	private function readResponse($socket)
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
	}
}
