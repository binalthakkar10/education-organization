<?php
$currentpage=(isset($_REQUEST['itfpage']))?$_REQUEST['itfpage']:'homes';
$pagenames=SITEPATH.'/wtemplate/itf_'.$currentpage.".php";
if(file_exists($pagenames))	include_once($pagenames);
?>