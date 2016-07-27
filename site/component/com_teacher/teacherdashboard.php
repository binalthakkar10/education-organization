<?php
if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
    redirectUrl(CreateLink(array("teacherlogin")));
    exit;
}
//echo '<pre>';print_r($_REQUEST);exit;
if ($_GET['type']!='1' || $_GET['type']!='2' || $_GET['type'] !=''&& $_GET['future']!='' || $_GET['future']!='yes') {
   // redirectUrl(CreateLink(array("index")));
    //exit;
}
$first_day_this_month = date('m-01-Y'); // hard-coded '01' for first day

$teacherArray = array();
$teacherArray['teacherId'] = $_SESSION['teacherLoginInfo']['user_id'];
$teacherArray['future'] = $_GET['future'];
$teacherArray['type'] = $_GET['type'];
$teacherId = $_SESSION['teacherLoginInfo']['user_id'];
$classObj = new Class1;
$classList = $classObj->showTeacherClasslist($teacherArray);

$listingName=$_GET['type']==2?'Summer Camps':'Classes';
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
                    <div id="title_teacher_login"><h2><?php echo $listingName?> Listing</h2></div>

                    <div class="list_classes">
                        <table cellspacing="0" cellpadding="0" border="1">
                            <thead>
                                <tr>
                                    <th>Course Name</th>
                                    <!--<th>Room</th>-->
                                    <th>Location</th>
                                    <th>Address</th>
                                    <th>Day of Week</th>
                                     <th>Start Date -End Date</th>
                                    <th>Start time -Duration</th>
                                    <th>Note</th>
                                    <th>Status</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($classList) > 0) {
                                    foreach ($classList as $values) {
                                        ?>
                                        <tr>
                                            <td width="5%"><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=class_student_listing&classId=<?php echo $values['id'] ?>"><?php echo $values['course_name'] ?></a></td>
                                            <!--<td width="8%"><?php echo $values['room'] ?></td>-->
                                            <td width="8%"><?php echo $values['loc_name'] ?></td>
                                            <td width="12%"><?php echo wordwrap($values['address'], 15, "<br>\n"); ?></td>

                                            <td width="7%"><?php echo $classObj->getDayofweek($values['day_of_week']) ?></td>
                                             <td width="7%"><?php echo date('m/d/Y', strtotime($values['start_date'])) ?> -<?php echo date('m/d/Y', strtotime($values['end_date'])) ?></td>
                                            <td width="12%"><?php echo $values['class_time']; ?>- <?php echo $values['duration'] ?></td>
                                            <td width="12%"> 
                                                <?php
                                                echo $values['notes'];
                                                //echo substr($values['notes'], 0, 20);
                                                // echo wordwrap($values['notes'], 20, "<br>\n");
                                                ?></td>
                                            <td width="12%"><?php echo $classObj->getClassRegistrationStatusName($values['registration_status']); ?></td>

                                            <td width="12%"><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=add_student&classId=<?php echo $values['id'] ?>">Add Student</a></td>
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
