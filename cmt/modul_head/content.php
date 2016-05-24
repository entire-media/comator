<?php
print "
<ul class='tabs'>
	<li class='tab ";
	if ($modul == 'content') print 'active';
	print "'>
		<a href='?modul=".$path."'><i class='icon-entry'></i>".v('CMT_TAB_CONTENT')."</a>
	</li><!-- /.tab -->
	<li class='tab ";
	if ($modul == 'content_tree') print 'active';
	print "'>
		<a href='?modul=".$path."_tree'><i class='icon-nav'></i>".v('CMT_TAB_TREE')."</a>
	</li><!-- /.tab -->
	<li class='tab ";
	if ($modul == 'content_templates') print 'active';
	print "'>
		<a href='?modul=".$path."_templates'><i class='icon-templates'></i>".v('CMT_TAB_TEMPLATES')."</a>
	</li><!-- /.tab -->
	<li class='tab ";
	if ($modul == 'content_fields') print 'active';
	print "'>
		<a href='?modul=".$path."_fields'><i class='icon-fields'></i>".v('CMT_TAB_FIELDS')."</a>
	</li><!-- /.tab -->
</ul><!-- /.tabs -->";

print "<div class='filter_box'></div>";
?>