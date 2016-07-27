<?php
if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
    redirectUrl(CreateLink(array("teacherlogin")));
    exit;
}
$teacherId = $_SESSION['teacherLoginInfo']['user_id'];
$studentsObj = new Student;
$aruArray = array();
$aruArray['studendId'] = $_GET['studendId'];
$studentDetails = $studentsObj->getStudentDetails($aruArray);
//echo '<pre>';
//print_r($studentDetails);
$start_date = explode('-', $studentDetails['startDate']);
$end_date = explode('-', $studentDetails['endDate']);
$start_month = $start_date['1'];
$start_day = $start_date['2'];
$start_year = $start_date['0'];
$end_month = $end_date['1'];
$end_day = $end_date['2'];
$end_year = $end_date['0'];
if (isset($_POST["first_name"])) {
 if ($_POST["first_name"] != '' && $_POST["first_name"] != '' && $_POST["primary_name"] != '') {
        $studentsObj->adminUpdate($_POST);
        flashMsg("data has been updated successfully", "2");
       // echo $_POST['class_id'];exit;
        redirectUrl('index.php?itfpage=teacher&itemid=student_listing&classId='.$_POST['class_id']);
    } else {
        flashMsg("Please fill Up Manadatory fields", "2");
    }
}
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
                    minlength: 2,
                    maxlength: 50
                },
                last_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                primary_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 15
                },
                primary_contact: {
                    minlength: 10,
                    maxlength: 15
                },
                primary_rel: {
                    minlength: 2,
                    maxlength: 50
                },
                sec_email: {
                    email: true
                }
            },
            messages: {
                first_name: {
                    required: "Please enter a First Name",
                    minlength: "First Name must consist of at least 2 characters",
                    maxlength: "First Name must consist of maximum 50 characters"
                },
                last_name: {
                    required: "Please enter a Last Name",
                    minlength: "Last Name must consist of at least 2 characters",
                    maxlength: "Last Name must consist of maximum 50 characters"
                },
                primary_name: {
                    required: "Please enter a Primary Contact Name",
                    minlength: "Primary Contact Name must consist of at least 2 characters",
                    maxlength: "Primary Contact Name must consist of maximum 50 characters"
                },
                primary_contact: {
                    minlength: "Primary Contact must consist of at least 10 characters",
                    maxlength: "Primary Contact must consist of maximum 15 characters"
                },
                primary_rel: {
                    minlength: "Your Last Name must consist of at least 2 characters",
                    maxlength: "Your Last Name must consist of maximum 50 characters"
                },
                sec_email: "Please enter a valid email."
            }

        });

    });
</script>
<style>
    #gallery ul{
        margin:0px;	
    }
</style>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <div id="page_title">
            <h1>Teacher <span style="color:#ab281f;">Dashboard</span></h1>
        </div>
        <div id="page_content">
            <?php include_once('left_menu.php') ?>
            <div class="right_content">
                <!-- My Profile -->
                <div id="gallery">
                    <!-- Forgot Password-->
                    <div class="teacher_login_page stu_dash" id="atten">
                        <div id="title_teacher_login"><h2>Student Form</h2></div>
                        <form method="post" action="" name="frm" id="frm">
                            <input name="teacher_id" type="hidden"   value="<?php echo $studentDetails['teacher_id'] ?>">
                            <input name="student_id" type="hidden"   value="<?php echo $studentDetails['student_id'] ?>">
                            <input name="class_id" type="hidden"   value="<?php echo $studentDetails['class_id'] ?>">
                            <input name="course_id" type="hidden"   value="<?php echo $studentDetails['course_id'] ?>">
                            <input name="id" type="hidden"   value="<?php echo $studentDetails['student_id'] ?>">
                            <div id="form_label">
                                <label>Class Code</label>
                                <input name="class_code" type="text"  disabled value="<?php echo $studentDetails['class_code'] ?>" tabindex="7">
                            </div>
                            <div id="form_label">
                                <label>Teacher Name</label>
                                <input name="teacher_name" type="text"  disabled value="<?php echo $studentDetails['teacher_name'] ?>" tabindex="8">
                            </div>
                            <div id="form_label">
                                <label>Student First Name<span>*</span></label>
                                <input name="first_name" id="first_name" type="text"  value="<?php echo $studentDetails['first_name'] ?>" tabindex="1">
                            </div>
                            <div id="form_label">
                                <label>Student Last Name<span>*</span></label>
                                <input name="last_name" id="last_name" type="text"  value="<?php echo $studentDetails['last_name'] ?>" tabindex="2">
                            </div>
                            <div id="form_label">
                                <label>Primary Contact Name<span>*</span></label>
                                <input name="primary_name" id="primary_name" type="text"  value="<?php echo $studentDetails['primary_name'] ?>" tabindex="3">
                            </div>
                            <div id="form_label">
                                <label>Primary Contact</label>
                                <input name="primary_contact" id="primary_contact" type="text"  value="<?php echo $studentDetails['primary_contact'] ?>" tabindex="3">
                            </div>
                            <div id="form_label">
                                <label>Primary Relationship</label>
                                <input name="primary_rel" id="primary_rel" type="text"  value="<?php echo $studentDetails['primary_rel'] ?>" tabindex="4">
                            </div>
                            <div id="form_label">
                                <label>Secondary Contact Name</label>
                                <input name="sec_name" id="sec_name" type="text"  value="<?php echo $studentDetails['sec_name'] ?>" tabindex="5">
                            </div>
                            <div id="form_label">
                                <label>Secondary Contact</label>
                                <input name="sec_contact" id="sec_contact" type="text"  value="<?php echo $studentDetails['sec_contact'] ?>" tabindex="5">
                            </div>
                            <div id="form_label">
                                <label>Secondary Email</label>
                                <input name="sec_email" id="sec_email " type="text"  value="<?php echo $studentDetails['sec_email'] ?>" tabindex="5">
                            </div>
                            <div id="form_label">
                                <label>Secondary Relationship</label>
                                <input name="sec_rel" id="sec_rel" type="text"  value="<?php echo $studentDetails['sec_rel'] ?>" tabindex="6">
                            </div>





                            <div id="btn_button">
                                <input type="submit" value="SUBMIT" name="Submit" class="button_btn_all" tabindex="16">
                               <!-- <input type="submit" value="CANCEL" name="Cancel" class="button_btn_all lft_btn">-->

                            </div>
                        </form>
                    </div>
                    <!-- Forgot Password-->



                </div>
                <!-- My Profile-->
            </div>
        </div>
    </div>
</div>
