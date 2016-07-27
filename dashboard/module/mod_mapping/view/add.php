<?php
if(isset($_POST['id']))
{
	if(!empty($_POST['id']))
	{
		$userobj->user_update($_POST);
		flash("Teacher is succesfully updated");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
	else
	{
            //print_r($_POST);
		$userobj->mapping_add($_POST);
		flash("Teacher is succesfully added");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
}

$ids=isset($_GET['id'])?$_GET['id']:'';
$ItfInfoData = $userobj->getUserInfo($ids);
$techObj = new Class1();
$tech=$techObj->showAllTeacherlist();
$courseObj= new Camp();
$camp=$courseObj->showAllCampList();
//echo "<pre>";
//print_r($camp);
//echo "<pre>";
$allclass=$techObj->showAllCourselist();
//print_r($allclass);
//$newResult= array_merge($array1)
?>
<script type="text/javascript">
$(document).ready(function() {

    $.validator.addMethod("noSpace", function(value, element) {

        var resinfo = parseInt(value.indexOf(" "));

        if(resinfo == 0 && value !="") { return false; } else return true;

    }, "Space are not allowed as first string !");

    var Validator = jQuery('#itffrminput').validate({
        rules: {

            name:{required:true, maxlength:'100', noSpace: true},
            business_phone:{required:true, noSpace: true},
            email:{required:true,email:true,
                <?php if(empty($ids)) { ?>
                remote: {
                    url: "<?php echo SITEURL; ?>/itf_ajax/index.php",
                    type: "post",
                    data: {
                        emailid: function() {
                            return $( "#email" ).val();
                        }
                    }
                }
               <?php } ?>
			
			},
            <?php if(empty($ids)) { ?>
           'password': {
                required: true
            },
            <?php } ?>
           'password2': {
               <?php if(empty($ids)) { ?>
                required: true,
               <?php } ?>
                equalTo: '#password'
            },

        },
		messages: {
			
			name: " Please enter teacher name",

			email:" Please enter valid email address",
                                                     
                                                     business_phone: " Please enter Phone no",
			
		}
    });
});
</script>


<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" >
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['user_id'])?$ItfInfoData['user_id']:'' ?>" />
        <input type="hidden" name="usertype" value="3" />

        <div class="element">
            <label>Teacher Name<span class="red">(required)</span></label>            
             <?php $course=$InfoData['course'];?>
                <select name="t_id" id="c_name" class="err">                    
                <option value="">---Please Select Teacher----</option>
                <?php foreach ($tech as $techList){ 
                    $techid='';
                    $techid=$techList['id'];
                    ?> 
                <option value="<?php echo $techList['id'] ?>" <?php if($techid==$teachers){ ?> selected="selected" <?php } ?> >  <?php echo $techList['name'].">>".$techList['email']; ?> </option>
                 <?php }?>
                 </select>
                </div>    
     <?php // print_r($allclass);die; ?>
        <div class="element">
            <label>Course <span class="red">(required)</span></label>
            <select name="c_id" id="c_id" class="err">
                <option value="">---Please Select Course----</option>
                <?php foreach ($allclass as $courseList){ 
                    $techid='';
                    $techid=$courseList['id'];
                    print_r($techid);                 
                    ?> 
                <option value="<?php echo $courseList['id'] ?>" <?php if($techid==$teachers){ ?> selected="selected" <?php } ?> >  <?php echo $courseList['course']; ?> </option>
                 <?php }?>
                 </select>
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