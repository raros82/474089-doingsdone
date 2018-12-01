<?php
require_once('functions.php');
require_once('data.php');
require_once('mysql_connect.php');

//запрос на выборку проектов (категорий)
$sql = 'SELECT * FROM `category` WHERE `user_id`=1'; //будет подставляться значение залогиненного юзера
$result = mysqli_query($mysqli, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($result);
    die('Error : ('. $error .')');
};

//запрос на выборку задач
$sql = 'SELECT * FROM `task`';
$result = mysqli_query($mysqli, $sql);

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
