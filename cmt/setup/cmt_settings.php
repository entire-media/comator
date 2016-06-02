<?php

if (isset($structure_install) && $structure_install == true){

	$structure = "CREATE TABLE IF NOT EXISTS `cmt_settings` (
  	`id` int(11) NOT NULL,
  	`title` varchar(255) DEFAULT NULL,
  	`value` varchar(255) NOT NULL,
  	`c_active` enum('0','1') NOT NULL DEFAULT '0'
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
	
	db_mysql_query($structure, $conn);
	
	$indizies = "ALTER TABLE `cmt_settings`
  ADD PRIMARY KEY (`id`);";
	db_mysql_query($indizies, $conn);
	
	$increment = "ALTER TABLE `cmt_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	db_mysql_query($increment, $conn);
	
}

if (isset($data_install) && $data_install == true){

	$data = "INSERT INTO `cmt_settings` (`id`, `title`, `value`, `c_active`) VALUES
(1, 'default_modul', 'content', '1'),
(2, 'version_core', '".$setup_version."', '1'),
(3, 'update_core', '0', '0');";
	
	db_mysql_query($data, $conn);
	
}

?>