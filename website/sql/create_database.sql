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
    artifacts BIGINT DEFAULT 0,
    user_rank varchar(64) DEFAULT "peasant",
    deck varchar(300) DEFAULT NULL,
    selected_character varchar(64) DEFAULT NULL,
    PRIMARY KEY (user_id)
);

CREATE TABLE cards (
    card_id int NOT NULL AUTO_INCREMENT,
    card_name varchar(128),
    description varchar(400) DEFAULT "Coming soon...",
    bp int DEFAULT 0,
    damage int DEFAULT 0,
    healing int DEFAULT 0,
    special boolean DEFAULT false,
    effects varchar(300) DEFAULT NULL,
    combo_list varchar(300) DEFAULT NULL,
    evolution varchar(300) DEFAULT NULL,
    rarity varchar(128) DEFAULT "Common",
    rarity_int int DEFAULT 1,
    element varchar(128) DEFAULT "Default";
    tag varchar(300) DEFAULT NULL,
    css varchar(500) DEFAULT 0,
    texture varchar(128) DEFAULT "Template_Card.webp",
    credit varchar(256) DEFAULT "The NocSkir Team",
    PRIMARY KEY (card_id)
);

CREATE TABLE ranks (
    id int NOT NULL AUTO_INCREMENT,
    rank_name varchar(128),
    required_score int DEFAULT 0,
    derank_pity_score int DEFAULT 0,
    points_on_win int DEFAULT 0,
    points_on_loss int DEFAULT 0,
    runes_on_win int DEFAULT 0,
    runes_on_loss int DEFAULT 0,
    artifacts_on_win int DEFAULT 0,
    starter_pity_score int DEFAULT 0,
    texture varchar(128),
    PRIMARY KEY (id)
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
);

CREATE TABLE chats (
    id int NOT NULL AUTO_INCREMENT,
    tablename varchar(128),
    user_id int,
    type varchar(32),
    last_chat int NOT NULL DEFAULT 0,
    last_accessed int NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
);

CREATE TABLE groupchat_settings (
    id int NOT NULL AUTO_INCREMENT,
    tablename varchar(128),
    groupchat_name varchar(30) DEFAULT "New Groupchat",
    groupchat_image varchar(128) DEFAULT "defaultgroupchat.svg",
    PRIMARY KEY (id)
);

CREATE TABLE visits (
    date varchar(128),
    amount int
);

CREATE TABLE error_reports (
    id int NOT NULL AUTO_INCREMENT,
    user_id int,
    ip varchar(64),
    timestamp varchar(64),
    category varchar(32),
    what_happened varchar(500),
    screenshot varchar(64),
    unix_timestamp int,
    PRIMARY KEY (id)
);


CREATE DATABASE IF NOT EXISTS gamehub_messages;

USE gamehub_messages;
CREATE TABLE public (message_id int NOT NULL AUTO_INCREMENT, user_id int, message varchar(500), file varchar(50), timestamp varchar(64), edited int DEFAULT 0, reply int DEFAULT 0, unix_timestamp int NOT NULL DEFAULT 0, PRIMARY KEY (message_id));

CREATE DATABASE IF NOT EXISTS gamehub_messages_archive;

CREATE DATABASE IF NOT EXISTS gamehub_messages_deleted_edited;

USE gamehub_messages_deleted_edited;

CREATE TABLE deleted_messages (message_id int, user_id int, message varchar(500), file varchar(50), timestamp varchar(64), edited int DEFAULT 0, reply int DEFAULT 0);

CREATE TABLE edited_messages (message_id int, user_id int, message varchar(500), file varchar(50), timestamp varchar(64), edited int DEFAULT 0, reply int DEFAULT 0);

CREATE DATABASE IF NOT EXISTS nocskir;

USE nocskir;

CREATE TABLE matchmaking (
    id int NOT NULL AUTO_INCREMENT,
    user_id_1 int,
    user_id_2 int NOT NULL DEFAULT 0,
    gamemode varchar(32),
    user_rank varchar(64) DEFAULT "none",
    match_name varchar(255),
    PRIMARY KEY (id)
);

CREATE TABLE match_invites (
    id int NOT NULL AUTO_INCREMENT,
    outgoing int,
    incoming int,
    accepted boolean DEFAULT 0,
    expire int,
    PRIMARY KEY (id)
);

CREATE DATABASE IF NOT EXISTS nocskir_matches;
