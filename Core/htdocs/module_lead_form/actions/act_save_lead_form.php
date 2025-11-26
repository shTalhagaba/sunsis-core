<?php
class save_lead_form implements IAction
{
    public function execute(PDO $link)
    {

        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if($review_id == '' || $tr_id == '')
            throw new Exception('Missing querystring argument(s)');

        $review_form = new LeapReviewForm();
        $review_form->id = $_POST['review_id'];
        foreach($_POST AS $key => $value)
        {
            $review_form->$key = $value;
        }

        if($review_form->coach_sign != '')
        {
            $review_form->coach_sign_date = date('Y-m-d');
            $review_form->coach_sign_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;
        }
        $review_form->save($link);

	if($review_form->coach_sign != '')
        {
            if($_SESSION['user']->id == $review_form->coach_id && $_SESSION['user']->signature == '')
            {
                DAO::execute($link, "UPDATE users SET users.signature = '{$review_form->coach_sign}' WHERE users.id = '{$_SESSION['user']->id}'");
            }
        }


        http_redirect($_SESSION['bc']->getPrevious());
    }
}