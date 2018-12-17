<?php

/**
 * Подключает шаблон страницы из папки templates/
 * @param string $name Имя шаблона
 * @param string $data Массив с данными для данного шаблона
 * @return false|string
 */
function include_template($name, $data)
{
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

/**
 * Заменяет в строке символы на безопасные HTML-символы
 * @param $str Изначальная строка
 * @return string Безопасная строка
 */
function esc($str)
{
    $text = htmlspecialchars($str);

    return $text;
}


/**
 * Сравниевает переданное значение времени с текущим и возвращает TRUE если разница между полученным значением времени и текущим временем меньше чем 24 часа.
 * @param string $term Значение времени
 * @return bool
 */
function leeway($term)
{
    $b_time = strtotime($term);

    if ($b_time !== false) {
        $curdate = time();
        $a_time = strtotime($term) - $curdate;
        if ($a_time <= 86400) {
            return true;
        }
    }
    return false;
}


/**
 * Проверяет переданное значение - если дата, то возвращает в отформатированном виде, если NULL - возвращает "Нет"
 * @param string $deadline
 */
function deadline($deadline)
{
    if (!is_null($deadline)) {
        $deadline_date = strtotime($deadline);
        echo date("d.m.Y", $deadline_date);
    } else {
        echo "Нет";
    }
}

/**
 * Проверяет принадлежит ли данный прокт данному юзеру
 * @param $user id-юзера
 * @param $project id-проекта
 * @param $bd_link ссылка на базу данных
 * @return array|null
 */
function user_project_verification($user, $project, $bd_link)
{

    $sql = 'SELECT category_id FROM category WHERE user_id = ' . $user . ' AND category_id = ' . intval($project);
    $result = mysqli_query($bd_link, $sql);
    if (!$result) {
        $error = mysqli_error($bd_link);
        die('Error : (' . $error . ')');
    }
    $user_project_verification = mysqli_fetch_assoc($result);
    return $user_project_verification;
}

/**
 * Проверяет принадлежит ли данная задача данному юзеру
 * @param $user id-юзера
 * @param $task id-задачи
 * @param $bd_link ссылка на базу данных
 * @return array|null
 */
function user_task_verification($user, $task, $bd_link)
{

    $sql = 'SELECT task_id FROM task t JOIN category cat ON t.category_id = cat.category_id WHERE user_id = ' . $user . ' AND task_id = ' . intval($task);
    $result = mysqli_query($bd_link, $sql);
    if (!$result) {
        $error = mysqli_error($bd_link);
        die('Error : (' . $error . ')');
    }
    $user_task_verification = mysqli_fetch_assoc($result);
    return $user_task_verification;
}


/**
 * Возвращает информацию о всех проектах пользователя с учетом всех невыполненных задач в каждом из проектов
 * @param $user id-юзера
 * @param $bd_link ссылка на базу данных
 * @return array|null
 */
function user_projects_with_open_tasks($user, $bd_link)
{
    $sql = 'SELECT cat.category_id, cat.category_name, COUNT(CASE WHEN t.task_status = 0 THEN 1 ELSE NULL END) as count_task_id FROM category cat LEFT JOIN task t ON cat.category_id = t.category_id WHERE cat.user_id =' . $user . ' GROUP BY cat.category_id, cat.category_name';
    $result = mysqli_query($bd_link, $sql);
    if (!$result) {
        $error = mysqli_error($bd_link);
        die('Error : (' . $error . ')');
    }
    $user_projects_with_open_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $user_projects_with_open_tasks;
}


/**
 * Возвращает все данный из таблицы user
 * @param $user id-юзера
 * @param $bd_link ссылка на базу данных
 * @return array|null
 */
function user_info($user, $bd_link)
{
    $sql = 'SELECT * FROM user WHERE user_id = ' . $user;
    $result = mysqli_query($bd_link, $sql);
    if (!$result) {
        $error = mysqli_error($bd_link);
        die('Error : (' . $error . ')');
    }
    $user_info = mysqli_fetch_assoc($result);
    return $user_info;
}

/**
 * Подготавливает безопасных запрос в БД
 * @param $link ссылка на базу данных
 * @param $sql запрос SQL
 * @param array $data данные для запроса
 * @return bool|mysqli_stmt
 */

function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
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