<?php
if(isset($_POST['id']))
{
	$userids=$_POST['id'];
	if(!empty($_POST['id']))
	{
		$categoryobj->admin_updateAdvertise($_POST);
		flash("Advertise is successfully Updated");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
	else
	{
		$categoryobj->admin_addAdvertise($_POST);
		flash("Advertise is successfully added");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
}

$ids=isset($_GET['id'])?$_GET['id']:'';
$InfoData = $categoryobj->CheckAdvertise($ids);
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#frmcategory").validate({
	rules: {
			name: "required"
		},
		messages: {
			name: "Please enter advertise name"
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
		

		<form name="frmcategory" id="frmcategory" method="post" action="" enctype="multipart/form-data">
		<input name="id" type="hidden" id="id" value="<?php echo !empty($InfoData['id'])?$InfoData['id']:''; ?>" />

	<div class="form">
<p>
	<span class="req">&nbsp;</span>
	<label>Advertise Name <span>*</span></label>
	<input class="field size1"  name="name" type="text"  id="name" size="35" value="<?php echo isset($InfoData['name'])?$InfoData['name']:'' ?>" />	
</p>

<p>
	<span class="req">&nbsp;</span>
	<label>Advertise Image <span>*</span></label>
	<div id="FileUpload">
    <input type="file" size="24" id="bannerimage" name="bannerimage" class="BrowserHidden" onchange="getElementById('tmp_bannerimage').value = getElementById('bannerimage').value;" />
    <div id="BrowserVisible"><input type="text" id="tmp_bannerimage" class="FileField" /></div>
</div>
	

</p>
</div>
<div class="buttons">
	<input type="submit" class="button" value="submit" />
	<input type="button" class="button" value="back" onclick="history.back()" />
</div>
</form>		
</div>