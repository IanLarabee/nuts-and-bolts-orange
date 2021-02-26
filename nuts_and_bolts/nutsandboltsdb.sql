use nutsandboltsdb;

CREATE TABLE `inventory` (
	`product_id` int unsigned not null auto_increment primary key,
    `product_name` nvarchar(255) not null,
    `sku` nvarchar(255) not null,
    `decription` nvarchar(255) not null,
    `price` decimal(5, 2) not null
);