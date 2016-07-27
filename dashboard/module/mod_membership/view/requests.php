<?php
$perpage = $stieinfo['paging_size'] ;//limit in each page

if(isset($_POST['itf_datasid'],$_POST['itfactions']))
{
	$acts=$_POST['itfactions'];
	$ids=implode(',',$_POST['itf_datasid']);

	if($acts=='delete')
		$userobj->removeMoneyRequest($ids);
		flash("Request is succesfully deleted");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
}

$InfoData1 = $userobj->ShowAllRequests();

$urlpath=CreateLinkAdmin(array($currentpagenames))."&";
$pagingobj=new ITFPagination($urlpath);
$InfoData=$pagingobj->setPaginateData($InfoData1);
?>

<!-- Box -->
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo $pagetitle;?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons">
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames)); ?>" class="button"><span>Back</span></a>
        <a onclick="return itfsubmitfrm('delete','itffrmlists');" class="button cancel"><span>Delete</span></a>
    </div>
    <div class="clear"></div>

    <form id="itffrmlists" name="itffrmlists" method="post" action="">
        <input type="hidden" name="itfactions" id="itfactions" value="" />
        <input type="hidden" name="itf_status" id="itf_status" value="" />
        <table>
            <thead>

            <tr>
                <th scope="col">&nbsp;<input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>
                <th scope="col">Name</th>
                <th scope="col">Total MOney</th>
                <th scope="col">Request</th>
                <th scope="col">Balance</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
            </tr>
            </thead>

            <tbody>
            <?php
            if(count($InfoData) > 0){
                for($i=0;$i<count($InfoData);$i++)
                {
                    ?>
                    <tr>
                        <td class="align-center"><input name="itf_datasid[]" type="checkbox" value="<?php echo $InfoData[$i]['id']; ?>" class="itflistdatas"/></td>
                        <td class="align-left"><?php echo $InfoData[$i]['name']; ?></td>
                        <td class="align-center"><?php echo Currency($InfoData[$i]['total_amount']); ?></td>
                        <td class="align-center"><?php echo Currency($InfoData[$i]['withdraw_amount']); ?></td>
                        <td class="align-center"><?php echo Currency($InfoData[$i]['balance']); ?></td>
                        <td class="align-left"><?php echo $InfoData[$i]['date_added']; ?></td>
                        <td class="align-center">
                            <a href="#itf" class="activations" rel="<?php echo $InfoData[$i]['id']; ?>" rev="user"><img src="imgs/<?php echo $InfoData[$i]['status']; ?>.png" /></a>
                        </td>
                    </tr>
                <?php
                } } else {
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