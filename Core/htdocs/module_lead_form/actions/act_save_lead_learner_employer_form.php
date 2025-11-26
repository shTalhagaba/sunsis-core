<?php
class save_lead_learner_employer_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $formName = isset($_REQUEST['formName']) ? $_REQUEST['formName'] : '';
        if($formName == '')
            throw new Exception("missing querystring argument: formName");

        if($formName == "frmReviewLearner")
        {
            $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
            if($key == '')
                throw new Exception("missing querystring argument: key");

            $review = DAO::getObject($link, "SELECT * FROM review_forms WHERE MD5(CONCAT('sunesis_lead_learner_review_form', id, tr_id)) = '{$key}'");/* @var $review LeapReviewForm */
            if(!isset($review->id))
                pre("invalid url");

            if($review->learner_sign != '')
            {
                unset($_POST);
                self::generateAlreadyCompletedPage($link);
                session_destroy();
            }

            $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
            $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

            if($review_id == '' || $tr_id == '')
                throw new Exception('Missing querystring argument(s)');

            $review_form = LeapReviewForm::loadFromDatabase($link, $review_id);
            if(is_null($review_form))
                throw new Exception('invalid details');

            if($review_form->tr_id != $tr_id)
                throw new Exception("invalid details");

            $review_form->learner_reflection_on_learning_to_date = isset($_POST['learner_reflection_on_learning_to_date']) ? substr($_POST['learner_reflection_on_learning_to_date'], 0, 1199) : '';

            if(isset($_POST['learner_sign']) && $_POST['learner_sign'] != '')
            {
                $review_form->learner_sign_date = date('Y-m-d');
                $review_form->learner_sign = $_POST['learner_sign'];
            }
            $review_form->save($link);
        }
        elseif ($formName == 'frmReviewEmployer')
        {
            $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
            if($key == '')
                throw new Exception("missing querystring argument: key");

            $review = DAO::getObject($link, "SELECT * FROM review_forms WHERE MD5(CONCAT('sunesis_lead_employer_review_form', id, tr_id)) = '{$key}'");/* @var $review LeapReviewForm */
            if(!isset($review->id))
                pre("invalid url");

            if($review->emp_sign != '')
                pre("already completed");

            $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
            $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

            if($review_id == '' || $tr_id == '')
                throw new Exception('Missing querystring argument(s)');

            $review_form = LeapReviewForm::loadFromDatabase($link, $review_id);
            if(is_null($review_form))
                throw new Exception('invalid details');

            if($review_form->tr_id != $tr_id)
                throw new Exception("invalid details");

            $review_form->employer_comments = isset($_POST['employer_comments']) ? substr($_POST['employer_comments'], 0, 1199) : '';

            if(isset($_POST['emp_sign']) && $_POST['emp_sign'] != '')
            {
                $review_form->emp_sign_date = date('Y-m-d');
                $review_form->emp_sign = $_POST['emp_sign'];
                $review_form->emp_sign_name = $_POST['emp_sign_name'];
            }
            $review_form->save($link);

        }
        else
        {
            pre("invalid details");
        }

        unset($_POST);
        LeapReviewForm::generateCompletionPage($link);
        session_destroy();

        //pre("Form completed");
    }


}