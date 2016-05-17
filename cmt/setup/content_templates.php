<?php

if (isset($structure_install) && $structure_install == true){

	$structure = "CREATE TABLE IF NOT EXISTS `content_templates` (
  `id` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `label_before` varchar(255) NOT NULL,
  `label_after` varchar(255) NOT NULL,
  `label_head` varchar(255) NOT NULL,
  `next_row` enum('0','1') NOT NULL DEFAULT '1',
  `max_columns` int(11) NOT NULL,
  `max_rows` int(11) NOT NULL,
  `max_width` int(11) NOT NULL,
  `align` enum('none','left','center','right') NOT NULL DEFAULT 'none',
  `id_template` int(11) DEFAULT NULL,
  `id_field` int(11) DEFAULT NULL,
  `sort_order` int(11) NOT NULL,
  `c_active` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
	
	db_mysql_query($structure, $conn);
	
	$structure = "CREATE TABLE IF NOT EXISTS `id_template` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `c_active` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

	db_mysql_query($structure, $conn);
	
	$indizies = "ALTER TABLE `content_templates`
  ADD PRIMARY KEY (`id`);";
	db_mysql_query($indizies, $conn);
	
	$increment = "ALTER TABLE `content_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	db_mysql_query($increment, $conn);
	
}

?>