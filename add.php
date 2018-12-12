<?php
require_once('init.php');

$user_id = 1;


//получаем данные о пользователе
$user = user_info($user_id, $mysqli);

//получаем информацию о всех проектах пользователя с учетом всех невыполненных задач в каждом из проектов
$categories = user_projects_with_open_tasks($user_id, $mysqli);

$errors = [];
$add_task = [];


if (!empty($_POST)) {
    $add_task = $_POST;
    $required = ['name' , 'project'];
    foreach ($required as $key) {
        if (!empty($add_task[$key])) {
            $add_task[$key] = trim($add_task[$key]);
        }
        if (empty($add_task[$key])) {
            $errors[$key] = 'Пожалуйста, заполните это поле. Максимальная длина 200 символов.';
        }
    }

   //проверка существования проекта
    if (!empty($add_task['project']) && user_project_verification($user_id, $add_task['project'], $mysqli ) == FALSE){
        $errors['project'] = 'Выбранный проект не существует';
    }

    //проверка даты
    if (!empty($add_task['date']) && (!strtotime($add_task['date']) OR (strtotime($add_task['date'])) < strtotime(date("Y-m-d", time())))){
        $errors['date'] = 'Введите дату в формате ДД.ММ.ГГГГ. Дата должна быть не меньше текущей.';
    }

    //проверка длины названия задачи
    if (!empty($add_task['name']) && iconv_strlen($add_task['name'])>200){
        $errors['name'] = 'Превышена максимальная длина 200 символов';
    }

    if (empty($errors)) {
        if(is_uploaded_file($_FILES['preview']['tmp_name'])) {
            $filename_id = uniqid();
            $add_task['file_path'] = 'uploads/' .$filename_id . '_' . $_FILES['preview']['name'];
            move_uploaded_file($_FILES['preview']['tmp_name'], $add_task['file_path']);
        }

        else {
            $add_task['file_path'] = null;
        }

        if(empty($add_task['date'])){
            $add_task['date'] = null;
        }

        $sql = 'INSERT INTO task (category_id, task_name, file_atach, deadline) VALUES (?, ?, ?, ?)';
        $stmt = mysqli_prepare($mysqli, $sql);
        mysqli_stmt_bind_param ($stmt, 'ssss' , $add_task['project'], $add_task['name'], $add_task['file_path'], $add_task['date']);
        $res = mysqli_stmt_execute($stmt);

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