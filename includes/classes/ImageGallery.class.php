<?php

class ImageGallery
{	
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
		
	function admin_addBanner($datas)
	{
		if(isset($_FILES['bannerimage']['name']) and !empty($_FILES['bannerimage']['name']))
		{
			$fimgname="ITFBANNER".time();
			$objimage= new ITFImageResize();
			$objimage->load($_FILES['bannerimage']['tmp_name']);
			$objimage->save(PUBLICFILE."banner/".$fimgname);
			$productimagename=$objimage->createnames;
			$datas['imagename']	=	$productimagename;
		}

		unset($datas['id']);
		$this->dbcon->Insert('itf_banner',$datas);
	}
	
	function admin_updateBanner($datas)
	{
		
		if(isset($_FILES['bannerimage']['name']) and !empty($_FILES['bannerimage']['name']))
		{
			$fimgname="ITFBANNER".time();
			$objimage= new ITFImageResize();
			$objimage->load($_FILES['bannerimage']['tmp_name']);
			$objimage->save(PUBLICFILE."banner/".$fimgname);
			$productimagename=$objimage->createnames;
			$datas['imagename']	=	$productimagename;
			$advertiseinfo=$this->CheckBanner($datas['id']);
			@unlink(PUBLICFILE."banner/".$advertiseinfo["imagename"]);
		}
		
		$condition = array('id'=>$datas['id']);
		
		unset($datas['id']);
		$this->dbcon->Modify('itf_banner',$datas,$condition);
	}

	function Banner_deleteAdmin($Id)
	{
		//Delete all selected file
		$alladvertise=$this->GetBannerbyId($Id);
		foreach($alladvertise as $advertisedata)
		@unlink(PUBLICFILE."banner/".$advertisedata["imagename"]);
		
		$sql="delete from itf_banner where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAllBanner()
	{
		$sql="select *  from itf_banner where status='1'";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	
	
	function CheckBanner($UsId)
	{
		$sql="select U.* from itf_banner U where U.id='".$UsId."'";
	 	return $this->dbcon->Query($sql);
	}
			
	
	//Function for change status	
	function PublishBlock($ids)
	{	

		$infos=$this->CheckBanner($ids);
		if($infos['status']=='1')
			$datas=array('status'=>'0');
		else
			$datas=array('status'=>'1');
		
		$condition = array('id'=>$ids);
		$this->dbcon->Modify('itf_banner',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}	
	function GetBannerbyId($Id)
	{
		$sql="select *  from itf_banner where id in(".$Id.")";
		return $this->dbcon->FetchAllResults($sql);
	}

	//front end============================================================
	function getAllBannerFront()
	{
		$sql="select *  from itf_banner where status='1'";
		return $this->dbcon->FetchAllResults($sql);
	}

}
?>