<?php
class Template 
{
	public $dbcon;
	public static $templatename;
	
	function __construct($templatename)
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	
	public static function setTemplate($templatename)
	{
		self::$templatename=$templatename;
	}

	public static function getTemplate()
	{
		return self::$templatename;
	}

}
?>