<?php
if(isset($_SESSION["FRONTUSER"]["current"]))
{

	if($_SESSION["FRONTUSER"]["current"]=="2")
	{
		$_SESSION["FRONTUSER"]["current"]="3";
		$_SESSION["FRONTUSER"]["usertype"]="3";
		flashMsg("You are transfer to Supplier Account.");
	}
	else
	{
		$_SESSION["FRONTUSER"]["current"]="2";
		$_SESSION["FRONTUSER"]["usertype"]="2";
		flashMsg("You are transfer to Customer Account.");
	}
	
	redirectUrl(CreateLink(array("dashboard")));
}
?>
