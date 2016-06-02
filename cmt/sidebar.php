<?php
print "<ul class='sidebar-nav'>";

preg_match("/(.*)_(.*)/i", $modul, $next_modul);
if ($next_modul)$next_modul = $next_modul[1];
else $next_modul = $modul;

$sql_sb = "SELECT modul, modul_string FROM cmt_modul WHERE c_active = '1' ORDER BY sort_order";
$result_sb = db_mysql_query($sql_sb, $conn);
if (db_mysql_num_rows($result_sb)){
	while($arr_sb = db_mysql_fetch_array($result_sb)){
		if ($arr_sb['modul'] == 'content'){
			$sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$_SESSION['TABLE_PREFIX']."content_tree' AND COLUMN_NAME = 'id_parent' ";
			$result = db_mysql_query($sql, $conn);
			$id_parent = db_mysql_num_rows($result);
			if ($id_parent){
				$arr_tmp = create_tree('content_tree');
				if ($arr_tmp){
					print "
						<li class='has-dropdown'>
							<a href='?modul=content'>
								<i class='icon-content'></i><span class='toggle ";
								if ($next_modul == 'content') print 'active';
								print "'>".v('CMT_MODUL_CONTENT')."</span>
							</a>
							<ul class='dropdown sub-tree ";
							if ($next_modul == 'content') print 'active';
							print "'>";
							if (isset($id_tree)) tree_sidebar('content', $arr_tmp, tree_get_parents('content_tree', $id_tree));
							else tree_sidebar('content', $arr_tmp, tree_get_parents('content_tree', NULL));
							 
					print "
							</ul><!-- /.dropdown -->
						</li><!-- /.has-dropdown -->";
				} else {
					print "
					<li>
						<a href='?modul=content'>
							<i class='icon-content'></i><span>".v('CMT_MODUL_CONTENT')."</span>
						</a>
					</li><!-- /.has-dropdown -->";
				}
			}
		} elseif ($arr_sb['modul'] == 'update'){
			$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX']."cmt_settings WHERE title = 'update_core' AND c_active = '1' ";
			$result = db_mysql_query($sql, $conn);
			if (db_mysql_num_rows($result)){
				print "
					<li>
						<a href='?modul=update'>
							<i class='icon-update'></i><span>".v('CMT_MODUL_UPDATE')."</span>
						</a>
					</li>";
			}
		} else {
			if ($arr_sb['modul_string']) $modul_string = $arr_sb['modul']."_".$arr_sb['modul_string'];
			else $modul_string = $arr_sb['modul'];
			$sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$_SESSION['TABLE_PREFIX'].$modul_string."' AND COLUMN_NAME = 'id' ";
			$result = db_mysql_query($sql, $conn);
			if (db_mysql_num_rows($result)){
				print "<li>";
				if ($arr_sb['modul_string']) print "<a href='?modul=".$arr_sb['modul']."_".$arr_sb['modul_string']."'>";
				else print "<a href='?modul=".$arr_sb['modul']."'>";
				print "<i class='icon-".$arr_sb['modul']."'></i><span>".v('CMT_MODUL_'.strtoupper($arr_sb['modul']))."</span>";
				print "</a>";
				print "</li>";
			} else {
				if ($arr_sb['modul_string']) $modul_string = "cmt_".$arr_sb['modul']."_".$arr_sb['modul_string'];
				else $modul_string = "cmt_".$arr_sb['modul'];
				$sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$_SESSION['TABLE_PREFIX'].$modul_string."' AND COLUMN_NAME = 'id' ";
				$result = db_mysql_query($sql, $conn);
				if (db_mysql_num_rows($result)){
					print "<li>";
					if ($arr_sb['modul_string']) print "<a href='?modul=cmt_".$arr_sb['modul']."_".$arr_sb['modul_string']."'>";
					else print "<a href='?modul=cmt_".$arr_sb['modul']."'>";
					print "<i class='icon-".$arr_sb['modul']."'></i><span>".v('CMT_MODUL_'.strtoupper($arr_sb['modul']))."</span>";
					print "</a>";
					print "</li>";
				} else {
					print "<li>";
					if ($arr_sb['modul_string']) print "<a href='?modul=".$arr_sb['modul']."_".$arr_sb['modul_string']."'>";
					else print "<a href='?modul=".$arr_sb['modul']."'>";
					print "<i class='icon-".$arr_sb['modul']."'></i><span>".v('CMT_MODUL_'.strtoupper($arr_sb['modul']))."</span>";
					print "</a>";
					print "</li>";
				}
			}
		}
	}
}

print "
	<li class='toggle'>
		<a href='#' class='toggle-sidebar'>
			<i class='icon-toggle'></i>
		</a>
	</li>
</ul><!-- /.sidebar-nav -->";

?>