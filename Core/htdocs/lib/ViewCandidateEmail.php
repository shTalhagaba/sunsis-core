<?php
class ViewCandidateEmail extends View
{

	public static function getInstance($link, $candidate_id)
	{
		$key = 'view_'.__CLASS__.$candidate_id;

		if(!isset($_SESSION[$key]))
		{
		$sql = <<<HEREDOC
SELECT
	*
FROM
		candidate_email_notes
WHERE candidate_email_notes.candidate_id = $candidate_id
ORDER BY date_sent DESC, time_sent DESC
HEREDOC;

 		$view = $_SESSION[$key] = new ViewCandidateEmail();
		$view->setSQL($sql);

		// Add view filters
		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(0,'No limit',null,null));
		$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		}

		return $_SESSION[$key];
//		return $view;
	}


	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		//$st=$link->query("call view_training_providers();");
		if($st)
		{
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="5">';
			echo '<thead height="40px"><tr><th>&nbsp;</th><th>Sender Name</th><th>Sender Email</th><th>Receiver Name</th><th>Receiver Email</th><th>Date</th><th>Time</th><th>Subject</th><th>Message</th><th>HTML Preview</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<td><img src="/images/email.JPG" border="0" width="35" height="35" /></td>';
				echo '<td align="left">' . HTML::cell($row["sender_name"]) . '</td>';
				echo '<td align="left">' . HTML::cell($row['sender_email']) . '</td>';
				echo '<td align="center">' . HTML::cell(stripslashes($row['receiver_name'])) . '</td>';
				echo '<td align="center">' . HTML::cell(stripcslashes($row['receiver_email'])) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toMedium($row['date_sent'])) . '</td>';
				echo '<td align="left">' . $row['time_sent'] . '</td>';
				echo '<td align="left">' . HTML::cell($row['subject']) . '</td>';
				$row['email_body'] = strip_tags($row['email_body']);
				$row['email_body'] = trim(preg_replace('/\s+/', ' ', $row['email_body']));
				echo '<td align="left">' . HTML::cell(html_entity_decode($row['email_body'])) . '</td>';
				echo '<td align="center"><a href="do.php?_action=view_html_preview_of_email&pool=false&employer=false&candidate=' . $row['id'] . '" target="blank">HTML Preview</a></td>';
				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator('left');

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>