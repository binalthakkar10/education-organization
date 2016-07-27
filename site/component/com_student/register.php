<?php 
$utilsObj = new Utils;
$classId = $_GET['class_id'];
$classId = $utilsObj->decryptIds($_GET['class_id']);
$type = $_GET['type'];
if ($type == '123EEDDD') {
    $type = 2;
} else {
    $type = 1;	
}
$classObj = new Class1;
$classDetail = $classObj->getClassListDetails($classId, $type);
if( strtotime( $classDetail['installment_start_date'] ) < strtotime('+ 30 days') ) {
    
    $classDetail['installment_start_date'] = date('Y-m-d',strtotime(' + 30 days'));
}
$courseDetails = $classObj->getCourseDetails($classDetail['course_id']);
$formDetail = $classObj->getFormName($classId);


if (empty($classDetail['class_id'])) {
    flashMsg("Not a valid query string", "2");
    redirectUrl('index.php');
}
if (isset($_POST["submit"])) {
    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["grade"]) && !empty($_POST["primary_email"])) {
		
		
        $objstudent = new Student();
        $paymentData = array();
        $_POST['reg_status'] = 3;
        $_POST['status'] = 0;
        $paymentData['course_name'] = $_POST['course_name'];
        if ($_POST['type'] == 1) {
            $_POST['source'] = 'Class_Web';
            $paymentData['source'] = 'class';
        } else if ($_POST['type'] == 2) {
            $_POST['source'] = 'Summer_Camp_Web';
            $paymentData['source'] = 'summer_camp';
        }
        $userId = $objstudent->adminAdd($_POST);
        $subscriberData = array();
        $_POST['reference_id'] = $userId;
        $paymentData['student_id'] = $userId;
        $paymentData['reference_id'] = $_POST["class_id"];
        if ($_POST["payment_option"] == 1) {
            $paymentData['amount'] = $_POST["single_pay_amt"];
            $paymentData['is_installment'] = 0;
            $paymentData['payment_type'] = 1;
        } else {
            $paymentData['amount'] = $_POST["installment_booking_amt"];
            $paymentData['is_installment'] = 1;
            $paymentData['no_of_installment'] = $_POST["no_of_installment"];
            $paymentData['installment_amount'] = $_POST["installment_amt"];
            $paymentData['installment_start_date'] = $_POST["installment_start_date"];
            $paymentData['payment_type'] = 2;
        }
        $paymentObj = new Payment();
        $obSubscriber = new Newsletter;
        $subsId = $obSubscriber->addSubscriberDetails($_POST);
        $order_id = $paymentObj->addOrder($paymentData);
        // flashMsg("Successfully Registered");
        $orderId = $utilsObj->encryptIds($order_id);
        $first_name = $utilsObj->encryptIds($_POST["first_name"]);
        $last_name = $utilsObj->encryptIds($_POST["last_name"]);
        echo '<form action="https://gurusdev.com/index.php?itfpage=payment" method="post" name="frm">'
        //echo '<form action="http://'.$_SERVER['HTTP_HOST'].'/index.php?itfpage=payment" method="post" name="frm">'
        . '<input type="hidden" name="order_id" value="' . $orderId . '"> '
        . '<input type="hidden" name="first_name" value="' . $first_name . '">'
        . '<input type="hidden" name="last_name" value="' . $last_name . '">
     </form> <script language="javascript" type="text/javascript">
    document.frm.submit();
     </script>
     <noscript><input type="submit" value="verify submit"></noscript>';
        //  redirectUrl(CreateLink(array("payment&order_id=$order_id")));
    } else {
        flashMsg("Please Fill up Manadatory Fields.", "2");
        // redirectUrl(CreateLink(array("com_payment?")));
		
    }
} else {
	
 if(isset($_SESSION['amit'])){ ?>	
<script> location.reload(true);</script>	
<?php 
unset($_SESSION['amit']);
}	
}
if (isset($_GET['msg']) and $_GET['msg'] == "na") {
    echo "<div class='msgbox n_error'><p>If you are not yet a customer please Register.</p></div>";
}
$startGrade = $classDetail['start_eligibility'];
$endGrade = $classDetail['end_eligibility'];
$gradeObj = new Grade();
$gradesList = $gradeObj->showClassGrade($startGrade, $endGrade);
$validJs = TemplateUrl() . "js2/jquery.validate.js";
?>

<script src="<?php echo $validJs ?>"></script>
<script>

    $().ready(function() {

        $("#frm").validate({
            errorElement: "span",
            rules: {
                first_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                last_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                grade: {
                    required: true,
                },
                primary_name: {
                    required: true,
                },
                primary_email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                first_name: {
                    required: "Please Enter a First Name",
                    minlength: "Please Enter a Valid First Name",
                    maxlength: "Please Enter a Valid First Name"
                },
                last_name: {
                    required: "Please Enter a Last Name",
                    minlength: "Please Enter a Valid Last Name",
                    maxlength: "Please Enter a Valid Last Name"
                },
                grade: {
                    required: "Please Select grade",
                },
                primary_name: "Please Enter a Primary Contact Name",
                primary_email: "Please Enter a Valid Email"
            }
        });

    });
</script>


<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <!--<div id="page_title">
        <h1>About <span style="color:#ab281f;">Us</span></h1>
    </div>-->
        <div id="page_content">
            <div class="register_online">
                <div id="reg_online_top">
                    <div id="reg_online_title">
                        <h1>Register by Mail</h1>
                    </div>
                    <p>
                        To register by mail, <?php if ($formDetail['name'] != '') { ?>
                            <a href="<?php echo SITEURL . "/itf_public/registration_form/" . $formDetail['name'] ?>" target="blank" style="text-decoration: underline;">click here</a>
                            <?php
                        } else {
                            echo 'click here';
                        }
                        ?> to download the registration form and send with your payment.
                    </p>
                </div>
                <div id="reg_online_bottom">
                    <div id="reg_online_title">
                        <h1>Register Online</h1>
<?php //print_r($classDetail); print_r($courseDetails); ?>
                    </div>
                    <form action="" method="post" name="frm" id="frm" class="regi_pg">
                        <input type="hidden" name="class_id" value="<?php echo $classDetail['class_id'] ?>" id="class_id">
                        <input type="hidden" name="course_id" value="<?php echo $classDetail['course_id'] ?>" id="course_id">
                        <input type="hidden" name="course_name" value="<?php echo $courseDetails['name'] ?>" id="course_name">
                        <input type="hidden" name="loc_id" value="<?php echo $classDetail['loc_id'] ?>" id="loc_id">
                        <input type="hidden" class="single_pay_amt" name="single_pay_amt" value="<?php echo $classDetail['single_pay_amt'] ?>" id="loc_id">
                        <input type="hidden" class="installment_booking_amt" name="installment_booking_amt" value="<?php echo $classDetail['installment_booking_amt'] ?>" id="loc_id">
                        <input type="hidden" name="installment_amt" value="<?php echo $classDetail['installment_amt'] ?>" id="installment_amt">
                        <input type="hidden" name="no_of_installment" value="<?php echo $classDetail['no_of_installment'] ?>" id="no_of_installment">
                        <input type="hidden" name="installment_start_date" value="<?php echo $classDetail['installment_start_date'] ?>" id="installment_start_date">
                        <input type="hidden" name="type" value="<?php echo $type ?>" id="type">
                        <div class="reg_form_online1">
                            <div id="reg_form_online_left">
                                <div id="online_left_t"> 

                                    <p>
                                        <em><?php echo $classDetail['loc_name'] ?></em>
                                        <br/>
                                        <em><?php echo $classDetail['address'] ?></em>
                                        <br/></p>
                                </div>
                                <div id="form_label">
                                    <label>Student First Name<span>*</span></label>
                                    <input type="text" name="first_name"  id="first_name" tabindex="1"/>
                                </div>
                                <div id="form_label">
                                    <label>Grade<span>*</span></label>
                                    <select name="grade" id="grade" tabindex="3">
                                        <option value="" >Select Grade</option>
                                        <?php
                                        foreach ($gradesList as $values) {
                                            if ($InfoData['start_eligibility'] == $values["id"]) {
                                                echo '<option value="' . $values["id"] . '" selected="selected">' . $values["grade_desc"] . '</option>';
                                            } else {
                                                echo '<option value="' . $values["id"] . '">' . $values["grade_desc"] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div><!-- reg_form_online_left -->
                            <div id="reg_form_online_right">
                                <div id="online_left_t"> 
                                    <p><em><?php echo $courseDetails['name'] ?></em>

                                    <p><em><?php echo $courseDetails['code'] ?></em></div>
                                <em><?php //echo $classDetail['city']                                          ?></em>
                                <em><?php //echo $classDetail['state'] . ' ' . $classDetail['zip']                                          ?></em>
                                </p>

                                <div id="form_label">
                                    <label>Student Last Name<span>*</span></label>
                                    <input type="text"name="last_name"  id="last_name" tabindex="2"/>
                                </div>
                            </div> 
                        </div><!-- reg_form_online_right -->
                        <!-- reg_form_online1 -->
                        <div class="reg_form_online1" id="reg_form_online2">
                            <h2>Primary Contact:</h2>
                            <div id="reg_form_online_left"> 
                                <div id="form_label">
                                    <label>Name<span>*</span></label>
                                    <input type="text" name="primary_name" value="" id="primary_name" tabindex="4" />
                                </div>
                                <div id="form_label">
                                    <label>Phone</label>
                                    <input type="text" name="primary_contact" value="" id="primary_contact" tabindex="6"/>
                                </div>
                            </div><!-- reg_form_online_left -->
                            <div id="reg_form_online_right"> 
                                <div id="form_label">
                                    <label>Relationship</label>
                                    <input type="text" name="primary_rel"  id="primary_rel" tabindex="5" />
                                </div>
                                <div id="form_label">
                                    <label>Email Address<span>*</span></label>
                                    <input type="text" name="primary_email"  id="primary_email" tabindex="7"/>
                                </div>
                            </div><!-- reg_form_online_right -->
                        </div><!-- reg_form_online2 -->
                        <div class="reg_form_online1" id="reg_form_online3">
                            <h2>Secondary Contact:</h2>
                            <div id="reg_form_online_left"> 
                                <div id="form_label">
                                    <label>Name</label>
                                    <input type="text" name="sec_name" value="" id="sec_name" tabindex="8"/>
                                </div>
                                <div id="form_label">
                                    <label>Phone</label>
                                    <input type="text" name="sec_contact" value="" id="sec_contact" tabindex="10"/>
                                </div>
                            </div><!-- reg_form_online_left -->
                            <div id="reg_form_online_right"> 
                                <div id="form_label">
                                    <label>Relationship</label>
                                    <input type="text" name="sec_rel" value="" id="sec_rel" tabindex="9"/>
                                </div>
                                <div id="form_label">
                                    <label>Email Address</label>
                                    <input type="text" name="sec_email" value="" id="sec_email" tabindex="11"/>
                                </div>
                                
                             
                                <!--<div id="submit_btn">
                                    <input class="button_submit" type="submit" name="PAY NOW" value="Cancel Registration" />
                                </div>-->
                            </div><!-- reg_form_online_right -->
                             <div class="reg_form_online1" id="reg_form_online4">
                            <h2>Additional Information:</h2>
                            <div id="reg_form_online_left"> 
                                <div id="form_label">
                                    <label>Paypal Transaction Id</label>
                                    <input type="text" name="paypal_txn_id" value="" id="paypal_txn_id" tabindex="4" />
                                </div>
                                <div id="form_label">
                                    <label>Payment Amount ($)</label>
                                    <input type="text" name="payment_amt" value="" id="payment_amt" tabindex="6"/>
                                </div>
                                <div id="form_label">
                                    <label>Installment Start Date</label>
                                    <input type="text" name="installment_start_date" value="" id="installment_start_date" tabindex="6"/>
                                </div>
                                 <div id="form_label">
                                     <label>Checkout Preference</label>
							            <select name="checkout_preference" id="checkout_preference">
							                <option value="">---Select Checkout Preference--- </option>
							                <option value="1">Self Checkout</option>
							                <option value="2">Parents Pickup</option>
							                <option value="3">Escort to onsite daycare</option>
							            </select>
                                </div>
                                <div id="form_label">
                                    <label>Allergies or any special needs</label>
                                    <input type="text" name="special_needs" value="" id="special_needs" tabindex="6"/>
                                </div>
                                <div id="form_label">
                                    <label>I hereby authorize Gurus Education to publish photographs taken of my child in class for the use in company's Advertising, including those that are printed, posted online or created in video form.</label>
                                    <input type="checkbox" name="class_registration" class="itflistdatas" value="1" checked="checked" >
                                </div>
                                <div id="form_label">
                                    <label>Sign me up for monthly newsletter. Newsletters are sent to announce upcoming classes and tournaments.</label>
                                    <input type="checkbox" name="class_registration" class="itflistdatas" value="1" checked="checked" >
                                </div>
                                
                            </div><!-- reg_form_online_left -->
                            <div id="reg_form_online_right"> 
                                <div id="form_label">
                                    <label>Installments Booking Amount ($)</label>
                                    <input type="text" name="installment_booking_amt"  id="installment_booking_amt" tabindex="5" />
                                </div>
                                <div id="form_label">
                                    <label>Number of Installments ($)</label>
                                    <input type="text" name="no_of_installments"  id="no_of_installments" tabindex="7"/>
                                </div>
                                <div id="form_label">
                                    <label>Installment Amount ($)</label>
                                    <input type="text" name="installment_amt"  id="installment_amt" tabindex="7"/>
                                </div>
                                <div id="form_label">
                                    <label>People autorized to pickup the student</label>
                                    <input type="text" name="authorized_pickup_person"  id="authorized_pickup_person" tabindex="7"/>
                                </div>
                            </div><!-- reg_form_online_right -->
                        </div><!-- reg_form_online2 -->
                            <div id="reg_form_online_left" style="width:100%"> 
                               <h2>Coupon:</h2>
                                <div id="apply_coupon" style="position:inherit;"> 
                                <div id="form_label">
                                    <label style="width:100%;">Coupon Code:</label>
                                    <div class="Msg" style="display:none;">Coupon successfully applied!!!</div>
                                    <div class="MsgR" style="display:none;">Coupon successfully removed!!!</div><br />
                                    <input type="text"tabindex="12" id="CouponVal" value="" name="coupon_code"/>
                                    <input type="button" tabindex="13" name="couponName" id="CouponApply" value="Apply" />
                                    <input type="button" name="couponRName" id="CouponRemove" value="Remove" style="display:none;" />
                                    <img src="<?php echo SITEURL;?>/template/default/images/imgLoad.gif" width="28" id="LoadImage" style="display:none;" />
                                </div></div>
                               
                            </div>
                            <div id="full_width_reg_form">
                                <div id="form_label">
                                    <label>Payment</label>
                                    <div id="payment">
                                        <p><input type="radio" value="1" name="payment_option" id="payment_option1" checked="checked" tabindex="14"/><span> One-time Payment Plan: <?php echo $stieinfo['currency_prefix'] ;?><span class="1Time"><?php echo $classDetail['single_pay_amt'] ?></span></span></p>

                                        <?php if ($classDetail['installment_booking_amt'] != '0.00' && $classDetail['registration_type'] != 'external') {
                                            ?>
                                            <p><input type="radio" value="2" name="payment_option" id="payment_option2" tabindex="15"/><span> 
                                                    Installment Plan: First Installment (Pay Now):
                                                    <?php
                                                    echo $stieinfo['currency_prefix'] ;?><span class="Insta"><?php echo $classDetail['installment_booking_amt'];?></span>, <?php echo  $classDetail['no_of_installment']; ?>  equal monthly installments of  
                                                    <?php echo $stieinfo['currency_prefix'] . $classDetail['installment_amt'] ?> starting 
                                                    <?php echo date('m/d/Y', strtotime($classDetail['installment_start_date'])) ?>. Installment amount would be automatically charged to your credit card every month for <?php echo  $classDetail['no_of_installment']; ?> months. </span></p>
                                        <?php } ?> </div>
                                </div>
                                <div id="paypal_btn">
                                    <!--<p><a href="#"><img src="<?php echo TemplateUrl() ?>images/Register_paypal.png" /></a></p>-->
                                    <p> <input class="button_submit pay_now" type="submit" name="submit" value=""  tabindex="16"/></p>
                                </div>

                                <!-- <div id="submit_btn">
                                     <input class="button_submit" type="submit" name="submit" value="PAY NOW" />
                                 </div>-->
                            </div>
                        </div><!-- reg_form_online3 -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$( document).ready(function(e) {
    $('#CouponApply').click( function(){
		var CoupnCode = $('#CouponVal').val();
		if(CoupnCode != ''){
			$('#CouponVal').css("border","1px solid #c5c5c5");
			$('#LoadImage').show();			
				$.ajax({
						type: "POST",
						url: "coupons.php?func=couponsValue",
						data: { valueC: CoupnCode,type: <?php echo $type; ?>,classID:<?php echo $classDetail['class_id'] ?>}
						})
						.done(function( msg ) {
							if(msg != 'Invalid'){
							$('#LoadImage').hide();
							var single_pay_amt = $('.single_pay_amt').val();
							var installment_booking_amt = $('.installment_booking_amt').val();
							////Single Pay amt
							var SingleWithC = (single_pay_amt-msg);
							$('.single_pay_amt').val(SingleWithC);
							$('.1Time').html(SingleWithC);
							///Installment amt
							var InstallWithC = (installment_booking_amt-msg);
							$('.installment_booking_amt').val(InstallWithC);
							$('.Insta').html(InstallWithC);
							$('#CouponApply').attr("disabled","disabled");
							//$('#CouponVal').attr("disabled","disabled");
							$('#CouponVal').hide();
							$('#CouponApply').hide();
							$('#CouponRemove').show();
							$('.Msg').show();
							$('.MsgR').hide();
						} else{
							$('#LoadImage').hide();
							alert('Expired or Invalid coupon.');	
						}
						});
		}else{
			$('#CouponVal').css("border","1px solid red");
		}
		});
		
		
    $('#CouponRemove').click( function(){
		var CoupnCode = $('#CouponVal').val();
		if(CoupnCode != ''){
			$('#CouponVal').css("border","1px solid #c5c5c5");
			$('#LoadImage').show();			
				$.ajax({
						type: "POST",
						url: "coupons.php?func=couponsValue",
						data: { valueC: CoupnCode,type: <?php echo $type; ?>,classID:<?php echo $classDetail['class_id'] ?>}
						})
						.done(function( msg ) {
							$('#LoadImage').hide();
							var single_pay_amt = $('.single_pay_amt').val();
							var installment_booking_amt = $('.installment_booking_amt').val();
							////Single Pay amt
							var SingleWithC = parseFloat(single_pay_amt)+parseFloat(msg);
							$('.single_pay_amt').val(SingleWithC);
							$('.1Time').html(SingleWithC);
							///Installment amt
							var InstallWithC = parseFloat(installment_booking_amt)+parseFloat(msg);
							$('.installment_booking_amt').val(InstallWithC);
							$('.Insta').html(InstallWithC);
							$('#CouponApply').removeAttr("disabled");
							//$('#CouponVal').attr("disabled","disabled");
							$('#CouponVal').val('')
							$('#CouponVal').show();
							$('#CouponApply').show();
							$('#CouponRemove').hide();
							$('.Msg').hide();
							$('.MsgR').show();
						});
		}else{
			$('#CouponVal').css("border","1px solid red");
		}
		});

		
		
		
		
});

</script>