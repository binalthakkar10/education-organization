<?php

$userobj = new User();
$currentpagenames = isset($_GET['itfpage']) ? $_GET['itfpage'] : '';
$pagetitle = "Student";
$statusList = $userobj->ShowAllStatus();
$actions = isset($_REQUEST['actions']) ? $_REQUEST['actions'] : 'list';
switch ($actions) {
    case 'add':
        include(ITFModulePath . 'view/add.php');
        break;

    case 'edit':
        include(ITFModulePath . 'view/add.php');
        break;

    case 'delete':
        include(ITFModulePath . 'view/list.php');
        break;

    case 'summer_add':
        include(ITFModulePath . 'view/summer_add.php');
        break;

    case 'summer_edit':
        include(ITFModulePath . 'view/summer_add.php');
        break;

    case 'summer_delete':
        include(ITFModulePath . 'view/summer_list.php');
        break;

    case 'summer_list':
        include(ITFModulePath . 'view/summer_list.php');
        break;
    default:
        include(ITFModulePath . 'view/list.php');
}
?>
