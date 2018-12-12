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


function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
}



function leeway($term){
    $b_time = strtotime($term);

    if ($b_time !== FALSE) {
        $curdate = time();
        $a_time = strtotime($term) - $curdate;
        if ($a_time <= 86400) {
            return true;
        }
    }
    return false;
}



function deadline($deadline) {
    if(!is_null($deadline)) {
        $deadline_date = strtotime($deadline);
        echo date("d.m.Y",$deadline_date);
    }
    else {
        echo "Нет";
    }
}

function user_project_verification($user, $project, $bd_link) {

    $sql = 'SELECT category_id FROM category WHERE user_id = ' . $user . ' AND category_id = ' . intval($project);
    $result = mysqli_query($bd_link, $sql);
    if (!$result) {
        $error = mysqli_error($bd_link);
        die('Error : ('. $error .')');
    }
    return $result;
}

//функция возвращает информацию о всех проектах пользователя с учетом всех невыполненных задач в каждом из проектов
function user_projects_with_open_tasks($user, $bd_link){
    $sql = 'SELECT cat.category_id, cat.category_name, COUNT(CASE WHEN t.task_status = 0 THEN 1 ELSE NULL END) as count_task_id FROM category cat LEFT JOIN task t ON cat.category_id = t.category_id WHERE cat.user_id =' . $user . ' GROUP BY cat.category_id, cat.category_name';
    $result = mysqli_query($bd_link, $sql);
    if (!$result) {
        $error = mysqli_error($bd_link);
        die('Error : (' . $error . ')');
    }
    $user_projects_with_open_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $user_projects_with_open_tasks;
}

function user_info ($user, $bd_link){
    $sql = 'SELECT * FROM user WHERE user_id = ' . $user;
    $result = mysqli_query($bd_link, $sql);
    if (!$result) {
        $error = mysqli_error($bd_link);
        die('Error : (' . $error . ')');
    }
    $user_info = mysqli_fetch_assoc($result);
    return $user_info;
}
