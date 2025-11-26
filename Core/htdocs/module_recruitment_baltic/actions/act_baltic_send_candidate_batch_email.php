<?php
class baltic_send_candidate_batch_email implements IAction
{
	public function execute(PDO $link)
	{
		$view = View::getViewFromSession('view_ViewCandidates', 'ViewCandidates'); /* @var $view View */
		$send = isset($_REQUEST['send']) ? $_REQUEST['send'] : '';
		$candidates_ids = isset($_REQUEST['candidates'])?$_REQUEST['candidates']:'';

		$sql = "SELECT email_type, email_subject FROM candidate_email_templates order by email_subject asc;";
		$saved_templates = DAO::getResultSet($link, $sql);

		$_SESSION['bc']->add($link, "do.php?_action=send_candidate_batch_email", "Send Email to Candidate");

		$candidates = $this->renderCandidates($link, $view);

		if($send == 'send')
		{
			$sender_email = isset($_SESSION['user']->home_email)?$_SESSION['user']->home_email:false;
			if(!$sender_email)
				throw new Exception('Your email is required to use this functionality. Please edit your user record and enter the email address.');
			
			if($candidates_ids == '')
				throw new Exception("No Candidate selected.");

			include('./lib/ProgressBar.php');
			set_time_limit(0);
			$p1 = new ProgressBar();
			$p1->render("Please wait.......");


//			$sender_name = isset($_REQUEST['sender_name'])?$_REQUEST['sender_name']:'';
//			$sender_email = isset($_REQUEST['sender_email'])?$_REQUEST['sender_email']:'';
			$subject = isset($_REQUEST['subject'])?$_REQUEST['subject']:'';
			//$email_content = isset($_REQUEST['email_content']) ? $_REQUEST['email_content'] : '';
			$candidates_ids = explode(",", $candidates_ids);

			$i = 0;
			$size = count($candidates_ids);

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			if(DB_NAME=="am_baltic")
			{
				$headers .= 'From: Baltic Training Services <' . $sender_email . '>' . PHP_EOL .
					'Reply-To: Baltic Training Services <' . $sender_email . '>' . PHP_EOL .
					'X-Mailer: PHP/' . phpversion();
			}
			else
			{
				$headers .= 'From: Sunesis Demo <' . $sender_email . '>' . PHP_EOL .
					'Reply-To: Sunesis Demo <' . $sender_email . '>' . PHP_EOL .
					'X-Mailer: PHP/' . phpversion();
			}
			foreach($candidates_ids as $candidate_id)
			{
				$p1->setProgressBarProgress($i * 100 / $size, 'Sending Emails...');

				$cand = Candidate::loadFromDatabase($link, $candidate_id);

				$email_content = isset($_REQUEST['email_content']) ? $_REQUEST['email_content'] : '';

				$email_content = str_replace('**CANDIDATE_NAME**', $cand->firstnames . ' ' . $cand->surname, $email_content);

				mail($cand->email, $subject, $email_content, $headers);

				$vo = new CandidateEmail();
				$vo->candidate_id = $cand->id;
				$vo->sender_name = htmlspecialchars((string)$_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname);
				$sender_email = isset($_SESSION['user']->home_email)?$_SESSION['user']->home_email:false;
				if(!$sender_email)
					$sender_email = 'yourfuture@baltictraining.com';
				$vo->sender_email = htmlspecialchars((string)$sender_email);
				$vo->receiver_name = htmlspecialchars((string)$cand->firstnames . ' ' . $cand->surname);
				$vo->receiver_email = htmlspecialchars((string)$cand->email);
				$vo->subject = htmlspecialchars((string)$subject);
				$vo->date_sent = date('Y-m-j');
				$vo->time_sent = time();
				$vo->email_body = $email_content;
				$vo->email_html_preview = $email_content;
				$vo->sent_from_sunesis = 1;

				DAO::transaction_start($link);
				try
				{
					$vo->save($link);
					$note = new Note();
					$note->subject = "Email sent to candidate as part of Batch Emails";
					$note->is_audit_note = true;
					$note->parent_table = 'candidate_email_notes';
					$note->parent_id = $cand->id;
					$note->note = $vo->email_body;
					$note->save($link);

					DAO::transaction_commit($link);
				}
				catch(Exception $e)
				{
					DAO::transaction_rollback($link, $e);
					throw new WrappedException($e);
				}

				$i++;

			}

			$p1->setProgressBarProgress(100);

			echo '<br><br><br><br>
<table align="center" width="500" border="3" cellpadding="10">
	<tr>
		<td align="left">
			Batch emails sent to the candidates.
			<br/><br/>
		</td>
	</tr>
</table>';
			//include('tpl_baltic_send_candidate_batch_email.php');
			//exit;

		}
		else
			include('tpl_baltic_send_candidate_batch_email.php');


	}

	public function renderCandidates(PDO $link, $view)
	{
		$s = new SQLStatement($view->query);
		$s->removeClause('LIMIT');

		$returnHTML = "";

		/* @var $result pdo_result */
		$st = $link->query($s);
		if($st)
		{
			$returnHTML.= '<div id="candidateslist" align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			$returnHTML.= '<thead><tr><th><input type="checkbox" name="selectAll" value="" onclick="checkAll(this);" /></th><th>First Name</th><th>Surname</th><th>Age</th><th>Email</th></tr></thead>';
			$counter=1;
			$returnHTML.= '<tbody>';
			while($row = $st->fetch())
			{
				$returnHTML.= '<tr>';
				$returnHTML .= '<td><input id="button'.$counter++.'" type="checkbox" title="' . $row['firstnames'] . '" name="evidenceradio" value="' . $row['id'] . '" /></td>';

				$returnHTML.= '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				$returnHTML.= '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				$returnHTML.= '<td align="left">' . HTML::cell(Date::dateDiff(date("Y-m-d"),$row['dob'])) . "</td>";
				$returnHTML.= '<td align="left">' . HTML::cell($row['email']) . "</td>";

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