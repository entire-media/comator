<?php

#########################################################
#########################################################
#####                                               #####
#####    	HTML HELP FUNCTIONS                       #####
#####                                               #####
#########################################################
#########################################################


##### PRINT SIDEBAR TREE #####
function tree_sidebar($modul, $t_data, $parents = NULL, $i = 1, $id_parent = NULL){
	global $id_tree;
	foreach ($t_data AS $t_key => $t_value){
		$url = "?modul=".$modul."&id_tree=".$t_value['id'];
		if ($t_value['id_parent'] == $id_parent) $i = 1;
		if (isset($t_value['children'])){
			print "<li class='has-dropdown level-".$i." ";
			if ($id_tree == $t_value['id']) print 'current';
			print "'><a href='".$url."' title='".$t_value['title']."'>";
			for ($y = 2; $y <= $i; $y++){
				print "<span class='spacer'></span>";
			}
			print "<i class='icon-tree'></i><span class='toggle ";
			if (isset($parents)){
				if (in_array($t_value['id'], $parents)) print 'active';
			}
			print "'>".$t_value['title']."</span></a>";
				print "<ul class='dropdown ";
				if (isset($parents)){
					if (in_array($t_value['id'], $parents)) print 'active';
				}
				print "'>";
					tree_sidebar($modul, $t_value['children'], $parents, $i+1, $t_value['id_parent']);
				print "</ul><!-- /.dropdown -->";
			print "</li><!-- /.has-dropdown -->";
		} else {
			print "<li class='level-".$i." ";
			if ($id_tree == $t_value['id']) print 'current';
			print "'><a href='".$url."' title='".$t_value['title']."'>";
			for ($y = 2; $y <= $i; $y++){
				print "<span class='spacer'></span>";
			}
			print "<i class='icon-tree'></i>".$t_value['title']."</a></li>";
		}
	}
}



function tree_nav_table($params, $title, $t_data, $parents = NULL, $i = 1, $id_parent = NULL, $tbody = ''){
	global $modul;
	foreach ($t_data AS $t_key => $t_value){
		$tbody .= "<div class='table-row'>";
		foreach ($params['TABLE'] AS $key => $value){
			$data = init($t_value[$key], $value, $key);
			if ($value == 'activate'){
				$tbody .= "<div class='table-cell icon-only ".$key."'>";
				if ($t_value[$key]) $tbody .= "<a href='#' class='icon-cell ".$key."_de".$value."' data-content='".$modul."' id='".$t_value['id']."' ><i class='icon-".$value."-active'></i>";
				else $tbody .= "<a href='#' class='icon-cell ".$key."_".$value."' data-content='".$modul."' id='".$t_value['id']."' ><i class='icon-".$value."'></i>";
				$tbody .= "</div><!-- /.table-cell -->";
			} else {
				$tbody .= "<div class='table-cell ".$key."'>";
				for ($y = 2; $y <= $i; $y++){
					$tbody .= "<span class='spacer'></span>";
				}
				$tbody .= "<i class='icon-tree'></i>".$data."</div><!-- /.table-cell -->";
			}
		}
		$width = 40+26*count($params['CONSTRUCT']);
		$tbody .= "<div class='table-cell tools' style='width: ".$width."px;'>";
		foreach ($params['CONSTRUCT'] AS $key => $value){
			if ($value===true) {
				if ($key == 'activate'){
					if ($t_value['c_active']) $tbody .= "<a href='#' class='icon-cell de".$key."' data-content='".$modul."' id='".$t_value['id']."' ><i class='icon-".$key."-active'></i>";
					else $tbody .= "<a href='#' class='icon-cell ".$key."' data-content='".$modul."' id='".$t_value['id']."' ><i class='icon-".$key."'></i>";
					$tbody .= "</a>";
				} else {
					$tbody .= "<a href='#' class='icon-cell ".$key."' data-content='".$modul."' id='".$t_value['id']."' ><i class='icon-".$key."'></i></a>";
				}
			}
		}
		$tbody .= "</div><!-- /.table-cell -->";
		$tbody .= "</div><!-- /.table-row -->";
		if ($t_value['id_parent'] == $id_parent) $i = 1;
		if (isset($t_value['children'])){
			$tbody .= tree_nav_table($params, $title, $t_value['children'], $parents, $i+1, $t_value['id_parent'], '');
		}
	}
	return $tbody;
}



function show_filter($filter_array = NULL){
	global $modul;
	if (isset($filter_array)){
		print "
			<form method='POST' action='?modul=".$modul."'>
				<div class='filter-head'>
					<i class='icon-filter'></i>".v('CMT_HEADLINE_FILTER')."
					<div class='filter-hide'><i class='icon-toggle ";
					if (!isset($_SESSION['toggle_filter']) OR $_SESSION['toggle_filter'] == 'show') print "active";
					else print "inactive";
					print "'></i></div><!-- /.filter-hide -->
				</div><!-- /.filter-head -->";
			
			print "<div class='filter-content ".$_SESSION['toggle_filter']."'>";
			foreach ($filter_array AS $key => $value){
				if (isset($key)){
					print "
						<div class='filter-title'>
							<h3>".v('CMT_'.$key)."<i class='icon-toggle ";
							if (!$_SESSION['toggle_filter_'.strtolower($key)] OR $_SESSION['toggle_filter_'.strtolower($key)] == 'show') print "active";
							else print "inactive";
							print "' id='".strtolower($key)."'></i></h3>
						</div><!-- /.filter-title -->";
					print "<div class='filter-form ".strtolower($key)." ".$_SESSION['toggle_filter_'.strtolower($key)]."'>";
					if ($_SESSION['toggle_filter_'.strtolower($key)] == 'hidden') {
						foreach ($value AS $sub_key => $sub_value){
							$value[$sub_key]['form_settings']['form_attributes']['disabled'] = 'disabled';
						}
					}
					print_form ($value);
					print "</div><!-- /.filter-form -->";
				}
			}
			print "
				<div class='filter-title'>
					<h3>&nbsp</h3>
				</div><!-- /.filter-title -->
				<div class='form_submit'>
					<button type='submit' name='cmt_filter' id='submit-form' title='".v('CMT_BUTTON_FILTER')."'><i class='icon-filter'></i>".v('CMT_BUTTON_FILTER')."</button>
				</div>
			</div><!-- /.filter-content -->
		</form>";
	}
}

function get_field_type($id){
	global $conn, $modul;
	preg_match("/(.*)_(.*)/i", $modul, $next_modul);
	if ($next_modul)$next_modul = $next_modul[1];
	else $next_modul = $modul;
	$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$next_modul."_fields WHERE id = '".$id."' ";
	$result = db_mysql_query($sql, $conn);
	if (db_mysql_num_rows($result)){
		$arr = db_mysql_fetch_array($result);
		return $arr['type'];
	}
}

function check_duplicate($val, $key = 'title'){
	global $conn, $modul, $id, $action;
	$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE ".$key." = '".$val."' ";
	if ($action != 'copy'){
		if (isset($id) && $id) $sql .= "AND id != '".$id."' ";
	}
	if (isset($_POST['language']) && $_POST['language']) $sql .= "AND language = '".$_POST['language']."' ";
	$result = db_mysql_query($sql, $conn);
	if (db_mysql_num_rows($result)) return true;
	else return false;
}

function csv_header($filename){
	header("Content-Disposition: attachment; filename=".$filename."-".date('d.m.Y-H:i', time()).".CSV");
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Type: application/download");
	header("Content-Type: text/csv");
}
?>