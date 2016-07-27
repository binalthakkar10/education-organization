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
$courseDetails = $classObj->getCourseDetails($classDetail['course_id']);
$formDetail = $classObj->getFormName($classId);
echo '<pre>';print_r($formDetail);

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
            $paymentData['installment_amount'] = $_POST["installment_amt"];
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
        echo '<form action="https://www.guruseducation.com/index.php?itfpage=payment" method="post" name="frm">'
        . '<input type="hidden" name="order_id" value="' . $orderId . '"> '
        . '<input type="hidden" name="first_name" value="' . $first_name . '">'
        . '<input type="hidden" name="last_name" value="' . $last_name . '">
     </form> <script language="javascript" type="text/javascript">
    document.frm.submit();
     </script>
     <noscript><input type="submit" value="verify submit"></noscript>';
        //  redirectUrl(CreateLink(array("payment&order_id=$order_id")));
    } else {
        flashMsg("Please Fill up Mandatory Fields.", "2");
        // redirectUrl(CreateLink(array("com_payment?")));
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
                        To register by mail, download 
                        <?php if ($formDetail['name'] != '') {
                            ?>
                            <a href="<?php echo SITEURL . "/itf_public/registration_form/" . $formDetail['name'] ?>" target="blank" >this form</a>
                        <?php } ?>
                        and send with your payment.<br> 
                        Mail to: 45630 Parkmeadow Court, Fremont, CA 94539<br>
                        Or Fax to: 510-350-9096<br>
                        Or Scan and email to: info@bayareadebateclub.com
                    </p>
                </div>
                <div id="reg_online_bottom">
                    <div id="reg_online_title">
                        <h1>Register Online</h1>
                    </div>
                    <form action="" method="post" name="frm" id="frm" class="regi_pg">
                        <input type="hidden" name="class_id" value="<?php echo $classDetail['class_id'] ?>" id="class_id">
                        <input type="hidden" name="course_id" value="<?php echo $classDetail['course_id'] ?>" id="course_id">
                        <input type="hidden" name="loc_id" value="<?php echo $classDetail['loc_id'] ?>" id="loc_id">
                        <input type="hidden" name="single_pay_amt" value="<?php echo $classDetail['single_pay_amt'] ?>" id="loc_id">
                        <input type="hidden" name="installment_booking_amt" value="<?php echo $classDetail['installment_booking_amt'] ?>" id="loc_id">
                        <input type="hidden" name="installment_amt" value="<?php echo $classDetail['installment_amt'] ?>" id="installment_amt">
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
                                <em><?php //echo $classDetail['city']                                   ?></em>
                                <em><?php //echo $classDetail['state'] . ' ' . $classDetail['zip']                                   ?></em>
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
                            <div id="full_width_reg_form">
                                <div id="form_label">
                                    <label>Payment</label>
                                    <div id="payment">
                                        <p><input type="radio" value="1" name="payment_option" id="payment_option1" checked="checked" tabindex="12"/><span> One-time Payment Plan: <?php echo $stieinfo['currency_prefix'] . $classDetail['single_pay_amt'] ?></span></p>

                                        <?php if ($classDetail['installment_booking_amt'] != '0.00' && $classDetail['registration_type'] != 'external') {
                                            ?>
                                            <p><input type="radio" value="2" name="payment_option" id="payment_option2" tabindex="13"/><span> 
                                                    Installment Plan: Initial amount:
                                                    <?php
                                                    echo $stieinfo['currency_prefix'] . $classDetail['installment_booking_amt'] . ' ,  ' .
                                                    $classDetail['no_of_installment']
                                                    ?>  monthly installments of 
                                                    <?php echo $stieinfo['currency_prefix'] . $classDetail['installment_amt'] ?> starting 
                                                    <?php echo date('m/d/Y', strtotime($classDetail['installment_start_date'])) ?></span></p>
                                        <?php } ?> </div>
                                </div>
                                <div id="paypal_btn">
                                    <!--<p><a href="#"><img src="<?php echo TemplateUrl() ?>images/Register_paypal.png" /></a></p>-->
                                    <p> <input class="button_submit pay_now" type="submit" name="submit" value=""  tabindex="14"/></p>
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