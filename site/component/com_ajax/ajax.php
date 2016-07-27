<?php
    Template::setTemplate("blank");
?>
<div class="review_box">
<form id="review" method="post" action="" >
    <input type="hidden" name="quote_id" value="<?php echo $_REQUEST['quote_id']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['FRONTUSER']['id']; ?>">
    <textarea name="review_text" id="review_text" style="width: 100%; height: 200px;" ></textarea>
    <input type="button" id='review_button' name="submit" value="Submit" >
</form>
</div>

<script>
    $(document).ready(function() {

        $('#review_button').click( function(){
            if($('#review_text').val() == '')
            {
                alert("Please enter review text. !");
                $('#review_text').focus();
            } else{

                $.ajax({
                    url: "<?php echo SITEURL; ?>/itf_ajax/index.php",
                    type :"POST",
                    data: $('#review').serialize()+"&itfpg=review",
                    success:function(msg){
                        alert('Your review is successfully submitted');
                        window.location = ("<?php echo CreateLink(array('dashboard#tab7')); ?>");
                        window.location.reload(true);
                    }
                });
            }
        });

    });

</script>