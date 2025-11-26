<?php
class test_numeracy implements IUnauthenticatedAction
{
	public function execute( PDO $link )
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$participant_id = isset($_REQUEST['participant_id'])?$_REQUEST['participant_id']:'';
		$key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		require_once('tpl_test_numeracy.php');
	}

	private function generateErrorMessage($error_message)
	{
		$html = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Error Notification</title>
<style type="text/css">
html, body
{
	height:100%;
    margin: 0;
    padding: 0;
    border: none;
    text-align: center;
    font-family: arial,sans-serif;
}

div.box
{
	color: #3A4D16;
	/*background-color: #FAFAFA;*/
	background-color: #dfe9cd;
	font-family: sans-serif;
	width: 600px;
	/*border: 3px #D41E48 solid;*/
	border: 3px #608025 solid;
	padding:10px;
	-moz-border-radius: 12px;
	-webkit-border-radius: 12px;
	border-radius: 12px;
	-moz-box-shadow: 3px 3px 5px rgba(0,0,0,0.5);
	-webkit-box-shadow: 3px 3px 5px rgba(0,0,0,0.5);
	box-shadow: 3px 3px 5px rgba(0,0,0,0.5);
}

div.box h4
{
	margin-top: 3px;
}

div.message
{
	border: 1px solid gray;
	background-color: #f3fedf;
	margin:20px 50px 20px 50px;
	padding: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	font-size: 10pt;
	text-align:left;
}

td p:first-child
{
	margin-top: 0px;
}
</style>


</head>

<body>

<table width="100%" border="0" style="height:100%">
	<tr>
		<td valign="middle" align="center">
		<div class="box">
		<h4>Sorry, the server has been unable to complete your request</h4>
			<div class="message">$error_message</div>
		</div>
		</td>
	</tr>
</table>

</body>
</html>
HTML;
		return $html;
	}
}