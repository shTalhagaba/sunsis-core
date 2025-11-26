<?php /* @var $vo Contract */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contract</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>


	<script language="JavaScript">
function save()
{
	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	
	myForm.submit();
}


function addType()
{
	var optn = document.createElement("OPTION");
	var value = window.prompt("Enter a new type");
	if(value!=null)
	{
		var type = document.getElementById('type');
		optn.value = parseInt(type[type.length-1].value)+1;
		optn.text = optn.value + ". " + value;
		
		// Save elements by AJAX
		var request = ajaxBuildRequestObject();
		if(request != null)
		{
			var postData = 'id=' + optn.value
				+ '&type=' + value;
				
			request.open("POST", expandURI('do.php?_action=save_evidence_type'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);
			
			if(request.status == 200)
			{
				type.options.add(optn);
			}
			else
			{
				alert(request.responseText);
			}
		}
		else
		{
			alert("Could not create XMLHttpRequest object");
		}
	}
}

function addContent()
{
	var optn = document.createElement("OPTION");
	var value = window.prompt("Enter new Content");
	if(value!=null)
	{
		var type = document.getElementById('content');
		optn.value = parseInt(type[type.length-1].value)+1;
		optn.text = optn.value + ". " + value;
		
		// Save elements by AJAX
		var request = ajaxBuildRequestObject();
		if(request != null)
		{
			var postData = 'id=' + optn.value
				+ '&content=' + value;
				
			request.open("POST", expandURI('do.php?_action=save_evidence_content'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);
			
			if(request.status == 200)
			{
				type.options.add(optn);
			}
			else
			{
				alert(request.responseText);
			}
		}
		else
		{
			alert("Could not create XMLHttpRequest object");
		}
	}
}


function addCategory()
{
	var optn = document.createElement("OPTION");
	var value = window.prompt("Enter new Category");
	if(value!=null)
	{
		var type = document.getElementById('category');
		optn.value = parseInt(type[type.length-1].value)+1;
		optn.text = optn.value + ". " + value;
		
		// Save elements by AJAX
		var request = ajaxBuildRequestObject();
		if(request != null)
		{
			var postData = 'id=' + optn.value
				+ '&category=' + value;
				
			request.open("POST", expandURI('do.php?_action=save_evidence_category'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);
			
			if(request.status == 200)
			{
				type.options.add(optn);
			}
			else
			{
				alert(request.responseText);
			}
		}
		else
		{
			alert("Could not create XMLHttpRequest object");
		}
	}
}


</script>

</head>
<body>
<div class="banner">
	<div class="Title">Evidence Template</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="if(confirm('Are you sure?'))window.location.href='do.php?_action=view_evidence&framework_id=<?php echo rawurlencode($framework_id);?>&tr_id=<?php echo rawurlencode($tr_id); ?>&internaltitle=<?php echo rawurlencode($internaltitle); ?>&qualification_id=<?php echo rawurlencode($qualification_id); ?>&group_id=<?php echo rawurlencode($group_id); ?> ';"> Close </button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />  
<input type="hidden" name="framework_id" value="<?php echo $framework_id; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $tr_id; ?>" />
<input type="hidden" name="internaltitle" value="<?php echo $internaltitle; ?>" />
<input type="hidden" name="qualification_id" value="<?php echo $qualification_id;?>" />
<input type="hidden" name="group_id" value="<?php echo $group_id;?>" />
<input type="hidden" name="_action" value="save_evidence_template"/>
<table border="0" cellspacing="4" style="margin-left:10px">
	<col width="140" />
	<tr>
		<td class="fieldLabel_compulsory">Evidence Title :</td>
		<td><textarea cols=40 rows=4 name="reference"><?php echo htmlspecialchars((string)$vo->reference ?: ''); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Evidence Reference :</td>
		<td><input class="compulsory" type="text" name="title" value="<?php echo htmlspecialchars((string)$vo->title ?: ''); ?>" size="10" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Portfolio Page No:</td>
		<td><input class="compulsory" type="text" name="page_no" value="<?php echo htmlspecialchars((string)$vo->page_no ?: ''); ?>" size="10" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Assessment Method:</td>
		<td><?php echo HTML::select('type', $dropdown_type, $vo->type, true, true); ?> </td>
		<td>  
				<div style="margin:10px 0px 5px 0px">
					<span class="button" onclick="addType();"> Add New </span>
				</div>
		</td>	
	</tr>	
	<tr>
		<td class="fieldLabel_compulsory">Evidence Type:</td>
		<td><?php echo HTML::select('content', $dropdown_content, $vo->content, true, true); ?></td>
		<td>  
				<div style="margin:10px 0px 5px 0px">
					<span class="button" onclick="addContent();"> Add New </span>
				</div>
		</td>	
	</tr>	
	<tr>
		<td class="fieldLabel_compulsory">Evidence Category:</td>
		<td><?php echo HTML::select('category', $dropdown_category, $vo->category, true, true); ?></td>
		<td>  
				<div style="margin:10px 0px 5px 0px">
					<span class="button" onclick="addCategory();"> Add New </span>
				</div>
		</td>	
	</tr>	
	<tr>
		<td class="fieldLabel_compulsory">Date:</td>
		<td><?php echo HTML::datebox('date', $vo->date, true); ?></td>
	</tr>
	<tr>
	<tr>
		<td class="fieldLabel_compulsory">Assessor:</td>
		<td><?php echo HTML::select('assessor', $dropdown_assessor, $vo->assessor, true, true); ?></td>

		<input type="hidden" name="tr_id" value="<?php echo htmlspecialchars((string)$vo->tr_id); ?>" />
		<input type="hidden" name="qualification_id" value="<?php echo htmlspecialchars((string)$vo->qualification_id); ?>" />
		<input type="hidden" name="framework_id" value="<?php echo htmlspecialchars((string)$vo->framework_id); ?>" />
		<input type="hidden" name="internaltitle" value="<?php echo htmlspecialchars((string)$internaltitle); ?>" />
		<input type="hidden" name="target" value="<?php echo htmlspecialchars((string)$target); ?>" />
		<input type="hidden" name="achieved" value="<?php echo htmlspecialchars((string)$achieved); ?>" />
	</tr>
<?php
			echo '<tr>';
			echo '<td width="140" class="fieldLabel_optional">Verified: </td>';
			if($vo->verified==1)
				echo '<td><input class="optional" type="checkbox" name="verified" checked value="0" /></td>';
			else
				echo '<td><input class="optional" type="checkbox" name="verified" value="1" /></td>';
			echo '</tr>';
?>
	<tr>
		<td class="fieldLabel_compulsory">Comments :</td>
		<td><textarea cols=40 rows=4 name="comments"><?php echo htmlspecialchars((string)$vo->comments ?: ''); ?></textarea></td>
	</tr>


</table>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>