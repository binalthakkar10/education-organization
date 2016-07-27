<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
	$objSlidingImage=new SlidingImage();
	$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
	$pagetitle="Sliding Image";

	$actions=isset($_REQUEST['actions'])?$_REQUEST['actions']:'banner_list';
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