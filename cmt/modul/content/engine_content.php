<?php

###### Inhalt aus datenbank auslesen wenn ID gesetzt ######
if (isset($id)){
	$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$modul." WHERE id = '".$id."' ";
	$result=db_mysql_query($sql,$conn);
	if (db_mysql_num_rows($result)){
		foreach (db_mysql_fetch_array($result) AS $key => $val){
			$$key = $val;
		}
	}
}

###### Speichern und reloaden #########
if (isset($_POST['cmt_save'])) {
	
	if ($_POST['cmt_save'] != 'reload'){
		###### Aktualisieren oder erstellern ######
		unset($_POST['cmt_save'], $_POST['update_parent'], $_POST['id'], $_POST['levels']);
		if ($_POST['id_template'] == 0) $alert['id_template'] = 'error';
		
		$sql_tmp = "SELECT id_field FROM ".$_SESSION['TABLE_PREFIX'].$modul."_templates WHERE c_active = '1' AND id_field != '0' AND id_template = '".$_POST['id_template']."' ORDER BY sort_order ASC";
		$result_tmp = db_mysql_query($sql_tmp, $conn);
		while($arr_tmp=db_mysql_fetch_array($result_tmp)){
			$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$modul."_fields WHERE c_active = '1' AND id = ".$arr_tmp['id_field']." ";
			$result = db_mysql_query($sql, $conn);
			while($arr=db_mysql_fetch_array($result)){
				if ($arr['type'] == 'checkbox') $_POST[$arr['title']] = (isset($_POST[$arr['title']])) ? 1 : 0;
				if ($arr['type'] == 'multicheckbox'){
					if ($arr['source_table']){
						if (!isset($arr['value']) OR !$arr['value']) $arr['value'] = 'id';
						if (!isset($arr['label']) OR !$arr['label']) $arr['label'] = 'title';
						$sql_sub = "SELECT ".$arr['value']." FROM ".$_SESSION['TABLE_PREFIX'].$arr['source_table']." WHERE c_active = '1' ";
						$result_sub = db_mysql_query($sql_sub, $conn);
						if (db_mysql_num_rows($result_sub)){
							while($arr_sub=db_mysql_fetch_array($result_sub)){
								$_POST[$arr['title']][$arr_sub[$arr['value']]] = (isset($_POST['c_multi_'.$arr['title'].'_'.$arr_sub[$arr['value']]])) ? 1 : 0;
								unset($_POST['c_multi_'.$arr['title'].'_'.$arr_sub[$arr['value']]]);
							}
							$_POST[$arr['title']] = serialize($_POST[$arr['title']]);
						}
					} else {
						$arr['source_value'] = unserialize($arr['source_value']);
						foreach ($arr['source_value'] AS $key => $val){
							$_POST[$arr['title']][$key] = (isset($_POST['c_multi_'.$arr['title'].'_'.$key])) ? 1 : 0;
							unset($_POST['c_multi_'.$arr['title'].'_'.$key]);
						}
						$_POST[$arr['title']] = serialize($_POST[$arr['title']]);
					}
				}
				
				if ($arr['c_required'] == 1) if (!isset($_POST[$arr['title']]) OR !$_POST[$arr['title']]) $alert[$arr['title']] = 'error';
				
				if ($arr['valid_type'] != 'none'){
					if ($arr['valid_type'] == 'numeric' && !is_numeric($_POST[$arr['title']])) $alert[$arr['title']] = array('type' => 'error', 'label' => 'NUMERIC');
					if ($arr['valid_type'] == 'min' && (strlen($_POST[$arr['title']]) < $arr['valid_value'])) $alert[$arr['title']] = array('type' => 'error', 'label' => 'MIN');
					if ($arr['valid_type'] == 'max' && (strlen($_POST[$arr['title']]) > $arr['valid_value'])) $alert[$arr['title']] =  array('type' => 'error', 'label' => 'MAX');
					if ($arr['valid_type'] == 'email') {
						require FRONTEND.'include/phpmailer/PHPMailerAutoload.php';
						if (!PHPMailer::ValidateAddress($_POST[$arr['title']])) $alert[$arr['title']] = array('type' => 'error', 'label' => 'EMAIL');
					}
					if ($arr['valid_type'] == 'url' && (!filter_var($_POST[$arr['title']], FILTER_VALIDATE_URL) === true)) $alert[$arr['title']] = array('type' => 'error', 'label' => 'URL');
					if ($arr['valid_type'] == 'regex' && !preg_match("/".$arr['valid_value']."/", $_POST[$arr['title']])) $alert[$arr['title']] = array('type' => 'error', 'label' => 'REGEX');
				}
			}
		}
		
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
		    new_order($id);
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
		    new_order(db_last_id($conn));
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

if (isset($update_parent)){
	$formdata['update_parent'] = array (
		'type'			=>	'hidden',
		'value'			=>	1
	);
}

$formdata['id_template'] = array	(
	'type'			=>	'select',
	'label'			=>	v('CMT_TEMPLATE'),
	'class'			=>	'reload_select',
	'settings'	=>	array(
		'data'		=>	'id_template'
	),
		'style'		=>	array(
			'class'	=>	"col-3_of_7"
		)
);

if (isset($id_template)) cross_table('id_template', $id_template);
else cross_table('id_template');

foreach ($formdata AS $key => $val){
	if ($val['type'] != 'datepicker'){
		if (isset(${$key})) $formdata[$key]['value'] = $$key;
		if (isset($alert[$key])) $formdata[$key]['settings']['alert'] = 'error';
	}
}

print_form ($formdata);
unset($formdata);

if (isset($id_template)){
	if ($id_template != 0){
		$sql_tmp = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$modul."_templates WHERE c_active = '1' AND id_template = '".$id_template."' ORDER BY sort_order ASC";
		$result_tmp = db_mysql_query($sql_tmp, $conn);
		while($arr_tmp=db_mysql_fetch_array($result_tmp)){
			if ($arr_tmp['id_field'] == 0){
				if (isset($id_headline) && $id_headline != $arr_tmp['id']){
					print "</div><!-- /.popup-toggle' -->";
				}
				$id_headline = $arr_tmp['id'];
				print "
					<div class='popup-title'>
						<h3>".v('CMT_'.$arr_tmp['label'])."<i class='icon-toggle ";
						if (!isset($_SESSION['toggle_filter_'.$id_headline])) print "active";
						else print "inactive";
						print "' id='".$id_headline."'></i></h3>
					</div><!-- /.popup-title -->
					<div class='popup-toggle ".$id_headline." ";
					if (!isset($_SESSION['toggle_filter_'.$id_headline])) print "show";
					else print $_SESSION['toggle_filter_'.$id_headline];
					print "' >";
			} else {
				$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX'].$modul."_fields WHERE c_active = '1' AND id = ".$arr_tmp['id_field']." ";
				$result = db_mysql_query($sql, $conn);
				while($arr=db_mysql_fetch_array($result)){
					$formdata[$arr['title']]['type'] = $arr['type'];
					if ($arr_tmp['label']) $formdata[$arr['title']]['label'] = v('CMT_'.strtoupper($arr_tmp['label']));
					if ($arr_tmp['next_row'] == 0) $formdata[$arr['title']]['parent_class'] = 'free_space';
					if ($arr_tmp['label_before']) $formdata[$arr['title']]['label_before'] = $arr_tmp['label_before'];
					if ($arr_tmp['label_head']) $formdata[$arr['title']]['label_head'] = $arr_tmp['label_head'];
					if ($arr_tmp['label_after']) {
						if ($arr['type'] == 'checkbox') $formdata[$arr['title']]['settings']['data'] = array('1' => v('CMT_'.strtoupper($arr_tmp['label_after'])));
						else $formdata[$arr['title']]['label_after'] = $arr_tmp['label_after'];
					}
					
					$field_type = get_field_type($arr_tmp['id_field']);
					if ($field_type == 'int' OR $field_type == 'text' OR $field_type == 'textarea' OR $field_type == 'select' ){
						if ($arr_tmp['align'] != 'none') $formdata[$arr['title']]['style']['class'] = "align-".$arr_tmp['align'];
						else $formdata[$arr['title']]['style']['class'] = "align-left";
						if ($arr_tmp['max_columns']) $formdata[$arr['title']]['style']['class'] .= " col-".$arr_tmp['max_columns']."_of_7";
						else $formdata[$arr['title']]['style']['class'] .= " col-6_of_7";
						if ($arr_tmp['max_width'] != 0) $formdata[$arr['title']]['style']['width'] = $arr_tmp['max_width'];
						if ($field_type == 'textarea'){
							if ($arr_tmp['max_rows'] != 0) $formdata[$arr['title']]['style']['rows'] = $arr_tmp['max_rows'];
							else $formdata[$arr['title']]['style']['rows'] = 5;
						}
					}
					if ($field_type == 'radio' OR $field_type == 'multicheckbox' OR $field_type == 'checkbox') {
						if ($arr_tmp['max_columns']) $formdata[$arr['title']]['style']['cols'] = " label-".$arr_tmp['max_columns']."_of_7";
						else $formdata[$arr['title']]['style']['cols'] = " label-2_of_7";
						if ($arr_tmp['max_width'] != 0) $formdata[$arr['title']]['style']['width'] = $arr_tmp['max_width'];
					}
					
					if ($arr['c_readonly']) $formdata[$arr['title']]['settings']['readonly'] = $arr['c_readonly'];
					if ($arr['source_table']) $formdata[$arr['title']]['settings']['data'] = $arr['source_table'];
					
					if ($arr['type'] == 'checkbox' &&!isset($formdata[$arr['title']]['settings']['data'])) $formdata[$arr['title']]['settings']['data'] = array('1' => v('CMT_YES'));
					
					if ($arr['type'] == 'multicheckbox'){
						${$arr['title']} = unserialize(${$arr['title']});
						foreach (${$arr['title']} AS $key => $val){
							${"c_multi_".$arr['title']."_".$key} = $val;
						}
						if ($arr['source_table']){
							if (!isset($arr['value']) OR !$arr['value']) $arr['value'] = 'id';
							if (!isset($arr['label']) OR !$arr['label']) $arr['label'] = 'title';
							$sql_sub = "SELECT ".$arr['value'].", ".$arr['label']." FROM ".$_SESSION['TABLE_PREFIX'].$arr['source_table']." WHERE c_active = '1' ";
							$result_sub = db_mysql_query($sql_sub, $conn);
							if (db_mysql_num_rows($result_sub)){
								$i = 1;
								while($arr_sub=db_mysql_fetch_array($result_sub)){
									$formdata['c_multi_'.$arr['title'].'_'.$arr_sub[$arr['value']]] = array	(
											'type'						=>	'checkbox',
											'parent_class'		=>	'multi_checkbox',
											'settings'				=>	array(
												'data'					=> 	array($arr_sub[$arr['value']] => $arr_sub[$arr['label']])
											)
									);
									if ($i == 1) {
										$formdata['c_multi_'.$arr['title'].'_'.$arr_sub[$arr['value']]]['label'] = $formdata[$arr['title']]['label'];
										unset($formdata[$arr['title']]);
										$i++;
									}
								}
							}
						} else {
							$arr['source_value'] = unserialize($arr['source_value']);
							$i = 1;
							foreach ($arr['source_value'] AS $key => $val){
								$formdata['c_multi_'.$arr['title'].'_'.$key] = array	(
										'type'						=>	'checkbox',
										'parent_class'		=>	'multi_checkbox',
										'settings'				=>	array(
											'data'					=> 	array($key => $val)
										)
								);
								if ($i == 1) {
									$formdata['c_multi_'.$arr['title'].'_'.$key]['label'] = $formdata[$arr['title']]['label'];
									unset($formdata[$arr['title']]);
									$i++;
								}
							}
						}
					}
					
					if ($arr['type'] == 'select' OR $arr['type'] == 'radio'){
						if ($arr['source_table']){
							if (isset(${$arr['title']})) cross_table($arr['title'], ${$arr['title']});
							else cross_table($arr['title'], 0);
						} else {
							$arr['source_value'] = unserialize($arr['source_value']);
							if ($arr['type'] != 'radio') $formdata[$arr['title']]['settings']['data'][0] = "...";
							foreach ($arr['source_value'] AS $key => $val){
								$formdata[$arr['title']]['settings']['data'][$key] = $val;
							}
						}
					}
					
					if ($arr['type'] == 'datepicker') {
						$formdata[$arr['title']]['parent_class'] = 'date';
						$formdata[$arr['title']]['value']['date'] = date('d.m.Y', $date);
						$formdata[$arr['title']]['value']['time'] = date('H:i', $date);
					}
					
					foreach ($formdata AS $key => $val){
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
					print_form($formdata);
					unset($formdata);
				}
			}
		}
		if (isset($id_headline) && $id_headline != $arr_tmp['id']){
			print "</div><!-- /.popup-toggle' -->";
		}
	}
}


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