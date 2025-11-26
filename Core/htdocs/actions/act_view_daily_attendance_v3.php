<?php
class view_daily_attendance_v3 implements IAction
{
    public function execute(PDO $link)
    {
        $view = VoltView::getViewFromSession('primaryView', 'view_daily_attendance_v3'); /* @var $view View */
        if(is_null($view))
        {
            // Create new view object
            $view = $_SESSION['primaryView'] = $this->buildView($link);
        }

        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_daily_attendance_v3" , "Month View");

        require_once('tpl_view_daily_attendance_v3.php');
    }


    private function buildView(PDO $link)
    {
        $sql = <<<HEREDOC
SELECT DISTINCT
	YEAR(lessons.date) AS `year`,
	MONTH(lessons.date) AS `month`,
	DAY(lessons.date) AS `day`,
	lessons.date,
	DAYOFWEEK(lessons.date) AS `dayofweek`,
	lessons.id AS lesson_id,
	lessons.start_time,
	lessons.end_time,
	(SELECT module_title FROM attendance_modules INNER JOIN attendance_module_groups ON attendance_modules.`id` = attendance_module_groups.`module_id` WHERE lessons.`groups_id` = attendance_module_groups.`id`) AS module_title,
	(SELECT GROUP_CONCAT(attendance_module_groups.`title`) FROM attendance_module_groups WHERE lessons.`groups_id` = attendance_module_groups.`id`) AS group_title,
	(SELECT attendance_modules.`qualification_title` FROM attendance_modules INNER JOIN attendance_module_groups ON attendance_modules.`id` = attendance_module_groups.`module_id` WHERE lessons.`groups_id` = attendance_module_groups.`id`) AS qualification_title
	
FROM
	lessons
	
ORDER BY
	#MONTH(lessons.date), DAY(lessons.date)
	lessons.date
HEREDOC;


        $view = new VoltView('view_daily_attendance_v3', $sql);

        // Date filters
        $dateInfo = getdate();

        $start_date = '01/'.$dateInfo['mon'].'/'.$dateInfo['year'];
        $format = "WHERE `date` >= '%s'";
        $f = new VoltDateViewFilter('start_date', $format, $start_date);
        $f->setDescriptionFormat("From: %s");
        $view->addFilter($f);

        $end_date = $this->days_in_month($dateInfo['mon'], $dateInfo['year']).'/'.$dateInfo['mon'].'/'.$dateInfo['year'];
        $format = "WHERE `date` <= '%s'";
        $f = new VoltDateViewFilter('end_date', $format, $end_date);
        $f->setDescriptionFormat("To: %s");
        $view->addFilter($f);



        return $view;
    }



    private function renderView(PDO $link, VoltView $view)
    {
        $month = null;
        $student = null;
        $day = null;
        $year = null;

        $sql = $view->getSQLStatement()->__toString();

        $st = $link->query($sql);
        if(!$st){
            throw new DatabaseException($link, $sql);
        }

        $data = [];
        while($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $month_name = Date::to($row['date'], 'F Y');
            if(!isset($data[$month_name][$row['day']]))
                $data[$month_name][$row['day']] = [];

            $data[$month_name][$row['day']][] = (object)[
                'lesson_id' => $row['lesson_id'],
                'date' => $row['date'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'module_title' => $row['module_title'],
                'group_title' => $row['group_title'],
                'qualification_title' => $row['qualification_title'],
            ];
        }

        echo '<table class="resultset" border="0" cellspacing="0" cellpadding="12" style="page-break-after:always">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Month</th>';
        for($i = 1; $i <= 31; $i++)
            echo '<th>' . $i . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach($data AS $month_name => $month_data)
        {
            echo '<tr>';
            echo '<td>' . str_replace(' ', '&nbsp;', $month_name) . '</td>';
            $current_day = 1;
            foreach($month_data AS $day => $lessons)
            {
                for($current_day = $current_day; $current_day < $day; $current_day++)
                {
                    echo "<td></td>";
                }

                echo '<td>';
                foreach($lessons AS $lesson_detail)
                {
                    echo '<img style="background-color:blue;border-radius: 10px;" id="img'.$lesson_detail->lesson_id.'" src="/images/register/empty.png" class="RegisterIcon" '
                        .'onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" '
                        .'onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" '
                        .'onclick="window.location.href=\'do.php?_action=read_register&lesson_id='.$lesson_detail->lesson_id.'\';" />';
                    echo '<script language="JavaScript">var img = document.getElementById("img'.$lesson_detail->lesson_id.'");';
                    echo 'img.module="'.addslashes((string)$lesson_detail->module_title).'";';
                    echo 'img.group="'.addslashes((string)$lesson_detail->group_title).'";';
                    echo 'img.qualification="'.addslashes((string)$lesson_detail->qualification_title).'";';
                    echo 'img.time="'.Date::toShort($lesson_detail->date) . ', ' . addslashes(substr($lesson_detail->start_time,0,5)) . ' - ' . addslashes(substr($lesson_detail->end_time,0,5)).'";';
                    echo '</script>';
                }
                echo '</td>';

                $current_day++;
            }

            for($i = $current_day; $i <= 31; $i++)
                echo "<td></td>";

            echo '</tr>';
        }


    }


    private function days_in_month($month, $year)
    {
        if($month < 1 || $month > 12)
        {
            throw new Exception("Month cannot be '$month'");
        }

        $days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        $is_leap_year = false;
        if($year % 400 == 0)
        {
            $is_leap_year = true;
        }elseif($year % 100 == 0)
        {
            $is_leap_year = false;
        }elseif($year % 4 == 0)
        {
            $is_leap_year = true;
        }


        if($is_leap_year && $month == 2)
        {
            return 29;
        }
        else
        {
            return $days[$month - 1];
        }
    }


    private function getWeekdayMap($month, $year)
    {
        $map = array();

        $week_day = mktime(0,0,0,$month,1,$year);
        $week_day = getdate($week_day);
        $week_day = $week_day['wday']  + 1; // Sunday == 1 (MySQL convention)

        $days_in_month = $this->days_in_month($month, $year);

        for($i = 1; $i <= $days_in_month; $i++)
        {
            $map[$i] = $week_day;

            if(++$week_day > 7)
            {
                $week_day = 1;
            }
        }

        return $map;
    }
}
?>