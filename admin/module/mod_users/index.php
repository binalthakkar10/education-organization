<?php
$obj = new Users();
//var_dump($obj);
$currentpagenames = isset($_GET['itfpage']) ? $_GET['itfpage'] : '';
$parentids = isset($_REQUEST['parentid']) ? $_REQUEST['parentid'] : '0';
//echo $pagetitle="class";die;
$pagetitle = ($parentids > 0) ? "Users" : "Users";
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
     case 'upload_form':
        include(ITFModulePath . 'view/upload_form.php');
        break;


    default:
        include(ITFModulePath . 'view/list.php');
}
?>
