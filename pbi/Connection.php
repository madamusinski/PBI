<?php
/**
* POD Singleton Class
*
* @Autor Mateusz Adamusiński
*
*/
require_once 'IConnection.php';

class Connection implements IConnection{
	
	public static $connection;
	
	protected function __constructor(){
		
	}
	
	public static function getConnection(){
		
		if(empty(self::$connection)){
			
			
			try{
			
				$config = parse_ini_file('../config.ini');
				$db_username = $config['username'];
				$db_password = $config['password'];
				$host = $config['host'];
				$port = $config['port'];
				$service = $config['service'];
			
			
				$dbtns = "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = ".$host.")
				(PORT = ".$port."))) (CONNECT_DATA = (SERVICE_NAME = ".$service.")))";

					self::$connection = new PDO("oci:dbname=" . $dbtns . ";charset=AL32UTF8", $db_username, $db_password, array(
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_EMULATE_PREPARES => false,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
				return self::$connection;
					
		} catch (PDOException $e) {
			echo $e->getMessage();
			die();
		}
		}
		return self::$connection;
	}
	
	
}

?>