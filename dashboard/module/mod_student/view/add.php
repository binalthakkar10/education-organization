<?php
$objstudent = new Student();
//$objAddClass = new AddClass();
$objAddGrade = new Grade();
//$drowpDwanClass = $objAddClass->showAllAddClass();
//$statusList = $objstudent->ShowAllStatus(); //ShowStudentCancelStatus
$cancelList = $objstudent->ShowStudentCancelStatus();
$paymentOptionList = $objstudent->ShowAllPaymentOption();
$classCodeList = $objstudent->ShowAllClassCode();
$courseName = $objstudent->ShowAllCourseName();
$locationName = $objstudent->ShowAllLocation();
if (isset($_POST['id'])) {
    if (!empty($_POST['id'])) {
        $_POST['source'] = 'Class_Admin';
        $objstudent->adminUpdate($_POST);
        flash("Student is succesfully updated");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    } else {
        $_POST['source'] = 'Class_Admin';
        $userId = $objstudent->adminAdd($_POST);
        $_POST['reference_id'] = $userId;
        $obSubscriber = new Newsletter;
        $subsId = $obSubscriber->addSubscriberDetails($_POST);
        flash("Student is succesfully added");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
if ($ids) {
    $ItfInfoData = $objstudent->CheckStudent($ids);
    $date_cancel = date('m/d/Y', strtotime($ItfInfoData['date_cancel']));
} else {
    $date_cancel = date('m/d/Y');
}
//echo '<pre>';print_r($ItfInfoData); 
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#itffrminput").validate({
            rules: {
                class_id: "required",
                course_id: "required",
                loc_id: "required",
                first_name: "required",
                last_name: "required",
                grade: "required",
                primary_name: "required",
                primary_email: {
                    required: true,
                    email: true
                },
                payment_option: "required",
                reg_status: "required",
            },
            messages: {
                class_id: "Please select class",
                first_name: "Please enter student first name",
                last_name: "Please enter student last name",
                grade: "Please select grade",
            }
        });
    });
</script>
<script type="text/javascript">

    function displayCancelFields() {
        var myselect = document.getElementById("status");
        var values = (myselect.options[myselect.selectedIndex].value);
        if (values == 2)
        {
            document.getElementById('cancelfields').style.display = 'block';
        }
        else
        {
            document.getElementById('cancelfields').style.display = 'none';
        }
    }


    function updateCourse(str)
    {
        var xmlhttp;

        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                document.getElementById("course_id").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "itf_ajax/auto.php?type=course&classId=" + str, true);
        xmlhttp.send();
        updateGrade(str);

    }
    function updateGrade(str)
    {
        var xmlhttp;

        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                document.getElementById("grade").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "itf_ajax/auto.php?type=grade&grade_id=<?php echo $ItfInfoData["grade"] ?>&classId=" + str, true);
        xmlhttp.send();
    }
    function updateLocation(str)
    {
        var xmlhttp;

        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                document.getElementById("loc_id").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "itf_ajax/auto.php?type=loc&classId=" + str, true);
        xmlhttp.send();
    }
</script>
<div class="full_w">
    <div class="h_title"><?php
        echo ($ids == "") ? "Add New " : "Edit ";
        echo $pagetitle;
        ?> </div>
    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id']) ? $ItfInfoData['id'] : '' ?>" />
        <input type="hidden" name="usertype" value="2" />
        <fieldset>
         <?php if ($ids != "") {?>
        <div class="element">
            <label>Created Date<span class="red">(required)</span></label>
            <input class="field size1" autocomplete="off" name="created_date" type="text"  id="created_date" disabled="disabled" size="35" value="<?php echo isset($InfoData['created_date']) ? date('m/d/Y', strtotime($InfoData['created_date'])) : '' ?>" />
        </div> 
        <?php } ?>
            <div class="element">
                <label>Class Code<span class="red">(required)</span></label>
                <select name="class_id" id="class_id" onchange="updateCourse(this.value);
                        updateLocation(this.value);">
                    <option value="">---Select Class Code--- </option>
                    <?php
                    foreach ($classCodeList as $item) {
                        $idtest = '';
                        $classId = $item['id'];
                        ?>
                        <option value="<?php echo $item['id']; ?>"   <?php if ($classId == $ItfInfoData['class_id']) { ?>selected="selected"<?php } ?>><?php echo $item['code']; ?> </option>
                    <?php } ?>
                </select>  
            </div>  
            <div class="element">
                <label>Course Name<span class="red">(required)</span></label>
                <select name="course_id" id="course_id" onchange="">
                    <option value="">---Select Course Name--- </option>
                    <?php
                    foreach ($courseName as $item) {
                        $idtest = '';
                        $courseId = $item['id'];
                        ?>
                        <option value="<?php echo $item['id']; ?>"   <?php if ($courseId == $ItfInfoData['course_id']) { ?>selected="selected"<?php } ?>><?php echo $item['name']; ?> </option>
                    <?php } ?>
                </select>  
            </div>  
            <div class="element">
                <label>Location<span class="red">(required)</span></label>
                <select name="loc_id" id="loc_id" onchange="">
                    <option value="">---Select Location Name--- </option>
                    <?php
                    foreach ($locationName as $item) {
                        $idtest = '';
                        $locationId = $item['id'];
                        ?>
                        <option value="<?php echo $item['id']; ?>"   <?php if ($locationId == $ItfInfoData['loc_id']) { ?>selected="selected"<?php } ?>><?php echo ucfirst($item['code'] . ' - ' . $item['name']); ?> </option>
                    <?php } ?>
                </select>     
            </div>  
            <div class="element">
                <label>Student First Name<span class="red">(required)</span></label>
                <input class="text" name="first_name" type="text"  id="first_name" size="35" value="<?php echo isset($ItfInfoData['first_name']) ? $ItfInfoData['first_name'] : '' ?>" />
            </div>  
            <div class="element">
                <label>Student Last Name<span class="red">*</span></label>
                <input class="text" name="last_name" type="text"  id="last_name" size="35" value="<?php echo isset($ItfInfoData['last_name']) ? $ItfInfoData['last_name'] : '' ?>" />
            </div>

            <div class="element"> 

                <?php
                $dropdowngrade = $objAddGrade->showAllactive();
                ?>
                <label>Grade<span class="red">(required)</span></label>
                <select name="grade" id="grade"  tabindex="1">
                    <option value="">---Please Select Class --- </option>
                    <?php
                    foreach ($dropdowngrade as $item) {
                        //$idtest = '';
                        $idtest = $item['id'];
                        ?>
                        <option value="<?php echo $item['id']; ?>"   <?php if ($ItfInfoData['grade'] == $idtest) { ?>selected="selected"<?php } ?>><?php echo $item['grade_desc']; ?> </option>
                    <?php } ?>
                </select>
            </div>            
            <!--<?php
            $ass_class = explode(',', $ItfInfoData['ass_class']);
            foreach ($drowpDwanClass as $item) {
                $idtest = '';
                echo $idtest = $item['id'];
            }
            ?>
                </select>
            </div>  -->  
        </fieldset>
        <div style="padding-top: 10px">&nbsp;</div>
        <fieldset>
            <legend>Primary Contact</legend>
            <div class="element">
                <label>Name<span class="red">(required)</span></label>
                <input class="text" name="primary_name" type="text"  id="primary_name" size="35" value="<?php echo isset($ItfInfoData['primary_name']) ? $ItfInfoData['primary_name'] : '' ?>" />		
            </div>

            <div class="element">
                <label>Relationship </label>
                <input class="text" name="primary_rel" type="text"  id="primary_rel" size="35" value="<?php echo isset($ItfInfoData['primary_rel']) ? $ItfInfoData['primary_rel'] : '' ?>" />		
            </div>

            <div class="element">
                <label>Phone  </label>

                <input class="text" name="primary_contact" type="text"  id="primary_contact" size="35" value="<?php echo isset($ItfInfoData['primary_contact']) ? $ItfInfoData['primary_contact'] : '' ?>" />		
            </div>
            <div class="element">
                <label>Email<span class="red">(required)</span></label>

                <input class="text"  name="primary_email" type="text" id="primary_email" value="<?php echo isset($ItfInfoData['primary_email']) ? $ItfInfoData['primary_email'] : '' ?>"/> 
            </div>


        </fieldset>
        <div style="padding-top: 10px">&nbsp;</div>
        <fieldset>
            <legend>Secondary Contact</legend>
            <div class="element">
                <label>Name</label>
                <input class="text" name="sec_name" type="text"  id="sec_name" size="35" value="<?php echo isset($ItfInfoData['sec_name']) ? $ItfInfoData['sec_name'] : '' ?>" />		
            </div>

            <div class="element">
                <label>Relationship </label>
                <input class="text" name="sec_rel" type="text"  id="sec_rel" size="35" value="<?php echo isset($ItfInfoData['sec_rel']) ? $ItfInfoData['sec_rel'] : '' ?>" />		
            </div>

            <div class="element">
                <label>Phone  </label>

                <input class="text" name="sec_contact" type="text"  id="sec_contact" size="35" value="<?php echo isset($ItfInfoData['sec_contact']) ? $ItfInfoData['sec_contact'] : '' ?>" />		
            </div>
            <div class="element">
                <label>Email</label>

                <input class="text"  name="sec_email" type="text" id="sec_primary_email" value="<?php echo isset($ItfInfoData['sec_email']) ? $ItfInfoData['sec_email'] : '' ?>"/> 
            </div>


        </fieldset>

        <?php
        $paymentId = $ItfInfoData['payment_option'];
        $status = $ItfInfoData['reg_status'];
        ?>
        <div class="element">
            <label>Payment Option<span class="red">(required)</span></label>
            <select name="payment_option" id="payment_option" onchange="">
                <option value="">---Select Payment Option--- </option>
                <?php
                foreach ($paymentOptionList as $item) {
                    $idtest = '';
                    $idtest = $item['id'];
                    ?>
                    <option value="<?php echo $item['id']; ?>"   <?php if ($idtest == $paymentId) { ?>selected="selected"<?php } ?>><?php echo $item['payment_name']; ?> </option>
                <?php } ?>
            </select>
        </div>
        
        <div class="element">
                <label>Paypal Transaction Id</label>

                <input class="text" disabled="disabled" name="paypal_txn_id" type="text"  id="paypal_txn_id" size="35" value="<?php echo isset($ItfInfoData['paypal_txn_id']) ? $ItfInfoData['paypal_txn_id'] : '' ?>" />		
            </div>
            
       <div class="element">
                <label>Payment Amount ($)</label>

                <input class="text" disabled="disabled" name="payment_amt" type="text"  id="payment_amt" size="35" value="<?php echo isset($ItfInfoData['payment_amt']) ? $ItfInfoData['payment_amt'] : '' ?>" />		
            </div>    
            
            <div class="element">
            <label>Installments Booking Amount ($)</label>
            <input class="field size1" disabled="disabled"  name="installment_booking_amt" type="text" id="installment_booking_amt" size="35" value="<?php echo isset($InfoData['installment_booking_amt']) ? $InfoData['installment_booking_amt'] : '' ?>" />
        </div>     
             <div class="element">
            <label>Number of Installments ($)</label>
            <input class="field size1" disabled="disabled"  name="no_of_installments" type="text"  id="no_of_installments" size="35" value="<?php echo isset($InfoData['no_of_installments']) ? $InfoData['no_of_installments'] : '' ?>" />
        </div>	
        
        <div class="element">
            <label>Installment Start Date</label>
            <input class="field size1 tcal" autocomplete="off"  name="installment_start_date" type="text" disabled="disabled"   id="installment_start_date" size="35" value="<?php echo isset($InfoData['installment_start_date']) ? date('m/d/Y', strtotime($InfoData['installment_start_date'])) : '' ?>"  readonly/>
        </div>
        <div class="element">
                <label>Installment Amount ($)</label>

                <input class="text" name="installment_amt" disabled="disabled" type="text"  id="installment_amt" size="35" value="<?php echo isset($ItfInfoData['installment_amt']) ? $ItfInfoData['installment_amt'] : '' ?>" />		
            </div> 
            
         <div class="element">
            <label>Checkout Preference</label>
            <select name="checkout_preference" id="checkout_preference">
                <option value="">---Select Checkout Preference--- </option>
                <?php
                $checkoutId = $ItfInfoData['checkout_preference'];?>
                <option value="1"   <?php if ($checkoutId == '1') { ?>selected="selected"<?php } ?>>Self Checkout</option>
                <option value="2"   <?php if ($checkoutId == '2') { ?>selected="selected"<?php } ?>>Parents Pickup</option>
                <option value="3"   <?php if ($checkoutId == '3') { ?>selected="selected"<?php } ?>>Escort to onsite daycare</option>
            </select>
        </div>   
            
              <div class="element">
                <label>People autorized to pickup the student</label>

                <input class="text" name="authorized_pickup_person" type="text"  id="authorized_pickup_person" size="35" value="<?php echo isset($ItfInfoData['authorized_pickup_person']) ? $ItfInfoData['authorized_pickup_person'] : '' ?>" />		
            </div> 
            <div class="element">
                <label>Allergies or any special needs</label>

                <input class="text" name="special_needs" type="text"  id="special_needs" size="35" value="<?php echo isset($ItfInfoData['special_needs']) ? $ItfInfoData['special_needs'] : '' ?>" />		
            </div>   
            <div class="element">
            <label>Authorization to take Pictures and Videos</label>

            <span class="nme"><input type="radio"  id="media_authorization" name="media_authorization"  size="35" value="Yes"  <?php if ($InfoData['media_authorization'] == '1' || empty($InfoData['id'])) { ?>checked="checked"<?php } ?> ><label class="r_nme">Yes</label> </span>      
            <div style="clear:both;"></div>
            <span class="nme"> <input type="radio"  id="media_authorization" name="media_authorization"  size="35" value="No" <?php if ($InfoData['media_authorization'] == '0') { ?>checked="checked"<?php } ?>><label class="r_nme">No</label></span> 
            <div style="clear:both;"></div>
            </div> 
             <div class="element">
            <label>Newsletter Authorization</label>

            <span class="nme"><input type="radio"  id="newsletter_authorization" name="newsletter_authorization"  size="35" value="Yes"  <?php if ($InfoData['newsletter_authorization'] == '1' || empty($InfoData['id'])) { ?>checked="checked"<?php } ?> ><label class="r_nme">Yes</label> </span>      
            <div style="clear:both;"></div>
            <span class="nme"> <input type="radio"  id="newsletter_authorization" name="newsletter_authorization"  size="35" value="No" <?php if ($InfoData['newsletter_authorization'] == '0') { ?>checked="checked"<?php } ?>><label class="r_nme">No</label></span> 
            <div style="clear:both;"></div>
            </div>  
        <div class="element">
            <label>Registration Status<span class="red">(required)</span></label>
            <select name="reg_status" id="reg_status" onchange="">
                <option value="">---Select Registration Status--- </option>
                <?php
                foreach ($statusList as $item) {
                    $idtest = $item['id'];
                    ?>
                    <option value="<?php echo $item['id']; ?>"   <?php if ($idtest == $status) { ?>selected="selected"<?php } ?>><?php echo $item['status_name']; ?> </option>
                <?php } ?>
            </select>
        </div>
        <!--<div id="cancelfields" style="display: none;">--

            <div class="element">
                <label>Cancellation Date</label>
                <input class="field size1 tcal" autocomplete="off" name="date_cancel" type="text"  id="date_cancel" size="35" value="<?php echo isset($ItfInfoData['date_cancel']) ? $ItfInfoData['date_cancel'] : date('m/d/Y') ?>" />
            </div> 

            <div class="element">
                <label>Cancellation Reason</label>
                <textarea name="cancel_reason" id="cancel_reason" cols="50" rows="10"><?php echo $ItfInfoData['cancel_reason'] ?></textarea>
            </div> 
        </div>-->

        <div class="element">
            <label>Cancellation Date</label>
            <input class="field size1 tcal" autocomplete="off" name="date_cancel" type="text"  id="date_cancel" size="35" value="<?php echo isset($ItfInfoData['date_cancel']) ? $date_cancel : '' ?>" readonly/>
        </div> 
        <div class="element">
            <label>Cancellation Reason</label>
            <select name="cancel_reason_id" id="cancel_reason_id">
                <option value="">---Select Cancellation Reason--- </option>
                <?php
                $cancelId = $ItfInfoData['cancel_reason_id'];
                foreach ($cancelList as $item) {
                    // $idtest = '';
                    $cancelID = $item['id'];
                    ?>
                    <option value="<?php echo $item['id']; ?>"   <?php if ($cancelID == $cancelId) { ?>selected="selected"<?php } ?>><?php echo $item['reason']; ?> </option>
                <?php } ?>
            </select>
        </div>   
        <div class="element">
            <label>Please provide details for Cancellation</label>
            <textarea name="cancel_detail" id="cancel_detail" cols="50" rows="10"><?php echo $ItfInfoData['cancel_detail'] ?></textarea>
        </div> 
        
        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
    </form>

</div>
<script>

<?php
if ($ids) {
    ?>
        //alert('<?php echo $ItfInfoData["grade"] ?>');
        updateCourse('<?php echo $ItfInfoData["class_id"] ?>');
        //   updateGrade('<?php echo $ItfInfoData["class_id"] ?>');
        updateLocation('<?php echo $ItfInfoData["class_id"] ?>')


<?php } ?>
</script>
