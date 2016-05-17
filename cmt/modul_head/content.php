<?php
preg_match("/(.*)_(.*)/i", $modul, $next_modul);
if ($next_modul)$next_modul = $next_modul[1];
else $next_modul = $modul;
print "
<ul class='tabs'>
	<li class='tab ";
	if ($modul == 'content') print 'active';
	print "'>
		<a href='?modul=".$next_modul."'><i class='icon-entry'></i>".v('CMT_TAB_CONTENT')."</a>
	</li><!-- /.tab -->
	<li class='tab ";
	if ($modul == 'content_tree') print 'active';
	print "'>
		<a href='?modul=".$next_modul."_tree'><i class='icon-nav'></i>".v('CMT_TAB_TREE')."</a>
	</li><!-- /.tab -->
	<li class='tab ";
	if ($modul == 'content_templates') print 'active';
	print "'>
		<a href='?modul=".$next_modul."_templates'><i class='icon-templates'></i>".v('CMT_TAB_TEMPLATES')."</a>
	</li><!-- /.tab -->
	<li class='tab ";
	if ($modul == 'content_fields') print 'active';
	print "'>
		<a href='?modul=".$next_modul."_fields'><i class='icon-fields'></i>".v('CMT_TAB_FIELDS')."</a>
	</li><!-- /.tab -->
</ul><!-- /.tabs -->";

print "<div class='filter_box'></div>";
//	<li class='tab ";
//	if ($modul == 'content_settings') print 'active';
//	print "'>
//		<a href='?modul=".$next_modul."_settings'><i class='icon-settings'></i>".v('CMT_TAB_SETTINGS')."</a>
//	</li><!-- /.tab -->


?>