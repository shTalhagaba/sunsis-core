<?php
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $saveasname);
header('Content-Transfer-Encoding: binary');
Ilr2008::generateStream3($link, $submission, $contractsstring, $con1, $L25, $transmission);
?>