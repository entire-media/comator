<?php

#########################################################
#########################################################
#####                                               #####
#####     VALIDATE SPECIAL FORMS                    #####
#####                                               #####
#########################################################
#########################################################


if (isset($_POST['date']) && isset($_POST['time'])){
	$_POST['date']=strtotime($_POST['date']." ".$_POST['time']);
	unset($_POST['time']);
}

if (isset($modul) && $modul == "content_fields" && isset($_POST)){
	foreach ($_POST AS $key => $val){
		preg_match("/key_source_value_(.*)/i", $key, $source_value);
		if ($source_value) {
			$_POST["source_value"][$_POST['key_source_value_'.$source_value[1]]] = $_POST['value_source_value_'.$source_value[1]];
			unset($_POST['key_source_value_'.$source_value[1]],$_POST['value_source_value_'.$source_value[1]]);
		}
	}
	if (isset($_POST["source_value"])) $_POST["source_value"] = serialize($_POST["source_value"]);
}

if (isset($_POST['levels'])){
	foreach ($_POST AS $key => $val){
		preg_match("/(.*)_level_(.*)/i", $key, $level_key);
		if ($level_key) {
			if (isset($_POST[$level_key[0]])){
				if ($_POST[$level_key[0]]) {
					$_POST[$level_key[1]] = parse($_POST[$level_key[0]], 'int');
					$_SESSION[$level_key[1]] = parse($_POST[$level_key[0]], 'int');
				}
				if ($level_key[2] == 1 && $_POST[$level_key[0]] == 0) {
					$_POST[$level_key[1]] = 0;
					$_SESSION[$level_key[1]] = 0;
				}
			}
			${$level_key[1]} =	parse($_POST[$level_key[1]], 'int');
			unset($_POST[$level_key[0]], $_SESSION[$level_key[0]]);
		}
	}
} else {
	
	foreach ($_SESSION AS $key => $val){
		preg_match("/(.*)_level_(.*)/i", $key, $level_key);
		if ($level_key) {
			${$level_key[1]} = parse($_SESSION[$level_key[1]]);
		}
	}
			
}
unset($_POST['levels']);

if (isset($_POST['from_day'])) {
	if ($_POST['from_day'] == 0) $from_date['day'] = 1;
	else $from_date['day'] = parse($_POST['from_day'], 'int');
	unset($_POST['from_day']);
}
if (isset($_POST['from_month'])) {
	if ($_POST['from_month'] == 0) $from_date['month'] = 1;
	else $from_date['month'] = parse($_POST['from_month'], 'int');
	unset($_POST['from_month']);
}
if (isset($_POST['from_year'])) {
	if ($_POST['from_year'] == 0) $from_date['year'] = 1;
	else $from_date['year'] = parse($_POST['from_year'], 'int');
	unset($_POST['from_year']);
}
if (isset($_POST['to_day'])) {
	if ($_POST['to_day'] == 0) $to_date['day'] = 1;
	else $to_date['day'] = parse($_POST['to_day'], 'int');
	unset($_POST['to_day']);
}
if (isset($_POST['to_month'])) {
	if ($_POST['to_month'] == 0) $to_date['month'] = 1;
	else $to_date['month'] = parse($_POST['to_month'], 'int');
	unset($_POST['to_month']);
}
if (isset($_POST['to_year'])) {
	if ($_POST['to_year'] == 0) $to_date['year'] = 1;
	else $to_date['year'] = parse($_POST['to_year'], 'int');
	unset($_POST['to_year']);
}

if (isset($_POST['cmt_filter'])){
	unset($_POST['cmt_filter']);
	foreach ($_POST AS $key => $value){
		$_SESSION['filter'][$modul][$key] = $value;
	}
}
if (isset($_SESSION['filter'][$modul])){
	foreach ($_SESSION['filter'][$modul] AS $key => $value){
		preg_match("/(.*)_q_(.*)/i", $key, $q_key);
		preg_match("/(.*)_activate_(.*)/i", $key, $activate_key);
		preg_match("/(.*)_option_(.*)/i", $key, $option_key);
		if ($activate_key){
			$$key = $value;
			if ($activate_key[2] == 'active') $value = 1;
			if ($activate_key[2] == 'notactive')  $value = 0;
			if (isset($data_array['FILTER']['activate'])){
				$data_array['FILTER']['activate'] .= "OR c_active = '".$value."' ";
			} else {
				$data_array['FILTER']['activate'] = "c_active = '".$value."' ";
			}
		} elseif ($option_key){
			$$key = $value;
		} elseif ($q_key) {
			$$key = $value;
			$q_string[$q_key[2]] = $q_key[2];
		} else {
			if ($key != 'q'){
				if ($value) {
					$$key = $value;
					$data_array['FILTER'][$key] = $value;
				}
			} else {
				$$key = $value;
				$q_value = $value;
			}
		}
	}
} else unset($_SESSION['filter']);

if (isset($q_string) OR isset($q_value)){
	if (isset($q_value)) $data_array['FILTER']['q'] = "id LIKE '%".$q_value."%' ";
	if (isset($q_string)){
		foreach ($q_string AS $key){
			if (isset($data_array['FILTER']['q'])) $data_array['FILTER']['q'] .= "OR ".$key." LIKE '%".$q_value."%' ";
			else $data_array['FILTER']['q'] .= $key." LIKE '%".$q_value."%' ";
		}
	}
}
?>