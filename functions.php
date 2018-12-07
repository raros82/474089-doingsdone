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
        echo date("d.m.Y",$deadline_date);}
    else {echo "Нет";}
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