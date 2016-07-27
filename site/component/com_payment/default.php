<?php
if(empty($_SESSION['FRONTUSER'])) {
    redirectUrl(CreateLink(array("signin")));
}

if(empty($_SESSION['cart'])) {
    redirectUrl(CreateLink(array("dashboard")));
}


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

foreach($carts as $cart){
    $orderData = array(
                'order_id'=>$order_id,
                'bid_id'=>$cart['id'],
                'amount'=>$cart['bid_amount']
    );

    $detail_id = $payment->addOrderDetails($orderData);

}


$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
//$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url

$success_url = SITEURL.'/'.CreateLink(array("payment","itemid"=>'success'));
$cancel_url = SITEURL.'/'.CreateLink(array("payment","itemid"=>'cancel'));
$notify_url = SITEURL.'/'.CreateLink(array("payment","itemid"=>'ipn'));

$p->add_field('business', 'rahul@shahdeep.com');
$p->add_field('return', $success_url);
$p->add_field('cancel_return', $cancel_url);
$p->add_field('notify_url', $notify_url);
$p->add_field('custom', $order_id);


foreach($carts as $key=>$cart){
    $counter = $Key+1;
    $p->add_field('item_name_'.$counter, $cart['quote_name']);
    $p->add_field('amount_'.$counter, $cart['bid_amount']);
}

$c->emptyCart();
$p->paypalPost(); // submit the fields to paypal
//$p->dumpFields();      // for debugging, output a table of all the fields

?>