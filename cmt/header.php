<?php
print "
<div class='logo'>";
if(!isset($popup)) print "<a href='index.php' target='_self' title='Comator'>";
print "<img src='images/logo.png' alt='Comator'/>";
if(!isset($popup)) print "</a>";
print "</div><!-- /.logo -->";
if (isset($_SESSION['cmt_login']) && $_SESSION['cmt_login'] === true){
	if(!isset($popup)){
		print "
		<div class='logout'>
			<span>".v('CMT_LOGGED_IN')."</span>
			<strong>".v('CMT_USER_FIRST_NAME')." ".v('CMT_USER_LAST_NAME')."</strong>
			<a href='?logout=1' title='".v('CMT_LOGOUT')."'>
				<i class='icon-logout'></i>
			</a>
		</div><!-- /.logout -->";
	}
}
?>