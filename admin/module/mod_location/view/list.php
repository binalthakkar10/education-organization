<?php
if (isset($_POST['itf_datasid'], $_POST['itfactions'])) {
    $userids = isset($_POST['itf_datasid']) ? $_POST['itf_datasid'] : "0";
    $acts = $_POST['itfactions'];
    $ids = implode(',', $_POST['itf_datasid']);
    if ($acts == 'delete') {
        $obj->State_deleteAdmin($ids);
        $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
        flash($pagetitle . " is successfully Deleted");
    }
    redirectUrl($urlname);
}
$perpage = $stieinfo['paging_size']; //limit in each page
$urlpath = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids)) . "&";
$InfoData1 = $obj->showAllLocation($_REQUEST['city']);

$cityName=$obj->getActiveCity();
//echo '<pre>';print_r($cityName);
$pagingobj = new ITFPagination($urlpath, $perpage);
$InfoData = $pagingobj->setPaginateData($InfoData1);
?>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo $pagetitle; ?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons">
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'add', 'parentid' => $parentids)); ?>" class="button add"><span>Add New <?php echo $pagetitle; ?></span></a>
        <a onclick="return itfsubmitfrm('delete', 'itffrmlists');" class="button cancel"><span>Delete</span></a>
        <button onclick="history.back()" type="button">Back</button>
    </div>
    <form id="itffrmsearch" name="itffrmsearch" method="post" action="">

        <input type="hidden" name="itfpage" value="<?php echo $currentpagenames; ?>" />
        <div class="element">
            <label>Select City For Search</label>
            <select name="city" id="city" >
                <option value="">---Select City--- </option>
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
                <!--<th scope="col">Code</th>
                <th scope="col">Code</th>-->
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Notes</th>
               <!-- <th scope="col">Country</th>
                <th scope="col">Zip Code</th>
                <th scope="col">Status</th>-->
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
                       <!-- <td class="align-left"><?php echo $InfoData[$i]['course']; ?></td> 
                        <td class="align-left"><?php echo $InfoData[$i]['code']; ?></td>-->
                        <td class="align-center"><?php echo $InfoData[$i]['name']; ?></td>
                        <td class="align-center"><?php echo $InfoData[$i]['type']; ?></td>
                        <td class="align-center"><?php echo $InfoData[$i]['note']; ?></td>
                       <!-- <td class="align-left"><?php echo $InfoData[$i]['country']; ?></td>
                        <td class="align-left"><?php echo $InfoData[$i]['zip']; ?></td>
                        <!--<td class="align-center">
                           <a href="#itf" class="activations" rel="<?php echo $InfoData[$i]['id']; ?>" rev="frmcategory"><img src="imgs/<?php echo $InfoData[$i]['status']; ?>.png" /></a>
                       </td>-->
                        <td class="align-center">
                            <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'edit', "parentid" => $parentids, 'id' => $InfoData[$i]['id'])); ?>" title="Edit <?php echo $pagetitle; ?> " alt="Edit <?php echo $pagetitle; ?>"><img src="img/i_edit.png" border="0" /></a> &nbsp;

                            <?php /* if($parentids==0){ ?>
                              <a href="<?php echo CreateLinkAdmin(array($currentpagenames,"parentid"=>$InfoData[$i]['id'])); ?>" title="Add City " alt="Add City "><img src="img/add_city.png" border="0" class="city" /></a>
                              <?php } */ ?>
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
