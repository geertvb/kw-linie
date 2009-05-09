CREATE TABLE `kwl_foto` (
  `foto_id` int(11) NOT NULL AUTO_INCREMENT,
  
  `bunker_id` int(11) NOT NULL,
  `omschrijving` varchar(256) NOT NULL,
  `filename` varchar(128) NOT NULL,
  
  `mimetype` varchar(64) NOT NULL,
  `size` bigint(20) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `content` mediumblob NOT NULL,
  
  `thumb_mimetype` varchar(64) NOT NULL,
  `thumb_size` bigint(20) NOT NULL,
  `thumb_width` int(11) NOT NULL,
  `thumb_height` int(11) NOT NULL,
  `thumb_content` mediumblob NOT NULL,
  
  PRIMARY KEY (`foto_id`)
  
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
