CREATE TABLE admin (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(128) NOT NULL,
    password varchar(64) NOT NULL,
    email varchar(128) NOT NULL,
    avatar varchar(128) NOT NULL,
    role_type char(1) NOT NULL DEFAULT 1,
    ins_id int(11) NOT NULL,
    upd_id int(11),
    ins_datetime datetime NOT NULL,
    upd_datetime datetime,
    del_flag char(1) NOT NULL DEFAULT 0
)

