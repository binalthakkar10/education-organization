<?php
//echo "state add";

if (isset($_POST['org_id'])) {
    $userids = $_POST['org_id'];
    if (!empty($_POST['org_id'])) {
        $obj->updateOrganizationData($_POST);
        flash($pagetitle . " is successfully Updated");
    } else {
        $obj->addOrganizationData($_POST);
        flash($pagetitle . " is successfully added");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }

    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}

$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $obj->OrganizationDetail($ids);

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
            	org_name: "required",
            	contact_address: "required",
            	primary_first_name: "required",
            	tax_id:"required",
            	company_email:{required:true, email:true},
            	primary_email:{required:true, email:true},
            contact_city: "required",
            contact_state: "required",
            contact_country: "required",
            contact_zip: "required",
            contact_address: "required",
            primary_last_name: "required",
            paypal_token:"required",
            contract_date:"required",
            contact_zip: {
                    required: true,
                    minlength: 5,
                    maxlength: 6
                },
            primary_phone:{
                	required:true,
                	contact: true,
                	rangelength:[8,15],
                	}, 
           secondary_phone:{
                		contact: {
                    		depends: function() {
                    		return $('#secondary_phone').val() != '';
                    		}
                	}
                	}, 

                	secondary_email:{    	
        				email: {
                			depends: function() {
                			return $('#secondary_email').val()!= '';
                			}
            			}
                    },    	    	
            },
            messages: {
            	org_name: "Please enter organization name",
            	primary_first_name: "Please enter first name",
            	primary_email:" Please Enter a Valid Email",
            	contact_city: "Please enter city",
            	contact_state: "Please enter city",
            	contact_country: "Please enter city",
                contact_address: "Please enter address",
                primary_last_name:"Please enter the last name",
                required:"Please enter tax id",
                paypal_token:"Please enter the paypal token",
                company_email:"Please enter assignrd company email",
                contract_date:"Please enter the date of the contract signed",
                secondary_phone:{
                	contact:"Please enter the correct phone number",
                    },
                primary_phone:{
						required:"Please enter your phone number",
						contact:"Please enter the correct phone number",
                    },
                    
               contact_zip: {
                    required: "Please enter zip",
                    minlength: "min length",
                    maxlength: "Max length",
                }
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
        <input name="org_id" type="hidden" id="org_id" value="<?php echo!empty($InfoData['org_id']) ? $InfoData['org_id'] : ''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />
        <input type="hidden" name="last_updated_date" id="last_updated_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
        <?php if ($ids != "") {?>
        
        <?php } ?>
        <div class="element">
            <label>Organization Name<span class="red">(required)</span></label>

            <input class="field size1"  name="org_name" type="text"  id="code" size="35" value="<?php echo isset($InfoData['org_name']) ? $InfoData['org_name'] : '' ?>" maxlength="50"/>
        </div>
      
        <div class="element">
            <label> Street Address <span class="red">(required)</span></label>
            <input name="contact_address" id="address"  size="35" type="text" value="<?php echo isset($InfoData['contact_address']) ? $InfoData['contact_address'] : '' ?>"/>
            <div class="clear"></div>
        </div>
        
        

        <div class="element">
            <label> City<span class="red">(required)</span></label>
            <input class="field size1"  name="contact_city" type="text"  id="city" size="35" value="<?php echo isset($InfoData['contact_city']) ? $InfoData['contact_city'] : '' ?>" />
        </div>
        <div class="element">
            <label>State<span class="red">(required)</span></label>
            <input class="field size1"  name="contact_state" type="text"  id="state" size="35" value="<?php echo isset($InfoData['contact_state']) ? $InfoData['contact_state'] : '' ?>" />
        </div>
        <div class="element">
            <label>Zip Code<span class="red">(required)</span></label>
            <input class="field size1"  name="contact_zip" type="text"  id="zip" size="35" value="<?php echo isset($InfoData['contact_zip']) ? $InfoData['contact_zip'] : '' ?>" />
        </div>
        <div class="element">
            <label>Country<span class="red">(required)</span></label>
            <input class="field size1"  name="contact_country" type="text"  id="country" size="35" value="<?php echo isset($InfoData['contact_country']) ? $InfoData['contact_country'] : '' ?>" />
        </div>



        <div class="element">          
            <div id="map_canvas" style="width:658px; height:350px; margin-left:0px;"></div>
            <div class="clear"></div>
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
            <label>	Phone<span class="red">(required)</span></label>

            <input class="field size1"  name="primary_phone" type="text"  id="primary_phone" size="35" value="<?php echo isset($InfoData['primary_phone']) ? $InfoData['primary_phone'] : '' ?>" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>Email<span class="red">(required)</span></label>

            <input class="field size1"  name="primary_email" type="text"  id="primary_email" size="35" value="<?php echo isset($InfoData['primary_email']) ? $InfoData['primary_email'] : '' ?>" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>First Name(Secondory)</label>

            <input class="field size1"  name="secondary_first_name" type="text"  id="secondary_first_name" size="35" value="<?php echo isset($InfoData['secondary_first_name']) ? $InfoData['secondary_first_name'] : '' ?>" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>Last Name(Secondory)</label>

            <input class="field size1"  name="secondary_last_name" type="text"  id="secondary_last_name" size="35" value="<?php echo isset($InfoData['secondary_last_name']) ? $InfoData['secondary_last_name'] : '' ?>" maxlength="50"/>
        </div>
        
       
		 <div class="element">
            <label>Phone(Secondory)<span class="red"></span></label>

            <input class="field size1"  name="secondary_phone" type="text"  id="secondary_phone" size="35" value="<?php echo isset($InfoData['secondary_phone']) ? $InfoData['secondary_phone'] : '' ?>" maxlength="50"/>
        </div>
        
         <div class="element">
            <label>Email(Secondory)</label>

            <input class="field size1"  name="secondary_email" type="text"  id="secondary_email" size="35" value="<?php echo isset($InfoData['secondary_email']) ? $InfoData['secondary_email'] : '' ?>" maxlength="50"/>
        </div>
        <div class="element">
            <label>	Date Contract Signed<span class="red">(required)</span></label>
            <input class="field size1 tcal" autocomplete="off" name="contract_date" type="text"  id="contract_date" size="35" value="<?php echo isset($InfoData['contract_date']) ? date('m/d/Y', strtotime($InfoData['contract_date'])) : '' ?>" />
        </div>
         <div class="element">
            <label>Assigned Company Email<span class="red">(required)</span></label>

            <input class="field size1"  name="company_email" type="text"  id="company_email" size="35" value="<?php echo isset($InfoData['company_email']) ? $InfoData['company_email'] : '' ?>" maxlength="50"/>
        </div>
                
        
        <div class="element">
            <label>Federal Tax Id<span class="red">(required)</span></label>

            <input class="field size1"  name="tax_id" type="text"  id="tax_id" size="35" value="<?php echo isset($InfoData['tax_id']) ? $InfoData['tax_id'] : '' ?>" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>Organization Status</label>
            <select name="Status" id="state" >
            <option value="">---Select status--- </option>
                <option value="0" <?php if ($InfoData['Status'] == '0') { echo"selected";}?>>Inactive </option>
                <option value="1" <?php if ($InfoData['Status'] == '1') { echo"selected";}?>>Active </option>
                <option value="2" <?php if ($InfoData['Status'] == '2') { echo"selected";}?>>Prospect </option>
                <option value="3" <?php if ($InfoData['Status'] == '3') { echo"selected";}?>>Terminated </option>
               
                </select>     
        </div>  
        
        <div class="element">
            <label>Paypal Token<span class="red">(required)</span></label>

            <input class="field size1"  name="paypal_token" type="text"  id="paypal_token" size="35" value="<?php echo isset($InfoData['paypal_token']) ? $InfoData['paypal_token'] : '' ?>" maxlength="50"/>
        </div>    
        <div class="element">
            <label>Last Updated Date<span class="red">(required)</span></label>
            <input class="field size1" autocomplete="off" name="updated_date" type="text"  id="updated_date" disabled="disabled" size="35" value="<?php echo isset($InfoData['last_updated_date']) ? date('m/d/Y', strtotime($InfoData['last_updated_date'])) : '' ?>" />
        </div>    
        <!-- Form Buttons -->
        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>
