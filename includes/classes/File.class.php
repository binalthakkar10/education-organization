<?php
class File 
{
	public $dbcon;
	function __construct()
	{
		
	}

	
	function FileDelete($filedirectory){
		if(is_file($filedirectory)){
			return @unlink($filedirectory);
		}
		elseif(is_dir($filedirectory)){
			$scanfile = glob(rtrim($filedirectory,'/').'/*');
			foreach($scanfile as $keys=>$filepath){
				self::FileDelete($filepath);
			}
			return @rmdir($filedirectory);
		}
	}
	
	
	function WatermarkImage ($SourceFile, $WaterMarkText, $DestinationFile) 
	{
		list($width, $height) = getimagesize($SourceFile);
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($SourceFile);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
		$black = imagecolorallocate($image_p, 0, 0, 0);
		$font = 'arial.ttf';
		$font_size = 10;
		imagettftext($image_p, $font_size, 0, 10, 20, $black, $font, $WaterMarkText);
		if ($DestinationFile<>'') 
		{
		  imagejpeg ($image_p, $DestinationFile, 100);
		} else 
		{
		  header('Content-Type: image/jpeg');
		  imagejpeg($image_p, null, 100);
		};
		imagedestroy($image);
		imagedestroy($image_p);
	}
	
	function ImageMarge()
	{
		$dest = imagecreatefrompng('1.png');
		$src = imagecreatefromjpeg('2.jpg');
		imagealphablending($dest, false);
		imagesavealpha($dest, true);
		imagecopymerge($dest, $src, 10, 9, 0, 0, 181, 180, 100); 
		header('Content-Type: image/png');
		imagepng($dest);
		imagedestroy($dest);
		imagedestroy($src);
	}

}
?>