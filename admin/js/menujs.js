String.prototype.toHHMMSS = function () {
    sec_numb    = parseInt(this);
    var hours   = Math.floor(sec_numb / 3600);
    var minutes = Math.floor((sec_numb - (hours * 3600)) / 60);
    var seconds = sec_numb - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    //var time    = hours+':'+minutes+':'+seconds;
	var time    = minutes+':'+seconds;
    return time;
}


function  submitfrm(act,frmname)
{
	if(act=='delete'){
		if(!confirm("Do you want to delete ?"))
		return false;
	}
	document.getElementById('actions').value=act;
	document.getElementById(frmname).submit();
}


function  itfsubmitfrm(act,frmname)
{
	if(act=='delete'){
	if(!$('#'+frmname+' input[type="checkbox"]').is(':checked'))
	{
		alert("Please select at least one record");
		return false;
	}
	else if(!confirm("Do you want to delete"))
		return false;
	}
	document.getElementById('itfactions').value=act;
	document.getElementById(frmname).submit();
}


function Delcheck()
{
if(confirm("Do you want to Delete"))
	return true;
else
	return false;
}

function hideshow(divids)
{
	$("#"+divids).toggle();
}

function addMoreRec(sr,de,me)
{
	var itfclone=$("."+sr).clone();
	itfclone.appendTo("#"+de);
	$("#"+me).remove();
	return false;
}




$(document).ready(function()
{				   
	$(".activations").click(
	   function(){ 
	   var p1p=$(this);
	   var fdt= "itfpg="+this.rev+"&flid="+this.rel; 
	   $.ajax({
			  url:"itf_ajax/index.php",
			  data:fdt,
			  type:"POST",
			  beforeSend:function(){ 
			  p1p.find('img').attr('src',"imgs/wait31.gif");
			  },
			  success:function(itfmsg) {  p1p.find('img').attr('src',"imgs/"+itfmsg+".png");
			 
			  }
		});	
	return false;
	
	});
	
	$(".activationsn").click(
	   function(){ 
	   var p1p=$(this);
	   var fdt= "itfpg="+this.rev+"&flid="+this.rel; 
	   $.ajax({
			  url:"itf_ajax/index.php",
			  data:fdt,
			  type:"POST",
			  beforeSend:function(){ 
			  p1p.find('img').attr('src',"imgs/wait31.gif");
			  },
			  success:function(itfmsg) {  p1p.find('img').attr('src',"imgs/"+itfmsg+".png");
			  window.location.reload();
			  }
		});	
	return false;
	});
	
	$("#category").change(
	   function(){ 
	   var p1p=$(this);
	   var fdt= "itfpg=changecategory"+"&flid="+$(this).val(); 
	   $.ajax({
			  url:"itf_ajax/index.php",
			  data:fdt,
			  type:"POST",
			  beforeSend:function(){ 
			  p1p.find('img').attr('src',"imgs/wait31.gif");
			  },
			  success:function(itfmsg) {
				  $("#prdlist").html(itfmsg);
			  }
		});	
	return false;
	});

	$("#selectalls").change(function(){if(this.checked){$(".itflistdatas").attr("checked", "checked");}else{$(".itflistdatas").attr("checked", "");}});
	$(".imageview img").click(function(){$.fancybox({'padding': 0,'href':  $(this).attr("src"),'transitionIn'	: 'elastic','transitionOut'	: 'elastic'}); });
	
	$(".loadmaps").click(function(){window.open('itf_ajax/itfbox.php' ,'_blank', 'height=500,width=600,top=20,left=200');});
	
	$(".close").click(function(){ $(".msg").remove(); });

});