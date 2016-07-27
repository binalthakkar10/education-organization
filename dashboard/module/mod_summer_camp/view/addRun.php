<?php

echo "add";

if(isset($_POST['id']))
{
	$userids = $_POST['id'];
	if(!empty($_POST['id']))
	{
		$obj->admin_updateState($_POST);
		flash($pagetitle." is successfully Updated");
	}
	else
	{
		$obj->admin_addState($_POST);
		flash($pagetitle." is successfully added");
	}
	
	$urlname = CreateLinkAdmin(array($currentpagenames,"parentid"=>$parentids));
	redirectUrl($urlname);
}

$ids=isset($_GET['id'])?$_GET['id']:'';
$InfoData = $obj->CheckState($ids);

?>
<?php // Html::AddJavaScript("admin/assests/jquery-ui-1.8.1.custom.min.js","component");?> 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="assests/jquery-ui-1.8.1.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$("#itffrminput").validate({
	rules: {
			course: "required",
                        address:"required",
                        zipcode:{
                            required:true,
                            minlength:6,
                            maxlength:6
                        }
                        
                       
		},
		messages: {
			name: "Please enter <?php echo $pagetitle; ?> course",
                        address: "Please enter <?php echo $pagetitle; ?> address",
                        zipcode:{
                           required: "Please enter <?php echo $pagetitle; ?> zipcode",
                   minlength:"min length",
                   maxlength:"Max length"
                }
			}
	});
});
</script>

 <script type="text/javascript">var geocoder;
var map;
var marker;
    
function initialize(){
//MAP
  var latlng = new google.maps.LatLng(41.659,-4.714);
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
        geocoder.geocode( {'address': request.term }, function(results, status) {
          response($.map(results, function(item) {
            return {
              label:  item.formatted_address,
              value: item.formatted_address,
              latitude: item.geometry.location.lat(),
              longitude: item.geometry.location.lng()
            }
          }));
        })
      },
      //This bit is executed upon selection of an address
      select: function(event, ui) {
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
        if (results[0]) {
          $('#address').val(results[0].formatted_address);
          $('#latitude').val(marker.getPosition().lat());
          $('#longitude').val(marker.getPosition().lng());
        }
      }
    });
  });
  
});</script>    






<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo !empty($InfoData['id'])?$InfoData['id']:''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />

 <div class="element">
            <label>Course<span class="red">(required)</span></label>
            <input class="field size1"  name="course" type="text"  id="course" size="35" value="<?php echo isset($InfoData['course'])?$InfoData['course']:'' ?>" />
        </div>
<!--        <div class="conta">
                <label>Address <span style="color:red;font-size: 15px;">*</span>:</label>
                <input name="address" id="address"  type="text" value="<?php echo isset($ItfInfoData['address'])?$ItfInfoData['address']:'' ?>"/>
                <div class="clear"></div>
               </div>-->
         <div class="element">
            <label> City</label>
            <input class="field size1"  name="city" type="text"  id="city" size="35" value="<?php echo isset($InfoData['city'])?$InfoData['city']:'' ?>" />
        </div>
         <div class="element">
            <label>State</label>
            <input class="field size1"  name="state" type="text"  id="state" size="35" value="<?php echo isset($InfoData['state'])?$InfoData['state']:'' ?>" />
        </div>
         <div class="element">
            <label>Country</label>
            <input class="field size1"  name="country" type="text"  id="country" size="35" value="<?php echo isset($InfoData['country'])?$InfoData['country']:'' ?>" />
        </div>
         <div class="element">
            <label>Zipcode</label>
            <input class="field size1"  name="zipcode" type="text"  id="zipcode" size="35" value="<?php echo isset($InfoData['zipcode'])?$InfoData['zipcode']:'' ?>" />
        </div>
<!--        <div class="element">
            <label>Latitute<span class="red">(required)</span></label>
            <input class="field size1"  name="latitute" type="text"  id="latitute" size="35" value="<?php echo isset($InfoData['latitute'])?$InfoData['latitute']:'' ?>" />
        </div>
        <div class="element">
            <label>Langitude<span class="red">(required)</span></label>
            <input class="field size1"  name="langitude" type="text"  id="langitude" size="35" value="<?php echo isset($InfoData['langitude'])?$InfoData['langitude']:'' ?>" />
        </div>-->
                   </div>
    <div class="conta">
                <label>Address <span style="color:red;font-size: 15px;">*</span>:</label>
                <input name="address" id="address"  type="text" value="<?php echo isset($ItfInfoData['address'])?$ItfInfoData['address']:'' ?>"/>
                <div class="clear"></div>
               </div>
     <div class="conta">          
    <div id="map_canvas" style="width:455px; height:350px; margin-left:175px;"></div>
    <div class="clear"></div>
           </div>
    <div class="conta">
    <label>latitude : </label>
    <input id="latitude" name="latitude" type="text" value="<?php echo isset($ItfInfoData['latitude'])?$ItfInfoData['latitude']:'' ?>"/>
    <div class="clear"></div>
     </div>
    
    <div class="conta">
    <label>longitude : </label>
    <input id="longitude" name="longitude" type="text" value="<?php echo isset($ItfInfoData['longitude'])?$ItfInfoData['longitude']:'' ?>"/>
    <div class="clear"></div>
            </div>
           </div>
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