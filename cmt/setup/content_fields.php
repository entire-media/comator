<?php

if (isset($structure_install) && $structure_install == true){

	$structure = "CREATE TABLE IF NOT EXISTS `content_fields` (
  `id` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `fieldset` enum('none','link') NOT NULL DEFAULT 'none',
  `type` enum('int','text','textarea','datepicker','checkbox','multicheckbox','select','radio') NOT NULL DEFAULT 'text',
  `value` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `source_table` varchar(255) NOT NULL,
  `source_value` text NOT NULL,
  `valid_type` enum('none','numeric','min','max','email','url','regex') NOT NULL DEFAULT 'none',
  `valid_value` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `c_readonly` enum('0','1') NOT NULL DEFAULT '0',
  `c_required` enum('0','1') NOT NULL DEFAULT '0',
  `c_list` enum('0','1') NOT NULL DEFAULT '0',
  `c_search` enum('0','1') NOT NULL DEFAULT '0',
  `c_default` enum('0','1') NOT NULL DEFAULT '0',
  `c_active` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
	
	db_mysql_query($structure, $conn);
	
	$indizies = "ALTER TABLE `content_fields`
  ADD PRIMARY KEY (`id`);";
	db_mysql_query($indizies, $conn);
	
	$increment = "ALTER TABLE `content_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	db_mysql_query($increment, $conn);
	
}

if (isset($data_install) && $data_install == true){

	$data = "INSERT INTO `content_fields` (`id`, `date`, `title`, `fieldset`, `type`, `value`, `label`, `source_table`, `source_value`, `valid_type`, `valid_value`, `sort_order`, `c_readonly`, `c_required`, `c_list`, `c_search`, `c_default`, `c_active`) VALUES
(1, 1460457000, 'id', 'none', 'int', '', '', '', '', 'none', '', 1, '1', '0', '0', '0', '1', '1'),
(2, 1461748200, 'date', 'none', 'datepicker', '', '', '', '', 'none', '', 2, '0', '0', '0', '0', '1', '1'),
(3, 1461748320, 'title', 'none', 'text', '', '', '', '', 'none', '', 3, '0', '1', '1', '1', '1', '1'),
(4, 1461751920, 'language', 'none', 'select', 'lkz', 'label', 'languages', '', 'none', '', 4, '0', '1', '0', '0', '1', '1'),
(5, 1461752100, 'sort_order', 'none', 'int', '', '', '', '', 'none', '', 6, '0', '1', '1', '0', '1', '1'),
(6, 1461752160, 'c_active', 'none', 'checkbox', '', '', '', '', 'none', '', 7, '0', '0', '0', '0', '1', '1'),
(7, 1461752700, 'id_template', 'none', 'select', 'id', 'title', 'id_template', '', 'none', '', 8, '0', '1', '1', '0', '1', '1'),
(8, 1461761760, 'id_tree', 'none', 'select', 'id', 'title', 'content_tree', '', 'none', '', 5, '0', '1', '1', '0', '1', '1');";
	
	db_mysql_query($data, $conn);
	
}

?>