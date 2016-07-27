<?php
if (!empty($_SESSION['teacherLoginInfo']['user_id'])) {
// CreateLink(array("teacherdashboard"));

    redirectUrl(CreateLink(array("teacher")));
}
if (isset($_POST["email"])) {
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
$validJs = TemplateUrl() . "js2/jquery.validate.js";
?>
<script src="<?php echo $validJs ?>"></script>
<script>
    $(document).ready(function() {

        $('#frm').validate({// initialize the plugin
            errorElement: "span",
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                email: "Please enter a valid email address",
                password: "Please enter a Password"

            }
        });
});
</script>
<!-- _________________________main_wrapper Section_________________________ -->    
<div class="main_wrapper" id="mid_wrapper">
    <!--<div class="main_mat">
        <p><a href="<?php echo ITFPATH; ?>">Home</a> / <a href="#">Teacher Login</a></p>
    </div>-->
    <form id="frm" name="frm" method="post" action="">
        <div class="full_width_page">
            <div id="page_title">
                <h1>Login</h1>
            </div>
           
                <div id="page_content">

                    <div class="teacher_login_page">

                        <div id="title_teacher_login"><h2>Teacher Login</h2></div>

                        <div id="form_label">
                            <label>Enter your Email Id<span>*</span></label>
                            <input type="text" name="email" id="email"value="" />
                        </div>
                        <div id="form_label">
                            <label>Password<span>*</span></label>
                            <input type="password" name="password" id="password" value="" />
                        </div>
                        <div id="btn_button">
                            <input class="button_btn_all" type="submit" name="submit" value="LOGIN" />
                        </div>
                        <div id="forget_password">
                            <a href="<?php echo CreateLink(array('teacherlogin', 'itemid' => 'forgot')); ?>">Forgot Password?</a>
                        </div>
                    </div>


                </div>
            </form>
        </div>
    </form>
</div>
<!-- end main_wrapper Section -->
</div><!-- end main_container -->