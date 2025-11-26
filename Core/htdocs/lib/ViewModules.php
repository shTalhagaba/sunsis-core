<?php
class ViewModules extends View
{

    public static function getInstance()
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            // Create new view object
            $sql = <<<HEREDOC
SELECT
modules.id, title, provider_id, learning_hours, organisations.legal_name as provider
from modules
inner join organisations on modules.provider_id = organisations.id
HEREDOC;

            $view = $_SESSION[$key] = new ViewModules();
            $view->setSQL($sql);

	        $f = new TextboxViewFilter('filter_title', "WHERE modules.title LIKE '%s%%'", null);
	        $f->setDescriptionFormat("Module Title: %s");
	        $view->addFilter($f);

	        // Provider Filter
	        $options = "SELECT id, legal_name, null, CONCAT('WHERE  organisations.id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
	        $f = new DropDownViewFilter('filter_provider', $options, null, true);
	        $f->setDescriptionFormat("Training Provider: %s");
	        $view->addFilter($f);

	        // Add view filters
            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(0,'No limit',null,null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);


            $options = array(
                0=>array(1, 'Module Title (asc)', null, 'ORDER BY title'),
                1=>array(2, 'Provider Name', null, 'ORDER BY provider'),
                2=>array(3, 'Learning Hours (asc)', null, 'ORDER BY learning_hours ASC'),
                3=>array(4, 'Learning Hours (desc)', null, 'ORDER BY learning_hours DESC'));
            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

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
            echo '<thead><tr><th>&nbsp;</th><th>Module Title</th><th>Provider</th><th>Learning Hours</th></tr></thead>';

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_module&id=' . $row['id']);
                echo '<td><img src="/images/group-icon-blue.png" border="0" /></td>';
                echo '<td align="left" style="font-family: monospace">' . HTML::cell($row['title']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['provider']) . "</td>";
                echo '<td align="left" style="font-family:monospace">' . HTML::cell($row['learning_hours']) . "</td>";
                echo '</tr>';
            }
            echo '</tbody></table></div align="center">';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }
}
?>