CREATE DATABASE `474089-doingsdone`
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;
USE `474089-doingsdone`;

create table `user` (
	`user_id` INT AUTO_INCREMENT PRIMARY KEY,
	`registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	`name` VARCHAR(50),
	`email` VARCHAR(128) UNIQUE NOT NULL,
	`password` VARCHAR(64)
);

CREATE TABLE `category` (
	`category_id` INT AUTO_INCREMENT PRIMARY KEY,
	`user_id` INT NOT NULL,
	`category_name` VARCHAR(128) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
);

CREATE TABLE `task` (
	`task_id` int AUTO_INCREMENT PRIMARY KEY,
	`category_id` INT NOT NULL,
	`creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	`complete_date` DATETIME,
	`task_status` TINYINT DEFAULT '0',
	`task_name` VARCHAR(200) NOT NULL,
	`file_atach` VARCHAR(200),
	`deadline` DATETIME,
	FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
	FULLTEXT task (task_name)
);

