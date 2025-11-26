<?php
class ajax_calendar_manager implements IAction
{
    public function execute( PDO $link )
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($subaction == '')
        {
            $start = $_REQUEST['start'];
            $end   = $_REQUEST['end'];

            echo $this->getSessions($link, $start, $end);

            exit;
        }
        if($subaction == 'get_session_detail')
        {
            echo $this->getSessionDetail($link);
            exit;
        }

    }

    private function getOrganisationCrmNextActions(PDO $link, $start, $end, &$out)
    {
        $sql = <<<SQL
SELECT
	crm_notes_orgs.*,
    organisations.legal_name,   
    lookup_crm_subject.`description` AS subject_desc,
	lookup_crm_contact_type.`description` AS contact_type_desc,
	lookup_crm_regarding.`description` AS next_action_desc
FROM
	crm_notes_orgs 
	INNER JOIN organisations ON crm_notes_orgs.organisation_id = organisations.id
	LEFT JOIN lookup_crm_subject ON crm_notes_orgs.`subject` = lookup_crm_subject.id
	LEFT JOIN lookup_crm_contact_type ON crm_notes_orgs.`type_of_contact` = lookup_crm_contact_type.id
	LEFT JOIN lookup_crm_regarding ON crm_notes_orgs.`next_action_id` = lookup_crm_regarding.id
WHERE
	next_action_date BETWEEN '$start' AND '$end' AND crm_notes_orgs.`actioned`  IN ('N', 'NA')
;
SQL;

        $crm_actions = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($crm_actions AS $row)
        {
            $line = "{$row['by_whom']}({$row['by_whom_position']}) created this note on " . Date::toShort($row['created_at']) .
                " at " . Date::to($row['created_at'], 'H:i:s') . " with the following details:<br>";
            $line .= "Contact Date: " . Date::toShort($row['contact_date']) . " Contact Time: " . $row['contact_time'] . "<br>";
            $line .= "Company: " . $row['legal_name'] . "<br>";
            $line .= "Contacted Person: " . DAO::getSingleValue($link, "SELECT contact_name FROM organisation_contact WHERE contact_id = '{$row['org_contact_id']}'") . "<br>";
            $line .= "Comments/Action:<br>" . substr($row['agreed_action'], 0, 500) . "<br>";

	    $color = '#3c8dbc';
            if(DB_NAME == "am_duplex" && ($row['by_whom'] == "Ellen Palmer" || $row['created_by'] == 295))
            {
                $color = '#90EE90';              
            }

            $out[] = [
                'id' => $row['id'],
                'title' => $row['legal_name'],//'CRM Action',
                'line' => $line,
                'next_action_desc' => $row['next_action_desc'],
                'url' => '/do.php?_action=ajax_calendar_manager&subaction=get_session_detail&id=' . $row['id'] . '&type=crm_action',
                'start' => $row['next_action_date'].'T'.$row['next_action_time'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'type' => 'crm_note',
                'allDay' => false,
                'nav_to_crm_detail' => 'do.php?_action=edit_org_crm_note&amp;id='.$row['id'].'&amp;organisations_id='.$row['organisation_id'],
            ];
        }
    }

    private function getSessions(PDO $link, $start, $end)
    {
        $out = array();

        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : $_SESSION['user']->id;

        $this->getOrganisationCrmNextActions($link, $start, $end, $out);


        return json_encode($out);
    }

    private function getSessionDetail(PDO $link)
    {
        $session_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
        if($session_id == '')
            return 'Missing querystring: session id';

        if($type == 'crm_action')
        {
/*            $result = DAO::getObject($link, "SELECT * FROM crm_notes_learner WHERE id = '{$session_id}'");
            $html = '<table class="table row-border">';
            $html .= '<tr><th>Event Title:</th><td>CRM Action</td></tr>';
            $html .= '<tr><th>Date:</th><td>' . Date::toShort($result->next_action_date) . '</td></tr>';
            $html .= '<tr><th>For:</th><td>' . $result->name_of_person . ' [' . $result->position . ']</td></tr>';
            $html .= '<tr><th>Contact Type:</th><td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_crm_contact_type WHERE id = '{$result->type_of_contact}'") . '</td></tr>';
            $html .= '<tr><th>Subject:</th><td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_crm_subject WHERE id = '{$result->subject}'") . '</td></tr>';
            $html .= '<tr><th>Agreed Action:</th><td>' . $result->agreed_action . '</td></tr>';
            $html .= '</table>';*/

            $html = 'INaam';
        }
        $html = 'INaam';
        return $html;
    }
}