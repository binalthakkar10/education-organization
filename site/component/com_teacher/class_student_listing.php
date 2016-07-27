<?php
if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
    redirectUrl(CreateLink(array("teacherlogin")));
    exit;
}
$teacherId = $_SESSION['teacherLoginInfo']['user_id'];
$classObj = new Class1;
$aruArray = array();
$aruArray['teacherId'] = $teacherId;
$aruArray['classId'] = $_GET['classId'];

$studentList = $classObj->getClassStudtenListing($aruArray);
//echo '<pre>';print_r($studentList);
$start_date = explode('-', $studentList['0']['start_date']);
$end_date = explode('-', $studentList['0']['end_date']);
$start_month = $start_date['1'];
$start_day = $start_date['2'];
$start_year = $start_date['0'];
$end_month = date('m');
$end_day = date('d');
$end_year = date('Y');
$studentsObj = new Student;
if ($_REQUEST['class_date'] != '') {
    $todayDate1 = $_REQUEST['class_date'];
} else {
    $todayDate1 = date('m/d/Y');
}
if (isset($_POST["SUBMIT"])) {
    if ($_POST["class_date"] != '' && $_POST["SUBMIT"] = 'SUBMIT') {
        $attendanceDate = date('Y-m-d', strtotime($_POST["class_date"]));
        $todayDate = date('Y-m-d');
        $_POST['class_date'] = $attendanceDate;
        if (strtotime($attendanceDate) > strtotime($todayDate)) {
            flashMsg("Attendance Date should not be greater  than today date", "2");
        } else {

            $data = array();
            $data['teacher_id'] = $_SESSION['teacherLoginInfo']['user_id'];
            $data['class_id'] = $studentList['0']['class_id'];
            $data['course_id'] = $studentList['0']['course_id'];
            $data['class_date'] = $_POST['class_date'];
            for ($i = 1; $i <= count($studentList); $i++) {
                $data['student_id'] = str_replace($i . '__', '', $_POST[$i . '__student_id']);
                $data['attendance'] = str_replace($i . '__', '', $_POST[$i . '__attendance']);
                $studentsObj->studentAttandance($data);
            }
            flashMsg("Attendance is successfully saved", "2");
            redirectUrl('index.php?itfpage=teacher&itemid=class_student_listing&classId=' . $data['class_id']);
        }
    } else {
        flashMsg("Please fill Up Manadatory fields", "2");
    }
}
$daylen = 60 * 60 * 24;
if (count($studentList) > 0) {
    $date1 = date('Y-m-d');
    $date2 = $studentList['0']['start_date'];
    $dateDiff = (strtotime($date1) - strtotime($date2)) / $daylen;
    if ($dateDiff < 1) {
        $dateDiff = 0;
    }
} else {
    $dateDiff = 30;
}
?>
<link rel="stylesheet" href="<?php echo TemplateUrl() ?>/css/jquery-ui.css">
<script src="<?php echo TemplateUrl() ?>/js2/jquery-1.10.2.js"></script>
<script src="<?php echo TemplateUrl() ?>/js2/jquery-ui.js"></script>
<script>
    $(function() {
        $("#datepicker").datepicker({minDate: -<?php echo $dateDiff ?>, maxDate: "0D"});
    });


    function popStudentListing(url) {
        //  alert('111111111111');
        newwindow = window.open(url, 'name', 'top=200, left=200,toolbar=no, scrollbars=yes, resizable=yes,  width=1200, height=800');
        if (window.focus) {
            newwindow.focus()
        }
        return false;
    }


</script>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <div id="page_title">
            <h1>Teacher <span style="color:#ab281f;">Dashboard</span></h1>
        </div>
        <div id="page_content">
            <?php include_once('left_menu.php') ?>
            <div class="right_content">
                <!-- My Profile -->
                <div id="gallery" class="Attendance_Roster">
                    <div id="title_teacher_login"><h2><!--<?php echo $studentList['0']['class_code'] ?> Student Listing-->Roster</h2></div>
                    <form method="post" action="" name="frm" id="frm">
                        <div class="list_classes">
                            <div id="gallery">
                                <!-- Forgot Password-->

                                <div class="teacher_login_page stu_dash" id="atten">
                                    <div id="form_label">
                                        <label>Select Date</label>
                                        <input type="text" id="datepicker" value="<?php echo $todayDate1; ?>" name="class_date" style="width:100px"/>
                                    </div>

                                    <table cellspacing="0" cellpadding="0" border="1">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>&nbsp;</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            $totalStudent = count($studentList);
                                            if (count($studentList) > 0) {
                                                foreach ($studentList as $values) {
                                                    $i++;
                                                    ?>
                                                <input name="<?php echo $i ?>__teacher_id" type="hidden" readonly value="<?php echo $values['teacher_assigned'] ?>">
                                                <input name="<?php echo $i ?>__student_id" type="hidden" readonly value="<?php echo $values['id'] ?>">
                                                <input name="<?php echo $i ?>__class_id" type="hidden" readonly value="<?php echo $values['class_id'] ?>">
                                                <input name="<?php echo $i ?>__course_id" type="hidden" readonly value="<?php echo $values['course_id'] ?>">

                                                <tr>
                                                    <td><?php echo $i ?></td>
                                                    <td><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=student_download_attendance&studendId=<?php echo $values['id'] ?>"><?php echo $values['student_name'] ?></a></td>
                                                    <!--<td><?php echo date('m/d/Y', strtotime($values['start_date'])) ?></td>
                                                    <td><?php echo date('m/d/Y', strtotime($values['end_date'])) ?></td>
                                                    --> 
                                                    <td> 
                                                        <span class="radio_btn">Absent: </span><input name="<?php echo $i ?>__attendance" type="radio" value="0" tabindex="14"> 
                                                        <span class="radio_btn">Present: </span><input name="<?php echo $i ?>__attendance" type="radio" value="1" checked="checked" tabindex="15">  
                                                    </td>
                                                </tr> <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="11">No Records Available</td></tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>

                                </div>
                                <?php if ($dateDiff > 0 && $totalStudent > 0) { ?>

                                    <div id="form_label">
                                        <label><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=export_all&&class_id=<?php echo $_GET['classId'] ?>&attendanceMonth=<?php echo $_REQUEST['sel_month'] ?>&attendanceYear=<?php echo $_REQUEST['sel_year'] ?>">
                                                Download Attendance</a></label>
                                    </div>
                                <?php } ?>
                                <br/><br/>
                                <div id="btn_button">
                                    <?php //if ($dateDiff > 0 && $totalStudent > 0) {
                                        if ($totalStudent > 0) {
                                    ?>

                                        <input type="submit" value="ROSTER" name="ROSTER" class="button_btn_all" onclick="popStudentListing('index.php?itfpage=teacher&itemid=print_student_listing&classId=<?php echo $studentList['0']['class_id'] ?>')" style="margin-right: 20px;width:85px;">
                                    <?php } ?>
                                    <input type="submit" value="BACK" name="Cancel" class="button_btn_all " onclick="history.go(-1);
                                            return false;" style="margin-right: 20px;">
                                           <?php //if ($dateDiff > 0 && $totalStudent > 0) {
                                                  if ($totalStudent > 0) {
                                               ?>
                                        <input type="submit" value="SUBMIT" name="SUBMIT" class="button_btn_all lft_btn" tabindex="16">
                                    <?php } ?>
                                </div>


                            </div>
                            <!-- My Profile-->
                        </div><!-- right_content -->
                    </form>

                </div>
            </div>
        </div>