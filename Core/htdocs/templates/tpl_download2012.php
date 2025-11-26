<?php
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $saveasname);
header('Content-Transfer-Encoding: binary');
Ilr2012::generateStream4($link, $submission, $contractsstring, $con1);
?>