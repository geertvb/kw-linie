<?php

include_once '../../../mysqliUtils.php';

class VleermuisBezoekService {

	function findAll() {
		$sql[] = "SELECT ";
		$sql[] = "  `kwl_vleermuisbezoek`.*,";
		$sql[] = "  `kwl_bunker`.`nummer` as `bunker_nummer`,";
		$sql[] = "  `kwl_bunker`.`type` as `bunker_type`,";
		$sql[] = "  `kwl_bunker`.`gemeente` as `bunker_gemeente`,";
		$sql[] = "  `kwl_bunker`.`deelgemeente` as `bunker_deelgemeente`,";
		$sql[] = "   CONCAT_WS(' ', `kwl_contact`.`voornaam`,`kwl_contact`.`naam`) as `invuller_naam`";
		$sql[] = "FROM ";
		$sql[] = "  `kwl_vleermuisbezoek` ";
		$sql[] = "LEFT JOIN";
		$sql[] = "  `kwl_bunker` ON (`kwl_bunker`.`bunker_id`=`kwl_vleermuisbezoek`.`bunker_id`)";
		$sql[] = "LEFT JOIN";
		$sql[] = "  `kwl_contact` ON (`kwl_contact`.`contact_id`=`kwl_vleermuisbezoek`.`invuller_id`)";
		$sql[] = "ORDER BY";
		$sql[] = "  `kwl_vleermuisbezoek`.`datum` DESC";
		
    	return findSQL(implode(" ", $sql));
    }

    function remove($id) {
    	$sql = "DELETE FROM `kwl_vleermuisbezoek` WHERE `vleermuisbezoek_id` = ?";
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
    	$sql .= " INSERT INTO `kwl_vleermuisbezoek`";
    	$sql .= " ()";
    	$sql .= " VALUES";
    	$sql .= " ()";
    	
    	if ($mysqli = newMysqli()) {
			$mysqli->query($sql);
			$result = $mysqli->query("SELECT LAST_INSERT_ID()");
			if ($result) {
				list($vleermuisbezoek_id) = $result->fetch_row();
				$result->close();
			}
			$mysqli->close();
    	}
    	
    	return $vleermuisbezoek_id;
    }

	function findByID($id) {
		
		if ($mysqli = newMysqli()) {
			
			$sql = "select * from `kwl_vleermuisbezoek` where `vleermuisbezoek_id` = ?";
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				if ($stmt->execute()) {
					$result = getSingleResult($stmt);
				}
				$stmt->close();
			}
			
			$result->bunker = $this->findBunker($mysqli, $result->bunker_id);
			$result->invuller = $this->findContact($mysqli, $result->invuller_id);
			$result->tellers = $this->findTellers($mysqli, $id);
			$result->aantallen = $this->findAantallen($mysqli, $id);
			
			$mysqli->close();
		}
		
		return $result;		
	}

    private function findBunker($mysqli, $id) {
		$sql = "select * from `kwl_bunker` where `bunker_id` = ?";
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
	
	private function findTellers($mysqli, $id) {
		$sql = "";
		$sql .= " SELECT";
		$sql .= "   `kwl_contact`.*";
		$sql .= " FROM";
		$sql .= "   `kwl_contact`,";
		$sql .= "   `kwl_vleermuisbezoek_teller`";
		$sql .= " WHERE";
		$sql .= "   `kwl_contact`.`contact_id` = `kwl_vleermuisbezoek_teller`.`contact_id` AND";
		$sql .= "   `kwl_vleermuisbezoek_teller`.`vleermuisbezoek_id` = ?";
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
	
	private function findAantallen($mysqli, $id) {
		$sql = "";
		$sql .= " SELECT";
		$sql .= "   *";
		$sql .= " FROM";
		$sql .= "   `kwl_vleermuisbezoek_aantal`";
		$sql .= " WHERE";
		$sql .= "   `vleermuisbezoek_id` = ?";
		$sql .= " ORDER BY";
		$sql .= "   `vleermuisbezoek_aantal_id` ASC";

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
			$this->updateTellers($vo, $mysqli);
			$this->updateAantallen($vo, $mysqli);
			
			$mysqli->close();
		}
		return $vo;
	}
		
	private function saveBezoek($vo, $mysqli) {
		$sql = "update `kwl_vleermuisbezoek` ";
		$sql .= "set ";
		$sql .= "`datum` = ?,"; 
		$sql .= "`bunker_id` = ?,"; 
		$sql .= "`invuller_id` = ?,"; 
		$sql .= "`opmerkingen` = ? "; 
		$sql .= "where `vleermuisbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('siisi', 
				$vo["datum"], 
				$vo["bunker_id"], 
				$vo["invuller_id"], 
				$vo["opmerkingen"], 
				$vo["vleermuisbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function updateTellers($vo, $mysqli) {
		$vleermuisbezoek_id = $vo["vleermuisbezoek_id"];
		$teller_ids = $vo["teller_ids"];
    	
    	$sql = "";
    	$sql .= " SELECT";
    	$sql .= "   `contact_id`";
    	$sql .= " FROM";
    	$sql .= "   `kwl_vleermuisbezoek_teller`";
    	$sql .= " WHERE";
    	$sql .= "   `vleermuisbezoek_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $anderobjectbezoek_id);
			if ($stmt->execute()) {
				$old_ids = getValues($stmt);
			}
			$stmt->close();
		}
		
    	// Remove bezoeker ids
		$remove_ids = array_diff($old_ids, $teller_ids);
		if (count($remove_ids) > 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_vleermuisbezoek_teller`";
	    	$sql .= " WHERE";
	    	$sql .= "   `vleermuisbezoek_id` = " . $vleermuisbezoek_id . " AND ";
	    	$sql .= "   `contact_id` in " . $this->arrayToList($remove_ids);
			$mysqli->query($sql);
    	}
		
		// Add bezoeker ids
		$add_ids = array_diff($teller_ids, $old_ids);
    	if (count($add_ids) > 0) {
			$sql = "";
			$sql .= " INSERT INTO `kwl_vleermuisbezoek_teller`";
			$sql .= "   (`vleermuisbezoek_id`, `contact_id`)";
			$sql .= " VALUES";
			$values = array();
			foreach ($add_ids as $add_id) {
				$values[] = "(" . $vleermuisbezoek_id . ", " . $add_id . ")";
			}
			$sql .= " " . implode(", ", $values);
			$mysqli->query($sql);
		}
    }	

	private function updateAantallen($vo, $mysqli) {
		$vleermuisbezoek_id = $vo["vleermuisbezoek_id"];
		$aantallen = $vo["aantallen"];
    	
    	// Remove old rows
		$sql = "";
    	$sql .= " DELETE FROM";
    	$sql .= "   `kwl_vleermuisbezoek_aantal`";
    	$sql .= " WHERE";
    	$sql .= "   `vleermuisbezoek_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $vleermuisbezoek_id);
			$stmt->execute();
		}
		
		$sql = "";
		$sql .= " INSERT INTO `kwl_vleermuisbezoek_aantal`";
		$sql .= "   (`vleermuisbezoek_id`, `soort`, `aantal`)";
		$sql .= " VALUES ";
		$values = array();
		foreach ($aantallen as $item) {
			
			$v = "";
			$v .= "(";
			$v .= $mysqli->real_escape_string($vleermuisbezoek_id);
			$v .= ", ";
			$v .= "'" . $mysqli->real_escape_string($item["soort"]) . "'";
			$v .= ", ";
			$v .= $mysqli->real_escape_string($item["aantal"]);
			$v .= ")";
			$values[] = $v;
		}
		$sql .= implode(", ", $values);
		$mysqli->query($sql);
		
		return $sql;
    }	

}