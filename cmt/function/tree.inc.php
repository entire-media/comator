<?php

#########################################################
#########################################################
#####                                               #####
#####    	CREATE MODUL TREE                         #####
#####                                               #####
#########################################################
#########################################################

##### CREATE AND BUILD #####
function create_tree($table, $active = 1, $filter = NULL) {
	global $conn;
	$data = array();
	$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$table." ";
	if ($active == 1) $sql.= "WHERE c_active = '".$active."' ";
	if (isset($filter)){
		$sql.= " WHERE ";
		$i = 0;
		foreach ($filter AS $key => $value){
			if ($i > 0) $sql.= "AND ";
			if ($key == 'activate'){
				$sql.= $value;
			} elseif ($key == 'q'){
				$sql.= "(".$value.")";
			} elseif ($key == 'date'){
				$sql.= $key." BETWEEN '".$value['from_date']."' AND '".$value['to_date']."' ";
			} else {
				$sql.= $key." = '".$value."' ";
			}
			$i++;
		}
	}
	$sql .= "ORDER BY sort_order ASC ";
	$result = db_mysql_query($sql, $conn);
	while($arr=db_mysql_fetch_array($result)){
		$data[$arr['id']] = $arr;
	}
	return build_tree($data);
} 

function build_tree(array &$elements, $id_parent = 0) {
	$branch = array();
	foreach ($elements AS &$element) {
		if ($element['id_parent'] == $id_parent) {
			$children = build_tree($elements, $element['id']);
			if ($children) $element['children'] = $children;
			$branch[$element['id']] = $element;
			unset($element);
		}
	}
	return $branch;
}

##### GET PARENT DATA #####
function tree_get_parents($table, $id){
	$ajdparent_ids = array();
	$has_parent = true;
	$ajdparent_ids[] = $id;
	while($has_parent){
		$id = tree_get_parent($table, $id);
		if($id > 1) $ajdparent_ids[] = $id;
		else {
			$ajdparent_ids[] = $id;
			$has_parent = false;
		}
	}
	$ajdparent_ids = array_reverse($ajdparent_ids);
	return $ajdparent_ids;
}

function tree_get_parent($table, $id){
	global $conn;
	$sql = "SELECT id_parent FROM ".$table." WHERE id = '".$id."' ";
	$result = db_mysql_query($sql, $conn);
	$arr=db_mysql_fetch_array($result);
	return $arr['id_parent'];
}

function cross_table($title, $value = 0){
	global $conn, $formdata, $modul;
	preg_match("/(.*)_(.*)/i", $modul, $next_modul);
	if ($next_modul)$next_modul = $next_modul[1];
	else $next_modul = $modul;
	$sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$_SESSION['TABLE_PREFIX'].$formdata[$title]['settings']['data']."' AND COLUMN_NAME = 'id_parent' ";
	$result = db_mysql_query($sql, $conn);
	$id_parent = db_mysql_num_rows($result);
	if ($id_parent){
		$arr_tmp = create_tree($formdata[$title]['settings']['data']);
		generate_tree_select($title, $arr_tmp, tree_get_parents($formdata[$title]['settings']['data'], $value));
		$formdata['levels'] = array (
			'type'		=>	'hidden',
			'value'		=>	1
		);
		unset($formdata[$title]);
	} else {
		if ($title == 'language') $next_modul = 'content';
		$sql = "SELECT value, label FROM ".$_SESSION['TABLE_PREFIX'].$next_modul."_fields WHERE source_table = '".$formdata[$title]['settings']['data']."' ";
		$result = db_mysql_query($sql, $conn);
		if (db_mysql_num_rows($result)){
			$arr = db_mysql_fetch_array($result);
		}
		if (!isset($arr['value']) OR !$arr['value']) $arr['value'] = 'id';
		if (!isset($arr['label']) OR !$arr['label']) $arr['label'] = 'title';
		
		$sql_tmp = "SELECT ".$arr['value'].", ".$arr['label'].", sort_order FROM ".$formdata[$title]['settings']['data']." WHERE c_active = '1' ";
		if ($formdata[$title]['settings']['data'] == 'content_templates') $sql_tmp .= "GROUP BY id_template ";
		$sql_tmp .= "ORDER BY sort_order ASC ";
		
		$result_tmp = db_mysql_query($sql_tmp, $conn);
		if ($formdata[$title]['settings']['data'] == 'cmt_accounts') $formdata[$title]['value'] = CMT_USER_ID;
		
		unset($formdata[$title]['settings']['data']);
		if ($formdata[$title]['type'] != 'radio') $formdata[$title]['settings']['data'][0] = "...";
		while($arr_tmp=db_mysql_fetch_array($result_tmp)){
			if ($arr['label'] == 'label') $formdata[$title]['settings']['data'][$arr_tmp[$arr['value']]] = v("CMT_".$arr_tmp[$arr['label']]);
			else $formdata[$title]['settings']['data'][$arr_tmp[$arr['value']]] = $arr_tmp[$arr['label']];
		}
	}
}

function generate_tree_select($title, $t_data, $parents = NULL, $i = 1, $id_parent = NULL){
	global $formdata;
	if (!isset($formdata[$title."_level_".$i])){
		$formdata[$title."_level_".$i]['type'] = $formdata[$title]['type'];
		$formdata[$title."_level_".$i]['class'] = 'reload_select';
		$formdata[$title."_level_".$i]['label'] = $formdata[$title]['label']." ".$i;
		$formdata[$title."_level_".$i]['settings'] = $formdata[$title]['settings'];
		if (isset($formdata[$title]['style'])) $formdata[$title."_level_".$i]['style'] = $formdata[$title]['style'];
		unset($formdata[$title."_level_".$i]['settings']['data']);
		$formdata[$title."_level_".$i]['settings']['data'][0] = "...";
	}
	foreach ($t_data AS $t_key => $t_value){
		if (isset($parents)){
			if (in_array($t_value['id'], $parents) OR in_array($t_value['id_parent'], $parents)) $formdata[$title."_level_".$i]['settings']['data'][$t_value['id']] = $t_value['title'];
			if (in_array($t_value['id'], $parents)) $formdata[$title."_level_".$i]['value'] = $t_value['id'];
		} else {
			$formdata[$title."_level_".$i]['settings']['data'][$t_value['id']] = $t_value['title']." ".$t_value['id_parent'];
		}
		if ($t_value['id_parent'] == $id_parent) $i = 1;
		if (isset($t_value['children'])){
			generate_tree_select($title, $t_value['children'], $parents, $i+1, $t_value['id_parent']);
		}
	}
}

function delete_tree($table, $data, $i = 1, $id_parent = NULL){
	global $conn;
	foreach ($data AS $key => $value){
		$sql = "DELETE FROM ".$_SESSION['TABLE_PREFIX'].$table." WHERE id_tree = '".$value['id']."' ";
		db_mysql_query($sql, $conn);
		$sql = "DELETE FROM ".$_SESSION['TABLE_PREFIX'].$table."_tree WHERE id = '".$value['id']."' ";
		db_mysql_query($sql, $conn);
		if ($value['id_parent'] == $id_parent) $i = 1;
		if (isset($value['children'])){
			delete_tree($table, $value['children'], $i+1, $value['id_parent']);
		}
	}
}

?>