<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
    redirectUrl(CreateLink(array("teacherlogin")));
    exit;
}
$teacherId = $_SESSION['teacherLoginInfo']['user_id'];
$teacherObj = new Teacher;
$teacherDetails = $teacherObj->Get_User($teacherId);
//echo '<pre>'; print_r($teacherDetails);
?>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <div id="page_title">
            <h1>Teacher <span style="color:#ab281f;">Dashboard</span></h1>
        </div>
        <div id="page_content">
            <?php include_once('left_menu.php') ?>
            <div class="right_content">
                <!-- My Profile -->
                <div id="gallery">
                    <div id="title_teacher_login"><h2>My Profile</h2></div>
                    <div>
                        <div class="nme"><h1><?php echo $teacherDetails['first_name'] . '&nbsp;' . $teacherDetails['last_name'] ?></h1></div>

                        <div class="clr"></div>
                    </div>

                    <div class="wauto mrb5">
                        <div class="label">Phone Number :</div>
                        <div class="nme"><?php echo $teacherDetails['phone'] ?></div>
                        <div class="clr"></div>
                    </div>
                    <div class="wauto mrb5">
                        <div class="label">Email Id :</div>
                        <div class="nme"><?php echo $teacherDetails['email'] ?></div>
                        <div class="clr"></div>
                    </div>
                    <div class="wauto mrb5">
                        <div class="label">Address:</div>
                        <div class="nme"><?php echo $teacherDetails['address'] ?></div>
                        <div class="clr"></div>
                    </div>

                </div>
                <!-- My Profile-->
            </div><!-- right_content -->
        </div>
    </div>
</div>
