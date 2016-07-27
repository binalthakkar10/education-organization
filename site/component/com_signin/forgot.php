<?php
$obj = new User();

if(isset($_POST["email"]))
{
    if(!empty($_POST["email"])){

        if($forgot = $obj->ForgotPassword($_POST["email"])){
            flashMsg("Your new password has been sent your mail address !");
            redirectUrl(CreateLink(array("signin")));
        } else {
            flashMsg("No user exist related with this email address !","2");
        }
    } else {
        flashMsg("Empty email not allowed !","2");
    }
}
	
?>


<div class="main_mat">
<p><a href="<?php echo ITFPATH; ?>">Home</a> / <a href=" ">Forgot Password</a></p>
</div>
<div class="sgn">
    <form id="sgn" name="signin" method="post" action="">
        <div class="adres">
            <label>User Name<span class="required">*</span></label>
            <input type="text" name="email">
            <div class="clear"></div>
        </div>
        <div class="forget">
            <a href="<?php echo CreateLink(array('signin')); ?>">Go to login page</a>
        </div>
        <div class="adres">
            <input type="submit" value="Submit">
            <div class="clear"></div>
        </div>
    </form>
</div>