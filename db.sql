SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `coin1` varchar(200) NOT NULL,
  `coin2` varchar(200) NOT NULL,
  `coin3` varchar(200) NOT NULL,
  `coin4` varchar(200) NOT NULL,
  `coin5` varchar(200) NOT NULL,
  `coin6` varchar(200) NOT NULL,
  `coin7` varchar(200) NOT NULL,
  `coin8` varchar(200) NOT NULL,
  `coin9` varchar(200) NOT NULL,
  `coin10` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `apis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `apikey` text NOT NULL,
  `privkey` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `balances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `coin1` varchar(200) NOT NULL,
  `coin2` varchar(200) NOT NULL,
  `coin3` varchar(200) NOT NULL,
  `coin4` varchar(200) NOT NULL,
  `coin5` varchar(200) NOT NULL,
  `coin6` varchar(200) NOT NULL,
  `coin7` varchar(200) NOT NULL,
  `coin8` varchar(200) NOT NULL,
  `coin9` varchar(200) NOT NULL,
  `coin10` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `buy_orderbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(300) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `username` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `want` varchar(100) NOT NULL,
  `initial_amount` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `rate` varchar(200) NOT NULL,
  `processed` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `ordersfilled` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `trader` varchar(100) NOT NULL,
  `oid` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `want` varchar(100) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `rate` varchar(200) NOT NULL,
  `total` varchar(200) NOT NULL,
  `processed` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `sell_orderbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(300) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `username` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `want` varchar(100) NOT NULL,
  `initial_amount` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `rate` varchar(200) NOT NULL,
  `processed` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(300) NOT NULL,
  `username` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `coin` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `txid` text NOT NULL,
  `amount` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

CREATE TABLE IF NOT EXISTS `who_is_online` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `botname` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `bot` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `guest` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `countrycode` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`),
  KEY `countrycode` (`countrycode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(300) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `username` varchar(65) NOT NULL,
  `password` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
