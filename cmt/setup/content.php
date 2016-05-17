<?php

if (isset($structure_install) && $structure_install == true){

	$structure = "CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `language` varchar(5) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `c_active` int(11) DEFAULT NULL,
  `id_template` int(11) DEFAULT NULL,
  `id_tree` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
	
	db_mysql_query($structure, $conn);
	
	$indizies = "ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);";
	db_mysql_query($indizies, $conn);
	
	$increment = "ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	db_mysql_query($increment, $conn);
	
}

?>