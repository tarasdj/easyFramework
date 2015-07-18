create table grants (id MEDIUMINT NOT NULL AUTO_INCREMENT, grant_user varchar(100), PRIMARY KEY  (id))ENGINE=InnoDB AUTO_INCREMENT=-1 DEFAULT CHARSET=utf8;
insert into grants (grant_user) values ('User');
insert into grants (grant_user) values ('Admin');
