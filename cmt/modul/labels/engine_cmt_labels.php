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
		###### Aktualisieren oder erstellern ######
		$_POST['language'] = 'de';
		$_POST['c_active'] = (isset($_POST['c_active'])) ? 1 : 0;
		unset($_POST['cmt_save'], $_POST['update_parent'], $_POST['id'], $_POST['levels']);
		
		if (!isset($_POST['title']) OR !$_POST['title']) $alert['title'] = 'error';
		if (isset($_POST['title'])) $_POST['title'] = strtoupper(str_replace(" ", "_", $_POST['title']));
		if (check_duplicate($_POST['title']) === true) $alert['title'] = array('type' => 'error', 'label' => 'DUPLICATE');
		
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
			$$key = $val;
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

if (!isset($c_active)) $c_active = 1;
if (!isset($language)) $language = CMT_USER_LANGUAGE;
		

if (isset($update_parent)){
	$formdata['update_parent'] = array (
		'type'			=>	'hidden',
		'value'			=>	1
	);
}

$formdata['date'] = array	(
	'type'				=>	'datepicker',
	'label'				=>	v('CMT_DATE'),
	'parent_class'=>	'date',
	'value'				=>	array(
		'date'			=>	date('d.m.Y', $date),
		'time'			=>	date('H:i', $date)
	)
);

$formdata['title'] = array	(
	'type'				=>	'text',
	'label'				=>	v('CMT_TITLE'),
	'style'				=>	array(
		'class'			=>	"col-3_of_7"
	)
);

$formdata['content'] = array	(
	'type'				=>	'textarea',
	'label'				=>	v('CMT_CONTENT')
);

$formdata['c_active'] = array	(
	'type'				=>	'checkbox',
	'label'				=>	v('CMT_ACTIVE')
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