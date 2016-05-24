<?php

$formdata['type'] = array	(
	'type'				=>	'select',
	'label'				=>	v('CMT_TYPE'),
	'class'				=>	'reload_select',
	'settings'		=>	array(
		'data'					=>	array(
			'0'								=>	'...',
			'int'							=>	v('CMT_TYPE_INTEGER'),
			'text'						=>	v('CMT_TYPE_TEXT'),
			'textarea'				=>	v('CMT_TYPE_TEXTAREA'),
			'datepicker'			=>	v('CMT_TYPE_DATEPICKER'),
			'checkbox'				=>	v('CMT_TYPE_CHECKBOX'),
			'multicheckbox'		=>	v('CMT_TYPE_MULTICHECKBOX'),
			'select'					=>	v('CMT_TYPE_SELECT'),
			'multiselect'			=>	v('CMT_TYPE_MULTISELECT'),
			'radio'						=>	v('CMT_TYPE_RADIO')
		)
	),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

foreach ($formdata AS $key => $val){
	if (isset(${$key})) $formdata[$key]['value'] = $$key;
}

$filter_array['BASICS'] = $formdata;

$formdata = NULL;

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
	"CMT_DATE"								=>	"date",
	"CMT_TITLE"								=>	"title",
	"CMT_TYPE"								=>	"type",
	"CMT_ICON-SORT_ORDER"			=>	"sort_order"
);

$head_array['CONSTRUCT'] = array("add"=>true);

$data_array['TABLE'] = array(
	"date"					=>	"date",
	"title"					=>	"default",
	"type"					=>	"default",
	"sort_order"		=>	"default"
);

$data_array['CONSTRUCT'] =	array(
	"edit"					=>	true,
	"copy"					=>	true,
	"delete"				=>	true,
	"activate"			=>	true
);

if (!isset($order)) $data_array['SORT'] = array("sort_order" => $direction);
else $data_array['SORT'] = array($order => $direction);

foreach ($head_array['CONSTRUCT'] AS $key => $value){
	if ($value===true) print "<div class='engine-function'><a href='#' class='".$key."' data-content='".$modul."' ><i class='icon-".$key."'></i></a></div><!-- /.engine-function -->";
}

print "<div class='table'>";
thead($head_array);
tbody($data_array);
print "</div><!-- /.table -->";

?>