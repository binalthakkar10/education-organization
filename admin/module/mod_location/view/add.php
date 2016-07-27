
<?php
/* Version 1.0 - Iniital Version
Version 1.1 - 6/3/16 - Enhancement 12 - Locations with registration enabled/disabled
*/
//echo "state add";

if (isset($_POST['id'])) {
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {
        $obj->admin_updateState($_POST);
        flash($pagetitle . " is successfully Updated");
    } else {
        $obj->admin_addState($_POST);
        flash($pagetitle . " is successfully added");
    }

    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}

$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $obj->LocationDetail($ids);
//$course5 = $obj->showAllCourse();
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
        $("#itffrminput").validate({
            rules: {
                code: "required",
                name: "required",
                type: "required",
                city: "required",
                state: "required",
                country: "required",
                zip: "required",
                address: "required",
                latitude: "required",
                longitude: "required",
                zip: {
                    required: true,
                    minlength: 5,
                    maxlength: 6
                }
            },
            messages: {
                code: "Please enter location code",
                city: "Please enter city",
                state: "Please enter city",
                country: "Please enter city",
                zip: "Please enter zip",
                address: "Please enter address",
                zip: {
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
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />
        <?php if ($InfoData['id'] == '') {
            ?>
            <input type="hidden" name="created_date" id="created_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
        <?php } else { ?>
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
        <?php } ?>
        <div class="element">
            <label>Location Code<span class="red">(required)</span></label>

            <input class="field size1"  name="code" type="text"  id="code" size="35" value="<?php echo isset($InfoData['code']) ? $InfoData['code'] : '' ?>" maxlength="50"/>
        </div>
        <div class="element">
            <label>Location Name<span class="red">(required)</span></label>

            <input class="field size1"  name="name" type="text"  id="name" size="35" value="<?php echo isset($InfoData['name']) ? $InfoData['name'] : '' ?>" maxlength="50"/>
        </div>

        <div class="element">
            <label>Location Type<span class="red">(required)</span></label>
            <select name="type" id="type" >
                <option value="">Select Location Type</option>
                <option value="school" <?php
                if ($InfoData['type'] == 'school') {
                    echo"selected";
                }
                ?>>School</option>
                <option value="thirdParty" <?php
                if ($InfoData['type'] == 'thirdParty') {
                    echo"selected";
                }
                ?>>Third Party</option>
                <option value="city" <?php
                if ($InfoData['type'] == 'city') {
                    echo"selected";
                }
                ?>>City</option>
                <option value="others" <?php
                if ($InfoData['type'] == 'others') {
                    echo"selected";
                }
                ?>>Others</option>

            </select>
        </div>
        <div class="element">
            <label>Address <span class="red">(required)</span></label>
            <input name="address" id="address"  size="35" type="text" value="<?php echo isset($InfoData['address']) ? $InfoData['address'] : '' ?>"/>
            <div class="clear"></div>
        </div>
        
         
          <!-- check box added 26/2016 by yash -->
        <div class="element">  
        <label>Enable Class Registration</label>
  			<input type="checkbox" name="class_registration" class="itflistdatas" value="1" <?php echo ($InfoData['class_registration']==1 ? 'checked' : '');?>>
  		<label>	Enable Summer Camp Registration</label>
			 <input type="checkbox" name="summer_camp_registration" value="1" <?php echo ($InfoData['summer_camp_registration']==1 ? 'checked' : '');?>>
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
            <label>latitude <span class="red">(required)</span> </label>
            <input id="latitude" name="latitude" type="text" class="field size1" size="35"  value="<?php echo isset($InfoData['latitude']) ? $InfoData['latitude'] : '' ?>"/>
            <div class="clear"></div>
        </div>
        <div class="element">
            <label>longitude <span class="red">(required)</span> </label>
            <input id="longitude" name="longitude" type="text" size="35" value="<?php echo isset($InfoData['longitude']) ? $InfoData['longitude'] : '' ?>"/>
            <div class="clear"></div>
        </div>
        <div class="element">
            <label>Note </label>
            <textarea name="note" id="note" cols="50" rows="10"><?php echo isset($InfoData['note']) ? $InfoData['note'] : '' ?></textarea>
           <div class="clear"></div>
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
