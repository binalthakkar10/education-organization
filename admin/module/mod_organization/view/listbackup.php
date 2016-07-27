<?php
error_reporting(0);
if (isset($_POST['itf_datasid'], $_POST['itfactions'])) {
    $userids = isset($_POST['itf_datasid']) ? $_POST['itf_datasid'] : "0";
    //print_r($userids);
    $acts = $_POST['itfactions'];
    $ids = implode(',', $_POST['itf_datasid']);
    if ($acts == 'delete') {
        $obj->Class_deleteAdmin($ids);
        $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
        flash($pagetitle . " is successfully deleted");
    }
    redirectUrl($urlname);
}
$perpage = $stieinfo['paging_size']; //limit in each page
$urlpath = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids)) . "&";
//echo "list1";
$courobj = new Course();
if ($_GET['show'] == 'all') {
    $InfoData1 = $obj->showAllClassList(false, $_REQUEST);
} else {
    $InfoData1 = $obj->showAllClassList(true, $_REQUEST);
}
$countTotal = count($InfoData1);
$pagingobj = new ITFPagination($urlpath, $perpage);
$InfoData = $pagingobj->setPaginateData($InfoData1);
$urlpath = CreateLinkAdmin(array($currentpagenames)) . "&show=all";
$objstudent = new Student();
$locationName = $objstudent->ShowAllLocation();
$courseName = $objstudent->ShowAllCourseName();
$classCode = $objstudent->ShowAllClassCode();
$statusList = $objstudent->ShowAllStatus();
?>
<script>
    function closedClass(val)
    {
        //alert(val);
        var showall = document.getElementById("showClasses").value;
        var x = document.getElementById("showClasses").checked;
        //var urlLink;
        if (x == true)
        {
            urlLink = 'itfmain.php?itfpage=class&show=all&';
        }
        else
        {
            urlLink = 'itfmain.php?itfpage=class&';
        }
        window.location = urlLink;
    }
    function popitup(url) {
        newwindow = window.open(url, 'name', 'height=300,width=250');
        if (window.focus) {
            newwindow.focus()
        }
        return false;
    }
</script>
<!-- Box -->
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo 'List of All Classes'; ?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons" style="width:95%">
        <span style="text-align: left;display: block;float:left;padding-right: 110px;"> 
            <input type="checkbox" name="showClasses" id="showClasses" align="left"  onclick="closedClass()" value="0" <?php echo $_GET['show'] == 'all' ? 'checked="checked"' : '' ?>>Show closed classes as well
        </span>
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'add', 'parentid' => $parentids)); ?>" class="button add"><span>Add New <?php echo $pagetitle; ?></span></a>
        <a onclick="return itfsubmitfrm('delete', 'itffrmlists');" class="button cancel"><span>Delete</span></a>
        <button onclick="history.back()" type="button">Back</button>
    </div>
    <form id="itffrmsearch" name="itffrmsearch" method="post" action="">

        <input type="hidden" name="itfpage" value="<?php echo $currentpagenames; ?>" />
        <div class="element">
            <label>Select Location For Search</label>
            <select name="loc_id" id="loc_id" onchange="update(this.value)">
                <option value="">---Select Location Name--- </option>
                <?php
                foreach ($locationName as $item) {
                    $idtest = '';
                    $locationId = $_REQUEST['loc_id'];
                    ?>
                    <option value="<?php echo $item['id']; ?>"   <?php if ($locationId == $item['id']) { ?>selected="selected"<?php } ?>><?php echo ucfirst($item['code'] . ' - ' . $item['name']); ?> </option>
                <?php } ?>
            </select>     
        </div>  

        <div class="element">
            <label>Select Course For Search</label>
            <select name="course" id="loc_id">
                <option value="">---Select Course Name--- </option>
                <?php
                $courseId = $_REQUEST['course'];
                foreach ($courseName as $courseItem) {
                    ?>
                    <option value="<?php echo $courseItem['id']; ?>"   <?php if ($courseId == $courseItem['id']) { ?>selected="selected"<?php } ?>><?php echo ucfirst($courseItem['name']); ?> </option>
                <?php } ?>
            </select>     
        </div>  

        <div class="element">
            <label>Select Class Code For Search</label>
            <select name="classCode" id="classCode" >
                <option value="">---Select Class Code--- </option>
                <?php
                foreach ($classCode as $classitem) {
                    //$idtest = '';
                    $classId = $_REQUEST['classCode'];
                    ?>
                    <option value="<?php echo $classitem['code']; ?>"   <?php if ($classId == $classitem['code']) { ?>selected="selected"<?php } ?>><?php echo ucfirst($classitem['code']); ?> </option>
                <?php } ?>
            </select>     
        </div> 

        <!-- <div class="element">
             <label>Select Student Status For Search</label>
             <select name="status" id="status" onchange="">
                 <option value="">---Select Registration Status--- </option>
        <?php
        foreach ($statusList as $item) {
            $status = $_REQUEST['status'];
            ?>
                                                                     <option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $status) { ?>selected="selected"<?php } ?>><?php echo $item['status_name']; ?> </option>
        <?php } ?>
             </select>
         </div>-->
        <input name="searchuser" type="submit" id="searchuser"  value="search" />
    </form> 
    <div class="clear"></div>
    <form id="itffrmlists" name="itffrmlists" method="post" action="">
        <input type="hidden" name="itfactions" id="itfactions" value="" />
        <input type="hidden" name="itf_status" id="itf_status" value="" />

        <table>

            <thead>
                <tr>
                    <th scope="col">&nbsp;<input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>
                    <!--<th scope="col" class="arrow">Location12 Name <a href="" class="arrow1">&nbsp;</a><a href="" class="arrow2">&nbsp;</a></th>-->
                    <th scope="col"><a href="itfmain.php?itfpage=class&col=loc_name_asc" class="arrow1">&nbsp;</a>Location Name<a href="itfmain.php?itfpage=class&col=loc_name_desc" class="arrow2">&nbsp;</a></th>
                    <th scope="col"><a href="itfmain.php?itfpage=class&col=course_asc" class="arrow1">&nbsp;</a>Course Name<a href="itfmain.php?itfpage=class&col=course_desc" class="arrow2">&nbsp;</a></th>
                    <th scope="col"><a href="itfmain.php?itfpage=class&col=start_date_asc" class="arrow1">&nbsp;</a>Start Date<a href="itfmain.php?itfpage=class&col=start_date_desc" class="arrow2">&nbsp;</a></th>
                    <th scope="col"><a href="itfmain.php?itfpage=class&col=end_date_asc" class="arrow1">&nbsp;</a>End Date<a href="itfmain.php?itfpage=class&col=end_date_desc" class="arrow2">&nbsp;</a></th>      
                    <th scope="col">Day of Week</th>
                    <th scope="col">Class Time</th>
                    <th scope="col">Teacher Assigned</th>

                    <th scope="col">Class Registration Status</th>
                    <th scope="col" style="width: 65px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($InfoData) > 0) {
                    for ($i = 0; $i < count($InfoData); $i++) {
                        ?>
                        <tr>
                            <td class="align-center"><input name="itf_datasid[]" type="checkbox" value="<?php echo $InfoData[$i]['id']; ?>" class="itflistdatas"/></td>
                            <td class="align-left"><?php echo $InfoData[$i]['loc_name']; ?></td> 
                            <td class="align-left"><?php echo $InfoData[$i]['name']; ?></td> 
                            <td class="align-left"><?php echo date('m/d/Y', strtotime($InfoData[$i]['start_date'])); ?></td>
                            <td class="align-left"><?php echo date('m/d/Y', strtotime($InfoData[$i]['end_date'])); ?></td>
                            <td class="align-left"><?php echo $obj->getDayofweek($InfoData[$i]['day_of_week']); ?></td> 
                            <td class="align-left"><?php echo $InfoData[$i]['class_time']; ?></td> 
                            <td class="align-left"><?php
                                //echo //$obj->showAllCourseName($InfoData[$i]['course_id']);
                                echo  $obj->getClassTeacherNames($InfoData[$i]['teacher_assigned']);
                                ?></td>

                            <td class="align-left"><?php echo $obj->getClasRegistrationStaus($InfoData[$i]['registration_status']); ?></td>
                            <!--<td class="align-left">
                            <?php
                            if ($obj->getStudentCount($InfoData[$i]['id']) > 0) {
                                ?>
                                                    <a href='student_count.php?id=<?php echo $InfoData[$i]["id"] ?>' onclick="return popitup('student_count.php?id=<?php echo $InfoData[$i]["id"] ?>')"
                                                       ><?php echo $obj->getStudentCount($InfoData[$i]['id']) ?></a>

                                <?php
                            } else {
                                echo 0;
                            }
                            ?>
                            </td>-->


                            <td class="align-center">
                                <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'edit', "parentid" => $parentids, 'id' => $InfoData[$i]['id'])); ?>" title="Edit <?php echo $pagetitle; ?> " alt="Edit <?php echo $pagetitle; ?>"><img src="img/i_edit.png" border="0" /></a> &nbsp;

                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="10" class="align-center">No Record Available !</td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </form>
    <div class="entry">
        <div class="pagination">
            <?php echo $pagingobj->Pages(); ?>
        </div>
        <div class="sep"></div>
    </div>


</div>
<!-- End Box -->