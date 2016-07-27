<?php

$obj = new Class1();
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$parentids = isset($_REQUEST['parentid'])?$_REQUEST['parentid']:'0';
//echo $pagetitle="class";die;
$pagetitle=($parentids>0)?"Class1":"class1";
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