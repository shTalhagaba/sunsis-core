<?php
class delete_file implements IAction
{
    public function execute(PDO $link)
    {
        $absolute_path = $this->getAbsolutePath(); // Ends in '/'
        $file_name = $this->getFile(); // Can include path info
        $file_path = $absolute_path.$file_name;
        $redirect = isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : '';
        $extra = isset($_REQUEST['extra']) ? $_REQUEST['extra'] : '';

        $tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';

        if(!is_file($file_path))
        {
            throw new Exception("File ".basename($file_path)." does not exist");
        }

        unlink($file_path);

        if(DB_NAME == "am_reed" || DB_NAME == "am_reed_demo")
        {
            if($extra != '')
            {
                $entry_id = isset($_REQUEST['entry_id']) ? $_REQUEST['entry_id'] : '';
                DAO::execute($link, "DELETE FROM {$extra} WHERE id = '{$entry_id}' AND file_name = '" . basename($file_path) . "'");
            }
            else
            {
                DAO::execute($link, "DELETE FROM tr_files WHERE tr_id = '{$tr_id}' AND file_name = '" . basename($file_path) . "'");
                DAO::execute($link, "DELETE FROM tr_files WHERE tr_id = '{$tr_id}' AND ae_of_file = '" . basename($file_path) . "'");
            }
        }

        if(IS_AJAX)
        {
            header("Content-Type: text/plain");
            return 1;
        }
        else
        {
            if($redirect)
            {
                http_redirect($redirect);
            }
            else
            {
                echo "File deleted.";
            }
        }
        //http_redirect('do.php?_action=read_training_record&repo=1&id=' . $tr_id);
    }

    /**
     * @return path ending in a path separator '/'
     * @throws Exception
     */
    private function getRoot()
    {
        $root = str_replace('\\', '/', DATA_ROOT); // Convert windows path separtors to Linux
        $root = rtrim($root,' /').'/uploads';
        if(!is_dir($root)){
            throw new Exception("Missing uploads directory");
        }
        return $root;
    }

    /**
     * @return absolute path ending in a path separator '/'
     */
    private function getAbsolutePath()
    {
        $path = isset($_GET['path'])?$_GET['path']:'/';
        if(!$path){
            $path = '/';
        }

        // Convert windows path separators to Linux
        $path = str_replace('\\', '/', $path);

        // Remove parent directory notation
        $path = str_replace('../', '', $path);

        // Remove special characters
        $path = str_replace(array(':', ';'), '', $path);

        // Remove duplicate path separators
        $path = preg_replace('#/{2,}#', '/', $path);

        // Pre-pend and append path separators
        if($path[0] != '/'){
            $path = '/'.$path;
        }
        if($path[strlen($path) - 1] != '/'){
            $path = $path.'/';
        }

        // Prepend root if required
        $root = $this->getRoot();
        if(!preg_match("#^$root#", $path)){
            $path  = $root.$path;
        }

        // Insert the database name if absent
        if(!preg_match("#^$root/".DB_NAME."#", $path)){
            $path = preg_replace("#^$root#", $root.'/'.DB_NAME, $path);
        }

        return $path;
    }

    /**
     *
     * @return relative path (does not start with '/')
     */
    private function getFile()
    {
        $f = isset($_GET['f'])?trim($_GET['f']):'';
        if(!$f){
            throw new Exception("No file specified");
        }

        // Convert windows path separators to Linux
        $f = str_replace('\\', '/', $f);

        // Remove parent directory notation
        $f = str_replace('../', '', $f);

        // Remove special characters
        $f = str_replace(array(':', ';'), '', $f);

        // Remove duplicate path separators
        $f = preg_replace('#/{2,}#', '/', $f);

        // Strip path separators (left and right)
        $f = trim($f, '/ ');

        return $f;
    }
}
?>