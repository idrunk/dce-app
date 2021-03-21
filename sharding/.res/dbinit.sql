CREATE TABLE IF NOT EXISTS `member` (
    `mid` bigint unsigned NOT NULL,
    `username` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `nickname` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `avatar` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `gender` tinyint unsigned NOT NULL DEFAULT '0',
    `sign_up_ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `sign_up_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
    PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `member_badge` (
    `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(15) NOT NULL,
    `memo` varchar(31) NOT NULL DEFAULT '',
    `create_time` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `member_badge_map` (
    `mid` bigint unsigned NOT NULL,
    `mb_id` tinyint unsigned NOT NULL,
    PRIMARY KEY (`mid`,`mb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `member_sign_in` (
    `id` bigint unsigned NOT NULL,
    `mid` bigint unsigned NOT NULL,
    `sign_in_date` date NOT NULL,
    `type` tinyint unsigned NOT NULL,
    `terminal` tinyint unsigned NOT NULL DEFAULT 0,
    `last_sign_in_date` date NOT NULL DEFAULT '1970-01-01',
    `create_time` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;