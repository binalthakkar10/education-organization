<?php
$paymentObj = new Payment;
//print_r($_POST);

if (isset($_POST['paymentType']) and ($_POST['paymentType'] == "Sale")) {
    echo "<script>document.getElementById('manage_account_page').style.display = 'none;';document.getElementById('test121').style.display = 'block';</script>";
    
    function CreateRecurringPaymentsProfile() {
        $token = urlencode($_SESSION['TOKEN']);
        $email = urlencode($_SESSION['email']);
        $shipToName = urlencode($_SESSION['shipToName']);
        $shipToStreet = urlencode($_SESSION['shipToStreet']);
        $shipToCity = urlencode($_SESSION['shipToCity']);
        $shipToState = urlencode($_SESSION['shipToState']);
        $shipToZip = urlencode($_SESSION['shipToZip']);
        $shipToCountry = urlencode($_SESSION['shipToCountry']);
        $nvpstr = "&TOKEN=" . $token;
        $nvpstr.="&EMAIL=" . $email;
        $nvpstr.="&SHIPTONAME=" . $shipToName;
        $nvpstr.="&SHIPTOSTREET=" . $shipToStreet;
        $nvpstr.="&SHIPTOCITY=" . $shipToCity;
        $nvpstr.="&SHIPTOSTATE=" . $shipToState;
        $nvpstr.="&SHIPTOZIP=" . $shipToZip;
        $nvpstr.="&SHIPTOCOUNTRY=" . $shipToCountry;
        $nvpstr.="&PROFILESTARTDATE=" . urlencode("2011-07-01T0:0:0");
        $nvpstr.="&DESC=" . urlencode("Test Recurring Payment($1 monthly)");
        $nvpstr.="&BILLINGPERIOD=Month";
        $nvpstr.="&BILLINGFREQUENCY=1";
        $nvpstr.="&AMT=1";
        $nvpstr.="&INITAMT=1";
        $nvpstr.="&CURRENCYCODE=USD";
        $nvpstr.="&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];
        $resArray = hash_call("CreateRecurringPaymentsProfile", $nvpstr);
        $ack = strtoupper($resArray["ACK"]);
        return $resArray;
    }

    function PPHttpPost($methodName_, $nvpStr_, $orderID) {
        global $environment, $paymentObj;
        $API_UserName = urlencode(PAYPALUSERNAMEAPI);
        $API_Password = urlencode(PAYPALLPASSWORD);
        $API_Signature = urlencode(PAYPALLSIGNATURE);
        $API_Endpoint = PAYPALLURL;
        
        $version = urlencode('85.0');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        $httpResponse = curl_exec($ch);
        
        $data = array();
        $data['request'] = serialize($nvpStr_);
        $data['response'] = serialize($httpResponse);
        $data['order_id'] = $orderID;
        
        $paymentObj->addPaymentLog($data);
        
        if (!$httpResponse) {
            exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
        }
        $httpResponseAr = explode("&", $httpResponse);
        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = urldecode($tmpAr[1]);
            }
        }
        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }
        return $httpParsedResponseAr;
    }
    $paymentType = urlencode($_POST['paymentType']);    // or 'Sale'
    $creditCardFirstName = urlencode($_POST['first_name']);
    $creditCardLastName = urlencode($_POST['last_name']);
    $creditCardType = urlencode($_POST['creditCardType']);
    $creditCardNumber = urlencode($_POST['creditCardNumber']);
    $expDateMonth = urlencode($_POST['expDateMonth']);
    $padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
    $expDateYear = urlencode($_POST['expDateYear']);
    $cvv2Number = urlencode($_POST['cvv2Number']);
    $address1 = urlencode($_POST['address1']);
    $city = urlencode($_POST['city']);
    $state = urlencode($_POST['state']);
    $zip = urlencode($_POST['zip']);
    $country = urlencode($_POST['country']);    // US or other valid country code
    $amount = urlencode($_POST['amount']);
    $currencyID = urlencode('USD');
    $studentDetails = $paymentObj->getStudentDeatils($_POST['student_id'], $_POST['source']);

    
    $nvpStr .= "&FIRSTNAME=".urlencode($creditCardFirstName);
    $nvpStr .= "&LASTNAME=".urlencode($creditCardLastName);

    /*$nvpStr .= "&FIRSTNAME=".urlencode($studentDetails['first_name']);
    $nvpStr .= "&LASTNAME=".urlencode($studentDetails['last_name']);*/
//print_r($_POST); die;

    $nvpStr .="&DESC=".urlencode($studentDetails['loc_name']);
    $nvpStr .= "&EMAIL=".urlencode($studentDetails['email']);
    $method = 'DoDirectPayment';
    if ($_POST['type'] == '2') {
        //$date = date("Y-m-d", strtotime("+1 month")). 'T0:0:0';
        $nvpStr .= "&SUBSCRIBERNAME=".urlencode($studentDetails['first_name']. ' '. $studentDetails['last_name']);
        $date = date("Y-m-d", strtotime($_POST['installment_start_date'])). 'T0:0:0';
        $nvpStr.="&PROFILEREFERENCE=" . $_POST['course_name'];
        $nvpStr.="&PROFILESTARTDATE=" . urlencode("$date");
        $nvpStr.="&BILLINGPERIOD=Month";
        $nvpStr.="&BILLINGFREQUENCY=1";
        $nvpStr.="&TOTALBILLINGCYCLES=".$_POST['no_of_installment'];
        $nvpStr.="&INITAMT=".$amount;
        //$nvpStr.= "&MAXFAILEDPAYMENTS=3" ;
        $nvpStr.= "&AUTOBILLOUTAMT=AddToNextBilling" ;
        $amount = urlencode($_POST['installment_amt']);
        $nvpStr.="&IPADDRESS=".$_SERVER['REMOTE_ADDR'];
        $ack = strtoupper($resArray["ACK"]);
        $method = 'CreateRecurringPaymentsProfile';
    }else {
       $nvpStr.="&DESC =" . $studentDetails['loc_name']. " ". $_POST['course_name'];
       $nvpStr .= "&CUSTOM=".urlencode($studentDetails['first_name']. ' '. $studentDetails['last_name']);
    }
    $nvpStr .= "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber" .
            "&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number" .
            "&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

    $order_id = $_POST['order_id'];
    $cardDetail = array();
    $cardDetail['order_id'] = $_POST['order_id'];
    $cardDetail['student_id'] = $_POST['student_id'];
    $cardDetail['source'] = $_POST['source'];
    $cardDetail['card_type'] = $_POST['creditCardType'];
    $cardDetail['card_number'] = $_POST['creditCardNumber'];
    $cardDetail['expiry_date'] = $expDateMonth.' ' .$expDateYear;
    $cardDetail['security_no'] = $cvv2Number;
    $paymentObj->addCreditCardDetails($cardDetail);
    $httpParsedResponseAr = PPHttpPost($method, $nvpStr, $order_id);
    
    
    
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
        $serialresult = serialize($httpParsedResponseAr);
        $data['id'] = $_SESSION['order_id'];
        $data['amount'] = $_SESSION['amount'];
        $data['payment_status'] = 'success';
        $data['student_id'] = $_SESSION['student_id'];
        $data['reg_status'] = 1;
        $data['status'] = 1;
        $orderDetails = $paymentObj->checkOrder($data['id']);
        if ($orderDetails['source'] != 'tournament') {
            $studentObj = new Student;
            $studentObj->registrationConfirmationForStudent($data);
            $studentObj->registrationConfirmationForAdmin($data);
            $studentObj->registrationConfirmationForTeacher($data);
            $updateOrder = $paymentObj->updatepaymenttable($data);
        } else {
            $studentObj = new Tournament;
            $studentObj->confirmationForTournamentStudent($data);
            $studentObj->confirmationTournamentStudentForAdmin($data);
            $paymentObj->updateTournamentPaymenttable($data);
        }
        flashMsg("Your transaction has been successfully completed");
        redirectUrl('index.php?itfpage=contents&itemid=payment-success-page');
    } else {
        $data['id'] = $_SESSION['order_id'];
        $data['amount'] = $_SESSION['amount'];
        $data['payment_status'] = 'failure';
        $updateOrder = $paymentObj->updatepaymenttable($data);
        flashMsg("Error : " . $httpParsedResponseAr["ACK"] . "<br/> " . $httpParsedResponseAr["L_LONGMESSAGE0"] . "<br/>" . $httpParsedResponseAr["L_LONGMESSAGE1"], 2);
        redirectUrl('index.php?itfpage=contents&itemid=payment-failure-page');
    }
} else {
    $utilsObj = new Utils;
    if (empty($_POST['order_id'])) {
        flashMsg("You are not authorize to access this page", "2");
        redirectUrl('index.php');
    } else {
        $orderID = $utilsObj->decryptIds($_POST['order_id']);
        $first_name = $utilsObj->decryptIds($_POST["first_name"]);
        $last_name = $utilsObj->decryptIds($_POST["last_name"]);
        $orderDetails = $paymentObj->checkOrder($orderID);
        
        //print_r($orderDetails);

        if($orderDetails['id'] != '' && $orderDetails['amount'] != '') {
            $_SESSION['order_id'] = $orderDetails['id'];
            $_SESSION['amount'] = $orderDetails['amount'];
            $_SESSION['student_id'] = $orderDetails['student_id'];
            $priceamt = $orderDetails['amount'];
        } else {
            flashMsg("You are not authorize to access this page", "2");
            redirectUrl('index.php');
        }
    }
}
$validJs = TemplateUrl() . "js2/jquery.validate.js";
//  DROP TABLE `itf_student_attendance`;
?>
<script src="<?php echo $validJs ?>"></script>
<script>
    $(document).ready(function() {

        $.validator.addMethod('integer', function(value, element, param) {
            return (value != 0) && (value == parseInt(value, 10));
        }, 'Please enter a non zero integer value!');
        $('#frm_payment').validate({// initialize the plugin
          errorElement: "span",
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                creditCardNumber: {
                    required: true,
                    integer: true,
                    minlength: 15,
                    maxlength: 16
                },
                expDateMonth: {
                    required: true
                },
                expDateYear: {
                    required: true

                },
                cvv2Number: {
                    required: true,
                    integer: true,
                    minlength: 3,
                    maxlength: 5
                }
            },
            messages: {
                first_name: {
                    required: "Please Enter a Valid First Name"
                },
                last_name: {
                    required: "Please Enter a Valid Last Name"
                },
                creditCardNumber: {
                    required: "Please enter a  Credit Card Number",
                    minlength: "Please Enter valid Credit Card Number",
                    maxlength: "Please Enter valid Credit Card Number"
                },
                expDateMonth: {
                    required: "Please select a  Expiry Month"
                },
                expDateYear: {
                    required: "Please select a Expiry Year"
                },
                cvv2Number: {
                    required: "Please enter a Security Number",
                    minlength: "Your Cvv2 Number must consist of at least 3 Digit",
                    maxlength: "Your Cvv2 Number must consist of maximum 5 Digit"
                }
            },
            submitHandler: function() {   
                $("submit").attr("disabled", true);
                $("submit").val('Please wait Processing');
                document.getElementById('manage_account_page').style.display = 'none;';
                document.getElementById('test121').style.display = 'block';
                form.submit();
            }

        });
        });</script>
<style>
    #test121 {
        color: #000000;
        font-family: "UbuntuMedium";
        font-size: 15px;
        margin: 0 auto;
        text-align: center;
        width: 60%;
    }
</style>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <form action="" method="post" name="frm_payment"  id="frm_payment">
            <div id="page_content">
                <div class="teacher_login_page checkout" id="manage_account_page">
                    <div id="title_teacher_login"><h2>Checkout</h2></div>
                    <div id="form_label">
                        <label>Cardholder First Name<span>*</span></label>
                        <input type="text" name="first_name" id="first_name" tabindex="1" size="30" />
                    </div>
                    <div id="form_label">
                        <label>Cardholder Last Name<span>*</span></label>
                        <input type="text" name="last_name" id="last_name" tabindex="2" size="30" />
                    </div>
                    <div id="form_label">
                        <label>CardholderCredit Card Type<span>*</span></label>
                        <ul class="card_type">
                            <li>
                                <input name="creditCardType" id="creditCardType" type="radio" value="Visa" checked="checked" tabindex="3"><span class="radio_btn"><img src="<?php echo TemplateUrl() ?>/images/visa.png" alt="Visa" > </span> 
                            </li>
                            <li>
                                <input name="creditCardType" id="creditCardType" type="radio" value="MasterCard"  tabindex="4"><span class="radio_btn"><img src="<?php echo TemplateUrl() ?>/images/mastrocard.png" alt="Mastro Card" > </span>  
                            </li>
                            <li>
                                <input name="creditCardType" id="creditCardType" type="radio" value="Amex" tabindex="5">  <span class="radio_btn"><img src="<?php echo TemplateUrl() ?>/images/american_express.png" alt="American Express" > </span>
                            </li>

                        </ul>
                    </div>
                    <input type="hidden" name="paymentType" value="Sale" />
                    <input type="hidden" name="type" value="<?php echo $orderDetails['payment_type'] ?>" />
                    <input type="hidden" name="amount" readonly="readonly" id="amount" value="<?php echo $priceamt; ?>"/>
                    <input type="hidden" name="order_id" readonly="readonly" id="order_id" value="<?php echo $orderDetails['id']; ?>"/>
                    <input type="hidden" name="student_id" readonly="readonly" id="student_id" value="<?php echo $orderDetails['student_id']; ?>"/>
                    <input type="hidden" name="source" readonly="readonly" id="source" value="<?php echo $orderDetails['source']; ?>"/>
                    <input type="hidden" name="no_of_installment" readonly="readonly" id="source" value="<?php echo $orderDetails['no_of_installment']; ?>"/>
                    <input type="hidden" name="installment_amt" readonly="readonly" id="source" value="<?php echo $orderDetails['installment_amount']; ?>"/>
                    <input type="hidden" name="installment_start_date" readonly="readonly" id="source" value="<?php echo $orderDetails['installment_start_date']; ?>"/>
                    <input type="hidden" name="course_name" readonly="readonly" value="<?php echo $orderDetails['source'].'_'.$orderDetails['course_name']; ?>"/>

                    <div id="form_label">
                        <label>Card Number<span>*</span></label>
                        <input type="text" name="creditCardNumber" id="creditCardNumber" maxlength="19" tabindex="4" size="30"/>
                    </div>

                    <div id="form_label">
                        <label>Amount <span>*</span> </label>

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
                                <select name="expDateMonth" id="expDateMonth" tabindex="7">
                                    <?php
                                   for ($i = 1; $i <= 12; $i++) {
                                        echo '<option value="' . $i . '"';
                                        if(date('n')==$i) {
                                                 echo " selected";
                                        }

                                        echo '>' . $i . '</option>';
                                    }
                                    ?>
                                </select>  
                            </li>
                            <li><select name="expDateYear" id="expDateYear" tabindex="8">
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
                        <label>CVV <span>*</span></label>
                        <input name="cvv2Number"  id="cvv2Number"value="" type="password" maxlength="5" tabindex="9"/>
                    </div>
                    <div id="form_label" class="btn_chk">
                        <input class="button_btn_all  " type="submit" name="submit" value="Pay"  tabindex="10"  id="submit"/>
                        
                    </div>
                    <div id="form_label" class="btn_chk">
                        <img src="https://www.paypal.com/en_US/i/icon/verification_seal.gif" border="0">
                    </div>
                    <div id="form_label" class="btn_chk">
                        <p>Your payment will be processed by Paypal secure payment processing system. Gurus Educational Services does not keep your credit card information on file.</p>
                    </div>
                </div>

                <div id="test121" style="display: none">
                    <img src="<?php echo TemplateUrl() ?>/images/loader.GIF" /><br/><br/>
                    <label>Processing. Do not press any button until this process completes.</label>

                </div>
            </div>
        </form>

    </div>
</div>