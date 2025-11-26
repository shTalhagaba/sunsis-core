<?php
$icons = [
    'danger' => 'fa fa-exclamation-triangle red',
    'warning' => 'fa fa-warning orange',
    'success' => 'fa fa-check green',
    'info' => 'fa fa-info-circle blue',
];
foreach (['danger', 'warning', 'success', 'info'] as $msg)
{
    if(isset($_SESSION['alert-' . $msg]) && $_SESSION['alert-' . $msg] != '')
    {
        unset($_SESSION['alert-' . $msg]);
    }
}

?>

