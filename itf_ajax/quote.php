<?php
$msgs="Login";
require('../itfconfig.php');
$obj = new Quote();
$service = new ServiceCategory();

if(isset($_REQUEST['id']) && !empty($_REQUEST['id']) ){
    $quote_info = $obj->checkQuote($_REQUEST['id']);
    //echo"<pre>";print_r($quote_info);die;
    $services = $service->CheckCategory($quote_info['service_cat']);
   
    $results = $obj->getQuoteDetails($_REQUEST['id']);
}



$bids = $obj->getBidsByQuote($quote_info['id']);

?>
<div class="enq_cont_lft" xmlns="http://www.w3.org/1999/html">
    <div class="enq_mat">

        <h3>Enquiry Detail</h3>
        <p style="font-weight: bold; color: #000; margin-top: 10px;"><?php echo $quote_info['quote_name']; ?></p>
        <?php if(count($results) >0 ){ 
            //echo "<pre>";print_r($results);die;?>
        <?php foreach($results as $result){ 
            
  
        
            ?>
        
       <?php if($result['name']!=NULL){?>
       
        
        <div class="enq_mat_cont">

           <div class="pro_category">
                <p class="name">
                    
                   
                    <label>Product Name :</label> <span > <a href="<?Php echo CreateLink(array("product",'itemid'=>'detail','id'=>$result['id']));?>"><?php echo $result['name']; ?>    </a></span>
                
                </p>
                <?php if($result['catname']) { ?>
                <p><label>Product Category  : </label> <span><?php echo $result['catname']; ?></span></p>
                <?php } ?>

                <?php if($services['catname']) { ?>
                <p><label>Service Category : </label> <span><?php echo $services['catname']; ?></span></p>
                <?php } ?>
                <p><label>Quantity : </label><span><?php echo $result['quantity'] > 0 ?$result['quantity']:'NA'; ?></span></p>
                <?php if($result['logn_desc']) { ?>
                <p><label>Description :</label><span><?php echo $result['logn_desc']; ?></span></p>
                <?php } ?>
                <?php if($result['special_req']) { ?>
                    <p><label>Special Requirement :</label><span><?php echo $result['special_req']; ?></span></p>
                <?php } ?>
                <?php if($quote_info['attachment']) { ?>
                    <p><label>Attachment :</label><span><a href="<?php echo PUBLICPATH."products/".$quote_info['attachment']; ?>" target="_blank" ><?php echo $quote_info['attachment']; ?></a></span></p>
                <?php } ?>
            </div>
        <?php } else {?>
             <div class="enq_mat_cont">

           <div class="pro_category">
               
                <?php if($result['service_name']) { ?>
                <p><label>Service Category  : </label> <span><?php echo $result['service_name']; ?></span></p>
                <?php } ?>

                <?php if($services['catname']) { ?>
                <p><label>Service Category : </label> <span><?php echo $services['catname']; ?></span></p>
                <?php } ?>
               
                <?php if($result['logn_desc']) { ?>
                <p><label>Description :</label><span><?php echo $result['logn_desc']; ?></span></p>
                <?php } ?>
                <?php if($result['special_req']) { ?>
                    <p><label>Special Requirement :</label><span><?php echo $result['special_req']; ?></span></p>
                <?php } ?>
                <?php if($quote_info['attachment']) { ?>
                    <p><label>Attachment :</label><span><a href="<?php echo PUBLICPATH."products/".$quote_info['attachment']; ?>" target="_blank" ><?php echo $quote_info['attachment']; ?></a></span></p>
                <?php } ?>
            </div>
        <?php }?>
            <div class="pro_category_pic">
                <p><span>
                        <?php if(!empty($result['main_image']) and file_exists(PUBLICFILE."products/".$result['main_image'])) { ?>
                            <img src="<?php echo PUBLICPATH."products/".$result['main_image']; ?>" width="120px" height="122px" alt=""></a>
                        <?php } else { ?>
                            <img src="<?php echo PUBLICPATH."products/noImageProduct.jpg"; ?>" width="120px" height="122px" alt=""></a>
                        <?php } ?>
                    </span></p>
            </div>
            <div class="clear"></div>
        </div>
        <?php } } ?>
        <?php if($quote_info['quote_desc']) { ?>
        <div class="pro_category_cont">
            <p><label>Quote Description: </label><br><b><?php echo $quote_info['quote_desc']; ?></b></p>
        </div>
        <?php } ?>

        <?php if($_SESSION['FRONTUSER']['usertype'] == 2) { ?>

           <?php if(count($bids) > 0) { ?>
        <form id="request" name="bid_form" method="post" action="">
         <input name="quote_id" type="hidden" value="<?php echo $quote_info['id']; ?>">
         <div class="supply_quote">
            <ul>
                <li>
                    <div class="supply_lft">
                        <p>Supplier ID</p>
                    </div>
                    <div class="supply_mid">
                        <p>Supplier Quote</p>
                    </div>
                    
                     <div class="supply_mid1">
                        <p>Pdf</p>
                    </div>
                    <div class="supply_rgt">
                        <p>Supplier Message</p>
                    </div>
                    <div class="clear"></div>
                </li>
                <?php foreach($bids as $bid) {
                    
               
                    ?>
                <li>
                    <div class="supply_lft">
                        <p><span><?php echo $bid['supplier_id']; ?></span></p>
                    </div>
                    <div class="supply_mid">
                        <p><?php echo Currency($bid['bid_amount']); ?></p>
                    </div>
                    <div class="supply_mid1">
                         
                        <p><a href="<?php echo PUBLICPATH."pdf/".$bid['attachment']; ?>" target="_blank" ><?php echo $bid['attachment']; ?></a></p>
                    </div>
                    
                    <div class="supply_rgt">
                        <p><?php echo $bid['bid_desc']; ?><span><input name="bid_check[]" type="checkbox" value="<?php echo $bid['id']; ?>"></span></p>
                    </div>
                    <div class="clear"></div>
                </li>
                <?php } ?>

            </ul>
        </div>
            <input type="submit" name="submit" value="Accept">
        </form>
        <?php } else { ?>
        <div class="supply_quote"><P STYLE="font-size: 12px; margin-left: 10px;">No Bid for this quote yet. </P></div>
        <?php } ?>
        <?php }
        elseif($_SESSION['FRONTUSER']['usertype'] == 3 && $obj->isBidded($quote_info['id']) === false) 
            { ?>
            <div class="supplier_quote" id="request">
                <form name="supplier_quote" id="supplier_quote" enctype="multipart/form-data" method="post" action="" >
                    <input type="hidden" value="<?php echo $quote_info['id']; ?>" name="quote_id">
                    <div style="float: left; margin-top: 20px;">
                    <label>Bid Price:<span class="required">*</span> </label>
                    <input type="text" name="bid_amount">
                    </div>
                       <div style="float: left; margin-top: 20px;">
                    <label>Upload file</label>
                    <input type="file" name="attachment" value="upload">
                </div> 
                    <div style="float: left; margin-top: 20px;">
                    <label>Bid Details:<span class="required">*</span></label>
                    <textarea name="bid_desc"></textarea>
                    </div>
                    <input id="submit" type="submit" name="submit" value="Submit">
                </form>
            </div>
        <?php  } ?>

    </div>
</div>
<div class="enq_cont_rgt">
    <div class="board">
        <h3>Public clarification board</h3>
        <form id="clearfy" name="clarification_board" method="post">
            <input type="hidden" value="<?php echo $quote_info['id']; ?>" id="quote_id" name="quote_id">
            <textarea name="chat_text" id="chat_text"></textarea>
            <input type="button" value="Submit" onclick="showchat()" >
            <?php if($obj->isBidded($quote_info['id']) === false){ ?>
            <input type="button" class="button" id="back" value="Back">
            <?php } else { ?>
             <input type="button" class="button" id="bid_back" value="Back">
            <?php } ?>
        </form>
    </div>
    <div class="board_cont" id="board_cont"> </div>
</div>

<div class="clear"></div>

<script type="text/javascript">

    function showchat(){
        if($('#chat_text').val() == ''){
            alert('Please enter text.');
            $('#chat_text').focus();
        } else {
            $.ajax({
                url: "<?php echo SITEURL; ?>/itf_ajax/quote_chat.php",
                type :"POST",
                data: $('#clearfy').serialize(),
                success:function(msg){
                    $("#board_cont").html(msg);
                    $("#chat_text").val('');
                }
            });
        }

    }

    $(function(){
        var id = $("#quote_id").val();
        $.ajax({
            url: "<?php echo SITEURL; ?>/itf_ajax/quote_chat.php",
            type :"POST",
            data: 'quote_id='+id,
            success:function(msg){
                $("#board_cont").html(msg);
                $("#chat_text").val('');
            }
        });

        $('#back').click(function() {
            $("#quote_detail").hide();
            $("#quotelist").show();
        });

        $('#bid_back').click(function() {
            $("#bid_quote_detail").hide();
            $("#my_bid").show();
        });

        $('#submit').click(function() {
            $("#bid_quote_detail").hide();
            $("#my_bid").show();
        });

        $('#supplier_quote').validate({
            rules: {
                bid_amount :{required:true, number:true},
                bid_desc: {required:true }

            },
            messages: {
                bid_amount :{number:'Please enter valid price'}
            }
        });

    });
</script>