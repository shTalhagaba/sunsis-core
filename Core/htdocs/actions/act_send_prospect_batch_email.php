<?php
class send_prospect_batch_email implements IAction
{
	public function execute(PDO $link)
	{
		$sender_email = isset($_SESSION['user']->home_email)?$_SESSION['user']->home_email:false;
		if(!$sender_email)
			throw new Exception('Your email is required to use this functionality. Please edit your user record and enter the email address.');

		$view = View::getViewFromSession('view_ViewEmployersPool', 'ViewEmployersPool'); /* @var $view View */
		$send = isset($_REQUEST['send']) ? $_REQUEST['send'] : '';
		$prospects_ids = isset($_REQUEST['prospects'])?$_REQUEST['prospects']:'';

		$_SESSION['bc']->add($link, "do.php?_action=send_prospect_batch_email", "Send Email to Prospects");

		$prospects = $this->renderProspects($link, $view);

		if($send == 'send')
		{
			if($prospects_ids == '')
				throw new Exception("No Prospect selected.");

			include('./lib/ProgressBar.php');
			set_time_limit(0);
			$p1 = new ProgressBar();
			$p1->render("Please wait.......");

			$subject = isset($_REQUEST['subject'])?$_REQUEST['subject']:'';
			$prospects_ids = explode(",", $prospects_ids);

			$i = 0;
			$size = count($prospects_ids);

			$headers = 'From: Baltic Training Services <yourfuture@baltictraining.com>' . PHP_EOL .
				'Reply-To: Baltic Training Services <yourfuture@baltictraining.com>' . PHP_EOL .
				'X-Mailer: PHP/' . phpversion();

			foreach($prospects_ids as $prospect_id)
			{
				$p1->setProgressBarProgress($i * 100 / $size, 'Sending Emails...');

				$prospect = EmployerPool::loadFromDatabase($link, $prospect_id);

				$email_content = isset($_REQUEST['email_content']) ? $_REQUEST['email_content'] : '';

				$email_content = str_replace('**PROSPECT_NAME**', $prospect->company, $email_content);

				$email = $prospect->primary_email_address;

				if(isset($email) && $email != '')
				{
					$done = mail($email, $subject, $email_content, $headers);
					if($done)
					{
						$org_id = $prospect->auto_id;
						$sender_name = $link->quote($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname);
						$sender_email = $link->quote($sender_email);
						$receiver_name = $link->quote('Primary Contact');
						$receiver_email = $link->quote($email);
						$date_sent = date('Y-m-j');
						$time_sent = time();
						$subject = $link->quote($subject);
						$email_body = $link->quote($email_content);
						$email_html_preview = $link->quote('Not Available');
						$sent_from_sunesis = $link->quote('1');
						$sql = "INSERT INTO employer_pool_contact_email_notes ";
						$sql .= " SET org_id = " . $org_id . ", ";
						$sql .= " sender_name = " . $sender_name . ", ";
						$sql .= " sender_email = " . $sender_email . ", ";
						$sql .= " receiver_name = " . $receiver_name . ", ";
						$sql .= " receiver_email = " . $receiver_email . ", ";
						$sql .= " date_sent = '" . $date_sent . "', ";
						$sql .= " time_sent = '" . $time_sent . "', ";
						$sql .= " subject = " . $subject . ", ";
						$sql .= " email_body = " . $email_body . ", ";
						$sql .= " email_html_preview = " . $email_html_preview . ", ";
						$sql .= " sent_from_sunesis = " . $sent_from_sunesis . " ";

						DAO::execute($link, $sql);
					}
				}
				$i++;

			}

			$p1->setProgressBarProgress(100);

			echo '<br><br><br><br>
<table align="center" width="500" border="3" cellpadding="10">
	<tr>
		<td align="left">
			Batch emails sent to the prospects.
			<br/><br/>
		</td>
	</tr>
</table>';

		}
		else
			include('tpl_send_prospect_batch_email.php');
	}

	public function renderProspects(PDO $link, $view)
	{
		$s = new SQLStatement($view->getSQL());
		$s->removeClause('LIMIT');

		$returnHTML = "";

		/* @var $result pdo_result */
		$st = $link->query($s);
		if($st)
		{
			$returnHTML.= '<div id="prospectslist" align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			$returnHTML.= '<thead><tr><th><input type="checkbox" name="selectAll" value="" onclick="checkAll(this);" /></th><th>Prospect Name</th><th>Primary Email</th></tr></thead>';
			$counter=1;
			$returnHTML.= '<tbody>';
			while($row = $st->fetch())
			{
				$returnHTML.= '<tr>';
				$returnHTML .= '<td><input id="button'.$counter++.'" type="checkbox" title="' . $row['company'] . '" name="evidenceradio" value="' . $row['auto_id'] . '" /></td>';

				$returnHTML.= '<td align="left">' . HTML::cell($row['company']) . "</td>";
				$returnHTML.= '<td align="left">' . HTML::cell($row['primary_email_address']) . "</td>";

				$returnHTML.= '</tr>';
			}
			$returnHTML.= '</tbody></table></div>';
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		return $returnHTML;
	}
}