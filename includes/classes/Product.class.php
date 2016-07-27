<?php
class Product
{

	public $dbcon;
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	//Add Data	
	function admin_add($datas)
	{
            
        
            
         $datas['category_id'] = implode(",", $datas['category_id']);
         
        if(!empty($_FILES['main_image']['name']) || !empty($_FILES['image']['name'][0])){
            $imagenames = $this->upload();
            if(!empty($_FILES['main_image']['name'])){
                $datas['main_image'] = $imagenames['main_image'];
            }
            if(!empty($_FILES['image']['name'][0])){
                $datas['image'] = $imagenames['image'];
            }
        }
		unset($datas['id']);
		return $this->dbcon->Insert('itf_product',$datas);
	}
        
	
	//Delete Data
	function admin_delete($id)
	{
        if(isset($id) and !empty($id))
        {
            $info = $this->CheckProduct($id);
            if(isset($info['main_image'])){
                @unlink(PUBLICFILE."products/".$info['main_image']);
            }
            if(isset($info['image'])){
                $images = explode(",",$info['image']);
                foreach ($images as $image){
                    @unlink(PUBLICFILE."products/".$image);
                }
            }
        }
        $sql="delete from itf_product where id in(".$id.")";
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	//Update Data

	function admin_update($datas)
	{

        if(!empty($_FILES['main_image']['name']) || !empty($_FILES['image']['name'][0])){
            $imagenames = $this->upload($datas['id']);
            if(!empty($_FILES['main_image']['name'])){
                $datas['main_image'] = $imagenames['main_image'];
            }
            if(!empty($_FILES['image']['name'][0])){
                $datas['image'] = $imagenames['image'];
            }
        }
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		$this->dbcon->Modify('itf_product',$datas,$condition);
	}

    function search($search)
    {
        $sql="select P.*, C.catname, PC.catname , MC.catname  from itf_product P
              LEFT JOIN itf_category C ON C.id = P.category_id
              LEFT JOIN itf_category PC ON PC.id = C.parent
              LEFT JOIN itf_category MC ON MC.id = PC.parent
              where P.name like '%".$this->dbcon->EscapeString($search)."%' or P.code like '%".$this->dbcon->EscapeString($search)."%' or P.specification like '%".$this->dbcon->EscapeString($search)."%' or C.catname like '%".$this->dbcon->EscapeString($search)."%' or PC.catname like '%".$this->dbcon->EscapeString($search)."%' or MC.catname like '%".$this->dbcon->EscapeString($search)."%'  ";
        $datas=$this->dbcon->FetchAllResults($sql);
        return $datas;
    }
        
    function upload($id)
    {

        if(isset($id) and !empty($id))
        {
            $info = $this->CheckProduct($id);
            if(!empty($_FILES['main_image']['name'])){
                @unlink(PUBLICFILE."products/".$info['main_image']);
            }
            if(!empty($_FILES['image']['name'][0])){
                $images = explode(",",$info['image']);
                foreach ($images as $image){
                    @unlink(PUBLICFILE."products/".$image);
                }
            }
        }
        if(isset($_FILES['main_image']['name']) and !empty($_FILES['main_image']['name']))
        {
                $fimgname="plucka_".rand();
                $objimage= new ITFImageResize();
                $objimage->load($_FILES['main_image']['tmp_name']);
                $objimage->save(PUBLICFILE."products/".$fimgname);
                $productimagename = $objimage->createnames;

                $datas['main_image'] = $productimagename;
        }
        if(isset($_FILES['image']['name']) and !empty($_FILES['image']['name'][0]))
        {
                $imagename = array();
                foreach($_FILES['image']['name'] as $key=>$files){
                    $fimgname = "plucka_".rand();
                    $objimage = new ITFImageResize();
                    $objimage->load($_FILES['image']['tmp_name'][$key]);
                    $objimage->save(PUBLICFILE."products/".$fimgname);
                    $productimagename = $objimage->createnames;
                    $imagename[] = $productimagename;
                }

                $datas['image'] = implode(",",$imagename);
        }

        return $datas;
    }

    function excelUpload(){
        if(isset($_FILES['file']['name']) and !empty($_FILES['file']['name']))
        {
            @unlink(PUBLICFILE."products/plucka_excel_products.xls");
            $fimgname="plucka_excel_products";
            $objimage= new ITFUpload();
            $objimage->load($_FILES['file']);
            $objimage->save(PUBLICFILE."products/".$fimgname);
            $productimagename = $objimage->createnames;

            return $productimagename;
        }
    }


	function ShowAllProduct()
	{
		$sql="select * from itf_product";
		$datas=$this->dbcon->FetchAllResults($sql);
	 	return $datas;
	}
        
    function getLatestProduct()
	{
		$sql="select * from itf_product where status = 1 order by id desc";
		$datas=$this->dbcon->FetchAllResults($sql);
	 	return $datas;
	}
	
	
	function GetPageData($id)
	{
		$sql="select * from itf_product  where id='".$id."'";
		$datas=$this->dbcon->Query($sql);
	 	return $datas;
	}

	function CheckProduct($UsId)
	{
		$sql="select * from itf_product where id='".$UsId."'";
		return $this->dbcon->Query($sql);
	}

    function CheckProductByName($name)
    {
        $sql="select * from itf_product where name='".$name."'";
        return $this->dbcon->Query($sql);
    }

    function CheckProductFront($id)
    {
        $sql="select * from itf_product where id='".$id."' and status=1 ";
        return $this->dbcon->Query($sql);
    }

	

	function PublishBlock($ids)
	{	
		$infos=$this->CheckProduct($ids);
		if($infos['status']=='1')
			$datas=array('status'=>'0');
		else
			$datas=array('status'=>'1');
		$condition = array('id'=>$ids);
		$this->dbcon->Modify('itf_product',$datas,$condition);
		return ($infos['status']=='1')?"0":"1";
	}


    function getProductByCategory($catid,$sort = 'id')
    {
        if($sort == 'id'){
            $order_by = 'order by P.'.$sort.' desc';
        }else{
            $order_by = 'order by P.'.$sort.' asc';
        }

        $sql="select P.* from itf_product P where P.category_id = '".$catid."' ".$order_by;

        return  $this->dbcon->FetchAllResults($sql);;
    }
    
     function ShowAllProductsSearch($txtsearch)

	{

		$sql="select * from itf_product where  code like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
                
		return $this->dbcon->FetchAllResults($sql);

	}

}
?>