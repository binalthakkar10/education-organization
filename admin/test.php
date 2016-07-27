<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require('../itfconfig.php');
//echo 'yyyyyyyyy'.$BASEPATHS;
unlink($BASEPATHS . "/admin/module/mod_class"); 
unlink($BASEPATHS . "/admin/module/mod_summer_camp"); 
unlink($BASEPATHS . "/admin/module/mod_attendance"); 
unlink($BASEPATHS . "/admin/module/mod_location"); 
//redirectUrl("admin");
//require('pageload.php');
//require("template/".Template::getTemplate()."/index.php");
