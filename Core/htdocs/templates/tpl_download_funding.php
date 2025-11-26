<?php
//$saveasname = basename($file);	
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . 'profile.xml');
header('Content-Transfer-Encoding: binary');
print($data);
?>