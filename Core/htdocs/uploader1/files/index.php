<?php
	include("dictionary_class.php"); // this is the main class and must be included to the main body.
?>
<html>
<head>
<title>Online dictionary! Written by Reza Salehi</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
        $dr=new dataReader();   // creating a new instance of the class dataReader. this class gets the inputed context and
								// uses the class LookupTable to find misspled words.
        $wrd="";
        $j=0;
		$i=0;
        $sug=false;
        $stat=false;
		
		if($_POST["sug"]=='1')
			$sug=true;
		else
			$sug=false;
			
		if($_POST["stat"]=='1')
			$stat=true;
		else
			$stat=false;
			
		$str=$_POST["str"];
        $dr->reader($str, $sug, $stat);
?>
<a href="index.htm"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Back</strong></font></a> 
</body>
</html>
