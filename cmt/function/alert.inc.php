<?php

#########################################################
#########################################################
#####                                               #####
#####     ALERT                                     #####
#####                                               #####
#########################################################
#########################################################


function print_alert($type, $headline, $content){
	$output = "<div class='alert ".$type."'>";
	$output .= "<div class='alert-headline'>";
	$output .= "<i class='icon-alert-".$type."'></i>".$headline;
	$output .= "</div><!-- /.alert-headline -->";
	$output .= "<div class='alert-content'>";
	$output .= $content;
	$output .= "</div><!-- /.alert-content -->";
	$output .= "</div><!-- /.alert -->";
	return $output;
}
?>