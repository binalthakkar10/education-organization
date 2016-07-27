<?php
error_reporting(0);
$msgs = "Login";
require('../itfconfig.php');
if (!AdminLogins())
    redirectUrl('index.php');
require('pagecreation.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author" content="Pawe? 'kilab' Balicki - kilab.pl" />
        <title><?php echo $stieinfo['sitename'] . ' | Admin Panel'; ?></title>

        <?php
        Html::AddCssFile("css/style.css");
        Html::AddCssFile("css/navi.css");
        Html::AddCssFile("css/tcal.css");

        echo Html::Css();

        Html::AddJsFile("js/jquery.js");
        Html::AddJsFile("../itfbox/itfboxpack.js");
        Html::AddJsFile("js/tcal.js");
        Html::AddJsFile("js/menujs.js");
        Html::AddJsFile("js/jquery.validate.js");
        Html::AddJsFile("js/itf_mask.js");
        Html::AddJsFile("js/jquery.form.js");
        echo Html::Js();
        ?> 
        <script type="text/javascript">
            $(function() {
                $(".box .h_title").not(this).next("ul").hide("normal");
                $(".box .h_title").not(this).next(".<?php echo $currentpage; ?>").show("normal");
                $(".box").children(".h_title").click(function() {
                    $(this).next("ul").slideToggle();
                });
            });
        </script>
    </head>
    <body>
        <div class="wrap">
            <div id="header">
                <div id="top">
                    <div class="left">
                        <a href="<?php echo CreateLinkAdmin(array('index')); ?>"><img src="img/logo1.png" /></a>
                    </div>
                    <div class="right">
                        <div class="align-right">
                            <p>Welcome, <strong><?php
//echo '<pre>';print_r($_SESSION);
                                    echo WordLimit($_SESSION['LoginInfo']['first_name'], 1);
                                    ?></strong> [ <a href="logouts.php">logout</a> ]</p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content">
                <div id="sidebar">


                    <div class="box">
                        <div class="h_title">&#8250; Manage Content Pages</div>
                        <ul class="cms">
                            <li class="b1"><a class="icon page" href="<?php echo CreateLinkAdmin(array('cms')); ?>">Show All Pages</a></li>
                            <!--<li class="b2"><a class="icon add_page" href="<?php echo CreateLinkAdmin(array('cms', 'actions' => 'add')); ?>">Add new page</a></li>-->
                        </ul>
                    </div>
                    <div class="box">
                        <div class="h_title">&#8250; Manage Menu</div>
                        <ul class="cms">
                            <li class="b1"><a class="icon page" href="<?php echo CreateLinkAdmin(array('menu')); ?>">Show All Menus</a></li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="h_title">&#8250; Manage Albums</div>
                        <ul class="create_album gallery">

                            <li class="b1"><a class="icon page" href="<?php echo CreateLinkAdmin(array('create_album')); ?>">Show All Albums</a></li>
                            <li class="b1"><a class="icon page" href="<?php echo CreateLinkAdmin(array('gallery')); ?>">Show All Images</a></li>
                         <!-- <li class="b2"><a class="icon add_page" href="<?php echo CreateLinkAdmin(array('images_gallery', 'actions' => 'add')); ?>">Add new Image</a></li>-->
                        </ul>
                    </div>

                    <div class="box">
                        <div class="h_title">&#8250; Manage Sliding Images</div>
                        <ul class="banner">
                            <li class="b1"><a class="icon page" href="<?php echo CreateLinkAdmin(array('sliding_image')); ?>">Show All Sliding Images</a></li>
                            <!--<li class="b2"><a class="icon add_page" href="<?php echo CreateLinkAdmin(array('banner', 'actions' => 'add')); ?>">Add new Image</a></li>-->
                        </ul>
                    </div>


                    <div class="box">
                        <div class="h_title">&#8250; Manage Newsletter</div>
                        <ul class="newsletter">
                            <li class="b1"><a class="icon send_message" href="<?php echo CreateLinkAdmin(array('newsletter', 'actions' => 'send')); ?>">Send Newsletter</a></li>
                            <li class="b1"><a class="icon message" href="<?php echo CreateLinkAdmin(array('newsletter')); ?>">Newsletter</a></li>
                            <li class="b1"><a class="icon group" href="<?php echo CreateLinkAdmin(array('newsletter', 'actions' => 'subscriber')); ?>">Subscribe Members</a></li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="h_title">&#8250; Manage Teachers</div>
                        <ul class="user supplier teacher">
                            <li class="b2"><a class="icon add_user" href="<?php echo CreateLinkAdmin(array('teacher')); ?>">View All Teachers</a></li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="h_title">&#8250; Manage Students</div>
                        <ul class="user supplier student">
                            <li class="b2"><a class="icon add_user" href="<?php echo CreateLinkAdmin(array('student')); ?>">Show All Students</a></li>
                            <li class="b2"><a class="icon add_user" href="<?php echo CreateLinkAdmin(array('student')); ?>&actions=summer_list">Show All Summer Camp Students</a></li>
                        </ul>
                    </div>


                    <div class="box">
                        <div class="h_title">&#8250; Manage Grades</div>
                        <ul class="grade">

                            <li class="b2"><a class="icon profile" href="<?php echo CreateLinkAdmin(array('grade')); ?>">Show All Grade</a></li>

                        </ul>
                    </div>

                    <div class="box">
                        <div class="h_title">&#8250; Manage Tournament</div>
                        <ul class="user tournament">
                            <li class="b2"><a class="icon add_user" href="<?php echo CreateLinkAdmin(array('tournament')); ?>">Show All Tournaments</a></li>
                        </ul>
                    </div>

                    <div class="box">
                        <div class="h_title">&#8250; Manage Class</div>
                        <ul class="addclass class">
                            <li class="b1"><a class="icon users" href="<?php echo CreateLinkAdmin(array('class')); ?>">List of All Classes</a></li>
                            <li class="b1"><a class="icon users" href="<?php echo CreateLinkAdmin(array('class')); ?>&actions=class_registration_list">Class Registration</a></li>
                           <!-- <li class="b1"><a class="icon users" href="<?php echo CreateLinkAdmin(array('class')); ?>&actions=upload_form">Upload Registration Form</a></li>-->

                        </ul>
                    </div>  
                    <div class="box">
                        <div class="h_title">&#8250; Manage Summer Camps</div>
                        <ul class="summer_camp camp_class">
                            <li class="b1"><a class="icon users" href="<?php echo CreateLinkAdmin(array('summer_camp')); ?>">List of All Summer Camps</a></li>
                            <li class="b1"><a class="icon users" href="<?php echo CreateLinkAdmin(array('summer_camp')); ?>&actions=summer_camps_registration_list">Summer Camps Registration</a></li>
                            <!--<li class="b1"><a class="icon users" href="<?php echo CreateLinkAdmin(array('summer_camp')); ?>&actions=upload_form">Upload Registration Form</a></li>-->

                        </ul>
                    </div> 

                    <div class="box">
                        <div class="h_title">&#8250; Manage Course</div>
                        <ul class="course course_location">
                            <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('course')); ?>">Show All Courses </a></li>
                           <!-- <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('course_location')); ?>">Location </a></li>-->
                <!--            <li class="b1"><a class="icon config" href="<?php echo CreateLinkAdmin(array('class')); ?>">Class</a></li>
                            <li class="b1"><a class="icon config" href="<?php echo CreateLinkAdmin(array('registration')); ?>">Registration</a></li>-->

                        </ul>
                    </div>    
                    <div class="box">
                        <div class="h_title">&#8250; Manage Locations</div>
                        <ul class="course course_location">
                            <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('location')); ?>">Show All Locations </a></li>

                        </ul>
                    </div>    


                    <!--<div class="box">
                           <div class="h_title">&#8250; Manage Attendance</div>
                           <ul class="attendance lessonroll view_attendance">
                               <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('attendance')); ?>">Attendance </a></li>
                               <!--<li class="b1"><a class="icon config" href="<?php echo CreateLinkAdmin(array('lessonroll')); ?>">View attendance</a></li>
                              <li><a href="<?php echo CreateLinkAdmin(array('lessonroll', "actions" => "display")); ?>">Attendance View</a></li>
                               <li class="b1"><a class="icon config" href="<?php echo CreateLinkAdmin(array('view_attendance')); ?>">View attendance</a></li>
                        </ul>
                       </div> -->




                    <div class="box">
                        <div class="h_title">&#8250; Manage Debt</div>
                        <ul class="debate">
                            <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('debate')); ?>">Debt Ranking </a></li>
                          <!--<li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('camp_class')); ?>">Summercamp Class  </a></li>
                          <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('locations')); ?>">Location </a></li>
                           <li class="b1"><a class="icon config" href="<?php echo CreateLinkAdmin(array('register')); ?>">Registration</a></li>-->
                        </ul>
                    </div> 
                    <div class="box">
                        <div class="h_title">&#8250; Manage Attendance</div>
                        <ul class="debate">
                            <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('attendance')); ?>">Attendance</a></li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="h_title">&#8250; Manage Coupons</div>
                        <ul class="debate">
                            <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('coupon')); ?>">Coupons</a></li>
                        </ul>
                    </div>
                   <div class="box">
                       <div class="h_title">&#8250; Manage Organization</div>
                       <ul class="debate">
                           <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('organization')); ?>">Organization</a></li>
                       </ul>
                   </div>
                    <div class="box">
                        <div class="h_title">&#8250; Manage Users</div>
                        <ul class="debate">
                            <li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('users')); ?>">Users</a></li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="h_title">&#8250; Settings</div>
                        <ul class="config profile password state">
                            <li class="b1"><a class="icon config" href="<?php echo CreateLinkAdmin(array('config')); ?>">Site Configuration</a></li>
                            <li class="b2"><a class="icon profile" href="<?php echo CreateLinkAdmin(array('profile')); ?>">Profile Configuration</a></li>
                            <li class="b1"><a class="icon password" href="<?php echo CreateLinkAdmin(array('password')); ?>">Change Password</a></li>
                            <!--<li class="b1"><a class="icon city" href="<?php echo CreateLinkAdmin(array('state')); ?>">Location Manage</a></li>-->
                        </ul>
                    </div>
                </div>
                <div id="main">

                    <?php flash(); ?>
<!--<div style='color:red;font-weight:bold'>Cannot delete or update Some of Parent Row: Due to foreign key constraint </div>-->
                    <?php echo $itf_body_contents; ?>
                </div>
                <div class="clear"></div>
            </div>
            <div id="footer">
                <div class="left">
                    <p>Design & Developed by : <a href="http://shahdeepinternational.com" target="_blank">Shah Deep International</a> | Admin Panel: <a href=""><?php echo $stieinfo['sitename']; ?></a></p>
                </div>
                <div class="right">
                    <p></p>
                </div>
            </div>
        </div>


    </body>
</html>