<?php
$objfaculty=new Faculty();
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$pagetitle="Faculty";

	$actions=isset($_REQUEST['actions'])?$_REQUEST['actions']:'list';

	switch($actions)
	{
		case 'add':
			include(ITFModulePath.'view/add.php');
			break;
	
		case 'edit':
			include(ITFModulePath.'view/add.php');
			break;
	
		case 'delete':
			include(ITFModulePath.'view/list.php');
			break;
	
		default:
			include(ITFModulePath.'view/list.php');
	}
	?>