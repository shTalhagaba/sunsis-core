<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Individual Learner Record Data Stream</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<?php // #186 {0000000204} - removed old references to spearate calendar files ?>
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>
 
<style type="text/css">

.fieldLink
{
	cursor: pointer;
	font-style: bold;
}

.LearnerBackground
{
	background-color=#C0C0C0;
}

.heading
{
	font-weight: bold;
	font-size: 20px;
	text-decoration: underline;
	color: #00008B;
}


#unitCanvas
{
	width: 650px;
	height: 300px;
	border: 1px solid black;
	margin-left: 10px;
	padding-top: 10px;
	overflow: scroll;
	
	background-image:url('/images/paper-background-orange.jpg');
}

#fieldsBox
{
	width: 650px;
	min-height: 200px;
	border: 1px solid black;
	margin: 5px 0px 10px 10px;
}

#unitFields, #unitsFields
{
	display:none;
	padding: 10px;
}

#unitFields > h3, #unitsFields > h3
{
	margin-top: 5px;
}

	div.Units
	{
		margin: 3px 10px 3px 20px;
		border: 1px orange dotted;
		padding: 1px 1px 10px 1px;
		background-color: white;
		
		min-height: 100px;
	}
	
	div.UnitsTitle
	{
		font-size: 12pt;
		font-weight: bold;
		color: #395596;
		cursor: default;
		padding: 2px;
		margin: 0px;
	}
	
	div.Unit
	{
		margin: 3px 10px 3px 20px;
		border: 2px gray solid;
		-moz-border-radius: 5pt;
		padding: 3px;
		background-color: #F0F8FF; 
		min-height: 20px;
	}

	div.Unit2
	{
		margin: 3px 10px 3px 20px;
		border: 2px gray solid;
		-moz-border-radius: 5pt;
		padding: 3px;
		background-color: #E6E6FA; 
		min-height: 20px;
	}

	div.Unit3
	{
		margin: 3px 10px 3px 20px;
		border: 2px gray solid;
		-moz-border-radius: 5pt;
		padding: 3px;
		background-color: #DCDCDC; 
		min-height: 20px;
	}

	div.UnitTitle
	{
		margin: 2px;
		padding: 2px;
		cursor: default;
		font-weight: bold;
		/* background-color: #FDE3C1; */
		-moz-border-radius: 5pt;
	}
	
	div.UnitDetail
	{
		margin-left:5px;
		margin-bottom:5px;
		display: none;
		/*width: 500px;*/
	}
	
	div.UnitDetail p
	{
		margin: 0px 5px 10px 5px;
		font-style: italic;
		color: navy;
		text-align: justify;
	}
	
	div.UnitDetail p.owner
	{
		text-align:right;
		font-style:normal;
		font-weight:bold;
	}

</style>
</head>

<body onload="body_onload()">
<div class="banner">
	<div class="Title">Individual Learner Record Data Stream</div>
	<div class="ButtonBar">
		<button onclick="window.history.go(-1);">Back</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php 
class create_stream implements IAction
{
	public function execute(PDO $link)
	{
		
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception("Missing argument \$id");
		}
		
		
		$vo= Ilr0708::generateStream($link, $id);
		
		$handle = fopen("stream.txt",'r');
		echo "<textarea cols='393' rows=10 readonly>";
		while (!feof($handle)) 
		{
	        $buffer = fgets($handle);
	        echo $buffer;
    	}
    	echo "</textarea>";
    	fclose($handle);
		
		
	}
}
?>
</body>
</html>