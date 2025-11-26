<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Person</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script language="JavaScript" src="/common.js"></script>
<script src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
$(document).ready(function() 
	    {
	        $("#dataMatrix").tablesorter(); 
	    } 
	); 
	   
</script>

</head>

<body>
<div class="banner">
	<div class="Title">KPI Report Overview</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location=''" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>			</td>
	</div>
</div>

<div class="banner">
	<table border=0 cellspacing="5" cellpadding="0" height="100%" width="100%">
		<tr>
			<td valign="top">KPI Report Overview</td>
			<td valign="top" align="right" class="Timestamp"></td>
		</tr>
		<tr>
			<td valign="bottom" align="left">
			</td>
			<td valign="bottom" align="right">
				<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
				<button onclick="window.location=''" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
				<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>			</td>
		</tr>
	</table>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php 

if(!isset($_REQUEST['p']))
{

?>
<h3>List of Reports</h3>

<ul class="formlist">
	<li class="el">
		<a href="?_action=kpi_report_overview&amp;p=1">Early leavers report</a>
		Shows all learners who have finished within the first 6 weeks of their qualification
	</li>
	
	<li class="fa">
		<a href="?_action=kpi_report_overview&amp;p=2">Framework Achievers</a>
		Shows all learners who have completed all the aims within the qualification they are taking
	</li>	
	
	<li class="la"><a href="?_action=kpi_report_overview&amp;p=3">Learner achievements</a>
	Shows all non-achieving learners and how many of their aims they have completed</li>	
	
	<li class="sla"><a href="?_action=kpi_report_overview&amp;p=4">Starters / Leavers / Achievers</a>
	Shows the total number of learners who have started/left/acheived in each submission period</li>
	
	<li class="tl"><a href="?_action=kpi_report_overview&amp;p=5">Transferred learners</a>
	All learners taking a qualification who have transferred from a different provider</li>
	
	<li class="ufl"><a href="?_action=kpi_report_overview&amp;p=6">Unfunded learners</a>
	Shows all learners who are currently being unfunded</li>
</ul>

<?php 
}
else
{
	switch($_REQUEST['p'])
	{
		case 1:
			?>

<h3>Early leavers report</h3><p>Shows all learners who have finished within the first 28 days of their qualification as of 2009-08-03</p><p>Total Rows: 9</p><table class="resultset" cellpadding="6" id="dataMatrix"><thead><tr><th class="topRow">Name</th><th class="topRow">Reference Number</th><th class="topRow">Date Of Birth</th><th class="topRow">Gender</th><th class="topRow">Start Date</th><th class="topRow">Leave Date</th><th class="topRow">Target Completion Date</th><th class="topRow">Days Left Early</th></tr></thead><tbody><tr><td><?php echo $this->randomName(); ?></td><td>QT31215590QE</td><td>1976-11-02</td><td>Male</td><td>2008-07-09</td><td>2008-08-13</td><td>2008-10-09</td><td>35</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>QT31216190TE</td><td>1989-03-22</td><td>Female</td><td>2008-07-09</td><td>2008-08-13</td><td>2008-10-09</td><td>35</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>JT85215440PJ</td><td>1980-09-27</td><td>Male</td><td>2008-08-05</td><td>2008-10-14</td><td>2008-12-05</td><td>70</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>MT04326782PJ</td><td>1981-02-15</td><td>Male</td><td>2008-11-05</td><td>2009-01-06</td><td>2009-05-05</td><td>62</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>OT95220068WH</td><td>1976-05-01</td><td>Female</td><td>2008-08-13</td><td>2008-10-08</td><td>2008-12-13</td><td>56</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>QT34312506UG</td><td>1971-03-27</td><td>Male</td><td>2008-09-18</td><td>2008-12-08</td><td>2009-03-18</td><td>81</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>JT85214640TJ</td><td>1985-03-07</td><td>Male</td><td>2008-08-05</td><td>2008-09-03</td><td>2008-12-05</td><td>29</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>HT11275929RE</td><td>1974-10-06</td><td>Male</td><td>2008-09-01</td><td>2008-10-09</td><td>2009-03-01</td><td>38</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>GT15270321SI</td><td>1949-03-01</td><td>Male</td><td>2008-09-17</td><td>2008-12-10</td><td>2009-12-17</td><td>84</td></tr></tbody></table>
			<?php
			break; 
			
		case 2:
			?>

<h3>Framework achievers</h3><p>Shows all learners who have completed all the aims within the framework they are taking as of 2009-08-03</p><p>Total Rows: 1235</p><table class="resultset" cellpadding="6" id="dataMatrix"><thead><tr><th class="topRow">Name</th><th class="topRow">Reference Number</th><th class="topRow">Date Of Birth</th><th class="topRow">Gender</th><th class="topRow">Framework</th><th class="topRow">Start Date</th><th class="topRow">Target Completion Date</th><th class="topRow">Actual Completion Date</th><th class="topRow">Days Finished Early</th></tr></thead><tbody><tr><td><?php echo $this->randomName(); ?></td><td>CS31030659TF</td><td>1979-03-19</td><td>Male</td><td>CG NVQ2 Carry and Deliver Goods 0809</td><td>2009-01-20</td><td>2009-05-20</td><td>2009-02-05</td><td>104</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>JT85217540QJ</td><td>1984-10-15</td><td>Male</td><td>EAL NVQ2 PMO 0809</td><td>2008-08-06</td><td>2008-12-06</td><td>2008-10-20</td><td>47</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000686</td><td>1987-10-08</td><td>Male</td><td>BS Adult Numeracy Level 2</td><td>2009-05-20</td><td>2009-05-21</td><td>2009-05-21</td><td>0</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>LS65011286UG</td><td>1986-01-07</td><td>Male</td><td>BS Adult Literacy Level 1</td><td>2009-01-05</td><td>2009-01-13</td><td>2009-01-13</td><td>0</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>NT05331477RI</td><td>1950-09-20</td><td>Male</td><td>CG NVQ2 Carry and Deliver Goods 0809</td><td>2008-09-23</td><td>2009-03-23</td><td>2008-10-31</td><td>143</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>QS50114994SH</td><td>1974-02-09</td><td>Male</td><td>CG NVQ2 Warehousing and Storage 0809</td><td>2009-03-31</td><td>2009-09-30</td><td>2009-04-08</td><td>175</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000207</td><td>1978-10-04</td><td>Male</td><td>BS Adult Literacy Level 1</td><td>2009-02-09</td><td>2009-02-10</td><td>2009-02-10</td><td>0</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000208</td><td>1978-10-04</td><td>Male</td><td>BS Adult Numeracy Level 2</td><td>2009-02-11</td><td>2009-02-12</td><td>2009-02-12</td><td>0</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000234</td><td>1975-09-23</td><td>Male</td><td>BS Adult Literacy Level 2</td><td>2009-02-09</td><td>2009-02-10</td><td>2009-02-10</td><td>0</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>JT85218040OJ</td><td>1985-10-10</td><td>Male</td><td>EAL NVQ2 PMO 0809</td><td>2008-08-06</td><td>2008-12-06</td><td>2008-09-30</td><td>67</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>JT85218440NJ</td><td>1983-07-06</td><td>Male</td><td>EAL NVQ2 PMO 0809</td><td>2008-08-06</td><td>2008-12-06</td><td>2008-11-05</td><td>31</td></tr>		
</tbody>
</table>

			<?php
			break; 

		case 3:
			?>

<h3>Learner achievements</h3><p>Shows all non-achieving learners and how many of their aims they have completed as of 2009-08-03</p><p>Total Rows: 477</p><table class="resultset" cellpadding="6" id="dataMatrix"><thead><tr><th class="topRow">Name</th><th class="topRow">Reference Number</th><th class="topRow">Date Of Birth</th><th class="topRow">Gender</th><th class="topRow">Framework Id</th><th class="topRow">Total Aims</th><th class="topRow">Total Completed</th></tr></thead><tbody><tr><td><a href="do.php?_action=read_training_record&id=113"><?php echo $this->randomName(); ?></a></td><td>JT85217540QJ</td><td>1984-10-15</td><td>Male</td><td>1</td><td>1</td><td>0</td></tr><tr><td><a href="do.php?_action=read_training_record&id=115"><?php echo $this->randomName(); ?></a></td><td>NT05331477RI</td><td>1950-09-20</td><td>Male</td><td>3</td><td>1</td><td>0</td></tr><tr><td><a href="do.php?_action=read_training_record&id=116"><?php echo $this->randomName(); ?></a></td><td>JT85218040OJ</td><td>1985-10-10</td><td>Male</td><td>1</td><td>1</td><td>0</td></tr><tr><td><a href="do.php?_action=read_training_record&id=117"><?php echo $this->randomName(); ?></a></td><td>JT85218440NJ</td><td>1983-07-06</td><td>Male</td><td>1</td><td>1</td><td>0</td></tr><tr><td><a href="do.php?_action=read_training_record&id=118"><?php echo $this->randomName(); ?></a></td><td>JT85218940UJ</td><td>1979-06-05</td><td>Male</td><td>1</td><td>3</td><td>1</td></tr><tr><td><a href="do.php?_action=read_training_record&id=120"><?php echo $this->randomName(); ?></a></td><td>JT81303102PI</td><td>1976-05-08</td><td>Male</td><td>3</td><td>1</td><td>0</td></tr><tr><td><a href="do.php?_action=read_training_record&id=1067"><?php echo $this->randomName(); ?></a></td><td>000000000216</td><td>1973-08-24</td><td>Male</td><td>5</td><td>1</td><td>0</td></tr><tr><td><a href="do.php?_action=read_training_record&id=122"><?php echo $this->randomName(); ?></a></td><td>OT95221068UH</td><td>1984-01-02</td><td>Male</td><td>1</td><td>1</td><td>0</td></tr><tr><td><a href="do.php?_action=read_training_record&id=1643"><?php echo $this->randomName(); ?></a></td><td>000000000780</td><td>1966-11-01</td><td>Male</td><td>3</td><td>1</td><td>0</td></tr>			
</tbody>
</table>

			<?php
			break; 

		case 4:
			?>

<style type="text/css">
#dataMatrix td { cursor: hand; }
</style>
<h3>Starters / Leavers / Achievers</h3>
<p>Shows the total number of learners who have started/left/acheived in each submission period</p>
<table class="resultset" cellpadding="6" id="dataMatrix">
<thead>
	<tr class="topRow">
		<td></td>
		<td colspan="3">Aug</td>
		<td colspan="3">Sep</td>
		<td colspan="3">Oct</td>
		<td colspan="3">Nov</td>
		<td colspan="3">Dec</td>
		<td colspan="3">Jan</td>
		<td colspan="3">Feb</td>
		<td colspan="3">Mar</td>
		<td colspan="3">Apr</td>
		<td colspan="3">May</td>
		<td colspan="3">Jun</td>
		<td colspan="3">Jul</td>
	</tr>
	<tr class="topRow">
		<td>Submission Period</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>
		<td title="Starters">S</td>
		<td title="Leavers">L</td>
		<td title="Achievers">A</td>																						
	</tr>				
</thead>
<tbody>
	<tr>
		<td>W01</td>
		<td title="Click here to view the individual learners">10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>
		<td>10</td>
		<td>0</td>
		<td>7</td>																						
	</tr>
	<tr>
		<td>W02</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>
		<td>12</td>
		<td>2</td>
		<td>9</td>																						
	</tr>
	<tr>
		<td>W03</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
		<td>20</td>
		<td>5</td>
		<td>13</td>
	</tr>
	<tr>
		<td>W04</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>	
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>
		<td>50</td>
		<td>0</td>
		<td>13</td>																					
	</tr>
	<tr>
		<td>W05</td>
		<td colspan="36" align="center">n/a</td>
	</tr>	
	<tr>
		<td>W06</td>
		<td colspan="36" align="center">n/a</td>
	</tr>
	<tr>
		<td>W07</td>
		<td colspan="36" align="center">n/a</td>
	</tr>
	<tr>
		<td>W08</td>
		<td colspan="36" align="center">n/a</td>
	</tr>
	<tr>
		<td>W09</td>
		<td colspan="36" align="center">n/a</td>
	</tr>
	<tr>
		<td>W10</td>
		<td colspan="36" align="center">n/a</td>
	</tr>
	<tr>
		<td>W11</td>
		<td colspan="36" align="center">n/a</td>
	</tr>
	<tr>
		<td>W12</td>
		<td colspan="36" align="center">n/a</td>
	</tr>						
</tbody>
</table>

			<?php
			break; 
			
		case 5:
			?>

<h3>Transferred Learners</h3>
<p>All learners taking a qualification who have transferred from a different provider as of 2009-08-03</p>
<table class="resultset" cellpadding="6" id="dataMatrix">
<thead>
	<tr>
		<th>Name</th>
		<th>Reference #</th>
		<th>DOB</th>
		<th>Gender</th>
		<th>Transfer Date</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>Hughes, Jane</td>
		<td>12145678</td>
		<td>01/01/1990</td>
		<td>Male</td>
		<td>01/01/2009</td>
	</tr>
	<tr>
		<td>Cox, Andrew</td>
		<td>12245678</td>
		<td>21/09/1990</td>
		<td>Male</td>
		<td>01/01/2009</td>
	</tr>	
	<tr>
		<td>Robertson, Jane</td>
		<td>12345678</td>
		<td>30/03/1990</td>
		<td>Male</td>
		<td>01/01/20099</td>
	</tr>	
	<tr>
		<td>Taylor, James</td>
		<td>12445678</td>
		<td>11/01/1990</td>
		<td>Male</td>
		<td>01/01/2009</td>
	</tr>
	<tr>
		<td>Taylor, Louise</td>
		<td>22445678</td>
		<td>11/01/1990</td>
		<td>Male</td>
		<td>01/01/2009</td>
	</tr>	
	<tr>
		<td>Robertson, Jane</td>
		<td>22445678</td>
		<td>11/01/1990</td>
		<td>Male</td>
		<td>01/01/2009</td>
	</tr>			
</tbody>
</table>

			<?php
			break; 

		case 6:
			?>

<h3>Unfunded learners</h3><p>Shows all learners who are currently being unfunded as of 2009-08-03</p><p>Total Rows: 39</p><table class="resultset" cellpadding="6" id="dataMatrix"><thead><tr><th class="topRow">Name</th><th class="topRow">Reference Number</th><th class="topRow">Date Of Birth</th><th class="topRow">Gender</th><th class="topRow">Start Date</th><th class="topRow">Target Completion Date</th><th class="topRow">Unfunded Days</th></tr></thead><tbody><tr><td><?php echo $this->randomName(); ?></td><td>000000000216</td><td>1973-08-24</td><td>Male</td><td>2009-01-29</td><td>2009-04-29</td><td>96</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000627</td><td>1977-05-23</td><td>Male</td><td>2009-04-20</td><td>2009-07-20</td><td>14</td></tr><tr><td>Blackwell, Sean</td><td>000000000787</td><td>1954-12-02</td><td>Male</td><td>2009-01-22</td><td>2009-07-22</td><td>12</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000012</td><td>1959-05-19</td><td>Male</td><td>2009-03-02</td><td>2009-06-02</td><td>62</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000786</td><td>1984-07-08</td><td>Male</td><td>2009-01-22</td><td>2009-07-22</td><td>12</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000785</td><td>1951-02-10</td><td>Male</td><td>2009-01-28</td><td>2009-07-28</td><td>6</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000678</td><td>1953-10-27</td><td>Male</td><td>2009-02-16</td><td>2009-04-16</td><td>109</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>RT14353133RF</td><td>1963-10-20</td><td>Male</td><td>2008-12-10</td><td>2009-06-10</td><td>54</td></tr><tr><td><?php echo $this->randomName(); ?></td><td>000000000652</td><td>1970-03-23</td><td>Male</td><td>2009-02-20</td><td>2009-06-20</td><td>44</td></tr>	
</tbody>
</table>

			<?php
			break; 			
	}
}
?>

</body>
</html>