<?php

#########################################################
#########################################################
#####                                               #####
#####     DATABASE                                  #####
#####                                               #####
#########################################################
#########################################################

function db_mysql_connect($num=1){
	$conns = parse_ini_file(FRONTEND.'config.inc.php');
	$i=1;
	foreach ($conns AS $key => $db){
		if (strpos($key, 'conn') !== false) {
			try {
				if ($i==$num){
					$dbh = $db['driver'].":host=".$db['host'].";dbname=".$db['dbname'];
					$pd = new PDO($dbh, $db['user'], $db['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
					$pd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$pd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
					return $pd;
				}
			} catch (PDOException $e) {
				db_exception_log($e->getMessage());
			}
		}
		$i++;
	}
}

function db_mysql_query($query, $conn){
	try {
		return $conn->query($query);
	} catch(PDOException $e) {
		echo db_exception_log($e->getMessage(), $query);
		die();
	}
}

function db_mysql_fetch_array($result){
	return $result->fetch(PDO::FETCH_ASSOC);
}

function db_mysql_num_rows($result){
	if ($result){
		return $result->rowCount();
	}
}

function db_last_id($conn){
	return $conn->lastInsertId();
}

function db_exception_log($message , $sql = ""){
	$exception  = 'Unhandled Exception. <br />';
	$exception .= $message;
	$exception .= "<br /> You can find the error back in the log.";
	if(!empty($sql)) {
		$message .= "\r\nRaw SQL : "  . $sql;
	}
	log_write($message);
	return $exception;
}		

?>