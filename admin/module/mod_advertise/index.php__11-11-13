<?php
	$possitionname=array("1"=>"Left","2"=>"Bottom","3"=>"Last Bottom");
	$categoryobj=new Advertise();
	$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
	$pagetitle="Advertise";

	$actions=isset($_REQUEST['actions'])?$_REQUEST['actions']:'advertise_list';
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