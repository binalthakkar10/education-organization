<?php
#error_reporting(E_ALL);
#ini_set('display_errors','On');
if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
    redirectUrl(CreateLink(array("teacherlogin")));
    exit;
}
$teacherId = $_SESSION['teacherLoginInfo']['user_id'];
$studentsObj = new Student;
if (isset($_POST["first_name"])) {
    if ($_POST["first_name"] != '' && $_POST["last_name"] != '' && $_POST["primary_email"] != '0') {
        $_POST['source'] = 'Class_Teacher';
        $studentId = $studentsObj->adminAdd($_POST);
        $studentsObj->addStudentByTeacherAdminMail($studentId);
        flashMsg("Student is succesfully Added");
        redirectUrl('index.php?itfpage=teacher&itemid=student_listing&classId=' . $_POST['class_id']);
    } else {
        flashMsg("Please fill Up Manadatory fields", "2");
    }
}
$classID = $_GET['classId'];
$classCodeList = $studentsObj->getClassInfo($classID);
$startGrade = $classCodeList['start_eligibility'];
$endGrade = $classCodeList['end_eligibility'];
$gradeObj = new Grade();
$gradesList = $gradeObj->showClassGrade($startGrade, $endGrade);
$paymentOptionList = $studentsObj->ShowAllPaymentOption();
$userobj = new User();
$statusList = $userobj->ShowAllStatus();

$validJs = TemplateUrl() . "js2/jquery.validate.js";
$cancelList = $studentsObj->ShowStudentCancelStatus();
?>

<style>
    #frm span.error {
        float: left;
        margin: 7px 7px 7px 186px;
        font-size: 13px;
    }

</style>
<script src="<?php echo $validJs ?>"></script>
<script>
    function showDetails(val)
    {
        if (val == 4)
        {
            document.getElementById("provide_div").style.display = 'block';
        }
        else
        {
            document.getElementById("provide_div").style.display = 'none';
        }
    }
    $().ready(function() {

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
                grade: {
                    required: true
                },
                /*   primary_contact: {
                 required: true,
                 maxlength: 15
                 },*/
                primary_email: {
                    required: true,
                    email: true
                },
                payment_option: {
                    required: true
                },
                reg_status: {
                    required: true
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
                grade: "Please Select Grade",
                primary_contact: {
                    required: "Please Enter a Contact No.",
                    minlength: "Please Enter a Valid Contact No.",
                    maxlength: "Please Enter a Valid Contact No."
                },
                primary_email: {
                    required: "Please enter a Valid Email."
                },
                payment_option: "Please Select Payment Option",
                reg_status: "Please Select Registration Status"
            }

        });

    });
</script>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <div id="page_title">
            <h1>Teacher <span style="color:#ab281f;">Dashboard</span></h1>
        </div>
        <div id="page_content">
            <?php include_once('left_menu.php') ?>
            <div class="right_content add_student">
                <!-- My Profile -->
                <div id="gallery">
                    <div class="teacher_login_page stu_dash" id="atten">
                        <!-- Forgot Password-->
                        <div class="teacher_login_page" id="atten">
                            <div id="title_teacher_login"><h2>Add Student</h2></div>
                            <form method="post" action="" name="frm" id="frm">
                                <input name="loc_id" type="hidden" readonly value="<?php echo $classCodeList['loc_id'] ?>">
                                <input name="class_id" type="hidden" readonly value="<?php echo $classCodeList['class_id'] ?>">
                                <input name="course_id" type="hidden" readonly value="<?php echo $classCodeList['course_id'] ?>">
                                <div id="form_label">
                                    <label>Course Name</label>
                                    <?php echo $classCodeList['course_name'] ?>
                                </div>
                                <div id="form_label">
                                    <label>Class Code</label>
                                    <?php echo $classCodeList['class_code'] ?>
                                </div>

                                <div id="form_label">
                                    <label>Location</label>
                                    <?php echo $classCodeList['loc_code'] . ' -- ' . $classCodeList['loc_name'] ?>  </div>
                                <div id="form_label">
                                    <label>Student First Name<span>*</span></label>
                                    <input name="first_name" type="text"  value="<?php echo $studentDetails['first_name'] ?>" tabindex="1">
                                </div>
                                <div id="form_label">
                                    <label>Student Last Name<span>*</span></label>
                                    <input name="last_name" type="text"  value="<?php echo $studentDetails['last_name'] ?>" tabindex="2">
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
                                <div style="padding-top: 10px">&nbsp;</div>
                                <fieldset>
                                    <legend>Primary Contact<span>*</span></legend>
                                    <div id="form_label">
                                        <label>Name</label>
                                        <input class="text" name="primary_name" type="text"  id="primary_name" size="35" value="<?php echo isset($ItfInfoData['primary_name']) ? $ItfInfoData['primary_name'] : '' ?>" tabindex="4"/>		
                                    </div>

                                    <div id="form_label">
                                        <label>Relationship </label>
                                        <input class="text" name="primary_rel" type="text"  id="primary_rel" size="35" value="<?php echo isset($ItfInfoData['primary_rel']) ? $ItfInfoData['primary_rel'] : '' ?>" tabindex="5" />		
                                    </div>

                                    <div id="form_label">
                                        <label>Phone  </label>

                                        <input class="text" name="primary_contact" type="text"  id="primary_contact" size="35" value="<?php echo isset($ItfInfoData['primary_contact']) ? $ItfInfoData['primary_contact'] : '' ?>"  tabindex="6"/>		
                                    </div>
                                    <div id="form_label">
                                        <label>Email <span>*</span></label>

                                        <input class="text"  name="primary_email" type="text" id="primary_email" value="<?php echo isset($ItfInfoData['primary_email']) ? $ItfInfoData['primary_email'] : '' ?>" tabindex="7"/> 
                                    </div>


                                </fieldset>
                                <div style="padding-top: 10px">&nbsp;</div>
                                <fieldset>
                                    <legend>Secondary Contact</legend>
                                    <div id="form_label">
                                        <label>Name</label>
                                        <input class="text" name="sec_name" type="text"  id="sec_name" size="35" value="<?php echo isset($ItfInfoData['sec_name']) ? $ItfInfoData['sec_name'] : '' ?>" tabindex="8"/>		
                                    </div>

                                    <div id="form_label">
                                        <label>Relationship </label>
                                        <input class="text" name="sec_rel" type="text"  id="sec_rel" size="35" value="<?php echo isset($ItfInfoData['sec_rel']) ? $ItfInfoData['sec_rel'] : '' ?>" tabindex="9" />		
                                    </div>

                                    <div id="form_label">
                                        <label>Phone  </label>

                                        <input class="text" name="sec_contact" type="text"  id="sec_contact" size="35" value="<?php echo isset($ItfInfoData['sec_contact']) ? $ItfInfoData['sec_contact'] : '' ?>" tabindex="10"/>		
                                    </div>
                                    <div id="form_label">
                                        <label>Email</label>

                                        <input class="text"  name="sec_email" type="text" id="sec_primary_email" value="<?php echo isset($ItfInfoData['sec_email']) ? $ItfInfoData['sec_email'] : '' ?>" tabindex="11"/> 
                                    </div>


                                </fieldset>

                                <?php
                                $paymentId = $ItfInfoData['payment_option'];
                                $status = $ItfInfoData['reg_status'];
                                ?>
                                <div id="form_label">
                                    <label>Payment Option<span>*</span></label>
                                    <select name="payment_option" id="payment_option" onchange="" tabindex="12">
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
                                <div id="form_label">
                                    <label>Registration Status<span>*</span></label>
                                    <select name="reg_status" id="reg_status" onchange="" tabindex="13">
                                        <option value="">---Select Registration Status--- </option>
                                        <?php
                                        foreach ($statusList as $item) {
                                            if($item['id']==4) {
                                            $idtest = $item['id'];
                                            ?>
                                            <option value="<?php echo $item['id']; ?>"   <?php if ($idtest == $status) { ?>selected="selected"<?php } ?>><?php echo $item['status_name']; ?> </option>
                                            <?php }} ?>
                                    </select>
                                </div>
                                <!--<div id="cancelfields" style="display: none;">--

            <div id="form_label">
                <label>Cancellation Date</label>
                <input class="field size1 tcal" autocomplete="off" name="date_cancel" type="text"  id="date_cancel" size="35" value="<?php echo isset($ItfInfoData['date_cancel']) ? $ItfInfoData['date_cancel'] : date('m/d/Y') ?>" />
            </div> 

            <div id="form_label">
                <label>Cancellation Reason</label>
                <textarea name="cancel_reason" id="cancel_reason" cols="50" rows="10"><?php echo $ItfInfoData['cancel_reason'] ?></textarea>
            </div> 
        </div>-->

                                <!-- <div id="form_label">
                                     <label>Cancellation Date(m/d/Y)</label>
                                     <input class="field size1 tcal" autocomplete="off" name="date_cancel" type="text"  id="date_cancel" size="35" value="<?php echo isset($ItfInfoData['date_cancel']) ? '' : '' ?>" tabindex="14" />
                                 </div> 
                                 <div id="form_label">
                                     <label>Cancellation Reason</label>
                                     <select name="cancel_reason_id" id="cancel_reason_id" onchange="showDetails(this.value);" tabindex="15">
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
                                 <div id="provide_div" style="display: none">
                                     <div id="form_label" >
                                         <label>Please Provide Details </label>
                                         <textarea title="Please Provide Details" name="cancel_detail"  id="cancel_detail"rows="5" cols="40" tabindex="16"></textarea>
                                     </div>
                                 </div>-->
                                <div id="btn_button">
                                    <input type="submit" value="SUBMIT" name="Submit" class="button_btn_all" tabindex="17">
                                    <!--<input type="submit" value="CANCEL" name="Cancel" class="button_btn_all lft_btn" tabindex="18">-->

                                </div>
                            </form>
                        </div>
                        <!-- Forgot Password-->



                    </div>
                </div>
                <!-- My Profile-->
            </div>
        </div>
    </div>
</div>
