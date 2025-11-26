<?php
define('METRES_IN_A_MILE', 1609.344);
class ViewVacancyApplications extends View
{

	public static function getInstance($vacancy_id, $status)
	{
		$key = 'view'.__CLASS__.$vacancy_id.$status;

		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
SELECT
	candidate_applications.*,
	candidate.gender,
	candidate.firstnames,
	candidate.surname,
	candidate.address1,
	candidate.address2,
	candidate.borough,
	candidate.postcode,
	DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d')) AS age,
	candidate.username,
	SQRT(POWER(ABS(vacancies.easting - candidate.easting), 2) + POWER(ABS(vacancies.northing - candidate.northing), 2)) AS distance
FROM
	candidate_applications
	LEFT JOIN candidate ON candidate_applications.candidate_id = candidate.id
	LEFT JOIN vacancies ON candidate_applications.vacancy_id = vacancies.id

WHERE
	candidate_applications.vacancy_id = $vacancy_id
	AND candidate_applications.current_status = $status
HEREDOC;

			$view = $_SESSION[$key] = new ViewVacancyApplications();
			$view->setSQL($sql);

			// Add view filters
			$f = new TextboxViewFilter('filter_firstnames', "WHERE candidate.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE candidate.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 100, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Created (Ascending)', null, 'ORDER BY candidate_applications.created ASC'),
				1=>array(2, 'Created (Descending)', null, 'ORDER BY candidate_applications.created DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}


	private function getCurrentStatusHeader($status)
	{
		$header = '<thead>';
		switch($status)
		{
			case RecCandidateApplication::CREATED:
				$header = '<tr class="topRow"><th colspan="7">Candidate</th><th colspan="4">Application</th></tr>';
				$header .= '<tr class="bottomRow"><th><input id="global" type="checkbox" onclick="checkAllDialogMultiUpdateFromNotScreenedToRejected(this);" /><br><input type="button" onclick="openDialogMultiUpdateFromNotScreenedToRejected();" value="Reject" /></th><th>&nbsp;</th><th>Candidate Name</th><th>Age</th><th>Address</th><th>Postcode</th><th>Distance(miles)</th><th>Status</th><th>RAG</th><th>Date</th><th>Action</th></tr>';
				break;
			case RecCandidateApplication::SCREENED:
				$header = '<tr class="topRow"><th colspan="7">Candidate</th><th colspan="4">Application</th></tr>';
				$header .= '<tr class="bottomRow"><th><input id="global" type="checkbox" onclick="checkAllDialogMultiUpdateFromScreenedToRejected(this);" /><br><input type="button" onclick="openDialogMultiUpdateFromScreenedToRejected();" value="Reject" /></th><th>&nbsp;</th><th>Candidate Name</th><th>Age</th><th>Address</th><th>Postcode</th><th>Distance(miles)</th><th>Status</th><th>RAG</th><th>Action</th></tr>';
				break;
			case RecCandidateApplication::TELEPHONE_INTERVIEWED:
				$header = '<tr class="topRow"><th colspan="7">Candidate</th><th colspan="5">Application</th></tr>';
				$header .= '<tr class="bottomRow"><th><input id="global" type="checkbox" onclick="checkAll(this);" /><br><input type="button" onclick="openDialogMultiUpdate();" value="Update" /></th><th>&nbsp;</th><th>Candidate Name</th><th>Age</th><th>Address</th><th>Postcode</th><th>Distance(miles)</th><th>Status</th><th>RAG</th><th>Telephone Interview<br>Score</th><th>Telephone Interview<br>Outcome</th><th>Action</th></tr>';
				break;
			case RecCandidateApplication::CV_SENT:
			case RecCandidateApplication::INTERVIEW_SUCCESSFUL:
				$header = '<tr class="topRow"><th colspan="6">Candidate</th><th colspan="7">Application</th></tr>';
				$header .= '<tr class="bottomRow"><th>&nbsp;</th><th>Candidate Name</th><th>Age</th><th>Address</th><th>Postcode</th><th>Distance(miles)</th><th>Status</th><th>RAG</th><th>Telephone Interview<br>Score</th><th>Store Manager<br>Interview Outcome</th><th>Area Manager<br>Interview Outcome</th><th>Action</th></tr>';
				break;
			case RecCandidateApplication::INTERVIEW_UNSUCCESSFUL:
			case RecCandidateApplication::SUNESIS_LEARNER:
				$header = '<tr class="topRow"><th colspan="6">Candidate</th><th colspan="7">Application</th></tr>';
				$header .= '<tr class="bottomRow"><th>&nbsp;</th><th>Candidate Name</th><th>Age</th><th>Address</th><th>Postcode</th><th>Distance(miles)</th><th>Status</th><th>RAG</th><th>Telephone Interview<br>Score</th><th>Telephone Interview<br>Outcome</th><th>Store Manager<br>Interview Outcome</th><th>Area Manager<br>Interview Outcome</th><th>Action</th></tr>';
				break;
			case RecCandidateApplication::REJECTED:
				$header = '<tr class="topRow"><th colspan="6">Candidate</th><th colspan="6">Application</th></tr>';
				$header .= '<tr class="bottomRow"><th>&nbsp;</th><th>Candidate Name</th><th>Age</th><th>Address</th><th>Postcode</th><th>Distance(miles)</th><th>Status</th><th>RAG</th><th>Telephone Interview<br>Score</th><th>Store Manager<br>Interview Outcome</th><th>Area Manager<br>Interview Outcome</th><th>Action</th></tr>';
				break;
			case RecCandidateApplication::WITHDRAWN:
				$header = '<tr class="topRow"><th colspan="6">Candidate</th><th colspan="6">Application</th></tr>';
				$header .= '<tr class="bottomRow"><th>&nbsp;</th><th>Candidate Name</th><th>Age</th><th>Address</th><th>Postcode</th><th>Distance(miles)</th><th>Status</th><th>RAG</th><th>Telephone Interview<br>Score</th><th>Store Manager<br>Interview Outcome</th><th>Area Manager<br>Interview Outcome</th><th>Action</th></tr>';
				break;
			default:
				$header = '<tr class="topRow"><th colspan="6">Candidate</th><th colspan="3">Application</th></tr>';
				$header .= '<tr class="bottomRow"><th>&nbsp;</th><th>Candidate Name</th><th>Age</th><th>Address</th><th>Postcode</th><th>Distance(miles)</th><th>Status</th><th>Date</th></tr>';
				break;
		}
		$header .= '</thead>';
		return $header;
	}

	public function render(PDO $link, $status = '')
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo $this->getCurrentStatusHeader($status);
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr id="tr_app_' . $row['id'] . '">';
				if($status == RecCandidateApplication::TELEPHONE_INTERVIEWED)
				{
					if($row['telephone_interview_outcome'] != 'unsuccessful')
						echo '<td align="center"><input id="checkbox_' . $row['id'] . '" type="checkbox"  onchange="" onclick="checkbox_onclick(this);" name="" value="' . $row['id'] . '"   /></td>';
					else
						echo '<td></td>';
				}
				if($status == RecCandidateApplication::CREATED)
				{
					echo '<td align="center"><input id="checkboxDialogMultiUpdateFromNotScreenedToRejected_' . $row['id'] . '" type="checkbox"  onchange="" onclick="checkboxDialogMultiUpdateFromNotScreenedToRejected_onclick(this);" name="" value="' . $row['id'] . '"   /></td>';
				}
				if($status == RecCandidateApplication::SCREENED)
				{
					echo '<td align="center"><input id="checkboxDialogMultiUpdateFromScreenedToRejected_' . $row['id'] . '" type="checkbox"  onchange="" onclick="checkboxDialogMultiUpdateFromScreenedToRejected_onclick(this);" name="" value="' . $row['id'] . '"   /></td>';
				}
				/*if($row['gender'] == 'M')
					echo '<td><img src="/images/boy-blonde-hair.gif" border="0" /></td>';
				elseif($row['gender'] == 'F')
					echo '<td><img src="/images/girl-black-hair.gif" border="0" /></td>';
				else*/
					echo '<td><img src="/images/blue-person.gif" border="0" /></td>';
				//if($_SESSION['user']->isAdmin())
				echo '<td><a href="do.php?_action=rec_read_candidate&id=' . $row['candidate_id'] . '">' . strtoupper((string) $row['surname']) . ', ' . $row['firstnames'] . '</a></td>';
				//else
				//	echo '<td>' . strtoupper($row['surname']) . ', ' . $row['firstnames'] . '</td>';
				echo '<td align="center">' . $row['age'] . '</td>';
				echo '<td>' . $row['address1'] . ' ' . $row['address2']  . ', ' . $row['borough'] . '</td>';
				echo '<td align="center">' . $row['postcode'] . '</td>';
				echo '<td align="center">'.sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE)).'</td>';
				echo '<td align="center">' . RecCandidateApplication::getStatusDesc($row['current_status']) . '</td>';
				if($status == RecCandidateApplication::CREATED)
				{
					echo '<td align="center">' . RecCandidateApplication::getApplicationRAGIcon($row['screening_rag']) . '</td>';
					echo '<td align="center">' . Date::to($row['created'], Date::DATETIME) . '</td>';
					echo '<td align="center"><img title="Open application in detail" src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" />';
					echo '<tr style="display: none;"><td colspan="9"></td></tr>';
				}
				elseif($status == RecCandidateApplication::SCREENED)
				{
					echo '<td align="center">' . RecCandidateApplication::getApplicationRAGIcon($row['screening_rag']) . '</td>';
					echo '<td align="center"><img title="Open application in detail" src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" />';
					echo ' &nbsp;&nbsp;<img title="Move application back to Not Screened" src="images/back.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="moveBackApplication(\''.$row['id'].'\', \'Screened\', \'Not Screened\'); return false;" /></td>';
				}
				elseif($status == RecCandidateApplication::TELEPHONE_INTERVIEWED)
				{
					echo '<td align="center">' . RecCandidateApplication::getApplicationRAGIcon($row['screening_rag']) . '</td>';
					echo '<td align="center">' . $row['telephone_interview_score'] . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['telephone_interview_outcome']) . '</td>';
					if($row['telephone_interview_outcome'] == 'unsuccessful')
					{
						echo '<td align="center">';
						echo '<img title="Open application in detail" src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" />';
						echo ' &nbsp; <img title="Reject application" src="images/cross.png" alt="" width="35" height="35" style="cursor: pointer" onclick="rejectApplicationAfterTelephoneInterview(\''.$row['id'].'\'); return false;" />';
						echo '</td>';
					}
					else
					{
						echo '<td align="center"><img title="Open application in detail" src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" />';
						echo ' <img title="Move application back to Screened" src="images/back.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="moveBackApplication(\''.$row['id'].'\', \'Telephone Interviewed\', \'Screened\'); return false;" /></td>';
					}
				}
				elseif($status == RecCandidateApplication::CV_SENT)
				{
					echo '<td align="center">' . RecCandidateApplication::getApplicationRAGIcon($row['screening_rag']) . '</td>';
					echo '<td align="center">' . $row['telephone_interview_score'] . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level1']) . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level2']) . '</td>';
					echo '<td align="center"><img title="Open application in detail" src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" />';
					if($_SESSION['user']->isAdmin())
						echo ' <img title="Move application back to Telephone Interviewed" src="images/back.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="moveBackApplication(\''.$row['id'].'\', \'CV Sent\', \'Telephone Interviewed\'); return false;" /></td>';
					else
						echo '</td>';
				}
				elseif($status == RecCandidateApplication::INTERVIEW_SUCCESSFUL)
				{
					echo '<td align="center">' . RecCandidateApplication::getApplicationRAGIcon($row['screening_rag']) . '</td>';
					echo '<td align="center">' . $row['telephone_interview_score'] . '</td>';
					//echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['telephone_interview_outcome']) . '</td>';
					//echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon('successful') . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level1']) . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level2']) . '</td>';
					if($_SESSION['user']->isAdmin())
					{
						echo '<td align="center"><img title="Convert candidate into Sunesis Learner" src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" />';
						echo '<img title="Withdraw application" src="images/back.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="moveBackApplication(\''.$row['id'].'\', \'Interview Successful\', \'Withdrawn\'); return false;" /></td>';
					}
					else
						echo '<td></td>';
				}
				elseif($status == RecCandidateApplication::SUNESIS_LEARNER)
				{
					echo '<td align="center">' . RecCandidateApplication::getApplicationRAGIcon($row['screening_rag']) . '</td>';
					echo '<td align="center">' . $row['telephone_interview_score'] . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['telephone_interview_outcome']) . '</td>';
					//echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon('successful') . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level1']) . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level2']) . '</td>';
					if($_SESSION['user']->isAdmin())
					{
						echo '<td align="center"><img src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" />';
						echo '<img title="Withdraw application" src="images/back.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="moveBackApplication(\''.$row['id'].'\', \'Sunesis Learner\', \'Withdrawn\'); return false;" /></td>';
					}
					else
						echo '<td></td>';
				}
				elseif($status == RecCandidateApplication::INTERVIEW_UNSUCCESSFUL)
				{
					echo '<td align="center">' . RecCandidateApplication::getApplicationRAGIcon($row['screening_rag']) . '</td>';
					echo '<td align="center">' . $row['telephone_interview_score'] . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['telephone_interview_outcome']) . '</td>';
					//echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon('unsuccessful') . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level1']) . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level2']) . '</td>';
					if($_SESSION['user']->isAdmin())
						echo '<td align="center"><img src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" /></td>';
					else
						echo '<td></td>';
				}
				elseif($status == RecCandidateApplication::WITHDRAWN)
				{
					echo '<td align="center">' . RecCandidateApplication::getApplicationRAGIcon($row['screening_rag']) . '</td>';
					echo '<td align="center">' . $row['telephone_interview_score'] . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level1']) . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level2']) . '</td>';
					//echo '<td align="center"><img src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" /></td>';
					echo '<td align="center"><img src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" />';
					echo '<img title="Reset to Screened" src="images/back.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="moveBackApplication(\''.$row['id'].'\', \'Withdrawn\', \'Screened\'); return false;" /></td>';
				}
				elseif($status == RecCandidateApplication::REJECTED)
				{
					echo '<td align="center">' . RecCandidateApplication::getApplicationRAGIcon($row['screening_rag']) . '</td>';
					echo '<td align="center">' . $row['telephone_interview_score'] . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level1']) . '</td>';
					echo '<td align="center">' . RecCandidateApplication::getInterviewOutcomeIcon($row['ftof_interview_level2']) . '</td>';
					//echo '<td align="center"><img src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" /></td>';
					echo '<td align="center"><img src="images/view_detail.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="displayCandidateDetail(\''.$row['id'].'\'); return false;" />';
					echo '<img title="Reset to Screened" src="images/back.ico" alt="" width="35" height="35" style="cursor: pointer" onclick="moveBackApplication(\''.$row['id'].'\', \'Rejected\', \'Screened\'); return false;" /></td>';					
				}
				echo '</tr>';
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