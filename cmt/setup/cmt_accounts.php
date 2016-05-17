<?php

if (isset($structure_install) && $structure_install == true){

	$structure = "CREATE TABLE IF NOT EXISTS `cmt_accounts` (
	  `id` int(11) NOT NULL,
	  `date` int(11) DEFAULT NULL,
	  `username` varchar(255) NOT NULL,
	  `email` varchar(255) NOT NULL,
	  `password` varchar(255) NOT NULL,
	  `password_salt` varchar(255) NOT NULL,
	  `first_name` varchar(255) DEFAULT NULL,
	  `last_name` varchar(255) DEFAULT NULL,
	  `street` varchar(255) DEFAULT NULL,
	  `street_number` varchar(255) DEFAULT NULL,
	  `zip` int(6) DEFAULT NULL,
	  `city` varchar(255) DEFAULT NULL,
	  `country` varchar(255) DEFAULT NULL,
	  `language` varchar(5) DEFAULT NULL,
	  `c_admin` enum('0','1') NOT NULL DEFAULT '0',
	  `c_moderator` enum('0','1') NOT NULL DEFAULT '0',
	  `c_editor` enum('0','1') NOT NULL DEFAULT '0',
	  `c_active` enum('0','1') NOT NULL DEFAULT '0'
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
	
	db_mysql_query($structure, $conn);
	
	$indizies = "ALTER TABLE `cmt_accounts`
  ADD PRIMARY KEY (`id`);";
	db_mysql_query($indizies, $conn);
	
	$increment = "ALTER TABLE `cmt_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	db_mysql_query($increment, $conn);
	
}

if (isset($data_install) && $data_install == true){
	$num = count($_POST);
	$i = 0;
	$sql = "INSERT INTO cmt_accounts (";
	foreach ($_POST AS $key => $val){
		$i++;
		$sql.= $key;
		if ($i != $num) $sql.=", ";
	}
  $sql.=") VALUES (";
	$i = 0;
	foreach ($_POST AS $key => $val){
		$i++;
		$sql.= "'".$val."'";
		if ($i != $num) $sql.=", ";
	}
  $sql.=")";
  db_mysql_query($sql,$conn);
}

?>