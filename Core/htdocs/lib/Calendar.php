<?php

abstract class Calendar
{
    protected $events = array();
    protected $year = 0;
    protected $queryString = null;

    protected $monthStamp = '';

    protected $monthReal = '';
    protected $daysInMonth = '';
    protected $weeksInMonth = '';
    protected $day = '';
    protected $firstDay = '';

    function __construct($year)
    {
        $this->year = intval($year);
    }

    abstract function draw();

    public function addEvent(CalendarEvent $event)
    {
        //echo $event->getStartYear() . '-' . $event->getStartMonth() . '-' . $event->getStartDay() . '<br />';
        $start = mktime(0, 0, 0, $event->getStartMonth(), $event->getStartDay(), $event->getStartYear());
        $end = mktime(23, 59, 59, $event->getEndMonth(), $event->getEndDay(), $event->getEndYear());
        $this->events[$event->getStartYear()][$event->getStartMonth()][$event->getStartDay()][] = $event;
    }

    public function addEvents($events)
    {
        foreach ($events as $event) {
            $this->addEvent($event);
        }
    }

    public function setQueryString($string)
    {
        $this->queryString = $string;
    }

    public static function getNextHalfHourTime()
    {
        $time = time();
        while (date('i', $time) != '00' and date('i', $time) != '30') {
            $time = $time + 60;
        }
        return $time;
    }
}

class UserCalendar
{
    private $data = array();
    public $id = null;

    function __construct($id, $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    function getColour()
    {
        if (isset($this->data['colour'])) {
            return $this->data['colour'];
        }
        return '#FFF';
    }
}

class CalendarEvent
{
    public $id = 0;
    private $title;
    private $year;
    private $month;
    private $day;
    private $startHour;
    private $startMinute;
    private $endHour;
    private $endMinute;
    private $allDay;
    private $start = 0;
    private $end = 0;
    private $calendar;
    private $description;

    /**
     * duration in minutes
     */
    function __construct($event_id, UserCalendar $calendar, $title, $description, $startYear, $startMonth, $startDay, $endYear, $endMonth, $endDay, $startHour = null, $startMinute = null, $endHour = null, $endMinute = null, $allDay = false)
    {
        // meta-data
        $this->id = $event_id ?: 0;
        $this->title = $title;
        $this->description = $description;
        $this->location = '';

        // start date
        $this->startYear = intval($startYear);
        $this->startMonth = intval($startMonth);
        $this->startDay = intval($startDay);

        // end date
        $this->endYear = intval($endYear);
        $this->endMonth = intval($endMonth);
        $this->endDay = intval($endDay);

        $this->allDay = $allDay;

        // calendar
        $this->calendar = $calendar;

        if ($startHour !== null) // type equivalence as well. why on earth is 0 seen as null. epic php fail
        {
            $this->setTimes($startHour, $startMinute, $endHour, $endMinute, $allDay);
        } else {
            $this->setTimes($startHour, $startMinute, $endHour, $endMinute, true);
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCalendar()
    {
        return $this->calendar;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }

    function setTimes($startHour, $startMinute, $endHour, $endMinute, $allDay = false)
    {
        $this->start = mktime($startHour, $startMinute, 0, $this->startMonth, $this->startDay, $this->startYear);
        $this->startHour = date('H', $this->start);
        $this->startMinute = date('i', $this->start);

        $this->end = mktime($endHour, $endMinute, 0, $this->endMonth, $this->endDay, $this->endYear);
        $this->endHour = date('H', $this->end);
        $this->endMinute = date('i', $this->end);

        /*$this->startHour = intval($startHour);
        $this->startMinute = intval($startMinute);

        $this->allDay = intval($allDay);

        $startTime = mktime($this->startHour, $this->startMinute, 00, $this->month, $this->day, $this->year);
        $this->startHour = date('H', $startTime);
        $this->startMinute = date('i', $startTime);


        $endTime = mktime($endHour, $endMinute, 00, $this->month, $this->day, $this->year);
        $this->endHour = date('H', $endTime);
        $this->endMinute = date('i', $endTime);

        $this->start = mktime(0, 0, 0, $this->month, $this->day, $this->year);
        $this->end = mktime(23, 59, 59, $this->month, $this->day, $this->year);*/
    }

    function getEventTimeLabel()
    {
        if ($this->allDay) {
            return 'All Day';
        } else {
            return $this->startHour . ':' . $this->startMinute . ' to ' . $this->endHour . ':' . $this->endMinute;
        }
    }

    function getStartYear()
    {
        return $this->startYear;
    }

    function getStartMonth()
    {
        return $this->startMonth;
    }

    function getStartDay()
    {
        return $this->startDay;
    }

    function getEndYear()
    {
        return $this->endYear;
    }

    function getEndMonth()
    {
        return $this->endMonth;
    }

    function getEndDay()
    {
        return $this->endDay;
    }

    function getStartKey()
    {
        if ($this->allDay) {
            return 0;
        }
        return $this->startHour . $this->startMinute . $this->endHour . $this->endMinute;
    }

    function getTitle()
    {
        return $this->title;
    }

    public static function validate($data, $new = true)
    {
        $errors = array();

        // general field validation
        if (empty($data['title'])) {
            $errors[] = 'You have not selected a title for this event';
        }
        if (empty($data['datefrom']) or empty($data['dateto'])) {
            $errors[] = 'You  must select a start and end date for this event';
        }
        if ($data['allday'] == 0 and (empty($data['datefromtime']) or empty($data['datetotime']))) {
            $errors[] = 'You must select a start time and end time if the event is not set to run all day';
        }

        // date-time validation
        $start = strtotime(str_replace('/', '-', $data['datefrom']) . ' ' . $data['datefromtime']);
        $end = strtotime(str_replace('/', '-', $data['dateto']) . ' ' . $data['datetotime']);

        if (!empty($data['datefromtime']) and !empty($data['datetotime']) and $data['allday'] == 0 and $start >= $end) {
            $errors[] = 'An event cannot start after it ends';
        }

        if ($new and $start < time()) {
            // Temporarily disabled - i can see a valid use-case for creating an event retrospectively
            // as a means of logging it
            //$errors[] = 'You cannot create an event in the past';
        }
        return $errors;
    }

    public static function save($db, $data)
    {
        $command = $clause = '';
        // INSERT
        if (empty($data['event_id'])) {
            $command = 'INSERT INTO';
        } else {
            $command = 'UPDATE';
            $clause = ' WHERE event_id = "' . intval($$data['event_id']) . '"';
        }

        $db->query("
			$command 
				calendar_event
			SET
					calendar_id = '" . intval($_REQUEST['calendar_id']) . "'
					, title = '" . addslashes((string)$_REQUEST['title']) . "'
					, datefrom = '" . addslashes((string)$_REQUEST['datefrom']) . "'
					, datefromtime = '" . addslashes((string)$_REQUEST['datefromtime']) . "'
					, dateto = '" . addslashes((string)$_REQUEST['dateto']) . "'
					, datetotime = '" . addslashes((string)$_REQUEST['datetotime']) . "'
					, allday = '" . intval($_REQUEST['allday']) . "'
					, location = '" . addslashes((string)$_REQUEST['location']) . "'
					, description = '" . addslashes((string)$_REQUEST['description']) . "'
					, username = '" . addslashes((string)$_SESSION['user']->username) . "'
			$clause
		");

    }
}

class Daily_Calendar extends Calendar
{
    function __construct($year, $month, $day)
    {
        parent::__construct($year);
        $this->month = intval($month);
        $this->day = $day;
        $this->monthStamp = mktime(0, 0, 0, $this->month, $this->day, $this->year);
    }

    public function draw()
    {
        $html = '<h4>Viewing events for ' . date('jS F Y', $this->monthStamp) . '</h4>';
        $html .= '<table class="calendar-daily">';

        if (sizeof($this->events) > 0) {
            $html .= '<table class="calendar-daily">';

            foreach ($this->events as $time => $items) {
                $html .= '<tr>';
                $html .= '<td class="daylabel">' . implode('<br />', explode(' ', $items[0]->getEventTimeLabel())) . '</td>';
                $html .= '<td><ul>';
                foreach ($items as $key => $event) {
                    $html .= '<li>' . $event->getTitle();
                    $location = $event->getLocation();
                    if (!empty($location)) {
                        $html .= '<p style="font-weight: normal; color: #aaa; margin-top: 2px; font-style: italic;">Location: ' . $location . '</p>';
                    }
                    $html .= '<p class="description">' . $event->getDescription() . '</p></li>';
                }
                $html .= '</ul></td>';
            }

            $html .= '</table>';
        } else {
            $html .= '<p>No events</p>';
        }
        return $html;
    }

    public function addEvent(CalendarEvent $event)
    {
        if ($this->day == $event->getStartDay() and $this->month == $event->getStartMonth() and $this->year == $event->getStartYear()) {
            $this->events[$event->getStartKey()][] = $event;
            ksort($this->events);
        }
    }
}

class Weekly_Calendar extends Calendar
{
    function __construct($year, $month, $day)
    {
        parent::__construct($year);
        $this->month = intval($month);
        $monthStamp = mktime(0, 0, 0, $this->month, 1, $this->year);
        $this->monthReal = date('F', $monthStamp);
        $this->daysInMonth = date('t', $monthStamp);
        $this->weeksInMonth = ceil($this->daysInMonth / 7);
        $this->day = $day;
    }

    private function getPrevWeek($timestamp)
    {
        $newtime = strtotime('-7 days', $timestamp);
        return array('d' => date('j', $newtime), 'm' => date('n', $newtime), 'y' => date('Y', $newtime));
    }

    private function getNextWeek($timestamp)
    {
        $newtime = strtotime('+7 days', $timestamp);
        return array('d' => date('j', $newtime), 'm' => date('n', $newtime), 'y' => date('Y', $newtime));
    }

    function draw()
    {
        $firstDay = mktime(0, 0, 0, $this->month, $this->day, $this->year);

        // fix start day if it's not a sunday :/
        if (date('D', $firstDay) != 'Sun') {
            $firstDay = strtotime('last sunday', $firstDay);
        }

        $prev = $this->getPrevWeek($firstDay);
        $next = $this->getNextWeek($firstDay);

        $html = '<h4><a id="cprev" class="goleft" href="?v=2&amp;y=' . $prev['y'] . '&amp;m=' . $prev['m'] . '&amp;d=' . $prev['d'] . '&amp;' . $this->queryString . '"><</a> ';
        $html .= 'Week beginning ' . date('jS F', $firstDay);
        $html .= ' <a id="cnext" class="goright" href="?v=2&amp;y=' . $next['y'] . '&amp;m=' . $next['m'] . '&amp;d=' . $next['d'] . '&amp;' . $this->queryString . '">></a></h4>';

        $day = (60 * 60 * 24);
        $html .= '<table class="calendar-weekly" border="1" cellpadding="4">';
        $html .= '<thead>';
        $html .= '<tr><th>Sunday <span>' . date('M j', $firstDay) . '</span></th><th>Monday <span>' . date('M j', $firstDay + $day) . '</span></th><th>Tuesday <span>' . date('M j', $firstDay + ($day * 2)) . '</span></th><th>Wednesday <span>' . date('M j', $firstDay + ($day * 3)) . '</span></th><th>Thursday <span>' . date('M j', $firstDay + ($day * 4)) . '</span></th><th>Friday <span>' . date('M j', $firstDay + ($day * 5)) . '</span></th><th>Saturday <span>' . date('M j', $firstDay + ($day * 6)) . '</span></th>';
        $html .= '</thead>';
        $html .= '<tbody><tr valign="top">';
        //print_r($this->events);
        for ($i = 1; $i <= 7; $i++) {
            $html .= '<td>';
            $aday = date('j', $firstDay + ($day * ($i - 1)));
            $amonth = date('n', $firstDay + ($day * ($i - 1)));
            $ayear = date('Y', $firstDay + ($day * ($i - 1)));

            if (isset($this->events[$ayear][$amonth][$aday])) {
                $rejig = array();

                $html .= '<ul>';
                foreach ($this->events[$ayear][$amonth][$aday] as $key => $event) {
                    $rejig[$event->getEventTimeLabel()][] = $event;
                }
                foreach ($rejig as $label => $events) {
                    $html .= '<li><span>' . $label . '</span>';
                    foreach ($events as $key => $event) {
                        $html .= '<a style="color:' . $event->getCalendar()->getColour() . '" href="?v=3&amp;y=' . $ayear . '&amp;m=' . $amonth . '&amp;d=' . $aday . '&amp;' . $this->queryString . '">' . $event->getTitle() . '</a><br />';
                    }
                    $html .= '</li>';
                }

                $html .= '</ul>';
            } else {
                $html .= '&nbsp;';
            }

            $html .= '</td>';
        }

        $html .= '</tr></tbody>';
        $html .= '</table>';

        return $html;
    }
}

class Monthly_Calendar extends Calendar
{
    protected $month = 0;
    protected $year = 0;

    function __construct($year, $month)
    {
        parent::__construct($year);
        $this->month = intval($month);
        $this->monthStamp = mktime(0, 0, 0, $this->month, 1, $this->year);
        $this->monthReal = date('F', $this->monthStamp);
        $this->daysInMonth = date('t', $this->monthStamp);
        $this->weeksInMonth = ceil($this->daysInMonth / 7);
        $this->firstDay = date('w', $this->monthStamp);
        $this->year = $year;
    }

    function getNextMonthYear()
    {
        $month = $this->month + 1;
        $year = $this->year;

        if ($this->month == 12) {
            $month = 1;
            $year = $year + 1;
        }

        return array('m' => $month, 'y' => $year);
    }

    function getPrevMonthYear()
    {
        $month = $this->month - 1;
        $year = $this->year;

        if ($this->month == 1) {
            $month = 12;
            $year = $year - 1;
        }
        return array('m' => $month, 'y' => $year);
    }

    function draw()
    {
        $prev = $this->getPrevMonthYear();
        $next = $this->getNextMonthYear();

        $html = '<h4><a id="cprev" class="goleft" href="?m=' . $prev['m'] . '&amp;y=' . $prev['y'] . '&amp;' . $this->queryString . '"><</a> ';
        $html .= $this->monthReal . ' ' . $this->year;
        $html .= ' <a id="cnext" class="goright" href="?m=' . $next['m'] . '&amp;y=' . $next['y'] . '&amp;' . $this->queryString . '">></a></h4>';

        $html .= '<table class="calendar-monthly">';
        $html .= '<thead>';
        $html .= '<tr><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th>';
        $html .= '</thead>';

        $html .= '<tbody>';
        $d = 0;
        for ($i = 0; $i <= $this->weeksInMonth; $i++) {
            $html .= '<tr valign="top">';
            for ($j = 1; $j <= 7; $j++) {
                $d++;
                $realDay = $d - $this->firstDay;
                $today = intval(date('Ynj'));
                $date = date('Ynj', mktime(0, 0, 0, $this->month, $realDay, $this->year));

                $color = '#FFFFFF';
                if ($today > $date) {
                    $class = 'before';
                } else {
                    if ($today < $date) {
                        $class = 'after';
                    } else {
                        if ($today == $date) {
                            $class = 'today';
                            $color = 'lightgrey';
                        } else {
                            $class = 'wtf';
                        }
                    }
                }


                if ($realDay > 0 and $realDay <= $this->daysInMonth) {
                    $html .= '<td style="background: ' . $color . '">';

                    $html .= '<a class="daylink" href="?v=3&amp;y=' . $this->year . '&amp;m=' . $this->month . '&amp;d=' . $realDay . '&amp;' . $this->queryString . '">' . ($realDay) . '</strong></a>';

                    if (isset($this->events[$this->year][$this->month][$realDay])) {
                        //echo $this->year . '-' . $this->month . '-' . $realDay . '<br />';
                        $html .= '<ul>';
                        foreach ($this->events[$this->year][$this->month][$realDay] as $key => $event) {
                            //$html .= '<li><span>' . $event->getEventTimeLabel() . '</span>: <a style="color:' . $event->getCalendar()->getColour() . '" href="?v=3&amp;y=' . $this->year . '&amp;m=' . $this->month . '&amp;d=' . $realDay . '&amp;' . $this->queryString . '">' . $event->getTitle() . '</a></li>';
                            $html .= '<li><span>' . $event->getEventTimeLabel() . '</span>: <a style="color:' . $event->getCalendar()->getColour() . '" href="do.php?_action=read_training_record&id=' . $event->id . '">' . $event->getTitle() . '</a></li>';
                            //pre($this);
                        }
                        $html .= '</ul>';
                    }
                    $html .= '</td>';
                } else {
                    $html .= '<td>&nbsp;</td>';
                }
            }
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }
}

class Mini_Monthly_Calendar extends Monthly_Calendar
{
    function __construct($year, $month)
    {
        parent::__construct($year, $month);
    }

    function draw()
    {
        $prev = $this->getPrevMonthYear();
        $next = $this->getNextMonthYear();

        $html = '<h4><a href="?m=' . $prev['m'] . '&amp;y=' . $prev['y'] . ' "><</a> ';
        $html .= $this->monthReal . ' ' . $this->year;
        $html .= ' <a href="?m=' . $next['m'] . '&amp;y=' . $next['y'] . '">></a></h4>';

        $html .= '<table border="1" cellpadding="4" class="calendar-monthly-mini">';
        $html .= '<thead>';
        $html .= '<tr><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th>';
        $html .= '</thead>';

        $html .= '<tbody>';
        $d = 0;
        for ($i = 0; $i <= $this->weeksInMonth; $i++) {
            $html .= '<tr valign="top">';
            for ($j = 1; $j <= 7; $j++) {
                $d++;
                $realDay = $d - $this->firstDay;
                if ($realDay > 0 and $realDay <= $this->daysInMonth) {
                    if (isset($this->events[$this->year][$this->month][$realDay])) {
                        $html .= '<td><a href="?v=3&amp;y=' . $this->year . '&amp;m=' . $this->month . '&amp;d=' . $realDay . '">' . ($realDay) . '</strong></a>';
                    } else {
                        $html .= '<td>' . ($realDay) . '</td>';
                    }
                } else {
                    $html .= '<td>&nbsp;</td>';
                }
            }
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }
}

?>