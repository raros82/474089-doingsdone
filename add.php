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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $add_task = $_POST;
    $add_file = $_FILES;

    $required = ['name'];
    $errors = [];

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить. Максимальная длина 200 символов.';
        }
    }

    //проверка существования проекта
    if (!in_array($add_task['project'], array_column($categories,'category_id'))){
        $errors['project'] = 'Выбранный проект не существует';
    }

    //проверка даты
    if (strtotime($add_task['date']) && (strtotime($add_task['date'])) <= (strtotime('now') - 86400)){
        $errors['date'] = 'Введите дату в формате ДД.ММ.ГГГГ. Дата должна быть не меньше текущей.';
    }

    //проверка длины названия задачи
    if (iconv_strlen($add_task['name'])>200){
        $errors['name'] = 'Превышена максимальная длина 200 символов';
    }


    if (count($errors)) {
        $page_content = include_template('add.php', ['add_task' => $add_task, 'errors' => $errors, 'categories' => $categories]);

        $add_task = array();
        $add_file = array();
    }
    //в случае отсутствия ошибок переадресация на главную страницу
    else {
        if(is_uploaded_file($_FILES['preview']['tmp_name'])) {
            $filename_id = uniqid();
            $filename = $filename_id . '_' . $_FILES['preview']['name'];
            move_uploaded_file($_FILES['preview']['tmp_name'], 'uploads/' . $filename);
            $sql = 'INSERT INTO task (category_id, task_name, file_atach, deadline) VALUES (?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($mysqli, $sql, [$add_task['project'], $add_task['name'], 'uploads/' . $filename, $add_task['date']]);
            $res = mysqli_stmt_execute($stmt);
        }
        else {
            $sql = 'INSERT INTO task (category_id, task_name, deadline) VALUES (?, ?, ?)';
            $stmt = db_get_prepare_stmt($mysqli, $sql, [$add_task['project'], $add_task['name'], $add_task['date']]);
            $res = mysqli_stmt_execute($stmt);
        }
        //редирект на главную страницу
        header('Location: /');
        exit();

    }
}

// Если метод не POST, то загружаем пустую форму
else {
    $page_content = include_template('add.php', ['errors' => FALSE,'categories' => $categories]);
 }


//обновляем информацию о всех проектах пользователя с учетом всех невыполненных задач в каждом из проектов
$sql = 'SELECT cat.category_id, cat.category_name, COUNT(CASE WHEN t.task_status = 0 THEN 1 ELSE NULL END) as count_task_id FROM category cat LEFT JOIN task t ON cat.category_id = t.category_id WHERE cat.user_id =' .$user_id .' GROUP BY cat.category_id, cat.category_name';
$result = mysqli_query($mysqli, $sql);
if (!$result) {
    $error = mysqli_error($mysqli);
    die('Error : ('. $error .')');
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);


$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке/Добавление задачи',
    'categories' => $categories,
    'content' => $page_content,
    'user' => $user
]);

print($layout_content);