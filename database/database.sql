-- create database DATABASE;
-- CREATE USER 'USER'@'HOST' IDENTIFIED BY 'PASSWORD';
-- GRANT ALL ON DATABASE.* TO 'USER'@'HOST';
-- use DATABASE;
CREATE TABLE users (
    id int NOT NULL AUTO_INCREMENT,
    name varchar(255),
    role varchar(255),
    PRIMARY KEY (id)
);