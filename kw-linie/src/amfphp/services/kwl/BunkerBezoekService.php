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
			
			$result->fotos = $this->findFotos($mysqli, $id);
						
			$mysqli->close();
		}
		
		return $result;		
	}
	
	private function findFotos($mysqli, $id) {
		$sql = "";
		$sql .= " SELECT";
		$sql .= "   `kwl_foto`.`foto_id`,";
		$sql .= "   `kwl_foto`.`omschrijving`,";
		$sql .= "   `kwl_foto`.`filename`,";
		$sql .= "   `kwl_foto`.`mimetype`,";
		$sql .= "   `kwl_foto`.`width`,";
		$sql .= "   `kwl_foto`.`height`,";
		$sql .= "   `kwl_foto`.`size`";
		$sql .= " FROM";
		$sql .= "   `kwl_bunkerbezoek_foto`,";
		$sql .= "   `kwl_foto`";
		$sql .= " WHERE";
		$sql .= "   `kwl_bunkerbezoek_foto`.`bunkerbezoek_id` = ? AND";
		$sql .= "   `kwl_bunkerbezoek_foto`.`foto_id` = `kwl_foto`.`foto_id`";
		$sql .= " ORDER BY";
		$sql .= "   `kwl_foto`.`foto_id` ASC";

		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) {
				$result = getresult($stmt);
			}
			$stmt->close();
		}
		
		return $result;		
	}	
	
	private function arrayToList($a) {
		return "(" . implode(", ", $a) . ")";
	}
	
	private function updateBezoekers($bunkerbezoek_id, $bezoeker_ids) {
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
			$this->saveBinnenToestand($vo, $mysqli);
			$this->saveIngang($vo, $mysqli);
			$this->saveBuitendeur($vo, $mysqli);
			$this->saveBinnendeur($vo, $mysqli);
			$this->saveCamouflage($vo, $mysqli);
			$this->saveDakplaten($vo, $mysqli);
			$this->saveAfsluitluikGranaatwerper($vo, $mysqli);
			$this->saveAfsluitluikPistoolkoker($vo, $mysqli);
			$this->saveRoosterIngang($vo, $mysqli);
			$this->saveNooduitgang($vo, $mysqli);
			$this->saveVerluchtingspijpen($vo, $mysqli);
			
			$mysqli->close();
		}
		return true;
	}

	private function saveCamouflage($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `camouflage_niet_bekeken` = ?,"; 
		$sql .= "   `camouflage_aanwezig` = ?,"; 
		$sql .= "   `camouflage_baksteen` = ?,"; 
		$sql .= "   `camouflage_pannendak` = ?,"; 
		$sql .= "   `camouflage_eitjesbepleistering` = ?,"; 
		$sql .= "   `camouflage_beschildering` = ?,"; 
		$sql .= "   `camouflage_haken_voor_netten_aanwezig` = ?,"; 
		$sql .= "   `camouflage_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiiiiiisi', 
				$vo["camouflage_niet_bekeken"], 
				$vo["camouflage_aanwezig"], 
				$vo["camouflage_baksteen"], 
				$vo["camouflage_pannendak"], 
				$vo["camouflage_eitjesbepleistering"], 
				$vo["camouflage_beschildering"], 
				$vo["camouflage_haken_voor_netten_aanwezig"], 
				$vo["camouflage_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveDakplaten($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `dakplaten_niet_bekeken` = ?,"; 
		$sql .= "   `dakplaten_aanwezig` = ?,"; 
		$sql .= "   `dakplaten_bunkernummer_leesbaar` = ?,"; 
		$sql .= "   `dakplaten_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiisi', 
				$vo["dakplaten_niet_bekeken"], 
				$vo["dakplaten_aanwezig"], 
				$vo["dakplaten_bunkernummer_leesbaar"], 
				$vo["dakplaten_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveAfsluitluikGranaatwerper($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `afsluitluik_granaatwerper_niet_bekeken` = ?,"; 
		$sql .= "   `afsluitluik_granaatwerper_aanwezig` = ?,"; 
		$sql .= "   `afsluitluik_granaatwerper_aantal_totaal` = ?,"; 
		$sql .= "   `afsluitluik_granaatwerper_aantal_met_ketting` = ?,"; 
		$sql .= "   `afsluitluik_granaatwerper_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iisssi', 
				$vo["afsluitluik_granaatwerper_niet_bekeken"], 
				$vo["afsluitluik_granaatwerper_aanwezig"], 
				$vo["afsluitluik_granaatwerper_aantal_totaal"], 
				$vo["afsluitluik_granaatwerper_aantal_met_ketting"], 
				$vo["afsluitluik_granaatwerper_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveAfsluitluikPistoolkoker($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `afsluitluik_pistoolkoker_niet_bekeken` = ?,"; 
		$sql .= "   `afsluitluik_pistoolkoker_aanwezig` = ?,"; 
		$sql .= "   `afsluitluik_pistoolkoker_met_ketting` = ?,"; 
		$sql .= "   `afsluitluik_pistoolkoker_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiisi', 
				$vo["afsluitluik_pistoolkoker_niet_bekeken"], 
				$vo["afsluitluik_pistoolkoker_aanwezig"], 
				$vo["afsluitluik_pistoolkoker_met_ketting"], 
				$vo["afsluitluik_pistoolkoker_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveRoosterIngang($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `rooster_ingang_niet_bekeken` = ?,"; 
		$sql .= "   `rooster_ingang_aanwezig` = ?,"; 
		$sql .= "   `rooster_ingang_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iisi', 
				$vo["rooster_ingang_niet_bekeken"], 
				$vo["rooster_ingang_aanwezig"], 
				$vo["rooster_ingang_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveNooduitgang($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `nooduitgang_niet_bekeken` = ?,"; 
		$sql .= "   `nooduitgang_aanwezig` = ?,"; 
		$sql .= "   `nooduitgang_toestand` = ?,"; 
		$sql .= "   `nooduitgang_schotbalken_binnenkant_aanwezig` = ?,"; 
		$sql .= "   `nooduitgang_schotbalken_buitenkant_aanwezig` = ?,"; 
		$sql .= "   `nooduitgang_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iisiisi', 
				$vo["nooduitgang_niet_bekeken"], 
				$vo["nooduitgang_aanwezig"], 
				$vo["nooduitgang_toestand"], 
				$vo["nooduitgang_schotbalken_binnenkant_aanwezig"], 
				$vo["nooduitgang_schotbalken_buitenkant_aanwezig"], 
				$vo["nooduitgang_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveVerluchtingspijpen($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `verluchtingspijpen_niet_bekeken` = ?,"; 
		$sql .= "   `verluchtingspijpen_aanwezig` = ?,"; 
		$sql .= "   `verluchtingspijpen_aantal_totaal` = ?,"; 
		$sql .= "   `verluchtingspijpen_aantal_met_roostertje` = ?,"; 
		$sql .= "   `verluchtingspijpen_aantal_met_shouwtje` = ?,"; 
		$sql .= "   `verluchtingspijpen_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iissssi', 
				$vo["verluchtingspijpen_niet_bekeken"], 
				$vo["verluchtingspijpen_aanwezig"], 
				$vo["verluchtingspijpen_aantal_totaal"], 
				$vo["verluchtingspijpen_aantal_met_roostertje"], 
				$vo["verluchtingspijpen_aantal_met_shouwtje"], 
				$vo["verluchtingspijpen_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveBuitenToestand($vo, $mysqli) {
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

	private function saveBinnenToestand($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `toestand_binnen_gebruik` = ?,"; 
		$sql .= "   `toestand_binnen_gebruik_andere` = ?,"; 
		$sql .= "   `toestand_binnen_toestand` = ?,"; 
		$sql .= "   `toestand_binnen_toestand_andere` = ?,"; 
		$sql .= "   `toestand_binnen_vochtigheid` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('sssssi', 
				$vo["toestand_binnen_gebruik"], 
				$vo["toestand_binnen_gebruik_andere"], 
				$vo["toestand_binnen_toestand"], 
				$vo["toestand_binnen_toestand_andere"], 
				$vo["toestand_binnen_vochtigheid"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveBezoek($vo, $mysqli) {
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

	private function saveOmgeving($vo, $mysqli) {
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
	
	private function saveIngang($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `ingang_niet_bekeken` = ?,";
  		$sql .= "   `ingang_toegang` = ?,";
  		$sql .= "   `ingang_ladder_nog_aanwezig` = ?,";
  		$sql .= "   `ingang_opmerkingen` = ?";
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('isisi', 
				$vo["ingang_niet_bekeken"], 
				$vo["ingang_toegang"], 
				$vo["ingang_ladder_nog_aanwezig"], 
				$vo["ingang_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}
		
	private function saveBuitendeur($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
  		$sql .= "   `buitendeur_niet_bekeken` = ?,";
  		$sql .= "   `buitendeur_aanwezig` = ?,";
  		$sql .= "   `buitendeur_origineel` = ?,";
  		$sql .= "   `buitendeur_replica` = ?,";
  		$sql .= "   `buitendeur_ander_type` = ?,";
  		$sql .= "   `buitendeur_ander_type_andere` = ?,";
  		$sql .= "   `buitendeur_toestand` = ?,";
  		$sql .= "   `buitendeur_scharnieren_aanwezig` = ?,";
  		$sql .= "   `buitendeur_opmerkingen` = ?";
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiiisssisi', 
				$vo["buitendeur_niet_bekeken"], 
				$vo["buitendeur_aanwezig"], 
				$vo["buitendeur_origineel"], 
				$vo["buitendeur_replica"], 
				$vo["buitendeur_ander_type"], 
				$vo["buitendeur_ander_type_andere"], 
				$vo["buitendeur_toestand"], 
				$vo["buitendeur_scharnieren_aanwezig"], 
				$vo["buitendeur_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}
		
	private function saveBinnendeur($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
  		$sql .= "   `binnendeur_niet_bekeken` = ?,";
  		$sql .= "   `binnendeur_aanwezig` = ?,";
  		$sql .= "   `binnendeur_origineel` = ?,";
  		$sql .= "   `binnendeur_replica` = ?,";
  		$sql .= "   `binnendeur_ander_type` = ?,";
  		$sql .= "   `binnendeur_ander_type_andere` = ?,";
  		$sql .= "   `binnendeur_toestand` = ?,";
  		$sql .= "   `binnendeur_scharnieren_aanwezig` = ?,";
  		$sql .= "   `binnendeur_opmerkingen` = ?";
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiiisssisi', 
				$vo["binnendeur_niet_bekeken"], 
				$vo["binnendeur_aanwezig"], 
				$vo["binnendeur_origineel"], 
				$vo["binnendeur_replica"], 
				$vo["binnendeur_ander_type"], 
				$vo["binnendeur_ander_type_andere"], 
				$vo["binnendeur_toestand"], 
				$vo["binnendeur_scharnieren_aanwezig"], 
				$vo["binnendeur_opmerkingen"], 
				
				$vo["bunkerbezoek_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

}