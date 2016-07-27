<?php
class Student 
{
	private $username;
	private $password;
	private $name;
	private $uertype;
	private $UserStatus;
	public $dbcon;

	function __construct()
	{
			global $itfmysql;
			$this->dbcon=$itfmysql;
	}

	function userLogin($uname,$pass)
	{
		$sql="select * from itf_student where email='".$this->dbcon->EscapeString($uname)."' and password='".md5($this->dbcon->EscapeString($pass))."' and status ='1' ";
		if($DD=$this->dbcon->Query($sql)){
		$_SESSION['FRONTUSER'] = $DD;
			return $DD;
			}
		else
		return '0';
	}



	function logout()
	{
		session_unset();
	}

	

	function adminAdd($datas)
	{
		unset($datas['id']);
		$this->dbcon->Insert('itf_student',$datas);
	}

	

	function adminUpdate($datas)
	{
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		$this->dbcon->Modify('itf_student',$datas,$condition);
	}


	function adminDelete($Id)
	{
		$sql="delete from itf_student where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}

	

	function ShowAllStudent()
	{
		$sql="select *  from itf_student";
		return $this->dbcon->FetchAllResults($sql);
	}

	
	function SearchStudent($txtsearch)
	{
		$sql="select * from itf_student where  name like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}

	

	function CheckStudent($UsId)
	{
		$sql="select U.* from itf_student U where U.id='".$UsId."'";
	 	return $this->dbcon->Query($sql);
	}


	//Function for change status	

	function PublishBlock($ids)
	{	
		$infos=$this->CheckStudent($ids);
		if($infos['status']=='1')
			$datas=array('status'=>'0');
		else
			$datas=array('status'=>'1');
		$condition = array('id'=>$ids);
		$this->dbcon->Modify('itf_student',$datas,$condition);
		return ($infos['status']=='1')?"0":"1";
	}


	function forgotPasswordAdmin($tomail)
	{
		$userdetail = $this->CheckEmailId($tomail);
		if(isset($userdetail['id']))
		{
			$newpass = "mag".substr(time(),-4);
			$datas = array('password'=>md5($newpass));
			$condition = array('id'=>$userdetail['id']);
			unset($datas['id']);
			$this->dbcon->Modify('users',$datas,$condition);
                        
                        $objuser=new User(); //User object
			$maildatavalue = $objuser->GetEmail(9);
			
                        $objmail=new ITFMailer();
			$objmail->SetSubject($maildatavalue['mailsubject']);
			$objmail->SetBody($maildatavalue['mailbody'],array('username'=>$userdetail['username'],"password"=>$newpass));
			$objmail->MailSend();
			return true;
		}
		else
			return false;
		
	}


	//WebService
	
	function register($datas)
	{
		unset($datas['id']);
		$datas["password"]=md5($datas["password"]);
		$datas["usertype"]="2";
		$userid=$this->dbcon->Insert('itf_student',$datas);
                
                $objuser=new User(); //User object
		$maildatavalue = $objuser->GetEmail(2);
                
		$objmail=new ITFMailer();
		$objmail->SetSubject($maildatavalue['mailsubject']);
		$objmail->SetBody($maildatavalue['mailbody'],array('name'=>$datas['name'],"emailid"=>$datas['email'],"password"=>"------"));
		$objmail->SetTo($datas['email']);
		$objmail->MailSend();
		return $userid;
	}
	
	
	function CheckEmailId($emailid)
	{
		$sql="select * from itf_student where email='".$emailid."'";
		$datas= $this->dbcon->Query($sql);
		if(isset($datas['email']) and !empty($datas['email']))
			return true;
		else
			return false;
	}
	
	
	function GetInfoByEmailId($emailid)
	{
		$sql="select * from itf_student where email='".$emailid."'";
		$datas= $this->dbcon->Query($sql);
		if(isset($datas['email']) and !empty($datas['email']))
			return $datas;
		else
			return $datas;
	}
	

	
	function UserCheckUsername($username)
	{
		$sql="select * from itf_student where username='".$username."'";
		$datas= $this->dbcon->Query($sql);
		if(isset($datas['username']) and !empty($datas['username']))
			return "1";
		else
			return "0";
	}
	
	
	
	function NormalUserLogin($datainfo)
	{
		 $sql="select id,name,last_name,username,email from  itf_student where  (email='".$this->dbcon->EscapeString($datainfo['username'])."' or username = '".$this->dbcon->EscapeString($datainfo['username'])."')
		        and password='".md5($this->dbcon->EscapeString($datainfo['password']))."' and status ='1'";
		if($DD=$this->dbcon->Query($sql))
		{
                    return $DD;
		}
		else
                    return '0';
	}
	
}
?>