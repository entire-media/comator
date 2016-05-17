<?php
preg_match("/(.*)_(.*)/i", $modul, $next_modul);
if ($next_modul)$next_modul = $next_modul[2];
else $next_modul = $modul;
print "
<ul class='tabs'>
	<li class='tab ";
	if ($modul == 'labels') print 'active';
	print "'>
		<a href='?modul=".$next_modul."'><i class='icon-entry'></i>".v('CMT_TAB_LABELS')."</a>
	</li><!-- /.tab -->
	<li class='tab ";
	if ($modul == 'cmt_labels') print 'active';
	print "'>
		<a href='?modul=cmt_".$next_modul."'><i class='icon-entry'></i>".v('CMT_TAB_CMT_LABELS')."</a>
	</li><!-- /.tab -->
</ul><!-- /.tabs -->";

print "<div class='filter_box'></div>";


?>