<?php
$perpage = $stieinfo['paging_size']; //limit in each page
if (isset($_POST['itf_datasid'], $_POST['itfactions'])) {
    $acts = $_POST['itfactions'];
    $ids = implode(',', $_POST['itf_datasid']);
    if ($acts == 'delete')
        $obj->admin_subscriber_delete($ids);
    flash("Member has been succesfully deleted");
    redirectUrl("itfmain.php?itfpage=" . $currentpagenames . '&actions=subscriber');
}
$InfoData1 = $obj->ShowAllSubscribers($_REQUEST['source']);
$urlpath = CreateLinkAdmin(array($currentpagenames)) . "&actions=subscriber&";
$pagingobj = new ITFPagination($urlpath, $perpage);
$InfoData = $pagingobj->setPaginateData($InfoData1);

$sourceName=$obj->ShowAllSubscribersActiveSource();
//$objstudent = new Student();
//$studentData = $objstudent->ShowAllActiveStudent();
//$objTournament = new Tournament();
//$TournamentStudentData = $objTournament->ShowAllActiveTournamentStudent();
?>

<!-- Box -->
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo "Subscribe Members"; ?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons">
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'add_subscriber')); ?>" class="button add"><span>Add New Subscriber</span></a>
        <a onclick="return itfsubmitfrm('delete', 'itffrmlists');" class="button cancel"><span>Delete</span></a>
    </div>
    <form id="itffrmsearch" name="itffrmsearch" method="post" action="">

        <input type="hidden" name="itfpage" value="<?php echo $currentpagenames; ?>" />
        <div class="element">
            <label>Select Source For Search</label>
            <select name="source" id="source">
                <option value="">---All Source--- </option>
                
                <?php
                foreach ($sourceName as $item) {
                    $idtest = '';
                    $source = $_REQUEST['source'];
                    ?>
                    <option value="<?php echo $item['source']; ?>"   <?php if ($source == $item['source']) { ?>selected="selected"<?php } ?>><?php echo $item['source']; ?> </option>
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
                    <th scope="col" style="width:5%">&nbsp;<input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>
                    <th scope="col" style="width:15%">First Name</th>
                    <th scope="col" style="width:15%">Last Name</th>
                    <th scope="col" style="width:15%">Email</th>
                    <th scope="col" style="width: 50px;">Source</th>
                    <th scope="col" style="width:15%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($InfoData) > 0 || count($studentData) > 0) {
                    for ($i = 0; $i < count($InfoData); $i++) {
                        ?>
                        <tr>
                            <td class="align-center"><input name="itf_datasid[]" type="checkbox" value="<?php echo $InfoData[$i]['id']; ?>" class="itflistdatas"/></td>

                            <td class="align-center">
                                <?php echo $InfoData[$i]['first_name']; ?>
                            </td>
                            <td class="align-center">
                                <?php echo $InfoData[$i]['last_name']; ?>
                            </td>
                            <td class="align-center">
                                <?php echo $InfoData[$i]['email']; ?>
                            </td>
                            <td class="align-center">
                                <?php echo $InfoData[$i]['source']; ?>
                            </td>
                            <td class="align-center">
                                <a href="#itf" class="activations" rel="<?php echo $InfoData[$i]['id']; ?>" rev="subscriber"><img src="imgs/<?php echo $InfoData[$i]['status']; ?>.png" /></a>
                            </td>
                            <!--<td class="align-center"><a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'edit', 'id' => $InfoData[$i]['id'])); ?>" title="Edit" alt="Edit"><img src="img/i_edit.png" border="0" /></a>	  </td>-->
                        </tr>
                        <?php
                    }
                } else {
                    echo '   <tr>
                        <td colspan="10" class="align-center">No Record Available !</td>
                    </tr>';
                }
                ?>


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
