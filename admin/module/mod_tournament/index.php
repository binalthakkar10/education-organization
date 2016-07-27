<?php
$userobj=new Tournament();
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$pagetitle="Tournament";

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
                case 'studentlist':
			include(ITFModulePath.'view/studentlist.php');
			break;
		case 'student_detail':
			include(ITFModulePath.'view/student_detail.php');
			break;


	
		default:
			include(ITFModulePath.'view/list.php');
	}
?>