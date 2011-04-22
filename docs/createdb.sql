
CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `money` int(11) NOT NULL DEFAULT '0',
  `bangName` varchar(30) NOT NULL,
  `rights` tinyint(4) NOT NULL DEFAULT '0',
  `filishTask` int(11) NOT NULL DEFAULT '0',
  `receiveTask` int(11) NOT NULL DEFAULT '0',
  `logins` int(11) NOT NULL,
  `regtime` datetime NOT NULL,
  `lasttime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `money` (`money`),
  KEY `logins` (`logins`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `bang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `money` int(11) NOT NULL DEFAULT '0',
  `own`	varchar(30) NOT NULL DEFAULT '',
  `members` int NOT NULL DEFAULT '0' COMMENT '成员数量',
  `orderby` int(11) NOT NULL DEFAULT '0',
  `regtime` datetime NOT NULL,
  `lasttime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `money` (`money`),
  KEY `members` (`members`),
  KEY `orderby` (`orderby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `moneyLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL default '1' COMMENT '转帐类型，1给门派转帐，2给个人转帐',
  `toid` int(11) NOT NULL COMMENT '转给谁',
  `toname` varchar(30) NOT NULL COMMENT '转给谁',
  `fromid`	varchar(30) NOT NULL COMMENT '从哪里转的',
  `fromname` int NOT NULL DEFAULT '0' COMMENT '从哪里转的这笔钱，即是从张三或武当派出转出来的',
  `whodothis` varchar(30) NOT NULL COMMENT '谁来执行的这个操作，任务自动完成的则为tasksys，扣考勤则为worksys等',
  `money` int(11) NOT NULL DEFAULT '0' COMMENT '金额，只能为正数',
  `notes` varchar(500) NOT NULL DEFAULT '' COMMENT '备注，转帐因由',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '转帐编码，用与关联同笔操作',
  `regtime` datetime NOT NULL,

  PRIMARY KEY (`id`),
  KEY `toid` (`toid`),
  KEY `toname` (`toname`),
  KEY `fromname` (`fromname`),
  KEY `fromid` (`fromid`),
  KEY `order id` (`orderid`),
  
  KEY `money` (`money`),
  KEY `whodothis` (`whodothis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;





