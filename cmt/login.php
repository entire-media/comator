<?php
print "<div class='login'>";
print "<form action='index.php?".$_SERVER['QUERY_STRING']."' method='post'>
				<div class='login-head'>
					<i class='icon-login'></i>".v('CMT_HEADLINE_LOGIN')."
				</div><!-- /.login-head -->
				<div class='login-content'>";

if (isset($login_alert)) print $login_alert;

$formdata['login_username']	=	array	(
	'type' 					=>	'text',
	'label'					=>	v('CMT_USERNAME'),
	'settings'			=>	array(
		'edit_label'	=>	true,
		'attributes'	=>	array(
			'autofocus'	=>	'autofocus'
		)
	)
);
	
$formdata['login_password']	=	array (
	'type'					=> 'password',
	'label'					=>  v('CMT_PASSWORD')
);

print_form ($formdata);

print "
	<div class='login-title'>
		<h3>&nbsp</h3>
	</div><!-- /.login-title -->
	<div class='form_submit'>
		<button type='submit' name='cmt_login' value='".v('CMT_BUTTON_LOGIN')."'><i class='icon-login'></i>".v('CMT_BUTTON_LOGIN')."</button>
	</div>";
print "
		</form>
	</div><!-- /.login-content -->
</div><!-- /.login -->";
?>