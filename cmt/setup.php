<?php
if (!isset($step)) {
	if (!is_dir(FRONTEND."/logs")) mkdir (FRONTEND."/logs", 0700);
	$step = 1;
	unset($_SESSION['structure_install'], $_SESSION['data_install'], $_SESSION['structure_modul_install'], $_SESSION['data_modul_install']);
}

if (isset($_POST['cmt_next'])) {
	unset($_POST['cmt_next']);
	if (isset($_GET['step']) && $_GET['step'] == 1) {
		if (!isset($_POST['db_host']) OR !$_POST['db_host']) $alert['db_host'] = array('type' => 'error', 'label' => 'Pflichtfeld', 'text' => 'Das Feld ist ein Pflichtfeld.');
		if (!isset($_POST['db_user']) OR !$_POST['db_user']) $alert['db_user'] = array('type' => 'error', 'label' => 'Pflichtfeld', 'text' => 'Das Feld ist ein Pflichtfeld.');
		if (!isset($_POST['db_password']) OR !$_POST['db_password']) $alert['db_password'] = array('type' => 'error', 'label' => 'Pflichtfeld', 'text' => 'Das Feld ist ein Pflichtfeld.');
		if (!isset($_POST['db_dbname']) OR !$_POST['db_dbname']) $alert['db_dbname'] = array('type' => 'error', 'label' => 'Pflichtfeld', 'text' => 'Das Feld ist ein Pflichtfeld.');
		if (!isset($alert)){
			$config_inc = fopen(FRONTEND.'config.inc.php', "w");
			$config_ini = ";<?php return; ?>\n";
			$config_ini .= "[CONN]\n";
			$config_ini .= "conn[driver] = mysql\n";
			$config_ini .= "conn[host] = ".$_POST['db_host']."\n";
			$config_ini .= "conn[user] = ".$_POST['db_user']."\n";
			$config_ini .= "conn[password] = ".$_POST['db_password']."\n";
			$config_ini .= "conn[dbname] = ".$_POST['db_dbname']."\n";
			fwrite($config_inc, $config_ini);
			fclose($config_inc);
			if (db_mysql_connect()){
				$step = 2;
				$success = true;
			} else {
				$alert = true;
				unlink(FRONTEND.'config.inc.php');
			}
		} else {
			foreach ($_POST AS $key => $val){
				$$key = $val;
			}
		}
	} elseif ($_GET['step'] == 2){
		if (!isset($_POST['username']) OR !$_POST['username']) $alert['username'] = array('type' => 'error', 'label' => 'Pflichtfeld', 'text' => 'Das Feld ist ein Pflichtfeld.');
		if (!isset($_POST['password']) OR !$_POST['password']) $alert['password'] = array('type' => 'error', 'label' => 'Pflichtfeld', 'text' => 'Das Feld ist ein Pflichtfeld.');
		if (strlen($_POST['password']) < 8) $alert['password'] = array('type' => 'error', 'label' => 'Zu Kurz', 'text' => 'Mindestens 8 Zeichen.');
		
		if (isset($_POST['password'])){
			if (isset($password) && $_POST['password'] == $password) unset($_POST['password']);
			else {
				$_POST['password_salt'] = md5(time());
				$_POST['password'] = md5($_POST['password_salt'].$_POST['password']);
			}
		}
		if (!isset($alert)){
			$conn = db_mysql_connect();
			if (!isset($_SESSION['structure_install'])) $_SESSION['structure_install'] = true;
			else $_SESSION['structure_install'] = false;
			if (!isset($_SESSION['data_install'])) $_SESSION['data_install'] = true;
			else $_SESSION['data_install'] = false;
			
			$structure_install = $_SESSION['structure_install'];
			$data_install = $_SESSION['data_install'];
			
			$_POST['date'] = time();
			$_POST['language'] = 'de';
			$_POST['c_admin'] = 1;
			$_POST['c_moderator'] = 1;
			$_POST['c_editor'] = 1;
			$_POST['c_active'] = 1;
			
			include(BACKEND."/setup/cmt_accounts.php");
			include(BACKEND."/setup/cmt_labels.php");
			include(BACKEND."/setup/languages.php");
			$step = 3;
			$success = true;
		} else {
			foreach ($_POST AS $key => $val){
				$$key = $val;
			}
		}
	} elseif ($_GET['step'] == 3){
		$_POST['modul_content'] = 1;
		$_POST['modul_label'] = 1;
		$_POST['modul_content'] = (isset($_POST['modul_content'])) ? 1 : 0;
		$_POST['modul_label'] = (isset($_POST['modul_label'])) ? 1 : 0;
		
		if (!isset($alert)){
			$conn = db_mysql_connect();
			if (!isset($_SESSION['structure_modul_install'])) $_SESSION['structure_modul_install'] = true;
			else $_SESSION['structure_modul_install'] = false;
			if (!isset($_SESSION['data_modul_install'])) $_SESSION['data_modul_install'] = true;
			else $_SESSION['data_modul_install'] = false;
			
			$structure_install = $_SESSION['structure_modul_install'];
			$data_install = $_SESSION['data_modul_install'];
			
			if (isset($_POST['modul_content']) && $_POST['modul_content'] == 1){
				include(BACKEND."/setup/content.php");
				include(BACKEND."/setup/content_fields.php");
				include(BACKEND."/setup/content_templates.php");
				include(BACKEND."/setup/content_tree.php");
			}
			if (isset($_POST['modul_label']) && $_POST['modul_label'] == 1){
				include(BACKEND."/setup/labels.php");
			}
			
			$success = true;
			header('location: index.php?setup=success');
		} else {
			foreach ($_POST AS $key => $val){
				$$key = $val;
			}
		}
	}
}
print "<div class='setup'>";
print "<form action='index.php?step=".$step."' method='post'>
				<div class='setup-head'>
					<i class='icon-setup'></i>SETUP
				</div><!-- /.setup-head -->
				<div class='setup-content'>";

if (isset($alert)) print print_alert('error', 'Speichervorgang fehlgeschlagen', 'Der Eintrag wurde nicht gespeichert.');
if (isset($success)) print print_alert('success', 'Speichervorgang erfolgreich', 'Der Eintrag wurde erfolgreich gespeichert.');

if ($step == 1){
	$formdata['db_dbname'] = array (
		'type'					=>	'text',
		'label'					=>	'Datenbankname',
		'label_after'		=>	'(Name der Datenbank)',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		),
		'settings'			=>	array(
			'attributes'	=>	array(
				'autofocus'	=>	'autofocus'
			)
		)
	);
	
	$formdata['db_user'] = array (
		'type'					=>	'text',
		'label'					=>	'Benutzername',
		'label_after'		=>	'(MySQL Benutzername)',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	
	$formdata['db_password'] = array (
		'type'					=>	'password',
		'label'					=>	'Passwort',
		'label_after'		=>	'(MySQL Passwort)',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	
	$formdata['db_host'] = array (
		'type'					=>	'text',
		'label'					=>	'Datenbank Host',
		'label_after'		=>	'(z.B. localhost oder IP/Domain)',
		'value'					=>	'localhost',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
} elseif ($step == 2){
	
	$formdata['username'] = array	(
		'type'					=>	'text',
		'label'					=>	'Benutzername',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	
	$formdata['password'] = array	(
		'type'					=>	'password',
		'label'					=>	'Passwort',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	
	$formdata['first_name'] = array	(
		'type'					=>	'text',
		'label'					=>	'Vorname',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	
	$formdata['last_name'] = array	(
		'type'					=>	'text',
		'label'					=>	'Nachname',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	
	$formdata['street'] = array	(
		'type'					=>	'text',
		'label'					=>	'Stra&szlig;e/Hausnummer',
		'parent_class'	=> 'free_space',
		'style'					=>	array(
			'width'				=>	'292'
		)
	);
	
	$formdata['street_number'] = array	(
		'type'					=>	'text',
		'style'					=>	array(
			'class'				=>	"align-right",
			'width'				=>	'56'
		)
	);
	
	$formdata['zip'] = array	(
		'type'					=>	'text',
		'label'					=>	'Postleitzahl/Ort',
		'parent_class'	=> 'free_space',
		'style'					=>	array(
			'width'				=>	'196'
		)
	);
	
	$formdata['city'] = array	(
		'type'					=>	'text',
		'style'					=>	array(
			'width'				=>	'152'
		)
	);
	
	$formdata['country'] = array	(
		'type'					=>	'text',
		'label'					=>	'Land',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	
	$formdata['email'] = array	(
		'type'					=>	'text',
		'label'					=>	'E-Mail-Adresse',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);

} elseif ($step == 3){

	$formdata['modul_label'] = array	(
		'type'						=>	'checkbox',
		'label'						=>  'Label Modul',
		'value'						=>	1,
		'settings'				=>	array(
			'readonly'			=>	true,
			'data'					=>	array('1' => 'ja')
		)
	);

	$formdata['modul_content'] = array	(
		'type'						=>	'checkbox',
		'label'						=>  'Content Modul',
		'value'						=>	1,
		'settings'				=>	array(
			'readonly'			=>	true,
			'data'					=>	array('1' => 'ja')
		)
	);
}

foreach ($formdata AS $key => $val){
	if (isset($alert[$key])) {
		if (is_array($alert[$key])){
			$formdata[$key]['settings']['alert']['type'] = $alert[$key]['type'];
			$formdata[$key]['settings']['alert']['label'] = $alert[$key]['label'];
			if (isset($alert[$key]['text'])) $formdata[$key]['settings']['alert']['text'] = $alert[$key]['text'];
		} else {
			$formdata[$key]['settings']['alert'] = $alert[$key];
		}
	}
}

print_form ($formdata);

print "
	<div class='setup-title'>
		<h3>&nbsp</h3>
	</div><!-- /.setup-title -->
	<div class='form_submit'>
		<button type='submit' name='cmt_next' value='Weiter'><i class='icon-login'></i>Weiter</button>
	</div>";
print "
		</form>
	</div><!-- /.setup-content -->
</div><!-- /.setup -->";
?>