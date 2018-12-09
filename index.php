<?php
require_once('init.php');

$user_id = 1;


//получаем данные о пользователе
$user = user_info($user_id, $mysqli);

//получаем информацию о всех проектах пользователя с учетом всех невыполненных задач в каждом из проектов
$categories = user_projects_with_open_tasks($user_id, $mysqli);


//получаем список задач для вывода на странице

$selected_category = 0;

if (isset($_GET['category'])) {

    $selected_category = intval($_GET['category']);

    $selected_category_exists = user_project_verification($user_id,  $selected_category, $mysqli);

    if(!$selected_category_exists) {
        http_response_code(404);
        echo 'Упс. Страница не найдена';
        die();
    }

}

$sql = 'SELECT * FROM `task` ORDER BY `creation_date` DESC ';
if ($selected_category > 0) {
    $sql = ' SELECT * FROM `task` WHERE category_id =' . $selected_category .' ORDER BY `creation_date` DESC ';
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
