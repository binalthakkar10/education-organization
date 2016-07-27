<?php
class Site 
{
	public $dbcon;
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}

	
	function admin_update()
	{
		$datas=$_REQUEST;
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		$this->dbcon->Modify('itf_siteinfo',$datas,$condition);
	}


	function CheckSite($Id)
	{
		$sql="select * from itf_siteinfo where id='".$Id."'";
	 	return $this->dbcon->Query($sql);
	}

    function getCountries()
    {
        $sql="select * from itf_country order by country_name ";
        return $this->dbcon->FetchAllResults($sql);
    }

}
?>