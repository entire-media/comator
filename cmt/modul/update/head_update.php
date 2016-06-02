<?php
print "
<ul class='tabs'>
	<li class='tab ";
	if ($modul == 'update') print 'active';
	print "'>
		<a href='?modul=".$path."'><i class='icon-entry'></i>".v('CMT_TAB_UPDATE')."</a>
	</li><!-- /.tab -->
</ul><!-- /.tabs -->";

?>