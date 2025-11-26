<?php
class manage_email_template implements IAction
{
    public function execute(PDO $link)
    {
        $file = new RepositoryFile(Repository::getRoot() . '/test_template/index.html');

        ob_start();

        include $file->getAbsolutePath();


        $content = ob_get_contents();
        ob_end_clean();

        $content = str_replace('FULLNAME', 'Maani', $content);

    }
}