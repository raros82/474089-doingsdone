<?php

$mysqli = new mysqli('doingsdone','root','','474089-doingsdone');

//запрос на выборку проектов (категорий)
if ($mysqli->connect_error) {
    die('Ошибка связи с базой данный');
    }