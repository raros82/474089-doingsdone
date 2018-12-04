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

//запрос на выборку задач для подсчета количества задач в каждом проекте
$sql = 'SELECT category.category_id, `category_name`, COUNT(`task_id`) as `count_task_id` FROM task RIGHT JOIN category ON task.category_id = category.category_id GROUP BY category.category_id';
$result = mysqli_query($mysqli, $sql);

if ($result) {
    $tasks_count = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($result);
    die('Error : ('. $error .')');
};


//запрос на выборку задач для вывода на странице
$sql = 'SELECT * FROM `task`';
if (isset($_GET['category']) && in_array($_GET['category'],array_column($categories, 'category_id')) ) {
    $sort_field = $_GET['category'];
    $sql = 'SELECT * FROM `task` WHERE `category_id` =' . $sort_field;
}
elseif (isset($_GET['category']) && !in_array($_GET['category'],array_column($categories, 'category_id')) ) {
    die(http_response_code(404));
};


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
    'tasks_count' => $tasks_count,
    'user' => $user
]);

print($layout_content);





//print_r(array_column($categories, 'category_id'));
//array_column($categories, 'category_id');