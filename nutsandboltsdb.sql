use nutsandboltsdb;

CREATE TABLE `inventory` (
	`product_id` int unsigned not null auto_increment primary key,
    `product_name` nvarchar(255) not null,
    `sku` nvarchar(255) not null,
    `description` nvarchar(255) not null,
    `price` decimal(5, 2) not null
);

CREATE TABLE if not exists `employees` (
	`id` int unsigned not null auto_increment primary key,
    `first_name` varchar(255) not null,
    `last_name` varchar(255) not null,
    `username` varchar(255) not null,
    `password` char(60) not null
);

CREATE TABLE if not exists `users` (
	`id` int unsigned not null auto_increment primary key,
    `first_name` varchar(255) not null,
    `last_name` varchar(255) not null,
    `username` varchar(255) not null,
    `password` char(60) not null
);