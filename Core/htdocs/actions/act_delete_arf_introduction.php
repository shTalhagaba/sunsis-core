<?php
class delete_arf_introduction implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $review_id = isset($_REQUEST['review_id'])?$_REQUEST['review_id']:'';
        $id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

        $previous_review_id = DAO::getSingleValue($link, "SELECT id FROM assessor_review WHERE tr_id = '$id' AND id < '$review_id' ORDER BY id DESC LIMIT 0,1;");
        DAO::execute($link, "update arf_introduction set next_contact = review_date where review_id = '$previous_review_id'");
        DAO::execute($link, "delete from assessor_review where id = '$review_id'");
    }
}
?>



