<?php
$objReport = new Report();
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$pagetitle="Reports";

$actions=isset($_REQUEST['actions'])?$_REQUEST['actions']:'enquiry';
	switch($actions)
	{
        case 'order':
            include(ITFModulePath.'view/enquiry.php');
            break;

        case 'clarification':
            include(ITFModulePath.'view/clarification.php');
            break;

        case 'export':
            include(ITFModulePath.'view/export.php');
            break;

	
		default:
			include(ITFModulePath.'view/enquiry.php');
	}
?>