<?php
class view_reviews_report implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewReviewsReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_reviews_report", "View Reviews Report");

/*
		//
		$trsql = <<<HEREDOC
SELECT DISTINCT assessor_review.tr_id, courses.`frequency`, courses.`subsequent`, tr.start_date FROM assessor_review
INNER JOIN courses_tr ON assessor_review.`tr_id` = courses_tr.`tr_id`
INNER JOIN courses ON courses.id = courses_tr.`course_id`
INNER JOIN tr on tr.id = assessor_review.tr_id
HEREDOC;

		$trst = $link->query($trsql);
		if($trst)
		{
			while($trrow = $trst->fetch())
			{
				$tr_id = $trrow['tr_id'];
				$first = $trrow['subsequent'];
				$subsequent = $trrow['frequency'];
				$start_date = $trrow['start_date'];
				$reviewsql = "select id, tr_id, due_date, meeting_date from assessor_review where tr_id = '$tr_id' AND (assessor_review.meeting_date != '0000-00-00') order by meeting_date";
				$reviewst = $link->query($reviewsql);
				if($reviewst)
				{
					$index = 0;
					while($reviewrow = $reviewst->fetch())
					{
						$index++;
						$review_id = $reviewrow['id'];
						if($index==1)
						{
                            $meeting_date = $start_date;
							$planned = new Date($start_date);
							if($first==1)
								$planned->addMonths($first);
							else
								$planned->addDays($first*7);

							DAO::execute($link, "update assessor_review set due_date = '$planned' where id = '$review_id'");
						}
						else
						{

							if($planned->after($meeting_date) || DB_NAME=='am_gigroup' || DB_NAME=='am_aet' || DB_NAME=='am_baltic')
								$planned = new Date($meeting_date);

							if($subsequent==1)
								$planned->addMonths($subsequent);
							else
								$planned->addDays($subsequent*7);

        					DAO::execute($link, "update assessor_review set due_date = '$planned' where id = '$review_id'");
						}

                        $meeting_date = $reviewrow['meeting_date'];
					}
				}
			}
		}


		//
        */


		require_once('tpl_view_reviews_report.php');
	}
}
?>