<?php
$objReport = new Report();
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$pagetitle="Reports";

$actions=isset($_REQUEST['actions'])?$_REQUEST['actions']:'list';
	switch($actions)
	{
        case 'order':
            include(ITFModulePath.'view/order.php');
            break;

        case 'transaction':
            include(ITFModulePath.'view/transaction.php');
            break;

        case 'export':
            include(ITFModulePath.'view/export.php');
            break;

		case 'delete':
			include(ITFModulePath.'view/list.php');
			break;
	
		default:
			include(ITFModulePath.'view/list.php');
	}
?>