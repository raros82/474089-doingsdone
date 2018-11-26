create database DOINGSDONE
	default character set utf8
	default collate utf8_general_ci;
use DOINGSDONE;

create table user (
	id INT AUTO_INCREMENT PRIMARY KEY,
	email char(128) UNIQUE,
	password char(64)
);

create table category (
	id int AUTO_INCREMENT PRIMARY KEY,
	category_name char(128)
);

create table task (
	id int AUTO_INCREMENT PRIMARY KEY,
	category_id char(64),
	user_id char(64),
	creation_date DATETIME,
	complete_date DATETIME,
	task_status TINYINT DEFAULT '0',
	task_name TEXT,
	file_atach TEXT,
	deadline DATETIME
);