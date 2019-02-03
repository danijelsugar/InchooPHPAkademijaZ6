DROP DATABASE IF EXISTS social_network;
CREATE DATABASE social_network CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use social_network;

create table post(
id int not null primary key auto_increment,
content text,
posttime DATETIME DEFAULT CURRENT_TIMESTAMP,
image varchar(255)
)engine=InnoDB;

create table comment(
id int not null primary key auto_increment,
postid int not null,
content text,
commenttime DATETIME DEFAULT CURRENT_TIMESTAMP
)engine=InnoDB;

insert into post (content) values
('Evo danas pada kiša opet :('),
('Jedem jagode.');
insert into comment (postid,content) values
(1,'Bas šteta :('),
(2,'Blago tebi');

alter table comment add foreign key (postid) references post(id);