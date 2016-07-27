<?php

$userobj=new User();

//include('pages/elements/adminpasswords.php');

$ids=isset($_GET['id'])?$_GET['id']:"0";

$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';

$pagetitle="Admin Profile";



	$actions=isset($_REQUEST['actions'])?$_REQUEST['actions']:'adminpassword';

	switch($actions)

	{

		

		default:

			include(ITFModulePath.'view/adminpasswords.php');

	}

?>