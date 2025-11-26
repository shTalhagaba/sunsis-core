<?php
class upload_abody_registration implements IAction
{
	public function execute(PDO $link)
	{

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_URL, "https://demo.sunesis.uk.net/do.php?_action=correct_ilrs");
        curl_setopt($ch, CURLOPT_POST, true);
        // same as <input type="file" name="file_box">
        $post = array(
            "file_box"=>"@/c:/install.ini",
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);


        $_SESSION['bc']->add($link, "do.php?_action=upload_abody_registration", "Update Learner");
		
		include('tpl_upload_abody_registration.php');
	}
}
?>