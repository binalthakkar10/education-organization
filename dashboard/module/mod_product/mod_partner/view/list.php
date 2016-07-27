<?php

if(isset($_REQUEST['status']) && isset($_REQUEST['status_id'])){
	
	$obj->updateStatus($_REQUEST['status'],$_REQUEST['status_id']);
}

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
    $InfoData1 = $obj->ShowAllPartnerData(false, $_REQUEST);
} else {
    $InfoData1 = $obj->ShowAllPartnerData(true, $_REQUEST);
}
$pagingobj = new ITFPagination($urlpath, $perpage);
$InfoData = $pagingobj->setPaginateData($InfoData1);
$cityName=$obj->getActiveCity();



?>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo $pagetitle; ?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons" style="width:95%">
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'add', 'parentid' => $parentids)); ?>" class="button add"><span>Add New <?php echo $pagetitle; ?></span></a>
        <?php /*<a onclick="return itfsubmitfrm('delete', 'itffrmlists');" class="button cancel"><span>Delete</span></a> */?>
        <button onclick="history.back()" type="button">Back</button>
    </div>
    <form id="itffrmsearch" name="itffrmsearch" method="post" action="">

        <input type="hidden" name="itfpage" value="<?php echo $currentpagenames; ?>" />
        <div class="element">
            <label>Select State For Search</label>
            <select name="city" id="city" >
                <option value="">---Select city--- </option>
                <?php
                foreach ($cityName as $item) {
                    $idtest = '';
                    $locationId = $_REQUEST['city'];
                    ?>
                    <option value="<?php echo $item['city']; ?>"   <?php if ($locationId == $item['city']) { ?>selected="selected"<?php } ?>><?php echo $item['city']; ?> </option>
                <?php } ?>
            </select>     
        </div>  
          
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
                    <th scope="col">Organization Name</th>
                    <th scope="col">Partner Name</th>
                    <th scope="col">City</th>
                    <th scope="col">Partner Type</th>
                    <th scope="col">Notes</th>
                    <th scope="col" style="width: 65px;">Action</th>
                    <th scope="col">Status</th>
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
                            <td class="align-left"><?php echo $InfoData[$i]['partner_name']; ?></td> 
                            <td class="align-left"><?php echo $InfoData[$i]['city']; ?></td>
                            <td class="align-left"><?php echo $InfoData[$i]['partner_type']; ?></td>
                            <td class="align-left"><?php echo $InfoData[$i]['notes']; ?></td> 
                            <td class="align-center">
                                <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'edit', "parentid" => $parentids, 'id' => $InfoData[$i]['id'])); ?>" title="Edit <?php echo $pagetitle; ?> " alt="Edit <?php echo $pagetitle; ?>"><img src="img/i_edit.png" border="0" /></a> &nbsp;
                            
                             
                                
                            </td>
                           <td><a href="#itf" class="activations" rel="<?php echo $InfoData[$i]['id']; ?>" rev="partner"><img src="imgs/<?php echo $InfoData[$i]['status']; ?>.png" /></a>
                        </tr>
                  		  
                <?php }
                    }?>

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