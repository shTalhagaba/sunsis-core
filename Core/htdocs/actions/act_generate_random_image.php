<?php
class generate_random_image implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $d1 = isset($_REQUEST['d1']) ? $_REQUEST['d1'] : '';
        $d2 = isset($_REQUEST['d2']) ? $_REQUEST['d2'] : '';
        $op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

        if($d1 == '' || $d2 == '' || $op == '')
            throw new Exception("invalid request");

        $d1_decrypt = "";
        $d2_decrypt = "";
        $op_decrypt = "";
        for($i = 1; $i <= 10; $i++)
        {
            if($d1 == md5("sunesis".$i))
            {
                $d1_decrypt = $i;
                break;
            }
        }
        for($i = 1; $i <= 10; $i++)
        {
            if($d2 == md5("sunesis".$i))
            {
                $d2_decrypt = $i;
                break;
            }
        }
        foreach(["+", "-", "*"] AS $v)
        {
            if($op == md5("sunesis".$v))
            {
                $op_decrypt = $v;
                break;
            }
        }

        if($d1_decrypt == '' || $d2_decrypt == '' || $op_decrypt == '')
            throw new Exception("invalid request");



        $this->streamImage($d1_decrypt, $d2_decrypt, $op_decrypt);

    }


    private function streamImage($num1, $num2, $op)
    {
        if($op == '+')
            $captcha_total=$num1+$num2;
        elseif($op == '-')
            $captcha_total=$num1-$num2;
        elseif($op == '*')
            $captcha_total=$num1*$num2;

        $math = "$num1$op$num2"." =";

        $_SESSION['rand_code'] = $captcha_total;

        $image = imagecreatetruecolor(120, 30); //Change the numbers to adjust the size of the image
        $black = imagecolorallocate($image, 0, 0, 0);
        $color = imagecolorallocate($image, 0, 100, 90);
        $white = imagecolorallocate($image, 255, 255, 255);

        imagefilledrectangle($image,0,0,399,99,$white);
        if(SOURCE_LOCAL)
            imagettftext ($image, 20, 0, 20, 25, $color, ("C:/Users/ianss/PhpstormProjects/sunesis_updated/htdocs/fonts/Signature_Regular.ttf"), $math );//Change the numbers to adjust the font-size
        else
            imagettftext ($image, 20, 0, 20, 25, $color, ("./fonts/Signature_Regular.ttf"), $math );//Change the numbers to adjust the font-size

        header("Content-type: image/png");
        imagepng($image);

        imagedestroy($image);
    }

    private function getTextImage($text,$font,$size)
    {
        $image = imagecreatetruecolor(100,30);

        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $grey_shade = imagecolorallocate($image, 204, 204, 204);

        Imagestring($image, 3, 0, 0, $text, $black); // to generate an image without using fonts
        $rotated_image = imagerotate($image,90,0);

        return $image;
    }

    private function getIfMatch()
    {
        $str = array_key_exists("HTTP_IF_MATCH", $_SERVER) ? trim($_SERVER['HTTP_IF_MATCH'],'"'):'';
        if(!$str){
            return array();
        }
        $str = str_replace('"', '', $str);
        $str = str_replace(' ', '', $str);
        return explode(',', $str);
    }

    private function getIfNoneMatch()
    {
        $str = array_key_exists("HTTP_IF_NONE_MATCH", $_SERVER) ? trim($_SERVER['HTTP_IF_NONE_MATCH'],'"'):'';
        if(!$str){
            return array();
        }
        $str = str_replace('"', '', $str);
        $str = str_replace(' ', '', $str);
        return explode(',', $str);
    }
}