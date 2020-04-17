<?php
class Database {
	public static $;
	public static $con;
	function Database(){
		$this->user="lprbqrsrkzwaoc";$this->pass="7f24e4d8ed460165c4df87da51f3825f0cd4f6adc286abcf38ae07bb88789676";$this->host="ec2-52-71-85-210.compute-1.amazonaws.com";$this->ddbb="dci536q9bk4pg1
";
	}

	function connect(){
		$con = new mysqli($this->host,$this->user,$this->pass,$this->ddbb);
		$con->query("set sql_mode=''");
		return $con;
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}
	
}
?>
