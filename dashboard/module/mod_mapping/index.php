<?php
$userobj=new Mapping();
$userCourse=new Course();
$dropdownCourse = $userCourse->showAllCourselist();
//print_r($dropdown);
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$pagetitle="Assign Course";
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