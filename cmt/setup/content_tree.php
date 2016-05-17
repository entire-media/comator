<?php

if (isset($structure_install) && $structure_install == true){

	$structure = "CREATE TABLE IF NOT EXISTS `content_tree` (
  `id` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `title_sub` varchar(255) DEFAULT NULL,
  `description` text,
  `text` text,
  `addition` text,
  `id_parent` int(11) DEFAULT '0',
  `language` varchar(5) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `c_top` enum('0','1') NOT NULL DEFAULT '0',
  `c_right` enum('0','1') NOT NULL DEFAULT '0',
  `c_bottom` enum('0','1') NOT NULL DEFAULT '0',
  `c_left` enum('0','1') NOT NULL DEFAULT '0',
  `c_top_2` enum('0','1') NOT NULL DEFAULT '0',
  `c_right_2` enum('0','1') NOT NULL DEFAULT '0',
  `c_bottom_2` enum('0','1') NOT NULL DEFAULT '0',
  `c_left_2` enum('0','1') NOT NULL DEFAULT '0',
  `c_active` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
	
	db_mysql_query($structure, $conn);
	
	$indizies = "ALTER TABLE `content_tree`
  ADD PRIMARY KEY (`id`);";
	db_mysql_query($indizies, $conn);
	
	$increment = "ALTER TABLE `content_tree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	db_mysql_query($increment, $conn);
	
}

?>