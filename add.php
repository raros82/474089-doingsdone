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
$categories = user_projects_with_open_tasks($user_id, $mysqli);

$errors = [];
$add_task = [
    'name' => '',
    'project' => null,
    'date' => null, //лучше deadline, ибо это конкретная дата
    'file_path' => null
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $add_task['name'] = $_POST['name'];
    $add_task['project'] = $_POST['project'];
    $add_task['date'] = $_POST['date'];
    //$add_task['file_path'] = $_POST['file_path'];

    //$add_file = $_FILES;

    $required = ['name' , 'project'];


    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить. Максимальная длина 200 символов.';
        }
    }


   //проверка существования проекта
    if (!isset($add_task['project']) OR user_project_verification($user_id, $add_task['project'], $mysqli ) == FALSE){
        $errors['project'] = 'Выбранный проект не существует';
    }


    //проверка даты
    if (!isset($add_task['date']) OR !strtotime($add_task['date']) OR (strtotime($add_task['date'])) < strtotime(date("Y-m-d", time()))){
        $errors['date'] = 'Введите дату в формате ДД.ММ.ГГГГ. Дата должна быть не меньше текущей.';
    }


    //проверка длины названия задачи
    if (iconv_strlen($add_task['name'])>200){
        $errors['name'] = 'Превышена максимальная длина 200 символов';
    }


    if (count($errors)) {
        $page_content = include_template('add.php', ['add_task' => $add_task, 'errors' => $errors, 'categories' => $categories]);
    }

    //в случае отсутствия ошибок переадресация на главную страницу
    elseif(isset($add_task['project']) && isset($add_task['name']) && isset($add_task['date'])) {
        if(is_uploaded_file($_FILES['preview']['tmp_name'])) {
            $filename_id = uniqid();
            $add_task['file_path'] = 'uploads/' .$filename_id . '_' . $_FILES['preview']['name'];
            move_uploaded_file($_FILES['preview']['tmp_name'], $add_task['file_path']);
            $sql = 'INSERT INTO task (category_id, task_name, file_atach, deadline) VALUES (?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($mysqli, $sql, [$add_task['project'], $add_task['name'], $add_task['file_path'], $add_task['date']]);
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


$page_content = include_template('add.php', ['add_task' => $add_task, 'errors' => $errors, 'categories' => $categories]);

$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке/Добавление задачи',
    'categories' => $categories,
    'content' => $page_content,
    'user' => $user
]);


print($layout_content);