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


function leeway($term) {
    $b_time = is_numeric(strtotime($term));

    if ($b_time && !is_null($term)) {
        $curdate = time();
        $a_time = strtotime($term) - $curdate;
        if ($a_time <= 86400) {
            return true;
        }
        else {return false;
        }
    }
}

function deadline($deadline) {
    if(!is_null($deadline)) {
        $deadline_date = strtotime($deadline);
        echo date("d.m.Y",$deadline_date);}
    else {
        echo "Нет";}
}

