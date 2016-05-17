<?php

#########################################################
#########################################################
#####                                               #####
#####     PARSE                                     #####
#####                                               #####
#########################################################
#########################################################


function parse_addslashes_array($var) {
	foreach ($var as $key => $value) {
		if (is_array($value)) {
			$var[$key] = parse_addslashes_array($value);
		} else {
			$var[$key] = addslashes(stripslashes($value));
		}
	}
	return $var;
}

function check($value){
	if (isset($value)) return $value;
	else return NULL;
}

function parse($var, $case='string'){
	switch ($case) {
		
		case 'arr_int':
			if ($var){
				$var_new = array();
				foreach ($var AS $key => $value){
					$var_new[parse($key,'int')]= parse($value,'int');	        
			  }
		    return $var_new;
		  } else return 0;
		  break;
		
		case 'arr':
			if ($var){
				$var_new = array();
				foreach ($var AS $key => $value){
					$var_new[parse($key,'string')]= parse($value,'string');	        
			  }
		    return $var_new;
		  } else return NULL;
		
		case 'float':
			if ($var){
				if (is_array($var)) $var = NULL;
				else {
		 			if(settype($var, 'float')) $var = $var;
		 			else $var = NULL;
		 		}
		  } else $var = NULL;
		  break;
		
		case 'int':
			if ($var){
				if (is_array($var)) $var = 0;
		  	else {
		  		if(settype($var, 'integer')) $var = $var;
		  		else $var = 0;
		  	}
			} else $var = 0;
			break;
		
		case 'url':
			if ($var){
				if (is_array($var)) $var = NULL;
				else $var = trim(strip_tags(rawurldecode($var)));
			} else $var = NULL;
			break;
			
		case 'string':
		default:
			if ($var){
				if (is_array($var)) $var = NULL;
				else $var = trim(strip_tags($var));
			} else $var = NULL;
			break;
			
	}
	return $var;
}
?>