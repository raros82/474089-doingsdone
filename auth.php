<?php
require_once('init.php');

session_start();

if (!empty($_POST)) {
    $form = [];
    $errors = [];
    $required = ['email', 'password'];

    foreach ($required as $key) {
        if (!empty($_POST[$key])) {
            $form[$key] = trim($_POST[$key]);
        }
        if (empty($form[$key])) {
            $errors[$key] = 'Пожалуйста, заполните это поле.';
        }
    }

    if(!empty($form['email']) && !empty($form['password'])) {

        $email = mysqli_real_escape_string($mysqli, $form['email']);
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $res = mysqli_query($mysqli, $sql);

        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;


        if (!count($errors) and $user) {
            if (password_verify($form['password'], $user['password'])) {
                $_SESSION['user'] = $user;
            }
            else {
                $errors['password'] = 'Неверный пароль';
            }
        }
        else {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }

    if(count($errors)){
        $page_content = include_template('auth.php', ['form' => $form, 'errors' => $errors]);
    }
    else {
        header("Location:/"); // куда переход???
        exit();
    }
}
else {
    if(isset($_SESSION['user'])){
        $page_content = include_template('index.php', ['user' => $user]);
    }
    else{
        $page_content = include_template('auth.php', []);
    }
}


$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'content' => $page_content
]);


print($layout_content);

