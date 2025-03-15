CREATE TABLE `attachment` (
    `attachment_id` int(11) NOT NULL AUTO_INCREMENT,
    `note_id`       int(11) NOT NULL,
    `attachment`    varchar(70) DEFAULT NULL,
    PRIMARY KEY (`attachment_id`),
    KEY `note_id` (`note_id`),
    CONSTRAINT `fk_attachment_note` FOREIGN KEY (`note_id`) REFERENCES `note` (`note_id`)
);

CREATE TABLE `note` (
    `note_id`     int(11) NOT NULL AUTO_INCREMENT,
    `user_id`     int(11) NOT NULL,
    `category_id` int(11) NOT NULL,
    `title`       varchar(70) DEFAULT NULL,
    `content`     text DEFAULT NULL,
    `created_at`  datetime NOT NULL,
    `updated_at`  datetime DEFAULT NULL,
    `deadline`    datetime DEFAULT NULL,
    `completion`  tinyint(1) DEFAULT 0,
    `star`        tinyint(1) DEFAULT 0,
    PRIMARY KEY (`note_id`),
    KEY `user_id` (`user_id`),
    KEY `category_id` (`category_id`),
    CONSTRAINT `fk_note_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
    CONSTRAINT `fk_note_category` FOREIGN KEY (`category_id`) REFERENCES `note_category` (`category_id`)
);

CREATE TABLE `note_category` (
    `category_id` int(11) NOT NULL AUTO_INCREMENT,
    `name`        varchar(70) NOT NULL,
    PRIMARY KEY (`category_id`)
);

CREATE TABLE `reminder` (
    `reminder_id`   int(11) NOT NULL AUTO_INCREMENT,
    `note_id`       int(11) NOT NULL,
    `user_id`       int(11) NOT NULL,
    `reminder_time` datetime DEFAULT NULL,
    `active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`reminder_id`),
    KEY `note_id` (`note_id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `fk_reminder_note` FOREIGN KEY (`note_id`) REFERENCES `note` (`note_id`),
    CONSTRAINT `fk_reminder_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
);

CREATE TABLE `token` (
    `token_id`          int(11) NOT NULL AUTO_INCREMENT,
    `user_id`           int(11) NOT NULL,
    `token_type`        enum('email_verification','password_reset') NOT NULL,
    `token`             varchar(70) NOT NULL,
    `token_expiration`  datetime NOT NULL,
    PRIMARY KEY (`token_id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `fk_token_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
);

CREATE TABLE `user` (
    `user_id`         int(11) NOT NULL AUTO_INCREMENT,
    `username`        varchar(70) NOT NULL,
    `email`           varchar(70) NOT NULL,
    `password_hash`   varchar(70) NOT NULL,
    `first_name`      varchar(70) DEFAULT NULL,
    `last_name`       varchar(70) DEFAULT NULL,
    `profile_picture` varchar(70) DEFAULT NULL,
    `created_at`      datetime NOT NULL,
    `updated_at`      datetime DEFAULT NULL,
    PRIMARY KEY (`user_id`)
);

CREATE EVENT delete_expired_tokens
    ON SCHEDULE
        EVERY 1 DAY
    DO 
        DELETE FROM token
        WHERE token_expiration <= NOW();

CREATE EVENT delete_expired_reminders
    ON SCHEDULE
        EVERY 1 DAY
    DO 
        DELETE FROM reminder
        WHERE active != 1;
