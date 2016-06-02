<?php

if (isset($structure_install) && $structure_install == true){

	$structure = "CREATE TABLE IF NOT EXISTS `cmt_modul` (
  	`id` int(11) NOT NULL,
  	`modul` varchar(255) NOT NULL,
  	`modul_string` varchar(255) NOT NULL,
  	`sort_order` int(11) NOT NULL,
  	`c_active` enum('0','1') NOT NULL DEFAULT '0'
	) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;";
	
	db_mysql_query($structure, $conn);
	
	$indizies = "ALTER TABLE `cmt_modul`
  ADD PRIMARY KEY (`id`);";
	db_mysql_query($indizies, $conn);
	
	$increment = "ALTER TABLE `cmt_modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	db_mysql_query($increment, $conn);
	
}

if (isset($data_install) && $data_install == true){

	$data = "INSERT INTO `cmt_modul` (`id`, `modul`, `modul_string`, `sort_order`, `c_active`) VALUES
	(1, 'content', '', 1, '1'),
	(2, 'labels', '', 3, '1'),
	(3, 'accounts', '', 4, '1'),
	(4, 'update', '', 5, '1');";
	
	db_mysql_query($data, $conn);
	
}

?>