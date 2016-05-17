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
		unset($_POST['cmt_save'], $_POST['update_parent']);
		$_POST['c_active'] = (isset($_POST['c_active'])) ? 1 : 0;
		$_POST['c_list'] = (isset($_POST['c_list'])) ? 1 : 0;
		$_POST['c_readonly'] = (isset($_POST['c_readonly'])) ? 1 : 0;
		$_POST['c_required'] = (isset($_POST['c_required'])) ? 1 : 0;
		
		if (!isset($_POST['title']) OR !$_POST['title']) $alert['title'] = 'error';
		
		$update_parent = true;
		preg_match("/(.*)_(.*)/i", $modul, $create_modul);
		if ($create_modul)$create_modul = $create_modul[1];
		else $create_modul = $modul;
		
		if (!isset($alert)){
			if (isset($action) && $action == 'edit'){
				$num = count($_POST);
				$i = 0;
				
				#### Erstellen ####
				$sql = "SELECT title, type FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE id='".$id."' ";
				$result=db_mysql_query($sql,$conn);
				$arr = db_mysql_fetch_array($result);
				if (isset($arr['value'])) $alter_type = alter_type($arr['type'], $arr['value']);
				else $alter_type = alter_type($arr['type']);
				if ($arr['type'] != $_POST['type']){
					$alter_type = alter_type($_POST['type']);
					$sql = "ALTER TABLE ".$_SESSION['TABLE_PREFIX'].$create_modul." 
									MODIFY COLUMN ".$arr['title']." ".$alter_type;
					if (!db_mysql_query($sql,$conn)) $error = true;
				}
				
				if ($arr['title'] != $_POST['title']){
					$sql = "ALTER TABLE ".$_SESSION['TABLE_PREFIX'].$create_modul." 
									CHANGE COLUMN ".$arr['title']." ".$_POST['title']." ".$alter_type;
					if (!db_mysql_query($sql,$conn)) $error = true;
				}
				if (!isset($error)){
					#### Eintragen ####
					$sql = "UPDATE ".$_SESSION['TABLE_PREFIX'].$modul." SET ";
					foreach ($_POST AS $key => $val){
						$i++;
						$sql.= $key." = '".$val."'";
						if ($i != $num) $sql.=", ";
					}
			    $sql.=" WHERE id='".$id."'";
			    db_mysql_query($sql,$conn);
		    	new_order($id);
			  }
			} else if (isset($action) && ($action == 'copy' OR $action == 'add')){
				$num = count($_POST);
				$i = 0;
				#### Erstellen ####
				if (isset($_POST['value'])) $alter_type = alter_type($_POST['type'], $_POST['value']);
				else $alter_type = alter_type($_POST['type']);
				$sql = "ALTER TABLE ".$_SESSION['TABLE_PREFIX'].$create_modul." 
								ADD COLUMN ".$_POST['title']." ".$alter_type;
		    if (db_mysql_query($sql,$conn)){
					#### Eintragen ####
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
		    	new_order(db_last_id($conn));
			    if ($action != 'add') $id = db_last_id($conn);
			  }
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
		
if (!isset($fieldset)) $fieldset = 'none';
if (!isset($valid_type)) $valid_type = 'none';

if (isset($update_parent)){
	$formdata['update_parent'] = array (
		'type'			=>	'hidden',
		'value'			=>	1
	);
}

$formdata['date'] = array	(
	'type'					=>	'datepicker',
	'label'					=>	v('CMT_DATE'),
	'parent_class'	=>	'date',
	'value'					=>	array(
		'date'				=>	date('d.m.Y', $date),
		'time'				=>	date('H:i', $date)
	)
);

$formdata['title'] = array	(
	'type'					=>	'text',
	'label'					=>	v('CMT_TITLE'),
	'style'					=>	array(
		'class'				=>	"col-3_of_7"
	)
);

$fieldset = 'none';

if ($fieldset == 'none'){

	$formdata['type'] = array	(
		'type'				=>	'select',
		'label'				=>	v('CMT_TYPE'),
		'class'				=>	'reload_select',
		'settings'		=>	array(
			'data'					=>	array(
				'int'							=>	v('CMT_TYPE_INTEGER'),
				'text'						=>	v('CMT_TYPE_TEXT'),
				'textarea'				=>	v('CMT_TYPE_TEXTAREA'),
				'datepicker'			=>	v('CMT_TYPE_DATEPICKER'),
				'checkbox'				=>	v('CMT_TYPE_CHECKBOX'),
				'multicheckbox'		=>	v('CMT_TYPE_MULTICHECKBOX'),
				'select'					=>	v('CMT_TYPE_SELECT'),
				'multiselect'			=>	v('CMT_TYPE_MULTISELECT'),
				'radio'						=>	v('CMT_TYPE_RADIO')
			)
		),
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	
	if (isset($type)){
		if ($type == 'multicheckbox' OR $type == 'select' OR $type == 'multiselect' OR $type == 'radio'){
			$formdata['value'] = array	(
				'type'			=>	'text',
				'label'			=>	v('CMT_VALUE'),
				'style'					=>	array(
					'class'				=>	"col-3_of_7"
				)
			);
			
			$formdata['label'] = array	(
				'type'			=>	'text',
				'label'			=>	v('CMT_LABEL'),
				'style'					=>	array(
					'class'				=>	"col-3_of_7"
				)
			);
			
			$formdata['source_table'] = array	(
				'type'			=>	'select',
				'label'			=>	v('CMT_DATABASE'),
				'settings'	=>	array(
					'data'		=>	array(
						NULL		=>	NULL
					),
				),
				'style'					=>	array(
					'class'				=>	"col-3_of_7"
				)
			);
			
			$sql = "SHOW TABLES";
			$result=db_mysql_query($sql,$conn);
			if (db_mysql_num_rows($result)){
				while ($arr = db_mysql_fetch_array($result)){
					foreach ($arr AS $database){
						$formdata['source_table']['settings']['data'][$database] = $database;
					}
				}
			}
	
		}
	}

	$formdata['valid_type'] = array	(
		'type'					=>	'select',
		'label'					=>	v('CMT_VALID_TYPE'),
		'class'					=>	'reload_select',
		'settings'			=>	array(
			'data'				=>	array(
				'none'			=>	v('CMT_VALID_NONE'),
				'numeric'		=>	v('CMT_VALID_NUMERIC'),
				'min'				=>	v('CMT_VALID_MIN'),
				'max'				=>	v('CMT_VALID_MAX'),
				'email'			=>	v('CMT_VALID_EMAIL'),
				'url'				=>	v('CMT_VALID_URL'),
				'regex'			=>	v('CMT_VALID_REGEX')
			)
		),
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	
	if ($valid_type != 'none' && $valid_type != 'numeric' && $valid_type != 'email' && $valid_type != 'ulr'){
		$formdata['valid_value'] = array	(
			'type'			=>	'text',
			'label'			=>	v('CMT_VALID_VALUE'),
			'style'					=>	array(
				'class'				=>	"col-3_of_7"
			)
		);
	}
}

$formdata['c_readonly'] = array (
	'type'			=>	'checkbox',
	'label'			=>	v('CMT_READONLY')
);

$formdata['c_required'] = array (
	'type'			=>	'checkbox',
	'label'			=>	v('CMT_REQUIRED')
);

$formdata['c_list'] = array	(
	'type'						=>	'checkbox',
	'label'						=>  v('CMT_DISPLAY'),
	'parent_class'		=>	'multi_checkbox',
	'settings'				=>	array(
		'data'					=>	array('1' => v('CMT_LIST'))
	)
);

$formdata['c_search'] = array	(
	'type'						=>	'checkbox',
	'parent_class'		=>	'multi_checkbox',
	'settings'				=>	array(
		'data'					=>	array('1' => v('CMT_SEARCH'))
	)
);

$formdata['sort_order'] = array	(
	'type'			=>	'text',
	'label'			=>	v('CMT_ORDER'),
	'style'			=>	array(
		'class'		=>	'align-right col-1_of_7'
	)
);

$formdata['c_active'] = array	(
	'type'			=>	'checkbox',
	'label'			=>	v('CMT_ACTIVE')
);

foreach ($formdata AS $key => $val){
	if (isset($c_default) && $c_default) $formdata[$key]['settings']['readonly'] = true;
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