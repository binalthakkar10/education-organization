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
?>
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
                    <div id="title_teacher_login"><h2>Student Listing</h2></div>

                    <div class="list_classes">

                        <table cellspacing="0" cellpadding="0" border="1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Attendance</th>
                                    <th>Edit</th>
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
                                            <td><?php echo $i ?></td>

                                            <td><?php echo $values['student_name'] ?></td>
                                            <td><?php echo date('m/d/Y', strtotime($values['start_date'])) ?></td>
                                            <td><?php echo date('m/d/Y', strtotime($values['end_date'])) ?></td>
                                            <td><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=student_attendance&studendId=<?php echo $values['id'] ?>">Attendance</a></td>
                                            <td><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=student_edit&studendId=<?php echo $values['id'] ?>">Edit</a></td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="11">No Records Available</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- My Profile-->
            </div><!-- right_content -->
        </div>
    </div>
</div>