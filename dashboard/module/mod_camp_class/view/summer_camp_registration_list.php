<?php
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
$courobj = new Course();
if ($_GET['show'] == 'all') {
    $InfoData1 = $obj->showAllSummerCampList(false);
} else {
    $InfoData1 = $obj->showAllSummerCampList();
}
$pagingobj = new ITFPagination($urlpath, $perpage);
$InfoData = $pagingobj->setPaginateData($InfoData1);
$objstudent = new Student();
$locationName = $objstudent->ShowAllLocation();
$courseName = $objstudent->ShowAllCourseName();
$classCode = $objstudent->ShowAllSummerClassCode();
$statusList = $objstudent->ShowAllStatus();
//echo '<pre>';print_r($classCode);
?>
<script>
    function closedClass(val)
    {
        var showall = document.getElementById("showClasses").value;
        var x = document.getElementById("showClasses").checked;
        if (x == true)
        {
            urlLink = 'itfmain.php?itfpage=summer_camp&show=all&';
        }
        else
        {
            urlLink = 'itfmain.php?itfpage=summer_camp&';
        }
        window.location = urlLink;
    }
function popStudentListing(url) {
        newwindow = window.open(url, 'name', 'top=200, left=200,toolbar=no, scrollbars=yes, resizable=yes,  width=1200, height=800');
        if (window.focus) {
            newwindow.focus()
        }
        return false;
    }
</script>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo $pagetitle; ?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons" style="width:95%">
        <span style="text-align: left;display: block;float:left;padding-right: 110px;"> 
            <input type="checkbox" name="showClasses" id="showClasses" align="left"  onclick="closedClass()" value="0" <?php echo $_GET['show'] == 'all' ? 'checked="checked"' : '' ?>>Show closed summer camps as well
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
            <label>Select Summer camp Code For Search</label>
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
                    <th scope="col"><a href="itfmain.php?itfpage=summer_camp&actions=class_registration_list&col=loc_name_asc" class="arrow1">&nbsp;</a>Location Name<a href="itfmain.php?itfpage=summer_camp&actions=class_registration_list&col=loc_name_desc" class="arrow2">&nbsp;</a></th>
                    <th scope="col"><a href="itfmain.php?itfpage=summer_camp&actions=class_registration_list&col=course_asc" class="arrow1">&nbsp;</a>Course Name<a href="itfmain.php?itfpage=summer_camp&actions=class_registration_list&col=course_desc" class="arrow2">&nbsp;</a></th>
                    <th scope="col"><a href="itfmain.php?itfpage=summer_camp&actions=class_registration_list&actions=class_registration_list&col=registration_status_asc" class="arrow1">&nbsp;</a>Class Registration Status<a href="itfmain.php?itfpage=summer_camp&actions=class_registration_list&actions=class_registration_list&col=registration_status_desc" class="arrow2">&nbsp;</a></th>
                    <th scope="col">Number of Registered Students</th>
                    <th scope="col">Number of Cancellations</th>

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
                            <td class="align-left"><?php echo $obj->getClasRegistrationStaus($InfoData[$i]['registration_status']); ?></td>

                            <td class="align-left">
                                  <a href="" onclick="popStudentListing('<?php echo SITEURL?>/index.php?itfpage=teacher&itemid=print_admin_student_listing&classId=<?php echo $InfoData[$i]['id'] ?>')"><?php
                                //echo //$obj->showAllCourseName($InfoData[$i]['course_id']);
                                echo $obj->getStudentStatus($InfoData[$i]['id'], 1);
                                ?></a></td>

                            <td class="align-left"><?php echo $obj->getStudentStatus($InfoData[$i]['id'], 2); ?></td>



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