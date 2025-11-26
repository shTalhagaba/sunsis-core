<?php
class export_current_view_to_excel implements IAction
{
    public function execute(PDO $link)
    {
        set_time_limit(0);
        ini_set('memory_limit','2048M');
        $key = array_key_exists('key', $_REQUEST) ? $_REQUEST['key'] : 'view';
        $columns = array_key_exists('columns', $_REQUEST) ? $_REQUEST['columns'] : 'view';

        $view = View::getViewFromSession($key); /* @var $view View */
        if(!is_null($view))
        {
            $saved_columns = $view->getSelectedColumns($link);
            if(count($saved_columns) > 0)
                $columns = implode(",", $saved_columns);
            $view->exportToCSV($link, $columns);
        }
        else
        {
            header("Content-Type: text/html");
            echo '<html><body><script language="JavaScript">alert("Cannot find the view to export"); history.go(-1);</script></body></html>';
        }
    }

}
?>