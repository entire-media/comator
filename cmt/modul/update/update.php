<?php

$sql = "SELECT title, value FROM ".$_SESSION['TABLE_PREFIX']."cmt_settings WHERE title = 'version_core' OR title = 'update_core'";
$result=db_mysql_query($sql,$conn);
if (db_mysql_num_rows($result)){
	while($arr = db_mysql_fetch_array($result)){
		if ($arr['title'] == 'version_core') $version_core = $arr['value'];
		if ($arr['title'] == 'update_core') $update_core = $arr['value'];
	}
}

if ($update_core != 0){
	print "
	<form action='index.php?".$_SERVER['QUERY_STRING']."' method='post'>
		<div class='update'>
			<div class='update-head'>
				<i class='icon-update'></i>".v('CMT_HEADLINE_UPDATE')."
			</div><!-- /.update-head -->
			<div class='update-content'>
				<div class='update-title'>
					<h3>".v('CMT_UPDATE_VERSION')." ".$version_core." auf ".$update_core."</h3>
				</div><!-- /.update-title -->
				<p>".v('CMT_TEXT_UPDATE')."</p>";
	if (isset($_POST['cmt_update'])){
		if (is_file(BACKEND.'modul/update/CMT_CORE_'.$update_core.'.zip')){
			$update_complete = false;
			$zip_file = zip_open(BACKEND.'modul/update/CMT_CORE_'.$update_core.'.zip');
			while ($data=zip_read($zip_file)){
				$filename = zip_entry_name($data);
				if (substr($filename,-1,1) == '/'){
					if (!is_dir(FRONTEND.$filename)){
						mkdir(FRONTEND.$filename);
						print "<p>".v('CMT_CREATED_FOLDER')."&nbsp".$filename."</p>";
					}
				} else {
					if ($filename != 'cmt/setup.php'){
						if (!is_file(FRONTEND.$filename)){
							$file_content = zip_entry_read($data, zip_entry_filesize($data));
							$new_file = fopen(FRONTEND.$filename, 'w');
							fwrite($new_file, $file_content);
							fclose($new_file);
							print "<p>".v('CMT_CREATED_FILE')."&nbsp".$filename."</p>";
						} else {
							$file_content = zip_entry_read($data, zip_entry_filesize($data));
							$old_file = file_get_contents(FRONTEND.$filename);
							if ($old_file != $file_content){
								$new_file = fopen(FRONTEND.$filename, 'w');
								fwrite($new_file, $file_content);
								fclose($new_file);
								print "<p>".v('CMT_UPDATED_FILE')."&nbsp".$filename."</p>";
							}
						}
					}
				}
				$update_complete = true;
			}
			if ($update_complete === true) {
				$sql = "UPDATE ".$_SESSION['TABLE_PREFIX']."cmt_settings SET value = '".$update_core."', c_active = '1' WHERE title = 'version_core' ";
				db_mysql_query($sql,$conn);
				$sql = "UPDATE ".$_SESSION['TABLE_PREFIX']."cmt_settings SET value = '0', c_active = '0' WHERE title = 'update_core' ";
				db_mysql_query($sql,$conn);
				unlink(BACKEND.'modul/update/CMT_CORE_'.$update_core.'.zip');
			}
		}
	}
				
	if (isset($_POST['cmt_download'])) {
		if (!is_file(BACKEND.'modul/update/CMT_CORE_'.$update_core.'.zip')){
			print "<p>".v('CMT_DOWNLOADING_UPDATE')."</p>";
			$headers = get_headers('http://update.comator.org/core/CMT_CORE_'.$update_core.'.zip');
			if (substr($headers[0], 9, 3) == '200'){
				$core_file = file_get_contents('http://update.comator.org/core/CMT_CORE_'.$update_core.'.zip');
				$handle = fopen(BACKEND.'modul/update/CMT_CORE_'.$update_core.'.zip', 'w');
				if (fwrite($handle, $core_file)) {
					fclose($handle);
					print "<p>".v('CMT_DOWNLOAD_COMPLETE')."</p>";
				}
			} else {
				print "<p>".v('CMT_DOWNLOAD_ERROR')."</p>";
			}
		} else {
			print "<p>".v('CMT_DOWNLOAD_ALREADY')."</p>";
		}
	}
	
	if (!isset($update_complete)){
		print "<div class='form_submit'>";
		if (!is_file(BACKEND.'modul/update/CMT_CORE_'.$update_core.'.zip')) print "<button type='submit' name='cmt_download' id='submit-form' title='".v('CMT_BUTTON_DOWNLOAD')."'><i class='icon-update'></i>".v('CMT_BUTTON_DOWNLOAD')."</button>";
		else print "<button type='submit' name='cmt_update' id='submit-form' title='".v('CMT_BUTTON_UPDATE')."'><i class='icon-update'></i>".v('CMT_BUTTON_UPDATE')."</button>";
		print "</div>";
	}
	print "
			</div><!-- /.update-content -->
		</div><!-- /.update -->
	</form>";
} else {
	print "
	<div class='update'>
		<div class='update-head'>
			<i class='icon-update'></i>".v('CMT_HEADLINE_UPDATE')."
		</div><!-- /.update-head -->
		<div class='update-content'>
			<div class='update-title'>
				<h3>".v('CMT_NO_UPDATE')."</h3>
			</div><!-- /.update-title -->
			<p>".v('CMT_TEXT_NO_UPDATE')."</p>
		</div><!-- /.update-content -->
	</div><!-- /.update -->";
}

?>