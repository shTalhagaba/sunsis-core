<?php
class Signature
{
    public static function render($title, $font, $size)
    {
        $text = $title;

        if($text == '')
            $text = "Signature";

        // Get conditional request headers
        $if_match = self::getIfMatch();
        $if_none_match = self::getIfNoneMatch();

        // Conditional request handling
        $etag = md5($text);
        if(count($if_match) > 0)
        {
            if(in_array('*', $if_match) || in_array($etag, $if_match))
            {
                header("HTTP/1.x 304 Not Modified");
                return;
            }
            else
            {
                header("HTTP/1.x 412 Precondition failed");
                return;
            }
        }
        elseif(count($if_none_match) > 0)
        {
            if(in_array('*', $if_none_match) || in_array($etag, $if_none_match))
            {
                header("HTTP/1.x 304 Not Modified");
                return;
            }
            else
            {
                self::streamImage($text,$font,$size);
            }
        }
        else
        {
            self::streamImage($text,$font,$size);
        }
    }

    private static function streamImage($text,$font,$size)
    {
        $im = self::getTextImage($text,$font,$size);

        header("Content-type: image/png");
        header("ETag: ".md5($text));
        header("Cache-Control: public, must-revalidate, max-age=0"); // public is required for IE to save it over HTTPS
        header("Expires: ");
        header("Pragma: public");

        imagepng($im);
        imagedestroy($im);
    }

    public static function getTextImage($text,$font,$size)
    {
        $im = imagecreate(285,49);

        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        $grey_shade = imagecolorallocate($im, 204, 204, 204);

        $fontPath = dirname(__FILE__) . "/../fonts/" . $font;

        if($size=='')
        {
            Imagestring($im, 3, 0, 0, $text, $black); // to generate an image without using fonts
            $rotated_image = imagerotate($im,90,0);
        }
        else
        {
            Imagettftext($im, $size, 0, 25, 35, $black, $fontPath, $text);
        }

        return $im;
    }

    private static function getIfMatch()
    {
        $str = array_key_exists("HTTP_IF_MATCH", $_SERVER) ? trim($_SERVER['HTTP_IF_MATCH'],'"'):'';
        if(!$str){
            return array();
        }
        $str = str_replace('"', '', $str);
        $str = str_replace(' ', '', $str);
        return explode(',', $str);
    }

    private static function getIfNoneMatch()
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
