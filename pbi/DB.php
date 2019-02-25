<?php
/**
* DB Class used to establish connection
* plus having lots of logic in methods
*
* @Author: Mateusz Adamusiński
*
*/
class DB{
	
	public $dbh;
	public function __construct() {

		try{
			
			$config = parse_ini_file('../config.ini');
			$db_username = $config['username'];
			$db_password = $config['password'];
			$host = $config['host'];
			$port = $config['port'];
			$service = $config['service'];
			
			
			$dbtns = "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = ".$host.")
			(PORT = ".$port."))) (CONNECT_DATA = (SERVICE_NAME = ".$service.")))";

		
					$this->dbh = new PDO("oci:dbname=" . $dbtns . ";charset=AL32UTF8", $db_username, $db_password, array(
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_EMULATE_PREPARES => false,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
					
		} catch (PDOException $e) {
			echo $e->getMessage();
			die();
		}
	}
		public function getDB(){
			if ($this->dbh instanceof PDO){
				return $this->dbh;
			}
		}

	
    
		public function parsePracNazw($nazw){
		//$nazw = preg_replace("/([^A-ZŁŻ]+[^a-ząęóżźćńłś]{2,})(\+-[^A-ZŁŻ][^a-ząęóżźćńłś]{2,})?$/", "", $nazw);
		//$nazw = ucfirst(strtolower($nazw));
		return $nazw;
	}
	
	public function parsePracImie($imie){
		//$imie = preg_replace("/[^A-Za-z]/", "", $imie);
		//$imie= ucfirst(strtolower($imie));
		return $imie;
	}
	
	public function pokazPracownika($id){
		$sql = "SELECT * FROM PRAC WHERE id = ?";
		$sth = $this->getDB()->prepare($sql);
		$sth->bindParam(1, $id);
		$sth->execute();
		$result = $sth->fetch();
		return $result;
		
	}
	/*
	public function pokazPracownikow(){
		$sql = "SELECT * FROM PRAC";
		$sth = $this->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll();
		print_r($result);
		return $result;
		
	}*/
	
	public function dodajPracownika($nazw, $imie){
		$pracownikIstnieje = $this->sprawdzCzyIstniejePracownik($nazw, $imie);
		if($pracownikIstnieje > 0) {
			echo "Taki użytkownik istnieje nie dodam tego!";
		}
		else {
			try{
				$this->dbh->beginTransaction();
				$sql = "INSERT INTO PRAC (IMIE, NAZW) VALUES (?, ?)";
				$sth = $this->dbh->prepare($sql);
				$sth->bindParam(1, $imie);
				$sth->bindParam(2, $nazw);
				$sth->execute();
				$this->dbh->commit();
				echo "<BR> Użytkownik dodany";
			}catch(PDOException $e){
				$this->dbh->rollback();
				echo "<BR> Błąd: " . $e . ". Użytkownik nie został dodany, wykonuję rollback.";
			}
		}
	}
	
	public function sprawdzCzyIstniejePracownik($nazw, $imie){
		$sql = "SELECT * FROM PRAC WHERE NAZW = ? and IMIE = ?";
		$sth = $this->dbh->prepare($sql);
		$sth->bindParam(1, $nazw);
		$sth->bindParam(2, $imie);
		try{
			$sth->execute();
		}
		catch(PDOException $e){
			echo "<BR>" . "Caught exception: " . $e->getMessage() . "<BR>";
		}
		$result = $sth->fetch();
		return $result;
	}
	
	public function pokazPracID(){
		$sqlselect1 = "SELECT last_number from all_sequences where sequence_name = 'PRAC_SEQ'";
		$sqlselect2 = "SELECT prac_seq.nextval FROM DUAL";
			try{
					//$query2 = $this->prepare($sqlselect2);
					//echo "prepare(NEXTVAL) wykonana!<BR>";
					//echo $query2->execute() . "<BR> Wykonałem execute(NEXTVAL)<BR>";
					$query1 = $this->prepare($sqlselect1);
					echo "prepare(CURRVAL) wykonana!<BR>";
					echo $query1->execute() . "<BR> Wykonałem execute(CURRVAL)<BR>";
			}catch(PDOException $e){
				echo $e->getMessage();
			}
	}
	
	public function populateCel() {
		$sql = "SELECT * FROM CEL";
		try{
		$sth = $this->dbh->prepare($sql);
		
			$sth->execute();
		$result = array();
		while($row = $sth->fetch()){
			$result[] = $row;
		}
		print_r($result);
		return $result;
		}
		catch(PDOException $e){
			echo "<BR>" . "Caught exception: " . $e->getMessage() . "<BR>";
		}
		
	}
	// KONIEC
	/*	
	$sql3 = "SELECT * FROM v_users WHERE username = :username";  


		try{
			$query3 = $this->pdo->prepare($sql3);
			$query3->execute(array(
				':username' => $_POST['username']
			));
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		
				while ($row = $query3->fetch(PDO::FETCH_ASSOC)) {
					$iduser = $row['id_user'];
					$salt = $row['salt'];
					$pass = $row['password'];
					$access = $row['id_role'];
					$usernameCompare = $row['username'];
					$firstname = $row['firstname'];
					$lastname = $row['lastname'];
					$email = $row['email'];
				}*/


}

?>

