<?php
 $uid=$_REQUEST['uid'];
$msgs="Login";
require('../itfconfig.php');
$obj = new Quote();
if(isset($_REQUEST['id']) && !empty($_REQUEST['id']) ){
    
            $quote_info = $obj->checkQuote($_REQUEST['id']);
    $results = $obj->getQuoteDetails($_REQUEST['id']);
}
//echo "<pre>";print_r( $quote_info);die;
//$bids = $obj->getBidsByQuote($quote_info['id']);

$bids = $obj->getDetailFromPayment($quote_info['id'], $uid);
//$bids = $obj->getDetailFromPayment($quote_info['id'],$uid);
//echo $_SESSION['FRONTUSER']['id'];die;
//echo "<pre>";print_r($bids);die;
//foreach($bids as $bid){
//    $supplier_id[] = $bid['supplier_id'];
//    $bid_price[] = Currency($bid['bid_amount']);
//}
?>

<form id="work_area" method="post" action="">
<div class="work_area">
    <h3>Work Area</h3>
    <div class="box">

        <p><span class="left">Enquiry Detail:</span> <span class="right"><?php echo $quote_info['quote_name']; ?></span></p>

        <?php if($_SESSION['FRONTUSER']['usertype'] == 3) { ?>
            <p><span class="left">Quote Created By:</span> <span id="receid" class="right"><?php echo $quote_info['customer']; ?></span></p>
            <p>
                <span class="left">Job Status : </span>
                <span class="right"><?php echo $obj->getQuoteStatus($quote_info['quote_status']); ?></span>
            </p>
        <?php } else { ?>
            <p><span class="left">Selected Seller ID: </span> <span id="receid" class="right"><?php  echo $bids['registration_id'];  //echo implode(",",$supplier_id); ?></span></p>
            <p><span class="left">Seller Price : </span> <span class="right"><?php echo $bids['bid_amount'];  //echo implode(",",$bid_price); ?></span></p>
            <p><span class="left">Job Status : </span>
            <input type="radio" value="0" name="quote_status" <?php if($quote_info['quote_status'] == 0){ echo "checked=true"; } ?>><label>In Progress</label>
            <input type="radio" value="1"  name="quote_status" <?php if($quote_info['quote_status'] == 1){ echo "checked=true"; } ?>><label>Pending</label>
            <input type="radio" value="2"  name="quote_status" <?php if($quote_info['quote_status'] == 2){ echo "checked=true"; } ?>><label>Complete</label>
        </p>
        <?php } ?>
    </div>
</div>
<br>
<hr>
<div class="message_box">
    <input type="hidden" value="<?php echo $quote_info['id']; ?>" id="quote_id" name="quote_id">
     <?php if($_SESSION['FRONTUSER']['usertype'] == 3) { ?>
    <input type="hidden" value="<?php echo $quote_info['customer'];  ?>" id="reciever_id" name="reciever_id">
    <?php } else { ?>
     <input type="hidden" value="<?php  echo $bids['registration_id'];  ?>" id="reciever_id" name="reciever_id">
          <?php } ?>
    <label>Message:</label>
    <textarea name="chat_text" id="chat_text"></textarea>
    <input type="button" value="Submit" onclick="showchat()" >
    <input type="button" class="button" id="active_back" value="Back">

</div>
<div class="board_cont message_content" id="board_cont"> </div>
</form>
<div class="clear"></div>

<script type="text/javascript">

    function showchat(){
     
    
       if($('#chat_text').val() == ''){
            alert('Please enter text.')
            $('#chat_text').focus();
        } else {
            $.ajax({
                url: "<?php echo SITEURL; ?>/itf_ajax/active_quote_chat.php",
                type :"POST",
                data: $('#work_area').serialize(),
                success:function(msg){
                    $("#board_cont").html(msg);
                    $("#chat_text").val('');
                }
            });
        }

    }


    $(function(){
        var id = $("#quote_id").val();
        
        var reciever_id=   $('#receid').html();
        
      
        $.ajax({
            url: "<?php echo SITEURL; ?>/itf_ajax/active_quote_chat.php",
            type :"POST",
            data: 'quote_id='+id+'&reciever_id='+reciever_id,
           
            success:function(msg){
                $("#board_cont").html(msg);
                $("#chat_text").val('');
            }
        });


        $('#active_back').click(function() {
            $("#active_quotes").hide();
            $("#quote_active").show();
        });

    });
</script>