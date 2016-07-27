<?php
if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
    redirectUrl(CreateLink(array("teacherlogin", "msg" => 'na')));
} 
$obj = new User();
$currentpage = isset($_GET['itfpage']) ? $_GET['itfpage'] : '';
$actions = isset($_REQUEST['itemid']) ? $_REQUEST['itemid'] : 'teacherdashboard';


$page = BASEPATHS . "/site/component/com_".$currentpage."/".$actions.".php";
if (file_exists($page))
    include_once($page);
else
    include_once(BASEPATHS . '/site/component/com_404/404.php');
?>
