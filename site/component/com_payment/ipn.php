<?php
$p = new Paypal();             // initiate an instance of the class
$c = new Quote();
$payment =  new Payment();
$total =0;

    //$carts = $c->getCart();

    $st='';
    ob_start();
    print_r($_REQUEST);
    $st=ob_get_contents();
    ob_end_clean();


    $subject = 'Instant Payment Notification - Recieved Payment';
    $to = 'lmani@shahdeepinternational.com';    //  your email
    $body =  "An instant payment notification was successfully recieved\n";
    $body .= "from ".$_REQUEST['payer_email']." on ".date('m/d/Y');
    $body .= " at ".date('g:i A')."\n\nDetails:\n";
    $body .= $st;


    mail($to, $subject, $body);

    if(isset($_REQUEST['custom']) && !empty($_REQUEST['custom'])) {
        $payment->confirmOrder($_REQUEST['custom']);
        $payment->addPaymentInfo($_REQUEST);
    }
?>
