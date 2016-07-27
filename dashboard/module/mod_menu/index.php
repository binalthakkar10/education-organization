<?php

$obj = new Menu();
$currentpagenames = isset($_GET['itfpage']) ? $_GET['itfpage'] : '';
$parentids = isset($_REQUEST['parentid']) ? $_REQUEST['parentid'] : '0';
$pagetitle = ($parentids > 0) ? "Menu" : "Menu";

$actions = isset($_REQUEST['actions']) ? $_REQUEST['actions'] : 'grade_list';

switch ($actions) {

    case 'edit':
        include(ITFModulePath . 'view/add.php');
        break;
    default:
        include(ITFModulePath . 'view/list.php');
}
?>