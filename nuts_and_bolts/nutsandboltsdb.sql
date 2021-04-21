use nutsandboltsdb;

CREATE TABLE if not exists `inventory` (
	`product_id` int unsigned not null auto_increment primary key,
    `product_name` varchar(255) not null,
    `sku` varchar(255) not null,
    `description` varchar(255) not null,
    `price` decimal(5, 2) not null,
    `quantity` int not null,
    `category_id` int unsigned,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
);

CREATE TABLE if not exists `categories`(
`id` int unsigned not null auto_increment primary key,
`name` varchar(255) not null
);

CREATE TABLE if not exists `images`(
`id` int unsigned auto_increment not null primary key,
`filename` varchar(255) not null,
`mimetype` varchar(50) not null,
`imagedata` mediumblob not null,
`product_id` int unsigned not null,
FOREIGN KEY (`product_id`) REFERENCES `inventory`(`product_id`)
) engine=InnoDB;

CREATE TABLE if not exists `employees` (
	`id` int unsigned not null auto_increment primary key,
    `first_name` varchar(255) not null,
    `last_name` varchar(255) not null,
    `username` varchar(255) not null,
    `password` char(60) not null
) engine=InnoDB;

CREATE TABLE if not exists `users` (
	`id` int unsigned not null auto_increment primary key,
    `first_name` varchar(255) not null,
    `last_name` varchar(255) not null,
    `username` varchar(255) not null,
    `password` char(60) not null
) engine=InnoDB;

CREATE TABLE if not exists`loginlogs`(
`id` int auto_increment not null primary key,
`TryTime` bigint(20) not null,
`IpAddress` varbinary(16),
`user_id` int unsigned not null
) engine=InnoDB default charset=latin1;
