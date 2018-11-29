<?php
require_once('functions.php');
require_once('data.php');


$mysqli = new mysqli('doingsdone','root','','474089-doingsdone');

//запрос на выборку проектов (категорий)
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
else {
    $sql = 'SELECT `category_id`, `category_name` FROM `category`';
    $result = mysqli_query($mysqli, $sql);
};

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($result);
    die('Error : ('. $error .')');
};

//запрос на выборку задач
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
else {
    $sql = 'SELECT * FROM `task`';
    $result = mysqli_query($mysqli, $sql);
};

if ($result) {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($result);
    die('Error : ('. $error .')');
};


$page_content = include_template('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'content' => $page_content,
    'task_category' => $categories,
    'tasks' => $tasks,
    'user' => $user
]);

print($layout_content);

//print_r($categories);

//foreach ($categories as $task_category_value) {
//    print($task_category_value['category_name']);
//};