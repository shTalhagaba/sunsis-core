<?php
class server_status implements IAction
{
	public function execute(PDO $link)
	{
		require("tpl_server_status.php");
	}
	
	
	private function renderTop()
	{
		$processes = `pgrep -d, 'mysqld|httpd'`;
		passthru("top -b -M -n 1 -p ".$processes);
	}
	
	private function renderDiskSpace()
	{
		passthru("df -hT");
	}
	
	private function renderFree()
	{
		passthru("free -m");
	}
	
	private function renderEntropy()
	{
		passthru("cat /proc/sys/kernel/random/entropy_avail");
	}
	
	private function renderRepositoryUsage()
	{
		//passthru("du -h --max-depth=1 ".DATA_ROOT."/uploads");
		//passthru("du --max-depth=1 ".DATA_ROOT."/uploads | sort -nr");
		
		$path = DATA_ROOT."/uploads";
		//passthru("du --max-depth=1 $path | sort -nr");
		//return;
		$result = `du -b --max-depth=1 $path | sort -nr`;
		$lines = explode("\n", $result);
		
		echo "<table>";
		echo "<col width=\"70\"/><col width=\"30\"/><col/>";
		foreach($lines as $line)
		{
			$cols = explode("\t", $line);
			if(count($cols) != 2){
				continue;
			}
			echo "<tr>";
			echo "<td align=\"right\">",Repository::formatFileSize($cols[0]),"</td>";
			echo "<td>&nbsp;</td>";
			echo "<td>",$cols[1],"</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
}
?>