<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN"
"http://www.w3.org/MarkUp/Wilbur/HTML32.dtd">
<html>
<body>
<?php 
require_once('./lib/nusoap.php');
// Create the client instance
$client = new soapclient('http://localhost/do.php?_action=course_server');
// Call the SOAP method
$result = $client->call('hello', array('name' => 'Scott'));
// Display the result
print_r($result);
?>
</body>
</html>		