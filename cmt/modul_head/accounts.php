<?php
preg_match("/(.*)_(.*)/i", $modul, $next_modul);
if ($next_modul)$next_modul = $next_modul[2];
else $next_modul = $modul;
print "
<ul class='tabs'>
	<li class='tab ";
	if ($modul == 'cmt_accounts') print 'active';
	print "'>
		<a href='?modul=cmt_".$next_modul."'><i class='icon-entry'></i>".v('CMT_TAB_CMT_ACCOUNTS')."</a>
	</li><!-- /.tab -->
</ul><!-- /.tabs -->";

print "<div class='filter_box'></div>";


?>