<?php

$formdata['id_template'] = array	(
	'type'				=>	'select',
	'label'				=>	v('CMT_TEMPLATE'),
	'class'				=>	'reload_select',
	'settings'		=>	array(
		'data'			=>	'id_template'
	),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

if (isset($id_template)) cross_table('id_template', $id_template);
else cross_table('id_template');

foreach ($formdata AS $key => $val){
	if (isset(${$key})) $formdata[$key]['value'] = $$key;
}

$filter_array['BASICS'] = $formdata;

$formdata = NULL;

if (!isset($order)) $data_array['SORT'] = array("sort_order" => $direction);
else $data_array['SORT'] = array($order => $direction);

print "<div class='filter'>";
show_filter($filter_array);
print "</div><!-- /.filter -->";

$old_modul = $modul;
$modul = 'id_template';

if (isset($data_array['SORT'])){
	$old_data_array['SORT'] = $data_array['SORT'];
	unset($data_array['SORT']);
	$data_array['SORT'] = array("sort_order" => $direction);
}
if (isset($data_array['FILTER'])){
	$old_data_array['FILTER'] = $data_array['FILTER'];
	unset($data_array['FILTER']);
}

if (isset($old_data_array['FILTER']['id_template'])) $data_array['FILTER']['id'] = $old_data_array['FILTER']['id_template'];
if (isset($old_data_array['FILTER']['date'])) $data_array['FILTER']['date'] = $old_data_array['FILTER']['date'];
if (isset($old_data_array['FILTER']['title'])) $data_array['FILTER']['title'] = $old_data_array['FILTER']['title'];
if (isset($old_data_array['FILTER']['sort_order'])) $data_array['FILTER']['sort_order'] = $old_data_array['FILTER']['sort_order'];
if (isset($old_data_array['FILTER']['c_active'])) $data_array['FILTER']['c_active'] = $old_data_array['FILTER']['c_active'];


$head_array['TABLE'] = array( 
	"CMT_TITLE"								=>	"title",
	"CMT_ICON-SORT_ORDER"			=>	"sort_order"
);

$head_array['CONSTRUCT'] = array("add"=>true);

$data_array['TABLE'] = array(
	"title"					=>	"default",
	"sort_order"		=>	"default"
);

$data_array['CONSTRUCT'] =	array(
	"edit"			=>	true,
	"copy"			=>	true,
	"delete"		=>	true,
	"activate"	=>	true
);

foreach ($head_array['CONSTRUCT'] AS $key => $value){
	if ($value===true) print "<div class='engine-function'><a href='#' class='".$key."' data-content='".$modul."' ><i class='icon-".$key."'></i></a></div><!-- /.engine-function -->";
}

print "<div class='table'>";
thead($head_array);
tbody($data_array);
print "</div><!-- /.table -->";

if (isset($data_array['SORT'])){
	unset($data_array['SORT']);
	$data_array['SORT'] = $old_data_array['SORT'];
}

if (isset($data_array['FILTER'])){
	unset($data_array['FILTER']);
	$data_array['FILTER'] = $old_data_array['FILTER'];
}
$modul = $old_modul;

$head_array['TABLE'] = array( 
	"CMT_DATE"								=>	"date",
	"CMT_TITLE"								=>	"title",
	"CMT_TEMPLATE"						=>	"id_template",
	"CMT_FIELD"								=>	"id_field",
	"CMT_ICON-SORT_ORDER"			=>	"sort_order"
);

$head_array['CONSTRUCT'] = array("add"=>true);

$data_array['TABLE'] = array(
	"date"					=>	"date",
	"title"					=>	"default",
	"id_template"		=>	"select",
	"id_field"			=>	"select",
	"sort_order"		=>	"default"
);

$data_array['CONSTRUCT'] =	array(
	"edit"			=>	true,
	"copy"			=>	true,
	"delete"		=>	true,
	"activate"	=>	true
);

foreach ($head_array['CONSTRUCT'] AS $key => $value){
	if ($value===true) print "<div class='engine-function'><a href='#' class='".$key."' data-content='".$modul."' ><i class='icon-".$key."'></i></a></div><!-- /.engine-function -->";
}

print "<div class='table'>";
thead($head_array);
tbody($data_array);
print "</div><!-- /.table -->";

?>