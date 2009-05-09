CREATE TABLE `kwl_document` (
  `document_id` int(11) NOT NULL AUTO_INCREMENT,
  `bunker_id` int(11) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `mimetype` varchar(64) NOT NULL,
  `size` bigint(20) NOT NULL,
  `content` blob NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
