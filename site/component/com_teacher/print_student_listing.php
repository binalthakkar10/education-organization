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
$objstudent = new Student();
$studentList = $objstudent->getStudentsDownload('', $_GET['classId'], $teacherId);
//echo '<pre>';print_r($studentList);
?>
<script>
    function myFunction() {
        window.print();
    }
</script>
<style>
    .main_wrapper{
        width:1102px;	
        max-width:1102px;
    }
    #mid_wrapper{
        padding: 20px 2px;	
    }
    .list_classes table th, .list_classes table td{padding: 10px 1px;/*border: 1px solid #274A90;*/}
    .list_classes table td {
        font-size: 11px;
    }
    .list_classes table th {
        font-size: 11px;
        color:#000;
    }
    .list_classes table{
        width:93%;	
    }
</style>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">

        <div id="page_content">


            <!-- My Profile -->
            <div id="gallery">
                <div id="title_teacher_login"><h2><!--<?php echo $studentList['0']['class_code'] ?> Student Listing-->Roster</h2></div>

                <div class="list_classes">
                    <div id="gallery" class="listing_pg3">
                        <!-- Forgot Password-->
                        <form method="post" action="" name="frm" id="frm">

                            <ul class="stu_listing_grid">
                                <li><div id="form_label">
                                        <label class="label_w">Course Name:</label>

                                        <label> <?php echo $studentList['0'][course_name] ?>
                                        </label>

                                    </div>
                                </li>
                                <li><div id="form_label">
                                        <label class="label_w">Location Name:</label>

                                        <label> <?php echo $studentList['0']['loc_name'] ?>
                                        </label>

                                    </div></li>

                                <li><div id="form_label">
                                        <label class="label_w">Notes:</label>

                                        <label> <?php echo $studentList['0']['notes'] ?>
                                        </label>

                                    </div></li>
                                <li><div id="form_label">
                                        <label class="label_w">Room:</label>
                                        <label> <?php echo $studentList['0']['room'] ?>       </label>
                                        <label class="label_w">Address:</label>

                                        <label> <?php echo $studentList['0'][loc_address] ?>
                                        </label>

                                    </div></li>


                            </ul>




                            <div class="teacher_login_page stu_dash" id="atten">


                                <table cellspacing="0" cellpadding="0" border="1">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Grade</th>
                                            <th>Primary Contact Name</th>
                                            <th>Primary Relationship</th>
                                            <th>Primary Phone</th>
                                            <th>Primary Email</th>
                                            <th>Secondary Contact Name</th>
                                            <th>Secondary Relationship</th>
                                            <th>Secondary Phone</th>
                                            <th>Secondary Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        if (count($studentList) > 0) {
                                            foreach ($studentList as $values) {
                                                $i++;
                                                ?>
                                                <tr>
                                                    <td><?php echo $values['first_name'] ?></td>
                                                    <td><?php echo $values['last_name'] ?></td>
                                                    <td><?php echo $values['grade_desc'] ?></td>
                                                    <td><?php echo $values['primary_name'] ?></td>
                                                    <td><?php echo $values['primary_rel'] ?></td>
                                                    <td><?php echo $values['primary_contact'] ?></td>
                                                    <td><?php echo $values['primary_email'] ?></td>
                                                    <td><?php echo $values['sec_name'] ?></td>
                                                    <td><?php echo $values['sec_rel'] ?></td>
                                                    <td><?php echo $values['sec_contact'] ?></td>
                                                    <td><?php echo $values['sec_email'] ?></td>
                                                </tr> <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="11">No Records Available</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                            <div id="btn_button">
                                <input type="button" value="PRINT" name="" class="button_btn_all " tabindex="16" onclick="myFunction()">
                            </div>
                        </form>

                    </div>
                    <!-- My Profile-->
                </div><!-- right_content -->
            </div>

        </div>
