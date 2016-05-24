<?php

if (isset($id)){
	$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE id = '".$id."' ";
	$result=db_mysql_query($sql,$conn);
	if (db_mysql_num_rows($result)){
		foreach (db_mysql_fetch_array($result) AS $key => $val){
			$$key = $val;
		}
	}
}

if (isset($_POST['cmt_save'])) {
	
	if ($_POST['cmt_save'] != 'reload'){
		if (!isset($_POST['password']) OR !$_POST['password']) $alert['password'] = 'error';
		if (strlen($_POST['password']) < 8) $alert['password'] = 'error';
		
		if (isset($_POST['password'])){
			if (isset($password) && $_POST['password'] == $password) unset($_POST['password']);
			else {
				$_POST['password_salt'] = md5(time());
				$_POST['password'] = md5($_POST['password_salt'].$_POST['password']);
			}
		}
		###### Aktualisieren oder erstellern ######
		unset($_POST['cmt_save'], $_POST['update_parent'], $_POST['id'], $_POST['levels']);
		
		$_POST['c_active'] = (isset($_POST['c_active'])) ? 1 : 0;
		if (!isset($_POST['username']) OR !$_POST['username']) $alert['username'] = 'error';
		if (check_duplicate($_POST['username'], 'username') === true) $alert['username'] = array('type' => 'error', 'label' => 'DUPLICATE');
		if (check_duplicate($_POST['email'], 'email') === true) $alert['email'] = array('type' => 'error', 'label' => 'DUPLICATE');
		
		$update_parent = true;
		if (!isset($alert)){
			if (isset($action) && $action == 'edit'){
				$num = count($_POST);
				$i = 0;
				$sql = "UPDATE ".$_SESSION['TABLE_PREFIX'].$modul." SET ";
				foreach ($_POST AS $key => $val){
					$i++;
					$sql.= $key." = '".$val."'";
					if ($i != $num) $sql.=", ";
				}
		    $sql.=" WHERE id='".$id."'";
		    db_mysql_query($sql,$conn);
			} else if (isset($action) && ($action == 'copy' OR $action == 'add')){
				$num = count($_POST);
				$i = 0;
				$sql = "INSERT INTO ".$_SESSION['TABLE_PREFIX'].$modul." (";
				foreach ($_POST AS $key => $val){
					$i++;
					$sql.= $key;
					if ($i != $num) $sql.=", ";
				}
		    $sql.=") VALUES (";
				$i = 0;
				foreach ($_POST AS $key => $val){
					$i++;
					$sql.= "'".$val."'";
					if ($i != $num) $sql.=", ";
				}
		    $sql.=")";
		    db_mysql_query($sql,$conn);
		    if ($action != 'add') $id = db_last_id($conn);
			}
			
			###### Inhalt aus datenbank auslesen wenn ID gesetzt und Datenaktualisiert wurden ######
			if (isset($id)){
				$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE id = '".$id."' ";
				$result=db_mysql_query($sql,$conn);
				if (db_mysql_num_rows($result)){
					foreach (db_mysql_fetch_array($result) AS $key => $val){
						$$key = $val;
					}
				}
			}
			
			$success = true;
		} else {
			foreach ($_POST AS $key => $val){
				$$key = $val;
			}
		}
	} else {
		###### Bei Reload variablen neu befüllen #######
		foreach ($_POST AS $key => $val){
			$$key = $value;
		}
	}
}

print "
<div class='popup'>
	<form action='index.php?".$_SERVER['QUERY_STRING']."' method='post'>
		<div class='popup-head'>
			<i class='icon-popup'></i>".v('CMT_HEADLINE_'.strtoupper($action));
if (isset($id)) print "<span># ".$id."</span>";
print "
		</div><!-- /.popup-head -->
		<div class='popup-content'>";

if (isset($alert)) print print_alert('error', v('CMT_HEADLINE_SAVE_ERROR'), v('CMT_TEXT_SAVE_ERROR'));
if (isset($success)) print print_alert('success', v('CMT_HEADLINE_SAVE_SUCCESS'), v('CMT_TEXT_SAVE_SUCCESS'));

if (isset($update_parent)){
	$formdata['update_parent'] = array (
		'type'			=>	'hidden',
		'value'		=>	1
	);
}

$formdata['date'] = array	(
	'type'					=>	'datepicker',
	'label'					=>	v('CMT_DATE'),
	'parent_class'	=>	'date',
	'value'					=>	array(
		'date'						=>	date('d.m.Y', $date),
		'time'						=>	date('H:i', $date)
	)
);

$formdata['username'] = array	(
	'type'					=>	'text',
	'label'					=>	v('CMT_USERNAME'),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

$formdata['password'] = array	(
	'type'					=>	'password',
	'label'					=>	v('CMT_PASSWORD'),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

$formdata['first_name'] = array	(
	'type'					=>	'text',
	'label'					=>	v('CMT_FIRST_NAME'),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

$formdata['last_name'] = array	(
	'type'					=>	'text',
	'label'					=>	v('CMT_LAST_NAME'),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

$formdata['street'] = array	(
	'type'					=>	'text',
	'label'					=>	v('CMT_STREET_STREET_NUMBER'),
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
	'label'					=>	v('CMT_ZIP_CITY'),
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
	'label'					=>	v('CMT_COUNTRY'),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

$formdata['email'] = array	(
	'type'					=>	'text',
	'label'					=>	v('CMT_EMAIL'),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

$formdata['c_admin'] = array	(
	'type'						=>	'checkbox',
	'label'						=>  v('CMT_ROLE'),
	'parent_class'		=>	'multi_checkbox',
	'settings'				=>	array(
		'data'					=>	array('1' => v('CMT_ADMIN'))
	)
);

$formdata['c_moderator'] = array	(
	'type'						=>	'checkbox',
	'parent_class'		=>	'multi_checkbox',
	'settings'				=>	array(
		'data'					=>	array('1' => v('CMT_MODERATOR'))
	)
);

$formdata['c_editor'] = array	(
	'type'						=>	'checkbox',
	'parent_class'		=>	'multi_checkbox',
	'settings'				=>	array(
		'data'					=>	array('1' => v('CMT_EDITOR'))
	)
);

$formdata['c_active'] = array	(
	'type'						=>	'checkbox',
	'label'						=>	v('CMT_ACTIVE')
);

foreach ($formdata AS $key => $val){
	if ($formdata[$key]['type'] == 'checkbox' && !isset($formdata[$key]['settings']['data'])) $formdata[$key]['settings']['data'] = array('1' => v('CMT_YES'));
	if ($val['type'] != 'datepicker'){
		if (isset(${$key})) $formdata[$key]['value'] = $$key;
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
}

print_form ($formdata);

print "
			<div class='popup-title'>
				<h3>&nbsp</h3>
			</div><!-- /.popup-title -->
			<div class='form_submit'>
				<button type='submit' name='cmt_save' id='submit-form' value='".v('CMT_BUTTON_SAVE')."'><i class='icon-save'></i>".v('CMT_BUTTON_SAVE')."</button>
			</div>
		</form>
	</div><!-- /.popup-content -->
</div><!-- /.popup -->";
?>