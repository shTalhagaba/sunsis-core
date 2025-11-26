<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Perspective
 * Date: 11/05/12
 * Time: 12:21
 * To change this template use File | Settings | File Templates.
 */

class view_lookups implements IAction
{
	public function execute(PDO $link) {

		$lookup_row = null;

		if ( isset($_REQUEST['save_value']) ) {
			$lookup_row = new LookUp();
			$lookup_row->populate($_REQUEST);

			// this is a bit naughty, as we are assigning values to
			// the object and then saving to database.
			foreach ( $_REQUEST as $r_id => $r_val ) {
				$lookup_row->{$r_id} = $r_val;
			}

			$lookup_row->save($link);
		}

		// }else {
		//	$lookup_row->delete($link);
		// }

		// resets the breadcrumb trail.
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_lookups", "View Lookup Data Tables");
		if ( isset($_REQUEST['table_name']) ) {
			$view = ViewLookUp::getInstance($link, $_REQUEST['table_name']);
			$view->refresh($link, $_REQUEST);
		}
		else {
			$view = new ViewLookUp();
		}

		require_once('tpl_view_lookups.php');
	}

	public $feedback_message = NULL;
}

?>