<?php
class delete_archive implements IAction
{
    public function execute(PDO $link)
    {
        set_time_limit(0);
        ini_set('memory_limit','1024M');

        $username = isset($_GET['username']) ? $_GET['username'] : '';
        $mode = isset($_GET['mode']) ? $_GET['mode'] : '';

        if($mode==1)
        {
            // Remove first
            $path = Repository::getRoot() . "/" . $username;
            shell_exec("rm -rf " . $path);

            http_redirect("do.php?_action=view_archive");
        }

    }
}
?>