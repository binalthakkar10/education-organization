<?php
$userobj=new User();

$datas=$userobj->getAllmemberPlan();
$Supdatas=$userobj->getAllmemberPlanSup();
$Bothdatas=$userobj->getAllmemberPlanBoth();
//echo "<pre>";print_r($datas);die;
?>
<div class="main_mat">
<p><a href="<?php echo ITFPATH; ?>">Home</a> / <a href=" ">Register</a> </p>
</div>

<div class="regis_new">
    <h3>Register here</h3>
    	<div class="regis_new_cont">
            

        	<div class="regis_customer">
                    <?php foreach($datas as $itfdata){?>
            <h4>Customer</h4>
                
                <div class="regis_customer_cont">
                <ul>
                	<li>
                        <div class="regis_customer_cont_lft">
                        <p>Name:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                   <p><?php echo $itfdata['type'] ?></p>
                        </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                        <div class="regis_customer_cont_lft">
                        <p>Amount:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                         <p><?php echo $itfdata['amount'] ?></p>
                        </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                        <div class="regis_customer_cont_lft">
                        <p>Time:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                        <p><?php echo $itfdata['duration_day'] ?> <?php echo $itfdata['duration_type'] ?></p>
                        </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                        <div class="regis_customer_cont_lft">
                        <p>Description:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                       <p><?php echo $itfdata['description'] ?></p>
                        </div>
                        </li>
                        </ul>
                        <div class="clear"></div>
                        <div class="regis_lnk">
                       <a href="<?php echo CreateLink(array("register","itemid"=>"customer",id=>$itfdata['id'])); ?>">Register</a>
<!--                        <a href="#">Register</a>-->
                        </div>
                </div>
                 <?php }?>
            </div>
            <div class="regis_supplier">
                <?php foreach($Supdatas as $itfsup){?>
            <h4>Supplier</h4>
                
                <div class="regis_customer_cont">
                <ul>
                	<li>
                        <div class="regis_customer_cont_lft">
                        <p>Name:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                       <p><?php echo $itfsup['type'] ?></p>
                        </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                        <div class="regis_customer_cont_lft">
                        <p>Amount:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                       <p><?php echo $itfsup['amount'] ?></p>
                        </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                        <div class="regis_customer_cont_lft">
                        <p>Time:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                     <p><?php echo $itfsup['duration_day'] ?> <?php echo $itfsup['duration_type'] ?></p>
                        </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                        <div class="regis_customer_cont_lft">
                        <p>Description:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                        <p><?php echo $itfsup['description'] ?></p>
                        </div>
                        </li>
                        </ul>
                        <div class="clear"></div>
                        <div class="regis_lnk">
                        <a href="<?php echo CreateLink(array("register","itemid"=>"supplier",id=>$itfsup['id'])); ?>">Register</a>
                        </div>
                </div>
              <?php }?>
            </div>
            <div class="regis_both">
                   <?php foreach($Bothdatas as $itfboth){?>
            <h4>Both</h4>
         
                <div class="regis_customer_cont">
                <ul>
                	<li>
                        <div class="regis_customer_cont_lft">
                        <p>Name:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                       <p><?php echo $itfboth['type'] ?></p>
                        </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                        <div class="regis_customer_cont_lft">
                        <p>Amount:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                      <p><?php echo $itfboth['amount'] ?></p>
                        </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                        <div class="regis_customer_cont_lft">
                        <p>Time:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                        <p><?php echo $itfboth['duration_day'] ?> <?php echo $itfboth['duration_type'] ?></p>
                        </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                        <div class="regis_customer_cont_lft">
                        <p>Description:</p>
                        </div>
                        <div class="regis_customer_cont_right">
                        <p><?php echo $itfboth['description'] ?></p>
                        </div>
                        </li>
                        </ul>
                        <div class="clear"></div>
                        <div class="regis_lnk">
                      <a href="<?php echo CreateLink(array("register","itemid"=>"both",id=>$itfboth['id'])); ?>">Register</a> 
                        </div>
                </div>
                <?php }?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
