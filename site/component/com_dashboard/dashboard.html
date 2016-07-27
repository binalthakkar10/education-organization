<?php
$obj = new User();
$currentpage = isset($_GET['itfpage'])?$_GET['itfpage']:'';
//echo"<pre>";print_r($_GET);echo"<br/>";
//echo "post"."<pre>";print_r($_POST);echo"<br/>";
//echo"<pre>";print_r($_SESSION);echo"<br/>";
//die;
if(empty($_SESSION['FRONTUSER']))
{
    redirectUrl(CreateLink(array("signin","msg"=>'na')));
} else {
   
    if($_SESSION['FRONTUSER']['usertype'] == 3)
    {
        $actions = 'supplier';
    } elseif($_SESSION['FRONTUSER']['usertype'] == 2){
        $actions = 'customer';
    }else {
        $actions = 'default';
    }
}

$page = BASEPATHS."/site/component/com_".$currentpage."/".$actions.".php";

if(file_exists($page))
   include_once($page);
else
   include_once(BASEPATHS.'/site/component/com_404/404.php');
	
?>