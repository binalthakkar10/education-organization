$(document).ready(function(){
	$('#frmcontact').validate({
        rules: {
			name: "required",
			emailid:{email:true,required:true},
			comments:"required",
			phoneno:"required"
	 },
		messages: {
			
			name: "&nbsp;",
			emailid:"&nbsp;",
			comments:"&nbsp;",
			phoneno:"&nbsp;"
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent() );
		},
		success: function(label) {
			label.html("&nbsp;").addClass("itfok");
		}
    });

});