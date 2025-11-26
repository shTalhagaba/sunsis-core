<?php
class view_support_ticket implements IAction
{
	public function execute(PDO $link)
	{
		if( SystemConfig::getEntityValue($link, "module_support_v2") != 1 )
        {
            throw new Exception("Version 2 of support system is not enabled for you.");
        }
		
		require_once('./lib/SupportModule/SupportModuleHelper.php');

        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if($id == '')
        {
            throw new Exception("Missing querysting argument: id");
        }

        $supportHelper = new SupportModuleHelper();

        $ticketApiResponse = $supportHelper->api_ticket($id);

	if( $ticketApiResponse->getHttpCode() != 200 )
        {
            $errorResp = '<p>Response Code: ' . $ticketApiResponse->getHttpCode() . '</p>';
            $errorResp = '<p>Response Body: ' . $ticketApiResponse->getBody() . '</p>';
            pre($errorResp);
        }

        $ticket = json_decode($ticketApiResponse->getBody());

        $ticket = $ticket->data;

	$_SESSION['bc']->add($link, "do.php?_action=view_support_ticket&id=", "View Support Ticket");

        require_once('tpl_view_support_ticket.php');
    }
}