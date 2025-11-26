<?php
class ViewAllocations extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__.'V2';

        if(!isset($_SESSION[$key]))
        {
            $sql = new SQLStatement("SELECT allocations.*, (SELECT GROUP_CONCAT(CONCAT('=>',title) SEPARATOR '\n') FROM contracts WHERE allocation_id = allocations.id) AS contracts FROM allocations;");

            $view = $_SESSION[$key] = new ViewAllocations();
            $view->setSQL($sql->__toString());
        }
        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator() . '<br>';

            echo <<<HEREDOC
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead class="bg-gray">
					<tr><th class="topRow" colspan="8">Allocations</th></tr>
					<tr>
						<th class="bottomRow">Title</th>
						<th class="bottomRow">From</th>
						<th class="bottomRow">To</th>
						<th class="bottomRow">Learner started from</th>
						<th class="bottomRow">Learner started up to</th>
						<th class="bottomRow">Allocation Amount</th>
						<th class="bottomRow">Contracts</th>
						<th class="bottomRow">Description</th>
					</tr>
					</thead>
HEREDOC;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo $_SESSION['user']->isAdmin() ? HTML::viewrow_opening_tag('do.php?_action=edit_allocation&id=' . $row['id']) : '<tr>';
                echo '<td>' . HTML::cell($row['title']) . '</td>';
                echo '<td>' . Date::toShort($row['start_date']) . '</td>';
                echo '<td>' . Date::toShort($row['end_date']) . '</td>';
                echo '<td>' . Date::toShort($row['learner_start_date']) . '</td>';
                echo '<td>' . Date::toShort($row['learner_end_date']) . '</td>';
                echo '<td class="text-center">' . HTML::cell($row['allocation_amount']) . '</td>';
                echo '<td>' . HTML::cell($row['contracts']) . '</td>';
                echo '<td>' . HTML::cell($row['description']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

}
?>