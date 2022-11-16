

CREATE TABLE `users` (
                         `id` int(10) unsigned NOT NULL auto_increment,
                         `email` varchar(50) NOT NULL,
                         `password` varchar(100) NOT NULL,
                         `token` varchar(40) NULL,
                         PRIMARY KEY  (`id`),
                         UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `links` (
                         `id` int(10) unsigned NOT NULL auto_increment,
                         `full_url` TEXT NOT NULL,
                         `code` varchar(30) NOT NULL,
                         `user_id` int(10) NOT NULL,
                         PRIMARY KEY  (`id`),
                         UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

