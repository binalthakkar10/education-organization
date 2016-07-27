<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <form action="" method="post" name="frm_payment"  id="frm_payment">
            <div id="page_content">
                <div class="teacher_login_page checkout" id="manage_account_page">
                    <div id="title_teacher_login"><h2>Checkout</h2></div>

                    <div id="form_label">
                        <label>Credit Card Type<span>*</span></label>
                        <ul class="card_type">
                            <li>
                                <span class="radio_btn"><img src="<?php echo TemplateUrl() ?>/images/visa.png" alt="Visa" > </span><input name="creditCardType" id="creditCardType" type="radio" value="Visa" checked="checked" tabindex="0"> 
                            </li>
                            <li>
                                <span class="radio_btn"><img src="<?php echo TemplateUrl() ?>/images/mastrocard.png" alt="Mastro Card" > </span><input name="creditCardType" id="creditCardType" type="radio" value="MasterCard"  tabindex="1">  
                            </li>
                            <li>
                                <span class="radio_btn"><img src="<?php echo TemplateUrl() ?>/images/american_express.png" alt="American Express" > </span><input name="creditCardType" id="creditCardType" type="radio" value="Amex" tabindex="2">  
                            </li>

                        </ul>
                    </div>
                    <input type="hidden" name="paymentType" value="Authorization" />
                    <input type="hidden" name="amount" readonly="readonly" id="amount" value="<?php echo $priceamt; ?>"/>
                    <input type="hidden" name="order_id" readonly="readonly" id="order_id" value="<?php echo $orderDetails['id']; ?>"/>
                    <input type="hidden" name="student_id" readonly="readonly" id="student_id" value="<?php echo $orderDetails['student_id']; ?>"/>
                    <input type="hidden" name="source" readonly="readonly" id="source" value="<?php echo $orderDetails['source']; ?>"/>

                    <div id="form_label">
                        <label>Card Number<span>*</span></label>
                        <input type="text" name="creditCardNumber" id="creditCardNumber" maxlength="19" tabindex="3" size="30"/>
                    </div>

                    <div id="form_label">
                        <label>Amount <span>*</span> :</label>

                        <input type="text" readonly="readonly"  size="30" value="<?php
                        if (isset($priceamt)) {
                            echo "$" . $priceamt;
                        } else {
                            echo "$" . $_REQUEST['amount'];
                        }
                        ?>" />

                    </div>


                    <div id="form_label">
                        <label>Select Card Expiry Date<span>*</span></label>
                        <ul class="date_grid" id="chk_page">

                            <li>
                                <select name="expDateMonth" id="expDateMonth">
                                    <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                    ?>
                                </select>  
                            </li>
                            <li><select name="expDateYear" id="expDateYear">
                                    <?php
                                    for ($i = date('Y'); $i <= 10 + date('Y'); $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            </li>
                        </ul>
                    </div>

                    <div id="form_label">
                        <label>Security Number :<span>*</span></label>
                        <input name="cvv2Number"  id="cvv2Number"value="" type="password" maxlength="5"/>
                    </div>
                    <div id="form_label" class="btn_chk">
                        <input class="button_btn_all  " type="submit" name="submit" value="Pay"  tabindex="10"/>
                    </div>
                </div>


            </div>
        </form>

    </div>




