<?php
require_once 'vendor/autoload.php';
require_once 'init.php';


$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$sql = "SELECT cat.category_id, t.task_id AS task_id, t.task_name AS task, t.deadline AS deadline, u.name AS name, u.email AS email FROM category cat JOIN task t ON cat.category_id = t.category_id JOIN user u ON cat.user_id = u.user_id WHERE t.deadline >= NOW() AND t.deadline <= (NOW() + INTERVAL 1 HOUR);";

$res = mysqli_query($mysqli, $sql);

if ($res && mysqli_num_rows($res)) {
    $tasks_for_email = mysqli_fetch_all($res, MYSQLI_ASSOC);

    foreach ($tasks_for_email as $task) {

        $message = new Swift_Message();
        $message->setSubject("Уведомление от сервиса «Дела в порядке»");
        $message->setFrom('keks@phpdemo.ru');
        $message->setBcc($task['email']);

        $msg_content = "Уважаемый, " . $task['name'] . ". У вас запланирована задача " . $task['task'] . " на " . $task['deadline'];
        $message->setBody($msg_content, 'text/plain');

        $result = $mailer->send($message);

        if ($result) {
            print("Рассылка успешно отправлена");
        } else {
            print("Не удалось отправить рассылку: " . $logger->dump());
        }

    }
}
