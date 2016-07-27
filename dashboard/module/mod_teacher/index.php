<?php
$objTeacher=new Teacher();
//$userCourse=new Course();
//$dropdownCourse = $userCourse->showAllCourselist();
//$userCamp = new Camp();
//$dropdownCamp=$userCamp->showAllCampList();
//print_r($dropdownCamp);
//print_r($dropdown);
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$pagetitle="Teacher";

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