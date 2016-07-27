<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$debateObj = new Debate();
//var_dump($debateObj);

$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$parentids = isset($_REQUEST['parentid'])?$_REQUEST['parentid']:'0';
//$pagetitle="Course";
$pagetitle=($parentids>0)?"Debt":"Debt";
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
                    
              case 'import':
                         include(ITFModulePath.'view/import.php');
                         break;
	
		default:
			include(ITFModulePath.'view/list.php');
	}
	?>
