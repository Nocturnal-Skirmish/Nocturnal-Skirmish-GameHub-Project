-- SQL script that creates the necessary database and tables.
CREATE DATABASE IF NOT EXISTS gamehub;

USE gamehub;

CREATE TABLE users (
    user_id int NOT NULL AUTO_INCREMENT,
    username varchar(25),
    password varchar(80),
    email varchar(128),
    joindate varchar(255),
    last_login BIGINT DEFAULT 0,
    profile_picture varchar(128) DEFAULT "defaultprofile.svg",
    profile_border varchar(128) DEFAULT "defaultborder.webp",
    profile_banner varchar(128) DEFAULT "defaultbanner.jpg",
    nickname varchar(25),
    description varchar(500),
    runes BIGINT DEFAULT 0,
    PRIMARY KEY (user_id)
);

CREATE TABLE recovery_codes (
    user_id int,
    code int,
    expire BIGINT
);

CREATE TABLE redeemed_codes (
    user_id int,
    code varchar(128),
    id int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
);

CREATE TABLE temp_profilepic (
    name varchar(128),
    expire BIGINT
);

CREATE TABLE dev_codes (
    code varchar(128) NOT NULL,
    runes int,
    border varchar(128),
    skin varchar(128),
    PRIMARY KEY (code)
);

CREATE TABLE border_inventory (
    user_id int,
    border varchar(128),
    id int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
);

CREATE TABLE friend_list (
    user_id_1 int,
    user_id_2 int,
    id int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
);

CREATE TABLE pending_friend_list (
    user_id_1 int,
    user_id_2 int,
    sent varchar(64),
    id int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
);

CREATE TABLE ip_adresses (
    user_id int,
    ip varchar(64),
    last_login varchar(64)
);

CREATE TABLE banned (
    id int NOT NULL AUTO_INCREMENT,
    user_id int,
    ip varchar(64),
    type varchar(64),
    duration varchar(64),
    reason varchar(300),
    PRIMARY KEY (id)
);

CREATE TABLE kick (
    user_id int
)