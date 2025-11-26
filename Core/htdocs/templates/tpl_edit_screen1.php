<?php /* @var $vo Screen1 */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Goods Receipt Note</title>
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



	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Goods Receipt Note</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="cps" value="<?php echo $vo->cps ?>" />
	<input type="hidden" name="_action" value="save_screen1" />
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="170" />
		<tr>
			<td class="fieldLabel_optional">Date Received</td>
			<td><?php echo HTML::datebox('date_received', $vo->date_received, false); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional" valign="top">Location:</td>
			<td>
				<?php
				$locations = array(
					0=>array('location1', 'Location 1', null, null),
					1=>array('location2', 'Location 2', null, null),
					2=>array('location3', 'Location 3', null, null),
					3=>array('location4', 'Location 4', null, null),
					4=>array('location5', 'Location 5', null, null),
					5=>array('location6', 'Location 6', null, null),
					6=>array('location7', 'Location 7', null, null),
					7=>array('location8', 'Location 8', null, null),
					8=>array('location9', 'Location 9', null, null),
					9=>array('location10', 'Location 10', null, null),
					10=>array('location11', 'Location 11', null, null),
					11=>array('location12', 'Location 12', null, null),
					12=>array('location13', 'Location 13', null, null),
					13=>array('location14', 'Location 14', null, null),
					14=>array('location15', 'Location 15', null, null),
					15=>array('location16', 'Location 16', null, null),
					16=>array('location17', 'Location 17', null, null),
					17=>array('location18', 'Location 18', null, null),
					18=>array('location19', 'Location 19', null, null));
				echo HTML::select('location', $locations, $vo->location,'');
				?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">NATO No.:</td>
			<td><input class="optional" type="text" id = "nato" name="nato" value="<?php echo htmlspecialchars((string)$vo->nato); ?>" size="4" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Repair & Refurb / New Case:</td>
			<td>
				<?php
				$repair_list = array(
					0=>array('item1', 'item 1', null, null),
					1=>array('item2', 'item 2', null, null),
					2=>array('item3', 'item 3', null, null),
					3=>array('item4', 'item 4', null, null),
					4=>array('item5', 'item 5', null, null),
					5=>array('item6', 'item 6', null, null),
					6=>array('item7', 'item 7', null, null),
					7=>array('item8', 'item 8', null, null),
					8=>array('item9', 'item 9', null, null),
					9=>array('item10', 'item 10', null, null),
					10=>array('item11', 'item 11', null, null),
					11=>array('item12', 'item 12', null, null),
					12=>array('item13', 'item 13', null, null),
					13=>array('item14', 'item 14', null, null),
					14=>array('item15', 'item 15', null, null),
					15=>array('item16', 'item 16', null, null),
					16=>array('item17', 'item 17', null, null),
					17=>array('item18', 'item 18', null, null),
					18=>array('item19', 'item 19', null, null));
				echo HTML::select('repair_list', $repair_list, $vo->repair_list,'');
				?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Multi Part Pick List ?:</td>
			<td>
				<?php
				$multi_part = array(
					0=>array('0', 'No', null, null),
					1=>array('1', 'Yes', null, null));
				echo HTML::select('multi_part', $multi_part, $vo->multi_part,'');
				?>
			</td>
		</tr>

		<tr>
			<td class="fieldLabel_optional">Received From / AC:</td>
			<td><input class="optional" type="text" id = "recevied_from" name="recevied_from" value="<?php echo htmlspecialchars((string)$vo->recevied_from); ?>" /></td>
		</tr>

		<tr>
			<td class="fieldLabel_optional">Type:</td>
			<td>
				<?php
				$type = array(
					0=>array('type1', 'type 1', null, null),
					1=>array('type2', 'type 2', null, null),
					2=>array('type3', 'type 3', null, null),
					3=>array('type4', 'type 4', null, null),
					4=>array('type5', 'type 5', null, null),
					5=>array('type6', 'type 6', null, null),
					6=>array('type7', 'type 7', null, null),
					7=>array('type8', 'type 8', null, null),
					8=>array('type9', 'type 9', null, null),
					9=>array('type10', 'type 10', null, null),
					10=>array('type11', 'type 11', null, null),
					11=>array('type12', 'type 12', null, null),
					12=>array('type13', 'type 13', null, null),
					13=>array('type14', 'type 14', null, null),
					14=>array('type15', 'type 15', null, null),
					15=>array('type16', 'type 16', null, null),
					16=>array('type17', 'type 17', null, null),
					17=>array('type18', 'type 18', null, null),
					18=>array('type19', 'type 19', null, null));
				echo HTML::select('type', $type, $vo->type,'');
				?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">For Customer:</td>
			<td><input class="optional" type="text" id = "for_customer" name="for_customer" value="<?php echo htmlspecialchars((string)$vo->for_customer); ?>" /></td>
		</tr>

		<tr>
			<td class="fieldLabel_optional">Transport:</td>
			<td><input class="optional" type="text" id = "transport" name="transport" value="<?php echo htmlspecialchars((string)$vo->transport); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Advice Note No.:</td>
			<td><input class="optional" type="text" id = "advice_note" name="advice_note" value="<?php echo htmlspecialchars((string)$vo->advice_note); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">DMC:</td>
			<td><input class="optional" type="text" id = "DMC" name="DMC" value="<?php echo htmlspecialchars((string)$vo->DMC); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Pack Level:</td>
			<td><input class="optional" type="text" id = "pack_level" name="pack_level" value="<?php echo htmlspecialchars((string)$vo->pack_level); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Order No.:</td>
			<td><input class="optional" type="text" id = "order_no" name="order_no" value="<?php echo htmlspecialchars((string)$vo->order_no); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Description:</td>
			<td><textarea rows="3" cols="50" id="description" name="description"><?php echo htmlspecialchars((string)$vo->description); ?></textarea> </td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Note:</td>
			<td><input class="optional" type="text" id = "note" name="note" value="<?php echo htmlspecialchars((string)$vo->note); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Supp 640 No.:</td>
			<td><input class="optional" type="text" id = "supp_640" name="supp_640" value="<?php echo htmlspecialchars((string)$vo->supp_640); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Supp Cont No.:</td>
			<td><input class="optional" type="text" id = "supp_cont_no" name="supp_cont_no" value="<?php echo htmlspecialchars((string)$vo->supp_cont_no); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Warrant No.:</td>
			<td><input class="optional" type="text" id = "warrant_no" name="warrant_no" value="<?php echo htmlspecialchars((string)$vo->warrant_no); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Br 640 in:</td>
			<td>
				<?php
				$br_640_in = array(
					0=>array('0', 'No', null, null),
					1=>array('1', 'Yes', null, null));
				echo HTML::select('br_640_in', $br_640_in, $vo->br_640_in,'');
				?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Br 640 out:</td>
			<td>
				<?php
				$br_640_out = array(
					0=>array('0', 'No', null, null),
					1=>array('1', 'Yes', null, null));
				echo HTML::select('br_640_out', $br_640_out, $vo->br_640_out,'');
				?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">No & Type of Containers:</td>
			<td><input class="optional" type="text" id = "no_and_type_of_containers" name="no_and_type_of_containers" value="<?php echo htmlspecialchars((string)$vo->no_and_type_of_containers); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Contract TYpe:</td>
			<td>
				<?php
				$contract_type = array(
					0=>array('0', '', null, null),
					1=>array('1', 'Contract', null, null),
					2=>array('2', 'Non-Contract', null, null),
					3=>array('3', 'Permanent', null, null),
				);
				echo HTML::select('contract_type', $contract_type, $vo->contract_type,'');
				?>
			</td>
		</tr>
	</table>

</body>
</html>