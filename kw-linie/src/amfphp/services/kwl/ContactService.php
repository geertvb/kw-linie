<?php

include_once '../../../mysqliUtils.php';

class ContactService {

	function findAll() {
    	$sql = "SELECT";
    	$sql .= "  * ";
    	$sql .= "FROM";
    	$sql .= "  `kwl_contact`";
    	$sql .= "ORDER BY";
    	$sql .= "  `contact_id` ASC";
    	return findSQL($sql);
    }

	function findByID($id) {
    	$sql = "SELECT";
    	$sql .= "  * ";
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
	
	function findByIds($ids) {
		$sql = "";
    	$sql .= " SELECT";
    	$sql .= "   *";
    	$sql .= " FROM";
    	$sql .= "   `kwl_contact`";
    	$sql .= " WHERE";
    	$sql .= "   `contact_id` in (" . implode(", ", $ids) . ")";
    	$sql .= " ORDER BY";
    	$sql .= "   `contact_id` ASC";
    	return findSQL($sql);
	}
	
	function asdf($vo) {
		$conditions = array();
		$fieldvalues = array();
		$fieldtypes = "";
		foreach ($vo as $fieldname => $fieldvalue) {
			if ($fieldvalue) {
				if (is_int($fieldvalue)) {
	                $fieldtype = 'i';
	                $operator = "=";
	            } else if (is_double($fieldvalue)) {
	                $fieldtype = 'd';
	                $operator = "=";
	            } else if (is_string($fieldvalue)) {
	                $fieldtype = 's';
	                $operator = "like";
	                $fieldvalue = "%" . $fieldvalue . "%";
	            }
			
				$conditions[] = "$fieldname $operator ?";
				$fieldvalues[] = $fieldvalue;
				$fieldtypes .= $fieldtype;
			}
		}
		if (count($conditions) > 0) {
			$whereclause = " WHERE " . implode(" AND ", $conditions);
		} else {
			$whereclause = "";
		}
		return array($whereclause, $fieldtypes, $fieldvalues);
	}

	function findByExample($contact) {
		list($where, $types, $values) = $this->asdf($contact);
		$sql = "SELECT";
    	$sql .= " * ";
    	$sql .= "FROM";
    	$sql .= "  `kwl_contact`";
    	$sql .= $where;
		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				call_user_func_array('mysqli_stmt_bind_param', array_merge (array($stmt, $types), $values)); 
				if ($stmt->execute()) {
					$result = getResult($stmt);
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
		$sql .= "`email` = ?,";
		$sql .= "`opmerkingen` = ? ";
		$sql .= "where `contact_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ssssssssssi', 
				$contactVO["naam"], 
				$contactVO["voornaam"], 
				$contactVO["straat"], 
				$contactVO["nummer"], 
				$contactVO["postcode"], 
				$contactVO["gemeente"], 
				$contactVO["telefoon"], 
				$contactVO["gsm"], 
				$contactVO["email"], 
				$contactVO["opmerkingen"], 
				$contactVO["contact_id"]);
			$stmt->execute();
			$stmt->close();
		}
	}
    
}

?>