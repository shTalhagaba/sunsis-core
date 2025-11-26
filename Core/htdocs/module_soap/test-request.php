<?php  
	// how can we make this bit generic?

    chdir("C:\\Users\\Khushnood\\PhpstormProjects\\sunesis\\htdocs");
echo getcwd();

    $dataSource = new MySQLDataSource('ams', isset($_SERVER['PERSPECTIVE_DB_USER'])?$_SERVER['PERSPECTIVE_DB_USER']:ini_get('mysqli.default_user'), isset($_SERVER['PERSPECTIVE_DB_PASSWORD'])?$_SERVER['PERSPECTIVE_DB_PASSWORD']:ini_get('mysqli.default_pw'), isset($_SERVER['PERSPECTIVE_DB_HOST'])?$_SERVER['PERSPECTIVE_DB_HOST']:ini_get('mysqli.default_host'));
    $dataSource->setSQLSource('assessor_review');

    Dashboard::setTitle("Fruit Nutrition Table");

/*

  	$client = new SoapClient("https://exg.sunesis.uk.net/module_soap/wsdl.php", array('trace' => 1));

 	$myFile = "ExchangeGroupLearnerUpload28032013_165915.csv";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, filesize($myFile));
	fclose($fh);
	

try {
    $response = $client->acceptEXG('admin','exgtransfer', $myFile, $theData);
}
catch( SoapFault $fault ) {
    $response = '<br/><br/> Error Message : <br/>';
    $response .= $fault->getMessage();
}


  	echo $response;

*/
?>