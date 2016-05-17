<?php

function new_order($id){
	global $conn, $modul;
	$sql = "SELECT sort_order FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE id = '".$id."' ";
	$result=db_mysql_query($sql,$conn);
	$arr = db_mysql_fetch_array($result);
	update_order($arr['sort_order'], $id);
}

function update_order($order, $id){
	global $conn, $modul;
	if ($id){
		$sql = "SELECT id, sort_order FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE sort_order = '".$order."' AND id != '".$id."' LIMIT 1 ";
		$result=db_mysql_query($sql,$conn);
		if (db_mysql_num_rows($result)){
			$arr = db_mysql_fetch_array($result);
			$order++;
			$sql_upd = "UPDATE ".$_SESSION['TABLE_PREFIX'].$modul." SET sort_order = '".$order."' WHERE id = '".$arr['id']."' ";
			db_mysql_query($sql_upd,$conn);
			update_order($order, $arr['id']);
		}
	}
}

?>