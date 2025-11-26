<?php
class edit_employer implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        if(SystemConfig::getEntityValue($link, "module_onboarding") ||
            SystemConfig::getEntityValue($link, "module_crm") ||
            in_array(DB_NAME, ["am_city_skills"]))
            http_redirect('do.php?_action=edit_employer_v2&id='.$id);

        $editMode = isset($_GET['edit']) ? $_GET['edit'] : '0';
        $subaction = isset($_GET['subaction']) ? $_GET['subaction'] : '';
        if($subaction == 'save_group_employer')
        {
            $this->save_group_employer($link);
            exit;
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_employer&id=" . $id, "Add/Edit Employer");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
        }

        if($id == '')
        {
            // New record
            $vo = new Employer();
            $vo->active = 1;
            $vo->organisation_type = "2";
        }
        else
        {
            $vo = Employer::loadFromDatabase($link, $id);
        }


        // Organisations category dropdown box array
        $org_type_id = "SELECT id, org_type, null FROM lookup_org_type ORDER BY id;";
        $org_type_id = DAO::getResultset($link, $org_type_id);
        $type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
        $type_checkboxes = DAO::getResultset($link, $type_checkboxes);

        // For first registered address
        $address = new Address();

        // Cancel button URL
        if($vo->id == 0)
        {
            $js_cancel = "window.location.replace('do.php?_action=view_employers');";
        }
        else
        {
            $js_cancel = "window.location.replace('do.php?_action=read_employer&id={$vo->id}');";
        }


        // Page title
        if($vo->id == 0)
        {
            $page_title = "New Employer" ;
        }
        elseif(strlen($vo->trading_name) > 50)
        {
            $page_title = substr($vo->trading_name, 0, 50).'...';
        }
        else
        {
            $page_title = $vo->trading_name;
        }

        $L01_dropdown = "SELECT value, description,null from dropdown0708 where code='L01' order by value;";
        $L01_dropdown = DAO::getResultset($link,$L01_dropdown);

        if(DB_NAME == "am_pera")
            $sector_dropdown = "SELECT id, description,NULL FROM lookup_sector_types WHERE id = 17 OR id > 21 ORDER BY description;";
        else
            $sector_dropdown = "SELECT id, description,null from lookup_sector_types order by description;";
        $sector_dropdown = DAO::getResultset($link,$sector_dropdown);


        $region_dropdown = 'select description, description, null from lookup_vacancy_regions order by description;';
        $region_dropdown = DAO::getResultset($link, $region_dropdown);

        // $region_dropdown = array(array('North West','North West',''), array('North East','North East',''), array('Midlands','Midlands',''), array('East Midlands','East Midlands',''), array('West Midlands','West Midlands',''), array('London North','London North',''), array('London South','London South',''), array('Peterborough','Peterborough',''), array('Yorkshire','Yorkshire',''));

        if(DB_NAME != "am_siemens" && DB_NAME != "am_siemens_demo")
        {
            if($_SESSION['user']->isAdmin())
                $account_manager_dropdown = "SELECT username, Concat(firstnames, ' ', surname) ,null from users where type = 7;";
            else
                $account_manager_dropdown = "SELECT username, Concat(firstnames, ' ', surname) ,null from users where username = '{$_SESSION['user']->username}';";
        }
        else
        {
            $account_manager_dropdown = "SELECT id, description, null FROM lookup_account_managers ORDER BY description ;";
        }
        $account_manager_dropdown = DAO::getResultset($link,$account_manager_dropdown);

        $delivery_partner = DAO::getResultset($link, "select id, legal_name, null from organisations where organisation_type = 3 order by legal_name");

        $L46_dropdown = "SELECT value, description,null from dropdown0708 where code='L46' order by value;";
        $L46_dropdown = DAO::getResultset($link,$L46_dropdown);

        $brands = DAO::getResultset($link,"SELECT id, title,null from brands order by title;");
        $code = "SELECT code, description,null from lookup_employer_size order by code;";
        $code = DAO::getResultset($link,$code);

        if(DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo")
        {
            if($id != '')
                $selected_business_codes_list = DAO::getSingleColumn($link, "SELECT brands_id FROM employer_business_codes WHERE employer_business_codes.employer_id = " . $id);
            else
                $selected_business_codes_list = null;
            $business_codes_list = DAO::getResultset($link, "SELECT id, title FROM brands ORDER BY title ASC");
        }

        // Presentation
        include('tpl_edit_employer.php');
    }

    public function save_group_employer(PDO $link)
    {
        $desc = isset($_REQUEST['group_employer_desc'])?$_REQUEST['group_employer_desc']:'';

        $entry = new stdClass();
        $entry->description = $desc;
        DAO::saveObjectToTable($link, 'lookup_group_employers', $entry);

    }

}
?>