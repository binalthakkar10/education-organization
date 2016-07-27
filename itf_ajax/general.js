function getSubscriptions()
{
	//alert(BASEURL);
	$.ajax({
		url:BASEURL+"/itf_ajax/payment.php",
		type: "POST",
		data:"itfpg=subscription&"+$("#frmproduct").serialize(),
		beforeSend:function(){$("#productplace").html("<p align='center'><img src='"+BASEURL+"/template/default/images/ajaload.gif'/></p>");},
		success:function(itfmsg)
			{
				//alert(itfmsg);
				$("#productplace").empty();
				$("#productplace").append(itfmsg);
			}
		});
	
	//$("#frmproduct").submit();
	//	return true;
	
}


function HideShowGift(giftids)
{
	
	$(".itfgift").hide();
	$("#"+$(giftids).attr("alt")).fadeIn("slow");
}

$(document).ready(function()
{
	$(".close").click(function(){$(".messagebox").remove();});

});