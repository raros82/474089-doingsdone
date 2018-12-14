<?php
require_once('init.php');

if (!$user) {
    $page_content = include_template('guest.php', []);
    $layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'content' => $page_content,
    'guest_layout' => true]);
    print($layout_content);
    exit();
}


$user_id = $user['user_id'];

//получаем информацию о всех проектах пользователя с учетом всех невыполненных задач в каждом из проектов
$categories = user_projects_with_open_tasks($user_id, $mysqli);


//получаем список задач для вывода на странице
$selected_category = 0;
if (isset($_GET['category'])) {

    $selected_category = intval($_GET['category']);

    $selected_category_exists = user_project_verification($user_id, $selected_category, $mysqli);

    if (!$selected_category_exists) {
        http_response_code(404);
        echo 'Упс. Страница не найдена';
        die();
    }
}

$sql = 'SELECT * FROM task t JOIN category cat ON t.category_id = cat.category_id WHERE user_id = ' . $user_id;
if ($selected_category > 0) {
    $sql .= ' AND t.category_id =' . $selected_category;
}
$sql .= ' ORDER BY creation_date DESC ';

$result = mysqli_query($mysqli, $sql);


if (!$result) {
    $error = mysqli_error($mysqli);
    $tasks = [];
}
else{
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$page_content = include_template('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);

$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'categories' => $categories,
    'content' => $page_content,
    'user' => $user,
    'selected_category' => $selected_category
    ]);


print($layout_content);
