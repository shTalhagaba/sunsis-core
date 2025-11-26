<?php
class read_user implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        if(!$id)
        {
            throw new Exception("Missing or empty querystring argument 'username'");
        }

        $vo = User::loadFromDatabaseById($link, $id);
        if (is_null($vo))
        {
            throw new Exception("No user with id '$id'");
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH).'?'.parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY)) : "";
        if (strpos($referer, 'add_system_user') !== false) {
            $_SESSION['bc']->pop();
        }
        $_SESSION['bc']->add($link, "do.php?_action=read_user&id={$id}", "View User");



        $isSafeToDelete = $vo->isSafeToDelete($link);

        $organisation = Organisation::loadFromDatabase($link, $vo->employer_id);
        $location = Location::loadFromDatabase($link, $vo->employer_location_id);

        $showPanel = array_key_exists('_showPanel', $_REQUEST) ? $_REQUEST['_showPanel'] : '0'; // Default to 1 lesson

        $gender_description = "SELECT description FROM lookup_gender WHERE id='{$vo->gender}';";
        $gender_description = DAO::getSingleValue($link, $gender_description);

        $page_title = "{$vo->firstnames} {$vo->surname}/{$organisation->trading_name}";
        if(strlen($page_title) > 50)
        {
            $page_title = substr($page_title, 0, 50).'...';
        }

        // Learner photo
        $photopath = $vo->getPhotoPath();
        if($photopath){
            $photopath = "do.php?_action=display_image&username=".rawurlencode($vo->username);
        } else {
            $photopath = "/images/no_photo.png";
        }

        // Presentation
        include('tpl_read_user.php');
    }

}
?>