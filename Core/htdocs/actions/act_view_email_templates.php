<?php
class view_email_templates implements IAction
{
    public function execute(PDO $link)
    {
        $template_id = isset($_REQUEST['template_id']) ? $_REQUEST['template_id'] : '';

        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE id = '{$template_id}'");

        

        include_once('tpl_view_email_templates.php');
    }
}