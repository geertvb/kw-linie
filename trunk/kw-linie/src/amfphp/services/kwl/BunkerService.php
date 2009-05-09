<?php

include_once '../../../mysqliUtils.php';

class BunkerService {
	
	function findTypes() {
		return findSQL("select distinct `type` from `kwl_bunker` where `type` is not null order by `type` asc");
	}
	
	function findCodes() {
		return findSQL("select distinct `code` from `kwl_bunker` where `code` is not null order by `code` asc");
	}
	
	function findGemeentes() {
		$sql = "SELECT distinct `gemeente` FROM `kwl_bunker` WHERE `gemeente` is not null order by `gemeente` asc";
		return findSQL($sql);
	}
	
	function findDocumenten($id) {
		$sql = "SELECT `document_id`, `bunker_id`, `omschrijving`, `filename`, `mimetype`, `size` FROM `kwl_document` WHERE `bunker_id` = ? order by `document_id` asc";

		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				if ($stmt->execute()) {
					$result = getresult($stmt);
				}
				$stmt->close();
			}
			$mysqli->close();
		}
		
		return $result;		
	}
	
	function findFotos($id) {
		$sql = "SELECT `foto_id`, `bunker_id`, `omschrijving`, `filename`, `mimetype`, `width`, `height`, `size` FROM `kwl_foto` WHERE `bunker_id` = ? order by `foto_id` asc";

		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				if ($stmt->execute()) {
					$result = getresult($stmt);
				}
				$stmt->close();
			}
			$mysqli->close();
		}
		
		return $result;		
	}
	
	function deleteDocument($id) {
		$sql = "DELETE FROM `kwl_document` WHERE `document_id` = ?";

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
	
	function findDeelgemeentes() {
		$sql = "SELECT distinct `deelgemeente` FROM `kwl_bunker` WHERE `deelgemeente` is not null order by `deelgemeente` asc";
		return findSQL($sql);
	}

	function findByID($id) {
		$sql = "select * from `kwl_bunker` where `bunker_id` = ?";
		
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

	function save($bunker) {
		if ($mysqli = newMysqli()) {

			//$this->saveLocatie($bunker, $mysqli);
			$result = $this->saveDocumenten($bunker, $mysqli);
			
			$mysqli->close();
		}
		return $result;
	}
	
	function saveLocatie($bunker, $mysqli) {
		$sql = "update `kwl_bunker` ";
		$sql .= "set ";
		$sql .= "`lat` = ?,"; 
		$sql .= "`lng` = ?,"; 
		$sql .= "`gemeente` = ?,"; 
		$sql .= "`deelgemeente` = ?,"; 
		$sql .= "`straat` = ? "; 
		$sql .= "where `bunker_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ddsssi', 
				$bunker["lat"], 
				$bunker["lng"], 
				$bunker["gemeente"], 
				$bunker["deelgemeente"], 
				$bunker["straat"], 
				$bunker["bunker_id"]);
			$stmt->execute();
			$stmt->close();
		}
	}

	function saveDocumenten($bunker, $mysqli) {
		$sql = "update `kwl_bunker` ";
		$sql .= "set ";
		$sql .= "`vh_grondplan` = ?,"; 
		$sql .= "`vh_onteigeningsdossier` = ?,"; 
		$sql .= "`vh_lastenboek` = ?,"; 
		$sql .= "`vh_militair_bunkerdossier` = ?,"; 
		$sql .= "`vh_overgave_aan_domeinen` = ?,"; 
		$sql .= "`vh_verkoop_door_domeinen` = ?,"; 
		$sql .= "`vh_oude_fotos` = ?,"; 
		$sql .= "`vh_andere` = ?,"; 
		$sql .= "`vh_andere_tekst` = ? "; 
		$sql .= "where `bunker_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiiiiiiisi', 
				$bunker["vh_grondplan"], 
				$bunker["vh_onteigeningsdossier"], 
				$bunker["vh_lastenboek"], 
				$bunker["vh_militair_bunkerdossier"], 
				$bunker["vh_overgave_aan_domeinen"], 
				$bunker["vh_verkoop_door_domeinen"], 
				$bunker["vh_oude_fotos"], 
				$bunker["vh_andere"], 
				$bunker["vh_andere_tekst"], 
				$bunker["bunker_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	function findAll() {
    	$sql = "SELECT";
    	$sql .= "  *";
    	$sql .= "FROM";
    	$sql .= "  `kwl_bunker`";
    	$sql .= "ORDER BY";
    	$sql .= "  `bunker_id` ASC";
    	return findSQL($sql);
    }
    
    /*
    function save($bunker) {
    	ob_start();
    	var_dump($bunker);
    	$result = ob_get_contents();
    	ob_end_clean();
    	return $result;
    }
    */

}

?>