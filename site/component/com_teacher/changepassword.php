<?php
if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
    redirectUrl(CreateLink(array("teacherlogin", "msg" => 'na')));
}
$obj = new User();

if (isset($_POST["current_password"])) {
    if ($_POST["password1"] != '' && $_POST["password2"] != '' && $_POST["current_password"] != '') {
        if ($_POST["password1"] == $_POST["password2"]) {
            $checkCurrentPassword = $obj->checkTeacherPassword($_SESSION['teacherLoginInfo']['user_id'], $_POST["current_password"]);
            if ($checkCurrentPassword) {
                if ($forgot = $obj->changeTeacherPassword($_SESSION['teacherLoginInfo']['user_id'], $_POST["password1"])) {
                    flashMsg("Your password has been changed","2");
                } else {
                    flashMsg("No user exist related with this email address !", "2");
                }
            } else {
                flashMsg("Current Password is not matched", "2");
            }
        } else {
            flashMsg("Confirm Password is not matched", "2");
        }
    } else {
        flashMsg("Please fill up manadatory fields", "2");
    }
}
$validJs = TemplateUrl() . "js2/jquery.validate.js";
?>
<style>
    .n_error p {
    text-align: center;
}

</style>
<script src="<?php echo $validJs ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm").validate({
            errorElement: "span",
            rules: {
                current_password: {required: true
                },
                password1:
                        {required: true,
                            minlength: 6,
                            maxlength: 20
                        },
                password2: {required: true, equalTo: "#password1"}
            },
            messages: {
                current_password: "Please Enter Old Password",
                password1: {
                    required: "Please Enter a New Password",
                    minlength: "At Least 6 Characters",
                    maxlength: "Maximum 50 Characters"
                },
                password2: "Password is Not Matched"

            }
        });
    });
</script>
<div class="main_wrapper" id="mid_wrapper">
    <!--<div class="main_mat">
        <p><a href="<?php echo ITFPATH; ?>">Home</a> / <a href="#">Change Password</a></p>
    </div>-->
    <form id="frm" name="frm" method="post" action="">
        <div class="full_width_page">
            <div id="page_title">
                <h1>Change Password</h1>
            </div>
            <div id="page_content">
                <?php include_once('left_menu.php') ?>
                <div class="teacher_login_page">
                    <div id="title_teacher_login"><h2>Change Password</h2></div>
                    <form action="" method="post">
                        <div id="form_label">
                            <label>Your Current Password:<span>*</span></label>
                            <input type="password" name="current_password" id="current_password"value=""  tabindex="1"/>
                        </div>
                        <div id="form_label">
                            <label>New Password:<span>*</span></label>
                            <input type="password" name="password1" id="password1"value="" tabindex="2" />
                        </div>
                        <div id="form_label">
                            <label>Confirm Password:<span>*</span></label>
                            <input type="password" name="password2" id="password2" value=""  tabindex="3"/>
                        </div>
                        <div id="btn_button">
                            <input class="button_btn_all" type="submit" name="submit" value="Submit" tabindex="4" />
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- end main_wrapper Section -->
</div><!-- end main_container -->
