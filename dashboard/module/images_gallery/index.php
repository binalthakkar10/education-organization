<?php
$obj = new Gallery();
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$parentids = isset($_REQUEST['parentid'])?$_REQUEST['parentid']:'0';
$pagetitle=($parentids>0)?"gallery":"Gallery";

	$actions=isset($_REQUEST['actions'])?$_REQUEST['actions']:'grade_list';

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