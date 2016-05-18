<?php
print "<ul class='sidebar-nav'>";


preg_match("/(.*)_(.*)/i", $modul, $next_modul);
if ($next_modul)$next_modul = $next_modul[1];
else $next_modul = $modul;

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

$sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$_SESSION['TABLE_PREFIX']."labels' AND COLUMN_NAME = 'id' ";
$result = db_mysql_query($sql, $conn);
if (db_mysql_num_rows($result)){
	print "
		<li>
			<a href='?modul=labels'>
				<i class='icon-labels'></i><span>".v('CMT_MODUL_LABELS')."</span>
			</a>
		</li>";
} else {
	print "
		<li>
			<a href='?modul=cmt_labels'>
				<i class='icon-labels'></i><span>".v('CMT_MODUL_LABELS')."</span>
			</a>
		</li>";
}

print "
	<li>
		<a href='?modul=cmt_accounts'>
			<i class='icon-accounts'></i>".v('CMT_MODUL_ACCOUNTS')."
		</a>
	</li>
	<li class='toggle'>
		<a href='#' class='toggle-sidebar'>
			<i class='icon-toggle'></i>
		</a>
	</li>
</ul><!-- /.sidebar-nav -->";

?>