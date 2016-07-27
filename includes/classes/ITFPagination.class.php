<?php
class ITFPagination
{
	public $urlpath='';
	public $currentpages='1';
	public $pagginstart='0';
	public $perpage='50';
	public $totalrecords='0';
	public $ItftotalData=array();

	function __construct($urlpath='',$perpage="50")
	{
		$this->urlpath=$urlpath;
		$this->perpage=$perpage;
	}
	
	function setPaginateData($alldata=array())
	{
		$this->totalrecords=count($alldata);
		$this->ItftotalData=$alldata;
		$this->StartPage();
		return $this->GetDataInformation();
	}
	
	function StartPage()
	{
		$itfpage = (int) (!isset($_GET["pages"]) ? 1 : $_GET["pages"]);
		$itfpage = ($itfpage == 0 ? 1 : $itfpage);
		$this->pagginstart=$itfpage;
		$this->currentpages = ($itfpage * $this->perpage) - $this->perpage;
	}
	
	function GetDataInformation()
	{
		$tmpdata=array();
		$lastdatarow=(($this->currentpages+$this->perpage)<=$this->totalrecords)?($this->currentpages+$this->perpage):$this->totalrecords;
		$startdatarow=$this->currentpages;
		for(;$startdatarow<$lastdatarow; $startdatarow++) $tmpdata[]=$this->ItftotalData[$startdatarow];

		return $tmpdata;
	}

	function Pages()
	{
		$path=$this->urlpath;
		$limit=$this->perpage;
		$total_pages = $this->totalrecords;
		$adjacents = "3";
		$page = $this->pagginstart;
		if($page) $start = ($page - 1) * $limit; else $start = 0;
		if ($page == 0) $page = 1;
		$prev = $page - 1;
		$next = $page + 1;
		$lastpage = ceil($total_pages/$limit);
		$lpm1 = $lastpage - 1;
		$pagination = "";
		if($lastpage > 1)
		{   
				$pagination .= "<div class='pagination'>";
			if ($page > 1)
				$pagination.= "&nbsp;<a href='".$path."pages=".$prev."'>Prev</a>";
			else
				$pagination.= "&nbsp;<span class='disabled'>Prev</span>";   
			if ($lastpage < 7 + ($adjacents * 2))
			{   
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
				if ($counter == $page)
					$pagination.= "&nbsp;<span class='current'>".$counter."</span>";
				else
					$pagination.= "&nbsp;<a href='".$path."pages=".$counter."'>".$counter."</a>";                   
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))
			{
				if($page < 1 + ($adjacents * 2))       
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "&nbsp;<span class='current'>".$counter."</span>";
						else
							$pagination.= "&nbsp;<a href='".$path."pages=".$counter."'>".$counter."</a>";                   
					}
					$pagination.= "...";
					$pagination.= "&nbsp;<a href='".$path."pages=".$lpm1."'>".$lpm1."</a>";
					$pagination.= "&nbsp;<a href='".$path."pages=".$lastpage."'>".$lastpage."</a>";       
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "&nbsp;<a href='".$path."pages=1'>1</a>";
					$pagination.= "&nbsp;<a href='".$path."pages=2'>2</a>";
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "&nbsp;<span class='current'>".$counter."</span>";
					else
						$pagination.= "&nbsp;<a href='".$path."pages=".$counter."'>".$counter."</a>";                   
					}
					$pagination.= "..";
					$pagination.= "&nbsp;<a href='".$path."pages=$lpm1'>$lpm1</a>";
					$pagination.= "&nbsp;<a href='".$path."pages=".$lastpage."'>".$lastpage."</a>";       
				}
				else
				{
					$pagination.= "&nbsp;<a href='".$path."pages=1'>1</a>";
					$pagination.= "&nbsp;<a href='".$path."pages=2'>2</a>";
					$pagination.= "..";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "&nbsp;<span class='current'>$counter</span>";
						else
							$pagination.= "&nbsp;<a href='".$path."pages=$counter'>$counter</a>";                   
					}
				}
			}
			
			if ($page < $counter - 1)
				$pagination.= "&nbsp;<a href='".$path."pages=$next'>Next</a>";
			else
				$pagination.= "&nbsp;<span class='disabled'>Next</span>";
				$pagination.= "</div>\n";       
		}
		return $pagination;
	}

}


/*

$pp=new ITFPagination("info.php?",2);
$infroamtions=$pp->setPaginateData($allhostsports);
echo "<pre>";	print_r($infroamtions);
echo $pp->Pages();

*/
?>