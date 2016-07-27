<?php


if(isset($_POST['id']))
{
    
   // print_r(date($_POST['start_date']));die;

    $userids = $_POST['id'];
	if(!empty($_POST['id']))
	{
	//echo 'Aniket';exit;	
            $obj->admin_updateCamp($_POST);
		flash($pagetitle." is successfully Updated");
	}          
	else
	{
            
		$obj->admin_addCamp($_POST);
		flash($pagetitle." is successfully added");
	}
	$urlname = CreateLinkAdmin(array($currentpagenames,"parentid"=>$parentids));
	redirectUrl($urlname);
}

$ids=isset($_GET['id'])?$_GET['id']:'';
$InfoData = $obj->CheckCamp($ids);
$categoryobj = new Category();
//$categories = $categoryobj->showCategoriesList(0);
$categories = $categoryobj->showAllCategoryMy();
//$priceCat=$categoryobj->showAllPriceCategory();

?>
<script>
$(document).ready(function(){
  $("#payment_type").change(function(){
    if( $(this).val() == 'recurring')
      $("#payment_type_rec1").show();
    else
      $("#payment_type_rec1").hide();
  });
});
</script>


<script type="text/javascript">
$(document).ready(function() {
	$("#itffrminput").validate({
	rules: {
		camp_for : "required",	
                camp_code: "required",
                program_name: "required",
                payment_type: "required",
                duration : "required",
                base_fee:"required"
		},
		messages: {
			camp_for: "Please select type",
                        camp_code: "Please enter program",
                        program_name: "Please enter program",
                        payment_type: "Please select price  ",
                        duration:"Please enter time duration",
                        base_fee:"Please enter base_fee"
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
        <label>Type<span class="red">(required)</span></label>
        <select name="camp_for" id="camp_for" class="err">
            <option value="">-- Select Type --</option>
               <?php foreach($categories as $cat) {    ?>
            <option value="<?php echo $cat['catname'] ?>" <?php if($cat['id'] == $fInfoData["id"]){ echo"selected";} ?>><?php echo ucwords($cat['catname']); ?></option>
        
 <?php } ?>
        </select>
    </div>           
                
        <div class="element">
            <label>Code</label>
            <input class="field size1"  name="camp_code" type="text"  id="camp_code" size="35" value="<?php echo isset($InfoData['camp_code'])?$InfoData['camp_code']:'' ?>" />
         </div>
        
        
 <div class="element">
            <label>Name<span class="red">(required)</span></label>
             <input class="field size1"  name="program_name" type="text"  id="program_name" size="35" value="<?php echo isset($InfoData['program_name'])?$InfoData['program_name']:'' ?>" />
        </div>       
        
        
            <div class="element">
            <label> Description</label>
            <input class="field size1"  name="program_description" type="text"  id="program_description" size="35" value="<?php echo isset($InfoData['program_description'])?$InfoData['program_description']:'' ?>" />
        </div>
            
<!--         <div class="element">
            <label>Eligibility</label>
            <input class="field size1"  name="eligibility" type="text"  id="eligibility" size="35" value="<?php echo isset($InfoData['eligibility'])?$InfoData['eligibility']:'' ?>" />
        </div>
        <div class="element">
            <label>Age Group</label>
            <input class="field size1"  name="age_group" type="text"  id="age_group" size="35" value="<?php echo isset($InfoData['eligibility'])?$InfoData['eligibility']:'' ?>" />
        </div>
            <div class="element">
            <label>Number of Class</label>
            <input class="field size1"  name="no_of_class" type="text"  id="no_of_class" size="35" value="<?php echo isset($InfoData['no_of_class'])?$InfoData['no_of_class']:'' ?>" />
        </div>
         <div class="element">
            <label>Room</label>
            <input class="field size1"  name="room" type="text"  id="room" size="35" value="<?php echo isset($InfoData['room'])?$InfoData['room']:'' ?>" />
        </div>
       <div class="element">
            <label>Start Date</label>
            <input class="field size1"  name="start_date" type="text"  id="start_date" size="35" value="<?php echo isset($InfoData['start_date'])?$InfoData['start_date']:'' ?>" />
        </div>
        <div class="element">
            <label>End Date</label>
            <input class="field size1"  name="end_date" type="text"  id="end_date" size="35" value="<?php echo isset($InfoData['end_date'])?$InfoData['end_date']:'' ?>" />
        </div>
         <div class="element">
             <label>Day(s) of week</label>
            <input class="field size1"  name="day_of_week" type="text"  id="day_of_week" size="35" value="<?php echo isset($InfoData['day_of_week'])?$InfoData['day_of_week']:'' ?>" />
        </div>
        
        <div class="element">
            <label>Duration</label>
            <input class="field size1"  name="duration" type="text"  id="duration" size="35" value="<?php echo isset($InfoData['duration'])?$InfoData['duration']:'' ?>" />
        </div>        
        <div class="element">
            <label>Base Fee</label>
            <input class="field size1"  name="base_fee" type="text"  id="base_fee" size="35" value="<?php echo isset($InfoData['course_price'])?$InfoData['course_price']:'' ?>" />
        </div>
        <div class="element">
        <label>Price Type<span class="red">(required)</span></label>
        <select name="payment_type" class="paymentType"  id="payment_type">
            <option value="">--Price Type --</option>
           <?php foreach($priceCat as $priceType) {  ?>
                <option value="<?php echo $priceType['price_type'] ?>" <?php if($priceType['id'] == $fInfoData["id"]){ echo"selected";} ?>><?php echo $priceType['price_type']; ?></option>
        <?php } ?>
        </select> 
         <input type="text" id="payment_type_rec1" name="payment_type_rec1" style="display:none" value="">           
        </div>   
                
        <div class="element">
            <label>Country</label>
            <input class="field size1"  name="country" type="text"  id="country" size="35" value="<?php echo isset($InfoData['country'])?$InfoData['country']:'' ?>" />
        </div>
         <div class="element">
            <label>Zipcode</label>
            <input class="field size1"  name="zipcode" type="text"  id="zipcode" size="35" value="<?php echo isset($InfoData['zipcode'])?$InfoData['zipcode']:'' ?>" />
        </div>
        <div class="conta">
                <label>Address <span style="color:red;font-size: 15px;">*</span>:</label>
                <input name="address" id="address"  size="35" type="text" value="<?php echo isset($ItfInfoData['address'])?$ItfInfoData['address']:'' ?>"/>
                <div class="clear"></div>
               </div>
       
<div class="conta">          
    <div id="map_canvas" style="width:455px; height:350px; margin-left:175px;"></div>
    <div class="clear"></div>
           </div>
        
         <div class="conta">
    <label>latitude : </label>
    <input id="latitude" name="latitude" type="text" value="<?php echo isset($InfoData['latitude'])?$InfoData['latitude']:'' ?>"/>
    <div class="clear"></div>
     </div>
        <div class="conta">
    <label>longitude : </label>
    <input id="longitude" name="longitude" type="text" size="35" value="<?php echo isset($InfoData['longitude'])?$InfoData['longitude']:'' ?>"/>
    <div class="clear"></div>
            </div>-->
             <!-- Form Buttons -->
        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>
