<?php

if(isset($_POST['id']))
{

	$userids = $_POST['id'];
        if(!empty($_POST['id']))
	{
	
            $obj->admin_updateSummerCampClass($_POST);
		flash($pagetitle." is successfully Updated");
	}          
	else
	{
		$obj->admin_addSummerCampClass($_POST);
		flash($pagetitle." is successfully added");
	}
	$urlname = CreateLinkAdmin(array($currentpagenames,"parentid"=>$parentids));
	redirectUrl($urlname);
}
$ids=isset($_GET['id'])?$_GET['id']:'';
$InfoData = $obj->CheckSummerCampClass($ids);
//print_r($InfoData);

//$categoryobj = new Category();
//$course=$obj->showAllCourse1();
$locObj = new Class1();
$location=$locObj->showAllLocation();
//print_r($location);
//$categories = $categoryobj->showCategoriesList(0);
//$categories = $categoryobj->showAllCategoryMy();
//$priceCat=$categoryobj->showAllPriceCategory();
$object = new Class1();
  $link=$object->showAlllinkCat();
//print_r($link);die;


$campcode=$obj->showAllCampCode();


?>

<script>
$(document).ready(function(){
  $("#link_type").change(function(){
    if( $(this).val() == 'external')
      $("#link1").show();
    else
      $("#link1").hide();
  });
 });
</script>

<script type="text/javascript">
$(document).ready(function() {
	$("#itffrminput").validate({
	rules: {
			course_code: "required",
                        location_code : "required",
                        class_code:"required"
                     
                       
		},
		messages: {
			course_code: "Please enter <?php echo $pagetitle; ?> course type",
                        location_code: "Please enter <?php echo $pagetitle; ?> course type",
                        class_code: "Please enter <?php echo $pagetitle; ?> course type"
			}
	});
});
</script>

<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo !empty($InfoData['id'])?$InfoData['id']:''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />
    
       
        <div class="element">
        <label>Summer Camp Code<span class="red">(required)</span></label>
      <select name="summercamp_class_code" id="course_code" class="err">
            <option value="">-- select Course Code--</option>
               <?php foreach($campcode as $courseCode) {    ?>
          <option value="<?php echo $courseCode['camp_code'] ?>" <?php if($courseCode['id'] == $fInfoData["id"]){ echo"selected";} ?>><?php echo ucfirst($courseCode['camp_code']); ?></option>
 <?php } ?>
        </select>
    </div>                   
 <div class="element">
            <label>Location Code<span class="red">(required)</span></label>
             <select name="location_code" id="location_code" class="err">
            <option value="">-- select Location Code--</option>
               <?php foreach($location as $loc) {    ?>
           <option value="<?php echo $loc['loc_code'] ?>" <?php if($loc['id'] == $fInfoData["id"]){ echo"selected";} ?>><?php echo ucfirst($loc['loc_code']); ?></option>      
 <?php } ?>
        </select>            
        </div>
        
         <div class="element">
            <label> Summer Camp Class Code</label>
            <input class="field size1"  name="summercamp_code" type="text"  id="summercamp_code" size="35" value="<?php echo isset($InfoData['summercamp_code'])?$InfoData['summercamp_code']:'' ?>" />
        </div>
             <div class="element">
            <label>Start Date</label>
            <input class="field size1 tcal" autocomplete="off" name="start_date" type="text"  id="start_date" size="35" value="<?php echo isset($InfoData['start_date'])?$InfoData['start_date']:'' ?>" />
        </div>
        <div class="element">
            <label>End Date</label>
            <input class="field size1 tcal"   autocomplete="off" name="end_date" type="text"  id="end_date" size="35" value="<?php echo isset($InfoData['end_date'])?$InfoData['end_date']:'' ?>" />
        </div>
        
        <div class="element">
             <label>Day(s) of week</label>
            <input class="field size1"  name="day_of_week" type="text"  id="day_of_week" size="35" value="<?php echo isset($InfoData['day_of_week'])?$InfoData['day_of_week']:'' ?>" />
        </div>
        <div class="element">
            <label>Class Time</label>           
            <input class="field size1"  name="class_time" type="text"  id="class_time" size="35" value="<?php echo isset($InfoData['class_time'])?$InfoData['class_time']:'' ?>" />
        </div>
           <div class="element">
            <label>Duration</label>
            <input class="field size1"  name="duration" type="text"  id="duration" size="35" value="<?php echo isset($InfoData['duration'])?$InfoData['duration']:'' ?>" />
        </div>        
        <div class="element">
            <label>Number of Class</label>
            <input class="field size1"  name="no_of_class" type="text"  id="no_of_class" size="35" value="<?php echo isset($InfoData['no_of_class'])?$InfoData['no_of_class']:'' ?>" />
        </div>
        
         <div class="element">
            <label>Start Eligibility</label>           
            <input class="field size1"  name="start_eligibility_grade" type="text"  id="start_eligibility_grade" size="35" value="<?php echo isset($InfoData['start_eligibility_grade'])?$InfoData['start_eligibility_grade']:'' ?>" />
        </div>
        <div class="element">
            <label>End Eligibility</label>
           <input class="field size1"  name="end_eligibility_grade" type="text"  id="end_eligibility_grade" size="35" value="<?php echo isset($InfoData['end_eligibility_grade'])?$InfoData['end_eligibility_grade']:'' ?>" />
        </div>
        
        <div class="element">
            <label>Notes</label>
           <input class="field size1"  name="notes"type="text"  id="notes" size="35" value="<?php echo isset($InfoData['notes'])?$InfoData['notes']:'' ?>" />
        </div>
        
         <div class="element">
            <label>Registration type</label>
            <input class="field size1"  name="registration_type" type="text"  id="registration_type" size="35" value="<?php echo isset($InfoData['registration_type'])?$InfoData['registration_type']:'' ?>" />
        </div>
                       
            <div class="element">
        <label>Link Type<span class="red">(required)</span></label>
        <select name="link_type" class="link_type"  id="link_type">
            <option value="">--link Type --</option>
          <?php  foreach($link as $linkType) {  ?>
            <option value="<?php echo $linkType['link_type'] ?>" <?php if($linkType['id'] == $fInfoData["id"]){ echo"selected";} ?>><?php echo $linkType['link_type']; ?></option>
        <?php } ?>           
        </select> 
          <input type="text" id="link1" name="link1" style="display:none" size="35" value="">           
        </div>   
                  
           
                 
        <div class="element">
            <label>Single Payment</label>
            <input class="field size1"  name="single_pay_amt" type="text"  id="single_pay_amt" size="35" value="<?php echo isset($InfoData['single_pay_amt'])?$InfoData['single_pay_amt']:'' ?>" />
        </div>
        
        <div class="element">
        <label>Installments Booking Amount<span class="red">(required)</span></label>
           <input class="field size1"  name="installment_booking_amt" type="text"  id="installment_booking_amt" size="35" value="<?php echo isset($InfoData['installment_booking_amt'])?$InfoData['installment_booking_amt']:'' ?>" />
        </div>   
                
        <div class="element">
            <label>installment amount</label>
            <input class="field size1"  name="installment_amt" type="text"  id="installment_amt" size="35" value="<?php echo isset($InfoData['installment_amt'])?$InfoData['installment_amt']:'' ?>" />
        </div>
         <div class="element">
            <label>Number of Installment</label>
            <input class="field size1"  name="no_of_installment" type="text"  id="no_of_installment" size="35" value="<?php echo isset($InfoData['no_of_installment'])?$InfoData['no_of_installment']:'' ?>" />
        </div>
         <div class="element">
            <label>Installment Start Date</label>
            <input class="field size1 tcal" autocomplete="off"  name="installment_start_date" type="text"  id="installment_start_date" size="35" value="<?php echo isset($InfoData['installment_start_date'])?$InfoData['installment_start_date']:'' ?>" />
        </div>
        <div class="element">
            <label>Registration Status</label>
            <input class="field size1"  name="registration_status" type="text"  id="registration_status" size="35" value="<?php echo isset($InfoData['registration_status'])?$InfoData['registration_status']:'' ?>" />
        </div>
         <div class="element">
            <label>Teacher Assigned</label>                    
            
            <input class="field size1"  name="teacher_assigned" type="text"  id="teacher_assigned" size="35" value="<?php echo isset($InfoData['teacher_assigned'])?$InfoData['teacher_assigned']:'' ?>" />
        </div>      
<!--              Form Buttons -->
        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>
