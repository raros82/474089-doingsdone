<?php
require_once('init.php');

$reg_data = [];

if (!empty($_POST)) {
    $form = $_POST;
    $errors = [];
    $required = ['email', 'password', 'name'];

    foreach ($required as $key) {
        if (!empty($form[$key])) {
            $form[$key] = trim($form[$key]);
        }
        if (empty($form[$key])) {
            $errors[$key] = 'Пожалуйста, заполните это поле.';
        }
    }

    if (!empty($form['email']) && filter_var($form['email'], FILTER_VALIDATE_EMAIL) === FALSE) {
        $errors['email'] = 'Введите корректный e-mail.';
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($mysqli, $form['email']);
        $sql = "SELECT user_id FROM user WHERE email = '$email'";
        $res = mysqli_query($mysqli, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO user (email, password, name) VALUES (?, ?, ?)';
            $stmt = mysqli_prepare($mysqli, $sql);
            mysqli_stmt_bind_param($stmt, 'sss', $form['email'], $password, $form['name']);
            $res = mysqli_stmt_execute($stmt);

        }
        if ($res && empty($errors)) {
            //header("Location: /enter.php"); // редирект на страницу входа
            header('Location: /'); // временный редирект на главную страницу для проверки работоспособности кода
            exit();
        }
    }

    $reg_data['errors'] = $errors;
    $reg_data['values'] = $form;

}

$page_content = include_template('reg.php', $reg_data);


print($page_content);