<div class="main_mat">
<p><a href="<?php echo SITEURL; ?>">Home</a> / <a href="<?php echo CreateLink(array('product')); ?>">Sitemap</a></p>
</div>
   <div class="about_us1">
         <h3>Sitemap</h3>
         <div class="regis">
        <div class="regis_lft1">
        <p>Top Menus</p>
         <li><a href="<?php echo SITEURL; ?>" <?php if($_SERVER['REQUEST_URI']== ITFPATH){ echo 'class="active"'; } ?> >Home</a></li>
                        <li><a href="<?php echo CreateLink(array("product")); ?>" <?php if($page == 'product'){ echo 'class="active"'; } ?>>Products</a></li>
                        <li><a href="<?php echo CreateLink(array("service")); ?>" <?php if($page == 'service'){ echo 'class="active"'; } ?>>Services</a></li>
                        <li><a href="<?php echo CreateLink(array("contents","itemid"=>'suppliers')); ?>" <?php if($cms == 'suppliers'){ echo 'class="active"'; } ?>>Suppliers</a></li>
                        <li><a href="<?php echo CreateLink(array("contents","itemid"=>'about-us')); ?>"<?php if($cms == 'about-us'){ echo 'class="active"'; } ?>>About Us</a></li>
                        <li><a href="<?php echo CreateLink(array("contents","itemid"=>'careers')); ?>" <?php if($cms == 'careers'){ echo 'class="active"'; } ?>>Careers</a></li>
                        <li><a href="<?php echo CreateLink(array("contents","itemid"=>'help')); ?>" <?php if($cms == 'help'){ echo 'class="active"'; } ?>>Help</a></li>
        </div>
        <div class="regis_mid">
        <img src="<?php echo TemplateUrl();?>images/regis_separater.jpg" alt="">
        </div>
        <div class="regis_rgt1">
        <p>Footer Menus</p>
        
              <li><a href="<?php echo ITFPATH; ?>">Home</a></li>
               <li> <a href="<?php echo CreateLink(array("product")); ?>">Products</a></li>
               <li><a href="<?php echo CreateLink(array("service")); ?>">Services</a></li>
               <li>  <a href="<?php echo CreateLink(array("contents","itemid"=>'suppliers')); ?>">Suppliers</a></li>
                <li> <a href="<?php echo CreateLink(array("contents","itemid"=>'contact-us')); ?>">Contact Us</a></li>
                 <li><a href="<?php echo CreateLink(array("contents","itemid"=>'careers')); ?>">Careers</a></li>
                  <li><a href="<?php echo CreateLink(array("contents","itemid"=>'help')); ?>">Help</a></li>
                   <li><a href="<?php echo CreateLink(array("contents","itemid"=>'terms-condition')); ?>">Terms & Conditions</a></li>
                       <li><a href="<?php echo CreateLink(array("contents","itemid"=>'privacy-policy')); ?>">Privacy Policy</a></li>
                    
                    
        
       
        </div>
        <div class="clear"></div>
    </div>
         </div>

