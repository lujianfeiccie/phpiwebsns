CREATE TABLE `isns_article_admin` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_sex` tinyint(2) DEFAULT 1,
  `user_ico` varchar(150) NOT NULL,
  `gid` smallint(5) DEFAULT 0,
  `gname` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `isns_article_channel` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned DEFAULT 0,
  `name` varchar(30) NOT NULL,
  `order_num` smallint(5) NOT NULL DEFAULT 0,
  `nodepath` text DEFAULT NULl,
  `meta_key` varchar(80) DEFAULT '',
  `meta_title` varchar(80) DEFAULT '',
  `meta_descrip` varchar(80) DEFAULT '',
  `out_link` varchar(100) DEFAULT '',
  `type_id` tinyint(2) DEFAULT 0,
  `is_menu` tinyint(2) DEFAULT 0,
  `is_digg` tinyint(2) DEFAULT 0,
  `is_show` tinyint(2) DEFAULT 0,
  `count` mediumint(8) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `isns_article_slide` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT '',
  `photo_src` varchar(150) DEFAULT '',
  `link` varchar(150) DEFAULT '',
  `order_num` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `isns_article_ads` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT '',
  `link` varchar(150) DEFAULT '',
  `type_id` tinyint(2) DEFAULT 0,
  `re_src` varchar(150) DEFAULT '',
  `descrip` varchar(50) DEFAULT '',
  `width` smallint(5) DEFAULT 0,
  `height` smallint(5) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `isns_article_resource` (
	`resource_id` varchar(30) NOT NULL,
	`modules_name` varchar(30) NOT NULL,
	`name` varchar(30) NOT NULL,
	PRIMARY KEY (`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `isns_article_group` (
	`id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(30) NOT NULL,
	`rights` text default NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `isns_article_comment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_ip` varchar(15) NOT NULL,
  `user_email` varchar(80) DEFAULT '',
  `content_id` mediumint(8) unsigned NOT NULL,
  `content` text NOT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `isns_article_news` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `channel_name` varchar(30) DEFAULT '',
  `title` varchar(80) NOT NULL,
  `thumb` varchar(150) NULL,
  `hits` mediumint(8) unsigned DEFAULT 0,
  `tag` varchar(50) NULL,
  `comments` mediumint(8) unsigned DEFAULT 0,
  `content` text NULL,
  `origin` varchar(200) NULL,
  `keywords` varchar(40) NOT NULL DEFAULT 'iwebsns',
  `description` char(255) NOT NULL DEFAULT '',
  `resume` varchar(150) DEFAULT '',
  `status` tinyint(2) unsigned DEFAULT 0,
  `support` mediumint(5) unsigned DEFAULT 0,
  `against` mediumint(5) unsigned DEFAULT 0,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) NOT NULL DEFAULT 'admin',
  `user_ico` varchar(150) NOT NULL DEFAULT '',
  `checker_name` varchar(20) DEFAULT '',
  `addtime` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  `order_num` int(5) NULL DEFAULT 0,
  `is_digg` tinyint(2) DEFAULT 0,
  `is_recom` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `isns_article_tag` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `hot` tinyint(2) DEFAULT 0,
  `count` mediumint(8) unsigned DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `isns_article_tag_relation` (
  `id` mediumint(8) unsigned NOT NULL,
  `content_id` mediumint(8) NOT NULL,
  KEY `id` (`id`),
  KEY `content_id` (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `isns_article_resource` (`resource_id`, `modules_name`, `name`) VALUES
('article_05', 'channel', '频道管理'),
('article_06', 'privacy', '权限管理'),
('article_07', 'comment', '评论管理'),
('article_08', 'content', '文章管理');