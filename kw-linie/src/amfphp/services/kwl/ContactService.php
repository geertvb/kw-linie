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

	function findByID($id) {
    	$sql = "SELECT";
    	$sql .= "  *";
    	$sql .= "FROM";
    	$sql .= "  `kwl_contact`";
    	$sql .= "WHERE";
    	$sql .= "  `contact_id` = ?";
		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				if ($stmt->execute()) {
					$result = getSingleResult($stmt);
				}
				$stmt->close();
			}
			$mysqli->close();
		}
		
		return $result;		
	}

    function save($contactVO) {
		if ($mysqli = newMysqli()) {

			$this->saveContact($contactVO, $mysqli);
			
			$mysqli->close();
		}
		return true;
	}
	
	function create() {
		$sql = "INSERT INTO `kwl_contact` (";
		$sql .= "`naam`,`voornaam`,`straat`,`nummer`,`postcode`,`gemeente`";
		$sql .= ") values (";
		$sql .= "'?', '?', '?', '?', '?', '?'";
		$sql .= ")";
		if ($mysqli = newMysqli()) {
			$mysqli->query($sql);
			$result = $mysqli->query("SELECT LAST_INSERT_ID()");
			if ($result) {
				list($contact_id) = $result->fetch_row();
				$result->close();
			}
			$mysqli->close();
		}

		return $contact_id;
	}

	function deleteContact($id) {
		$sql = "DELETE FROM `kwl_contact` WHERE `contact_id` = ?";

		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				$result = $stmt->execute();
				$stmt->close();
			}
			$mysqli->close();
		}
		
		return $result;
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