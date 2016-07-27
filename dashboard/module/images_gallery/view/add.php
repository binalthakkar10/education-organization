    <?php
if(isset($_POST['id']))
{
	$userids = $_POST['id'];
	if(!empty($_POST['id']))
	{
		$obj->admin_updateGrade($_POST);
		flash($pagetitle." is successfully Updated");
	}
	else
	{
		$obj->admin_addGrade($_POST);
		flash($pagetitle." is successfully added");
	}
	
	$urlname = CreateLinkAdmin(array($currentpagenames,"parentid"=>$parentids));
	redirectUrl($urlname);
}

$ids=isset($_GET['id'])?$_GET['id']:'';
echo $InfoData = $obj->CheckGrade($ids);

?>
 
<script type="text/javascript">
$(document).ready(function() {
	$("#itffrminput").validate({
	rules: {
			grade: "required"                                         
                     
                       
		},
		messages: {
			name: "Please enter <?php echo $pagetitle; ?> grade"
                       
			}
	});
});
</script>

<style>
    .ui-autocomplete {
	background-color: white;
	width: 300px;
	border: 1px solid #cfcfcf;
	list-style-type: none;
	padding-left: 0px;
}

.add_field,.remove_field{
	background-color: #d3d3d3;
	width: 20px;
	height: 20px;
	display: inline-block;
	text-align: center;
	color: #0033ff;
	font-size: 19px;
	cursor: pointer;
}

.input_holder input{
	display:block;
}

.add_field1,.remove_field1{
	background-color: #d3d3d3;
	width: 20px;
	height: 20px;
	display: inline-block;
	text-align: center;
	color: #3E3E3E;
	font-size: 19px;
	cursor: pointer;
}

.input_holder1 input{
	display:block;
}
</style>




<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo !empty($InfoData['id'])?$InfoData['id']:''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />

 <div class="element">
            <label>Grade<span class="red">(required)</span></label>
            <input class="field size1"  name="grade" type="text"  id="grade" size="35" value="<?php echo isset($InfoData['grade'])?$InfoData['grade']:'' ?>" />
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