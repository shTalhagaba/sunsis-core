<?php
header('Content-Type: text/xml');
header('Content-Disposition: attachment; filename=' . $saveasname);
header('Content-Transfer-Encoding: binary');

Ilr2020::generateStream4($link, $submission, $contractsstring, $con1, $beta);
?>