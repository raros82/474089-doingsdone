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

//чекбоксы задач - изменение статуса задачи выполненная/невыполненная
if(isset($_GET['task_id'])){
    $task_checked = intval($_GET['task_id']);

    $task_checked_exist = user_task_verification($user_id, $task_checked, $mysqli);

    if (!$task_checked_exist) {
        http_response_code(404);
        echo 'Упс. Страница не найдена';
        die();
    }

    $sql = 'SELECT * FROM task WHERE task_id = ' . $task_checked;
    $result = mysqli_query($mysqli, $sql);


    if (!$result) {
        $error = mysqli_error($mysqli);
        echo 'Ошибка Базы данных - задача не обнаружена';
        die();
    }
    else{
        $task_checked = mysqli_fetch_assoc($result);
        header ("Location:/");

    }

    if ($task_checked && isset($_GET['check'])) {

        $task_checked_status = intval($_GET['check']);
        if($task_checked_status !== 0){
            $task_checked_status = 1;
        }

        $sql = "UPDATE task SET task_status = $task_checked_status  WHERE task_id =  " . $task_checked['task_id'];
        $result = mysqli_query($mysqli, $sql);

        if (!$result) {
            $error = mysqli_error($mysqli);
            echo 'Ошибка Базы данных';
            die();
        }
    }

}


//получаем информацию о всех проектах пользователя с учетом всех невыполненных задач в каждом из проектов
$categories = user_projects_with_open_tasks($user_id, $mysqli);



//получаем список задач для вывода на странице
$selected_category = 0;
if (isset($_GET['category'])) {

    $selected_category = intval($_GET['category']);

    $selected_category_exists = user_project_verification($user_id, $selected_category, $mysqli);

    if (!$selected_category_exists) {
        unset($_SESSION['category']);
        header("Location:/?filter=all_tasks");
        exit();
    } else {
        $_SESSION['category'] = $selected_category;
    }
}

$sql = 'SELECT * FROM task t JOIN category cat ON t.category_id = cat.category_id WHERE user_id = ' . $user_id;


if (isset($_GET['filter']) && $_GET['filter'] == 'all_tasks'){
    $active_filter = 'all_tasks';
    $_SESSION['task_filter'] = $active_filter;
}

if (isset($_GET['filter']) && $_GET['filter'] == 'agenda'){
    $active_filter = 'agenda';
    $_SESSION['task_filter'] = $active_filter;
    $sql .= ' AND DAY(deadline) = DAY(NOW())';
}

if (isset($_GET['filter']) && $_GET['filter'] == 'tomorrow'){
    $active_filter = 'tomorrow';
    $_SESSION['task_filter'] = $active_filter;
    $sql .= ' AND DAY(deadline) = DAY(TOMORROW())';
}

if (isset($_GET['filter']) && $_GET['filter'] == 'overdue'){
    $active_filter = 'overdue';
    $_SESSION['task_filter'] = $active_filter;
    $sql .= ' AND DAY(deadline) < DAY(NOW())';
}

if (isset($_SESSION['task_filter'])) {
    $active_filter = $_SESSION['task_filter'];
} else {
    $active_filter = 'all_tasks';
    $_SESSION['task_filter'] = $active_filter;
}

if(isset($_SESSION['category']) && $_SESSION['category'] > 0 ){
    $selected_category = $_SESSION['category'];
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
