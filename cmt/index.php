<?php
ini_set('display_errors','1');
session_start();
setlocale (LC_ALL, 'en_US');
header('Content-Type: text/html; charset=utf-8');


define('FRONTEND', dirname(dirname(__FILE__)).'/');
define('BACKEND', dirname(__FILE__).'/');
define('TABLE_PREFIX', '');
define('DEFAULT_LANGUAGE', 'de');

$_SESSION['TABLE_PREFIX'] = TABLE_PREFIX;

if (file_exists(BACKEND."/setup.php")) $_SESSION['cmt_login'] = false;

require_once BACKEND.'function/predo.inc.php';
load_cmt_scripts();

if (isset($_GET['setup']) && $_GET['setup'] == 'success'){
	if (file_exists(BACKEND."/setup.php"))unlink(BACKEND."/setup.php");
}

if (!file_exists(BACKEND."/setup.php")){
	$conn = db_mysql_connect();
	load_cmt_text();
	$login_alert = do_login();
	define_user();
}

$_POST	= parse_addslashes_array ($_POST);
$_GET	=  parse_addslashes_array ($_GET);

###################################
###################################
#####                         #####
#####     $_GET variablen     #####
#####                         #####
###################################
###################################

if (isset($_GET['modul'])) $modul = parse($_GET['modul'], 'string');
if (isset($_GET['id'])) $id = parse($_GET['id'], 'int');
if (isset($_GET['id_tree'])) $id_tree = parse($_GET['id_tree'], 'int');
if (isset($_GET['action'])) $action = parse($_GET['action'], 'string');
if (isset($_GET['delete'])) $delete = parse($_GET['delete'], 'int');
if (isset($_GET['activate'])) $activate = parse($_GET['activate'], 'int');
if (isset($_GET['deactivate'])) $deactivate = parse($_GET['deactivate'], 'int');
if (isset($_GET['order'])) $order = parse($_GET['order'], 'string');
if (isset($_GET['direction'])) $direction = parse($_GET['direction'], 'string');
if (isset($_GET['popup'])) $popup = parse($_GET['popup'], 'int');

###################################
###################################
#####                         #####
#####    $_POST variablen     #####
#####                         #####
###################################
###################################

if (isset($_POST['modul'])) $modul = parse($_POST['modul'], 'string');
if (isset($_POST['id'])) $id = parse($_POST['id'], 'int');
if (isset($_POST['id_tree'])) $id_tree = parse($_POST['id_tree'], 'int');
if (isset($_POST['action'])) $action = parse($_POST['action'], 'string');
if (isset($_POST['delete'])) $delete = parse($_POST['delete'], 'int');
if (isset($_POST['activate'])) $activate = parse($_POST['activate'], 'int');
if (isset($_POST['deactivate'])) $deactivate = parse($_POST['deactivate'], 'int');
if (isset($_POST['order'])) $order = parse($_POST['order'], 'string');
if (isset($_POST['direction'])) $direction = parse($_POST['direction'], 'string');
if (isset($_POST['popup'])) $popup = parse($_POST['popup'], 'int');

if (!isset($modul)) $modul = 'content';
if (!$modul OR $modul=='index') $modul = 'content';
if (isset($modul)) $_SESSION['modul'] = $modul;
if (!isset($direction)) $direction = "ASC";

require_once BACKEND.'validate_post.php';

require_once BACKEND.'default_session.php';

do_delete();
do_c_active();

if (isset($start_export) && $start_export === true){
	if (is_file(BACKEND.'export/export_'.$modul.'.php')) require_once BACKEND.'export/export_'.$modul.'.php';
	die();
}
?>
<!doctype html>
<html lang="de">
	<head>
	  <meta charset="utf-8">
	  <title>Comator</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	  <link rel="stylesheet" type="text/css" href="css/reset.css">
	  <link rel="stylesheet" type="text/css" href="css/style.css">
	  <link rel="stylesheet" type="text/css" href="css/icons.css">
	  <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
	  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic">
	</head>
	
	<body>
		<div class="wrapper">
			<header>
				<?php require_once('header.php');?>
			</header>
			<?php
				if (isset($_SESSION['cmt_login']) && $_SESSION['cmt_login'] === true && !isset($popup)){
					?>
					<nav <?php print "class='".$_SESSION['toggle_sidebar']."' ";?>>
						<div class='sidebar'>
							<?php require_once('sidebar.php');?>
						</div><!-- /.sidebar -->
					</nav>
					<?php
				}
			?>
			<main>
			<?php
				preg_match("/(.*)_(.*)/i", $modul, $path);
				if ($path){
					if ($path[1] == 'cmt') $path = $path[2];
					else $path = $path[1];
				}	else $path = $modul;
				if (file_exists(BACKEND."/setup.php")){
					include('setup.php');
				} elseif (isset($_SESSION['cmt_login']) && $_SESSION['cmt_login'] === true){
					if(isset($popup) && $popup == '1'){
						if (!isset($sort_order) OR $action == 'copy'){
							$sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$_SESSION['TABLE_PREFIX'].$modul."' AND COLUMN_NAME = 'sort_order' ";
							$result = db_mysql_query($sql, $conn);
							$field = db_mysql_num_rows($result);
							if ($field){
								$sql = "SELECT sort_order FROM ".$_SESSION['TABLE_PREFIX'].$modul." ORDER BY sort_order DESC LIMIT 1";
								$result = db_mysql_query($sql,$conn);
								if (db_mysql_num_rows($result)){
									foreach (db_mysql_fetch_array($result) AS $key => $val){
										$$key = $val+1;
									}
								} else $sort_order = 1;
							}
						}
						if (!isset($date) OR $action == 'copy') $date = time();
						include('modul/'.$path.'/engine_'.$modul.'.php');
					} else {
						include('modul/'.$path.'/'.$modul.'.php');
					}
				} else {
					include('login.php');
				}
			?>
			</main>
		</div><!-- /.wrapper -->
	  <script src="js/jquery-2.1.4.min.js"></script>
	  <script src="js/jquery-ui.min.js"></script>
	  <script src="js/function.js"></script>
	</body>
</html>