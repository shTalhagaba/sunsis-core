<?php
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $saveasname);
header('Content-Transfer-Encoding: binary');
Ilr2014::generateStream4($link, $submission, $contractsstring, $con1, $beta);
?>