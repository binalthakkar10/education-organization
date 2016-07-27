<?php
$Userss=new User();

if(isset($_REQUEST['emailid']))
{
	if($Userss->forgotPasswordAdmin($_REQUEST['emailid']))
	{
		flash("Your new password has been sent to your mail address");
		redirectUrl('index.php');
	}
	else
	{
		flash("No user exist related with this email address",2);
	}

}

?>
<script type="text/javascript">

$(document).ready(function() {

    var Validator = jQuery('#itflogin').validate({
        rules: {
            emailid: {required:true,email:true}
			},
		
		messages: {
			
			emailid: " Please enter valid email address"	
		},
        errorPlacement: function(error, element) {
            error.appendTo( element.parent().prev() );
        },
        success: function(label) {
            label.html("&nbsp;").addClass("itfok");
        }
    });
});
</script>
<form id="itflogin" name="itflogin" method="post" action="" >

<p>Email Address*</p>

<p><input name="emailid" type="text" id="emailid" class="form-login formminp" /></p>

<button type="submit" class="ok">Forgot Password</button>

<a href="index.php" class="button" >Go to login page</a>


 </form>
