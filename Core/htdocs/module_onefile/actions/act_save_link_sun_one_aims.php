<?php

class save_link_sun_one_aims implements IAction
{
    public function execute(PDO $link)
    {
        $framework_id = isset($_POST['framework_id']) ? $_POST['framework_id']: '';
        if($framework_id != '')
        {
            DAO::execute($link, "UPDATE framework_qualifications SET onefile_standard_id = NULL WHERE framework_id = '{$framework_id}'");
            foreach($_POST AS $key => $value)
            {
                if( substr($key, 0, 24) == "onefile_standard_id_for_"  )
                {
                    $auto_id = str_replace("onefile_standard_id_for_", "", $key);
                    $onefile_standard_id = $value;

                    if($auto_id != '' && $onefile_standard_id != '')
                    {
                        DAO::execute($link, "UPDATE framework_qualifications SET onefile_standard_id = '{$onefile_standard_id}' WHERE auto_id = '{$auto_id}' AND framework_id = '{$framework_id}'");
                    }
                }
            }
        }

        if(IS_AJAX)
        {
            echo 1;
        }
        else
        {
            http_redirect('do.php?_action=read_framework&id='.$framework_id);
        }
    }
}