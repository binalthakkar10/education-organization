<?php AddJS("contact/assests/contact.js","component"); ?>
<div class="contant_left">
  <div class="contact_box"> Contact Information</div>
  
  
  <div class="contact_form">
  <form name="frmcontact" id="frmcontact" method="post">
  <div class="contact_text">Name<span>&nbsp;*</span></div>
    <div class="contact_texta"><input name="name" id="name" type="text" class="contactinp" /></div>
    
      <div class="contact_text">Email <span>&nbsp;*</span></div>
    <div class="contact_texta"><input name="emailid" id="emailid" type="text" class="contactinp" /></div>
    
      <div class="contact_text">Telephone</div>
    <div class="contact_texta"><input name="phoneno" id="phoneno" type="text" class="contactinp" /></div>
    
      <div class="contact_text">Comment <span>&nbsp;*</span></div>
    <div class="contact_texta">

        <textarea name="comments" id="comments" cols="45" rows="7" class="contactinp"></textarea>

    </div>
     
  <div class="contact_text"><input type="submit" value="" name="submit" id="submit" class="submitcontact" /></div>
  </form>
  </div>

	<div class="mappinfo">&nbsp;</div>

</div>