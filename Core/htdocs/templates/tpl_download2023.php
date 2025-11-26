<?php
header('Content-Type: text/xml');
header('Content-Disposition: attachment; filename=' . $saveasname);
header('Content-Transfer-Encoding: binary');

Ilr2023::generateStream4($link, $submission, $contractsstring, $con1, $beta);
?>