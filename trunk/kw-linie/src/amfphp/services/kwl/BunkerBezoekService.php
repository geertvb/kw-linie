<?php

include_once '../../../mysqliUtils.php';

class BunkerBezoekService {

	function findAll() {
    	$sql = "SELECT";
    	$sql .= "  * ";
    	$sql .= "FROM";
    	$sql .= "  `kwl_bunkerbezoek`";
    	$sql .= "ORDER BY";
    	$sql .= "  `bunkerbezoek_id` ASC";
    	return findSQL($sql);
    }
	
	function findByID($id) {
		
		if ($mysqli = newMysqli()) {
			
			$sql = "select * from `kwl_bunkerbezoek` where `bunkerbezoek_id` = ?";
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				if ($stmt->execute()) {
					$result = getSingleResult($stmt);
				}
				$stmt->close();
			}
			
			$sql = "select `contact_id` from `kwl_bunkerbezoek_contact` where `bunkerbezoek_id` = ?";
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				if ($stmt->execute()) {
					$result->bezoeker_ids = getValues($stmt);
				}
				$stmt->close();
			}
						
			$mysqli->close();
		}
		
		return $result;		
	}
	
	function arrayToList($a) {
		return "(" . implode(", ", $a) . ")";
	}
	
	function updateBezoekers($bunkerbezoek_id, $bezoeker_ids) {
    	$mysqli = newMysqli();
    	
    	$sql = "";
    	$sql .= " SELECT";
    	$sql .= "   `contact_id`";
    	$sql .= " FROM";
    	$sql .= "   `kwl_bunkerbezoek_contact`";
    	$sql .= " WHERE";
    	$sql .= "   `bunkerbezoek_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $bunkerbezoek_id);
			if ($stmt->execute()) {
				$old_ids = getValues($stmt);
			}
			$stmt->close();
		}
		
    	// Remove bezoeker ids
		$remove_ids = array_diff($old_ids, $bezoeker_ids);
		if (count($remove_ids) > 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_bunkerbezoek_contact`";
	    	$sql .= " WHERE";
	    	$sql .= "   `bunkerbezoek_id` = " . $bunkerbezoek_id . " AND ";
	    	$sql .= "   `contact_id` in " . $this->arrayToList($remove_ids);
			$mysqli->query($sql);
    	}
		
		// Add bezoeker ids
		$add_ids = array_diff($bezoeker_ids, $old_ids);
    	if (count($add_ids) > 0) {
			$sql = "";
			$sql .= " INSERT INTO `kwl_bunkerbezoek_contact`";
			$sql .= "   (`bunkerbezoek_id`, `contact_id`)";
			$sql .= " VALUES";
			$values = array();
			foreach ($add_ids as $add_id) {
				$values[] = "(" . $bunkerbezoek_id . ", " . $add_id . ")";
			}
			$sql .= " " . implode(", ", $values);
			$mysqli->query($sql);
		}
					
		$mysqli->close();
    	return $result;
    }	

	function save($vo) {
		if ($mysqli = newMysqli()) {

			$this->saveBezoek($vo, $mysqli);
			$this->updateBezoekers($vo["bunkerbezoek_id"], $vo["bezoeker_ids"]);
			$this->saveOmgeving($vo, $mysqli);
			$this->saveBuitenToestand($vo, $mysqli);
			
			$mysqli->close();
		}
		return true;
	}

	function saveBuitenToestand($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `toestand_buiten_niet_bekeken` = ?,"; 
		$sql .= "   `toestand_buiten_goed` = ?,"; 
		$sql .= "   `toestand_buiten_betonrot` = ?,"; 
		$sql .= "   `toestand_buiten_beschadiging_gevechten` = ?,"; 
		$sql .= "   `toestand_buiten_beschadiging_latere_datum` = ?,"; 
		$sql .= "   `toestand_buiten_beschadiging_natuurlijk` = ?,"; 
		$sql .= "   `toestand_buiten_andere` = ?,"; 
		$sql .= "   `toestand_buiten_andere_tekst` = ?,"; 
		$sql .= "   `toestand_buiten_toegankelijk` = ?,"; 
		$sql .= "   `toestand_buiten_ontoegankelijk_reden` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiiiiiisssi', 
				$vo["toestand_buiten_niet_bekeken"], 
				$vo["toestand_buiten_goed"], 
				$vo["toestand_buiten_betonrot"], 
				$vo["toestand_buiten_beschadiging_gevechten"], 
				$vo["toestand_buiten_beschadiging_latere_datum"], 
				$vo["toestand_buiten_beschadiging_natuurlijk"], 
				$vo["toestand_buiten_andere"], 
				$vo["toestand_buiten_andere_tekst"], 
				$vo["toestand_buiten_toegankelijk"], 
				$vo["toestand_buiten_ontoegankelijk_reden"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	function saveBezoek($vo, $mysqli) {
		$sql = "update `kwl_bunkerbezoek` ";
		$sql .= "set ";
		$sql .= "`datum` = ?,"; 
		$sql .= "`invuller_id` = ?,"; 
		$sql .= "`aanwezigheid` = ?,"; 
		$sql .= "`reden_niet_aanwezig` = ?,"; 
		$sql .= "`reden_niet_aanwezig_tekst` = ? "; 
		$sql .= "where `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('sisssi', 
				$vo["datum"], 
				$vo["invuller_id"], 
				$vo["aanwezigheid"], 
				$vo["reden_niet_aanwezig"], 
				$vo["reden_niet_aanwezig_tekst"], 
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	function saveOmgeving($vo, $mysqli) {
		$sql = "update `kwl_bunkerbezoek` ";
		$sql .= "set ";

		$sql .= "`omgeving_10m_niet_bekeken` = ?,"; 
		$sql .= "`omgeving_10m_bos` = ?,"; 
		$sql .= "`omgeving_10m_bebouwing` = ?,"; 
		$sql .= "`omgeving_10m_weiland` = ?,"; 
		$sql .= "`omgeving_10m_park` = ?,"; 
		$sql .= "`omgeving_10m_akker` = ?,"; 
		$sql .= "`omgeving_10m_water` = ?,"; 

		$sql .= "`omgeving_100m_niet_bekeken` = ?,"; 
		$sql .= "`omgeving_100m_bos` = ?,"; 
		$sql .= "`omgeving_100m_bebouwing` = ?,"; 
		$sql .= "`omgeving_100m_weiland` = ?,"; 
		$sql .= "`omgeving_100m_park` = ?,"; 
		$sql .= "`omgeving_100m_akker` = ?,"; 
		$sql .= "`omgeving_100m_water` = ?,"; 
		
		$sql .= "`ligging` = ?,"; 
		$sql .= "`expositie` = ?,"; 
		$sql .= "`relief` = ?,"; 
		$sql .= "`afstand_berijdbare_weg_meter` = ?,"; 
		$sql .= "`afstand_berijdbare_weg_niet_bekeken` = ?,"; 
		$sql .= "`recreatieve_ontsluiting` = ?,"; 
		$sql .= "`recreatieve_ontsluiting_andere` = ? "; 
		
		$sql .= "where `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiiiiiiiiiiiiisssiissi', 
				$vo["omgeving_10m_niet_bekeken"], 
				$vo["omgeving_10m_bos"], 
				$vo["omgeving_10m_bebouwing"], 
				$vo["omgeving_10m_weiland"], 
				$vo["omgeving_10m_park"], 
				$vo["omgeving_10m_akker"],
				$vo["omgeving_10m_water"],

				$vo["omgeving_100m_niet_bekeken"], 
				$vo["omgeving_100m_bos"], 
				$vo["omgeving_100m_bebouwing"], 
				$vo["omgeving_100m_weiland"], 
				$vo["omgeving_100m_park"], 
				$vo["omgeving_100m_akker"],
				$vo["omgeving_100m_water"],
				
				$vo["ligging"], 
				$vo["expositie"], 
				$vo["relief"], 
				$vo["afstand_berijdbare_weg_meter"], 
				$vo["afstand_berijdbare_weg_niet_bekeken"], 
				$vo["recreatieve_ontsluiting"],
				$vo["recreatieve_ontsluiting_andere"],
				
				$vo["bunkerbezoek_id"]
				);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}
}