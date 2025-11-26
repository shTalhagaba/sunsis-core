<?php
header('Content-Type: text/xml');
header('Content-Disposition: attachment; filename=' . $saveasname);
header('Content-Transfer-Encoding: binary');

if(DB_NAME == "am_baltic")
	Ilr2017Temp::generateStream4($link, $submission, $contractsstring, $con1, $beta);
else
	Ilr2017::generateStream4($link, $submission, $contractsstring, $con1, $beta);
?>