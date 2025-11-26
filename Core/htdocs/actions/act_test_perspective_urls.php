<?php
class test_perspective_urls implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		if(DB_NAME != 'am_demo' && DB_NAME != "ams")
			exit;
		$urls = array(
			'perspective-uk.com',
			'demo.sunesis.uk.net',
			'skillspoint.sunesis.uk.net',
			'doncaster.sunesis.uk.net',
			'donc-demo.sunesis.uk.net',
			'superdrug.sunesis.uk.net',
			'reed.sunesis.uk.net',
			'reed-demo.sunesis.uk.net',
			'pathway.sunesis.uk.net',
			'traintogether.sunesis.uk.net',
			'aet.sunesis.uk.net',
			'set.sunesis.uk.net',
			'edudo.sunesis.uk.net',
			'ligauk.sunesis.uk.net',
			'direct.sunesis.uk.net',
			'raytheon.sunesis.uk.net',
			'baltic.sunesis.uk.net',
			'gigroup.sunesis.uk.net',
			'lcurve.sunesis.uk.net',
			'lead.sunesis.uk.net',
			'platinum.sunesis.uk.net',
			'cracker.sunesis.uk.net',
			'svn.perspective-uk.com',
			'sugar.perspective-uk.com',
			'demo.clm.uk.net',
			'clm.uk.net'
		);

		foreach($urls AS $url)
		{
			pre($this->curl_download($url));
		}
		exit;
	}

	private function curl_download($Url)
	{
		// is cURL installed yet?
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_URL, $Url.':80');
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2) Gecko/20100115 Firefox/3.6");
		curl_setopt($ch, CURLOPT_PROXY, $Url.':80');

		// Download the given URL, and return output
		$output = curl_exec($ch);

		$output_result = curl_getinfo($ch);

		echo '<br>*****************************************<br>';
		echo '<br>URL: ' . $output_result['url'];
		echo '<br>HTTP Code: ' . $output_result['http_code'];
		echo '<br>Total Time: ' . $output_result['total_time'];
		echo '<br>Name Lookup Time: ' . $output_result['namelookup_time'];
		echo '<br>Redirect URL: ' . $output_result['redirect_url'];

		// Close the cURL resource, and free system resources
		curl_close($ch);

	}
}