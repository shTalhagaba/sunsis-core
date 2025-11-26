<?php
class link_sun_one_aims extends ActionController
{
	public function indexAction(PDO $link)
	{
        $authorised = $_SESSION['user']->isAdmin();
		if (!$authorised) 
        {
			throw new UnauthorizedException();
		}

        $framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';
        if($framework_id == '')
        {
            throw new Exception("Missing querystring argument: framework_id");
        }
        $framework = Framework::loadFromDatabase($link, $framework_id);
        if($framework->onefile_organisation_id == '')
        {
            throw new Exception("Please first edit the record and select Onefile organisation.");
        }

        $_SESSION['bc']->add($link, "do.php?_action=link_sun_one_aims&framework_id=" . $framework->id, "Attach Sunesis/Onefile Aims");

        $view = ViewFrameworkQualifications::getInstance($link, $framework->id);
		$view->refresh($link, ['_reset' => 1]);



        include_once('tpl_link_sun_one_aims.php');
    }

    public function save(PDO $link)
    {
        pre($_REQUEST);
    }

    public function renderQualificationsTable(PDO $link, View $view, Framework $framework)
	{
        $onefile_standards_list = [];
        $onefile_standards_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'standards_{$framework->onefile_organisation_id}'");
        if($onefile_standards_list_from_db != '')
        {
            $onefile_standards_list_from_db = json_decode($onefile_standards_list_from_db);
            usort($onefile_standards_list_from_db, function($a, $b) {return strcmp($a->Title, $b->Title);});
            foreach($onefile_standards_list_from_db AS $onefile_standard)
            {
                $onefile_standards_list[] = [$onefile_standard->ID, '[' . $onefile_standard->ID . '] ' . $onefile_standard->Title];
            }
        }

		$st = $link->query($view->getSQL());
		if($st) 
		{
			echo '<div align="left"><table class="table table-bordered resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>QAN</th><th>Title</th><th>Onefile Standards/Learning Aims</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr>';
				echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="center">';
                echo HTML::selectChosen('onefile_standard_id_for_'.$row['auto_id'], $onefile_standards_list, $row['onefile_standard_id'], true);
                echo '</td>';
				echo '</tr>';
			}
			echo '</tbody></table></div>';
		}
		else
		{
			throw new DatabaseException($link, $view->getSQL());
		}
		
	}
}