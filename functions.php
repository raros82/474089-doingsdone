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

//
//function category_task_count($tasks_arr, $category_name) {
//    $task_count=0;
//    foreach ($tasks_arr as $tasks_value){
//        if ($tasks_value['category_id'] == $category_name) {
//            $task_count ++;
//        }
//    }
//    return $task_count;
//}

function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
}


function leeway($term) {
    $b_time = is_numeric(strtotime($term));

    if ($b_time && !is_null($term)) {
        $curdate = time();
        $a_time = strtotime($term) - $curdate;
        if ($a_time <= 86400) {
            return true;
        } else {return false;};
    } else {return false;}
};

function deadline($deadline) {
    if(!is_null($deadline)) {
        $deadline_date = strtotime($deadline);
        echo date("d.m.Y",$deadline_date);}
    else {
        echo "Нет";};
    };

