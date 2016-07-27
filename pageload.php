<?php
	$currentpage=isset($_REQUEST['itfpage'])?($_REQUEST['itfpage']):'index';
	$pagenames=BASEPATHS."/site/component/com_".$currentpage."/".$currentpage.".php";
	$data=array();
	foreach($_POST as $k=>$v) $data[$k]=$v;
	foreach($_GET as $k=>$v) $data[$k]=$v;
	
	ob_start();
	if(file_exists($pagenames))
		include_once($pagenames);
	else
		include_once(BASEPATHS.'/site/component/com_404/404.php');
	$itf_bodydata=ob_get_contents();
	ob_clean();
?>