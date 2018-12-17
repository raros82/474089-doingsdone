<?php
require_once('init.php');

if ($user) {
    header("Location:/");
    exit();
}

$form = [];
$errors = [];

if (!empty($_POST)) {

    $required = ['email', 'password'];

    foreach ($required as $key) {
        if (!empty($_POST[$key])) {
            $form[$key] = trim($_POST[$key]);
        }
        if (empty($form[$key])) {
            $errors[$key] = 'Пожалуйста, заполните это поле.';
        }
    }

    $user = [];

    if (!count($errors)) {
        $email = mysqli_real_escape_string($mysqli, $form['email']);
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $res = mysqli_query($mysqli, $sql);

        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
        if (!$user) {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }

    if (!count($errors)) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;

            header("Location:/");
            exit();

        } else {
            $errors['password'] = 'Неверный пароль';
        }
    }
}

$page_content = include_template('auth.php', ['form' => $form, 'errors' => $errors]);
$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'content' => $page_content
]);


print($layout_content);

