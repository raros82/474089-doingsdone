<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$task_category = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'deadline' => '01.12.2018',
        'category' => 'Работа',
        'complited' => 'Нет'
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'deadline' => '25.12.2018',
        'category' => 'Работа',
        'complited' => 'Нет'
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'deadline' => '21.12.2018',
        'category' => 'Учеба',
        'complited' => 'Да'
    ],
    [
        'task' => 'Встреча с другом',
        'deadline' => '22.12.2018',
        'category' => 'Входящие',
        'complited' => 'Нет'
    ],
    [
        'task' => 'Купить корм для кота',
        'deadline' => 'Нет',
        'category' => 'Домашние дела',
        'complited' => 'Нет'
    ],
    [
        'task' => 'Заказать пиццу',
        'deadline' => 'Нет',
        'category' => 'Домашние дела',
        'complited' => 'Нет'
    ]
];

$user = [
        'name' => 'Константин',
        'sex' => 'male',
        'userpic' => "#"
 ];