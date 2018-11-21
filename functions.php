<?php
function include_template($name, $data) {
$name = 'templates/' . $name;
$result = '';

if (!file_exists($name)) {
return $result;
}

ob_start();
extract($data);
require $name;

$result = ob_get_clean();

return $result;
}


function category_task_count($tasks_arr, $category_name) {
    $task_count=0;
    foreach ($tasks_arr as $tasks_value){
        if ($tasks_value['Категория'] == $category_name) {
            $task_count ++;
        }
    }
    return $task_count;
}

function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
}