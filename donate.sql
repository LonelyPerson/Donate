CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action_key` varchar(255) NOT NULL,
  `action_value` text NOT NULL,
  `action_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `mokejimai` (
  `orderid` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `amount` float NOT NULL,
  `currency` varchar(50) NOT NULL,
  `points` float NOT NULL,
  `ip` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `paypal` (
  `item_number` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `currency` varchar(50) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points` float NOT NULL,
  `ip` varchar(50) NOT NULL,
  `buyer_info` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`item_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(64) NOT NULL,
  `access` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL,
  `username` varchar(255) NOT NULL,
  `balance` float NOT NULL,
  `server` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_unique_id` varchar(255) NOT NULL,
  `sms_keyword` varchar(255) NOT NULL,
  `sms_price` varchar(50) NOT NULL,
  `sms_currency` varchar(50) NOT NULL,
  `sms_response` text NOT NULL,
  `sms_date` datetime NOT NULL,
  `sms_type` varchar(20) NOT NULL,
  `sms_from` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
