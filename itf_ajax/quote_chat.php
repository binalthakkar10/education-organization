<?php
require('../itfconfig.php');
$obj = new Quote();
$userobj = new User();
$userinfo = $userobj->getUserInfo($_SESSION['FRONTUSER']['id']);

if(isset($_REQUEST['chat_text']) && !empty($_REQUEST['chat_text']) ){

    $obj->addQuoteChat($_REQUEST);
}
$results = array();
if(isset($_REQUEST['quote_id']) && !empty($_REQUEST['quote_id']) ){

    $results = $obj->getQuoteChat($_REQUEST['quote_id']);

}
?>

<?php if(count($results) > 0) { ?>
<?php foreach($results as $result) { ?>
<div class="board_cont_user">
    <img src="<?php echo PUBLICPATH."/profile/"; ?><?php if($result['profile_image']){ echo $result['profile_image'];} else { echo 'no_image.jpg'; }; ?>">
    <p><span><?php echo $result['name']; ?></span><label><?php echo date('d M Y h:i A',$result['added_date']); ?></label><br><?php echo $result['chat_text']; ?></p>
    <div class="clear"></div>
</div>
<?php }  } else { ?>
    <div class="board_cont_user">
        <p>No Conversation here !</p>
        <div class="clear"></div>
    </div>

<?php } ?>