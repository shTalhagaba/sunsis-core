<?php
class edit_application_acl implements IAction
{
    public function execute(PDO $link)
    {
        $acl = ACL::loadFromDatabase($link, 'application', '1');

        // Authorisation
        if(!$_SESSION['user']->isAdmin())
        {
            throw new UnauthorizedException();
        }

        // Presentation
        include('tpl_edit_application_acl.php');
    }
}
?>