<?php
if(isset($_POST['id']))
{
	if(!empty($_POST['id']))
	{
		$userobj->member_update($_POST);
		flash("Membership plan is succesfully updated");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
	else
	{
		$userobj->memberadded($_POST);
		flash("Membership plan is succesfully added");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
        
        }
        
        $ids=isset($_GET['id'])?$_GET['id']:'';
        $ItfInfoData = $userobj->CheckMembership($ids);
        //echo "<pre>";print_r($ItfInfoData);die;
        


?>

<script type="text/javascript">

    $.validator.addMethod("noSpace", function(value, element) {

        var resinfo = parseInt(value.indexOf(" "));

        if(resinfo == 0 && value !="") { return false; } else return true;

    }, "Space not allowed in the starting of string !");

    $(document).ready(function() {

        $('#itffrminput').validate({
            rules: {
                type:{required:true},
              duration_day:{required:true},
              amount:{required:true},

            duration_type:{required:true},
           
            
            },
            messages: {
              amount:{required:"You must fill in all of the fields !"},
               duration_type: {required:"You must fill in all of the fields !" },
                type:{required:"You must fill in all of the fields !"},
              duration_day:{required:"You must fill in all of the fields !"}
                

            }
        });

    });
</script>


<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" >
             <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id'])?$ItfInfoData['id']:'' ?>" />

        <div class="element">
            <label>Membership Type<span class="red">(required)</span></label>
             <select class="cate" name="type">
                     <option value="">-- select Membership Type --</option>
                     
                     <option value="Customer"<?php if($ItfInfoData['type']=="Customer"){echo "selected='selected'";}else{} ?>>Customer</option>
                     <option value="Supplier"<?php if($ItfInfoData['type']=="Supplier"){echo "selected='selected'";}else{} ?>>Supplier</option>
                     <option value="Both" <?php if($ItfInfoData['type']=="Both"){echo "selected='selected'";}else{} ?>>Both</option>
                    </select>
        </div>

        <div class="element">
            <label>Amount<span class="red">(required)</span></label>
            <input class="text"  name="amount" type="text"  id="last_name" size="35" value="<?php echo isset($ItfInfoData['amount'])?$ItfInfoData['amount']:'' ?>" />
        </div>

        <div class="element">
            <label>Day<span class="red">(required)</span></label>
          
            <input class="text" name="duration_day" type="text"  id="email" size="35" value="<?php echo isset($ItfInfoData['duration_day'])?$ItfInfoData['duration_day']:'' ?>" />
        </div>
        
             <div class="element">
            <label>Duration_time<span class="red">(required)</span></label>
                   <select class="cate" name="duration_type">
                     <option value="">-- select Duration --</option>
                     <option value="day"<?php if($ItfInfoData['duration_type']=="day"){echo "selected='selected'";}else{} ?>>Day</option>
                     <option value="month"<?php if($ItfInfoData['duration_type']=="month"){echo "selected='selected'";}else{} ?>>Month</option>
                     <option value="year"<?php if($ItfInfoData['duration_type']=="year"){echo "selected='selected'";}else{} ?>>Year</option>
                    </select>
        </div>
             
              <div class="element">
            <label>Description</label>
                        <textarea name="description" class="textarea"><?php echo isset($ItfInfoData['description'])?$ItfInfoData['description']:'' ?></textarea>
<!--            <input class="text"  name="description" type="textarea"  id="description" size="35" value="<?php //echo isset($ItfInfoData['description'])?$ItfInfoData['description']:'' ?>" />-->
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