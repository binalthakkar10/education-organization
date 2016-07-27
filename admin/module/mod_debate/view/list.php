<?php
if (isset($_POST['itf_datasid'], $_POST['itfactions'])) {
    $userids = isset($_POST['itf_datasid']) ? $_POST['itf_datasid'] : "0";
    //print_r($userids);
    $acts = $_POST['itfactions'];
    $ids = implode(',', $_POST['itf_datasid']);
    if ($acts == 'delete') {
        $debateObj->Debate_deleteAdmin($ids);
        $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
        flash($pagetitle . " is successfully Deleted");
    }
    redirectUrl($urlname);
}

$perpage = $stieinfo['paging_size']; //limit in each page
$urlpath = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids)) . "&";
$InfoData1 = $debateObj->showAllDebate($parentids);
//echo '<pre>';print_r($InfoData1);
$pagingobj = new ITFPagination($urlpath, $perpage);
$InfoData = $pagingobj->setPaginateData($InfoData1);
?>

<!-- Box -->
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ucwords($pagetitle); ?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons">
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'add', 'parentid' => $parentids)); ?>" class="button add"><span>Add New <?php echo ucfirst($pagetitle); ?></span></a>
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'import')); ?>" class="button import"><span>Import <?php echo $pagetitle; ?></span></a>
        <a onclick="return itfsubmitfrm('delete', 'itffrmlists');" class="button cancel"><span>Delete</span></a>
        <button onclick="history.back()" type="button">Back</button>
    </div>
    <div class="clear"></div>
    <form id="itffrmlists" name="itffrmlists" method="post" action="">
        <input type="hidden" name="itfactions" id="itfactions" value="" />
        <input type="hidden" name="itf_status" id="itf_status" value="" />
        <table>
            <thead>
                <tr>
                    <th scope="col">&nbsp;<input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>
                    <th scope="col">First Name</th> 
                    <th scope="col">Last Name</th>
                    <th scope="col">Debt Score</th>
                    <th scope="col">Last Tournament Attended</th>
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
                            <td class="align-center"><input name="itf_datasid[]" type="checkbox" value="<?php echo $InfoData[$i]['id']; ?>" class="itflistdatas"/></td>
                            <td class="align-center"><?php echo $InfoData[$i]['first_name']; ?></td>
                            <td class="align-center"><?php echo $InfoData[$i]['last_name']; ?></td>

        <!--<td class="align-left"><?php echo $InfoData[$i]['ranking']; ?></td>-->
                            <td class="align-center"><?php echo $InfoData[$i]['debt_score']; ?></td>
                            <td class="align-center"><?php echo date('m/d/Y',  strtotime($InfoData[$i]['attend_date'])); ?></td>
                            <td class="align-center">
                                <a href="#itf" class="activations" rel="<?php echo $InfoData[$i]['id']; ?>" rev="frmdebate"><img src="imgs/<?php echo $InfoData[$i]['status']; ?>.png" /></a>
                            </td>
                            <td class="align-center">
                                <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'edit', "parentid" => $parentids, 'id' => $InfoData[$i]['id'])); ?>" title="Edit <?php echo $pagetitle; ?> " alt="Edit <?php echo $pagetitle; ?>"><img src="img/i_edit.png" border="0" /></a> &nbsp;

                                <?php //  if($parentids==0){  ?>
                <!--                               <a href="<?php echo CreateLinkAdmin(array($currentpagenames, "parentid" => $InfoData[$i]['id'])); ?>" title="Add Course " alt="Add Course"><img src="img/add_city.png" border="0" class="city" /></a>-->
                                <?php // }   ?>
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
