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
		$sql = "update `kwl_bunker` ";
		$sql .= "set ";
		$sql .= "`lat` = ?,"; 
		$sql .= "`lng` = ?,"; 
		$sql .= "`gemeente` = ?,"; 
		$sql .= "`deelgemeente` = ?,"; 
		$sql .= "`straat` = ? "; 
		$sql .= "where `bunker_id` = ?";
		
		if ($mysqli = newMysqli()) {
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
			$mysqli->close();
		}
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