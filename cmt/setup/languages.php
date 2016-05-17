<?php

if (isset($structure_install) && $structure_install == true){

	$structure = "CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL,
  `lkz` varchar(3) NOT NULL,
  `label` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `c_active` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
	
	db_mysql_query($structure, $conn);
	
	$indizies = "ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);";
	db_mysql_query($indizies, $conn);
	
	$increment = "ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	db_mysql_query($increment, $conn);
	
}

if (isset($data_install) && $data_install == true){

	$data = "INSERT INTO `languages` (`id`, `lkz`, `label`, `sort_order`, `c_active`) VALUES
(1, 'de', 'GERMAN', 2, '1');";
	
	db_mysql_query($data, $conn);
	
}

?>