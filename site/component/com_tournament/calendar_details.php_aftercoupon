<?php
#error_reporting(E_ALL);
#ini_set('display_errors','On');
$tounamentObj = new Tournament;
$calID = $_GET['calID'];
$utilsObj = new Utils;
$calID = $utilsObj->decryptIds($_GET['calID']);
$tournamentList = $tounamentObj->gettournamentInfo($calID);
//echo '<pre>';print_r($tournamentList);exit;
$validJs = TemplateUrl() . "js2/jquery.validate.js";
$jqueryJs = TemplateUrl() . "js2/jquery.validate.js";
if (isset($_POST['submit'])) {
    $studentObj = new Student();
    $userID = $studentObj->studentTournamentRegistration($_POST);
    $paymentData = array();
    $paymentData['student_id'] = $userID;
    $paymentData['source'] = 'tournament';
    $paymentData['reference_id'] = $_POST["reference_id"];
    $paymentData['amount'] = $_POST["payment_amount"];
    $paymentData['is_installment'] = 0;
    $paymentData['payment_type'] = 1;
    $_POST['reference_id'] = $userID;
    $paymentObj = new Payment();
    $order_id = $paymentObj->addOrder($paymentData);
    $obSubscriber = new Newsletter;
    $subsId = $obSubscriber->addSubscriberDetails($_POST);
    // flashMsg("Successfully Registered");
    $utilsObj = new Utils;
    $orderId = $utilsObj->encryptIds($order_id);
    $first_name = $utilsObj->encryptIds($_POST["first_name"]);
    $last_name = $utilsObj->encryptIds($_POST["last_name"]);
    echo '<form action="index.php?itfpage=payment" method="post" name="frm"><input type="hidden" name="order_id" value="' . $orderId . '">
<input type="hidden" name="first_name" value="' . $first_name . '">'
    . '<input type="hidden" name="last_name" value="' . $last_name . '">     

</form> <script language="javascript" type="text/javascript">
    document.frm.submit();
     </script>
     <noscript><input type="submit" value="verify submit"></noscript>';
    if ($userID != '') {
        // flashMsg("Thanks for your registration", "2");
    } else {
        flashMsg("There are some technical issues in our site", "2");
    }
}else {
	
 if(isset($_SESSION['amit'])){ ?>	
<script> location.reload(true);</script>	
<?php 
unset($_SESSION['amit']);
}
}
$validJs = TemplateUrl() . "js2/jquery.validate.js";
?>
<script src="<?php echo $validJs ?>"></script>
<script>
    $().ready(function() {
        jQuery.validator.addMethod("greaterThan",
                function(value, element, params) {
                    if (isNaN($("#phone").val())) {
                        // It isn't a number
                    } else {
                        // It is a number
                    }
                    $("#phone").css({display: "none"});
                    return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
                }, 'Data should be digit.');
        $("#frm").validate({
            errorElement: "span",
            rules: {
                first_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                last_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                dob: {
                    required: true
                },
                phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 15
                },
                email: {
                    required: true,
                    email: true
                }

            },
            messages: {
                first_name: {
                    required: "Please Enter a First Name",
                    minlength: "Please Enter Valid First Name",
                    maxlength: "Please Enter Valid First Name"
                },
                last_name: {
                    required: "Please Enter a Last Name",
                    minlength: "Please Enter Valid Last Name",
                    maxlength: "Please Enter Valid Last Name"
                },
                dob: "Please Enter a Valid Date of Birth.",
                phone: {
                    required: "Please Enter a Phone Number",
                    minlength: "Please Enter Valid Phone No.",
                    maxlength: "Please Enter Valid Phone No."
                },
                email: "Please Enter a Valid Email."
            }

        });

    });
</script>

<style type="text/css">
    #frm { width: 723px; }
    #frm label.error {
        /*margin-right: 10px;
        width: auto;
        display: inline;*/
    }

</style>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <div id="page_title">
            <h1>Calendar</h1>
        </div>
        <div id="page_content">
            <div class="calender_details">
                <a href="index.php?itfpage=tournament&itemid=calendar_details&calID=<?php echo $_GET['calID'] ?>" title="VIEW DETAILS"> 
                    <img src="<?php echo PUBLICPATH . "tournament_image/" . $tournamentList['image']; ?>" width="224" height="149"/></a>

                <div id="calender_details_content">
                    <h2><?php echo $tournamentList['title'] ?></h2>
                    <p><?php echo $tournamentList['sdescription'] ?></p>
                </div>
                <div id="calender_details_bottom">
                    <p>
                        <span>Tournament Date: <span><?php echo $tournamentList['tournament_date'] ?></span></span>
                        <span>Start Time: <span><?php echo $tournamentList['start_time'] ?></span></span>
                        <span>End Time: <span><?php echo $tournamentList['end_time'] ?></span></span>
                        <span>Price: <span><?php echo $stieinfo['currency_prefix'];?><span class="1Time"> <?php echo $tournamentList['fee'] ?></span></span></span>
                        <span>Event Location: <?php echo $tournamentList['loc_name'] . '&nbsp;' . $tournamentList['location_address'] ?></span>
                    </p>
                    <div id="calender_form" class="frm_cal">
                        <h2>Register Now</h2>
                        <form action="" method="post" name="frm" id="frm">
                            <input type="hidden" name="reference_id" id="reference_id" value="<?php echo $tournamentList['id'] ?>" />
                            <input type="hidden" name="tournament_id" id="tournament_id" value="<?php echo $tournamentList['id'] ?>" />
                            <input type="hidden" name="payment_amount" id="payment_amount" value="<?php echo $tournamentList['fee'] ?>" />
                            <input type="hidden" name="loc_id" value="<?php echo $tournamentList['loc_id'] ?>" id="loc_id">
                            <div id="form_label">
                                <label for="first_name">Student First Name<span>*</span></label>
                                <input type="text" name="first_name" id="first_name" />
                            </div>
                            <div id="form_label">
                                <label for="last_name">Student Last Name<span>*</span></label>
                                <input type="text" name="last_name" value=""  id="last_name"/>
                            </div>
                            <div id="form_label">
                                <label for="age">Date of Birth<span>*</span></label>
                                <input type="text" name="dob"  id="dob"/>
                            </div>
                            <div id="form_label">
                                <label>Phone No<span>*</span></label>
                                <input type="text" name="phone" id="phone" />
                            </div>
                            <div id="form_label">
                                <label >Email Id<span>*</span></label>
                                <input type="text" name="email"  id="email" />
                            </div>
                            <?php // if ($tournamentList['topics_choices'] != '') { ?>
                            <div id="form_label">
                                <label >Tournament Topics Choices</label>
                                <textarea col="" rows="" name="topics_choices"  id="topics_choices"></textarea>
                                <!--<input type="text" name="topics_choices"  id="topics_choices" />-->
                            </div>
                            <?php // } ?>
                            <!--  <div id="form_label">
                                  <label>Topic</label>
                            <?php
                            $topicOptions = $tounamentObj->getTournamentTopicDetails($tournamentList['id']);

                            // $tounamentObj->getTournamentValue();
                            ?>
                                  <select name="topic_id[]" id="topic_id" multiple="">
                            <?php echo $topicOptions ?>
                                  </select>
                              </div>-->
                            <div id="form_label">
                                <label>Did you attend tournaments hosted by Bay Area Debate Club before?</label>
                                <select name="attend_tournament" id="attend_tournament">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div> 
                            <div id="form_label">
                               <h3>Coupon:</h3>
                                    <label>Coupon Code:</label>
                                    <div class="Msg" style="display:none;">Coupon successfully applied!!!</div>
                                    <div class="MsgR" style="display:none;">Coupon successfully removed!!!</div>
                                    <input type="text"  id="CouponVal" value="" name="coupon_code"/>
                                    <input type="button" name="couponName" id="CouponApply" value="Apply" />
                                    <input type="button" name="couponRName" id="CouponRemove" value="Remove" style="display:none;" />
                                    <img src="<?php echo SITEURL;?>/template/default/images/imgLoad.gif" width="28" id="LoadImage" style="display:none; width:28px;" />
                               </div>
                               
                            <div id="submit_btn">
                                <input class="button_submit sh_btn1" type="submit" name="submit" value="PAY NOW" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end main_wrapper Section -->
</div><!-- end main_container -->
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
						data: { valueC: CoupnCode,type: 't',classID:<?php echo $tournamentList['id']; ?>}
						})
						.done(function( msg ) {
							if(msg != 'Invalid'){
							$('#LoadImage').hide();
							var single_pay_amt = $('#payment_amount').val();
							////Single Pay amt
							var SingleWithC = (single_pay_amt-msg);
							$('#payment_amount').val(SingleWithC);
							$('.1Time').html(SingleWithC);
							///Installment amt
							//$('.Insta').html(InstallWithC);
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
						data: { valueC: CoupnCode,type: 't',classID:<?php echo $tournamentList['id']; ?>}
						})
						.done(function( msg ) {
							$('#LoadImage').hide();
							var single_pay_amt = $('#payment_amount').val();
							////Single Pay amt
							var SingleWithC = parseFloat(single_pay_amt)+parseFloat(msg);
							$('#payment_amount').val(SingleWithC);
							$('.1Time').html(SingleWithC);
							//$('.Insta').html(InstallWithC);
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



