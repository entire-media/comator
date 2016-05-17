<?php

#########################################################
#########################################################
#####                                               #####
#####     Write Log File                            #####
#####                                               #####
#########################################################
#########################################################


function log_write($message) {
	date_default_timezone_set('Europe/Berlin');	
	$path = FRONTEND.'logs/';
	$date = new DateTime();
	$log = $path.$date->format('Y-m-d').".txt";
	
	if(is_dir($path)) {
		if(!file_exists($log)) {
			$fh  = fopen($log, 'a+') or die("Fatal Error !");
			$logcontent = "Time : ".$date->format('H:i:s')."\r\n".$message."\r\n";
			fwrite($fh, $logcontent);
			fclose($fh);
		} else {
			log_edit($log,$date, $message);
		}
	} else {
		print $path;
		if(mkdir($path,0777) === true){
			log_write($message);  
		}	
	}
}

function log_edit($log,$date,$message) {
	$logcontent = "Time : ".$date->format('H:i:s')."\r\n".$message."\r\n\r\n";
	$logcontent = $logcontent.file_get_contents($log);
	file_put_contents($log, $logcontent);
}
?>