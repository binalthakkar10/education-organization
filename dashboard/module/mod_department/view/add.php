<script type="text/javascript">
$(document).ready(function() {
	$("#frmcategory").validate({
	rules: {
			catname: "required"
		},
		messages: {
			catname: "Please enter category name"
			}
	});
});
</script>
<?php
if(isset($_POST['id']))
{
	$userids=$_POST['id'];
	if(!empty($_POST['id']))
	{
		$objmodule->adminUpdate($_POST);
		flash("Faculty is successfully Updated");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
	else
	{
		$objmodule->adminAdd($_POST);
		flash("Faculty is successfully added");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
}

$objfaculty=new Faculty();
$allfaculty=$objfaculty->getActiveData();
//echo "<pre>"; print_r($allfaculty);die;



$ids=isset($_GET['id'])?$_GET['id']:'';
$InfoData = $objmodule->checkData($ids);
?>
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
	<label>Faculty <span>*</span></label>
		<?php 
			$facultyid=isset($InfoData['faculty_id'])?$InfoData['faculty_id']:'';
			echo Html::ComboBox("faculty_id",Html::CovertSingleArray($allfaculty,"id","title"),$facultyid,array("class"=>"field size1")); 
		?>
	</p>
	
<p>
	<span class="req">&nbsp;</span>
	<label>Title <span>*</span></label>
	<input class="field size1"  name="title" type="text"  id="title" size="35" value="<?php echo isset($InfoData['title'])?$InfoData['title']:'' ?>" />	
</p>
</div>
<div class="buttons">
	<input type="submit" class="button" value="submit" />
	<input type="button" class="button" value="back" onclick="history.back()" />
</div>
</form>		
</div>