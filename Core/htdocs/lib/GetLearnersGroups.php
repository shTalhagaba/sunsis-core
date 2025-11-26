<?php
class GetLearnersGroups extends View
{

    public static function getInstance($link, $tr_id)
    {
        $key = 'view'.__CLASS__;

	    if($_SESSION['user']->isAdmin())
	    {
		    $where = '';
	    }
	    else
	    {
		    $provider_id = $_SESSION['user']->employer_id;
			$where = ' where courses_id = ' . $provider_id . ' ';
	    }
        if(true)
        {
            // Create new view object
            $sql = <<<HEREDOC
SELECT id, title
,(SELECT CONCAT(firstnames,' ',surname) FROM users WHERE users.id = groups.`tutor`) AS tutor
,(SELECT CONCAT(firstnames,' ',surname) FROM users WHERE users.id = groups.`assessor`) AS assessor
,(SELECT CONCAT(firstnames,' ',surname) FROM users WHERE users.id = groups.`verifier`) AS verifier
,start_date,end_date,capacity, case status when 1 then 'Open' when 2 then 'Closed' when 3 then 'Cancelled' end as status
,(SELECT GROUP_CONCAT(firstnames,' ',surname) FROM tr INNER JOIN group_members ON group_members.tr_id = tr.id WHERE group_members.`groups_id` = groups.id) AS members
,(select group_concat(group_members.tr_id) from group_members where group_members.groups_id = groups.id) as members_ids
,(SELECT tr_id FROM group_members WHERE groups.id = group_members.`groups_id` AND group_members.`tr_id` = $tr_id) AS tr_id
 From
groups
$where
order by title
HEREDOC;


            $view = $_SESSION[$key] = new GetLearnersGroups();
            $view->setSQL($sql);

            // Add view filters
            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(0,'No limit',null,null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            // Add view filters
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Open', null, 'WHERE groups.status=1'),
                2=>array(2, 'Close', null, 'WHERE groups.status=2'),
                3=>array(3, 'Cancelled', null, 'WHERE groups.status=3'));
            $f = new DropDownViewFilter('filter_record_status', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            // Date filters
            $dateInfo = getdate();
            $weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
            $timestamp = time()  - ((60*60*24) * $weekday);
            // Calculate the timestamp for the end of this week

            // Start Date Filter
            $format = "WHERE groups.start_date >= '%s'";
            $f = new DateViewFilter('start_date', $format, '');
            $f->setDescriptionFormat("From start date: %s");
            $view->addFilter($f);


            $format = "WHERE groups.start_date <= '%s'";
            $f = new DateViewFilter('end_date', $format, '');
            $f->setDescriptionFormat("To start date: %s");
            $view->addFilter($f);


            // Target date filter
            $format = "WHERE groups.end_date >= '%s'";
            $f = new DateViewFilter('target_start_date', $format, '');
            $f->setDescriptionFormat("From target date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE groups.end_date <= '%s'";
            $f = new DateViewFilter('target_end_date', $format, '');
            $f->setDescriptionFormat("To target date: %s");
            $view->addFilter($f);

            $parent_org = $_SESSION['user']->employer_id;
            // Group Tutor
            if($_SESSION['user']->type==8)
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',char(39),id,char(39)) FROM users where type=2 and employer_id = $parent_org";
            else
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',char(39),id,char(39)) FROM users where type=2";
            $f = new DropDownViewFilter('filter_tutor', $options, null, true);
            $f->setDescriptionFormat("Group Tutor: %s");
            $view->addFilter($f);

            $provider_id = $_SESSION['user']->employer_id;
            $options = "SELECT distinct qualification, qualification, null, CONCAT('WHERE id in (select groups_id from lessons where qualification=',char(39),qualification,char(39),char(41)) FROM lessons where qualification is not null and groups_id IN (SELECT id FROM groups WHERE courses_id = '$provider_id') order by qualification";
            $f = new DropDownViewFilter('filter_qualification', $options, null, true);
            $f->setDescriptionFormat("Qualification: %s");
            $view->addFilter($f);

            /*
            $options = array(
                0=>array(1, 'Contract Year (desc), title (asc)', null, 'ORDER BY contract_year desc, title asc'),
                1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY qualification_type DESC, level DESC'));
            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);
            */
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link,$tr_id)
    {if(SOURCE_BLYTHE_VALLEY) pre($this->getSQL());
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th><input id="global" type="checkbox" title = "top" onclick="checkAll(this);" /></a></th><th>Title</th><th>Tutor</th><th>Assessor</th><th>Verifier</th><th>Start Date</th><th>End Date</th><th>Capacity</th><th>Status</th><th>Members</th></tr></thead>';
            $counter=1;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                //echo '<tr title="' . $row['contract_year'] .  '">';
                $members = Array();
                if($row['members_ids']!='')
                {
                    $members = explode(",",$row['members_ids']);
                }
                if($row['status']!='Open')
                    $disabled = " DISABLED ";
                else
                    $disabled = "";
                $tr_id = $row['tr_id'];
                if(in_array($tr_id, $members))
                    echo '<td><input ' . $disabled . ' id="button'.$counter++.'" type="checkbox" checked title="' . $row['title'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
                else
                    echo '<td><input ' . $disabled . ' id="button'.$counter++.'" type="checkbox" title="' . $row['title'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td align="left">' . HTML::cell($row['tutor']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['assessor']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['verifier']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['start_date']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['end_date']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['capacity']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['status']) . "</td>";
                echo '<td>' . $row['members'] . '</td>';
                echo '</tr>';

                $qid = $row['id'];

            }
            echo '</tbody></table></div align="left">';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}
?>