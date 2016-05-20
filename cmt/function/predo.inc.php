<?php

#########################################################
#########################################################
#####                                               #####
#####     PREDO                                     #####
#####                                               #####
#########################################################
#########################################################

##### LOAD FRONTEND AND BACKEND FUNCTIONS #####
function load_cmt_scripts(){
	require_once FRONTEND.'function/log.inc.php';
	require_once FRONTEND.'function/db.inc.php';
	require_once FRONTEND.'function/parse.inc.php';
	require_once FRONTEND.'function/form.inc.php';
	
	require_once BACKEND.'function/alert.inc.php';
	require_once BACKEND.'function/edit.inc.php';
	require_once BACKEND.'function/tree.inc.php';
	require_once BACKEND.'function/helper.inc.php';
	require_once BACKEND.'function/table.inc.php';
	
	require_once BACKEND.'function/sort_order.inc.php';
	require_once BACKEND.'function/chart.inc.php';
}

##### LOGIN USER #####
function do_login(){
	global $conn;
	$login_error = false;
	
	if(isset($_GET['logout'])) {
		$logout=parse($_GET['logout'],'int');
		if ($logout == 1){
			do_logout();
		}
	}
	
	if (isset($_POST['cmt_login'])) {
		$username = parse($_POST['login_username'], 'string');
		$password = parse($_POST['login_password'], 'string');
		$sql = "SELECT password, password_salt, id FROM ".$_SESSION['TABLE_PREFIX']."cmt_accounts WHERE username = '".$username."' AND c_active = '1'";
		$result=db_mysql_query($sql,$conn);
		if (db_mysql_num_rows($result)){
			if (!$username) $login_error = true;
			if (!$password) $login_error = true;
			$arr=db_mysql_fetch_array($result);
			
			if ($arr['password'] == md5($arr['password_salt'].$password)){
				$_SESSION['cmt_login'] = true;
				$_SESSION['cmt_id'] = $arr['id'];
			} else $login_error = true;
			
			if($login_error){
				$_SESSION['cmt_login'] = false;
				return print_alert('error', v('CMT_HEADLINE_LOGIN_ERROR'), v('CMT_TEXT_LOGIN_ERROR'));
			}
		} else {
			$_SESSION['cmt_login'] = false;
			return print_alert('error', v('CMT_HEADLINE_LOGIN_ERROR'), v('CMT_TEXT_LOGIN_ERROR'));
		}
	}
}

##### LOGOUT USER #####
function do_logout(){
	$_SESSION['cmt_login']=false;
	session_unset(); 
	session_destroy();
	unset($_SESSION);
	session_regenerate_id();
	header('location: index.php');
	ob_end_flush();
}

##### DEFINE USER VARS #####
function define_user(){
	global $conn;
	if (isset($_SESSION['cmt_login']) && $_SESSION['cmt_login'] === true){
		$sql = "SELECT * FROM ".$_SESSION['TABLE_PREFIX']."cmt_accounts WHERE id = '".$_SESSION['cmt_id']."' AND c_active='1'";
		$result=db_mysql_query($sql,$conn);
		if (db_mysql_num_rows($result)){
			$arr=db_mysql_fetch_array($result);
			define('CMT_USER_ID', $arr['id']);
			define('CMT_USER_FIRST_NAME', $arr['first_name']);
			define('CMT_USER_LAST_NAME', $arr['last_name']);
			define('CMT_USER_LANGUAGE', $arr['language']);
		} else $_SESSION['cmt_login'] = false;
	}
	if (!defined('CMT_USER_LANGUAGE')) define('CMT_USER_LANGUAGE', 'en');
}

##### DEFINE LANGUAGES #####
function load_cmt_text(){
	global $conn;
	if (isset($_SESSION['cmt_login'])){
		$sql="SELECT title, content FROM ".$_SESSION['TABLE_PREFIX']."cmt_labels WHERE language = '".v('CMT_USER_LANGUAGE')."' AND c_active = '1'";
		$result=db_mysql_query($sql,$conn);
		if (db_mysql_num_rows($result)){
			while($arr=db_mysql_fetch_array($result)){
			  if (!defined('CMT_'.$arr['title'])) define('CMT_'.$arr['title'],$arr['content'],true);
			}
		}
	}
	if (v('CMT_USER_LANGUAGE') != DEFAULT_LANGUAGE OR !isset($_SESSION['cmt_login'])){
		$sql="SELECT title, content FROM ".$_SESSION['TABLE_PREFIX']."cmt_labels WHERE language = '".DEFAULT_LANGUAGE."' AND c_active = '1'";
		$result=db_mysql_query($sql,$conn);
		if (db_mysql_num_rows($result)){
			while($arr=db_mysql_fetch_array($result)){
			  if (!defined('CMT_'.$arr['title'])) define('CMT_'.$arr['title'],$arr['content'],true);
			}
		}
	}
}

function v($label){
	if (defined($label)) return constant($label);
	else {
		return $label;
	}
}

##### Inhalte in Tabelle ausgeben #####
function init($data, $para="default", $table = NULL){
	global $conn, $modul;
	switch ($para){
		case "add":
			$return_data = init($data, $table);
			break;
		case "number":
			$return_data = number_format($data, 0, ",", ".");
			break;
		case "int":
			$return_data = $data;
			break;
		case "text":
			$return_data = $data;
			break;
		case "activate":
			$return_data = $data;
			break;
		case "duration":
			$hour = floor($data / 3600);
			$min = floor(($data- ($hour * 3600)) / 60);
			$sec = $data - ($hour * 3600) - ($min * 60);
			if ($hour < 10) $hour = "0".$hour;
			if ($min < 10) $min = "0".$min;
			if ($sec < 10) $sec = "0".$sec;
			$return_data = $hour.":".$min.":".$sec;
			break;
		case "datefromto":
			$data = explode('-', $data);
			$return_data = date('d.m.Y - H:i', $data[0])." ".v('CMT_TO')." ".date('d.m.Y - H:i', $data[1]);
			break;
		case "date":
			$return_data = date('d.m.Y - H:i', $data);
			break;
		case "select":
			preg_match("/(.*)_(.*)/i", $modul, $next_modul);
			if ($next_modul)$next_modul = $next_modul[1];
			else $next_modul = $modul;
			if ($table == 'id_field' OR $table == 'id_parent'){
				if ($table == 'id_field') $table = $next_modul."_fields";
				if ($table == 'id_parent') $table = $modul;
				$sql = "SELECT title FROM ".$_SESSION['TABLE_PREFIX'].$table." WHERE id='".$data."' LIMIT 1";
				$result=db_mysql_query($sql,$conn);
				if (db_mysql_num_rows($result)){
					$arr=db_mysql_fetch_array($result);
					$return_data = $arr['title'];
				} else $return_data = NULL;
			} else {				
				$sql = "SELECT source_table, label FROM ".$_SESSION['TABLE_PREFIX'].$next_modul."_fields WHERE title='".$table."' LIMIT 1";
				$result=db_mysql_query($sql,$conn);
				if (db_mysql_num_rows($result)){
					$arr=db_mysql_fetch_array($result);
					if (!$arr['label']) $arr['label'] = 'title';
					$sql_tmp = "SELECT ".$arr['label']." FROM ".$_SESSION['TABLE_PREFIX'].$arr['source_table']." WHERE id='".$data."' LIMIT 1";
					$result_tmp=db_mysql_query($sql_tmp,$conn);
					if (db_mysql_num_rows($result_tmp)){
						$arr_tmp=db_mysql_fetch_array($result_tmp);
						$return_data = $arr_tmp[$arr['label']];
					} else $return_data = NULL;
				} else $return_data = NULL;
			}
			break;
		case "textarea":
			$return_data = $data;
			break;
		case "default":
		default:
			if (is_numeric($data)) $return_data = init($data, 'number');
			else $return_data = $data;
			break;
	}
	return $return_data;
}

function alter_type($type, $value = NULL){
	switch($type){
		case 'text':
			$type = 'VARCHAR (255)';
			break;
		case 'multicheckbox':
		case 'textarea':
			$type = 'TEXT NOT NULL';
			break;
		case 'checkbox':
			$type = 'ENUM ("0","1")';
			break;
		case 'select':
			if ($value) {
				if ($value == 'id') $type = 'INT (11)';
				else  $type = 'VARCHAR (255)';
			} else {
				$type = 'INT (11)';
			}
			break;
		case 'datepicker':
		case 'int':
		case 'default':
		default:
			$type = 'INT (11)';
			break;
	}
	return $type;
}

?>