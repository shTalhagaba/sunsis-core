<?php
class sa_settings extends ActionController
{
	public function indexAction(PDO $link)
	{
		$authorised = $_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL);
		if (!$authorised) {
			throw new UnauthorizedException();
		}

		$wsdl = SystemConfig::get("smartassessor.soap.wsdl");
		$apiKey = SystemConfig::get("smartassessor.soap.api_key");
		$namespace = SystemConfig::get("smartassessor.soap.namespace");
		$enabled = SystemConfig::get("smartassessor.soap.enabled");
		$display_menu = SystemConfig::get("smartassessor.display_menu");

		// Normalise
		$enabled = $enabled ? '1':'0';
		$display_menu = $display_menu ? '1':'0';

		include("smartassessor/settings/tpl_read.php");
	}

	public function editAction(PDO $link)
	{
		$authorised = $_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL);
		if (!$authorised) {
			throw new UnauthorizedException();
		}

		$wsdl = SystemConfig::get("smartassessor.soap.wsdl");
		$apiKey = SystemConfig::get("smartassessor.soap.api_key");
		$namespace = SystemConfig::get("smartassessor.soap.namespace");
		$enabled = SystemConfig::get("smartassessor.soap.enabled");
		$display_menu = SystemConfig::get("smartassessor.display_menu");

		// Normalise
		$enabled = $enabled ? '1':'0';
		$display_menu = $display_menu ? '1':'0';

		include("smartassessor/settings/tpl_edit.php");
	}


	public function saveAction(PDO $link)
	{
		$authorised = $_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL);
		if (!$authorised) {
			throw new UnauthorizedException();
		}

		$wsdl = $this->_getParam("wsdl");
		$apiKey = $this->_getParam("api_key");
		$namespace = $this->_getParam("namespace");
		$enabled = $this->_getParam("enabled");
		$display_menu = $this->_getParam("display_menu");

		// Normalise
		$enabled = $enabled ? '1':'0';
		$display_menu = $display_menu ? '1':'0';

		SystemConfig::set("smartassessor.soap.wsdl", $wsdl);
		SystemConfig::set("smartassessor.soap.api_key", $apiKey);
		SystemConfig::set("smartassessor.soap.namespace", $namespace);
		SystemConfig::set("smartassessor.soap.enabled", $enabled);
		SystemConfig::set("smartassessor.display_menu", $display_menu);
	}

	public function resetAction(PDO $link)
	{
		$authorised = $_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL);
		if (!$authorised) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
		}

		$sa = new SmartAssessor(false);
		$sa->reset();
	}
}