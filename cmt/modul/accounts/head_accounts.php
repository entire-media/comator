<?php
print "
<ul class='tabs'>
	<li class='tab ";
	if ($modul == 'cmt_accounts') print 'active';
	print "'>
		<a href='?modul=cmt_".$path."'><i class='icon-entry'></i>".v('CMT_TAB_CMT_ACCOUNTS')."</a>
	</li><!-- /.tab -->
</ul><!-- /.tabs -->";
?>