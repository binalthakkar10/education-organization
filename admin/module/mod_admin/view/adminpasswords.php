<?php
if(isset($_POST['id']))
{
	if($userobj->ChangePassword())
	flash("Password is successfully updated");
	else
	flash("Old password is not matched","2");	
	redirectUrl("itfmain.php?itfpage=adminuser");
}
$UserInfo = $userobj->CheckUser($_SESSION['LoginInfo']['USERID']);
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#frmadmin").validate({
		rules: {
			
			adminpass:"required",
			adminpass1:{required: true},
			adminpass2:{required: true,equalTo:"#adminpass1"}
		},
		messages: {
			adminpass:"Please enter old password",
			adminpass1:"Please enter new password",
			adminpass2:"Confirm password is not matched"
			
		}
	});	
});
</script>

<div class="box">
					<!-- Box Head -->
					<div class="box-head">
						<h2><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?> </h2>
					</div>
					<!-- End Box Head -->
		
<form name="frmadmin" id="frmadmin" method="post" action="" >
<input name="id" type="hidden" id="id" value="r"/>
		
	<div class="form">
<p>
	<span class="req">&nbsp;</span>
	<label>Old Password <span>*</span></label>
	<input class="field size3" name="adminpass" type="password" id="adminpass" value=""/>
</p>

<p>
	<span class="req">&nbsp;</span>
	<label>New Password<span>*</span></label>
	<input class="field size3" name="adminpass1" type="password" id="adminpass1" value=""/>	
</p>

<p>
	<span class="req">&nbsp;</span>
	<label>Old Password<span>*</span></label>
	
	 <input class="field size3" name="adminpass2" type="password" id="adminpass2" value=""/>
</p>

		
</div>
<div class="buttons">
	<input type="submit" class="button" value="submit" />
</div>

</form>		
</div>