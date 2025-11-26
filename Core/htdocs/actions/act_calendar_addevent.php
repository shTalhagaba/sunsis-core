<?php
class calendar_addevent implements IAction
{
	private $db = null;
	
	public function execute(PDO $link)
	{
		$this->db = $link;
		
		require_once('./lib/calendar.php');
		
		if(!isset($_REQUEST['v']))
		{
			$_REQUEST['v'] = 1;
		}
		if(!isset($_REQUEST['y']))
		{
			$_REQUEST['y'] = date('Y');
		}
		if(!isset($_REQUEST['m']))
		{
			$_REQUEST['m'] = date('n');
		}
		if(!isset($_REQUEST['d']))
		{
			$_REQUEST['d'] = date('j', strtotime('last sunday'));
		}

		
		$_SESSION['bc']->index=1;
		$_SESSION['bc']->add($link, 'do.php?_action=calendar_addevent', 'Add Event');		
		
		$errors = array();
		if(isset($_REQUEST['do']))
		{
			$errors = CalendarEvent::validate($_REQUEST);
			if(sizeof($errors) == 0)
			{
				CalendarEvent::save($link, $_REQUEST);
				$dateBits = explode('/', $_REQUEST['datefrom']);
				header('Location: /do.php?_action=calendar_view&v=2&d=' . $dateBits[0] . '&m=' . $dateBits[1] . '&y=' . $dateBits[2]);
			}
		}
		else
		{
			$_REQUEST['allday'] = $_REQUEST['calendar_id'] = 0;
			$_REQUEST['datefrom'] = date('d/m/Y');
			$start = Calendar::getNextHalfHourTime();
			$end = $start + (60 * 60);
			$_REQUEST['datefromtime'] = date('H:i', $start);
			$_REQUEST['datetotime'] = date('H:i', $end);
			$_REQUEST['dateto'] = date('d/m/Y');
			$_REQUEST['title'] = $_REQUEST['location'] = $_REQUEST['description'] = '';
		}
		
		require_once('./templates/tpl_calendar_addevent.php');
	}
	
	private function renderTimePicker($name)
	{
		$timeMargins = array('00', '30');
		$html = '<select name="' . $name . '" id="' . $name . '">';
		for($h = 0; $h <= 23; $h++)
		{
			foreach($timeMargins AS $key => $timeLabel)
			{
				$val = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . $timeLabel;
				$html .= '<option value="' . $val . '">' . $val . '</option>';
			}
		}
		$html .= '</select>';
		return $html;
	}
	
	private function renderCalendarList($value)
	{
		$html = '<select name="calendar_id">';
		$query = "
			SELECT
				*
			FROM
				calendar
		";
		$st = $this->db->query($query);

		if($st)
		{
			$org = null;
			while($row = $st->fetch())
			{
				$html .= '<option value="' . $row['calendar_id'] . '"' . ($value == $row['calendar_id'] ? ' selected="selected"' : '') . '>' . $row['title'] . '</option>';
			}
		}
		$html .= '</select>';
		return $html;
	}
}
?>