<?php
$currentpage=(isset($_GET['itfpage']))?$_GET['itfpage']:'index';
$itfbasepath=dirname(__FILE__);


$pagenamessload=$itfbasepath."/module/mod_".$currentpage."/index.php";
ob_start();
if(file_exists($pagenamessload))
{
	define("ITFModulePath",$itfbasepath."/module/mod_".$currentpage."/");
	include($pagenamessload);
}
else
{
	define("ITFModulePath",$itfbasepath."/module/mod_404/");
	include($itfbasepath."/module/mod_404/index.php");
}
$itf_body_contents=ob_get_contents();
ob_clean();
?>