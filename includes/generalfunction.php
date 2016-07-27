<?php
function redirectUrl($url)
{
	if(!headers_sent())
	{
		header('Location:'.$url);
		exit();
	}
		echo "<meta http-equiv=\"refresh\" content=\"0;url=".$url."\" />";
		exit();
}

function AdminLogins()
{
	if(isset($_SESSION['LoginInfo']['USINFO']))
		return true;
	else
		return false;
}
function LogedUserLogin()
{
	if(isset($_SESSION['FRONTUSER']['id']))
		return true;
	else
		return false;
}

function CreateLink($parameters=array())
{
	$linksarg='index.php?itfpage=';
	$i=0;
	foreach($parameters as $keys=>$data)
	{
		if($i==0)
		{
		$linksarg.=$data."&"; $i++;
		}
		else
		$linksarg.=$keys."=".$data."&";
	}
	$linksarg=substr($linksarg,0,-1);
	return $linksarg;
}

function CreateLinkAdmin($parameters=array())
{
	$linksarg='itfmain.php?itfpage=';
	$i=0;
	foreach($parameters as $keys=>$data)
	{
		if($i==0)
		{
		$linksarg.=$data."&"; $i++;
		}
		else
		$linksarg.=$keys."=".$data."&";
	}
	$linksarg=substr($linksarg,0,-1);
	return $linksarg;
}


function flash($msg='',$f='1')
{
	if($msg=='')
	{
            if(isset($_SESSION['MSGS']) and $_SESSION['MSGS']['TEXT'] !='')
            {
                if($_SESSION['MSGS']['MTYPE']=='1') {
                   $cssclass="n_ok";
                } elseif ($_SESSION['MSGS']['MTYPE']=='2'){
                    $cssclass="n_error";
                } else {
		  $cssclass="n_warning";
                }

		echo "<div class=\"msg ".$cssclass."\"><p><strong>".$_SESSION['MSGS']['TEXT']."</strong><a href=\"#itf\" class=\"close\">close</a></p></div>";
		unset($_SESSION['MSGS']);
		}else {	echo '';}		
	}
	else
	{
		$_SESSION['MSGS']=array("TEXT"=>$msg,"MTYPE"=>$f);
	}
}



function flashMsg($msg='',$f='1')
{
	if($msg=='')
	{
		if(isset($_SESSION['FRONTMSGS']) and $_SESSION['FRONTMSGS']['TEXT'] !='')
		{
			if($_SESSION['FRONTMSGS']['MTYPE']=='1'){ 
                            $cssclass="n_ok";
                        } elseif ($_SESSION['FRONTMSGS']['MTYPE']=='2'){
                            $cssclass="n_error";
                        } else {
                            $cssclass="n_warning";
                        }
			echo "<div class=\"msgbox ".$cssclass."\"><p><strong>".$_SESSION['FRONTMSGS']['TEXT']."</strong><!--<a href=\"#itf\" class=\"close\">close</a>--></p></div>";
			unset($_SESSION['FRONTMSGS']);
		}else {	echo '';}		
	}
	else
	{
		$_SESSION['FRONTMSGS']=array("TEXT"=>$msg,"MTYPE"=>$f);
	}
}

function GetCurrentUrl()
{
	return $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
}


function isLogins()
{
	if(isset($_SESSION['FRONTUSER']))
		return 1;
	else
		return 0;
}


function itf_modules($modname='login')
{
	ob_start();
	include(BASEPATHS.'/site/module/mod_'.$modname.'/default.php');
	$moduleinfo = ob_get_contents();
	ob_clean();
	return $moduleinfo;
}


function itf_getIPAddress() {
	$ip='';
	if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
	else
		$ip = "UNKNOWN";
	return $ip;
}

function seconds2human($ss) {
    $s = $ss%60;
    $m = floor(($ss%3600)/60);
    $h = floor(($ss%86400)/3600);
    $d = floor(($ss%2592000)/86400);
    $M = floor($ss/2592000);

    //return "$M months, $d days, $h hours, $m minutes, $s seconds";

    return "$d Days $h Hrs $m Min $s Sec";
}

function check_date($date) {
    if(strlen($date) == 10) {
        $pattern = '/\-/i';    // . or / or -
        preg_match($pattern, $date, $char);
        $array = preg_split($pattern, $date, -1, PREG_SPLIT_NO_EMPTY);  
        if(strlen($array[2]) == 4) {
            // dd.mm.yyyy || dd-mm-yyyy
            if($char[0] == "."|| $char[0] == "-") {
                $month = $array[1];
                $day = $array[0];
                $year = $array[2];
            }
            // mm/dd/yyyy    # Common U.S. writing
            if($char[0] == "/") {
                $month = $array[0];
                $day = $array[1];
                $year = $array[2];
            }
        }
        // yyyy-mm-dd    # iso 8601
        if(strlen($array[0]) == 4 && $char[0] == "-") {
            $month = $array[1];
            $day = $array[2];
            $year = $array[0];
        }
        if(checkdate($month, $day, $year)) {    //Validate Gregorian date
            return TRUE;
       
        } else {
            return FALSE;
        }
    }else {

        return FALSE;    // more or less 10 chars

    }
}

function siteURL()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'].'/';
    return $protocol.$domainName.BASE_DIR ;
   //return $protocol.$domainName ;
}

function TemplateUrl($templatename="default")
	{
	       return siteURL()."gurus/template/".Template::getTemplate()."/";
               //return str_replace("http","https",SITEURL."/template/".Template::getTemplate()."/");

	}

	function WordLimit($strtxt,$limits="12")
	{
		$strdata= strip_tags($strtxt);
		$strdatas=explode(" ",$strdata,$limits+1);
		
		if(count($strdatas)>$limits)
		{
			$strdatas1=array_chunk($strdatas,$limits);
			$strdatas=$strdatas1[0];
		}
		$res=implode(" ",$strdatas);		
		return $res; 
		
	}
	
	function CreatePage($pagename='',$convets="O")
	{
		 return ($convets=="O")?str_replace(array(" ","&"),array("_","_an_"),$pagename):str_replace(array("_","_an_"),array(" ","&"),$pagename);
	}

        function currency($price){
        $objsite = new Site();
        $stieinfo = $objsite->CheckSite("1");

        $currency = $stieinfo['currency_prefix'].number_format($price, 2);

        return $currency;
    }

    function seoLink($link){
        $ex = explode(" ",$link);
        $finalLink = implode("",$ex);

        return $finalLink;
    }
    
 
        
    
    
?>