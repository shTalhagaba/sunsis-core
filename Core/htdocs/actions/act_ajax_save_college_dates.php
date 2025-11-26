<?php
class ajax_save_college_dates implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $college_start_date = isset($_REQUEST['college_start_date'])?$_REQUEST['college_start_date']:'';
        $college_end_date = isset($_REQUEST['college_end_date'])?$_REQUEST['college_end_date']:'';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $tr->college_start_date = Date::toMySQL($college_start_date);
        $tr->college_end_date = Date::toMySQL($college_end_date);

        $tr->save($link);
    }
}
?>
