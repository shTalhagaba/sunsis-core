<?php
class read_application_acl implements IAction
{
    public function execute(PDO $link)
    {
        $acl = ACL::loadFromDatabase($link, 'application', '1');

        // Presentation
        include('tpl_read_application_acl.php');
    }


    private function renderSuperUsers($acl)
    {

    }
}
?>