<?php
class ob_2fa implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        if (!isset($_SESSION['token']))
        {
            $token = md5(uniqid(rand(), TRUE));
            $_SESSION['token'] = $token;
            $_SESSION['token_time'] = time();
        }
        else
        {
            $token = $_SESSION['token'];
        }

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:(isset($_POST['id'])?$_POST['id']:'');
        $forwarding = isset($_REQUEST['forwarding'])?$_REQUEST['forwarding']:(isset($_POST['forwarding'])?$_POST['forwarding']:'');
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:(isset($_POST['key'])?$_POST['key']:'');

        $invalid = false;

        if($id != '' && $key != '')
        {
            if(!in_array($forwarding, ["sa", "ob"]))
            {
                OnboardingHelper::generateErrorPage($link);
                exit;
            }
            if(!OnboardingHelper::isValidKey2Fa($link, $forwarding, $id, $key))
            {
                OnboardingHelper::generateErrorPage($link);
                exit;
            }

            $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$id}'");
            if(!isset($ob_learner->id))
            {
                OnboardingHelper::generateErrorPage($link);
                exit;
            }

            if(isset($_POST['dob']))
            {
                $dob = Date::toMySQL($_POST['dob']);
                $dob = $link->quote($dob);
                $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learners WHERE ob_learners.id = '{$id}' AND ob_learners.dob = $dob");

                if($found > 0)
                {
                    switch($forwarding)
                    {
                        case 'ia':
                            http_redirect("do.php?_action=ob_screening&id={$id}&forwarding=ia&key={$key}");
                            break;
                        case 'ksa':
                            http_redirect("do.php?_action=ks_assessment&id={$id}&forwarding=ksa&key={$key}");
                            break;
                        case 'ob':
                            http_redirect("do.php?_action=ob_form&id={$id}&forwarding=ob&key={$key}");
                            break;
                    }
                }
                else
                {
                    $invalid = true;
                }
            }
        }
        else
        {
            //pr($id . $key);
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
        $header_image2 = SystemConfig::getEntityValue($link, "ob_header_image2");

        include_once('tpl_ob_2fa.php');
    }

}