<?php

$obj = new Newsletter();
$currentpagenames = isset($_GET['itfpage']) ? $_GET['itfpage'] : 'newsletter';
$pagetitle = "Newletter";
//add_subscriber
$actions = isset($_REQUEST['actions']) ? $_REQUEST['actions'] : 'list';
switch ($actions) {
    case 'add':
        include(ITFModulePath . 'view/add.php');
        break;
    case 'add_subscriber':
        include(ITFModulePath . 'view/add_subscriber.php');
        break;
    case 'edit':
        include(ITFModulePath . 'view/add.php');
        break;

    case 'send':
        include(ITFModulePath . 'view/send.php');
        break;

    case 'subscriber':
        include(ITFModulePath . 'view/subscriber.php');
        break;

    case 'delete':
        include(ITFModulePath . 'view/list.php');
        break;

    default:
        include(ITFModulePath . 'view/list.php');
}
?>