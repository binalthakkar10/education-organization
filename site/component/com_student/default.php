<?php
if (!empty($_SESSION['teacherLoginInfo']['user_id'])) {
// CreateLink(array("teacherdashboard"));

    redirectUrl(CreateLink(array("teacher")));
}
 if(isset($_POST["email"])) {
    if (!empty($_POST["email"])) {

        $logininfo = $obj->loginTeacher($_POST["email"], $_POST["password"]);

        if ($logininfo) {
            redirectUrl(CreateLink(array("teacher")));
            // exit;
        } else {
            flashMsg("Username and Password do not match or you do not have an account yet.", "2");
            redirectUrl(CreateLink(array("teacherlogin")));
        }
    } else {
        flashMsg("Empty Username / Password not allowed.", "2");
        redirectUrl(CreateLink(array("teacherlogin")));
    }
}
if (isset($_GET['msg']) and $_GET['msg'] == "na") {
    echo "<div class='msgbox n_error'><p>If you are not yet a customer please Register.</p></div>";
}
?>




<!-- _________________________main_wrapper Section_________________________ -->    
<div class="main_wrapper" id="mid_wrapper">
    <div class="main_mat">
        <p><a href="<?php echo ITFPATH; ?>">Home</a> / <a href="#">Teacher Login</a></p>
    </div>
    <form id="sgn" name="teacherlogin" method="post" action="">
        <div class="full_width_page">
            <div id="page_title">
                <h1>Login</h1>
            </div>
            <div id="page_content">
                <div class="teacher_login_page">
                    <div id="title_teacher_login"><h2>Teacher Login</h2></div>
                    <form action="" method="post">
                        <div id="form_label">
                            <label>Enter your Email Id<span>*</span></label>
                            <input type="text" name="email" id="email"value="" />
                        </div>
                        <div id="form_label">
                            <label>Password<span>*</span></label>
                            <input type="text" name="password" id="password" value="" />
                        </div>
                        <div id="btn_button">
                            <input class="button_btn_all" type="submit" name="LOGIN" value="LOGIN" />
                        </div>
                        <div id="forget_password">
                            <a href="<?php echo CreateLink(array('teacherlogin', 'itemid' => 'forgot')); ?>">Forgot Password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- end main_wrapper Section -->
</div><!-- end main_container -->
