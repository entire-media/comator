<?php

#########################################################
#########################################################
#####                                               #####
#####    	DEFAULT SESSIONS                          #####
#####                                               #####
#########################################################
#########################################################

if (!isset($_SESSION['toggle_sidebar'])) $_SESSION['toggle_sidebar'] = "show";
if (!isset($_SESSION['toggle_filter'])) $_SESSION['toggle_filter'] = "show";
if (!isset($_SESSION['toggle_filter_basics'])) $_SESSION['toggle_filter_basics'] = "show";
if (!isset($_SESSION['toggle_filter_search'])) $_SESSION['toggle_filter_search'] = "show";
if (!isset($_SESSION['toggle_filter_options'])) $_SESSION['toggle_filter_options'] = "show";


?>