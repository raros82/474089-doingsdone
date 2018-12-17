<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('functions.php');

session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = [];
}


$show_complete_values = [0, 1];
if (isset($_GET['show_completed']) && in_array(isset($_GET['show_completed']), $show_complete_values)) {
    $show_complete_tasks = $_GET['show_completed'];
    $_SESSION['show_completed'] = $show_complete_tasks;
}


if (isset($_SESSION['show_completed'])) {
    $show_complete_tasks = $_SESSION['show_completed'];
} else {
    $show_complete_tasks = 0;
}


$mysqli = mysqli_connect('doingsdone', 'root', '', '474089-doingsdone');
mysqli_set_charset($mysqli, "utf8");


if (!$mysqli) {
    $error = mysqli_connect_error();
    exit('Сайт временно не доступен.');
}

