DROP TABLE IF EXISTS `coky_contacts`;

CREATE TABLE `coky_contacts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `full_name` varchar(50) COLLATE utf8_bin NOT NULL,
    `phone` varchar(50) COLLATE utf8_bin DEFAULT NULL,
    `email` varchar(50) COLLATE utf8_bin DEFAULT NULL,
    `address` varchar(150) COLLATE utf8_bin DEFAULT NULL,
    `message` text COLLATE utf8_bin NOT NULL,
    `data` text COLLATE utf8_bin DEFAULT NULL,
    `privacy_policy` int(11) NOT NULL DEFAULT 1,
    `send_promo` int(11) NOT NULL DEFAULT 0,
    `status` int(11) NOT NULL DEFAULT 1,
    `created` datetime DEFAULT current_timestamp(),
    `modified` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_bin;
