<?php
setlocale (LC_ALL, 'de_DE');
header('Content-Type: text/html; charset=utf-8');
define('FRONTEND', dirname(dirname(__FILE__)).'/../');
define('BACKEND', dirname(__FILE__).'/../');
define('TABLE_PREFIX', '');
define('DEFAULT_LANGUAGE', 'de');

require_once BACKEND.'function/predo.inc.php';
load_cmt_scripts();
$conn = db_mysql_connect();
define_user();
load_cmt_text();

if (!isset($_SESSION)) { session_start();}

if (isset($_POST['toggle_sidebar'])){
	$_SESSION['toggle_sidebar'] = $_POST['toggle_sidebar'];
}

if (isset($_POST['toggle_filter'])){
	$_SESSION['toggle_filter'] = $_POST['toggle_filter'];
}

if (isset($_POST['toggle_val']) && isset($_POST['toggle_type'])){
	$_SESSION['toggle_filter_'.$_POST['toggle_type']] = $_POST['toggle_val'];
}

if (isset($_GET['update_sort_order'])){
	$i = 1;
	foreach ($_POST['sort'] AS $value){
		$sql = "UPDATE ".$_SESSION['TABLE_PREFIX'].$_SESSION['modul']." SET sort_order = '".$i."' WHERE id = '".$value."' ";
		db_mysql_query($sql, $conn);
		$i++;
	}
	
}

if (isset($_GET['form_inputadd'])){
	$i = $_GET['form_inputadd'];
	$formdata['source_value_'.$i] = array	(
		'type'			=>	'input_add',
		'label'			=>	v('CMT_DATA'),
		'class' 		=> 'source_input',
		'style'					=>	array(
			'class'				=>	"col-3_of_7"
		)
	);
	print_form ($formdata);
	unset($formdata);
}
?>