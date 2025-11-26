<?php
class save_crm_holidays implements IAction
{
    public function execute(PDO $link)
    {
        $holiday = new CRMHoliday();
        $holiday->populate($_POST);

        $holiday->save($link);

        http_redirect($_SESSION['bc']->getPrevious());
    }
}
?>