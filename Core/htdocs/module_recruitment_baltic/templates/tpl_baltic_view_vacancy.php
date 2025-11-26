<?php /* @var $view View */ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Vacancy</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>

	<?php
	$selected_theme = SystemConfig::getEntityValue($link, 'module_theme');
	if ( $selected_theme ) {
		echo '<link rel="stylesheet" href="/css/'.$selected_theme.'/common.css" type="text/css"/>';
	}
	?>

	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />

	<script type="text/javascript" language="javascript">
		function send_to_employer(candidate_id)
		{
			var vacancy_id = <?php echo $vacancy->id; ?>;
			var postData = 'candidate_id=' + candidate_id + '&vacancy_id=' + vacancy_id;

			var request = ajaxRequest('do.php?_action=baltic_ajax_send_cv_to_employer', postData);

			if(request)
			{
				alert("Candidate Status Changed.");
				window.location.reload(true);
			}
		}

		function checkCandidateMandatoryInfo(candidate_id, vacancy_id, vacancy_postcode, vacancy_radius)
		{
			var postData = '&candidate_id='+candidate_id;
			var request = ajaxRequest('do.php?_action=baltic_ajax_check_candidate_mandatory_info', postData);

			if(request)
			{
				if(request.responseText != '')
				{
					var response = "Following mandatory information is missing for this candidate, please edit and save the candidate record to fill the mandatory information\r\n";
					response += request.responseText;
					alert(response);

				}
				else
				{
					window.location.href = "do.php?_action=fill_vacancy&id="+vacancy_id+"&pc="+vacancy_postcode+"&cd_id="+candidate_id+"&radius="+vacancy_radius;
				}
			}
		}
	</script>
</head>
<body id="candidates" >
<?php
$banner = array();
$banner['page_title'] = 'Vacancy';
$banner['low_system_buttons'] = '<button onclick="showHideBlock(\'div_filters\');showHideBlock(\'div_filter_crumbs\');" title="Show/hide filters" id="btn-filter" /></button>';
include_once('layout/tpl_banner.php');
?>

<?php
// establish all the messaging values
// for use in feedback
$feedback_message = '&#160;';
$feedback_color = '#DCE5CD';
$current_tab = '#tab-2';

if ( isset($vacancy) ) {
	if ( $vacancy->feedback['message'] != NULL ) {
		$feedback_message = $vacancy->feedback['message'];
	}
	if ( $vacancy->feedback['background-color'] != NULL ) {
		$feedback_color = $vacancy->feedback['background-color'];
	}
	if ( $vacancy->feedback['location'] != NULL ) {
		$current_tab = $vacancy->feedback['location'];
	}

	if ( isset($_REQUEST['search_apps']) ) {
		$current_tab = '#tab-1';
	}
}


?>
<div id="infoblock">
	<?php $_SESSION['bc']->render($link); ?>
	<div id="feedback"><?php echo $feedback_message; ?></div>
</div>

<div id="maincontent">
<?php
if ( isset($vacancy) && $vacancy->feedback['message'] != NULL ) {
	$vacancy->feedback['message'] = NULL;
}
?>
<div id="tabs">
<h3>Management of vacancy.
	<?php if ( $vacancy->active == 1 ) { ?>
		<a href="do.php?_action=new_candidate&amp;vacancy_id=<?php echo $vacancy->id; ?>" >register a new candidate &raquo;</a>
		<?php } ?>
</h3>
  		<span>
  		Contact
			  <?php
			  // details of the vacancy organisation for contact presentation
			  // there is an issue if the location has been removed - need to add extra
			  // checks into the location removal
			  $vacancy_organisation = Location::loadFromDatabase($link, $vacancy->location);
			  if( is_object($vacancy_organisation) ) {
				  if ( $vacancy_organisation->contact_email != '' ) {
					  echo '<a href="mailto:'.$vacancy_organisation->contact_email.'">'.$vacancy_organisation->contact_name.'</a>';
				  }
				  else {
					  echo $vacancy_organisation->contact_name;
				  }
				  echo '&nbsp;(tel: '.$vacancy_organisation->contact_mobile.' ';
				  echo 'mob: '.$vacancy_organisation->contact_telephone.')';

				  // add new breadcrumb
				  $_SESSION['bc']->add($link, "do.php?_action=view_vacancy&amp;id=$vacancy->id&amp;pc=$vacancy_organisation->postcode", "View ".$vacancy->trading_name." Vacancy");
			  }
			  else {
				  echo 'The location for this vacancy is no longer held in Sunesis - please refer to your system administrator for assistance.';
			  }
			  ?>
			  &nbsp;to screen any candidates for this vacancy.  This vacancy is
			  <?php
			  if ( $vacancy->active == 1 ) {
				  echo '<strong> actively recruiting</strong>';
			  }
			  else {
				  echo ' closed for new registrations.';
			  }
			  ?>
  		</span>
<table>
	<thead>
	<tr>
		<th>Employer</th>
		<th>Sector</th>
		<th>Job Title</th>
		<th>Total Positions Available</th>
		<th>Current Applications</th>
		<th>New Applications</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td align="left" style="" ><a href="/do.php?_action=read_employer&id=<?php echo $vacancy->employer_id; ?>"><?php echo $vacancy->trading_name; ?></a></td>
		<td align="left" style="" ><?php echo $vacancy->vac_desc; ?></td>
		<td align="left" style="" ><a href="#" onclick="open_new_window('/do.php?_action=read_vacancy&id=<?php echo $vacancy->id; ?>');"><?php echo $vacancy->job_title; ?></a></td>
		<td align="center" style="" ><?php echo $vacancy->no_of_vacancies; ?></td>

		<td align="center" style="" >
			<?php
			$current_sql = <<<HEREDOC
SELECT
	count(*)
FROM
	candidate, candidate_applications
WHERE
	candidate.id = candidate_applications.candidate_id
AND
	candidate_applications.vacancy_id = {$vacancy->id}
AND
	candidate_applications.application_status = 1;
HEREDOC;
			echo DAO::getSingleValue($link, $current_sql);
			echo '</td>';
			echo '<td align="center" style="" >';
			$current_sql = <<<HEREDOC
SELECT
	count(*)
FROM
	candidate, candidate_applications
WHERE
	candidate.id = candidate_applications.candidate_id
AND
	candidate_applications.vacancy_id = {$vacancy->id}
AND
	candidate_applications.application_status is null;
HEREDOC;
			echo DAO::getSingleValue($link, $current_sql);
			echo '</td>';
			?>
	</tr>
	</tbody>
</table>
<ul>
	<li><a href="#tab-1">Search Candidates</a></li>
	<li><a href="#tab-2">Direct Candidate</a></li>
	<li><a href="#tab-3">Approved Candidates</a></li>
	<li><a href="#tab-4">Employer Selection</a></li>
	<li><a href="#tab-5">Successful Candidates</a></li>
</ul>
<?php  //if ( isset($pc) && $pc != '' ) { ?>
<div id="tab-1">
	<form method="post" action="do.php?_action=view_vacancy">
		<h3>
			&#160;Available Candidates within
			<input name="pc" value="<?php echo $pc; ?>" type="hidden" />
			<input name="id" value="<?php echo $vacancy->id; ?>" type="hidden" />
			<input name="radius" value="<?php echo $vacancy->radius; ?>" type="text" size="4" maxlength="4" /> miles<br>
			Candidate Surname [optional]<input name="surname" value="<?php if(isset($surname)) echo $surname; ?>" type="text" size="20" maxlength="20" /><br>
			Candidates With Interest In (<?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_type WHERE id = " . $vacancy->type); ?>) [optional]
			<?php if(isset($cand_with_interest) AND $cand_with_interest == 'on') $checked = " checked "; else $checked = ""; ?>
			<input name="cand_with_interest"  <?php echo $checked; ?>  type="checkbox" /><br>
			Candidate Age [optional]<input name="cand_age" value="<?php if(isset($cand_age)) echo $cand_age; ?>" type="text" size="4" maxlength="4" /><br>
			<?php $search_cand_status = isset($search_cand_status)?$search_cand_status: ''; ?>
			Candidate Status [optional]<?php echo HTML::select('search_cand_status', DAO::getResultset($link, "SELECT id, description, null FROM lookup_candidate_status ORDER BY description"), $search_cand_status, true); ?>
			<input type="submit" value="search &raquo;" class="submit" />
			<input type="hidden" name="search_apps" value="1" />
			<input type="submit" name="mailshot" value="download mailshot list &raquo;" class="submit" />
		</h3>
	</form>
	<?php
	// re - only do this if the form is submitted - quick fix to speed up the
	//    - load times
	if ( isset($_REQUEST['search_apps']) && $_REQUEST['search_apps'] == 1 ) {
		?>
		<table class="resultset">
			<thead>
			<tr>
				<th>Candidate Name</th>
				<th>Address</th>
				<th>Postcode</th>
				<th>Distance (miles)</th>
				<th>Age</th>
				<th>Screening Score</th>
				<th>Status</th>
				<th>Last Action</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$surname = isset($_REQUEST['surname'])?$_REQUEST['surname']:'';
				$cand_age = isset($_REQUEST['cand_age'])?$_REQUEST['cand_age']:'';
				$cand_with_interest = isset($_REQUEST['cand_with_interest'])?$_REQUEST['cand_with_interest']:'';
				$search_cand_status = isset($_REQUEST['search_cand_status'])?$_REQUEST['search_cand_status']:'';
				if($surname != '')
				{
					$surname = " AND candidate.surname LIKE '" . addslashes((string)$surname) . "%'";
				}
				if($cand_age != '')
				{
					$cand_age = " AND (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d'))) = " . $cand_age . " ";
				}
				$where_clause_extra = '';
				if($cand_with_interest != '')
				{
					//$cand_with_interest = "	LEFT JOIN candidate_sector_choice ON ( candidate.id = candidate_sector_choice.candidate_id ) AND candidate_sector_choice.sector = " . $vacancy->type;
					$where_clause_extra = " AND candidate.id IN (SELECT candidate_id FROM candidate_sector_choice WHERE candidate_sector_choice.sector = " . $vacancy->type . ") ";
				}
				if($search_cand_status)
				{
					$where_clause_extra .= " AND candidate.status_code = " . $search_cand_status . " ";
				}
				$radius_metres = $vacancy->radius_metres;
// this query includes the users link which isn't relevant, 
// but want to look at reducing the number of queries in this template
// ...relmes more work needed!
				$sql = <<<HEREDOC
	SELECT
		candidate.*,
		(SELECT description FROM lookup_candidate_status WHERE id = candidate.status_code) AS cand_status,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d')) AS age,
		candidate.username as learner_name,
		SQRT(POWER(ABS($vacancy->easting - candidate.easting), 2) + POWER(ABS($vacancy->northing - candidate.northing), 2)) AS distance
	FROM
		candidate
	WHERE
	    candidate.username is NULL AND
		candidate.easting >= ($vacancy->easting - $radius_metres) AND candidate.easting <= ($vacancy->easting + $radius_metres) AND
		candidate.northing >= ($vacancy->northing - $radius_metres) AND candidate.northing <= ($vacancy->northing + $radius_metres) AND
		candidate.id NOT IN ( SELECT candidate_applications.candidate_id FROM candidate_applications WHERE candidate_applications.vacancy_id = $vacancy->id AND candidate_applications.application_status = 1 )	
		$surname
		$cand_age
		$where_clause_extra
	HAVING
		distance <= $radius_metres
	ORDER BY
		distance,
        candidate.surname;
HEREDOC;
				//pre($sql);
				$sta = $link->query($sql);

				if($sta) {
					$row_count = 1;
					while( $row = $sta->fetch() ) {
						if ( $row['learner_name'] === NULL ) {

							if ( $row_count % 2 ) {
								$row_style = 'background-color: #F9F9F9';
							}
							else {
								$row_style = 'background-color: #FFFFFF';
							}
							$row_count++;

							$candidate_id = $row['id'];

							$candidate_details = Candidate::loadFromDatabase($link, $candidate_id);
							$candidate_details->age = $row['age'];
							$candidate_details->learner_name = $row['learner_name'];
							$candidate_details->distance = $row['distance'];
							// load the candidate history
							$candidate_details->candidate_notes = CandidateNotes::loadFromDatabase($link, $candidate_id);


							echo '<tr id="user_1_'.$candidate_id.'" style="'.$row_style.'" class="shortrecord" >';
							echo '<td>'.$row['firstnames'].' '.$row['surname'].'</td>';
							echo '<td>'.$row['address2'].', '.$row['county'].'</td>';
							echo '<td>'.$row['postcode'].'</td>';
							echo '<td>'.sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE)).'</td>';
							echo '<td>'.$row['age'].'</td>';
							echo '<td>'.$row['screening_score'].'</td>';
							echo '<td>'.$row['cand_status'].'</td>';
							// if ( isset($candidate_details->candidate_notes->comments[0]['note']) ) {
							$comment_html = '<td>&nbsp;</td>';
							foreach ( $candidate_details->candidate_notes->comments as $comment ) {
								if ( $comment['status'] == 0 ) {
									$comment_html = '<td>'.$comment['note'].' ('.$comment['username'].')</td>';
									break;
								}
								// echo '<td>'.$candidate_details->candidate_notes->comments[0]['note'].' ('.$candidate_details->candidate_notes->comments[0]['username'].')</td>';
							}

							echo $comment_html;
							echo '<td><a href="do.php?_action=read_candidate&amp;candidate_id='.$candidate_id.'">&nbsp; Detailed View &raquo; </a></td>';
							if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
								echo '<td><a href="#" onclick="displaydetail(\'1_'.$candidate_id.'\'); return false;">&nbsp; screen &raquo;</a></td>';
//							echo '<td><a href="/do.php?_action=fill_vacancy&amp;id='.$vacancy->id.'&amp;pc='.htmlentities($vacancy->postcode).'&amp;cd_id='.$row['id'].'&amp;radius='.$vacancy->radius.'">&nbsp; approve applicant &raquo;</a></td>';
							if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
								echo '<td><a href="#" onclick="checkCandidateMandatoryInfo('. $row['id'] .', '. $vacancy->id .', \''. $vacancy->postcode .'\', ' . $vacancy->radius . ');">&nbsp; approve applicant &raquo;</a></td>';
//							echo '<td><a href="/do.php?_action=fill_vacancy&amp;id='.$vacancy->id.'&amp;pc='.htmlentities($vacancy->postcode).'&amp;cd_id='.$row['id'].'&amp;radius='.$vacancy->radius.'&amp;rmv=1&amp;da=1">&nbsp; delete applicant &raquo;</a></td>';
							echo '</tr>';

							echo '<tr id="detail_1_'.$candidate_id.'" style="display:none;">';
							echo '	<td colspan="10" style="text-align:center" >';
							echo ' <img src="images/candidate_loader.gif" /> ';
							echo '	</td>';
							echo '</tr>';
						}
					}
				}
				else {
					throw new DatabaseException($link, $sql);
				}
				?>
			</tbody>
		</table>
		<?php
		// re - only do this if the form is submitted - quick fix to speed up the 
		//    - load times
	}
	else {
		?>
		<h3>Please use the distance search above to find candidates close to this role</h3>
		<span style="text-align:left;">We have disabled the automatic searching of candidates for this vacancy to improve the speed at which the system operates.
<br/>
<br/>
In order to obtain the results for a search, please amend the distance and click on the 'search >>' button.
                                </span>

		<?php
	}
	?>
</div>
<div id="tab-2">
	<h3>Candidates who have applied directly for this role</h3>
	<table class="resultset">
		<thead>
		<tr>
			<th>Candidate Name</th>
			<th>Address</th>
			<th>Postcode</th>
			<th>Distance (miles)</th>
			<th>Age</th>
			<th>Screening Score</th>
			<th>Last Action</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php

		$radius_metres = $vacancy->radius_metres;
// this query includes the users link which isn't relevant, 
// but want to look at reducing the number of queries in this template
// ...relmes more work needed!
		$sql = <<<HEREDOC
        SELECT
                candidate.*, candidate.username AS learner_name,
                DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d')) AS age,
#               users.username as learner_name,
                SQRT(POWER(ABS($vacancy->easting - easting), 2) + POWER(ABS($vacancy->northing - northing), 2)) AS distance,
                candidate_applications.application_screening
        FROM
                candidate,
# this is massively slowing the query down - need to resolve
#               LEFT JOIN users
#               ON ( candidate.firstnames = users.firstnames
#               AND candidate.surname = users.surname
#               AND candidate.national_insurance = users.ni ),
                candidate_applications
        WHERE
                # candidate.enrolled > 1 AND
                candidate.username is null AND
                candidate.id = candidate_applications.candidate_id AND
                candidate_applications.vacancy_id = $vacancy->id AND
            candidate_applications.application_status is null
	GROUP BY 
		candidate.id
	ORDER BY
    	distance,
		candidate.surname;
HEREDOC;

		$sta = $link->query($sql);

		if($sta) {
			while( $row = $sta->fetch() ) {
				if ( $row['learner_name'] === NULL ) {
					$candidate_id = $row['id'];
					$candidate_details = Candidate::loadFromDatabase($link, $candidate_id);
					$candidate_details->age = $row['age'];
					$candidate_details->learner_name = $row['learner_name'];
					$candidate_details->distance = $row['distance'];
					// load the candidate history
					$candidate_details->candidate_notes = CandidateNotes::loadFromDatabase($link, $candidate_id);

					$screen_level = 'low';
					if ( $row['application_screening'] >= 45 && $row['application_screening'] <= 70 ) {
						$screen_level = 'med';
					}
					else if ( $row['application_screening'] >= 70 ) {
						$screen_level = 'high';
					}

					echo '<tr id="user_2_'.$candidate_id.'" class="shortrecord '.$screen_level.'" >';
					echo '<td>'.$row['firstnames'].' '.$row['surname'].'</td>';
					echo '<td>'.$row['address2'].', '.$row['county'].'</td>';
					echo '<td>'.$row['postcode'].'</td>';
					echo '<td>'.sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE)).'</td>';
					echo '<td>'.$row['age'].'</td>';
					echo '<td>'.$row['screening_score'].'</td>';

					$comment_html = '<td>&nbsp;</td>';
					foreach ( $candidate_details->candidate_notes->comments as $comment ) {
						if ( $comment['status'] == 0 ) {
							$comment_html = '<td>'.$comment['note'].' ('.$comment['username'].')</td>';
							break;
						}

					}
					echo $comment_html;
					echo '<td><a href="do.php?_action=read_candidate&amp;candidate_id='.$candidate_id.'">&nbsp; Detailed View &raquo; </a></td>';
					if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
					{
						echo '<td><a href="#" onclick="displaydetail(\'2_'.$candidate_id.'\'); return false;">&nbsp; screen &raquo;</a></td>';
						echo '<td><a href="/do.php?_action=fill_vacancy&amp;id='.$vacancy->id.'&amp;pc='.htmlentities($vacancy->postcode).'&amp;cd_id='.$row['id'].'&amp;radius='.$vacancy->radius.'">&nbsp; approve candidate &raquo;</a></td>';
						echo '<td><a href="/do.php?_action=fill_vacancy&amp;id='.$vacancy->id.'&amp;pc='.htmlentities($vacancy->postcode).'&amp;cd_id='.$row['id'].'&amp;radius='.$vacancy->radius.'&amp;rmv=1">&nbsp; remove candidate &raquo;</a></td>';
					}
					echo '</tr>';

					echo '<tr id="detail_2_'.$candidate_id.'" style="display:none;">';
					echo '	<td colspan="10" style="text-align:center" >';
					echo ' <img src="images/candidate_loader.gif" /> ';
					echo '	</td>';
					echo '</tr>';
				}
			}
		}
		else {
			throw new DatabaseException($link, $sql);
		}
		?>
		</tbody>
	</table>
</div>

<div id="tab-3">
	<h3>Candidates approved for this role</h3>
	<table class="resultset">
		<thead>
		<tr>
			<th>Candidate Name</th>
			<th>Address</th>
			<th>Postcode</th>
			<th>Distance (miles)</th>
			<th>Age</th>
			<th>Screening Score</th>
			<th>Last Action</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>

		<?php
		$sql = <<<HEREDOC
	SELECT
		candidate.*,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d')) AS age,
		users.username as learner_name,
		SQRT(POWER(ABS($vacancy->easting - easting), 2) + POWER(ABS($vacancy->northing - northing), 2)) AS distance
	FROM
		candidate 
		LEFT JOIN users 
		ON (candidate.firstnames = users.firstnames 
		AND candidate.surname = users.surname
		AND UPPER(candidate.national_insurance) = UPPER(users.ni)
		)
	WHERE
		candidate.id in ( select candidate_applications.candidate_id from candidate_applications where candidate_applications.vacancy_id = $vacancy->id and candidate_applications.application_status = 1)
		AND candidate.status_code != 18
	GROUP BY 
		candidate.id
	ORDER BY
    	distance,
		candidate.surname;
HEREDOC;

		$st = $link->query($sql);

		$candidates_unenrolled = '';
		$candidates_unenrolled_count = 0;
		$candidates_enrolled = '';
		$candidates_enrolled_count = 0;

		if($st) {
			while( $row = $st->fetch() ) {
				$candidate_id = $row['id'];
				$candidate_details = Candidate::loadFromDatabase($link, $candidate_id);
				$candidate_details->age = $row['age'];
				$candidate_details->learner_name = $row['learner_name'];
				$candidate_details->distance = $row['distance'];

				// load the candidate history
				$candidate_details->candidate_notes = CandidateNotes::loadFromDatabase($link, $candidate_id);

				$screen_level = 'low';
				if ( $row['screening_score'] >= 45 && $row['screening_score'] <= 70 ) {
					$screen_level = 'med';
				}
				else if ( $row['screening_score'] >= 70 ) {
					$screen_level = 'high';
				}

				if ( $row['learner_name'] != NULL ) {
					$candidates_enrolled .= '<tr id="user_4_'.$candidate_id.'" class="shortrecord '.$screen_level.'">';
					$candidates_enrolled .= '<td><a href="/do.php?_action=read_user&amp;username='.$row['learner_name'].'">'.$row['firstnames'].' '.$row['surname'].' &raquo;</a></td>';
					$candidates_enrolled .= '<td>'.$row['address2'].', '.$row['county'].'</td>';
					$candidates_enrolled .= '<td>'.$row['postcode'].'</td>';
					$candidates_enrolled .= '<td>'.sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE)).'</td>';
					$candidates_enrolled .= '<td>'.$row['age'].'</td>';
					$candidates_enrolled .= '<td>'.$row['screening_score'].'</td>';
					if ( isset($candidate_details->candidate_notes->comments[0]['note']) ) {
						$candidates_enrolled .= '<td>'.$candidate_details->candidate_notes->comments[0]['note'].' ('.$candidate_details->candidate_notes->comments[0]['username'].')</td>';
					}
					else {
						$candidates_enrolled .= '<td>&nbsp;</td>';
					}
					if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
						$candidates_enrolled .= '<td><a href="#" onclick="displaydetail(\'4_'.$candidate_id.'\'); return false;">view &raquo;</a></td>';
					$candidates_enrolled .= '</tr>';
					$candidates_enrolled .= $candidate_details->render_candidate_details($link, 4);
					$candidates_enrolled_count++;
				}
				else {
					$candidates_unenrolled .= '<tr id="user_3_'.$candidate_id.'" class="shortrecord '.$screen_level.'" >';
					$candidates_unenrolled .= '<td>'.$row['firstnames'].' '.$row['surname'].'</td>';
					$candidates_unenrolled .= '<td>'.$row['address2'].', '.$row['county'].'</td>';
					$candidates_unenrolled .= '<td>'.$row['postcode'].'</td>';
					$candidates_unenrolled .= '<td>'.sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE)).'</td>';
					$candidates_unenrolled .= '<td>'.$row['age'].'</td>';
					$candidates_unenrolled .= '<td>'.$row['screening_score'].'</td>';

					$comment_html = '<td>&nbsp;</td>';
					foreach ( $candidate_details->candidate_notes->comments as $comment ) {
						if ( $comment['status'] == 0 ) {
							$comment_html = '<td>'.$comment['note'].' ('.$comment['username'].')</td>';
							break;
						}

					}

					$candidates_unenrolled .= $comment_html;

					//$candidates_unenrolled .= '<td><a href="#" onclick="displayform(\'3_'.$candidate_id.'\'); return false;">&nbsp; view and move to employer selection &raquo;</a></td>';
					if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
					{
						$candidates_unenrolled .= '<td><a href="/do.php?_action=fill_vacancy&amp;id='.$vacancy->id.'&amp;pc='.htmlentities($vacancy->postcode).'&amp;cd_id='.$row['id'].'&amp;radius='.$vacancy->radius.'&amp;send_to_emp=1">&nbsp; move to employer selection &raquo;</a></td>';
						$candidates_unenrolled .= '<td><a href="/do.php?_action=fill_vacancy&amp;id='.$vacancy->id.'&amp;pc='.htmlentities($vacancy->postcode).'&amp;cd_id='.$row['id'].'&amp;radius='.$vacancy->radius.'&amp;rmv=1">&nbsp; remove from vacancy &raquo;</a></td>';
					}
					$candidates_unenrolled .= '</tr>';

					$candidates_unenrolled .= $candidate_details->render_candidate_details($link, 3, $vacancy->id);
					$candidates_unenrolled_count++;
				}
				if ( $candidates_unenrolled_count > 0 ) {

				}
			}
		}
		else {
			throw new DatabaseException($link, $sql);
		}
//}
		if ( isset($candidates_unenrolled) ) {
			echo $candidates_unenrolled;
		}
		?>
		</tbody>
	</table>
</div>
<div id="tab-4">
	<h3>Candidates selected by employer</h3>
	<table class="resultset">
		<thead>
		<tr>
			<th>Candidate Name</th>
			<th>Address</th>
			<th>Postcode</th>
			<th>Distance (miles)</th>
			<th>Age</th>
			<th>Screening Score</th>
			<th>Last Action</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>

		<?php
		$sql = <<<HEREDOC
	SELECT
		candidate.*,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d')) AS age,
		users.username as learner_name,
		SQRT(POWER(ABS($vacancy->easting - easting), 2) + POWER(ABS($vacancy->northing - northing), 2)) AS distance
	FROM
		candidate
		LEFT JOIN users
		ON (candidate.firstnames = users.firstnames
		AND candidate.surname = users.surname
		AND UPPER(candidate.national_insurance) = UPPER(users.ni)
		)
	WHERE
		candidate.id in ( select candidate_applications.candidate_id from candidate_applications where candidate_applications.vacancy_id = $vacancy->id and candidate_applications.application_status = 1)
		AND candidate.status_code = 18 AND candidate.username is null 
		AND candidate.`enrolled` = $vacancy->id
	GROUP BY
		candidate.id
	ORDER BY
    	distance,
		candidate.surname;
HEREDOC;

		$st = $link->query($sql);

		$candidates_unenrolled = '';
		$candidates_unenrolled_count = 0;
		$candidates_enrolled = '';
		$candidates_enrolled_count = 0;

		if($st) {
			while( $row = $st->fetch() ) {
				$candidate_id = $row['id'];
				$candidate_details = Candidate::loadFromDatabase($link, $candidate_id);
				$candidate_details->age = $row['age'];
				$candidate_details->learner_name = $row['learner_name'];
				$candidate_details->distance = $row['distance'];

				// load the candidate history
				$candidate_details->candidate_notes = CandidateNotes::loadFromDatabase($link, $candidate_id);

				$screen_level = 'low';
				if ( $row['screening_score'] >= 45 && $row['screening_score'] <= 70 ) {
					$screen_level = 'med';
				}
				else if ( $row['screening_score'] >= 70 ) {
					$screen_level = 'high';
				}

				if ( $row['learner_name'] != NULL ) {
					$candidates_enrolled .= '<tr id="user_4_'.$candidate_id.'" class="shortrecord '.$screen_level.'">';
					$candidates_enrolled .= '<td><a href="/do.php?_action=read_user&amp;username='.$row['learner_name'].'">'.$row['firstnames'].' '.$row['surname'].' &raquo;</a></td>';
					$candidates_enrolled .= '<td>'.$row['address2'].', '.$row['county'].'</td>';
					$candidates_enrolled .= '<td>'.$row['postcode'].'</td>';
					$candidates_enrolled .= '<td>'.sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE)).'</td>';
					$candidates_enrolled .= '<td>'.$row['age'].'</td>';
					$candidates_enrolled .= '<td>'.$row['screening_score'].'</td>';
					if ( isset($candidate_details->candidate_notes->comments[0]['note']) ) {
						$candidates_enrolled .= '<td>'.$candidate_details->candidate_notes->comments[0]['note'].' ('.$candidate_details->candidate_notes->comments[0]['username'].')</td>';
					}
					else {
						$candidates_enrolled .= '<td>&nbsp;</td>';
					}
					if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
						$candidates_enrolled .= '<td><a href="#" onclick="displaydetail(\'4_'.$candidate_id.'\'); return false;">view &raquo;</a></td>';
					$candidates_enrolled .= '</tr>';
					$candidates_enrolled .= $candidate_details->render_candidate_details($link, 4);
					$candidates_enrolled_count++;
				}
				else {
					$candidates_unenrolled .= '<tr id="user_3_'.$candidate_id.'" class="shortrecord '.$screen_level.'" >';
					$candidates_unenrolled .= '<td>'.$row['firstnames'].' '.$row['surname'].'</td>';
					$candidates_unenrolled .= '<td>'.$row['address2'].', '.$row['county'].'</td>';
					$candidates_unenrolled .= '<td>'.$row['postcode'].'</td>';
					$candidates_unenrolled .= '<td>'.sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE)).'</td>';
					$candidates_unenrolled .= '<td>'.$row['age'].'</td>';
					$candidates_unenrolled .= '<td>'.$row['screening_score'].'</td>';

					$comment_html = '<td>&nbsp;</td>';
					foreach ( $candidate_details->candidate_notes->comments as $comment ) {
						if ( $comment['status'] == 0 ) {
							$comment_html = '<td>'.$comment['note'].' ('.$comment['username'].')</td>';
							break;
						}

					}

					$candidates_unenrolled .= $comment_html;
					if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
					{
						$candidates_unenrolled .= '<td><a href="#" onclick="displayform(\'3_'.$candidate_id.'\'); return false;">&nbsp; view and enrol &raquo;</a></td>';
						$candidates_unenrolled .= '<td><a href="/do.php?_action=fill_vacancy&amp;id='.$vacancy->id.'&amp;pc='.htmlentities($vacancy->postcode).'&amp;cd_id='.$row['id'].'&amp;radius='.$vacancy->radius.'&amp;rmv=1">&nbsp; remove from vacancy &raquo;</a></td>';
					}
					$candidates_unenrolled .= '</tr>';

					$candidates_unenrolled .= $candidate_details->render_candidate_details($link, 3, $vacancy->id);
					$candidates_unenrolled_count++;
				}
				if ( $candidates_unenrolled_count > 0 ) {

				}
			}
		}
		else {
			throw new DatabaseException($link, $sql);
		}
//}
		if ( isset($candidates_unenrolled) ) {
			echo $candidates_unenrolled;
		}
		?>
		</tbody>
	</table>
</div>
<div id="tab-5">
	<h3>Successful Candidates converted to learners for this employer</h3>
	<table class="resultset">
		<thead>
		<tr>
			<th>Candidate Name</th>
			<th>Address</th>
			<th>Postcode</th>
			<th>Distance (miles)</th>
			<th>Age</th>
			<th>Screening Score</th>
			<th>Comments</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php
		//if ( isset($candidates_enrolled) ) {
		//	echo $candidates_enrolled;
		//}
		$sql = <<<HEREDOC
	SELECT
		candidate.*,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d')) AS age,
		users.username as learner_name,
		SQRT(POWER(ABS($vacancy->easting - candidate.easting), 2) + POWER(ABS($vacancy->northing - candidate.northing), 2)) AS distance
	FROM
		candidate INNER JOIN users ON candidate.`username` = users.`username`
		INNER JOIN vacancies ON users.`employer_id` = vacancies.`employer_id`
		INNER JOIN candidate_applications ON candidate.id = candidate_applications.`candidate_id` AND candidate_applications.`vacancy_id` = vacancies.id AND candidate_applications.application_status = 1
#		LEFT JOIN users
#		ON (candidate.firstnames = users.firstnames
#		AND candidate.surname = users.surname
#		AND UPPER(candidate.national_insurance) = UPPER(users.ni)
#		)
	WHERE
		#candidate.id in ( select candidate_applications.candidate_id from candidate_applications where candidate_applications.vacancy_id = $vacancy->id and candidate_applications.application_status = 1)
		#AND candidate.username IS NOT NULL
		candidate_applications.`vacancy_id` = $vacancy->id AND candidate.username IS NOT NULL
	GROUP BY
		candidate.id
	ORDER BY
    	distance,
		candidate.surname;
HEREDOC;

		$st = $link->query($sql);
		$candidates_enrolled = '';
		if($st)
		{
			while( $row = $st->fetch() )
			{
				$candidate_id = $row['id'];
				$candidate_details = Candidate::loadFromDatabase($link, $candidate_id);
				$candidate_details->age = $row['age'];
				$candidate_details->learner_name = $row['learner_name'];
				$candidate_details->distance = $row['distance'];

				// load the candidate history
				$candidate_details->candidate_notes = CandidateNotes::loadFromDatabase($link, $candidate_id);

				$screen_level = 'low';
				if ( $row['screening_score'] >= 45 && $row['screening_score'] <= 70 ) {
					$screen_level = 'med';
				}
				else if ( $row['screening_score'] >= 70 ) {
					$screen_level = 'high';
				}

				if ( $row['learner_name'] != NULL )
				{
					$candidates_enrolled .= '<tr id="user_4_'.$candidate_id.'" class="shortrecord '.$screen_level.'">';
					$candidates_enrolled .= '<td><a href="/do.php?_action=read_user&amp;username='.$row['learner_name'].'">'.$row['firstnames'].' '.$row['surname'].' &raquo;</a></td>';
					$candidates_enrolled .= '<td>'.$row['address2'].', '.$row['county'].'</td>';
					$candidates_enrolled .= '<td>'.$row['postcode'].'</td>';
					$candidates_enrolled .= '<td>'.sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE)).'</td>';
					$candidates_enrolled .= '<td>'.$row['age'].'</td>';
					$candidates_enrolled .= '<td>'.$row['screening_score'].'</td>';
					if ( isset($candidate_details->candidate_notes->comments[0]['note']) ) {
						$candidates_enrolled .= '<td>'.$candidate_details->candidate_notes->comments[0]['note'].' ('.$candidate_details->candidate_notes->comments[0]['username'].')</td>';
					}
					else {
						$candidates_enrolled .= '<td>&nbsp;</td>';
					}
					//$candidates_enrolled .= '<td><a href="#" onclick="displaydetail(\'4_'.$candidate_id.'\'); return false;">view &raquo;</a></td>';
					$candidates_enrolled .= '</tr>';
					$candidates_enrolled .= $candidate_details->render_candidate_details($link, 4);
					$candidates_enrolled_count++;
				}
			}
		}
		if ( isset($candidates_enrolled) )
		{
			echo $candidates_enrolled;
		}
		?>
		</tbody>
	</table>
</div>
</div>
</div>
<?php include_once('templates/layout/tpl_footer.php'); ?>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
		$('#tabs > div').hide();
		// ---------------------------
		// default load position
		$('#tabs <?php echo $current_tab; ?>').show();
		$('#tabs ul li a[href=<?php echo $current_tab; ?>]').parent().addClass('active');

		$('#tabs ul li a').click(function(){
			$('#tabs ul li').removeClass('active');
			$(this).parent().addClass('active');
			var currentTab = $(this).attr('href');
			$('#tabs > div').hide();
			$(currentTab).show();

			return false;
		});
		// --------------------------

	<?php
	if ( isset($_REQUEST['display']) ) {
		echo '$("[id^=detail_]").filter("[id$='.$_REQUEST['display'].']").each( function() {';
		echo "\n  candidate_location = $(this).prop('id').split('_');\n";
		echo "	$('#tabs > div').hide();\n";
		echo "  $('#tabs ul li').removeClass('active');\n";
		echo "  $('#tabs #tab-'+candidate_location[1]).show();\n";
		echo "  $('#tabs ul li a[href=#tab-'+candidate_location[1]+']').parent().addClass('active');\n";
		echo "  displaydetail(candidate_location[1]+'_'+candidate_location[2]);\n";
		echo '});';
	}
	?>

		// if the feedback element has content show it
		if ( '&nbsp;' != $('#feedback').html() ) {
			$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
			$('#feedback').slideDown('2000'); //.delay('1500').slideUp('2000');
		}

		$('#feedback').click(function(){
			$('#feedback').slideUp('2000');
		});
	});

	function div_filter_crumbs_onclick(div) {
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	function displaydetail(tr) {
		var detail_tr = 'detail_'+tr;
		var user_tr = 'user_'+tr;
		var candid = tr.split("_");
		var table_row = document.getElementById(detail_tr);
		var user_row = document.getElementById(user_tr);

		var current_status = table_row.style.display;

		$("tr[id^=detail]").each(function() {
			$(this).css('display','none');
		});
		$("tr[id^=user]").each(function() {
			//$(this).css('background-color','#fff');
		});
		// IE sillyness - check for table-row conformance IE7 +
		if( $.browser.msie && $.browser.version < 7 ) {
			if ( current_status != 'block' ) {
				table_row.style.display = 'block';
				//user_row.style.backgroundColor = '#DCE5CD';
			}
		}
		else {
			if ( current_status != 'table-row' ) {
				table_row.style.display = 'table-row';
				//user_row.style.backgroundColor = '#DCE5CD';
			<?php if ( isset($vacancy) ) { ?>
				var request = ajaxRequest('do.php?_action=ajax_display_candidate_screening','candid='+candid[1]+'&tabid='+candid[0]+'&vacid='+<?php echo $vacancy->id; ?>);
				if ( request.responseText.match('/^Successfully/') ) {
					alert('There has been a problem finding candidate screening');
				}
				else {
					$("tr[id="+detail_tr+"] td:first").html(request.responseText);
				}
				<?php } ?>
			}
		}
	}

	function displayform(tr) {
		var detail_tr = 'detail_'+tr;
		var user_tr = 'user_'+tr;
		var candid = tr.split("_");
		var table_row = document.getElementById(detail_tr);
		var user_row = document.getElementById(user_tr);

		var current_status = table_row.style.display;

		$("tr[id^=detail]").each(function() {
			$(this).css('display','none');
		});
		$("tr[id^=user]").each(function() {
			//$(this).css('background-color','#fff');
		});
		// IE sillyness - check for table-row conformance IE7 +
		if( $.browser.msie && $.browser.version < 7 ) {
			if ( current_status != 'block' ) {
				table_row.style.display = 'block';
				//user_row.style.backgroundColor = '#DCE5CD';
			}
		}
		else {
			if ( current_status != 'table-row' ) {
				table_row.style.display = 'table-row';
			}
		}
	}

	function open_new_window(URL) {
		var NewWindow = window.open(URL,"vacancy_screen","toolbar=no,menubar=0,status=0,copyhistory=0,location=no,scrollbars=yes,resizable=0,location=0,Width=920,Height=730") ;
		NewWindow.location.href = URL;
	}

	function setscreening(score, formid) {
		var formname = document.getElementById('screen_'+formid);
		formname.screening_score.value = score;
		formname.submit();
	}


	<?php
	// if we have candidate notes, then output the functionality to allow
	// saving of the note.
	echo CandidateNotes::render_js();
	?>

</script>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>
</body>
</html>
