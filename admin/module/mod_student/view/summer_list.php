<?php
error_reporting(0);
$objstudent = new Student();
if (isset($_POST['itf_datasid'], $_POST['itfactions'])) {
    $acts = $_POST['itfactions'];
    $ids = implode(',', $_POST['itf_datasid']);

    if ($acts == 'delete')
        $objstudent->deleteStudentDetails($ids);
    flash("Student is succesfully deleted");
    redirectUrl("itfmain.php?itfpage=student&actions=summer_list");
}
if ($_REQUEST['col'] != '')
    $txtsearch['ordering'] = $_REQUEST['col'];

$InfoData1 = $objstudent->searchSummerStudents($_REQUEST);
$perpage = $stieinfo['paging_size'];
$urlpath = CreateLinkAdmin(array($currentpagenames)) . "&";
$pagingobj = new ITFPagination($urlpath, $perpage);
$InfoData = $pagingobj->setPaginateData($InfoData1);
$locationName = $objstudent->ShowAllLocation();
$courseName = $objstudent->ShowAllCourseName();
$classCode = $objstudent->ShowAllClassCode();
?>
<script type="text/javascript">
    function update(str)
    {
        var xmlhttp;

        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                // document.getElementById("data").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "itf_ajax/auto.php?opt=" + str, true);
        xmlhttp.send();
    }
</script>
<div class="full_w">
    <div class="h_title"><?php echo $pagetitle; ?></div>


    <div class="topcontroller"> 
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'summer_add')); ?>" class="button add"><span>Add New <?php echo $pagetitle; ?></span></a> 
        <a onclick="return itfsubmitfrm('delete', 'itffrmlists');" class="button cancel"><span>Delete</span></a>
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
            <label>Select Registration Status For Search</label>
            <select name="reg_status" id="reg_status" onchange="">
                <option value="">---Select Registration Status--- </option>
                <?php
                foreach ($statusList as $item) {
                    $idtest = $item['id'];
                    $status = $_REQUEST['reg_status'];
                    ?>
                    <option value="<?php echo $item['id']; ?>"   <?php if ($idtest == $status) { ?>selected="selected"<?php } ?>><?php echo $item['status_name']; ?> </option>
                <?php } ?>
            </select>
        </div>


        <!-- <div class="element">
             <label>Select Class Code For Search</label>
             <select name="classCode" id="classCode" onchange="update(this.value)">
                 <option value="">---Select Class Code--- </option>
        <?php
        foreach ($classCode as $classitem) {
            //$idtest = '';
            $classId = $_REQUEST['classCode'];
            ?>
                                     <option value="<?php echo $classitem['id']; ?>"   <?php if ($classId == $classitem['id']) { ?>selected="selected"<?php } ?>><?php echo ucfirst($classitem['code']); ?> </option>
        <?php } ?>
             </select>     
         </div>  -->
        <input name="searchuser" type="submit" id="searchuser"  value="search" />
    </form> 
    <div class="table">
        <a href="<?php echo CreateLinkAdmin(array('report', 'actions' => 'export', 'type' => 2, 'requestFrom' => 'admin')); ?>">Download Student Data</a>

        <form id="itffrmlists" name="itffrmlists" method="post" action="">
            <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                <tr>
                    <th width="4%" align="center"  class="tblhead">&nbsp;
                        <input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>
                    <th scope="col" class="arrow" width="11%"><a href="itfmain.php?itfpage=student&col=first_name_asc" class="arrow1">&nbsp;</a>First Name<a href="itfmain.php?itfpage=student&col=first_name_desc" class="arrow2">&nbsp;</a></th>
                    <th scope="col" class="arrow" width="11%"><a href="itfmain.php?itfpage=student&col=last_name_asc" class="arrow1">&nbsp;</a>Last Name<a href="itfmain.php?itfpage=student&col=last_name_desc" class="arrow2">&nbsp;</a></th>

                    <th width="12%"  class="tblhead"><a href="itfmain.php?itfpage=student&col=email_asc" class="arrow1">&nbsp;</a>Email<a href="itfmain.php?itfpage=student&col=email_desc" class="arrow2">&nbsp;</a> </th>
                      <!--<th width="14%"  class="tblhead">Student Id</th>
                                <th width="9%" align="center"  class="tblhead">Location</th>-->
                    <th width="8%" align="center"  class="tblhead"><a href="itfmain.php?itfpage=student&col=status_asc" class="arrow1">&nbsp;</a>Status<a href="itfmain.php?itfpage=student&col=status_desc" class="arrow2">&nbsp;</a></th>
                    <th width="6%" align="center"  class="tblhead">Action</th>
                </tr>
                <?php
                if (isset($InfoData) and $InfoData != null) {
                    for ($i = 0; $i < count($InfoData); $i++) {
                        ?>
                        <tr class="<?php echo ($i % 2 == 0) ? "odd" : ""; ?>" >
                            <td class="align-center"><input name="itf_datasid[]" type="checkbox" value="<?php echo $InfoData[$i]['id']; ?>" class="itflistdatas"/></td>

                            <td><?php echo $InfoData[$i]['first_name']; ?></td>
                            <td><?php echo $InfoData[$i]['last_name']; ?></td>
                            <td><?php echo $InfoData[$i]['primary_email']; ?></td>
                            <!--<td><?php // echo $InfoData[$i]['student_uni_id'];                                                  ?></td>
                            <td><a href="<?php // echo CreateLinkAdmin(array("$currentpagenames",'actions'=>'locateonmap','id'=>$InfoData[$i]['id']));                                                  ?>">Location</a></td>-->
                            <td align="center">
                                <?php
                                $statusvalues = $objstudent->getStatusbyID($InfoData[$i]['reg_status']);
                                echo $statusvalues['status_name'];
                                ?> 
                            </td>
                            <td align="center"><a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'summer_add', 'id' => $InfoData[$i]['id'])); ?>" title="Edit User" alt="Edit User"><img src="img/i_edit.png" border="0" /></a></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo ("<tr> <td colspan=\"10\" align=center>No Records Exits.</td></tr>");
                }
                ?>
            </table>
        </form>

        <!-- Pagging -->

        <div class="pagging">

            <div class="right">

                <?php echo $pagingobj->Pages(); ?>

            </div>

        </div>
    </div>
</div>
