<?php
if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
    redirectUrl(CreateLink(array("teacherlogin")));
    exit;
}
$teacherId = $_SESSION['teacherLoginInfo']['user_id'];
$studentsObj = new Student;
$aruArray = array();
$aruArray['studendId'] = $_GET['studendId'];
$studentDetails = $studentsObj->getStudentsDetailInfo($_GET['studendId']);

$start_date = explode('-', $studentDetails['startDate']);
$end_date = explode('-', $studentDetails['endDate']);
$start_month = $start_date['1'];
$start_day = $start_date['2'];
$start_year = $start_date['0'];
$end_month = $end_date['1'];
$end_day = $end_date['2'];
$end_year = $end_date['0'];
if (isset($_POST["sel_month"])) {
    if ($_POST["sel_month"] != '0' && $_POST["sel_year"] != '0') {
        
    } else {
        flashMsg("Please fill Up Manadatory fields", "2");
    }
}
$validJs = TemplateUrl() . "js2/jquery.validate.js";
?>
<script>
function myFunction() {
    window.print();
}
</script>

<script src="<?php echo $validJs ?>"></script>

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
                             <div id="form_label">
                                <label>Course Name</label>

                                <label> <?php echo $studentDetails[course_name] ?>
                                </label>

                            </div>
                             <div id="form_label">
                                <label>Class</label>

                                <label> <?php echo $studentDetails[class_code] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Location</label>

                                <label> <?php echo $studentDetails[loc_name] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Student First Name</label>

                                <label> <?php echo $studentDetails[first_name] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Student Last Name</label>

                                <label> <?php echo $studentDetails[last_name] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Grade</label>

                                <label> <?php echo $studentDetails[grade_desc] ?>
                                </label>

                                </ul>
                            </div>
                            <div id="form_label">
                                <label>Primary Contact Name</label>

                                <label> <?php echo $studentDetails[primary_name]?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Primary Relationship</label>

                                <label> <?php echo $studentDetails[primary_rel] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Primary Phone</label>

                                <label> <?php echo $studentDetails[primary_phone]?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Primary Email</label>

                                <label> <?php echo $studentDetails[primary_email]?>
                                </label>
                            </div>
                           
                            
                             <div id="form_label">
                                <label>Secondary Contact Name</label>

                                <label> <?php echo $studentDetails[sel_name]?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Secondary Relationship</label>

                                <label> <?php echo $studentDetails[sec_rel] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Secondary Phone</label>

                                <label> <?php echo $studentDetails[sec_phone]?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Secondary Email</label>

                                <label> <?php echo $studentDetails[sec_email]?>
                                </label>
                            </div>
                           
                            <div id="form_label">
                                <label>Select Date</label>
                                <ul class="date_grid">
                                    <li>
                                        <select id="sel_month" name="sel_month" tabindex="11">
                                            <option value="0">Month</option>
                                            <?php
                                            for ($i = 1; $i <= $end_month; $i++) {
                                                if ($i < 10) {
                                                    $i = '0' . $i;
                                                }
                                                if ($_REQUEST['sel_month'] == $i) {
                                                    echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
                                                } else {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                            }
                                            ?>  
                                        </select>
                                    </li>

                                    <li>
                                        <select id="sel_year" name="sel_year" tabindex="13">
                                            <option value="0" >Year</option>
                                            <?php
                                            // ==$i;
                                            for ($i = date('Y'); $i <= $end_year; $i++) {
                                                if ($_REQUEST['sel_year'] == $i) {
                                                    echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
                                                } else {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
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
                                        <label> <a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=export&studendId=<?php echo $studentDetails['student_id'] ?>&class_id=<?php echo $studentDetails['class_id'] ?>&attendanceMonth=<?php echo $_REQUEST['sel_month'] ?>&attendanceYear=<?php echo $_REQUEST['sel_year'] ?>">
                                                Download</a>
                                        </label>
                                    </li>

                                </ul>
                            </div>
                            <div id="btn_button">
                                    <input type="button" value="PRINT" name="" class="button_btn_all " tabindex="16" onclick="myFunction()">
                              
                                <input type="submit" value="SUBMIT" name="Submit" class="button_btn_all lft_btn" tabindex="16">
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