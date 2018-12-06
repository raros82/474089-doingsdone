<?php
require_once('init.php');

$user_id = 1;

//получаем данные о пользователе
$sql = 'SELECT * FROM user WHERE user_id = ' . $user_id;
$result = mysqli_query($mysqli, $sql);
if (!$result) {
    $error = mysqli_error($mysqli);
    die('Error : ('. $error .')');
}

$user = mysqli_fetch_assoc($result);

//получаем информацию о всех проектах пользователя с учетом всех невыполненных задач в каждом из проектов
$sql = 'SELECT cat.category_id, cat.category_name, COUNT(CASE WHEN t.task_status = 0 THEN 1 ELSE NULL END) as count_task_id FROM category cat LEFT JOIN task t ON cat.category_id = t.category_id WHERE cat.user_id =' .$user_id .' GROUP BY cat.category_id, cat.category_name';
$result = mysqli_query($mysqli, $sql);
if (!$result) {
    $error = mysqli_error($mysqli);
    die('Error : ('. $error .')');
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);


//получаем список задач для вывода на странице

$selected_category = 0;

if (isset($_GET['category'])) {
    $selected_category = intval($_GET['category']);

    $sql = 'SELECT * FROM category WHERE user_id = ' . $user_id . ' AND category_id = ' . $selected_category;

    $result = mysqli_query($mysqli, $sql);

    if (!$result) {
        $error = mysqli_error($mysqli);
        die('Извините, сайт временно не доступен. Ведутся технические работы.');
    }

    $selected_category_exists = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (count($selected_category_exists) == 0) {
        http_response_code(404);
        echo 'Упс. Страница не найдена';
        die();
    }
}

$sql = 'SELECT * FROM `task` ORDER BY `creation_date` DESC ';
if ($selected_category > 0) {
    $sql .= ' WHERE category_id =' . $selected_category;
}
$result = mysqli_query($mysqli, $sql);


if (!$result) {
    $error = mysqli_error($mysqli);
    die('Извините, сайт временно не доступен. Ведутся технические работы.');
}

$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);


$page_content = include_template('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);


$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'categories' => $categories,
    'content' => $page_content,
    'user' => $user
]);

print($layout_content);


