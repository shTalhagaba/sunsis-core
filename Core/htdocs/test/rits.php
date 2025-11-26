<?php
require("config.php");


if(isset($_GET['id']))
{
	$ndaq = new RITS();

	$id = $_GET['id'];
	$import = isset($_GET['import']) ? $_GET['import'] : '1';
	
	$full_unit_descriptions = ($import == 2);
	$xml = $ndaq->getQualification($id, $full_unit_descriptions);
	if(!$xml)
	{
		$xml = $ndaq->getUnit($id);
	}

	header("Content-Type: text/xml; charset=ISO-8859-1", true);
	if($xml)
	{
		echo $xml;
	}
	else
	{
		echo "<error>No qualification or unit with reference '$id' exists in the RITS database</error>";
	}
	
	exit(0);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>RITS Query</title>
</head>

<body>
<h1>RITS Query</h1>
<p>RITS is located at:</p>
<ul>
	<li><a href="http://ritsuatest.amorgroup.com/RitsRegister" target="_blank">Test site (prior to Monday 25th October)</a></li>
	<li><a href="http://register.ofqual.gov.uk" target="_blank">Live site</a></li>
</ul>
<p>Useful qualifications to search for:</p>
<ul>
<li>501/2230/7 : Seven units</li>
</ul>


<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">

<table>
	<tr>
		<td>QCA Reference: </td>
		<td><input type="text" name="id" value=""/></td>
		<td>
			<select name="import">
				<option value="1" selected="selected">Partial unit descriptions (no learning outcomes)</option>
				<option value="2">Full unit descriptions</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><input type="submit" value="Go"/></td>
	</tr>
</table>

</form>




</body>
</html>
