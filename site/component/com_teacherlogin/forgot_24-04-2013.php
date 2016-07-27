<?php
$obj = new User();
if(isset($_POST["email"]))
{
    if(!empty($_POST["email"])){

        if($forgot = $obj->ForgotTeacherPassword($_POST["email"])){
            flashMsg("Your new password has been sent your mail address !");
            redirectUrl(CreateLink(array("teacherlogin")));
        } else {
            flashMsg("No user exist related with this email address !","2");
        }
    } else {
        flashMsg("Empty email not allowed !","2");
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
                }
            },
            messages: {
                email: "Please enter a valid email address"
            }
        });
});
</script>
<div class="main_wrapper" id="mid_wrapper">
    
    <form id="frm" name="frm" method="post" action="">
        <div class="full_width_page">
            <div id="page_title">
                <h1>Forgot Password</h1>
            </div>
            <div id="page_content">
                <div class="teacher_login_page">
                    <div id="title_teacher_login"><h2>Forgot Password</h2></div>
                    
                        <div id="form_label">
                            <label>Your Email Id<span>*</span></label>
                            <input type="text" name="email" id="email"value="" />
                        </div>
                        <div id="btn_button">
                            <input class="button_btn_all" type="submit" name="submit" value="Submit" />
                        </div>
                        <div id="forget_password">
                            <a href="<?php echo CreateLink(array('teacherlogin')); ?>">Go to login page</a>
                        </div>
                   
                </div>
            </div>
        </div>
    </form>
</div>
<!-- end main_wrapper Section -->
</div><!-- end main_container -->
