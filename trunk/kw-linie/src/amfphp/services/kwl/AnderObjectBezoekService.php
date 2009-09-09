<?php

include_once '../../../mysqliUtils.php';

class AnderObjectBezoekService {

	function findAll() {
    	$sql = "SELECT";
    	$sql .= "  * ";
    	$sql .= "FROM";
    	$sql .= "  `kwl_anderobjectbezoek`";
    	$sql .= "ORDER BY";
    	$sql .= "  `anderobjectbezoek_id` ASC";
    	return findSQL($sql);
    }

    function remove($id) {
    	$sql = "DELETE FROM `kwl_anderobjectbezoek` WHERE `anderobjectbezoek_id` = ?";
		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				$stmt->execute();
			}
		}
		return $id;
    }
    
    function create() {
    	$sql = "";
    	$sql .= " INSERT INTO `kwl_anderobjectbezoek`";
    	$sql .= " ()";
    	$sql .= " VALUES";
    	$sql .= " ()";
    	
    	if ($mysqli = newMysqli()) {
			$mysqli->query($sql);
			$result = $mysqli->query("SELECT LAST_INSERT_ID()");
			if ($result) {
				list($anderobjectbezoek_id) = $result->fetch_row();
				$result->close();
			}
			$mysqli->close();
    	}
    	
    	return $anderobjectbezoek_id;
    }

	function findByID($id) {
		
		if ($mysqli = newMysqli()) {
			
			$sql = "select * from `kwl_anderobjectbezoek` where `anderobjectbezoek_id` = ?";
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				if ($stmt->execute()) {
					$result = getSingleResult($stmt);
				}
				$stmt->close();
			}
			
			$result->anderobject = $this->findAnderObject($mysqli, $result->anderobject_id);
			$result->invuller = $this->findContact($mysqli, $result->invuller_id);
			$result->bezoekers = $this->findBezoekers($mysqli, $id);
			
			$mysqli->close();
		}
		
		return $result;		
	}

    private function findAnderObject($mysqli, $id) {
		$sql = "select * from `kwl_anderobject` where `anderobject_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) {
				$result = getSingleResult($stmt);
			}
			$stmt->close();
		}
		
		return $result;	
    }
    

	private function findContact($mysqli, $id) {
    	$sql = "SELECT";
    	$sql .= "  * ";
    	$sql .= "FROM";
    	$sql .= "  `kwl_contact`";
    	$sql .= "WHERE";
    	$sql .= "  `contact_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) {
				$result = getSingleResult($stmt);
			}
			$stmt->close();
		}
		
		return $result;		
	}
	
	private function findBezoekers($mysqli, $id) {
		$sql = "";
		$sql .= " SELECT";
		$sql .= "   `kwl_contact`.*";
		$sql .= " FROM";
		$sql .= "   `kwl_contact`,";
		$sql .= "   `kwl_anderobjectbezoek_contact`";
		$sql .= " WHERE";
		$sql .= "   `kwl_contact`.`contact_id` = `kwl_anderobjectbezoek_contact`.`contact_id` AND";
		$sql .= "   `kwl_anderobjectbezoek_contact`.`anderobjectbezoek_id` = ?";
		$sql .= " ORDER BY";
		$sql .= "   `kwl_contact`.`contact_id` ASC";

		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) {
				$result = getresult($stmt);
			} else {
				throw new Exception($mysqli->error);
			}
			$stmt->close();
		}
		
		return $result;		
	}
	
    function save($vo) {
		if ($mysqli = newMysqli()) {

			$this->saveBezoek($vo, $mysqli);
			$this->updateBezoekers($vo, $mysqli);
			$this->saveToestand($vo, $mysqli);
			
			$mysqli->close();
		}
		return $vo;
	}
		
	private function saveBezoek($vo, $mysqli) {
		$sql = "update `kwl_anderobjectbezoek` ";
		$sql .= "set ";
		$sql .= "`datum` = ?,"; 
		$sql .= "`anderobject_id` = ?,"; 
		$sql .= "`invuller_id` = ?,"; 
		$sql .= "`aanwezigheid` = ?,"; 
		$sql .= "`reden_niet_aanwezig` = ?,"; 
		$sql .= "`reden_niet_aanwezig_tekst` = ? "; 
		$sql .= "where `anderobjectbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('siisssi', 
				$vo["datum"], 
				$vo["anderobject_id"], 
				$vo["invuller_id"], 
				$vo["aanwezigheid"], 
				$vo["reden_niet_aanwezig"], 
				$vo["reden_niet_aanwezig_tekst"], 
				$vo["anderobjectbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function updateBezoekers($vo, $mysqli) {
		$anderobjectbezoek_id = $vo["anderobjectbezoek_id"];
		$bezoeker_ids = $vo["bezoeker_ids"];
    	
    	$sql = "";
    	$sql .= " SELECT";
    	$sql .= "   `contact_id`";
    	$sql .= " FROM";
    	$sql .= "   `kwl_anderobjectbezoek_contact`";
    	$sql .= " WHERE";
    	$sql .= "   `anderobjectbezoek_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $anderobjectbezoek_id);
			if ($stmt->execute()) {
				$old_ids = getValues($stmt);
			}
			$stmt->close();
		}
		
    	// Remove bezoeker ids
		$remove_ids = array_diff($old_ids, $bezoeker_ids);
		if (count($remove_ids) > 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_anderobjectbezoek_contact`";
	    	$sql .= " WHERE";
	    	$sql .= "   `anderobjectbezoek_id` = " . $anderobjectbezoek_id . " AND ";
	    	$sql .= "   `contact_id` in " . $this->arrayToList($remove_ids);
			$mysqli->query($sql);
    	}
		
		// Add bezoeker ids
		$add_ids = array_diff($bezoeker_ids, $old_ids);
    	if (count($add_ids) > 0) {
			$sql = "";
			$sql .= " INSERT INTO `kwl_anderobjectbezoek_contact`";
			$sql .= "   (`anderobjectbezoek_id`, `contact_id`)";
			$sql .= " VALUES";
			$values = array();
			foreach ($add_ids as $add_id) {
				$values[] = "(" . $anderobjectbezoek_id . ", " . $add_id . ")";
			}
			$sql .= " " . implode(", ", $values);
			$mysqli->query($sql);
		}
    }	

	private function saveToestand($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_anderobjectbezoek`";
		$sql .= " set ";
		$sql .= "   `toestand_niet_bekeken` = ?,"; 
		$sql .= "   `toestand` = ?,"; 
		$sql .= "   `toestand_andere` = ?,"; 
		$sql .= "   `bedreigingen_niet_bekeken` = ?,"; 
		$sql .= "   `bedreigingen` = ?,"; 
		$sql .= "   `bedreigingen_andere` = ?,"; 
		$sql .= "   `recreatieve_ontsluiting_niet_bekeken` = ?,"; 
		$sql .= "   `recreatieve_ontsluiting` = ?,"; 
		$sql .= "   `recreatieve_ontsluiting_andere` = ?,"; 
		$sql .= "   `opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `anderobjectbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('issississsi', 
				$vo["toestand_niet_bekeken"], 
				$vo["toestand"], 
				$vo["toestand_andere"], 
				$vo["bedreigingen_niet_bekeken"], 
				$vo["bedreigingen"], 
				$vo["bedreigingen_andere"], 
				$vo["recreatieve_ontsluiting_niet_bekeken"], 
				$vo["recreatieve_ontsluiting"], 
				$vo["recreatieve_ontsluiting_andere"], 
				$vo["opmerkingen"], 
				
				$vo["anderobjectbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		} else {
			throw new Exception($mysqli->error);
		}
		return $sql;
	}

}