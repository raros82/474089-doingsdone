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
    $b_time = is_numeric(strtotime($term));

    if ($b_time && $term != "0000-00-00 00:00:00") {
        $curdate = time();
        $a_time = strtotime($term) - $curdate;
        if ($a_time <= 86400) {
            return true;
        }
    }
    return false;
}


function deadline($deadline) {
    if(!is_null($deadline) && $deadline != "0000-00-00 00:00:00" ) {
        $deadline_date = strtotime($deadline);
        echo date("d.m.Y",$deadline_date);
    }
    else {
        echo "Нет";
    }
}


function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
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