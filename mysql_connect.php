<?
	$server = "expressway";
	$user   = "expressway";
	$pw     = "coffee";

	if ( $_SERVER["OS"] == "Windows_NT" ) {
		$hostname = strtolower($_SERVER["COMPUTERNAME"]);
	} else {
		$hostname = `hostname`;
		$hostnamearray = explode('.', $hostname);
		$hostname = $hostnamearray[0];
	}
	echo "Script is running on: $hostname<br/><br/>";

	$ip     = gethostbyname($server);
	if ( $ip != $server ) {
		echo "<br/>The database server resolves to <b>" . $ip . "</b>.<br/><br/>";
	} else {
		echo "<br/>DNS lookup failed.<br/><br/>";
		exit;
	}

	$link = mysql_connect($ip, $user, $pw);
	if(!$link)
	{
		echo "There was an error connecting to the database: " . mysql_error();
	} else {
		echo "It works.";
	}
?>