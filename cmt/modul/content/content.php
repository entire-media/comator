<?php

if (isset($id_tree)) {
	if ($id_tree > '0')	$data_array['FILTER']['id_tree'] = $id_tree;
}

$formdata['date'] = array	(
	'type'				=>	'filter-date',
	'label'				=>	v('CMT_DATE')
);

if (isset($from_date)) {
	$formdata['date']['value']['from_date'] = $from_date;
	$data_array['FILTER']['date']['from_date'] = mktime(0,0,0,$from_date['month'],$from_date['day'],$from_date['year']);
}
if (isset($to_date)) {
	$formdata['date']['value']['to_date'] = $to_date;
	$data_array['FILTER']['date']['to_date'] = mktime(23,59,59,$to_date['month'],$to_date['day'],$to_date['year']);
}

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

$formdata['id_tree'] = array	(
	'type'				=>	'select',
	'label'				=>	v('CMT_LEVEL'),
	'settings'		=>	array(
		'data'			=>	'content_tree'
	),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

if (isset($id_tree)) cross_table('id_tree', $id_tree);
else cross_table('id_tree');

foreach ($formdata AS $key => $val){
	if (isset(${$key})) $formdata[$key]['value'] = $$key;
}

$filter_array['BASICS'] = $formdata;

$formdata = NULL;

$formdata['q'] = array	(
	'type'				=>	'text',
	'label'				=>	v('CMT_SEARCH'),
	'style'				=>	array(
		'class'			=>	"col-3_of_7"
	)
);


$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$modul."_fields WHERE c_active = '1' AND c_search = '1' ";
$result = db_mysql_query($sql, $conn);
while($arr=db_mysql_fetch_array($result)){
	
	$formdata['c_q_'.$arr['title']] = array	(
			'type'						=>	'checkbox',
			'parent_class'		=>	'multi_checkbox'
	);
	$formdata['c_q_'.$arr['title']]['settings']['data'] = array('1' => v('CMT_'.strtoupper($arr['title'])));
	$formdata['c_q_'.$arr['title']]['value'] = 1;
		
}

foreach ($formdata AS $key => $val){
	if (isset(${$key})) $formdata[$key]['value'] = $$key;
}

$filter_array['SEARCH'] = $formdata;

$formdata = NULL;

$formdata['c_activate_active'] = array	(
	'type'						=>	'checkbox',
	'parent_class'		=>	'multi_checkbox',
	'label'						=>	v('CMT_ACTIVE')
);
$formdata['c_activate_active']['settings']['data'] = array('1' => v('CMT_YES'));

$formdata['c_activate_notactive'] = array	(
	'type'						=>	'checkbox',
	'parent_class'		=>	'multi_checkbox'
);
$formdata['c_activate_notactive']['settings']['data'] = array('1' => v('CMT_NO'));

foreach ($formdata AS $key => $val){
	if (isset(${$key})) $formdata[$key]['value'] = $$key;
}

$filter_array['OPTIONS'] = $formdata;

$formdata = NULL;

print "<div class='filter'>";
show_filter($filter_array);
print "</div><!-- /.filter -->";

$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$modul."_fields WHERE c_active = '1' AND c_list = '1' ORDER BY sort_order";
$result = db_mysql_query($sql, $conn);
while($arr=db_mysql_fetch_array($result)){
	if ($arr['title'] != 'sort_order') $head_array['TABLE']['CMT_'.strtoupper($arr['title'])] = $arr['title'];
	else $head_array['TABLE']['ICON-'.strtoupper($arr['title'])] = $arr['title'];
	$data_array['TABLE'][$arr['title']] = $arr['type'];
}

$head_array['CONSTRUCT'] = array("add"=>true);

$data_array['CONSTRUCT'] =	array(
	"edit"			=>	true,
	"copy"			=>	true,
	"delete"		=>	true,
	"activate"	=>	true
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