<?php
//echo "state add";

if (isset($_POST['id'])) {
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {
        $obj->updatePartnerData($_POST);
        flash($pagetitle . " is successfully Updated");
    } else {
        $obj->addPartnerData($_POST);
        flash($pagetitle . " is successfully added");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }

    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}

$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $obj->PartnersDetail($ids);


?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="assests/jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript">var geocoder;
    var map;
    var marker;

    function initialize() {
//MAP
        var latlng = new google.maps.LatLng(41.659, -4.714);
        var options = {
            zoom: 16,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.SATELLITE
        };

        map = new google.maps.Map(document.getElementById("map_canvas"), options);

        //GEOCODER
        geocoder = new google.maps.Geocoder();

        marker = new google.maps.Marker({
            map: map,
            draggable: true
        });

    }

    $(document).ready(function() {

        initialize();

        $(function() {
            $("#address").autocomplete({
                //This bit uses the geocoder to fetch address values
                source: function(request, response) {
                    geocoder.geocode({'address': request.term}, function(results, status) {
                        response($.map(results, function(item) {
                            var address = "", city = "", state = "", zip = "", country = "", formattedAddress = "";
                            for (var i = 0; i < results[0].address_components.length; i++) {
                                var addr = results[0].address_components[i];
                                if (addr.types[0] == 'country')
                                    country = addr.short_name;
                                else if (addr.types[0] == 'street_address')
                                    address = address + addr.long_name;
                                else if (addr.types[0] == 'route')
                                    address = address + addr.long_name;
                                else if (addr.types[0] == ['administrative_area_level_1'])
                                    state = addr.long_name;
                                else if (addr.types[0] == ['administrative_area_level_2'])
                                    town = addr.long_name;
                                else if (addr.types[0] == ['locality'])
                                    city = addr.long_name;
                                else if (addr.types[0] == ['location'])
                                    location = addr.location;
                                else if (addr.types[0] == 'postal_code')       // Zip
                                    zip = addr.short_name;
                            }
                            // alert('Formated Address: \n' + 'City: ' + city + '\n' + 'Town: ' + town + '\n' + 'State: ' + state + '\n' + 'Country: ' + country + '\n' + 'Coordinates: ' + location);

                            return {
                                label: item.formatted_address,
                                value: item.formatted_address,
                                city: city,
                                state: state,
                                country: country,
                                zip: zip,
                                latitude: item.geometry.location.lat(),
                                longitude: item.geometry.location.lng()
                            }
                        }));
                    })
                },
                //This bit is executed upon selection of an address
                select: function(event, ui) {
                    //alert(ui.item.state + ' ' + ui.item.zip);
                    $("#city").val(ui.item.city);
                    $("#state").val(ui.item.state);
                    $("#zip").val(ui.item.zip);
                    $("#country").val(ui.item.country);
                    $("#latitude").val(ui.item.latitude);
                    $("#longitude").val(ui.item.longitude);
                    var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
                    marker.setPosition(location);
                    map.setCenter(location);
                }
            });
        });

        //Add listener to marker for reverse geocoding
        google.maps.event.addListener(marker, 'drag', function() {
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    //alert('test');
                    if (results[0]) {
                        $('#address').val(results[0].formatted_address);
                        $('#latitude').val(marker.getPosition().lat());
                        $('#longitude').val(marker.getPosition().lng());
                    }
                }
            });
        });

    });</script>    
<script type="text/javascript">
    $(document).ready(function() {
    	jQuery.validator.addMethod("contact", function(value, element) {
    		 return this.optional(element) || /^[0-9-()]{8,15}$/.test(value);
    		}, "Please enter valid number. It may contain 0-9,-,() with 8-15 chars.");
        $("#itffrminput").validate({
            rules: {
            	partner_name: "required",
            	partner_type: "required",
            	address: "required",
            	city: "required",
            	state: "required",
            	zip: {
	                    required: true,
	                    minlength: 5,
	                    maxlength: 6
	                },
            	country: "required",
            	registration_phone:{
	               required:true,
	               contact: true,
	               rangelength:[8,15],
	               }, 
            	fax: "required",
            	primary_first_name: "required",
            	primary_last_name: "required",
            	primary_title: "required",
            	primary_email:{required:true, email:true},
                company_email:{required:true, email:true},	
                contract_date: "required",
                registration_url: "required",
                registration_type:"required",
                fingerprinting_required:"required",
                invoice_method:"required",
                payment_percentage:"required",
                notes:"required",   	    	
            },
            messages: {
            	partner_name: "Please enter organization name",
            	partner_type: "Please enter first name",
            	address:" Please Enter a Valid Email",
            	city: "Please enter city",
            	state: "Please enter state",
            	country: "Please enter Country",
            	zip: "Please enter zipcode",
            	registration_phone:{
						required:"Please enter your phone number",
						contact:"Please enter the correct phone number",
                 },
                fax: "Please enter zipcode",
                primary_first_name:"Please enter the first name", 
                primary_last_name:"Please enter the last name",
                primary_title:"Please enter the Title",
                primary_email:"Please enter the email id",
                company_email:"Please enter assignrd company email",
                contract_date:"Please enter the date of the contract signed",
                registration_url:"Please enter the registration URL",
                registration_type:"Please select the registration type",
                fingerprinting_required:"Please select the figer print",
                invoice_method:"Please enter the invoice method",
                payment_percentage:"Please enter the payment percentage",
                notes:"Please enter the notes",
            }
        });
    });
</script>

<style>
    .ui-autocomplete {
        background-color: white;
        width: 300px;
        border: 1px solid #cfcfcf;
        list-style-type: none;
        padding-left: 0px;
    }

    .add_field,.remove_field{
        background-color: #d3d3d3;
        width: 20px;
        height: 20px;
        display: inline-block;
        text-align: center;
        color: #0033ff;
        font-size: 19px;
        cursor: pointer;
    }

    .input_holder input{
        display:block;
    }

    .add_field1,.remove_field1{
        background-color: #d3d3d3;
        width: 20px;
        height: 20px;
        display: inline-block;
        text-align: center;
        color: #3E3E3E;
        font-size: 19px;
        cursor: pointer;
    }

    .input_holder1 input{
        display:block;
    }
</style>

<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php
        echo ($ids == "") ? "Add New " : "Edit ";
        echo $pagetitle;
        ?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />
        <input type="hidden" name="last_updated_date" id="last_updated_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
        
        <div class="element">
            <label>Partner Name<span class="red">(required)</span></label>

            <input class="field size1"  name="partner_name" type="text"  id="partner_name" size="35" value="<?php echo isset($InfoData['partner_name']) ? $InfoData['partner_name'] : '' ?>" />
        </div>
        <div class="element">
            <label>Partner Type<span class="red">(required)</span></label>
            <select name="partner_type" id="partner_type" >
            <option value="">---Select Partner Type--- </option>
                <option value="School" <?php if ($InfoData['partner_type'] == 'School') { echo"selected";}?>>School </option>
                <option value="Park and Rec" <?php if ($InfoData['partner_type'] == 'Park and Rec') { echo"selected";}?>>Park and Rec </option>
                <option value="Third Party" <?php if ($InfoData['partner_type'] == 'Third Party') { echo"selected";}?>>Third Party </option>
                <option value="Others" <?php if ($InfoData['partner_type'] == 'Others') { echo"selected";}?>>Others </option>
               
                </select>     
        </div>  
      
        <div class="element">
            <label> Street Address <span class="red">(required)</span></label>
            <input name="address" id="address"  size="35" type="text" value="<?php echo isset($InfoData['address']) ? $InfoData['address'] : '' ?>"/>
            <div class="clear"></div>
        </div>
        
        

        <div class="element">
            <label> City<span class="red">(required)</span></label>
            <input class="field size1"  name="city" type="text"  id="city" size="35" value="<?php echo isset($InfoData['city']) ? $InfoData['city'] : '' ?>" />
        </div>
        <div class="element">
            <label>State<span class="red">(required)</span></label>
            <input class="field size1"  name="state" type="text"  id="state" size="35" value="<?php echo isset($InfoData['state']) ? $InfoData['state'] : '' ?>" />
        </div>
        <div class="element">
            <label>Zip Code<span class="red">(required)</span></label>
            <input class="field size1"  name="zip" type="text"  id="zip" size="35" value="<?php echo isset($InfoData['zip']) ? $InfoData['zip'] : '' ?>" />
        </div>
        <div class="element">
            <label>Country<span class="red">(required)</span></label>
            <input class="field size1"  name="country" type="text"  id="country" size="35" value="<?php echo isset($InfoData['country']) ? $InfoData['country'] : '' ?>" />
        </div>



        <div class="element">          
            <div id="map_canvas" style="width:658px; height:350px; margin-left:0px;"></div>
            <div class="clear"></div>
        </div>

		 <div class="element">
            <label>Phone No for Registration Questions<span class="red">(required)</span></label>

            <input class="field size1"  name="registration_phone" type="text"  id="registration_phone" size="35" value="<?php if($InfoData['registration_phone']!="" && $InfoData['registration_phone']!='0'){echo $InfoData['registration_phone'];} ?>" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>Fax Number<span class="red">(required)</span></label>

            <input class="field size1"  name="fax" type="text"  id="fax" size="35" value="<?php if($InfoData['fax']!="" && $InfoData['fax']!='0'){echo $InfoData['fax'];} ?>" maxlength="50"/>
        </div>
        
         <div class="element">
            <label>	First Name<span class="red">(required)</span></label>

            <input class="field size1"  name="primary_first_name" type="text"  id="primary_first_name" size="35" value="<?php echo isset($InfoData['primary_first_name']) ? $InfoData['primary_first_name'] : '' ?>" maxlength="50"/>
        </div>
         <div class="element">
            <label>Last Name<span class="red">(required)</span></label>

            <input class="field size1"  name="primary_last_name" type="text"  id="primary_last_name" size="35" value="<?php echo isset($InfoData['primary_last_name']) ? $InfoData['primary_last_name'] : '' ?>" maxlength="50"/>
        </div>
        
         <div class="element">
            <label>Title<span class="red">(required)</span></label>

            <input class="field size1"  name="primary_title" type="text"  id="primary_title" size="35" value="<?php echo isset($InfoData['primary_title']) ? $InfoData['primary_title'] : '' ?>" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>	Phone<span class="red">(required)</span></label>

            <input class="field size1"  name="primary_phone" type="text"  id="primary_phone" size="35" value="<?php echo isset($InfoData['primary_phone']) ? $InfoData['primary_phone'] : '' ?>" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>Email<span class="red">(required)</span></label>

            <input class="field size1"  name="primary_email" type="text"  id="primary_email" size="35" value="<?php echo isset($InfoData['primary_email']) ? $InfoData['primary_email'] : '' ?>" maxlength="50"/>
        </div>
          <div class="element">
            <label>First Name(Secondary)</label>

            <input class="field size1"  name="secondary_first_name" type="text"  id="secondary_first_name" size="35" value="<?php echo isset($InfoData['secondary_first_name']) ? $InfoData['secondary_first_name'] : '' ?>" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>Last Name(Secondary)</label>

            <input class="field size1"  name="secondary_last_name" type="text"  id="secondary_last_name" size="35" value="<?php echo isset($InfoData['secondary_last_name']) ? $InfoData['secondary_last_name'] : '' ?>" maxlength="50"/>
        </div>
         <div class="element">
            <label>Title (Secondary)</label>

            <input class="field size1"  name="secondary_title" type="text"  id="secondary_title" size="35" value="<?php echo isset($InfoData['secondary_title']) ? $InfoData['secondary_title'] : '' ?>" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>Assigned Company Email<span class="red">(required)</span></label>

            <input class="field size1"  name="company_email" type="text"  id="company_email" size="35" value="<?php echo isset($InfoData['company_email']) ? $InfoData['company_email'] : '' ?>" maxlength="50"/>
        </div>
        
          <div class="element">
            <label>	Date Contract Signed<span class="red">(required)</span></label>
            <input class="field size1 tcal" autocomplete="off" name="contract_date" type="text"  id="contract_date" size="35" value="<?php echo isset($InfoData['contract_date']) ? date('m/d/Y', strtotime($InfoData['contract_date'])) : '' ?>" />
        </div>
        <div class="element">
            <label>URL for External Registration<span class="red">(required)</span></label>

            <input class="field size1"  name="registration_url" type="text"  id="registration_url" size="35" value="<?php echo isset($InfoData['registration_url']) ? $InfoData['registration_url'] : '' ?>" maxlength="50"/>
        </div>
        
             <div class="element registration_type">
             
            <label>Registration Type<span class="red">(required)</span></label>

            <span class="nme"><input type="radio"  id="registration_type" name="registration_type"  size="35" value="internal"  <?php if ($InfoData['registration_type'] == 'internal' || empty($InfoData['id'])) { ?>checked="checked"<?php } ?> ><label class="r_nme">Internal</label> </span>      
            <div style="clear:both;"></div>
            <span class="nme"> <input type="radio"  id="registration_type" name="registration_type"  size="35" value="external" <?php if ($InfoData['registration_type'] == 'external') { ?>checked="checked"<?php } ?>><label class="r_nme">External</label></span> 
            <div style="clear:both;"></div>        
         </div> 
         
         <div class="element fingerprinting">
            <label>Fingerprinting Required<span class="red">(required)</span></label>

            <span class="nme"><input type="radio"  id="fingerprinting_required" name="fingerprinting_required"  size="35" value="yes"  <?php if ($InfoData['fingerprinting_required'] == 'yes' || empty($InfoData['id'])) { ?>checked="checked"<?php } ?> ><label class="r_nme">Yes</label> </span>      
            <div style="clear:both;"></div>
            <span class="nme"> <input type="radio"  id="fingerprinting_required" name="fingerprinting_required"  size="35" value="no" <?php if ($InfoData['fingerprinting_required'] == 'no') { ?>checked="checked"<?php } ?>><label class="r_nme">No</label></span> 
            <div style="clear:both;"></div>        
         </div>  
        <div class="element">
            <label>Invoice Method<span class="red">(required)</span></label>

            <input class="field size1"  name="invoice_method" type="text"  id="invoice_method" size="35" value="<?php echo isset($InfoData['invoice_method']) ? $InfoData['invoice_method'] : '' ?>" />
        </div>
       
		 <div class="element">
            <label>Payment Percentage<span class="red">(required)</span></label>

            <input class="field size1"  name="payment_percentage" type="text"  id="payment_percentage" size="35" value="<?php if($InfoData['payment_percentage']!="" && $InfoData['payment_percentage']!='0'){echo $InfoData['payment_percentage'];} ?>" />
        </div> 
        
        <div class="element">
            <label>Notes<span class="red">(required)</span></label>

            <textarea class="field size1"  name="notes" type="text"  id="notes" size="35" ><?php echo isset($InfoData['notes']) ? $InfoData['notes'] : '' ?>
            </textarea>
        </div>    
        <?php if($ids!=""){?>
        <div class="element">
            <label>Last Updated Date<span class="red">(required)</span></label>
            <input class="field size1" autocomplete="off" name="last_updated_date" type="text"  id="last_updated_date" disabled="disabled" size="35" value="<?php echo isset($InfoData['last_updated_date']) ? date('m/d/Y', strtotime($InfoData['last_updated_date'])) : '' ?>" />
        </div>    
        <?php }?>
        <!-- Form Buttons -->
        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>

        