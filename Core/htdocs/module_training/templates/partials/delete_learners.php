<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-danger" onclick="deleteSelectedLearners();">
			<i class="fa fa-trash"></i> Delete Selected
		</span> &nbsp;
	</div>
	<div class="col-sm-2"></div>
</div>
<p></p>
<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold">Remove Learners</span>
		<br>
	</div>
	<div class="col-sm-12">
		<div class="callout callout-danger">
			<i class="fa fa-warning"></i> Using this functionality you can remove the learners from this course "<?php echo $course->title; ?>".<br>
			<i class="fa fa-warning"></i> Please note that this action is irreversible and cannot be undone.
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<span class="text-bold">Select Learners:</span>

		<?php
		$sql = <<<SQL
SELECT
	tr.id AS tr_id, tr.gender, tr.surname, tr.firstnames, tr.status_code, tr.l03,
	(SELECT title FROM training_groups WHERE id = tr.tg_id) AS tg_title,
	tr.start_date, tr.target_date, tr.username, organisations.legal_name,
	(SELECT CONCAT(COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,''), ')') FROM locations WHERE id = tr.employer_location_id) AS employer_location,
	contracts.title AS contract_title, groups.title AS group_title
FROM
	tr LEFT JOIN organisations ON tr.employer_id = organisations.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN groups ON groups.id = group_members.groups_id
WHERE
 	courses_tr.course_id = '{$course->id}'
ORDER BY
	tr.surname
;
SQL;
		$st = $link->query($sql);
		if(!$st)
		{
			throw new DatabaseException($link, $sql);
		}
		?>
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead><tr><th>&nbsp;</th><th>&nbsp;</th><th>L03</th><th>Surname</th><th>Firstname</th><th>Cohort</th><th>Training Group</th><th>Start Date</th><th>End Date</th><th>Username</th><th>Organisation</th><th>Contract</th></tr></thead>
				<tbody>
				<?php
				if($st->rowCount() == 0)
				{
					echo '<tr><td colspan="12"><i>No learner has been enrolled to this course.</i></td> </tr>';
				}
				else
				{
					echo '<form name="frmDeleteLearnersFromCourse" method="post" action="do.php?_action=delete_training_v2">';
					echo '<input type="hidden" name="course_id" value="' . $course->id . '">';
					while($row = $st->fetch())
					{
						echo '<td align="center"><input class="chkDeleteLearnersSelection" type="checkbox" name="learnersToRemove[]" onclick="learnersToRemove_onclick(this);" value="' . $row['tr_id'] . '" /></td>';
						echo '<td title=#'.$row['tr_id'] . '>';
						$folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
						$textStyle = '';
						switch($row['status_code'])
						{
							case 1:
								echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" />";
								break;

							case 2:
								echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" />";
								break;

							case 3:
							case 6:
								echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" />";
								break;

							case 4:
							case 5:
								echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" />";
								$textStyle = 'text-decoration:line-through;color:gray';
								break;

							default:
								echo '?';
								break;
						}
						echo '</td>';
						echo '<td align="left">' . HTML::cell($row['l03']) . "</td>";
						echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
						echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
						echo '<td align="left">' . HTML::cell($row['group_title']) . "</td>";
						echo '<td align="left">' . HTML::cell($row['tg_title']) . "</td>";
						echo '<td align="left">' . HTML::cell(Date::toShort($row['start_date'])) . "</td>";
						echo '<td align="left">' . HTML::cell(Date::toShort($row['target_date'])) . "</td>";
						echo '<td align="left" style="font-family:monospace">' . htmlspecialchars((string)$row['username']) . "</td>";
						echo '<td>' . HTML::cell($row['legal_name']) . '<br> &nbsp; <span class="small"><i class="fa fa-map-marker"></i> ' . HTML::cell($row['employer_location']) . '</span>' . '</td>';
						echo '<td align="left">' . htmlspecialchars((string)$row['contract_title']) . "</td>";
						echo '</tr>';
					}
					echo '</form>';
				}

				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	function learnersToRemove_onclick(element)
	{
		var row = element.parentNode.parentNode;

		if(element.checked == true)
		{
			row.style.backgroundColor = 'orange';
		}
		else
		{
			row.style.backgroundColor = '';
		}
	}

	function deleteSelectedLearners()
	{
		var selectedLearners = $('input[type=checkbox][class=chkDeleteLearnersSelection]:checked');
		if(selectedLearners.length == 0)
		{
			return alert('Please select at least one learner.');
		}

		if(!confirm('This action is irreversible and cannot be undone, are you sure you want to remove ' + selectedLearners.length + ' learner?'))
		{
			return;
		}

		var form = document.forms['frmDeleteLearnersFromCourse'];

		$.ajax({
			url:form.action,
			type:form.method,
			data:$(form).serialize()
		}).done(function (response, textStatus) {
				window.location.reload();
			}).fail(function (jqXHR, textStatus, errorThrown) {
				alert(textStatus + ': ' + errorThrown);
			});
	}
</script>