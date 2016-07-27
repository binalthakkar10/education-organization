<?php
if (empty($_SESSION['LoginInfo']['USERID'])) {
    redirectUrl(CreateLink(array("teacherlogin")));
    exit;
}
$objstudent = new Student();
$studentList = $objstudent->getStudentsAdminPrint($_GET['classId']);
//echo '<pre>';print_r($studentList);exit;
?>
<script>
    function myFunction() {
        window.print();
    }
</script>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">

        <div id="page_content">


            <!-- My Profile -->
            <div id="gallery">
                <div id="title_teacher_login"><h2><!--<?php echo $studentList['0']['class_code'] ?> Student Listing-->Roster</h2></div>

                <div class="list_classes">
                    <div id="gallery">
                        <!-- Forgot Password-->
                        <form method="post" action="" name="frm" id="frm">
                            <div id="form_label">
                                <label>Course Name</label>

                                <label> <?php echo $studentList['0'][course_name] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Class</label>

                                <label> <?php echo $studentList['0'][class_code] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Room</label>

                                <label> <?php echo $studentList['0'][room] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Location Name</label>

                                <label> <?php echo $studentList['0'][loc_name] ?>
                                </label>

                            </div>
                            <div id="form_label">
                                <label>Address</label>

                                <label> <?php echo $studentList['0'][loc_address] ?>
                                </label>

                            </div>
                           
                            
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
