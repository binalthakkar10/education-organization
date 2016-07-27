<?php
class ITFUpload {
 
   public $filename;
   public $image_type;
   public $newfilename;
   public $createnames;
   
 
   function load($filename) 
   {
       	$this->image_type = $this->getExtention($filename);
	  	$this->filename = $filename;
		
		
   }
   
   
   function save($filename, $permissions=null) {
 
		$this->newfilename=$filename.".".$this->image_type;
		if( $permissions != null)  
			chmod($filename,$permissions);
		
		@copy($this->filename["tmp_name"],$this->newfilename);
		$exp =explode('/',$this->newfilename);
		$namess = end($exp);
		$this->createnames = $namess;
		
   }
  
  function GetFilename()
  {
	  return $this->createnames;
  } 
     
   function getExtention($tmp_filenames)
   {
	   $exts = explode('.',$tmp_filenames['name']);
	   return end($exts);
   }
   
   function validate($files,$extention)
   {
       $allowExts = explode("|", $extention);       
       
       foreach($files["name"] as $file){
           
           $ext = explode('.',$file);
           foreach ($allowExts as $allowExt)
           {
              if(end($ext) == $allowExt){
                  
                  return true;
              }
           }            
           
       }
       return false;
     
   }
   
 
}
?>