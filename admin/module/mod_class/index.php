
<?php
/*
Version 1.0 - Initial Version
Version 1.1 - 6/3/16 - Enhancement 14 - Duplicate Classes */

$obj = new Class1();
//var_dump($obj);
$currentpagenames = isset($_GET['itfpage']) ? $_GET['itfpage'] : '';
$parentids = isset($_REQUEST['parentid']) ? $_REQUEST['parentid'] : '0';
//echo $pagetitle="class";die;
$pagetitle = ($parentids > 0) ? "Class" : "Class";
$actions = isset($_REQUEST['actions']) ? $_REQUEST['actions'] : 'list';
switch ($actions) {
    case 'add':
        include(ITFModulePath . 'view/add.php');
        break;

    case 'edit':
        include(ITFModulePath . 'view/add.php');
        break;
        
    case 'duplicate':
        include(ITFModulePath . 'view/duplicate.php');
        break;
    case 'class_registration_list':
        include(ITFModulePath . 'view/class_registration_list.php');
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
