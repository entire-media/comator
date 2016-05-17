<?php

require_once BACKEND.'modul_head/content.php';

$formdata['q'] = array	(
	'type'				=>	'text',
	'label'				=>	v('CMT_SEARCH')
);

$formdata['c_q_title'] = array	(
		'type'						=>	'checkbox',
		'parent_class'		=>	'multi_checkbox',
		'settings'				=>	array(
			'data'					=>	array('1' => v('CMT_TITLE'))
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
	"CMT_ICON-TREE"				=>	"title",
	"CMT_ICON-C-TOP"			=>	"c_top",
	"CMT_ICON-C-RIGHT"		=>	"c_right",
	"CMT_ICON-C-BOTTOM"		=>	"c_bottom",
	"CMT_ICON-C-LEFT"			=>	"c_left",
	"CMT_ICON-C-TOP-2"		=>	"c_top_2",
	"CMT_ICON-C-RIGHT-2"	=>	"c_right_2",
	"CMT_ICON-C-BOTTOM-2"	=>	"c_bottom_2",
	"CMT_ICON-C-LEFT2"		=>	"c_left_2"
);

$head_array['CONSTRUCT'] = array("add"=>true);

$data_array['TABLE'] = array(
	"title"					=>	"default",
	"c_top"					=>	"activate",
	"c_right"				=>	"activate",
	"c_bottom"			=>	"activate",
	"c_left"				=>	"activate",
	"c_top_2"				=>	"activate",
	"c_right_2"			=>	"activate",
	"c_bottom_2"		=>	"activate",
	"c_left_2"			=>	"activate"
);

$data_array['CONSTRUCT'] =	array(
	"edit"			=>	true,
	"copy"			=>	true,
	"delete"		=>	true,
	"activate"	=>	true
);


if (isset($order)) $data_array['SORT'] = array($order => $direction);

foreach ($head_array['CONSTRUCT'] AS $key => $value){
	if ($value===true) print "<div class='engine-function'><a href='#' class='".$key."' data-content='".$modul."' ><i class='icon-".$key."'></i></a></div><!-- /.engine-function -->";
}

print "<div class='table'>";
thead($head_array);
tbody($data_array);
print "</div><!-- /.table -->";

?>