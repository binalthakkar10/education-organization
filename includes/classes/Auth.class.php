<?php
class Auth 
{
	public $dbcon;
	protected $userid;
	protected $username;
	protected $password;
	protected $role;

	
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	
	function setId($id){ $this->$userid=$id; }
	function setUsername($username){ $this->$username=$username; }
	function setPassword($password){ $this->$password=$password; }
	function setRole($role){ $this->$role=$role;}

	function getId(){ return $this->$userid; }
	function getUsername(){ return  $this->$username; }
	function getPassword(){ return  $this->$password; }
	function getRole(){ return $this->$role;}

	
	function authorize($userdata)
	{
		$this->setId($userdata['id']);
		$this->setUsername($userdata['username']);
		$this->setPassword($userdata['password']);
	}
	

	function isAdminLogins()
	{
		if(isset($_SESSION['LoginInfo']['USINFO']))
			return true;
		else
			return false;
	}

	function isUserLogin()
	{
		if(isset($_SESSION['FRONTUSERS']['id']))
			return true;
		else
			return false;
	}
}
?>