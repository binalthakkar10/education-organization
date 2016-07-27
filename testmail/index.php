<html>
<head>
<title>PHP Mail Test Script</title>
</head>
<body>
<fieldset style="width:300px">
<form action="" method="post">
<p>Email Address to send to&#58;</p>
<input type="text" name="addy" maxlength="50" />
<input type="submit" name="submit" value="Submit"/>
</form>
</fieldset>
<?php
if(($_POST['addy'] != '') && (isset($_POST['submit']))){
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $mail_to= $_POST['addy'];
        $mail_subject="Test email from $host";
        $mail_body="This is a test email, sent from $host$uri";
        $header = "Content-type: text/html\n";
        $header .= "From: \"PHP mail() Test Script\"<askhuran@guruseducation.com>\n";
        if(mail($mail_to, $mail_subject, $mail_body,$header)){
            print "Email sent successfully!";
        }
        else{
            print "Email did not send";
        }
}
?>
</body>
</html>