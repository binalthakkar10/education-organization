<?php
$obj = new User();
$checkUrl = $obj->checkGeneratePasswordUrl($_GET['checkinfo']);
if (!$checkUrl) {
    flashMsg("You are not authorize to view this page", "2");

    redirectUrl(CreateLink(SITEURL));
    exit;
}

if (isset($_POST["password1"])) {
    if ($_POST["password1"] != '' && $_POST["password2"] != '') {
        if ($_POST["password1"] == $_POST["password2"]) {
            $obj->updatePassword($checkUrl, $_POST["password1"]);
            flashMsg("Password is updated successfully", "2");
        } else {
            flashMsg("Confirm Password is not matched", "2");
        }
    } else {
        flashMsg("Please fill up manadatory fields", "2");
    }
}
$validJs = TemplateUrl() . "js2/jquery.validate.js";
?>
<script src="<?php echo $validJs ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm").validate({
            errorElement: "span",
            rules: {
                password1: {required: true, minlength: 6,
                    maxlength: 20},
                password2: {required: true,
                     equalTo: "#password1"}
            },
            messages: {
                password1: {
                    required: "Please enter a Password",
                    minlength: "Password must consist of at least 6 characters",
                    maxlength: "Password must consist of maximum 20 characters"
                },
               password2:"Confirm Password is not matched"
            }
        });
    });
</script>
<div class="main_wrapper" id="mid_wrapper">

    <div class="full_width_page">
        <div id="page_title">
            <h1>Generate Password</h1>
        </div>
        <div id="page_content">
            <?php include_once('left_menu.php') ?>
            <form id="frm" name="frm" method="post" action="">
                <div class="teacher_login_page">
                    <div id="title_teacher_login"><h2>New Password</h2></div>

                    <div id="form_label">
                        <label>New Password<span>*</span></label>
                        <input type="password" name="password1" id="password1"value="" />
                    </div>
                    <div id="form_label">
                        <label>Confirm Password<span>*</span></label>
                        <input type="password" name="password2" id="password2" value="" />
                    </div>
                    <div id="btn_button">
                        <input class="button_btn_all" type="submit" name="submit" value="submit" />
                    </div>

                </div>
            </form>

        </div>
    </div>
</form>
</div>
<!-- end main_wrapper Section -->
</div><!-- end main_container -->
