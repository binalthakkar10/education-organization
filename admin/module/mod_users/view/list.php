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
    $InfoData1 = $obj->ShowAllUsers(false, $_REQUEST);
} else {
    $InfoData1 = $obj->ShowAllUsers(true, $_REQUEST);
}
$pagingobj = new ITFPagination($urlpath, $perpage);
$InfoData = $pagingobj->setPaginateData($InfoData1);
$stateName=$obj->getActiveState();



?>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo $pagetitle; ?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons" style="width:95%">
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'add', 'parentid' => $parentids)); ?>" class="button add"><span>Add New <?php echo $pagetitle; ?></span></a>
        <!-- <a onclick="return itfsubmitfrm('delete', 'itffrmlists');" class="button cancel"><span>Delete</span></a>  -->
        <button onclick="history.back()" type="button">Back</button>
    </div>
    <form id="itffrmsearch" name="itffrmsearch" method="post" action="">

        <input type="hidden" name="itfpage" value="<?php echo $currentpagenames; ?>" />
      <?php /*  <div class="element">
            <label>Select State For Search</label>
            <select name="state" id="state" >
                <option value="">---Select state--- </option>
                <?php
                foreach ($stateName as $item) {
                    $idtest = '';
                    $locationId = $_REQUEST['state'];
                    ?>
                    <option value="<?php echo $item['contact_state']; ?>"   <?php if ($locationId == $item['contact_state']) { ?>selected="selected"<?php } ?>><?php echo $item['contact_state']; ?> </option>
                <?php } ?>
            </select>     
        </div> 
          
        <input name="searchuser" type="submit" id="searchuser"  value="search" />*/ ?>
    </form> 
    <div class="clear"></div>
    <form id="itffrmlists" name="itffrmlists" method="post" action="">
        <input type="hidden" name="itfactions" id="itfactions" value="" />
        <input type="hidden" name="itf_status" id="itf_status" value="" />

        <table>

            <thead>
                <tr>
                    <th scope="col">&nbsp;<input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>
                    <th scope="col">Organization Name</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                     <th scope="col">User Type</th>
                    <th scope="col">Username</th>
                    <th scope="col">email</th>
                    <th scope="col">Status</th>
                    <th scope="col" style="width: 65px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($InfoData) > 0) {
                    for ($i = 0; $i < count($InfoData); $i++) {
                        ?>
                        <tr>
                            <td class="align-center"><input name="itf_datasid[]" type="checkbox" value="<?php echo $InfoData[$i]['org_id']; ?>" class="itflistdatas"/></td>
                            <td class="align-left"><?php echo $InfoData[$i]['org_name']; ?></td> 
                             <td class="align-left"><?php echo $InfoData[$i]['first_name']; ?></td>
                            <td class="align-left"><?php echo $InfoData[$i]['last_name']; ?></td> 
                            <td class="align-left"><?php if($InfoData[$i]['user_type']=='2'){ echo 'Organization User';}else if($InfoData[$i]['user_type']=='1'){echo 'Admin User';} ?></td> 
                            <td class="align-left"><?php echo $InfoData[$i]['username']; ?></td>
                            <td class="align-left"><?php echo $InfoData[$i]['email']; ?></td> 
                            <td><a href="#itf" class="activations" rel="<?php echo $InfoData[$i]['id']; ?>" rev="users"><img src="imgs/<?php echo $InfoData[$i]['status']; ?>.png" /></a>
                            </td>
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