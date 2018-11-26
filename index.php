<?php
require_once('functions.php');
require_once('data.php');

$page_content = include_template('index.php', ['tasks' => $tasks]);
$layout_content = include_template('layout.php', [
        'title' => 'Дела в порядке',
        'content' => $page_content,
        'task_category' => $task_category,
        'tasks' => $tasks,
        'user' => $user
]);

print($layout_content);