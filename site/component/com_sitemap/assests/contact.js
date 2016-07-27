$(document).ready(function(){
	$('#frmenqiry').validate({
        rules: {
			name: "required",
			emailid:{email:true,required:true},
			feedback:"required",
			phoneno:"required"
	 },
		messages: {
			
			name: "&nbsp;",
			emailid:"&nbsp;",
			feedback:"&nbsp;",
			phoneno:"&nbsp;"
		}
    });

});