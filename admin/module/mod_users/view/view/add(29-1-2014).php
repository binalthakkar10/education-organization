<?php

if(isset($_POST['id']))
{
   
	$userids = $_POST['id'];
	if(!empty($_POST['id']))
	{
		
            $obj->admin_updateCourse($_POST);
		flash($pagetitle." is successfully Updated");
	}          
	else
	{
		$obj->admin_addCourse($_POST);
		flash($pagetitle." is successfully added");
	}
	$urlname = CreateLinkAdmin(array($currentpagenames,"parentid"=>$parentids));
	redirectUrl($urlname);
}
$ids=isset($_GET['id'])?$_GET['id']:'';
$InfoData = $obj->CheckCourse($ids);
$categoryobj = new Category();
//$categories = $categoryobj->showCategoriesList(0);
$categories = $categoryobj->showAllCategoryMy();
$priceCat=$categoryobj->showAllPriceCategory();
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#itffrminput").validate({
	rules: {
			course_for: "required",
                        course_code : "required",
                        course : "required",
                        
                     
                       
		},
		messages: {
			course_for: "Please enter <?php echo $pagetitle; ?> course type",
                        course_code: "Please enter <?php echo $pagetitle; ?> course code",
                        course: "Please enter <?php echo $pagetitle; ?> course code",
                       
               
			}
	});
});
</script>

<!--<style>
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
</style>-->

<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo !empty($InfoData['id'])?$InfoData['id']:''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />
    
       
        <div class="element">
        <label>Course Type<span class="red">(required)</span></label>
        <select name="course_for" id="course_for" class="err">
            <option value="">-- Select Course for--</option>
               <?php foreach($categories as $cat) {    ?>
            <option value="<?php echo $cat['catname'] ?>" <?php if($cat['id'] == $fInfoData["id"]){ echo"selected";} ?>><?php echo ucwords($cat['catname']); ?></option>
        
 <?php } ?>
        </select>
    </div>    
        
        <div class="element">
            <label>Course Code<span class="red">(required)</span></label>
             <input class="field size1"  name="course_code" type="text"  id="course_code" size="35" value="<?php echo isset($InfoData['course_code'])?$InfoData['course_code']:'' ?>" />
        </div>
                
 <div class="element">
            <label>Course<span class="red">(required)</span></label>
             <input class="field size1"  name="course" type="text"  id="course" size="35" value="<?php echo isset($InfoData['course'])?$InfoData['course']:'' ?>" />
        </div>
        
         <div class="element">
            <label> Course Description</label>
            <input class="field size1"  name="course_description" type="text"  id="course_description" size="35" value="<?php echo isset($InfoData['course_description'])?$InfoData['course_description']:'' ?>" />
        </div>
            
      <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>
    