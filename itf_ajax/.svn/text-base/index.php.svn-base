<?php

$msgs="Login";
require('../itfconfig.php');
$ojbuser=new User();
if(isset($_REQUEST['itfpg']) and $_REQUEST['itfpg']=="uservalidate")
{
	
	if($ojbuser->userUniqueByUsername($_REQUEST['username'])=="1")
	echo 'false';
	else
	echo 'true';
}
elseif(isset($_REQUEST['emailid']))
{
	if($ojbuser->CheckEmailId($_REQUEST['email']))
        echo 'false';
	else
        echo 'true';
}
elseif(isset($_REQUEST['itfusername']))
{
	if($ojbuser->UserCheckUsername($_REQUEST['itfusername']))
        echo 'false';
	else
        echo 'true';
}
elseif(isset($_REQUEST['itfpg']) and $_REQUEST['itfpg']== "review")
{
    $quote = new Quote();
    $quote->addReview($_REQUEST);

    return true;
}
elseif(isset($_REQUEST['itfpg']) and $_REQUEST['itfpg']== "removecat")
{
    $quote = new User();
    $quote->removeCategory($_REQUEST);

    return true;
}
elseif(isset($_REQUEST['itfpg']) and $_REQUEST['itfpg']== "quote")
{

    $quote = new Quote();
    $quote->removeQuote($_REQUEST['id']);

    return true;
}

elseif(isset($_REQUEST['user']))
{

    $quote = new Quote();
  //  $notif=$quote->getCart();
      $p = new Paypal();             // initiate an instance of the class
$c = new Quote();
$payment =  new Payment();
$total =0;

$carts = $c->getCart();

foreach($carts as $cart){
    $quote_id = $cart['quote_id'];
    $total += $cart['bid_amount'];
}

$datas = array(
        'user_id'=> $_SESSION['FRONTUSER']['id'],
        'quote_id'=> $quote_id,
        'quantity'=> count($carts),
        'amount'=> $total
);

$order_id = $payment->addOrder($datas);
$_SESSION['orderid']=$order_id;
$_SESSION['quote_id']= $quote_id;
foreach($carts as $cart){
    $orderData = array(
                'order_id'=>$order_id,
                'bid_id'=>$cart['id'],
                'amount'=>$cart['bid_amount']
    );

    $detail_id = $payment->addOrderDetails($orderData);

}
    $ojbuser->adminNotifMail($carts);
    

    return true;
}

elseif(isset($_REQUEST['itfpg']) and $_REQUEST['itfpg']== "cart")
{
    $quote = new Quote();
    $quote->removeCart($_REQUEST['id']);

    return true;
}
elseif(isset($_REQUEST['itfpg']) and $_REQUEST['itfpg']== "discard")
{
    $quote = new Quote();
    $quote->discardQuote();

    return true;
}
elseif(isset($_REQUEST['itfpg']) and $_REQUEST['itfpg']== "discardBid")
{
    $quote = new Quote();
    $bid = $quote->getBidsById($_REQUEST['id']);

    if($bid['status'] == 0 || $bid['status'] == 2 ){
        $quote->deleteBid($_REQUEST['id']);

        $json['res'] = 'success';
        echo json_encode($json);

    } else {

        $json['res'] = 'error';
        echo json_encode($json);
    }


}

elseif(isset($_REQUEST['itfpg']) and $_REQUEST['itfpg']== "discardBida")
{
    $quote = new Quote();
   

        $quote->deleteExpQu($_REQUEST['id']);

        $json['res'] = 'success';
        echo json_encode($json);



}
elseif(isset($_POST['itfpg']) and !empty($_POST['itfpg']))
{
	if($_POST['itfpg']=="news")
	{
	?>
	<ul>
		<?php 
		$objtimezone=new TimeZone();
		$newes=$objtimezone->getNews();
		foreach($newes as $k=>$news):
		$newsdata=(array)$news;
		if($k==10)break;
		?>
			<li>
				<div class="title"><a href="<?php echo $newsdata["link"]; ?>" target="_blank"><?php echo $newsdata["title"]; ?></a></div>
				<div class="info">
					<div class="cat"><?php echo WordLimit($newsdata["description"],8); ?>...</div>
					<span class="cat"><?php echo $newsdata["pubDate"]; ?></span>
				</div>
				<div class="clear"></div>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php
	
	
	}
	elseif($_POST['itfpg']=="astro")
	{
	?>
	<ul>
		<?php 
		$objtimezone=new TimeZone();
		$newes=$objtimezone->getAstrology();
		foreach($newes as $k=>$news):
		$newsdata=(array)$news;
		//if($k==10)break;
		?>
			<li>
				<div class="title"><a href="<?php echo $newsdata["link"]; ?>" target="_blank"><?php echo $newsdata["title"]; ?></a></div>
				<div class="info">
				<div class="cat"><?php echo WordLimit($newsdata["description"],15); ?>...</div>
				</div>		
				<div class="clear"></div>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php
	}
	elseif($_POST['itfpg']=="currancy")
	{
		$currancyfrom=$_REQUEST["cfrom"];
		$currancyto=$_REQUEST["cto"];
		$objtime=new TimeZone();
		$res=$objtime->getCurrency($currancyfrom,$currancyto);
		echo $res;
	}
}
?>