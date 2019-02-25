<?php	
/**
* Class User holds all the logic and queries for website pages
*
* Author: Mateusz Adamusiński
*
*/
require_once 'Connection.php';

class User{
	
	public $db;
	
	public function __consructor(){
		$this->db = Connection::getConnection(); 
		var_dump($db);
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
		$sth = $this->db()->prepare($sql);
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
				$this->db->beginTransaction();
				$sql = "INSERT INTO PRAC (IMIE, NAZW) VALUES (?, ?)";
				$sth = $this->db->prepare($sql);
				$sth->bindParam(1, $imie);
				$sth->bindParam(2, $nazw);
				$sth->execute();
				$this->db->commit();
				echo "<BR> Użytkownik dodany";
			}catch(PDOException $e){
				$this->db->rollback();
				echo "<BR> Błąd: " . $e . ". Użytkownik nie został dodany, wykonuję rollback.";
			}
		}
	}
	
	public function sprawdzCzyIstniejePracownik($nazw, $imie){
		$sql = "SELECT * FROM PRAC WHERE NAZW = ? and IMIE = ?";
		$sth = $this->db->prepare($sql);
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
		$sth = $this->db->prepare($sql);
		
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
}	
?>