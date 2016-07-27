<script type="text/javascript">
$(document).ready(function() {
	$("#frmcategory").validate({
	rules: {
			title: "required"
		},
		messages: {
			title: "Please enter session name"
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
		flash("University is successfully Updated");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
	else
	{
		$objmodule->adminAdd($_POST);
		flash("University is successfully added");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
}

$objfaculty=new Faculty();
$allfaculty=$objfaculty->getActiveData();

$objdepartment=new Department();
$alldepartment=$objdepartment->getActiveData();

$facultydatas=Html::CovertArrayIndex($allfaculty,"id");
foreach($alldepartment as  $tmpdata)
{
	$facultydatas[$tmpdata["faculty_id"]]["faculty"]=$tmpdata;
}

//$alldeptdata=array();


//echo "<pre>"; print_r($facultydatas);die;



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
	<label>University Name<span>*</span></label>
	<input class="field size1"  name="title" type="text"  id="title" size="35" value="<?php echo isset($InfoData['title'])?$InfoData['title']:'' ?>" />	
</p>

<p>
	<span class="req">&nbsp;</span>
	<label>University Id<span>*</span></label>
	<input class="field size1"  name="uni_id" type="text"  id="uni_id" size="35" value="<?php echo isset($InfoData['uni_id'])?$InfoData['uni_id']:'' ?>" />	
</p>
 <p>
     
	<span class="req">&nbsp;</span>
	<label>Product Key<span>*</span></label>
		 <select class="field size1" name="product_key" id="product_key">
                     <?php $programid=isset($InfoData['product_key'])?$InfoData['product_key']:''; ?>
  <option value="">Select Product Key</option>
  <option value="0"<?php if($programid=="0"){echo "selected='selected'";} else{}?> >Disable</option>
  <option value="1" <?php if($programid=="1"){echo "selected='selected'";} else{}?>>Activate</option>
</select>   
	</p>

        
        <p>
	<span class="req">&nbsp;</span>
	<label>Check Exam Result<span>*</span></label>
		 <select class="field size1" name="chk_exam_result" id="chk_exam_result">
                     <?php $programid=isset($InfoData['chk_exam_result'])?$InfoData['chk_exam_result']:''; ?>
  <option value="">Select Product Key</option>
  <option value="0"<?php if($programid=="0"){echo "selected='selected'";} else{}?> >Disable</option>
  <option value="1" <?php if($programid=="1"){echo "selected='selected'";} else{}?>>Activate</option>
</select>   
	</p>
        
        
</div>
<div class="buttons">
	<input type="submit" class="button" value="submit" />
	<input type="button" class="button" value="back" onclick="history.back()" />
</div>
</form>		
</div>