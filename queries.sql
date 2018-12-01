/*Ввод данных пользователя*/
INSERT INTO `user` (name, email, `password`)
VALUES ('Константин', 'kostya99@mail.ru', 'boss');

/*Ввод категорий задач*/`474089-doingsdone`
INSERT INTO `category` (user_id, category_name) 
VALUES ('1','Входящие'), 
		 ('1','Учеба'), 
		 ('1','Работа'),
	    ('1','Домашние дела'), 
		 ('1','Авто');

/*Ввод задач*/
INSERT INTO `task` (category_id, task_status, task_name, deadline)
VALUES ('3', '0', 'Собеседование в IT компании', '01.12.2018'),
       ('3', '0', 'Выполнить тестовое задание', '25.12.2018'),
       ('2', '1', 'Сделать задание первого раздела', '21.12.2018'),
       ('1', '0', 'Встреча с другом', '22.12.2018'),
       ('4', '0', 'Купить корм для кота', NULL ),
       ('4', '0', 'Заказать пиццу', NULL );


/*получить список из всех проектов для одного пользователя*/
SELECT * FROM `task` WHERE user_id = 1;

/*получить список из всех задач для одного проекта*/
SELECT * FROM `task` WHERE category_id = 3;

/*пометить задачу как выполненную*/
UPDATE `task` SET task_status = '1' WHERE task_id = 1;

/*получить все задачи для завтрашнего дня*/
SELECT * FROM `task` WHERE deadline = CURRENT_DATE() + 1;

/*обновить название задачи по её идентификатору*/
UPDATE `task` SET task_name = 'Сделать задание первого раздела' WHERE task_id = 3;



