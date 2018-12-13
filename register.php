<?php
require_once('init.php');

$reg_data = [];

if (!empty($_POST)) {
    $form = [];
    $errors = [];
    $required = ['email', 'password', 'name'];

    foreach ($required as $key) {
        if (!empty($_POST[$key])) {
            $form[$key] = trim($_POST[$key]);
        }
        if (empty($form[$key])) {
            $errors[$key] = 'Пожалуйста, заполните это поле.';
        }
    }

    //проверка длины имени
    if (empty($errors['name']) && iconv_strlen($form['name']) > 50) {
        $errors['name'] = 'Имя не должно превышать 50 символов.';
    }

    //проверка длины email
    if (empty($errors['email']) && iconv_strlen($form['email']) > 128) {
        $errors['email'] = 'Email не должен превышать 128 символов.';
    }

    //проверка корректности email
    if (empty($errors['email']) && filter_var($form['email'], FILTER_VALIDATE_EMAIL) === FALSE) {
        $errors['email'] = 'Введите корректный e-mail.';
    }

    //проверка уникальности email
    if (!empty($form['email'])) {
        $email = mysqli_real_escape_string($mysqli, $form['email']);
        $sql = "SELECT user_id FROM user WHERE email = '$email'";
        $res = mysqli_query($mysqli, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }


    if (empty($errors)) {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $sql = 'INSERT INTO user (email, password, name) VALUES (?, ?, ?)';
        $stmt = mysqli_prepare($mysqli, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $form['email'], $password, $form['name']);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location: /auth.php"); // редирект на страницу входа
            exit();
        }
    }

    $reg_data['errors'] = $errors;
    $reg_data['values'] = $form;
}
//$reg_data['title'] = 'Дела в порядке / Регистрация';
$page_content = include_template('reg.php', $reg_data);

$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'content' => $page_content
]);


print($layout_content);