<?php
class edit_generic_review implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['review_id']))
                echo $this->deleteLearnerReview($link, $_REQUEST['review_id']);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_generic_review&tr_id=" . $tr_id, "Add/Edit Learner Review");

        if($review_id == '')
        {
            // New record
            $vo = new Review();
            $vo->tr_id = $tr_id;
            $page_title = "Add Review Details";
            $sql = "SELECT id, description, null FROM lookup_review_status WHERE id IN (" . Review::BookedReview. ", " . Review::RescheduledReview . ") ORDER BY description; ";
            $review_statuses = DAO::getResultSet($link, $sql);
            $vo->interviewer = $_SESSION['user']->id;
            $sql = "SELECT id, CONCAT(firstnames, ' ', surname), NULL FROM users WHERE id = " . $_SESSION['user']->id . " ORDER BY firstnames; "; // reed asked for a change this means that anyone can book an appointment for him/herself and this should be preset and not editable.
            $assessors = DAO::getResultSet($link, $sql);
        }
        else
        {
            $vo = Review::loadFromDatabase($link, $review_id);
            $page_title = "Edit Review Details";
            $today_date = new Date(date('Y-m-d'));
            $review_date = new Date($vo->review_date);
            if($review_date->after($today_date))
                $sql = "SELECT id, description, null FROM lookup_review_status WHERE id IN (" . Review::BookedReview. ", " . Review::RescheduledReview . ") ORDER BY description; ";
            else
                $sql = "SELECT id, description, null FROM lookup_review_status ORDER BY description; ";
            $review_statuses = DAO::getResultSet($link, $sql);
            $sql = "SELECT id, CONCAT(firstnames, ' ', surname), NULL FROM users WHERE id = " . $vo->interviewer . " ORDER BY firstnames; "; // reed asked for a change this means that anyone can book an appointment for him/herself and this should be preset and not editable.
            $assessors = DAO::getResultSet($link, $sql);
        }

        // Dropdown arrays
        $sql = "SELECT id, description, null FROM lookup_review_types ORDER BY description; ";
        $review_types = DAO::getResultSet($link, $sql);

        $sql = "SELECT id, description, null FROM lookup_review_paperwork ORDER BY description; ";
        $review_paperworks = DAO::getResultSet($link, $sql);

        /*$sql = "SELECT modules.id, title, legal_name FROM modules INNER JOIN organisations ON modules.`provider_id` = organisations.id ORDER BY title; ";
        $modules = DAO::getResultSet($link, $sql);*/

        // Cancel button URL
        //$js_cancel = "window.location.replace('do.php?_action=read_training_record&appointment_tab=1&id=$tr_id');";

        include('tpl_edit_generic_review.php');
    }

    private function deleteLearnerReview(PDO $link, $review_id)
    {
        $result = DAO::execute($link, "DELETE FROM reviews WHERE reviews.id = " . $review_id);
        if($result > 0)
            return 'The record has been successfully deleted.';
        else
            return 'Operation failed.';
    }

}
?>