<?php
class onefile_settings extends ActionController
{
	public function indexAction(PDO $link)
	{
        $authorised = $_SESSION['user']->isAdmin();
		if (!$authorised) 
        {
			throw new UnauthorizedException();
		}

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=onefile_settings", "Onefile Settings");

        $enabled = SystemConfig::get("onefile.integration");
        $customerToken = SystemConfig::get("onefile.X-CustomerToken");
        $customer = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'onefile.customer'");
        $customerID = '';
        $customerName = '';
        if($customer != '')
        {
            $customer = json_decode($customer);
            $customerID = $customer->ID;
            $customerName = $customer->Name;
        }

        include_once('tpl_onefile_settings.php');
    }    
}