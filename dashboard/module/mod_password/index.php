<?php
$userobj=new User();
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$pagetitle="Change Password";
$ItfInfoData = $userobj->Get_User($_SESSION['LoginInfo']['USERID']);

include(BASEPATHS."/fckeditor/fckeditor.php");
?>


<script type="text/javascript">

    $(document).ready(function() {

        var Validator = jQuery('#itffrminput').validate({
            rules: {

                oldpassword: "required",
                newpassword: {required:true},
                confirmpassword: {required:true,equalTo:"#newpassword"}

            },
            messages: {

                oldpassword: "Please enter old password.",
                newpassword:{required:"Please enter new password."},
                confirmpassword:{required:"Please enter confirm password."}

            }
        });
    });
</script>
<?php
if(isset($_POST['id']))
{

    if(!empty($_POST['id']))
    {
        if($userobj->ChangePassword($_POST)){
            flash("Password is successfully updated");
            redirectUrl("itfmain.php?itfpage=".$currentpagenames);
        } else {
            flash("Old Password doesn't match !","2");
            redirectUrl("itfmain.php?itfpage=".$currentpagenames);
        }

    }
}
?>
<div class="full_w">
    <!-- Page Head -->
    <div class="h_title"><?php echo $pagetitle;?></div>

    <form action="" method="post" name="itffrminput" id="itffrminput">
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id'])?$ItfInfoData['id']:'' ?>" />

            <div class="element">
                <span class="req">&nbsp;</span>
                <label>Old Password<span class="red">(required)</span></label>
                <input class="text" name="oldpassword" type="password"  id="oldpassword" size="35" value="<?php echo isset($ItfInfoData['oldpassword'])?$ItfInfoData['oldpassword']:'' ?>" />
            </div>

            <div class="element">
                <span class="req">&nbsp;</span>
                <label>New Password<span class="red">(required)</span></label>
                <input class="text" name="newpassword" type="password"  id="newpassword" size="35" value="<?php echo isset($ItfInfoData['newpassword'])?$ItfInfoData['newpassword']:'' ?>" />
            </div>

            <div class="element">
                <span class="req">&nbsp;</span>
                <label>Confirm New Password<span class="red">(required)</span></label>
                <input class="text" name="confirmpassword" type="password"  id="confirmpassword" size="35" value="<?php echo isset($ItfInfoData['confirmpassword'])?$ItfInfoData['confirmpassword']:'' ?>" />
            </div>

            <!-- Form Buttons -->
            <div class="entry">
                <button type="submit">Submit</button>
                <button type="button" onclick="history.back()">Back</button>
            </div>
            <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>