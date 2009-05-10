<?php

include_once '../../../mysqliUtils.php';

class ContactService {

	function findAll() {
    	$sql = "SELECT";
    	$sql .= "  *";
    	$sql .= "FROM";
    	$sql .= "  `kwl_contact`";
    	$sql .= "ORDER BY";
    	$sql .= "  `contact_id` ASC";
    	return findSQL($sql);
    }

	function save($contactVO) {
		if ($mysqli = newMysqli()) {

			$this->saveContact($contactVO, $mysqli);
			
			$mysqli->close();
		}
		return true;
	}
	
	function create() {
		if ($mysqli = newMysqli()) {
			$mysqli->query("INSERT INTO `kwl_contact` () values ()");
			$result = $mysqli->query("SELECT LAST_INSERT_ID()");
			if ($result) {
				list($contact_id) = $result->fetch_row();
				$result->close();
			}
			$mysqli->close();
		}

		return $contact_id;
	}
	
	function saveContact($contactVO, $mysqli) {
		$sql = "update `kwl_contact` ";
		$sql .= "set ";
		$sql .= "`naam` = ?,"; 
		$sql .= "`voornaam` = ?,"; 
		$sql .= "`straat` = ?,"; 
		$sql .= "`nummer` = ?,"; 
		$sql .= "`postcode` = ?,"; 
		$sql .= "`gemeente` = ?,"; 
		$sql .= "`telefoon` = ?,"; 
		$sql .= "`gsm` = ?,"; 
		$sql .= "`email` = ? "; 
		$sql .= "where `contact_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('sssssssssi', 
				$contactVO["naam"], 
				$contactVO["voornaam"], 
				$contactVO["straat"], 
				$contactVO["nummer"], 
				$contactVO["postcode"], 
				$contactVO["gemeente"], 
				$contactVO["telefoon"], 
				$contactVO["gsm"], 
				$contactVO["email"], 
				$contactVO["contact_id"]);
			$stmt->execute();
			$stmt->close();
		}
	}
    
}

?>