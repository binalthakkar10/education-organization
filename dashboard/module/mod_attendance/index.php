<?php

$objAtt = new Attendance();
//var_dump($objAtt);
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$parentids = isset($_REQUEST['parentid'])?$_REQUEST['parentid']:'0';
//$pagetitle="Course";
$pagetitle=($parentids>0)?"Attendance":"Attendance";
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
                case 'studentcount':
			include(ITFModulePath.'view/student_count.php');
			break;
	
		default:
			include(ITFModulePath.'view/list.php');
	}
	?>

