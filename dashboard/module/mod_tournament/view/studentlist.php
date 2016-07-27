<?php
$perpage = $stieinfo['paging_size']; //limit in each page
$id = $_GET['id'];
if (isset($_POST['itf_datasid'], $_POST['itfactions'])) {
    $acts = $_POST['itfactions'];
    $ids = implode(',', $_POST['itf_datasid']);
    if ($acts == 'delete')
        $userobj->tournament_delete($ids);
    flash("Tournament is succesfully deleted");
    redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
}
$InfoData1 = $userobj->ShowAllTournamentStudent($id);
$urlpath = CreateLinkAdmin(array($currentpagenames)) . "&";
$pagingobj = new ITFPagination($urlpath);
$InfoData = $pagingobj->setPaginateData($InfoData1);
?>
<!-- Box -->
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title">Tournament Student List</div>
    <!-- Page Heading -->
    <!-- <div class="topcontroller">
         <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'add')); ?>" class="button add"><span>Add New <?php echo $pagetitle; ?></span></a>
         <a onclick="return itfsubmitfrm('delete', 'itffrmlists');" class="button cancel"><span>Delete</span></a>
     </div>-->
    <div class="clear"></div>
   <a href="<?php echo CreateLinkAdmin(array('report', 'actions' => 'export', 'tournamentId' => $id, 'requestFrom' => 'Tournament')); ?>">Download Student Data</a>
       
  
    <form id="itffrmlists" name="itffrmlists" method="post" action="">
        <input type="hidden" name="itfactions" id="itfactions" value="" />
        <input type="hidden" name="itf_status" id="itf_status" value="" />
        <table>
            <thead>
                <tr>
                   <!-- <th scope="col">&nbsp;<input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>-->
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Status</th>

                </tr>
            </thead>

            <tbody>
                <?php
                if (count($InfoData) > 0) {
                    for ($i = 0; $i < count($InfoData); $i++) {
                        ?>
                        <tr>
                           <!-- <td class="align-center"><input name="itf_datasid[]" type="checkbox" value="<?php echo $InfoData[$i]['id']; ?>" class="itflistdatas"/></td>-->
                            <td align="center">
                                <a href="itfmain.php?itfpage=tournament&actions=student_detail&id=<?php echo $InfoData[$i]['id'] ?>"> <?php echo $InfoData[$i]['first_name']; ?></a>
                            </td>
                            <td align="center">
                                <?php echo $InfoData[$i]['last_name']; ?>
                            </td>
                            <td align="center">
                                <?php echo $InfoData[$i]['email']; ?>
                            </td>
                            <td align="center">
                                <?php echo $InfoData[$i]['phone']; ?>
                            </td>
                            <td align="center">
                                <?php echo $InfoData[$i]['status'] == '1' ? 'Active' : 'Inactive'; ?>
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
