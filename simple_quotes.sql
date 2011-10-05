
CREATE TABLE IF NOT EXISTS `quotes` (
  `quote_id` int(11) NOT NULL auto_increment,
  `text` varchar(255) NOT NULL,
  `author` varchar(255) default NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY  (`quote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
