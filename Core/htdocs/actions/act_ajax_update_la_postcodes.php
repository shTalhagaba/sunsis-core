<?php
/**
 * Created By: Perspective Ltd.
 * User: Richard Elmes
 * Date: 28/06/12
 * Time: 14:54
 */

class ajax_update_la_postcodes implements IAction
{
	public function execute(PDO $link)
	{
		if ( SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL ) {
			$postcode = isset($_REQUEST['postcode'])?$_REQUEST['postcode']:'';
			$la = isset($_REQUEST['la'])?$_REQUEST['la']:'';

			if ( $postcode == '' || $la == '' ) {
				echo 'failed';
			}

			// deleting all the qualifications from this framework
			$query = <<<HEREDOC
update central.lookup_postcode_la set local_authority = "{$la}" where trim(postcode) = "{$postcode}";
HEREDOC;
			DAO::execute($link, $query);
			echo 'OK';
		}
	}
}
?>