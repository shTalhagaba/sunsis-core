<?php
class edit_system_owner implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_system_owner&id=" . $id, "Edit System Owner");

		if ($id !== '' && !is_numeric($id)) {
			throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
		}

		if ($id == '') {
			// New record
			$vo = new SystemOwner();
		} else {
			$vo = SystemOwner::loadFromDatabase($link, $id);
		}


		// Organisations category dropdown box array
		$org_type_id = "SELECT id, org_type, null FROM lookup_org_type ORDER BY id;";
		$org_type_id = DAO::getResultset($link, $org_type_id);
		$type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
		$type_checkboxes = DAO::getResultset($link, $type_checkboxes);

		// For first registered address
		$address = new Address();


		$page_title = $vo->trading_name;

		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		];

		// Always enforce SSL
		$sslCa = getenv('PERSPECTIVE_DB_SSL_CA');
		if ($sslCa && file_exists($sslCa)) {
			$options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
			$options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
		} else {
			throw new Exception("SSL CA certificate not found. Cannot connect securely.");
		}

		$dsn = "mysql:host=" . DB_HOST .
			";dbname=lis201314" .
			";port=" . DB_PORT .
			";charset=utf8mb4";

		$linklis = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
		$linklad = new PDO("mysql:host=" . DB_HOST . ";dbname=lad201314;port=" . DB_PORT, DB_USER, DB_PASSWORD, $options);

		$L01_dropdown = "SELECT DISTINCT CAPN, LEFT(concat(CAPN, ' ', Name),35), null from providers order by Name;";
		$L01_dropdown = DAO::getResultset($linklis, $L01_dropdown);

		$L46_dropdown = "SELECT DISTINCT UKPRN, LEFT(CONCAT(Name,' ',UKPRN),50),CONCAT('-----------', LEFT(NAME, 1), '-----------') from lis201415.providers order by Name;";
		$L46_dropdown = DAO::getResultset($link, $L46_dropdown);

		$linklis = '';

		// Presentation
		include('tpl_edit_system_owner.php');
	}
}
