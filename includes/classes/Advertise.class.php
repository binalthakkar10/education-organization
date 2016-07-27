<?php
class Advertise 
{
	
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	
	
	function admin_addAdvertise($datas)
	{
		
		
		if(isset($_FILES['bannerimage']['name']) and !empty($_FILES['bannerimage']['name']))
		{
			$fimgname="ITFADVER".time();
			$objimage= new ITFImageResize();
			$objimage->load($_FILES['bannerimage']['tmp_name']);
			$objimage->save(PUBLICFILE."banner/".$fimgname);
			$productimagename=$objimage->createnames;
			$datas['imagename']	=	$productimagename;
		}

		unset($datas['id']);
		$this->dbcon->Insert('itf_banner',$datas);
	}

	



	function admin_updateAdvertise($datas)
	{
		
		if(isset($_FILES['bannerimage']['name']) and !empty($_FILES['bannerimage']['name']))
		{
			$fimgname="ITFADVER".time();
			$objimage= new ITFImageResize();
			$objimage->load($_FILES['bannerimage']['tmp_name']);
			$objimage->save(PUBLICFILE."banner/".$fimgname);
			$productimagename=$objimage->createnames;
			$datas['imagename']	=	$productimagename;
			$advertiseinfo=$this->CheckAdvertise($datas['id']);
			@unlink(PUBLICFILE."banner/".$advertiseinfo["imagename"]);
		}
		
		$condition = array('id'=>$datas['id']);
		
		unset($datas['id']);
		$this->dbcon->Modify('itf_banner',$datas,$condition);
	}

	

	function Advertise_deleteAdmin($Id)
	{
		//Delete all selected file
		$alladvertise=$this->GetAdvertisebyId($Id);
		foreach($alladvertise as $advertisedata)
		@unlink(PUBLICFILE."banner/".$advertisedata["imagename"]);
		
		$sql="delete from itf_banner where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAllAdvertise()
	{
		$sql="select *  from itf_banner where status='1'order by id DESC";
		return $this->dbcon->FetchAllResults($sql);
	}
        
        function showAdvertise()
	{
		$sql="select *  from itf_banner where status='1' order by id DESC";
		return $this->dbcon->Query($sql);
	}
	
	
	
	
	function CheckAdvertise($UsId)
	{
		$sql="select U.* from itf_banner U where U.id='".$UsId."'";
	 	return $this->dbcon->Query($sql);
	}
			
	
	//Function for change status	
	function PublishBlock($ids)
	{	

		$infos=$this->CheckAdvertise($ids);
		if($infos['status']=='1')
			$datas=array('status'=>'0');
		else
			$datas=array('status'=>'1');
		
		$condition = array('id'=>$ids);
		$this->dbcon->Modify('itf_banner',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}
	
	function GetAdvertisebyId($Id)
	{
		$sql="select *  from itf_banner where id in(".$Id.")";
		return $this->dbcon->FetchAllResults($sql);
	}

	//front end============================================================
	function getAllAdvertiseFront($possitionid="1",$adsnumber="5")
	{
		$sql="select *  from itf_advertise where status='1' and placename='".$possitionid."' limit ".$adsnumber;
		return $this->dbcon->FetchAllResults($sql);
	}


}
?>