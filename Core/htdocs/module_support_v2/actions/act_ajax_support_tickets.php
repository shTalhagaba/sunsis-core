<?php
class ajax_support_tickets extends ActionController
{
	private $supportHelper;

    private function setUp()
    {
		require_once('./lib/SupportModule/SupportModuleHelper.php');
        $this->supportHelper = new SupportModuleHelper();
    }
	
	public function indexAction(PDO $link)
	{
		$this->setUp();
	}

	public function getUserTicketsAction()
	{
		if( !isset($_SESSION['user']->support_contact_id) )
		{
			echo 'No tickets raised.';
			return;
		}

		$this->setUp();

		$ticketApiResponse = $this->supportHelper->api_tickets_for_account_contact(['account_contact_id' => $_SESSION['user']->support_contact_id]);

		$ticketApiResponse = json_decode($ticketApiResponse->getBody());

		$stats = '<table class="table table-bordered">';
		$stats .= '<caption class="text-info text-bold">Your Support Tickets</caption>';
		$stats .= '<tr class="bg-info">';
		$stats .= '<th>Status</th>';
		$stats .= '<th>Count</th>';
		$stats .= '</tr>';
		$i = 0;
		foreach($ticketApiResponse AS $statusDesc => $count)
		{
			$count = (int) $count;
			$url = 'do.php?_action=view_support_tickets&account_contact_id=' . $_SESSION['user']->support_contact_id . '&ticket_status=' . ++$i;
			$stats .= '<tr>';
			$stats .= '<th>' . $statusDesc . '</th>';
			$stats .= $count > 0 ? 
				'<td class="text-center"><a href="' . $url . '">' . $count . '</a></td>' :
				'<td class="text-center">' . $count . '</td>';
			$stats .= '</tr>';
		}
		$stats .= '</table>';

		echo $stats;
	}

	public function saveAccountContactIdAction(PDO $link)
	{
		$accountContactId = isset($_REQUEST['account_contact_id']) ? $_REQUEST['account_contact_id'] : '';
		if($accountContactId == '')
		{
			return;
		}

		if( $_SESSION['user']->support_contact_id != $accountContactId )
		{
			$_SESSION['user']->support_contact_id = $accountContactId;
			$_SESSION['user']->save($link);
		}
		echo 'success for ' . $accountContactId;
	}

	
}