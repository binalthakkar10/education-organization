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
if (isset($_POST["sel_month"])) {
    if ($_POST["sel_month"] != '0' && $_POST["sel_date"] != '0' && $_POST["sel_year"] != '0') {
        $_POST['class_date'] = $_POST["sel_year"] . '-' . '0' . $_POST["sel_month"] . '-' . $_POST["sel_date"];
        if (strtotime($_POST['class_date']) > strtotime($studentDetails['endDate'])) {
            flashMsg("Attendance Date should be less than or equal to end date", "2");
        } else {
            $studentsObj->studentAttandance($_POST);
            flashMsg("Attendance is successfully saved", "2");
            redirectUrl('index.php?itfpage=teacher&itemid=student_listing&classId=' . $_POST['class_id']);
        }
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
                phone: {
                    required: true,
                    maxlength: 15
                }
            },
            messages: {
                first_name: {
                    required: "Please enter a First Name",
                    minlength: "Your First Name must consist of at least 2 characters",
                    maxlength: "Your First Name must consist of maximum 50 characters"
                },
                last_name: {
                    required: "Please enter a First Name",
                    minlength: "Your Last Name must consist of at least 2 characters",
                    maxlength: "Your Last Name must consist of maximum 50 characters"
                },
                primary_name: {
                    required: "Please enter a Primary Contact Name",
                    minlength: "Your Primary Contact Name must consist of at least 2 characters",
                    maxlength: "Your Primary Contact Name must consist of maximum 50 characters"
                }
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
                            <input name="teacher_id" type="hidden" readonly value="<?php echo $studentDetails['teacher_id'] ?>">
                            <input name="student_id" type="hidden" readonly value="<?php echo $studentDetails['student_id'] ?>">
                            <input name="class_id" type="hidden" readonly value="<?php echo $studentDetails['class_id'] ?>">
                            <input name="course_id" type="hidden" readonly value="<?php echo $studentDetails['course_id'] ?>">
                            <input name="id" type="hidden" readonly value="<?php echo $studentDetails['student_id'] ?>">
                          
                            <div id="form_label">
                                <label>Select Date</label>
                                <ul class="date_grid">
                                    <li>
                                        <select id="sel_month" name="sel_month" tabindex="11">
                                            <option value="0">Month</option>
                                            <?php
                                            for ($i = 1; $i <= $end_month; $i++) {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                            ?>  
                                        </select>
                                    </li>
                                    <li>
                                        <select id="sel_date" name="sel_date" tabindex="12">
                                            <option value="0">Date</option>
                                            <?php
                                            for ($i = 1; $i <= 31; $i++) {

                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </li>
                                    <li>
                                        <select id="sel_year" name="sel_year" tabindex="13">
                                            <option value="0">Year</option>
                                            <?php
                                            for ($i = date('Y'); $i <= $end_year; $i++) {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </li>
                                </ul>
                            </div>	

                            <div id="form_label">
                                <label>Attendance</label>
                                <ul class="date_grid att_gird">
                                    <li>
                                        <span class="radio_btn">Absent: </span><input name="attendance" type="radio" value="0" tabindex="14"> 
                                    </li>
                                    <li>
                                        <span class="radio_btn">Present: </span><input name="attendance" type="radio" value="1" checked="checked" tabindex="15">  
                                    </li>

                                </ul>
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