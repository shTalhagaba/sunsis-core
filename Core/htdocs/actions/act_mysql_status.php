<?php
class mysql_status implements IAction
{
	public function execute(PDO $link)
	{
		if(!SOURCE_LOCAL && !SOURCE_BLYTHE_VALLEY && !SOURCE_HOME){
			throw new UnauthorizedException();
		}

		unset($link);
		$link = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
		if(!$link){
			throw new Exception("Connection to database '" . DB_NAME . "' failed. ". mysqli_connect_error(), mysqli_connect_errno());
		}
			    
		define("BYTES_TO_MEGABYTES", (1024 * 1024));

		$totalSize = 0;
		$totalData = 0;
		$totalRecords = 0;
		$totalIndex = 0;

		$status = preg_replace('#([0-9] )#', '\1<br/>', $link->stat());
		
		require('tpl_mysql_status.php');
		
	}
	
	
	
	private function renderTableSizes(mysqli $link)
	{
		echo <<<HEREDOC
<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
	<thead>
	<tr>
		<th>Database Table</th>
		<th>Engine</th>
		<th>Rows</th>
		<th>Data (MB)</th>
		<th>Index (MB)</th>
		<th>Total (MB)</th>
	</tr>
	</thead>
HEREDOC;

		$totalSize = 0;
		$totalData = 0;
		$totalRecords = 0;
		$totalIndex = 0;
		if($result = mysqli_query($link, "SHOW TABLE STATUS;"))
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$totalRecords += $row['Rows'];
				$totalData += $row['Data_length'];
				$totalIndex += $row['Index_length'];
				$totalSize += ($row['Index_length']+$row['Data_length']);
				
				echo '<tr>';
				echo '<td align="left"><em>' . $row['Name'] . '</em></td>';
				echo '<td align="left"  style="font-family:monospace">' . $row['Engine'] . '</td>';
				echo '<td align="right" style="font-family:monospace">' . $row['Rows'] . '</td>';
				echo '<td align="right" style="font-family:monospace">' . sprintf("%.3f",$row['Data_length'] / BYTES_TO_MEGABYTES) . '</td>';
				echo '<td align="right" style="font-family:monospace">' . sprintf("%.3f",$row['Index_length'] / BYTES_TO_MEGABYTES) . '</td>';
				echo '<td align="right" style="font-family:monospace">' . sprintf("%.3f", ($row['Index_length']+$row['Data_length']) / BYTES_TO_MEGABYTES) . '</td>';
				echo '</tr>';
			}
			
			mysqli_free_result($result);

			$totalData = sprintf("%.3f",$totalData / BYTES_TO_MEGABYTES);
			$totalIndex = sprintf("%.3f",$totalIndex / BYTES_TO_MEGABYTES);
			$totalSize = sprintf("%.3f",$totalSize / BYTES_TO_MEGABYTES);
			echo "<tr style=\"background-color:silver\"><td align=\"right\"><b>Totals: </b></td><td>&nbsp;</td><td align=\"right\" style=\"font-family:monospace\"><b>$totalRecords</b></td>";
			echo "<td align=\"right\" style=\"font-family:monospace\"><b>$totalData</b></td>";
			echo "<td align=\"right\" style=\"font-family:monospace\"><b>$totalIndex</b></td>";
			echo "<td align=\"right\" style=\"font-family:monospace\"><b>$totalSize</b></td></tr>";
			echo '</table>';
		}
		else
		{
			throw new Exception(mysqli_error($link), mysqli_errno($link));
		}
	}
	
	
	private function renderServerVariables(mysqli $link)
	{
		echo <<<HEREDOC
<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
	<thead>
	<tr>
		<th>Variable</th>
		<th>Value</th>
	</tr>
	</thead>
HEREDOC;
	
		if($result = mysqli_query($link, "SHOW VARIABLES;"))
		{
			while($row = mysqli_fetch_row($result))
			{
				echo '<tr>';
				echo '<td align="left"><em>' . $row[0] . '</em></td>';
				echo '<td align="left" style="font-family:monospace">' . $row[1] . '</td>';
				echo '</tr>';
			}
			echo '</table>';
			
			mysqli_free_result($result);				
		}
		else
		{
			throw new Exception(mysqli_error($link), mysqli_errno($link));
		}
	}
	
}
?>