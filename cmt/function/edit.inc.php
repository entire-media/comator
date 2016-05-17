<?php

#########################################################
#########################################################
#####                                               #####
#####    	DEFAULT EDIT                              #####
#####                                               #####
#########################################################
#########################################################

function do_c_active(){
	global $activate, $deactivate, $action, $conn, $modul;
	if (isset($action)){
		if ($activate){
			$sql = "UPDATE ".$_SESSION['TABLE_PREFIX'].$modul." SET ".$action." = '1' WHERE id = '".$activate."' ";
		}
		if ($deactivate){
			$sql = "UPDATE ".$_SESSION['TABLE_PREFIX'].$modul." SET ".$action." = '0' WHERE id = '".$deactivate."' ";
		}
	} else {
		if ($activate){
			$sql = "UPDATE ".$_SESSION['TABLE_PREFIX'].$modul." SET c_active = '1' WHERE id = '".$activate."' ";
		}
		if ($deactivate){
			$sql = "UPDATE ".$_SESSION['TABLE_PREFIX'].$modul." SET c_active = '0' WHERE id = '".$deactivate."' ";
		}
	}
	if (isset($sql)) db_mysql_query($sql,$conn);
}

function do_delete(){
	global $delete, $conn, $modul;
	if ($delete){
		preg_match("/(.*)_(.*)/i", $modul, $alter_modul);
		if (isset($alter_modul[2])){
			if ($alter_modul[2] == 'fields'){
				$sql = "SELECT title, c_default FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE id='".$delete."' ";
				$result=db_mysql_query($sql,$conn);
				$arr = db_mysql_fetch_array($result);
				if ($arr['c_default'] == '1') unset($delete);
				else {
					$sql = "ALTER TABLE ".$_SESSION['TABLE_PREFIX'].$alter_modul[1]." 
									DROP COLUMN ".$arr['title']." ";
					db_mysql_query($sql,$conn);
				}
			}
			if ($alter_modul[2] == 'tree'){
				$data = array();
				$sql = "SELECT id, title, id_parent, sort_order FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE c_active = '1' ORDER BY sort_order ASC ";
				$result = db_mysql_query($sql, $conn);
				while($arr=db_mysql_fetch_array($result)){
					$data[$arr['id']] = $arr;
				}
				delete_tree($alter_modul[1], build_tree($data, $delete), $delete);
				$sql = "DELETE FROM ".$_SESSION['TABLE_PREFIX'].$alter_modul[1]." WHERE id_tree = '".$delete."' ";
				db_mysql_query($sql, $conn);
			}
		}
		if (isset($delete)){
			$sql = "DELETE FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE id = '".$delete."' ";
			db_mysql_query($sql, $conn);
		}
	}
}
?>