<?php
preg_match("/(.*)_(.*)/i", $modul, $next_modul);
if ($next_modul)$next_modul = $next_modul[2];
else $next_modul = $modul;
print "
<ul class='tabs'>";
$sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$_SESSION['TABLE_PREFIX']."labels' AND COLUMN_NAME = 'id' ";
$result = db_mysql_query($sql, $conn);
if (db_mysql_num_rows($result)){
	print "
	<li class='tab ";
	if ($modul == 'labels') print 'active';
	print "'>
		<a href='?modul=".$next_modul."'><i class='icon-entry'></i>".v('CMT_TAB_LABELS')."</a>
	</li><!-- /.tab -->";
}
print "
	<li class='tab ";
	if ($modul == 'cmt_labels') print 'active';
	print "'>
		<a href='?modul=cmt_".$next_modul."'><i class='icon-entry'></i>".v('CMT_TAB_CMT_LABELS')."</a>
	</li><!-- /.tab -->
</ul><!-- /.tabs -->";

print "<div class='filter_box'></div>";


?>