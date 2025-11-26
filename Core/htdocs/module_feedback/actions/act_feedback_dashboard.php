<?php
class feedback_dashboard extends ActionController
{
    public function indexAction(PDO $link)
    {
        $startDate = date('Y')-1 . '-01-01';
        $endDate = date("Y-m-t", strtotime(date('Y-m-d')));

        // $_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=feedback_dashboard", "View Feedback Dashboard");

        $avgPanelStart = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : $startDate;
        $avgPanelEnd = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : $endDate;

        $rebookPanelStart = isset($_REQUEST['rebookPanelStart']) ? $_REQUEST['rebookPanelStart'] : $startDate;
        $rebookPanelEnd = isset($_REQUEST['rebookPanelEnd']) ? $_REQUEST['rebookPanelEnd'] : $endDate;

        $rebookPanelPieChart = $this->rebookPanelPie($link, $rebookPanelStart, $rebookPanelEnd);

        $avgPanelData = $this->refreshAvgPanelAction($link, $avgPanelStart, $avgPanelEnd);

        include_once('tpl_feedback_dashboard.php');
    }

    public function refreshAvgPanelAction(PDO $link, $sd, $ed)
    {
        $sql = new SQLStatement("SELECT
        ROUND(AVG(q1)) AS q1_avg,
        ROUND(AVG(q2)) AS q2_avg,
        ROUND(AVG(q3)) AS q3_avg,
        ROUND(AVG(q4)) AS q4_avg,
        ROUND(AVG(q5)) AS q5_avg,
        ROUND(AVG(q6)) AS q6_avg
      FROM
        learner_feedbacks
      ;
        ");

        $sd = Date::toMySQL($sd);
        $ed = Date::toMySQL($ed);

        $sql->setClause("WHERE learner_feedbacks.created_at BETWEEN '$sd' AND '$ed'");

        $html = '';

        $row = DAO::getObject($link, $sql->__toString());
        if(isset($row->q1_avg))
        {
            $html .= '<table class="table table-bordered">';
            $html .= '<thead class="bg-gray"><tr>';
            $html .= '<th>Question</th><th>Avg. Score</th>';
            $html .= '</tr></thead>';
            $html .= '<tbody>';
            $html .= '<tr>';
            $html .= '<td>1. How was the booking process for your electric vehicle training course?</td>';
            $html .= '<td class="text-center"><input class="questionRating" value="' . $row->q1_avg . '" class="rating-loading" data-size="xs">' . $row->q1_avg . ' </td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>2. Did the Joining instructions provide all the information you required to get you to your training course?</td>';
            $html .= '<td class="text-center"><input class="questionRating" value="' . $row->q2_avg . '" class="rating-loading" data-size="xs">' . $row->q2_avg . ' </td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>3. How easy was the process of getting set up on to the IMI Vocanto Platform?</td>';
            $html .= '<td class="text-center"><input class="questionRating" value="' . $row->q3_avg . '" class="rating-loading" data-size="xs">' . $row->q3_avg . ' </td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>4. Once you got on to Vocanto- how did you find the quality of the online training material?</td>';
            $html .= '<td class="text-center"><input class="questionRating" value="' . $row->q4_avg . '" class="rating-loading" data-size="xs">' . $row->q4_avg . ' </td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>5. Please rate the face to face training facilities including the workshops and equipment provided for the training.</td>';
            $html .= '<td class="text-center"><input class="questionRating" value="' . $row->q5_avg . '" class="rating-loading" data-size="xs">' . $row->q5_avg . ' </td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>6. Please rate your course trainer on their knowledge and delivery of the material during the week.</td>';
            $html .= '<td class="text-center"><input class="questionRating" value="' . $row->q6_avg . '" class="rating-loading" data-size="xs">' . $row->q6_avg . ' </td>';
            $html .= '</tr>';
            $html .= '</tbody></table>';
        }
        else
        {
            $html .= '<span class="text-info"><i class="fa fa-info-circle"></i> No records found for this period.</span>';
        }       

        return $html;
    }

    public function questionsScoreBarChart(PDO $link, $sd, $ed)
    {
        $sql = new SQLStatement("SELECT
            SUM( IF(q9 = 'Yes', 1, 0) ) AS 'yes',
	        SUM( IF(q9 = 'No', 1, 0) ) AS 'no'
        FROM
            learner_feedbacks
        ;
        ");

        $sd = Date::toMySQL($sd);
        $ed = Date::toMySQL($ed);

        $sql->setClause("WHERE learner_feedbacks.created_at BETWEEN '$sd' AND '$ed'");

        $row = DAO::getObject($link, $sql->__toString());

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'Learners who want to rebook'];
        $options->plotOptions = (object)['pie' => (object )['innerSize' => 100, 'depth' => 45, 'allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true]];
        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->colorByPoint = true;
        $series->data = [
            (object)[
                'name' => 'Yes',
                'y' => $row->yes,
                'key' => 'Yes',
                'color' => '#00FF00',
            ],
            (object)[
                'name' => 'No',
                'y' => $row->no,
                'key' => 'No',
                'color' => '#FF0000',
            ],
        ];

        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function rebookPanelPie(PDO $link, $sd, $ed)
    {
        $sql = new SQLStatement("SELECT
            SUM( IF(q9 = 'Yes', 1, 0) ) AS 'yes',
	        SUM( IF(q9 = 'No', 1, 0) ) AS 'no'
        FROM
            learner_feedbacks
        ;
        ");

        $sd = Date::toMySQL($sd);
        $ed = Date::toMySQL($ed);

        $sql->setClause("WHERE learner_feedbacks.created_at BETWEEN '$sd' AND '$ed'");

        $row = DAO::getObject($link, $sql->__toString());

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'Learners who want to rebook'];
        $options->plotOptions = (object)['pie' => (object )['innerSize' => 100, 'depth' => 45, 'allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true]];
        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->colorByPoint = true;
        $series->data = [
            (object)[
                'name' => 'Yes',
                'y' => $row->yes,
                'key' => 'Yes',
                'color' => '#00FF00',
            ],
            (object)[
                'name' => 'No',
                'y' => $row->no,
                'key' => 'No',
                'color' => '#FF0000',
            ],
        ];

        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    private function getView(PDO $link)
    {
        $view = VoltView::getViewFromSession('ViewFeedbacksDashboard', 'ViewFeedbacksDashboard'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewFeedbacksDashboard'] = $this->buildView($link);
        }
        $filters = [
            'filter_from_submitted_date' => $_REQUEST['sd'],
            'filter_to_submitted_date' => $_REQUEST['ed'],
        ];
        $view->refresh($filters, $link);

        return $view;
    }

    
}