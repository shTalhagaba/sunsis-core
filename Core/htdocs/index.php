<?php

    require __DIR__.'/vendor/autoload.php';

	require_once('./config.php');

	http_redirect('do.php?_action=login');
?>