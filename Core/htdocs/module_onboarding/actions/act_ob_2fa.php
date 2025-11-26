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

        $invalid = isset($_REQUEST['invalid']) ? $_REQUEST['invalid'] : false;

        if($id != '' && $key != '')
        {
            if(!in_array($forwarding, ["ia", "ksa", "ob"]))
            {
                $_POST = null;
                unset($_POST);
                http_redirect('do.php?_action=form_error');
            }
            if(!OnboardingHelper::isValidKey($link, $id, $key))
            {
                $_POST = null;
                unset($_POST);
                http_redirect('do.php?_action=form_error');
            }

            $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$id}'");
            if(!isset($ob_learner->id))
            {
                $_POST = null;
                unset($_POST);
                http_redirect('do.php?_action=form_error');
            }

            if(isset($_POST['dob']))
            {
                if ($_POST['captcha_entered'] != $_SESSION['rand_code'])
                {
                    $invalid = true;
                    $referrer = $_SERVER['HTTP_REFERER'];
                    if(!SOURCE_LOCAL)
                    {
                        $pos = strpos($referrer, '/do.php');
                        $referrer = substr($referrer, $pos);
                    }
                    $referrer = str_replace('&invalid=1', '', $referrer);
                    http_redirect("{$referrer}&invalid={$invalid}");
                }

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
                    $referrer = $_SERVER['HTTP_REFERER'];
                    if(!SOURCE_LOCAL)
                    {
                        $pos = strpos($referrer, '/do.php');
                        $referrer = substr($referrer, $pos);
                    }
                    $referrer = str_replace('&invalid=1', '', $referrer);
                    http_redirect("{$referrer}&invalid={$invalid}");
                }
            }
            else
            {

                $digit1 = mt_rand(1,10);
                $digit2 = mt_rand(1,10);

                if( mt_rand(0,2) === 1 )
                {
                    $op = '+';
                    $math = "$digit1 + $digit2";
                    $_SESSION['answer'] = $digit1 + $digit2;
                }
                elseif(mt_rand(0,2) === 2)
                {
                    $op = '-';
                    $math = "$digit1 * $digit2";
                    $_SESSION['answer'] = $digit1 * $digit2;
                }
                else
                {
                    $op = '*';
                    $math = "$digit1 - $digit2";
                    $_SESSION['answer'] = $digit1 - $digit2;
                }

                $digit1_cypher = md5("sunesis".$digit1);
                $digit2_cypher = md5("sunesis".$digit2);
                $op_cypher = md5("sunesis".$op);

                $q = "do.php?_action=generate_random_image&d1={$digit1_cypher}&d2={$digit2_cypher}&op={$op_cypher}";

                if($op == '+')
                    $captcha_total=$digit1+$digit2;
                elseif($op == '-')
                    $captcha_total=$digit1-$digit2;
                elseif($op == '*')
                    $captcha_total=$digit1*$digit2;

                $_SESSION['rand_code'] = $captcha_total;

            }
        }
        else
        {
            $_POST = null;
            unset($_POST);
            http_redirect('do.php?_action=form_error');
        }

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
        $header_image2 = SystemConfig::getEntityValue($link, "ob_header_image2");

        include_once('tpl_ob_2fa.php');
    }

    private function streamImage($text,$font,$size)
    {
        $num1=rand(1,9); //Generate First number between 1 and 9
        $num2=rand(1,9); //Generate Second number between 1 and 9
        $captcha_total=$num1+$num2;

        $math = "$num1"." + "."$num2"." =";

        $_SESSION['rand_code'] = $captcha_total;

        $font = 'mmobuyit-captcha-fonts/Arial.ttf';

        $image = imagecreatetruecolor(120, 30); //Change the numbers to adjust the size of the image
        $black = imagecolorallocate($image, 0, 0, 0);
        $color = imagecolorallocate($image, 0, 100, 90);
        $white = imagecolorallocate($image, 0, 26, 26);

        imagefilledrectangle($image,0,0,399,99,$white);
        imagettftext ($image, 20, 0, 20, 25, $color, ("C:/Users/ianss/PhpstormProjects/sunesis_updated/htdocs/fonts/".$font), $math );//Change the numbers to adjust the font-size

        header("Content-type: image/png");
        imagepng($image);
    }

    private function getTextImage($text,$font,$size)
    {
        $im = imagecreate(40,40);

        $black = imagecolorallocate($im, 0, 0, 0);

        if($size=='')
        {
            Imagestring($im, 3, 0, 0, $text, $black); // to generate an image without using fonts
        }
        else
        {
            Imagettftext($im, $size, 0, 25, 35, $black, ("C:/Users/ianss/PhpstormProjects/sunesis_updated/htdocs/fonts/".$font), $text);
        }

        return $im;
    }

}