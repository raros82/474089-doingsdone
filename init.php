<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('functions.php');

$show_complete_tasks = rand(0, 1);


$mysqli = mysqli_connect('doingsdone','root','','474089-doingsdone');
mysqli_set_charset($mysqli, "utf8");


if (!$mysqli) {
    $error = mysqli_connect_error();
    exit('Сайт временно не доступен.');
}
