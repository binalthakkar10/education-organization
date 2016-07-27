<?php
error_reporting(0);
$msgs = "Login";
require('../../itfconfig.php');
$objTeacher = new Teacher();
//echo '<pre>';print_r($_REQUEST);
if (isset($_REQUEST['itfpg']) and $_REQUEST['itfpg'] == "uservalidate") {
    if ($objTeacher->userUniqueByUsername($_REQUEST['username']) == "1")
        echo 'false';
    else
        echo 'true';
}
elseif (isset($_REQUEST['emailid']) and $_REQUEST['emailid'] == "emailvalue") {
    if ($ojbuser->CheckEmailId($_REQUEST['email']))
        echo 'false';
    else
        echo 'true';
}
elseif(isset($_REQUEST['email']))
{
	if($objTeacher->checkTeacherEmailId($_REQUEST['email']))
        echo 'false';
	else
        echo 'true';
}
elseif (isset($_POST['itfpg']) and !empty($_POST['itfpg'])) {
    if ($_POST['itfpg'] == "userlist" or $_POST['itfpg'] == "userlistagent") {
        echo $objTeacher->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "frmpages") {
        $objCMS = new PageCms();
        echo $objCMS->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "cmspage") {
        $objCMS = new PageCms();
        echo $objCMS->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "product") {
        $obj = new Product();
        echo $obj->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "quote") {
        $obj = new Quote();
        echo $obj->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "user") {
        $obj = new User();
        echo $obj->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "tournament") {
        $obj1 = new Tournament();
        echo $obj1->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "member") {
        $obj = new User();
        echo $obj->PublishMember($_POST['flid']);
    } 
    elseif ($_POST['itfpg'] == "teacher") {
        $obj = new Teacher();
        echo $obj->PublishMember($_POST['flid']);
    } elseif ($_POST['itfpg'] == "users") {
        $obj = new Users();
        echo $obj->PublishMember($_POST['flid']);
    }elseif ($_POST['itfpg'] == "frmcategory") {
        $objCAT = new CreateAlbum();
        echo $objCAT->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "frmgrade") {
        $objCAT = new Grade();
        echo $objCAT->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "frmadvertise") {
        $objactions = new Advertise();
        echo $objactions->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "adminuserlist") {
        $objAdminUser = new AdminUser();
        echo $objAdminUser->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "frmbusiness") {
        $objactions = new Business();
        echo $objactions->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "frmfaculty") {
        $objactions = new Faculty();
        echo $objactions->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "newsletter") {
        // echo 'Aniket';
        $obj = new Newsletter();
        echo $obj->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "subscriber") {
        $obj = new Newsletter();
        echo $obj->PublishBlocksubscriber($_POST['flid']);
    } elseif ($_POST['itfpg'] == "frmmenu") {
        $obj = new Menu();
        echo $obj->PublishBlock($_POST['flid']);
    } elseif ($_POST['itfpg'] == "frmdebate") {
        $obj = new Debate();
        echo $obj->PublishBlock($_POST['flid']);
    }
     elseif ($_POST['itfpg'] == "frmcourses") {
        $obj = new Course();
        echo $obj->PublishBlock($_POST['flid']);
    }
}
?>
