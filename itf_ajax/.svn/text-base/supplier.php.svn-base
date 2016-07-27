<?php
error_reporting(0);
Html::AddJavaScript("dashboard/assests/jquery.js","component");
Html::AddJavaScript("dashboard/assests/jquery.autocomplete.js","component");
Html::AddStylesheet("dashboard/assests/jquery-ui-timepicker-addon.css","component");
Html::AddStylesheet("dashboard/assests/jquery.autocomplete.css","component");

if(empty($_SESSION['FRONTUSER']))
{
   redirectUrl(CreateLink(array("signin")));
}

$countries = $objsite->getCountries();

$userinfo = $obj->getUserInfo($_SESSION['FRONTUSER']['id']);
$category_ids = explode(",",$userinfo['product_group_id']);
//echo"<pre>";print_r($userinfo);die;
$serviceObj = new ServiceCategory();
$services = $serviceObj->getCategories();

$categoryObj = new Category();
$categories = $categoryObj->getCategories();
foreach($categories as $category)
{
    $cats[$category['id']] = $category['catname'];
}

$showCat = array();

foreach($category_ids as $ids){
    if (array_key_exists($ids, $cats)) {
        $showCat[$ids] = $cats[$ids];
        unset($cats[$ids]);
    }
}

$categoriesData = implode('","',$cats);

$quote = new Quote();
$enquiries = $quote->getEnquiryByLocation($userinfo['city_id'],$userinfo['service_category']);
$bids = $quote->getBids();

$totalMoney = $quote->totalMoney();

$state = new State();
$areas = $state->getAllStateFront();


$finalizeData = $quote->getOrder();
$activeQuotes = $quote->getActiveQuoteByLocation($userinfo['city_id']);
$closedQuotes = $quote->getClosedQuoteByLocation($userinfo['city_id']);

if(isset($_POST['submit'])){

    if(!empty($_POST['emailid'])){
        if(!empty($_POST['change_password'])){
            $obj->ChangePasswordFront($_POST['change_password']);
        }

        $obj->front_user_update($_POST);
        flashMsg("Success: Your profile is modified.");
        redirectUrl(CreateLink(array("dashboard#tab8")));
    }

    if(isset($_POST['category'])){
        if(!empty($_POST['category'])){
            $obj->addCategory($_POST);
            flashMsg("Success: ".$_POST['category']." have been added in your profile .");
            redirectUrl(CreateLink(array("dashboard#tab3")));
        }else{
            flashMsg("Error: Please enter category ",2);
            redirectUrl(CreateLink(array("dashboard#tab3")));
        }
    }

    if(!empty($_POST['paypal_id'])){
        $rest = $totalMoney - $_REQUEST['withdraw_money'];
        $data = array('user_id'=>$_SESSION['FRONTUSER']['id'],'total_amount'=>$totalMoney, 'withdraw_amount'=>$_POST['withdraw_money'], 'balance'=>$rest );
        $quote->addMoney($data);
        flashMsg("Success: Your request is submitted.");
        redirectUrl(CreateLink(array("dashboard")));
    }

    if(!empty($_POST['bid_amount'])){
        $quote->addBid($_POST);
        redirectUrl(CreateLink(array("dashboard#tab4")));
    }

    if(!empty($_POST['product_name'])){
        $userobj = new User();
        $maildatavalue = $userobj->GetEmail(11);
        $admin_mail = $userobj->Get_User(1);

        if(isset($_FILES['image']['name'])){
            if(!empty($_FILES['image']['name'])){
                $fimgname="plucka_".rand();
                $objimage= new ITFImageResize();
                $objimage->load($_FILES['image']['tmp_name']);
                $objimage->save(PUBLICFILE."products/".$fimgname);
                $imagename = $objimage->createnames;

            }
        }
        
        
        
        $file = PUBLICFILE."/products/".$imagename;
        $filename=$imagename;
         //$file = $path . "/" . $filename;
$to = 	$admin_mail['email'];
 $subject=$maildatavalue['mailsubject'];
 $url=SITEURL;
 $from=$admin_mail['email'];
$bound_text = 	"jimmyP123";
$bound = 	"--".$bound_text."\r\n";
$bound_last = 	"--".$bound_text."--\r\n";

    $headers =  "From: Plucka Helping Hand <".$from."> \r\n";  	 
//$headers = 	"From:".$admin_mail['email']."\r\n";
$headers .= 	"MIME-Version: 1.0\r\n"
  	."Content-Type: multipart/mixed; boundary=\"$bound_text\"";
  	 
$message .= 	"If you can see this MIME than your client doesn't accept MIME types!\r\n"
  	.$bound;
  	 
$message .= 	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
  	."Content-Transfer-Encoding: 7bit\r\n\r\n"
       ." 
           <body>
<table width='642' border='1' align='center' cellpadding='5' cellspacing='0'>
  <tr>
    <td height='83' style='font-size:25px;'>
	<a href='http://trade2rise.com/project/' style='color:#FFFFFF;'><img border='1' src='http://trade2rise.com/project/plucka/template/default/images/logo.png' title='Plucka Helping Hand'></a></td>
  </tr>
  <tr>
  <td>
<p>Hi Admin,</p><br><br>
<p>" .$_SESSION['FRONTUSER']['name']. "want to add product in your site,</p><br>
<p>There are following detail of product.</p>
<p>Product Name : " .$_POST['product_name']."   </p>
<p>Product Details : ".$_POST['detail']." </p>
<p>Image : In attachment </p>

   <br />
              <br />
     <p> <strong>Thanks and Regards<br />
	 &nbsp;&nbsp;Plucka Helping Hand </strong></p></td>
  </tr>
  <tr>
    <td height='52' align='center' style='font-size:12px;'><a href='' style='color:#000000;'>Plucka Helping Hand</a></td>
  </tr>
</table>
</body>
"
  	
  	.$bound;
  	 
$files = 	file_get_contents($file);
  	 
$message .= 	"Content-Type: image/jpg; name=\".$filename.\"\r\n"
  	."Content-Transfer-Encoding: base64\r\n"
  	."Content-disposition: attachment; file=\".$filename.\"\r\n"
  	."\r\n"
  	.chunk_split(base64_encode($files))
  	.$bound_last;

mail($to, $subject, $message, $headers);

        
        
//        $objmail=new ITFMailer();
//        $objmail->SetSubject($maildatavalue['mailsubject']);
//        $objmail->SetBody($maildatavalue['mailbody'],array('name'=>$_SESSION['FRONTUSER']['name'],'productname'=>$_POST['product_name'],'detail'=>$_POST['detail']));
//        $objmail->SetTo($admin_mail['email']);
//        $objmail->FileAttach($files);
//        
//        echo"<pre>";print_r($objmail);die;
//        $objmail->MailSend();

        flashMsg("Success: Your request is successfully sent !.");
        redirectUrl(CreateLink(array("dashboard")));
    }

}

?>
<script>

    // Wait until the DOM has loaded before querying the document
    $(document).ready(function(){


        $('ul.tabs').each(function(){
            // For each set of tabs, we want to keep track of
            // which tab is active and it's associated content
            var $active, $content, $links = $(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
            $active.addClass('active');
            $content = $($active.attr('href'));

            // Hide the remaining content
            $links.not($active).each(function () {
                $($(this).attr('href')).hide();
            });

            // Bind the click event handler
            $(this).on('click', 'a', function(e){
                // Make the old tab inactive.
                $active.removeClass('active');
                $content.hide();

                // Update the variables with the new link and content
                $active = $(this);
                $content = $($(this).attr('href'));

                // Make the tab active.
                $active.addClass('active');
                $content.show();

                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });

        var activelink = location.hash+"1";
        if(activelink!="1")
        $(activelink).click();

    });


</script>
<script type="text/javascript">

    $(document).ready(function() {

        $.validator.addMethod("noSpace", function(value, element) {

            var resinfo = parseInt(value.indexOf(" "));

            if(resinfo == 0 && value !="") { return false; } else return true;

        }, "Space are not allowed as first string !");

        $('#info').validate({
            rules: {
                name:{required:true, maxlength:100, noSpace: true},
                last_name:{required:true, maxlength:100, noSpace: true},
                address:{required:true, maxlength:100, noSpace: true},
                change_password:{minlength:8, maxlength:20, noSpace: true},
                payment_type:"required"

            },
            messages: {
                name:{required:"You must fill in all of the fields !"},
                last_name: {required:"You must fill in all of the fields !"},
                address: {required:"You must fill in all of the fields !" },
                payment_type: "You must fill in all of the fields !",
                change_password:{ required: "You must fill in all of the fields !"}

            }
        });


    });
</script>

<div style="padding-top:25px;">
<ul class="tabs" style="border-bottom:2px #b6b6b6 solid;">
    <li><a class="#" href="#tab1" id="tab11">Dashboard</a></li>
    <li><a class="#" href="#tab8" id="tab81">My Profile</a></li>
    <li><a class="" href="#tab2" id="tab21">My Money</a></li>
    <li><a class="" href="#tab3" id="tab31">My Categories</a></li>
    <li><a class="" href="#tab5" id="tab51">Browse Quotes</a></li>
    <li><a class="" href="#tab4" id="tab41">My Bids</a></li>
    <li><a class="" href="#tab6" id="tab61">Active Quote</a></li>
    <li><a class="" href="#tab7" id="tab71">Closed Quote</a></li>
    <li><a class="#" href="#tab9" id="tab91">Add Product</a></li>
</ul>
<div style="display: block;" id="tab1">
    <div class="cont_info">
        <div class="summary">
            <div class="summary_lft">
                <div class="summary_lft_cont">
                    <p>Supplier Id:<span> <?php echo $userinfo['registration_id'];?></span></p>
                    <p>Member Since:<span> <?php echo date('d M Y',$userinfo['created_date']); ?></span></p><br>
                    <p><b>Summary of bid requested:</b></p>
                    <p>Total Bids:<span><?php echo count($bids); ?></span></p>
                    <p>Total Bids Won:<span><?php echo count($activeQuotes) + count($closedQuotes); ?></span></p>
                </div>
            </div>
            <div class="summary_rgt">
                <div class="map">
                    <?php
                    if(isset($userinfo['address']) && !empty($userinfo['address'])){
                        $myaddress = urlencode($userinfo['address']);
                        //here is the google api url
                        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$myaddress&sensor=false";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $getmap = curl_exec($ch);
                        curl_close($ch);

                        $googlemap = json_decode($getmap);
                        //get the latitute, longitude from the json result by doing a for loop
                        foreach($googlemap->results as $res){
                            $address = $res->geometry;
                            $latlng = $address->location;
                            $formattedaddress = $res->formatted_address;

                            ?>
                            <iframe class="map" width="447" height="257" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $myaddress;?>&amp;ie=UTF8&amp;hq=&amp;hnear=<?php echo urlencode($formattedaddress);?>&amp;t=m&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe>
                        <?php
                            break;
                        }
                    }else {?>
                        <img src="<?php echo IMAGEPATH.'map-not-available_lg.gif';?>" width="447" height="257" >

                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>
<div style="display: block;" id="tab8">
    <div class="cont_info">
        <h3>Contact Information</h3>
        <form id="info" name="profile" method="post" action="<?php echo CreateLink(array('dashboard')); ?> " enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo isset($userinfo['user_id'])?$userinfo['user_id']:''; ?>">
            <div class="sec">
                <label>First Name</label>
                <input name="name" type="text" value="<?php echo isset($userinfo['name'])?$userinfo['name']:''; ?>">
                <div class="clear"></div>
            </div>
            <div class="sec">
                <label>Last Name</label>
                <input name="last_name" type="text" value="<?php echo isset($userinfo['last_name'])?$userinfo['last_name']:''; ?>">
                <div class="clear"></div>
            </div>
            <div class="sec">
                <label>Email ID</label>
                <input name="emailid" type="text" readonly  value="<?php echo isset($userinfo['email'])?$userinfo['email']:''; ?>">
                <div class="clear"></div>
            </div>
            <div class="sec">
                <label>Change Password - to change the current password.</label>
                <input name="change_password" type="password" value="">
                <div class="clear"></div>
            </div>
            <div class="chebox sec">
                <label>Location</label>
                     <select class="sect_category" name="serviceArea[]" id="servicecategory" multiple>
            <?php foreach($areas as $area){  $city_ids = explode(",",$userinfo['city_id']);?>

                         <option value="<?php echo $area['id']; ?>" <?php if(in_array($area['id'],$city_ids)){ echo "selected"; } ?>><?php echo $area['name']; ?></option>
                         
                         
                
            <?php } ?>
                     </select>
                <div class="clear"></div>
            </div>

            <div class="sec">
                <label>Service Category</label>
                <select class="sect_category" name="serviceGroup[]" id="servicecategory" multiple>
                    <?php foreach($services as $cat){  $cat_ids = explode(",",$userinfo['service_category']);?>
                        <?php if(count($cat['subcat']) > 0){ ?>
                            <optgroup label="<?php echo $cat['catname'] ?>" style="padding-top: 10px;">
                                <?php foreach($cat['subcat'] as $subcat){ ?>
                                    <?php if(count($subcat['subcat']) > 0){ ?>
                                        <optgroup label="<?php echo $subcat['catname'] ?>" style="padding-left: 10px;">
                                            <?php foreach($subcat['subcat'] as $subsubcat){ ?>
                                                <option value="<?php echo $subsubcat['id'] ?>" <?php if(in_array($subsubcat['id'],$cat_ids)){ echo "selected"; } ?>><?php echo $subsubcat['catname'] ?></option>
                                            <?php } ?>
                                        </optgroup>

                                    <?php } else { ?>
                                        <option value="<?php echo $subcat['id'] ?>" <?php if(in_array($subcat['id'],$cat_ids)){ echo "selected"; } ?>><?php echo $subcat['catname'] ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </optgroup>

                        <?php } else { ?>
                            <option value="<?php echo $cat['id'] ?>" <?php if(in_array($cat['id'],$cat_ids)){ echo "selected"; } ?> ><?php echo $cat['catname'] ?></option>
                        <?php } ?>
                    <?php } ?>

                </select>
            </div>
            
            <div class=" sec">
                <label>Country</label>
                <select class="sect" name="country_code">
                    <?php foreach($countries as $country){ ?>
                        <option value="<?php echo $country['country_code'];?>" <?php if($userinfo['country_code'] == $country['country_code']){ echo"selected"; } ?>>
                            <?php echo $country['country_name'];?> (<?php echo $country['country_code'];?>)
                        </option>
                    <?php } ?>
                </select>
                <div class="clear"></div>
            </div>

            <div class="sec">
                <label>Address<span class="required">*</span></label>
                <textarea name="address"><?php echo isset($userinfo['address'])?$userinfo['address']:''; ?></textarea>
                <div class="clear"></div>
            </div>
            <div class="sec">
                <label>Postal Code</label>
                <input name="postal_code" type="text" value="<?php echo isset($userinfo['postal_code'])?$userinfo['postal_code']:''; ?>">
                <div class="clear"></div>
            </div>
            
            <div class="sec">
                <label>Edit Image</label>
                <img src="<?php echo PUBLICPATH."/profile/"; ?><?php if($userinfo['profile_image']){ echo $userinfo['profile_image'];} else { echo 'no_image.jpg'; }; ?>" class="edit_mg" height="129px" width="120px">
                <div class="upld">
                    <p><input type="file" name="image" value="Upload"> </p>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>

            <div class="sec">
                <label>&nbsp;</label>
                <input type="submit" name="submit" value="update">
                <div class="clear"></div>
            </div>
        </form>
    </div>
</div>
<div style="display: none;" id="tab2" class="detail">
    <div class="money">
        <form id="mny" method="post" action="">
            <div class="mny_cont">
                <label>Total Amount: <span>$</span></label>
                <input type="text" id="total_money" value="<?php echo $totalMoney; ?>"  class="sml" readonly>
                <div class="clear"></div>
            </div>
            <div class="mny_cont">
                <label>Amount for Withdraw <span>$</span></label>
                <input type="text" name="withdraw_money" id="withdraw_money" value="" class="sml">
                <div class="clear"></div>
            </div>
            <div class="mny_cont">
                <label>Amount Left <span>$</span></label>
                <input type="text" id="rest_amount" value="" class="sml" readonly>
                <div class="clear"></div>
            </div>
            <div class="mny_cont">
                <label>PayPal Id</label>
                <input type="text" name="paypal_id"  value="">
                <div class="clear"></div>
            </div>
            <div class="mny_cont">
                <label>&nbsp;</label>
                <input type="submit" name="submit" value="Submit">
                <div class="clear"></div>
            </div>
        </form>
    </div>
</div>




<div style="display: block;" id="tab3" class="detail">
    <div class="my_categori">
        <h3>My Categories</h3>
        <form id="cte" name="frmcategory" action="" method="post" enctype="multipart/form-data">
            <input type="text" name="category" id="category" value=""  autocomplete="off" >
            <input type="submit" name="submit" value="" >
            <div class="clear"></div>
        </form>
    </div>
    <div class="my_categori_detail">
        <div class="categori_detail_lft">
            <?php if(count($showCat) > 0) { ?>
            <ul>
                    <?php foreach($showCat as $key=>$cat) { ?>
                        <li><?php echo $cat; ?> <a href="#itf" onclick="removeCategory(<?php echo $key; ?>)"><img src="<?php echo TemplateUrl();?>images/close_btn.png" alt="close"></a></li>
                    <?php } ?>

            </ul>
            <?php } else { ?>
                <p>No records !</p>
            <?php } ?>
        </div>
    </div>

</div>
<div style="display:none;" id="tab4">
    <div class="active_quote" id="my_bid">
        <h3>My Bids</h3>

        <?php if(count($bids) >0 ) { ?>
        <ul>

            <li>
                <div class="bid_lft">
                    <p><b>Quote Summary</b></p>
                </div>
                <div class="bid_second">
                    <p><b>Bid Amount</b></p>
                </div>
                <div class="bid_third">
                    <p><b>Message</b></p>
                </div>
                <div class="bid_forth">
                    <p><b>Status</b></p>
                </div>
                <div class="bid_rgt">
                    <p><b>Discard</b></p>
                </div>
                <div class="clear"></div>
            </li>
            <?php foreach($bids as $bid) {  ?>
            <li>
                <div class="bid_lft">
                    <a href="#itf" title="click for detail" onclick="showQuoteDetails(<?php echo $bid['quote_id']; ?>)">
                    <p><span><?php echo $bid['quote_name']; ?></span><br>
                    <span class="small-text"><?php echo WordLimit($bid['quote_desc']); ?></span></p>
                     </a>
                </div>
                <div class="bid_second">
                    <p><span><?php echo Currency($bid['bid_amount']); ?></span></p>
                </div>
                <div class="bid_third">
                    <p><span><?php echo WordLimit($bid['bid_desc']); ?></span></p>
                </div>
                <div class="bid_forth">
                    <p><span><?php echo $quote->getBidStatus($bid['status']); ?></span></p>
                </div>
                <div class="bid_rgt">
                    <p><span><a href="#itf" onclick="discardBid(<?php echo $bid['id']; ?>)"><img alt="close" src="<?php echo TemplateUrl(); ?>/images/close_btn.png"></a> </span></p>
                </div>
                <div class="clear"></div>
            </li>
           <?php } ?>
        </ul>
        <?php  } else { ?>
                <p style="text-align: center">No Bids Available !</p>
           <?php } ?>
    </div>
    <div id="bid_quote_detail"></div>
</div>

<div style="display: none;" id="tab5" class="detail">
    <div class="active_quote" id="quotelist">
        <h3>Browse Quotes</h3>
        <?php if(count($enquiries) > 0) { ?>
        <ul>
            <li>
                <div class="pro_name_lft">
                    <p><b>Quote Title</b></p>
                </div>
                <div class="pro_name_second">
                    <p><b>Bids</b></p>
                </div>
                <div class="pro_name_third">
                    <p><b>Started</b></p>
                </div>
                <div class="pro_name_rgt">
                    <p><b>Ends In</b></p>
                </div>
                <div class="clear"></div>
            </li>
            <?php foreach($enquiries as $enquiry) { ?>

                <?php if($enquiry['endTime'] > 0 ) { ?>
                <li>
                    <div class="pro_name_lft">
                        <a href="#itf" title="click for bid" onclick="showdetails(<?php echo $enquiry['id']; ?>)">
                        <p><span><?php echo $enquiry['quote_name']; ?></span></p>
                        <p><?php echo $enquiry['quote_desc']; ?></p>
                        </a>
                    </div>
                    <div class="pro_name_second">
                        <p><a href="#itf" title="click for bid" onclick="showdetails(<?php echo $enquiry['id']; ?>)"><span>Bid Now</span></a>
                        </p>
                    </div>
                    <div class="pro_name_third">
                        <p><span><?php echo date('d M Y',$enquiry['added_date']); ?></span></p>
                    </div>
                    <div class="pro_name_rgt">
                        <p><span><?php echo seconds2human($enquiry['endTime']); ?></span></p>
                    </div>
                    <div class="clear"></div>
                </li>
                <?php } ?>
            <?php } ?>

        </ul>
        <?php } else { ?>
            <p style="text-align: center; margin-top: 50px;">No Quotes Available !</p>
        <?php } ?>
    </div>
    <div class="enq_cont" id="quote_detail" style="display: none;">

    </div>
</div>
<div style="display:none;" id="tab6">
    <div class="active_quote" id="quote_active">
        <?php if(count($activeQuotes) > 0 ) { ?>
            <ul>
                <li>
                    <div class="pro_name_lft">
                        <p><b>Quote Title</b></p>
                    </div>
                    <div class="pro_name_second">
                        <p><b>Quote Created By </b></p>
                    </div>
                    <div class="pro_name_third">
                        <p><b>Status</b></p>
                    </div>
                    <div class="pro_name_rgt">
                        <p><b>Delivery Location</b></p>
                    </div>
                    <div class="clear"></div>
                </li>

                <?php foreach($activeQuotes as $quotes) { ?>
                    <li>
                        <div class="pro_name_lft">
                            <a href="#itf" title="click for details" onclick="showActiveQuotes(<?php echo $quotes['id']; ?>)">
                                <p><span><?php echo $quotes['quote_name']; ?></span></p>
                                <p><?php echo $quotes['quote_desc']; ?></p>
                            </a>
                        </div>
                        <div class="pro_name_second">
                            <p>
                                    <span><?php echo $quotes['customer']; ?></span>
                            </p>
                        </div>
                        <div class="pro_name_third">
                            <p><span><?php echo $quote->getQuoteStatus($quotes['quote_status']); ?></span></p>
                        </div>
                        <div class="pro_name_rgt">
                            <p><span><?php echo $quotes['location_name']; ?></span></p>
                        </div>
                        <div class="clear"></div>
                    </li>
                <?php } ?>

            </ul>
        <?php } else { ?>
            <p style="text-align: center; margin-top: 50px;">No Active Quotes Available !</p>
        <?php } ?>
    </div>
    <div id="active_quotes"></div>
</div>

<div style="display:none;" id="tab7">
    <div class="active_quote close_quote">
        <?php if(count($closedQuotes) >0 ){ ?>
            <ul>
                <li>
                    <div class="pro_name2_lft">
                        <p><b>Quote Title</b></p>
                    </div>
                    <div class="pro_name2_mid">
                        <p><b>Customer Review</b></p>
                    </div>
                    <div class="clear"></div>
                </li>
                <?php foreach($closedQuotes as $closedQuote) { $reviews = $quote->getCustomerReviews($closedQuote['id']); ?>
                    <li>
                        <div class="pro_name2_lft">
                            <p><span><?php echo $closedQuote['quote_name']; ?></span></p>
                            <p><?php echo WordLimit($closedQuote['quote_desc']); ?></p>
                        </div>
                        <div class="pro_name2_mid">
                            <?php if(count($reviews) >0 ) { ?>
                            <?php foreach($reviews as $review){ ?>
                                <p><?php echo WordLimit($review['review_text']).'<br> <span> by '.$review['registration_id'].'</span>'; ?></p>
                            <?php }?>
                            <?php } else { ?>
                                <p>No review !</p>
                            <?php } ?>
                            <div class="clear"></div>
                        </div>
                        <div class="pro_name2_rgt2">
                            <p><a href="<?Php echo CreateLink(array("ajax",'quote_id'=>$closedQuote['id']));?>" class="review">write review</a></p>
                        </div>
                        <div class="clear"></div>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p style="text-align: center; margin-top: 50px;">No closed quote available ! </p>
        <?php } ?>

    </div>
</div>

<div style="display: none;" id="tab9" class="detail">
    <h3>Add Product</h3>
    <div class="money product">

        <form id="addproduct" method="post" action="" enctype="multipart/form-data">
            <div class="mny_cont">
                <label>Product Name<span class="required">*</span> </label>
                <input type="text" name="product_name" value="">
                <div class="clear"></div>
            </div>
            <div class="mny_cont">
                <label>Product Image<span class="required">*</span></label>
                <input type="file" name="image" value="">
                <div class="clear"></div>
            </div>
            <div class="mny_cont">
                <label>Product Detail<span class="required">*</span></label>
                <textarea name="detail"></textarea>
                <div class="clear"></div>
            </div>


            <div class="mny_cont">
                <label>&nbsp;</label>
                <input type="submit" name="submit" value="Submit">
                <div class="clear"></div>
            </div>
        </form>
    </div>
</div>
</div>

<script language="javascript" src="<?php echo TemplateUrl();?>js/jquery.validate.js"></script>
<script type="text/javascript">

    function discardBid(id){
        $.ajax({
            url: "<?php echo SITEURL; ?>/itf_ajax/index.php",
            type :"POST",
            data: "id="+id+"&itfpg=discardBid",
            dataType:"json",
            success:function(data){
                if(data.res == 'error'){
                    alert("Accepted bid can not be deleted !");

                    return false;
                }else{
                    window.location = ("<?php echo CreateLink(array('dashboard#tab4')); ?>");
                    window.location.reload(true);
                }
            }

        });


    }

    function showActiveQuotes(id){
        $.ajax({
            url: "<?php echo SITEURL; ?>/itf_ajax/work_area.php",
            type :"POST",
            data: "id="+id,
            success:function(msg){
                $("#quote_active").hide();
                $("#active_quotes").html(msg);
                $("#active_quotes").show();
            }
        });
    }

    function removeCategory(id){
        $.ajax({
            url: "<?php echo SITEURL; ?>/itf_ajax/index.php",
            type :"POST",
            data: "id="+id+"&itfpg=removecat",
            success:function(msg){
                window.location = ("<?php echo CreateLink(array('dashboard#tab3')); ?>");
                window.location.reload(true);
            }
        });
    }

    function showdetails(id){
        $.ajax({
            url: "<?php echo SITEURL; ?>/itf_ajax/quote.php",
            type :"POST",
            data: "id="+id,
            success:function(msg){
                $("#quotelist").hide();
                $("#quote_detail").html(msg);
                $("#quote_detail").show();
            }
        });
    }

    function showQuoteDetails(id){
    alert(id);
        $.ajax({
            url: "<?php echo SITEURL; ?>/itf_ajax/quote.php",
            type :"POST",
            data: "id="+id,
            success:function(msg){
                $("#my_bid").hide();
                $("#bid_quote_detail").html(msg);
                $("#bid_quote_detail").show();
            }
        });
    }

    $(function(){

        jQuery.validator.addMethod("moneycheck", function(value, element) {


            if(parseFloat($('#withdraw_money').val()) >= 0){
                var temp =   parseFloat($('#total_money').val()) -  parseFloat($('#withdraw_money').val());
                if(parseFloat($('#withdraw_money').val()) > parseFloat($('#total_money').val()))
                {
                    return false;
                }else{
                    $('#rest_amount').val(temp);
                    return true;
                }
            } else {
                return false;
            }



        }, "Please enter value less than total money !");

        $("#category").autocompleteArray(
            [
                "<?php echo $categoriesData; ?>"
            ],
            {
                delay:10,
                minChars:1,
                cacheLength:0,
                matchSubset:1,
                autoFill:true,
                maxItemsToShow:10,
                mustMatch:1,
                matchContains:1
            }
        );

        $('#mny').validate({
            rules: {
                withdraw_money :{required:true, number:true, moneycheck:true},
                paypal_id: {required:true, email:true }

            },
            messages: {
                withdraw_money:{max:"Please enter a value less than or equal to Total Money."}
            }
        });

        $('#addproduct').validate({
            rules: {
                product_name :{required:true},
                detail: {required:true},
                image:{required:true,accept:'jpg|png|gif'}

            },
            messages: {
                image:{accept:'File must be jpg | png | gif extension '}
            }
        });



    });




</script>
