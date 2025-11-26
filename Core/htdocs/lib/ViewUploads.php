<?php
class ViewUploads extends View
{

    public static function getInstance()
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            if($_SESSION['user']->isAdmin())
            {
                $sql = <<<HEREDOC
SELECT DATE_FORMAT(upload_time,'%d %M %Y %H:%i:%S') AS upload_time2, filename, COUNT(upload_time) as records FROM exg GROUP BY upload_time ORDER BY upload_time DESC;
HEREDOC;
            }

            $view = $_SESSION[$key] = new ViewUploads();
            $view->setSQL($sql);

            // Add view filters
            /*$options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Funding Body (asc), Contract Type (asc)', null, 'ORDER BY title desc, funding_body, contracts.contract_type'),
                1=>array(2, 'Funding Body (desc), Contract Type (desc)', null, 'ORDER BY title , funding_body DESC, contracts.contract_type DESC'),
                2=>array(3, 'Contract Year (desc)', null, 'ORDER BY contracts.contract_year desc, title'));
            $f = new DropDownViewFilter('order_by', $options, 3, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'All Contracts', null, null),
                1=>array(2, 'Active Contracts', null, 'where  contracts.active=1'),
                2=>array(3, 'Inactive Contracts', null, 'where contracts.active<>1'));
            $f = new DropDownViewFilter('by_active', $options, 2, false);
            $f->setDescriptionFormat("Active: %s");
            $view->addFilter($f);

            $options = 'SELECT id, description, null, CONCAT("WHERE lookup_contract_locations.id=",id) FROM lookup_contract_locations';
            $f = new DropDownViewFilter('filter_location', $options, null, true);
            $f->setDescriptionFormat("Contract Location: %s");
            $view->addFilter($f);
*/
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			if(DB_NAME=="am_donc_demo" || DB_NAME=="am_doncaster")
			{
				echo '<thead><tr><th>Upload Time</th><th>Filename</th><th>Records</th><th>Actions</th></tr></thead>';
				echo '<tbody>';
				while($row = $st->fetch())
				{
					echo '<tr>';
					echo '<td align="left">' . HTML::cell($row['upload_time2']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['filename']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['records']) . '</td>';
					echo '<td align="left"><span class="button" onclick="window.location.href=\'do.php?_action=read_uploads&time=' . $row['upload_time2']. '\';">View</span> &nbsp;&nbsp;&nbsp;&nbsp; ';
					echo '<span class="button" onclick="window.location.href=\'do.php?_action=start_import_process&time=' . $row['upload_time2']. '\';">Process</span></td>';
					echo '</tr>';
				}
			}
	        else
	        {
            	echo '<thead><tr><th>Upload Time</th><th>Filename</th><th>Records</th></tr></thead>';
            	echo '<tbody>';
           	 	while($row = $st->fetch())
            	{
                	echo HTML::viewrow_opening_tag('/do.php?_action=read_uploads&time=' . $row['upload_time2']);
                	echo '<td align="left">' . HTML::cell($row['upload_time2']) . '</td>';
                	echo '<td align="left">' . HTML::cell($row['filename']) . '</td>';
                	echo '<td align="left">' . HTML::cell($row['records']) . '</td>';
                	echo '</tr>';
            	}
			}
            echo '</tbody></table></div>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

}
?>