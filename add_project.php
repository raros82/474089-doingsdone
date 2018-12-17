<?php
require_once('init.php');

if (!$user) {
    header("Location:/");
    exit();
}

$user_id = $user['user_id'];;


//получаем информацию о всех проектах пользователя с учетом всех невыполненных задач в каждом из проектов
$categories = user_projects_with_open_tasks($user_id, $mysqli);

$errors = [];
$add_project = [];


if (!empty($_POST)) {
    $add_project = $_POST;
    $required = ['project_name'];
    foreach ($required as $key) {
        if (!empty($add_project[$key])) {
            $add_project[$key] = trim($add_project[$key]);
        }
        if (empty($add_project[$key])) {
            $errors[$key] = 'Пожалуйста, заполните это поле. Максимальная длина 128 символов.';
        }
    }


    //проверка длины названия проекта
    if (!empty($add_project['project_name']) && iconv_strlen($add_project['project_name']) > 128) {
        $errors['project_name'] = 'Превышена максимальная длина 128 символов';
    }

    //проверка существования проекта
    if (!empty($add_project['project_name'])) {

        $project = mysqli_real_escape_string($mysqli, $add_project['project_name']);
        $sql = "SELECT * FROM category WHERE user_id = '$user_id' AND category_name = '$project'";
        $res = mysqli_query($mysqli, $sql);

        $project = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

        if ($project) {
            $errors['project_name'] = 'Такой проект уже существует';
        }
    }

    if (empty($errors)) {

        $sql = 'INSERT INTO category (user_id, category_name) VALUES (?, ?)';
        $stmt = mysqli_prepare($mysqli, $sql);
        mysqli_stmt_bind_param($stmt, 'is', $user_id, $add_project['project_name']);
        $res = mysqli_stmt_execute($stmt);

        //редирект на главную страницу
        header('Location: /');
        exit();
    }
}

$page_content = include_template('add_project_templ.php', ['add_project' => $add_project, 'errors' => $errors]);

$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке/Добавление проекта',
    'categories' => $categories,
    'content' => $page_content,
    'user' => $user
]);


print($layout_content);