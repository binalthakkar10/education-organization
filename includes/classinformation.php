<?php
	require_once($BASEPATHS.'/includes/generalfunction.php');

	function __autoload($x)
	{
		$ClassFilePath = dirname(__FILE__);
require_once($ClassFilePath."/classes/".$x.".class.php");
	}
?>