<?php
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
{
    header('Pragma: public');
    header('Cache-Control: max-age=0');
}
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $saveasname);
header('Content-Transfer-Encoding: binary');
Ilr0809::generateStream3($link, $submission, $contractsstring, $con1, $L25);
?>