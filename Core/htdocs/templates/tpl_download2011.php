<?php
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $saveasname);
header('Content-Transfer-Encoding: binary');
if(DB_NAME!='am_platinum' && DB_NAME!='am_dv8training') // Single ILR
{
	Ilr2011::generateStream4($link, $submission, $contractsstring, $con1, $L25, $last_transmission);
}
else
{
	Ilr2011::generateStream3($link, $submission, $contractsstring, $con1, $L25, $last_transmission);
}
?>