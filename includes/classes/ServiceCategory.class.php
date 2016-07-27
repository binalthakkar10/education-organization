<?php
class ServiceCategory
{
	
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	
	function Get_Category($id)
	{
		$sql="select * from itf_service_category where id='".$id."'";
		return $this->dbcon->Query($sql);
	}

	function admin_addCategory($datas)
	{
        if(!empty($_FILES['image']['name'])){
            if(isset($datas['id'])){
                $imagenames = $this->upload($datas['id']);
            } else{
                $imagenames = $this->upload();
            }
            $datas['image'] = $imagenames['image'];
        }
		unset($datas['id']);
		return $this->dbcon->Insert('itf_service_category',$datas);

	}


    function upload($id)
    {

        if(isset($id) and !empty($id))
        {
            $info = $this->CheckCategory($id);
            if(!empty($_FILES['image']['name'])){
                @unlink(PUBLICFILE."categories/".$info['image']);
            }

        }
        if(isset($_FILES['image']['name']) and !empty($_FILES['image']['name']))
        {
            $fimgname="plucka_".rand();
            $objimage= new ITFImageResize();
            $objimage->load($_FILES['image']['tmp_name']);
            $objimage->save(PUBLICFILE."categories/".$fimgname);
            $productimagename = $objimage->createnames;

            $datas['image'] = $productimagename;
        }

        return $datas;
    }


	function admin_updateCategory($datas)
	{
        $imagenames = $this->upload($datas['id']);
        if(!empty($_FILES['image']['name'])){
            $datas['image'] = $imagenames['image'];
        }
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		return $this->dbcon->Modify('itf_service_category',$datas,$condition);
	}

	

	function cat_deleteAdmin($id)
	{
        if(isset($id) and !empty($id))
        {
            $info = $this->CheckCategory($id);
            if(!empty($_FILES['image']['name'])){
                @unlink(PUBLICFILE."categories/".$info['image']);
            }

        }
        $sql="delete from itf_service_category where id in(".$id.")";
		$this->dbcon->Query($sql);

		return $this->dbcon->querycounter;
	}
	
	function showAllCategory()
	{
		$sql="select *  from itf_service_category where status='1' order by catname";

		return $this->dbcon->FetchAllResults($sql);
	}
	


	function ShowAllCategorySearch($txtsearch)
	{
		$sql="select * from itf_service_category where  name like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function CheckCategory($UsId)
	{
		$sql="select U.* from itf_service_category U where U.id='".$UsId."'";
	 	return $this->dbcon->Query($sql);
	}

    function CheckCategoryByName($cat_name ,$parent = 0)
    {
        $sql="select C.* from itf_service_category C where C.catname = '".$this->dbcon->EscapeString($cat_name)."' and C.parent = '".$parent."'";
        return $this->dbcon->Query($sql);
    }
			
	
	//Function for change status	
	function PublishBlock($ids)
	{	

		$infos=$this->CheckCategory($ids);
		if($infos['status']=='1')
			$datas=array('status'=>'0');
		else
			$datas=array('status'=>'1');
		
		$condition = array('id'=>$ids);
		$this->dbcon->Modify('itf_service_category',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}

	//front end============================================================

    function getAllCategoryFront($parent=0,$limit=32)
    {
        $sql="select *  from itf_service_category where parent ='".$parent."' and status='1' order by catname limit ".$limit." ";
        $res = $this->dbcon->FetchAllResults($sql);

        if(count($res) > 0){
            foreach($res as &$r)
            {
                $re = $this->getCategories($r['id']);
                $r["subcat"] = $re;

            }
        }
        return $res;
    }

    // Get All categories and subcategories

    function getCategories($parent=0)
    {
        $sql="select *  from itf_service_category where parent ='".$parent."' and status='1' order by catname";
        $res = $this->dbcon->FetchAllResults($sql);

        if(count($res) > 0){
            foreach($res as &$r)
            {
                $re = $this->getCategories($r['id']);
                $r["subcat"] = $re;

            }
        }
            return $res;

    }

    function  getAllCategories()
    {
        $sql="select *  from itf_service_category where status='1' order by id desc limit 32 ";

        return $this->dbcon->FetchAllResults($sql);
    }

    function showCategoriesList($parent=0)
    {
        $categories = $this->getCategories($parent);
        $catlist = array();
        foreach($categories as $key=>$category)
        {
            $catlist[$category["id"]] = $category["catname"];
            if(count($category["subcat"]) > 0){
                foreach($category["subcat"] as $subcat)
                {
                    $catlist[$subcat["id"]] = $category["catname"].">>".$subcat["catname"];
                    if(count($subcat["subcat"]) > 0){
                        foreach($subcat["subcat"] as $subsubcat)
                        {
                            $catlist[$subsubcat["id"]] = $category["catname"].">>".$subcat["catname"].">>".$subsubcat["catname"];
                        }
                    }
                }

            }
        }

        return $catlist;
    }

    function showCategoriesListFront($parent=0)
    {
        $categories = $this->getCategories($parent);
        $catlist = array();
        foreach($categories as $key=>$category)
        {
            $catlist[] = array("id"=>$category["id"],"catname"=>$category["catname"],"status"=>$category["status"]);
            if(count($category["subcat"]) > 0){
                foreach($category["subcat"] as $subcat)
                {
                    $catlist[] = array("id"=>$subcat["id"],"catname"=>$category["catname"].">>".$subcat["catname"],"status"=>$subcat["status"]);
                    if(count($subcat["subcat"]) > 0){
                        foreach($subcat["subcat"] as $subsubcat)
                        {
                            $catlist[] = array("id"=>$subsubcat["id"],"catname"=>$category["catname"].">>".$subcat["catname"].">>".$subsubcat["catname"],"status"=>$subsubcat["status"]);
                        }
                    }
                }

            }
        }

        return $catlist;
    }

    function showCountProduct($id)
    {
        $sql="select *  from itf_product where category_id ='".$id."' and status='1'";
        $res = $this->dbcon->FetchAllResults($sql);

        return count($res);
    }

}
?>