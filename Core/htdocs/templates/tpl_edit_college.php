<?php /* @var $vo College */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>College</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	<script language="JavaScript">
		function save()
		{

			document.getElementById('organisation_type').value = <?php echo $vo->organisation_type; ?>;

			var myForm = document.forms[0];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			var illegal_characters = /[*,\/]/;

			var legal_name = myForm.elements['legal_name'];
			if(illegal_characters.test(legal_name.value))
			{
				alert("Full name may not contain '/', ',' or '*' characters");
				legal_name.focus();
				return false;
			}

			myForm.submit();
		}

		function trading_name_onfocus(trading_name)
		{
			if(trading_name.value == '')
			{
				trading_name.value = trading_name.form.elements['legal_name'].value;
			}
		}

		function short_name_onfocus(short_name)
		{
			if(short_name.value == '')
			{
				short_name.value = short_name.form.elements['legal_name'].value.substring(0, 13);
			}
		}

		function populate()
		{
			var grid_level = document.getElementById('grid_level');
			grid_level.clear();
			var ty = "<?php echo $vo->organisation_type;?>";
			grid_level.setValues(ty.split(','));
		}

	</script>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Name</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="organisation_type" id="organisation_type" value="7"/>
	<input type="hidden" name="_action" value="save_college" />
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="140" />
		<tr>
			<td class="fieldLabel_compulsory">College name:</td>
			<td><input class="compulsory" type="text" name="legal_name" value="<?php echo htmlspecialchars((string)$vo->legal_name); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Trading Name:</td>
			<td><input class="compulsory" type="text" name="trading_name" value="<?php echo htmlspecialchars((string)$vo->trading_name); ?>" size="40" onfocus="trading_name_onfocus(this);" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory"><abbr title="A space saver for use in views the shorter the better">Abbreviation</abbr>:</td>
			<td><input class="compulsory" type="text" name="short_name" value="<?php echo htmlspecialchars((string)$vo->short_name); ?>" size="12" maxlength="12" onfocus="short_name_onfocus(this);"/>
			<span style="color:gray;font-style:italic">12 letters or fewer</span></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">UKPRN:</td>
			<td><input class="optional" type="text" name="ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn); ?>" size="40" /></td>
			<td><a href="https://www.ukrlp.co.uk/ukrlp/ukrlp_provider.page_pls_searchProviders" target="_blank"><img src="/images/external.png"></a></td>
		</tr>
	</table>
</form>
</body>
</html>