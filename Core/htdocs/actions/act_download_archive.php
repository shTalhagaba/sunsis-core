<?php
class download_archive implements IAction
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
            shell_exec("rm -rf " . Repository::getRoot() . "/archive_folder");

            $LearnRefNumber = DAO::getSingleValue($link, "select l03 from tr where username = '$username' limit 1");

            $path = Repository::getRoot() . "/" . $username;
            $filename = Repository::getRoot() . "/archive_folder/" . $LearnRefNumber . "-" . $username . ".zip";
            $file = "archive_folder/".$LearnRefNumber . "-" . $username . ".zip";

            if (!file_exists(Repository::getRoot() . "/archive_folder")) {
                mkdir(Repository::getRoot() . "/archive_folder", 0777, true);
            }

            ExtendedZip::zipTree($path, $filename, ZipArchive::CREATE);

            http_redirect("do.php?_action=downloader&f=$file");

        }

    }
}
?>