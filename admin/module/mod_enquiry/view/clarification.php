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

$InfoData1 = $objReport->showAllEnquiries();

$urlpath = CreateLinkAdmin(array($currentpagenames))."&";
$pagingobj = new ITFPagination($urlpath);
$InfoData = $pagingobj->setPaginateData($InfoData1);

$quote = new Quote();

//echo "<pre>"; print_r($InfoData); die;
?>

<!-- Box -->
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo 'Clarification '.$pagetitle;?></div>
    <!-- Page Heading -->
    <div class="entry top_buttons">
    </div>
    <div class="clear"></div>

    <form id="itffrmlists" name="itffrmlists" method="post" action="">
        <input type="hidden" name="itfactions" id="itfactions" value="" />
        <input type="hidden" name="itf_status" id="itf_status" value="" />
        <table>
            <thead>

            <tr>
                <th scope="col">Enquiry Name</th>
                <th scope="col">Description</th>
                <th scope="col">Delivery Location</th>
                <th scope="col">Customer Name</th>
                <th scope="col">Quote Status</th>
                <th scope="col">Quote Date</th>
                <th scope="col">Clarification</th>
            </tr>
            </thead>

            <tbody>
            <?php
            if(count($InfoData) > 0){
                for($i=0;$i<count($InfoData);$i++)
                {
                    ?>
                    <tr>
                        <td class="align-left"><?php echo $InfoData[$i]['quote_name']; ?></td>
                        <td class="align-center"><?php echo WordLimit($InfoData[$i]['quote_desc'],8); ?></td>
                        <td class="align-center"><?php echo $InfoData[$i]['location']; ?></td>
                        <td class="align-center"><?php echo $InfoData[$i]['name']; ?></td>
                        <td class="align-center"><?php echo $quote->getQuoteStatus($InfoData[$i]['quote_status']); ?></td>
                        <td class="align-center"><?php echo  $InfoData[$i]['date_added']; ?></td>
                        <td class="align-center"><a href="#itf" onclick="showBox(<?php echo $InfoData[$i]['id']; ?>);" title="Edit" alt="Edit"><img src="img/view.png" border="0" /></a></td>
                    </tr>
                    <tr id="content_detail_<?php echo $InfoData[$i]['id']; ?>" style="display: none;">
                        <td colspan="8">
                            <?php $details = $objReport->showQuoteChat($InfoData[$i]['id']);     ?>
                            <?php if(count($details) > 0) { ?>
                                <table class="details">
                                    <thead>
                                    <tr>
                                        <th scope="col">Chat Text</th>
                                        <th scope="col">Chat By</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($details as $detail) { ?>
                                        <tr>
                                            <td class="align-center"><?php echo $detail['chat_text']; ?></td>
                                            <td class="align-center"><?php echo $detail['name'].' ('.$detail['registration_id'].')'; ?></td>
                                            <td class="align-center"><?php echo date('d M Y h:i A',$detail['added_date']); ?></td>
                                        </tr>

                                    <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <p style="text-align: center">No chat on this quote !</p>
                            <?php } ?>
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
<script>
    function showBox(id){

        $('#content_detail_'+id).toggle();
    }
</script>