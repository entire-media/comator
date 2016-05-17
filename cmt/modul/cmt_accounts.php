<?php

require_once BACKEND.'modul_head/accounts.php';

$formdata['q'] = array	(
	'type'				=>	'text',
	'label'				=>	v('CMT_SEARCH')
);

$formdata['c_q_username'] = array	(
		'type'						=>	'checkbox',
		'parent_class'		=>	'multi_checkbox',
		'settings'				=>	array(
			'data'					=>	array('1' => v('CMT_USERNAME'))
		),
		'value'						=>	1
);

$formdata['c_q_first_name'] = array	(
		'type'						=>	'checkbox',
		'parent_class'		=>	'multi_checkbox',
		'settings'				=>	array(
			'data'					=>	array('1' => v('CMT_FIRST_NAME'))
		),
		'value'						=>	1
);

$formdata['c_q_last_name'] = array	(
		'type'						=>	'checkbox',
		'parent_class'		=>	'multi_checkbox',
		'settings'				=>	array(
			'data'					=>	array('1' => v('CMT_LAST_NAME'))
		),
		'value'						=>	1
);

$formdata['c_q_email'] = array	(
		'type'						=>	'checkbox',
		'parent_class'		=>	'multi_checkbox',
		'settings'				=>	array(
			'data'					=>	array('1' => v('CMT_EMAIL'))
		),
		'value'						=>	1
);

foreach ($formdata AS $key => $val){
	if (isset(${$key})) $formdata[$key]['value'] = $$key;
}

$filter_array['SEARCH'] = $formdata;

$formdata = NULL;

print "<div class='filter'>";
show_filter($filter_array);
print "</div><!-- /.filter -->";

$head_array['TABLE'] = array(
	"CMT_DATE"				=>	"date",
	"CMT_USERNAME"		=>	"username",
	"CMT_FIRST_NAME"	=>	"first_name",
	"CMT_LAST_NAME"		=>	"last_name"
);

$head_array['CONSTRUCT'] = array("add"=>true);

$data_array['TABLE'] = array(
	"date"				=>	"date",
	"username"		=>	"default",
	"first_name"	=>	"default",
	"last_name"		=>	"default"
);

$data_array['CONSTRUCT'] =	array(
	"edit"			=>	true,
	"copy"			=>	true,
	"delete"		=>	true,
	"activate"	=>	true
);

if (!isset($order)) $data_array['SORT'] = array("date" => $direction);
else $data_array['SORT'] = array($order => $direction);

foreach ($head_array['CONSTRUCT'] AS $key => $value){
	if ($value===true) print "<div class='engine-function'><a href='#' class='".$key."' data-content='".$modul."' ><i class='icon-".$key."'></i></a></div><!-- /.engine-function -->";
}

print "<div class='table'>";
thead($head_array);
tbody($data_array);
print "</div><!-- /.table -->";

?>