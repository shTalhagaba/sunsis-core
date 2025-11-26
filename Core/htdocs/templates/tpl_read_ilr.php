<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Learner Record</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	
	<script language="JavaScript">
	function deleteRecord()
	{
		if(window.confirm("Delete This Learner?"))
		{
			window.location.replace('do.php?_action=delete_learner&id=<?php echo $vo->id; ?>');
		}
	}

	function qcaRefresh()
	{
		window.location.replace('do.php?_action=import_learner_ndaq&id=<?php echo rawurlencode($vo->id) ?>');
	}
	
	function expandAllUnits()
	{
		var divUnits = document.getElementById('divUnits');
		var divs = divUnits.getElementsByTagName('div');
		
		for(var i = 0; i < divs.length; i++)
		{
			if(divs[i].className == "UnitDetail")
			{
				showHideBlock(divs[i], true);
			}
		}
	}
	
	function collapseAllUnits()
	{
		var divUnits = document.getElementById('divUnits');
		var divs = divUnits.getElementsByTagName('div');
		
		for(var i = 0; i < divs.length; i++)
		{
			if(divs[i].className == "UnitDetail")
			{
				showHideBlock(divs[i], false);
			}
		}
	}	
	
	</script>
	
	<style type="text/css">
	div.Units
	{
		margin: 1px 0px 20px 20px;
		/* border: 1px orange dotted; */
		padding: 0px 10px 0px 0px;
	}
	
	div.UnitsTitle
	{
		padding: 2px;
		margin: 0px;
	}
	
	div.UnitsTitle span
	{
		font-weight:bold;
		font-size: 120%;
		color: #395596;
		/* background-color: #FDE3C1; */
		margin: 0px;
	}
	
	div.Unit
	{
		margin-top: 1px;
		margin-left:20px;
		margin-bottom:3px;
		border: 1px gray solid;
		-moz-border-radius: 5pt;
		width: 500px;
		padding: 3px;
		background-color: #FDF1E2;
	}

	div.UnitTitle
	{
		margin: 2px;
		cursor: pointer;
		padding: 2px;
		font-weight: bold;
		background-color: #FDE3C1;
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

<body>
<div class="banner">
	<div class="Title">Learner Record</div>
	<div class="ButtonBar">
		<button class="toolbarbutton" onclick="window.location.href='do.php?_action=view_learners'">Close</button>
		<button onclick="window.location.replace('do.php?_action=edit_ilr&id=<?php echo rawurlencode($vo->learnerinformation->L01 . $vo->learnerinformation->L03 . $vo->aims[0]->A09); ?>');">Submission</button>
		<button onclick="window.location.replace('do.php?_action=create_stream&id=<?php echo rawurlencode($vo->learnerinformation->L01 . $vo->learnerinformation->L03 . $vo->aims[0]->A09); ?>');">Stream</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>


<h3>Individual Learner Form <img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<form name="ilr" id="ilr" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="_action" value="save_course_structure" />
<input type="hidden" name="qan_before_editing" value="<?php echo htmlspecialchars((string)$vo->id); ?>" />

<table border="0" cellspacing="4" cellpadding="4">
	<col width="200"/><col />
	<tr>
		<td class="fieldLabel"> L25 LSC Codes </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L25); ?></td>
		<td class="fieldLabel"> L44 NES Delivery LSC </td> 
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L44); ?></td>
		<td class="fieldLable"> L01 Provider Number (UPIN) </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L01); ?></td>
	</tr>
	<tr>
		<td class="fieldLable">L03 Learner Reference Number </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L03); ?></td>
		<td class="fieldLable">L46 UK Provider Reference No.  </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L46); ?></td>
		<td class="fieldLable">L45 Unique Learner No. </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L45); ?></td>
		<tr> <td> &nbsp </td> </tr>

        <tr cellpadding="5" borders="all">           
               <td width=100% border=1 colspan=6 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - PART A - Learner Information - WBL/ESF/TtG </font> </b> </td>
        </tr> 
        <tr>
               <td colspan=6 align="center" valign="middle"> <b> <ul> Section 1  </ul> </b> </td>
        </tr>
        <tr>
		<td class="fieldLable">L09 Learner's Surname </td> 
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L09); ?></td>
		<td class="fieldLable">L10 Learner's Forenames </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L10); ?></td>
		<td class="fieldLable">L26 National Insurance No. </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L26); ?></td>
		</tr>
		<tr>		
		<td class="fieldLable">L17 Home Postcode (of Permanent Address before enrolment) </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L17); ?></td>
		<td class="fieldLabel">L11 Date of Birth </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L11); ?></td>
		<td valign="top" class="fieldLable"> L13 Sex (M or F) </td> 
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L13); ?></td>
 		</tr>
		<tr>
		<td class="fieldLable"> L18 House No./ Name and Street </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L18); ?></td>
		<td class="fieldLable"> L19 Suburb/ Village </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L19); ?></td>
		<td class="fieldLable"> L20 Town/ City </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L20); ?></td>
		</tr>
		<tr>
		<td class="fieldLable"> L21 County </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L21); ?></td>
		<td class="fieldLable">L22 Current Postcode </td> 
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L22); ?></td>
		<td class="fieldLable">L23 Contact Telephone No (inc STD Code) </td> 
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L23); ?></td>
		</tr>
        <tr>
		<td class="fieldLabel">L12 Ethnicity </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L12); ?></td>
		<td class="fieldLabel">L24 Country of Domicile </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L24); ?></td>
		</tr>
		<tr>
               <td colspan=6 align="center" valign="middle"> <b> <ul> Section 2  </ul> </b> </td>
        </tr>
        <tr> 
               <td colspan=6>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel">L14 Learning Difficulties/ Disabilities </td> 
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L14); ?></td>
		<td class="fieldLabel">L15 Disability or Health Problem </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L15); ?></td>
		<td class="fieldLabel">L16 Learning Difficulty </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L16); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel">L35 Prior Attainment Level </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L35); ?></td>
		<td class="fieldLabel">L34 Learning Support Reasons </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L34a) . "-" . htmlspecialchars((string)$vo->learnerinformation->L34b) . "-" . htmlspecialchars((string)$vo->learnerinformation->L34c) . "-" . htmlspecialchars((string)$vo->learnerinformation->L34d); ?></td>
		<td class="fieldLabel">L36 Status on day prior to learning </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L36); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel">L37 Status on First Day of Learning </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L37); ?></td>
		<td class="fieldLabel">L47 Current Employment Status </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L47); ?></td>
		<td class="fieldLabel">L48 Date Employment Status Changed </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L48); ?></td>
		</tr>
        <tr>
               <td colspan=6 align="center" valign="middle"> <b> <ul> Section 3  </ul> </b> </td>
        </tr>
        <tr>
               <td colspan=6>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel">L28 Eligibility for Enhanced Funding </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L28a) . "-" . htmlspecialchars((string)$vo->learnerinformation->L28b); ?></td>
		<td class="fieldLabel">L39 Destination </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L39); ?></td>
		</tr> <tr>
		<td class="fieldLabel">L40 National Aim Learner Monitoring </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L40a); ?></td>
		<td class="fieldLabel">L40 National Aim Learner Monitoring </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L40b); ?></td>
        </tr>
		
		<tr>
		<td class="fieldLabel"> L41 Local LSC Learner Monitoring </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L41a); ?> </td>
		<td class="fieldLabel"> L41 Local LSC Learner Monitoring </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L41b); ?> </td>
		</tr>
		
		<tr>
		<td class="fieldLabel"> L42 Provider Specified Learner Data </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L42a); ?> </td>
		<td class="fieldLabel"> L42 Provider Specified Learner Data  </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learnerinformation->L42b); ?> </td>
		</tr>
        </tr>
		<tr><td> &nbsp </td></tr>		
        <tr cellpadding="5" borders="all">           
               <td colspan=6 border=1 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - Aims Information </font> </b> </td>
        </tr> 
        <tr><td>&nbsp;</td></tr>
        <tr> 
		<td class="fieldLabel"> A02 Contract Type </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A02); ?></td>
		<td class="fieldLabel">A01 Provider Number (UPIN) </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A01); ?></td>
		<td class="fieldLabel">A03 Learner Reference Number </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A03); ?></td>
       	</tr> 
     	<tr>   
		<td class="fieldLabel"> A56 UK Provider reference Number  </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A56); ?></td>
		<td class="fieldLabel"> A55 Unique Learner Number </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A55); ?></td>
		<tr><td> &nbsp </td></tr>		
        <tr cellpadding="5" borders="all">           
               <td colspan=6 border=1 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - Part B - Main Aim Information - WBL/ESF/TtG </font> </b> </td>
        </tr> 
        <tr>
               <td colspan=6 align="center" valign="middle"> <b> <ul> Section 6 Main Aim - Start  </ul> </b> </td>
        </tr>
        <tr>
               <td colspan=6>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel"> A09 Learning Aim Reference Number </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A09); ?></td>
		<td class="fieldLabel"> A10 LSC Funding Stream </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A10); ?></td>
		<td class="fieldLabel"> A15 Programme Type </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A15); ?></td>
		</tr>
        <tr>
		<td class="fieldLabel"> A16 Programme Entry Route </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A16); ?></td>
		<td class="fieldLabel"> A18 Main Delivery Method </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A18); ?></td>
		<td class="fieldLabel"> A27 Learning Start Date </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A27); ?></td>
        </tr>
        <tr>
		<td class="fieldLabel"> A28 Planned End Date </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A28); ?></td>
		<td class="fieldLabel"> A46 National Learning Aim Monitoring </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A46a); ?></td>
		<td class="fieldLabel"> A46 National Learning Aim Monitoring </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A46b); ?></td>
        </tr>
        <tr>
		<td class="fieldLabel"> A24 SOC Code </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A24); ?></td>
		<td class="fieldLable"> A26 Sector Code </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A26); ?></td>
		<td class="fieldLable"> A32 Guided Learning Hours </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A32); ?></td>
        </tr>
		<tr>     	
		<td class="fieldLable"> A51a Proportion of Funding </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A51a); ?></td>
		<td class="fieldLable"> A54 Broker Contract No. </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A54); ?></td>
		<td class="fieldLabel"> A53 Additional Learning/ Social Needs </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A53); ?></td>
        </tr>
        <tr>
		<td class="fieldLable"> A49 Special Projects and Pilots </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A49); ?></td>
		<td class="fieldLable"> A44 Employer Identifier </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A44); ?></td>
		<td class="fieldLable"> A45 Workplace Location Postcode </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A45); ?></td>
        </tr>
        <tr>
		<td class="fieldLable"> A23 Delivery Location Postcode </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A23); ?></td>
		<td class="fieldLabel"> A06 ESF Data Set </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A06); ?></td>
        </tr>
        <tr>
        <td colspan=6 align="center" valign="middle"> <b> <ul> Section 7 Main Aim - End Information  </ul> </b> </td>
        </tr>
        <tr>
        <td colspan=6>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel"> A40 Achievement Date </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A40); ?></td>
		<td class="fieldLabel"> A31 Learning Actual End Date </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A31); ?></td>
		<td class="fieldLabel"> A34 Completion Status </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A34); ?></td>
        </tr>
        <tr>
		<td class="fieldLabel"> A35 Learning Outcome </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A35); ?></td>
		<td class="fieldLabel"> A37 No. of units completed from a learning aim </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A37); ?></td>
		<td class="fieldLabel"> A38 Total number of units required to achieve full learning aim </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A38); ?></td>
        </tr>
        <tr>
		<td class="fieldLabel"> A50 Reason Learning Ended </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A50); ?></td>
		<td class="fieldLabel"> A43 Framework Achievement Date </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A43); ?></td>
<!-- 	<td class="fieldLabel"> ILR Submission Date </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->submission_date); ?></td> -->
        </tr>
        <tr>
		<td class="fieldLabel"> A47 Local LSC Learning Aim Monitoring </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A47a); ?> </td>
		<td class="fieldLabel"> A47 Local LSC Learning Aim Monitoring </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A47b); ?> </td>
        </tr>

        <tr>
		<td class="fieldLabel"> A48 Provider Specified Learning Aim Data </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A48a); ?> </td>
		<td class="fieldLabel"> A48 Provider Specified Learning Aim Data </td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->A48b); ?> </td>
        </tr>
		</table>

		<?php 
		if($vo->aims[0]->A06=='01')
		{ ?>
					<table rules="none" width=100%>
			        <tr>
			               <td colspan=3>&nbsp;</td>
			        </tr>
			        <tr cellpadding="5" borders="all">           
			               <td width=100% colspan=3 border=1 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - Part D - Co-Financing Information </font> </b> </td>
			        </tr> 
			        <tr><td>&nbsp;</td></tr>
			        <tr>
			               <td width=100% colspan=3 align="center" valign="middle"> <b> <ul> Section 10 ESF Co-Financing - Start </ul> </b> </td>
			        </tr>
			        <tr>
			               <td colspan=3>&nbsp;</td>
			        </tr>
			        <tr>
					<td class="fieldLabel"> E01 Provider Number (UPIN) </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E01); ?> </td>
					<td class="fieldLabel">E03 Learner Reference Number </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E03); ?> </td>
					</tr>
					<tr>
					<td class="fieldLabel"> E24 Unique Learner Number </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E24); ?> </td>
					<td class="fieldLabel"> E25 UK Provider reference Number </td> 
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E25); ?> </td>
					</tr>
			        <tr>
					<td class="fieldLabel"> E08 Date Started ESF Co-financing  </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E08); ?> </td>
					<td class="fieldLabel"> E22 Project Dossier Number </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E22); ?> </td>
					</tr>		
			        <tr>
					<td class="fieldLabel"> E09 Planned End for ESF Co-financing </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E09); ?> </td>
					<td class="fieldLabel"> E23 Local Project No. </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E23); ?> </td>
					</tr>		
					<tr>        
					<td class="fieldLabel"> E11 Industrial Sector of Learner's Employer </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E11); ?> </td>
					<td class="fieldLabel"> E18 Delivery Mode </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E18a) . "-" . htmlspecialchars((string)$vo->aims[0]->E18b) . "-" . htmlspecialchars((string)$vo->aims[0]->E18c) . "-" . htmlspecialchars((string)$vo->aims[0]->E18d); ?> </td>
					</tr>		
					<tr>        
					<td class="fieldLabel_"> E12 Employment Status on day before starting ESF/ CFD Project </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E12); ?> </td>
					<td class="fieldLabel"> E15 Type and size of Learner's Employer </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E15); ?> </td>
					</tr>		
					<tr>        
					<td class="fieldLabel"> E19 Support measures to be accessed by the learner </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E19a) . "-" . htmlspecialchars((string)$vo->aims[0]->E19b) . "-" . htmlspecialchars((string)$vo->aims[0]->E19c) . "-" . htmlspecialchars((string)$vo->aims[0]->E19d) . "-" . htmlspecialchars((string)$vo->aims[0]->E19e); ?> </td>
					<td class="fieldLabel"> E13 Learner's Employment Status </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E13); ?> </td>
					</tr>
					<tr>		
					<td class="fieldLabel"> E20 Learner Background </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E20a); ?> </td>
					<td class="fieldLabel"> E20 Learner Background </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E20b); ?> </td>
					</tr>		
					<tr>        
					<td class="fieldLabel"> E20 Learner Background </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E20c); ?> </td>
					<td class="fieldLabel"> E14 Length of unemployment before starting ESF Project </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E14); ?> </td>
					</tr>		
					<tr>        
					<td class="fieldLabel"> E16 Addressing gender stereotyping </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E16a) . "-" . htmlspecialchars((string)$vo->aims[0]->E16b) . "-" . htmlspecialchars((string)$vo->aims[0]->E16c) . "-" . htmlspecialchars((string)$vo->aims[0]->E16d) . "-" . htmlspecialchars((string)$vo->aims[0]->E16e); ?> </td>
					<td class="fieldLabel"> E21 Support measures for learners with disabilities </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E21); ?> </td>

					</tr>
			        <tr><td>&nbsp;</td></tr>
			        <tr>
			               <td width=100% colspan=3 align="center" valign="middle"> <b> <ul> Section 11 ESF Co-Financing - End </ul> </b> </td>
			        </tr>
			        <tr>
			               <td colspan=3>&nbsp;</td>
			        </tr>
			        <tr>
					<td class="fieldLabel_compulsory"> E10 Date ended ESF Co-financing </td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->aims[0]->E10); ?> </td>
		        <tr>
		               <td colspan=3>&nbsp;</td>
		        </tr>
		
				</table>
		<?php
			
		}
		?>
		
		<?php 
		for($a=1; $a<=$vo->subaims; $a++)
		{
	   				echo "<table>";
			        echo "<tr cellpadding='5' borders='all'> ";           
			        echo "<td border=1 colspan=6 bgcolor='darkseagreen' align='Center'> <b> <font size=3> Individual Learner Record - Part C - Subsidiary Aim Information - WBL/ESF </font> </b> </td>";
			        echo "</tr>";
					echo "<tr>";
                    echo "<td colspan=6 align='center' valign='middle'> <b> <ul> Section 8 Subsidiary Aim (Including Technical Certificates and Key Skills)  </ul> </b> </td>";
			        echo "</tr>";
            		echo "<tr>";
					echo "<td colspan=6>&nbsp;</td>";
                    echo "</tr>";
                    echo "<tr>";
					echo "<td class='fieldLable'> A09 Learning Aim Reference Number </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A09); echo "</td>";
					echo "<td class='fieldLabel'> A10 LSC Funding Stream </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A10); echo "</td>";
					echo "<td class='fieldLabel'> A15 Programme Type </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A15); echo "</td>";
					echo "</tr> </tr>";
      			    echo "<td class='fieldLabel'> A16 Programme Entry Route </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A16); echo "</td>";
      			    echo "<td class='fieldLabel'> A27 Learning Start Date </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A27); echo "</td>";
   					echo "<td class='fieldLable'> A26 Sector Code </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A26); echo "</td>";
   					echo "</tr><tr>";
					echo "<td class='fieldLabel'> A28 Planned End Date </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A28); echo "</td>";
					echo "<td class='fieldLabel'> A06 ESF Data Set </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A06); echo "</td>";
					echo "<td class='fieldLabel'> A53 Additional Learning/ Social Needs </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A53); echo "</td>";
				    echo "</tr><tr>";
					echo "<td class='fieldLabel'> A46 National Learning Aim Monitoring </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A46a) . "-" . htmlspecialchars((string)$vo->aims[$a]->A46b) ; echo "</td>";
					echo "<td class='fieldLable'> A51a Proportion of Funding </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A51a); echo "</td>";
					echo "</tr><tr>";
					echo "<td class='fieldLabel'> A40 Achievement Date </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A40); echo "</td>";
					echo "<td class='fieldLabel'> A31 Learning Actual End Date </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A31); echo "</td>";
					echo "<td class='fieldLabel'> A34 Completion Status </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A34); echo "</td>";
					echo "</tr><tr>";
					echo "<td class='fieldLabel'> A35 Learning Outcome </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A35); echo "</td>";
					echo "<td class='fieldLable'> A37 No. of units completed from a learning aim </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A37); echo "</td>";
					echo "</tr><tr>";
					echo "<td class='fieldLable'> A38 Total number of units required to achieve full learning aim </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A38); echo "</td>";
					echo "<td class='fieldLabel'> A50 Reason Learning Ended </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A50); echo "</td>";
					echo "</tr><tr>";
					echo "<td class='fieldLable'> A47 Local LSC Learning Aim Monitoring </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A47a); echo "</td>";
					echo "<td class='fieldLabel'> A47 Local LSC Learning Aim Monitoring </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A47b); echo "</td>";
					echo "</tr><tr>";
					echo "<td class='fieldLable'> A48 Provider Specified Learning Aim Data </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A48a); echo "</td>";
					echo "<td class='fieldLabel'> A48 Provider Specified Learning Aim Data </td>";
					echo "<td class='fieldValue'>"; echo htmlspecialchars((string)$vo->aims[$a]->A48b); echo "</td>";
					echo "</tr>	</table>";

		} ?>	
					
					
      			</div>	
<input id="counter" type="hidden" value="0">

</form>








<!-- 

<h3>Grades &amp; Performance Figures</h3>
<p class="sectionDescription">This qualification's contribution towards school attainment targets</p>
<?php
if(!is_null($vo->grades) && (count($vo->grades) > 0) )
{
	echo <<<HEREDOC
<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
<tr>
	<th>Grade</th>
	<th>Level 1 threshold (%)</th>
	<th>Level 1 &amp; 2 threshold (%)</th>
	<th>Level 3 threshold (%)</th>
	<th>Points</th>
</tr>
HEREDOC;

	foreach($vo->grades as $grade)
	{
		echo '<tr><td align="left">'.htmlspecialchars((string)$grade->grade).'</td>';
		echo '<td align="center" style="color:' . ($grade->levela_threshold == 0 ? 'silver':'') . '">'.htmlspecialchars((string)$grade->levela_threshold).'</td>';
		echo '<td align="center" style="color:' . ($grade->levela_andb_threshold == 0 ? 'silver':'') . '">'.htmlspecialchars((string)$grade->levela_andb_threshold).'</td>';
		echo '<td align="center" style="color:' . ($grade->levelc_threshold == 0 ? 'silver':'') . '">'.htmlspecialchars((string)$grade->levelc_threshold).'</td>';
		echo '<td align="center" style="color:' . ($grade->points == 0 ? 'silver':'') . '">'.htmlspecialchars((string)$grade->points).'</td></tr>';
	}
	echo '</table>';
}
?>

<h3>Assessment Method</h3>
<?php
if($vo->assessment_method != '')
{
	
	echo '<p class="sectionDescription">'.str_replace("\n", '</p><p class="sectionDescription">', htmlspecialchars((string)$vo->assessment_method)).'</p>';
}
?>

<h3>Structure</h3>
<?php echo '<p class="sectionDescription">'.str_replace("\n", '</p><p class="sectionDescription">', htmlspecialchars((string)$vo->structure)).'</p>'; ?>

<h3>Units</h3>
<p style="margin-left: 10px"><span class="button" onclick="expandAllUnits();return false;">Expand all units</span>&nbsp;<span class="button" onclick="collapseAllUnits(); return false;">Collapse all units</span></p>

<div id="divUnits"><?php echo $structureHTML ?></div>

<br/>
<br/>
-->
</body>
</html>