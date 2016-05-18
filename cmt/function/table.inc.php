<?php
	
function thead($params){
	global $modul, $direction, $order;
	if ($modul == 'id_template') {
		$modul_old = 'id_template';
		$modul = 'content_templates';
	}
	$thead = NULL;
	if ($direction == "ASC") $direction = "DESC";
	else $direction = "ASC";
	
	$thead .= "
	<div class='table-head ".$modul."'>
		<div class='table-row'>";
		foreach ($params['TABLE'] AS $key => $value){
			preg_match("/ICON-(.*)/i", $key, $icon_key);
			if ($icon_key){
				if ($value == 'title'){
					$thead .= "<div class='table-cell ".$value."'><a href='";
					if ($modul == 'content_tree') $thead .= "#";
					else $thead .= "index.php?modul=".$modul."&order=".$value."&direction=".$direction;
					$thead .= "' ><i class='icon-nav'></i>".v('CMT_TITLE')."</a></div><!-- /.table-cell -->";
				} elseif ($value == 'sort_order') {
					$thead .= "<div class='table-cell ".$value."'><a href='index.php?modul=".$modul."&order=".$value."&direction=".$direction."' class='icon-cell'><i class='icon-".$value."'></i><i class='icon-sort ";
					if ($value == $order) $thead .= $direction;
					else $thead .= "DESC";
					$thead .= "'></i></a></div><!-- /.table-cell -->";
				} else {
					$thead .= "<div class='table-cell icon-only ".$value."'><a href='";
					if ($modul == 'content_tree') $thead .= "#";
					else $thead .= "index.php?modul=".$modul."&order=".$value."&direction=".$direction;
					$thead .= "' ><i class='icon-".$value."'></i></a></div><!-- /.table-cell -->";
				}
			} else {
				$thead .= "<div class='table-cell ".$value."'><a href='index.php?modul=".$modul."&order=".$value."&direction=".$direction."' >".v($key)."<i class='icon-sort ";
				if ($value == $order) $thead .= $direction;
				else $thead .= "DESC";
				$thead .= "'></i></a></div><!-- /.table-cell -->";
			}		
		}
		$thead .= "
		<div class='table-cell tools'></div><!-- /.table-cell -->
		</div><!-- /.table-row -->
	</div><!-- /.table-head -->";
	
	if (isset($modul_old)) $modul = $modul_old;
	print $thead;
}

function tbody($params){
	global $conn, $modul;
	if ($modul == 'content_tree'){
		if (isset($params['FILTER'])) $arr_tmp = create_tree('content_tree', 0, $params['FILTER']);
		else $arr_tmp = create_tree('content_tree', 0);
		if ($arr_tmp){
			$tbody = "<div class='table-body tree'>";
			$tbody .= tree_nav_table($params, 'content', $arr_tmp, tree_get_parents('content_tree', NULL));
			$tbody .= "</div><!-- /.table-body -->";
		}
	} else {
		if (!isset($params['FILTER'])) $params['FILTER'] = NULL;
		elseif (!isset($params['SORT']))  $params['SORT'] = NULL;
		if (!isset($params['GROUP']))  $params['GROUP'] = NULL;
		if (!isset($params['ADD']))  $params['ADD'] = NULL;
		$result = db_mysql_query(select_tbody($params['TABLE'], $params['SORT'], $params['FILTER'], $params['GROUP'], $params['ADD']), $conn);
		
		$sql_sub = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$_SESSION['TABLE_PREFIX'].$modul."' AND COLUMN_NAME = 'sort_order' ";
		$result_sub = db_mysql_query($sql_sub, $conn);
		if ($modul == 'content_tree'){
			 $tbody = "<div class='table-body tree'>";
		} else {
			if (db_mysql_num_rows($result_sub)) $tbody = "<div class='table-body sortable'>";
			else $tbody = "<div class='table-body ".$modul."'>";
		}
		while($arr=db_mysql_fetch_array($result)){
			$tbody .= "<div class='table-row' id='".$arr['id']."' >";
			foreach ($params['TABLE'] AS $key => $value){
				$data = init($arr[$key], $value, $key);
				if ($value == 'activate'){
					$tbody .= "<div class='table-cell icon-only ".$key."'>";
					if ($arr[$key]) $tbody .= "<a href='#' class='icon-cell ".$key."_de".$value."' data-content='".$modul."' id='".$arr['id']."' ><i class='icon-".$value."-active'></i>";
					else $tbody .= "<a href='#' class='icon-cell ".$key."_".$value."' data-content='".$modul."' id='".$arr['id']."' ><i class='icon-".$value."'></i>";
					$tbody .= "</div><!-- /.table-cell -->";
				} else {
					$tbody .= "<div class='table-cell ".$key."'>".$data."</div><!-- /.table-cell -->";
				}
			}
			$width = 40+26*count(array_filter($params['CONSTRUCT']));
			$tbody .= "<div class='table-cell tools' style='width: ".$width."px;'>";
			foreach ($params['CONSTRUCT'] AS $key => $value){
				if ($value===true) {
					if ($key == 'activate'){
						if (!isset($arr['c_default']) OR $arr['c_default'] == 0){
							if ($arr['c_active']) $tbody .= "<a href='#' class='icon-cell de".$key."' data-content='".$modul."' id='".$arr['id']."' ><i class='icon-".$key."-active'></i>";
							else $tbody .= "<a href='#' class='icon-cell ".$key."' data-content='".$modul."' id='".$arr['id']."' ><i class='icon-".$key."'></i>";
							$tbody .= "</a>";
						}
					} elseif ($key == 'delete'){
						if (!isset($arr['c_default']) OR $arr['c_default'] == 0) $tbody .= "<a href='#' class='icon-cell ".$key."' data-content='".$modul."' id='".$arr['id']."' ><i class='icon-".$key."'></i></a>";
					} else {
						$tbody .= "<a href='#' class='icon-cell ".$key."' data-content='".$modul."' id='".$arr['id']."' ><i class='icon-".$key."'></i></a>";
					}
				}
			}
			$tbody .= "</div><!-- /.table-cell -->";
			$tbody .= "</div><!-- /.table-row -->";
		}
		$tbody .= "</div><!-- /.table-body -->";
	}
	if (isset($tbody)) print $tbody;
}

function select_tbody($params, $order, $filter = NULL, $group_by = NULL, $add = NULL){
	global $modul;
	$i = 0;
	$z = count($params);
	
	$sql = "SELECT c_active, ";
	if ($modul == 'content_fields') $sql .= "c_default, ";
	if (isset($add)) $sql .= $add.", ";
	foreach ($params AS $key => $value){
		$i++;
		if ($value != 'add'){
			$sql.=$key;
			if ($i != $z) $sql.=", ";
			else $sql.=" ";
		}
	}
	if (!isset($params['id'])) $sql.=", id ";
	$sql.= "FROM ".$_SESSION['TABLE_PREFIX'].$modul;
	if (isset($filter)){
		$sql.= " WHERE ";
		$i = 0;
		foreach ($filter AS $key => $value){
			if ($i > 0) $sql.= "AND ";
			if ($key == 'activate'){
				$sql.= $value;
			} elseif ($key == 'options'){
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
	if (isset($group_by)){
		$sql.= " GROUP BY ".$group_by;
	}
	if (count($order)){
		$sql.= " ORDER BY ";
		foreach ($order AS $key => $value){
			$sql.= $key." ".$value;
		}
	}
	return $sql;
}
?>