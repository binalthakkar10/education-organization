<?php
error_reporting( E_ALL );

require_once('includes/classes/class.phpmailer.php');

$mail = new PHPMailer();

try {
    $mail->isMail();
    $mail->setFrom('info@guruseducation.com', 'Gurus');
    $mail->addAddress('ashishkhurana1@gmail.com', 'Debate Club');
    $mail->Subject = 'PHPMailer Test Subject';
    $mail->msgHTML('<p>Test Email</p>');
    // optional - msgHTML will create an alternate automatically
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
    //$mail->addAttachment('../examples/images/phpmailer.png'); // attachment
    //$mail->addAttachment('../examples/images/phpmailer_mini.png'); // attachment
   // $mail->action_function = 'callbackAction';
    $mail->send();
    echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
}

?>
