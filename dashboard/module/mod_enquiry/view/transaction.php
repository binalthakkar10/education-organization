<?php
$perpage = $stieinfo['paging_size'] ;//limit in each page

if(isset($_POST['itf_datasid'],$_POST['itfactions']))
{
	$acts=$_POST['itfactions'];
	$ids=implode(',',$_POST['itf_datasid']);

	if($acts=='delete')
        $objReport->deleteOrder($ids);
		flash("User is succesfully deleted");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
}

$InfoData1 = $objReport->showAllTransactions();

$urlpath = CreateLinkAdmin(array($currentpagenames))."&";
$pagingobj = new ITFPagination($urlpath);
$InfoData = $pagingobj->setPaginateData($InfoData1);

//echo "<pre>"; print_r($InfoData); die;
?>

<!-- Box -->
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo 'Order '.$pagetitle;?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons">
        <a href="<?php echo CreateLinkAdmin(array('report','actions'=>'export','rep'=>'transaction')) ?>" class="button excel"><span>Export to excel</span></a>
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
                <th scope="col">Order Name</th>
                <th scope="col">Amount</th>
                <th scope="col">Payment Date</th>
                <th scope="col">Payment User</th>
                <th scope="col">Transaction Id</th>
                <th scope="col">IPN Track Id</th>
                <th scope="col">Payment Status</th>
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
                        <td class="align-left"><?php echo $InfoData[$i]['quote_name']; ?></td>
                        <td class="align-center"><?php echo $InfoData[$i]['payment_amount']; ?></td>
                        <td class="align-center"><?php echo $InfoData[$i]['date_added']; ?></td>
                        <td class="align-center"><?php echo $InfoData[$i]['order_user']; ?></td>
                        <td class="align-center"><?php echo $InfoData[$i]['txn_id']; ?></td>
                        <td class="align-center"><?php echo $InfoData[$i]['ipn_track_id']; ?></td>
                        <td class="align-center"><?php echo  $InfoData[$i]['payment_status']; ?></td>
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