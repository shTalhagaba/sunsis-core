<?php
/**
 * User: Richard Elmes
 * Date: 10/05/12
 * Time: 14:12
 */

class view_captureinfo implements IAction
{
	public function execute(PDO $link) {

		$question = null;

		if ( isset($_REQUEST['update_question']) ) {
			$question = new CaptureInfo();
			$question->populate($_REQUEST);
			if ( isset($_REQUEST['save']) ) {

				if ( isset($_REQUEST['newsection']) ) {
					$_REQUEST['infogroupid'] = DAO::getSingleValue($link, "select max(infogroupid)+1 from users_capture_info");
				}
				$question->save($link);
			}
			else {
				$question->delete($link);
			}
		}

		// resets the breadcrumb trail.
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_captureinfo", "View User Capture Info");
		$view = ViewCaptureInfo::getInstance($link);

		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_captureinfo.php');
	}
}

?>