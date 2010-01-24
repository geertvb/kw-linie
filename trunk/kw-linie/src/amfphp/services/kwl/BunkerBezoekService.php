<?php

include_once '../../../mysqliUtils.php';

class BunkerBezoekService {

    function create() {
    	$sql = "";
    	$sql .= " INSERT INTO `kwl_bunkerbezoek`";
    	$sql .= " ()";
    	$sql .= " VALUES";
    	$sql .= " ()";
    	
    	if ($mysqli = newMysqli()) {
			$mysqli->query($sql);
			$result = $mysqli->query("SELECT LAST_INSERT_ID()");
			if ($result) {
				list($bunkerbezoek_id) = $result->fetch_row();
				$result->close();
			}
			$mysqli->close();
    	}
    	
    	return $bunkerbezoek_id;
    }
    
    function remove($id) {
    	$sql = "DELETE FROM `kwl_bunkerbezoek` WHERE `bunkerbezoek_id` = ?";
		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				$stmt->execute();
			}
		}
    }
    
	function findAll() {
		$sql[] = "SELECT ";
		$sql[] = "  `kwl_bunkerbezoek`.*,";
		$sql[] = "  `kwl_bunker`.`nummer` as `bunker_nummer`,";
		$sql[] = "  `kwl_bunker`.`type` as `bunker_type`,";
		$sql[] = "  `kwl_bunker`.`gemeente` as `bunker_gemeente`,";
		$sql[] = "  `kwl_bunker`.`deelgemeente` as `bunker_deelgemeente`,";
		$sql[] = "   CONCAT_WS(' ', `kwl_contact`.`voornaam`,`kwl_contact`.`naam`) as `invuller_naam`";
		$sql[] = "FROM ";
		$sql[] = "  `kwl_bunkerbezoek` ";
		$sql[] = "LEFT JOIN";
		$sql[] = "  `kwl_bunker` ON (`kwl_bunker`.`bunker_id`=`kwl_bunkerbezoek`.`bunker_id`)";
		$sql[] = "LEFT JOIN";
		$sql[] = "  `kwl_contact` ON (`kwl_contact`.`contact_id`=`kwl_bunkerbezoek`.`invuller_id`)";
		$sql[] = "ORDER BY";
		$sql[] = "  `kwl_bunkerbezoek`.`datum` DESC";
		
    	return findSQL(implode(" ", $sql));
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
			
			$result->bunker = $this->findBunker($mysqli, $result->bunker_id);
			$result->invuller = $this->findContact($mysqli, $result->invuller_id);
			$result->bezoekers = $this->findBezoekers($mysqli, $id);
			$result->fotos = $this->findFotos($mysqli, $id);
			$result->schietgaten = $this->findSchietgaten($mysqli, $id);
			$result->binnendeuren = $this->findBinnendeuren($mysqli, $id);
						
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
	
	private function findBezoekers($mysqli, $id) {
		$sql = "";
		$sql .= " SELECT";
		$sql .= "   `kwl_contact`.*";
		$sql .= " FROM";
		$sql .= "   `kwl_contact`,";
		$sql .= "   `kwl_bunkerbezoek_contact`";
		$sql .= " WHERE";
		$sql .= "   `kwl_contact`.`contact_id` = `kwl_bunkerbezoek_contact`.`contact_id` AND";
		$sql .= "   `kwl_bunkerbezoek_contact`.`bunkerbezoek_id` = ?";
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
	
	private function findSchietgaten($mysqli, $id) {
		$sql = "";
		$sql .= " SELECT";
		$sql .= "   *";
		$sql .= " FROM";
		$sql .= "   `kwl_bunkerbezoek_schietgat`";
		$sql .= " WHERE";
		$sql .= "   `bunkerbezoek_id` = ?";
		$sql .= " ORDER BY";
		$sql .= "   `schietgat_nummer` ASC";

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
	
	private function findBinnendeuren($mysqli, $id) {
		$sql = "";
		$sql .= " SELECT";
		$sql .= "   *";
		$sql .= " FROM";
		$sql .= "   `kwl_bunkerbezoek_binnendeur`";
		$sql .= " WHERE";
		$sql .= "   `bunkerbezoek_id` = ?";
		$sql .= " ORDER BY";
		$sql .= "   `binnendeur_nummer` ASC";

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
	
	private function arrayToList($a) {
		return "(" . implode(", ", $a) . ")";
	}
	
	private function updateBezoekers($vo, $mysqli) {
		$bunkerbezoek_id = $vo["bunkerbezoek_id"];
		$bezoeker_ids = $vo["bezoeker_ids"];
    	
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
    }	

	private function updateFotos($vo, $mysqli) {
		$bunkerbezoek_id = $vo["bunkerbezoek_id"];
		$new_ids = array();
		foreach ($vo["fotos"] as $foto) {
			$new_ids[] = $foto["foto_id"];
		}
		
    	$sql = "";
    	$sql .= " SELECT";
    	$sql .= "   `foto_id`";
    	$sql .= " FROM";
    	$sql .= "   `kwl_bunkerbezoek_foto`";
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
		$remove_ids = array_diff($old_ids, $new_ids);
		if (count($remove_ids) > 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_bunkerbezoek_foto`";
	    	$sql .= " WHERE";
	    	$sql .= "   `bunkerbezoek_id` = " . $bunkerbezoek_id . " AND ";
	    	$sql .= "   `foto_id` in " . $this->arrayToList($remove_ids);
			$mysqli->query($sql);
    	}
		
		// Add bezoeker ids
		$add_ids = array_diff($new_ids, $old_ids);
    	if (count($add_ids) > 0) {
			$sql = "";
			$sql .= " INSERT INTO `kwl_bunkerbezoek_foto`";
			$sql .= "   (`bunkerbezoek_id`, `foto_id`)";
			$sql .= " VALUES";
			$values = array();
			foreach ($add_ids as $add_id) {
				$values[] = "(" . $bunkerbezoek_id . ", " . $add_id . ")";
			}
			$sql .= " " . implode(", ", $values);
			$mysqli->query($sql);
		}
    }	

    function save($vo) {
		if ($mysqli = newMysqli()) {

			$this->saveBezoek($vo, $mysqli);
			$this->updateBezoekers($vo, $mysqli);
			$this->updateFotos($vo, $mysqli);
			$this->saveOmgeving($vo, $mysqli);
			$this->saveBuitenToestand($vo, $mysqli);
			$this->saveBinnenToestand($vo, $mysqli);
			$this->saveIngang($vo, $mysqli);
			$this->saveBuitendeur($vo, $mysqli);
			$this->saveCamouflage($vo, $mysqli);
			$this->saveDakplaten($vo, $mysqli);
			$this->saveAfsluitluikGranaatwerper($vo, $mysqli);
			$this->saveAfsluitluikPistoolkoker($vo, $mysqli);
			$this->saveRoosterIngang($vo, $mysqli);
			$this->saveNooduitgang($vo, $mysqli);
			$this->saveVerluchtingspijpen($vo, $mysqli);
			$this->updateSchietgaten($vo, $mysqli);
			$this->updateBinnendeuren($vo, $mysqli);
			
			$sql = <<<SQL
SELECT
  `kwl_bunkerbezoek`.*,
  `kwl_bunker`.`nummer` as `bunker_nummer`,
  `kwl_bunker`.`type` as `bunker_type`,
  `kwl_bunker`.`gemeente` as `bunker_gemeente`,
  `kwl_bunker`.`deelgemeente` as `bunker_deelgemeente`,
   CONCAT_WS(' ', `kwl_contact`.`voornaam`,`kwl_contact`.`naam`) as `invuller_naam`
FROM
  `kwl_bunkerbezoek`
LEFT JOIN
  `kwl_bunker` ON (`kwl_bunker`.`bunker_id`=`kwl_bunkerbezoek`.`bunker_id`)
LEFT JOIN
  `kwl_contact` ON (`kwl_contact`.`contact_id`=`kwl_bunkerbezoek`.`invuller_id`)
WHERE
  `kwl_bunkerbezoek`.`bunkerbezoek_id` = ?
SQL;
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $vo["bunkerbezoek_id"]);
				if ($stmt->execute()) {
					$result = getSingleResult($stmt);
				}
				$stmt->close();
			}			
			$mysqli->close();
		}
		return $result;
	}

	private function saveCamouflage($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `camouflage_bekeken` = ?,"; 
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
			$stmt->bind_param('ssiiiiisi', 
				$vo["camouflage_bekeken"], 
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
		$sql .= "   `dakplaten_bekeken` = ?,"; 
		$sql .= "   `dakplaten_aanwezig` = ?,"; 
		$sql .= "   `dakplaten_bunkernummer_leesbaar` = ?,"; 
		$sql .= "   `dakplaten_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ssisi', 
				$vo["dakplaten_bekeken"], 
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
		$sql .= "   `afsluitluik_granaatwerper_bekeken` = ?,"; 
		$sql .= "   `afsluitluik_granaatwerper_aanwezig` = ?,"; 
		$sql .= "   `afsluitluik_granaatwerper_aantal_totaal` = ?,"; 
		$sql .= "   `afsluitluik_granaatwerper_aantal_met_ketting` = ?,"; 
		$sql .= "   `afsluitluik_granaatwerper_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('sssssi', 
				$vo["afsluitluik_granaatwerper_bekeken"], 
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
		$sql .= "   `afsluitluik_pistoolkoker_bekeken` = ?,"; 
		$sql .= "   `afsluitluik_pistoolkoker_aanwezig` = ?,"; 
		$sql .= "   `afsluitluik_pistoolkoker_met_ketting` = ?,"; 
		$sql .= "   `afsluitluik_pistoolkoker_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ssisi', 
				$vo["afsluitluik_pistoolkoker_bekeken"], 
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
		$sql .= "   `rooster_ingang_bekeken` = ?,"; 
		$sql .= "   `rooster_ingang_aanwezig` = ?,"; 
		$sql .= "   `rooster_ingang_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('sssi', 
				$vo["rooster_ingang_bekeken"], 
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
		$sql .= "   `nooduitgang_bekeken` = ?,"; 
		$sql .= "   `nooduitgang_aanwezig` = ?,"; 
		$sql .= "   `nooduitgang_toestand` = ?,"; 
		$sql .= "   `nooduitgang_schotbalken_binnenkant_aanwezig` = ?,"; 
		$sql .= "   `nooduitgang_schotbalken_buitenkant_aanwezig` = ?,"; 
		$sql .= "   `telefoonaansluiting_aanwezig` = ?,"; 
		$sql .= "   `telefoonaansluiting_inscriptie` = ?,"; 
		$sql .= "   `nooduitgang_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ssssssssi', 
				$vo["nooduitgang_bekeken"], 
				$vo["nooduitgang_aanwezig"], 
				$vo["nooduitgang_toestand"], 
				$vo["nooduitgang_schotbalken_binnenkant_aanwezig"], 
				$vo["nooduitgang_schotbalken_buitenkant_aanwezig"], 
				$vo["telefoonaansluiting_aanwezig"], 
				$vo["telefoonaansluiting_inscriptie"], 
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
		$sql .= "   `verluchtingspijpen_bekeken` = ?,"; 
		$sql .= "   `verluchtingspijpen_aanwezig` = ?,"; 
		$sql .= "   `verluchtingspijpen_aantal_totaal` = ?,"; 
		$sql .= "   `verluchtingspijpen_aantal_met_roostertje` = ?,"; 
		$sql .= "   `verluchtingspijpen_aantal_met_shouwtje` = ?,"; 
		$sql .= "   `verwarmingsbuizen` = ?,"; 
		$sql .= "   `kacheltjes` = ?,"; 
		$sql .= "   `verluchtingspijpen_opmerkingen` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ssssssssi', 
				$vo["verluchtingspijpen_bekeken"], 
				$vo["verluchtingspijpen_aanwezig"], 
				$vo["verluchtingspijpen_aantal_totaal"], 
				$vo["verluchtingspijpen_aantal_met_roostertje"], 
				$vo["verluchtingspijpen_aantal_met_shouwtje"], 
				$vo["verwarmingsbuizen"], 
				$vo["kacheltjes"], 
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
		$sql .= "   `bedreigingen_bekeken` = ?,"; 
		$sql .= "   `bedreigingen` = ?,"; 
		$sql .= "   `bedreigingen_andere` = ?,"; 
		$sql .= "   `toestand_buiten_bekeken` = ?,"; 
		$sql .= "   `toestand_buiten_goed` = ?,"; 
		$sql .= "   `toestand_buiten_betonrot` = ?,"; 
		$sql .= "   `toestand_buiten_beschadiging_gevechten` = ?,"; 
		$sql .= "   `toestand_buiten_beschadiging_latere_datum` = ?,"; 
		$sql .= "   `toestand_buiten_beschadiging_natuurlijk` = ?,"; 
		$sql .= "   `toestand_buiten_andere` = ?,"; 
		$sql .= "   `toestand_buiten_andere_tekst` = ?,"; 
		$sql .= "   `toestand_buiten_toegankelijk_bekeken` = ?,"; 
		$sql .= "   `toestand_buiten_toegankelijk` = ?,"; 
		$sql .= "   `toestand_buiten_ontoegankelijk_reden_bekeken` = ?,"; 
		$sql .= "   `toestand_buiten_ontoegankelijk_reden` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ssssiiiiiisssssi', 
				$vo["bedreigingen_bekeken"], 
				$vo["bedreigingen"], 
				$vo["bedreigingen_andere"], 
				$vo["toestand_buiten_bekeken"], 
				$vo["toestand_buiten_goed"], 
				$vo["toestand_buiten_betonrot"], 
				$vo["toestand_buiten_beschadiging_gevechten"], 
				$vo["toestand_buiten_beschadiging_latere_datum"], 
				$vo["toestand_buiten_beschadiging_natuurlijk"], 
				$vo["toestand_buiten_andere"], 
				$vo["toestand_buiten_andere_tekst"], 
				$vo["toestand_buiten_toegankelijk_bekeken"], 
				$vo["toestand_buiten_toegankelijk"], 
				$vo["toestand_buiten_ontoegankelijk_reden_bekeken"], 
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
		$sql .= "   `toestand_binnen_gebruik_bekeken` = ?,"; 
		$sql .= "   `toestand_binnen_gebruik` = ?,"; 
		$sql .= "   `toestand_binnen_gebruik_andere` = ?,"; 
		$sql .= "   `toestand_binnen_toestand_bekeken` = ?,"; 
		$sql .= "   `toestand_binnen_toestand` = ?,"; 
		$sql .= "   `toestand_binnen_toestand_andere` = ?,"; 
		$sql .= "   `toestand_binnen_vochtigheid_bekeken` = ?,"; 
		$sql .= "   `toestand_binnen_vochtigheid` = ?"; 
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ssssssssi', 
				$vo["toestand_binnen_gebruik_bekeken"], 
				$vo["toestand_binnen_gebruik"], 
				$vo["toestand_binnen_gebruik_andere"], 
				$vo["toestand_binnen_toestand_bekeken"], 
				$vo["toestand_binnen_toestand"], 
				$vo["toestand_binnen_toestand_andere"], 
				$vo["toestand_binnen_vochtigheid_bekeken"], 
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
		$sql .= "`bunker_id` = ?,"; 
		$sql .= "`invuller_id` = ?,"; 
		$sql .= "`aanwezigheid` = ?,"; 
		$sql .= "`reden_niet_aanwezig` = ?,"; 
		$sql .= "`reden_niet_aanwezig_tekst` = ? "; 
		$sql .= "where `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('siisssi', 
				$vo["datum"], 
				$vo["bunker_id"], 
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

		$sql .= "`omgeving_10m_bekeken` = ?,"; 
		$sql .= "`omgeving_10m_bos` = ?,"; 
		$sql .= "`omgeving_10m_bebouwing` = ?,"; 
		$sql .= "`omgeving_10m_weiland` = ?,"; 
		$sql .= "`omgeving_10m_park` = ?,"; 
		$sql .= "`omgeving_10m_akker` = ?,"; 
		$sql .= "`omgeving_10m_water` = ?,"; 

		$sql .= "`omgeving_100m_bekeken` = ?,"; 
		$sql .= "`omgeving_100m_bos` = ?,"; 
		$sql .= "`omgeving_100m_bebouwing` = ?,"; 
		$sql .= "`omgeving_100m_weiland` = ?,"; 
		$sql .= "`omgeving_100m_park` = ?,"; 
		$sql .= "`omgeving_100m_akker` = ?,"; 
		$sql .= "`omgeving_100m_water` = ?,"; 
		
		$sql .= "`ligging_bekeken` = ?,"; 
		$sql .= "`ligging` = ?,"; 
		$sql .= "`expositie_bekeken` = ?,"; 
		$sql .= "`expositie` = ?,"; 
		$sql .= "`relief_bekeken` = ?,"; 
		$sql .= "`relief` = ?,"; 
		$sql .= "`afstand_berijdbare_weg_meter` = ?,"; 
		$sql .= "`afstand_berijdbare_weg_bekeken` = ?,"; 

		$sql .= "`recreatieve_ontsluiting_bekeken` = ?,"; 
		$sql .= "`recreatieve_ontsluiting_langs_trage_weg` = ?,";
		$sql .= "`recreatieve_ontsluiting_fietspad` = ?,";
		$sql .= "`recreatieve_ontsluiting_informatiebord` = ?,";
		$sql .= "`recreatieve_ontsluiting_andere` = ?,";
		$sql .= "`recreatieve_ontsluiting_andere_omschrijving` = ? "; 
		
		$sql .= "where `bunkerbezoek_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			if (!$stmt->bind_param('siiiiiisiiiiiissssssissiiiisi', 
				$vo["omgeving_10m_bekeken"], 
				$vo["omgeving_10m_bos"], 
				$vo["omgeving_10m_bebouwing"], 
				$vo["omgeving_10m_weiland"], 
				$vo["omgeving_10m_park"], 
				$vo["omgeving_10m_akker"],
				$vo["omgeving_10m_water"],

				$vo["omgeving_100m_bekeken"], 
				$vo["omgeving_100m_bos"], 
				$vo["omgeving_100m_bebouwing"], 
				$vo["omgeving_100m_weiland"], 
				$vo["omgeving_100m_park"], 
				$vo["omgeving_100m_akker"],
				$vo["omgeving_100m_water"],
				
				$vo["ligging_bekeken"], 
				$vo["ligging"], 
				$vo["expositie_bekeken"], 
				$vo["expositie"], 
				$vo["relief_bekeken"], 
				$vo["relief"], 
				$vo["afstand_berijdbare_weg_meter"], 
				$vo["afstand_berijdbare_weg_bekeken"], 
				
				$vo["recreatieve_ontsluiting_bekeken"],
				$vo["recreatieve_ontsluiting_langs_trage_weg"],
				$vo["recreatieve_ontsluiting_fietspad"],
				$vo["recreatieve_ontsluiting_informatiebord"],
				$vo["recreatieve_ontsluiting_andere"],
				$vo["recreatieve_ontsluiting_andere_omschrijving"],
				
				$vo["bunkerbezoek_id"]
				)) {
				throw new Exception($mysqli->error);
			}
			if (!$stmt->execute()) {
				throw new Exception($mysqli->error);
			}
			$stmt->close();
		} else {
			throw new Exception($mysqli->error);
		}
		return $sql;
	}
	
	private function saveIngang($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
		$sql .= "   `ingang_bekeken` = ?,";
  		$sql .= "   `ingang_toegang` = ?,";
  		$sql .= "   `ingang_ladder_nog_aanwezig` = ?,";
  		$sql .= "   `ingang_opmerkingen` = ?";
		$sql .= " where";
		$sql .= "   `bunkerbezoek_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ssisi', 
				$vo["ingang_bekeken"], 
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
  		$sql .= "   `buitendeur_bekeken` = ?,";
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
			$stmt->bind_param('ssiisssssi', 
				$vo["buitendeur_bekeken"], 
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
		
	/*
	private function saveBinnendeur($vo, $mysqli) {
		$sql = "";
		$sql .= " update";
		$sql .= "   `kwl_bunkerbezoek`";
		$sql .= " set ";
  		$sql .= "   `binnendeur_bekeken` = ?,";
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
			$stmt->bind_param('ssiisssssi', 
				$vo["binnendeur_bekeken"], 
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
	*/

	private function updateBinnendeuren($vo, $mysqli) {
		$bunkerbezoek_id = $vo["bunkerbezoek_id"];
		$binnendeuren = $vo["binnendeuren"];
		if ($binnendeuren == null || count($binnendeuren) == 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_bunkerbezoek_binnendeur`";
	    	$sql .= " WHERE";
	    	$sql .= "   `bunkerbezoek_id` = " . $bunkerbezoek_id;
			$mysqli->query($sql);
			//throw new Exception('Empty');
		} else {
			$remove_nrs = array();
			foreach ($binnendeuren as $binnendeur) {
				$remove_nrs[] = $binnendeur["binnendeur_nummer"];
			}
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_bunkerbezoek_binnendeur`";
	    	$sql .= " WHERE";
	    	$sql .= "   `bunkerbezoek_id` = " . $bunkerbezoek_id . " AND ";
	    	$sql .= "   `binnendeur_nummer` not in (" . implode(", ", $remove_nrs) . ")";
			$mysqli->query($sql);
			
			$sql = "";
			$sql .= " INSERT INTO `kwl_bunkerbezoek_binnendeur` (";
			$sql .= "   `bunkerbezoek_id`,";
			$sql .= "   `binnendeur_nummer`,";
			
			$sql .= "   `bekeken`,";
			$sql .= "   `aanwezig`,";
			$sql .= "   `origineel`,";
			$sql .= "   `replica`,";
			$sql .= "   `ander_type`,";
			$sql .= "   `ander_type_andere`,";
			$sql .= "   `toestand`,";
			$sql .= "   `scharnieren_aanwezig`,";
			$sql .= "   `opmerkingen`";

			$sql .= " ) VALUES (";
			$sql .= "   ?,";
			$sql .= "   ?,";
			
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?";

			$sql .= " ) ON DUPLICATE KEY UPDATE";
			$sql .= " `bekeken` = VALUES(`bekeken`),";
			$sql .= " `aanwezig` = VALUES(`aanwezig`),";
			$sql .= " `origineel` = VALUES(`origineel`),";
			$sql .= " `replica` = VALUES(`replica`),";
			$sql .= " `ander_type` = VALUES(`ander_type`),";
			$sql .= " `ander_type_andere` = VALUES(`ander_type_andere`),";
			$sql .= " `toestand` = VALUES(`toestand`),";
			$sql .= " `scharnieren_aanwezig` = VALUES(`scharnieren_aanwezig`),";
			$sql .= " `opmerkingen` = VALUES(`opmerkingen`)";
			
			if ($stmt = $mysqli->prepare($sql)) {
				foreach ($binnendeuren as $binnendeur) {
					$stmt->bind_param('iissiisssss', 
						$bunkerbezoek_id, 
						$binnendeur["binnendeur_nummer"], 
						
						$binnendeur["bekeken"],
						$binnendeur["aanwezig"],
						$binnendeur["origineel"],
						$binnendeur["replica"],
						$binnendeur["ander_type"],
						$binnendeur["ander_type_andere"],
						$binnendeur["toestand"],
						$binnendeur["scharnieren_aanwezig"],
						$binnendeur["opmerkingen"]
					);
					if (!$stmt->execute()) {
						throw new Exception($mysqli->error);
					}
				}
				$stmt->close();
			} else {
				throw new Exception($mysqli->error);
			}
			
		}
	}
	
	private function updateSchietgaten($vo, $mysqli) {
		$bunkerbezoek_id = $vo["bunkerbezoek_id"];
		$schietgaten = $vo["schietgaten"];
		if ($schietgaten == null || count($schietgaten) == 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_bunkerbezoek_schietgat`";
	    	$sql .= " WHERE";
	    	$sql .= "   `bunkerbezoek_id` = " . $bunkerbezoek_id;
			$mysqli->query($sql);
			//throw new Exception('Empty');
		} else {
			$remove_nrs = array();
			foreach ($schietgaten as $schietgat) {
				$remove_nrs[] = $schietgat["schietgat_nummer"];
			}
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_bunkerbezoek_schietgat`";
	    	$sql .= " WHERE";
	    	$sql .= "   `bunkerbezoek_id` = " . $bunkerbezoek_id . " AND ";
	    	$sql .= "   `schietgat_nummer` not in (" . implode(", ", $remove_nrs) . ")";
			$mysqli->query($sql);
			
			$sql = "";
			$sql .= " INSERT INTO `kwl_bunkerbezoek_schietgat` (";
			$sql .= "   `bunkerbezoek_id`,";
			$sql .= "   `schietgat_nummer`,";
			$sql .= "   `bekeken`,";

			$sql .= "   `toestand`,";
			$sql .= "   `schootsveld`,";
			$sql .= "   `afsluitluik_buitenzijde_aanwezig`,";
			$sql .= "   `afsluitluik_bedieningsketting_aanwezig`,";
			//$sql .= "   `afsluitluik_binnenzijde_aanwezig`,";

			$sql .= "   `affuit_aanwezig`,";
			$sql .= "   `affuit_type`,";
			$sql .= "   `affuit_toestand`,";
			$sql .= "   `affuit_verankeringspunten_aanwezig`,";
			$sql .= "   `affuit_nummer_inscripties_aanwezig`,";

			$sql .= "   `witte_lijn_aanwezig`,";
			$sql .= "   `observatiesleuf_toestand`,";
			$sql .= "   `observatiesleuf_luikje_aanwezig`,";
			$sql .= "   `haken_petroleumlampen_aanwezig`,";
			$sql .= "   `haken_petroleumlampen_aantal`,";

			$sql .= "   `houten_schapje_aanwezig`,";
			$sql .= "   `zitbankje_aanwezig`,";
			$sql .= "   `metalen_schap_met_haken_aanwezig`,";
			$sql .= "   `metalen_schap_met_haken_aantal`,";
			$sql .= "   `opmerkingen`";

			$sql .= " ) VALUES (";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			//$sql .= "   ?,";

			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";

			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";

			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?,";
			$sql .= "   ?";

			$sql .= " ) ON DUPLICATE KEY UPDATE";
			$sql .= " `bekeken` = VALUES(`bekeken`),";

			$sql .= " `toestand` = VALUES(`toestand`),";
			$sql .= " `schootsveld` = VALUES(`schootsveld`),";
			$sql .= " `afsluitluik_buitenzijde_aanwezig` = VALUES(`afsluitluik_buitenzijde_aanwezig`),";
			$sql .= " `afsluitluik_bedieningsketting_aanwezig` = VALUES(`afsluitluik_bedieningsketting_aanwezig`),";
			//$sql .= " `afsluitluik_binnenzijde_aanwezig` = VALUES(`afsluitluik_binnenzijde_aanwezig`),";
			
			$sql .= " `affuit_aanwezig` = VALUES(`affuit_aanwezig`),";
			$sql .= " `affuit_type` = VALUES(`affuit_type`),";
			$sql .= " `affuit_toestand` = VALUES(`affuit_toestand`),";
			$sql .= " `affuit_verankeringspunten_aanwezig` = VALUES(`affuit_verankeringspunten_aanwezig`),";
			$sql .= " `affuit_nummer_inscripties_aanwezig` = VALUES(`affuit_nummer_inscripties_aanwezig`),";
			
			$sql .= " `witte_lijn_aanwezig` = VALUES(`witte_lijn_aanwezig`),";
			$sql .= " `observatiesleuf_toestand` = VALUES(`observatiesleuf_toestand`),";
			$sql .= " `observatiesleuf_luikje_aanwezig` = VALUES(`observatiesleuf_luikje_aanwezig`),";
			$sql .= " `haken_petroleumlampen_aanwezig` = VALUES(`haken_petroleumlampen_aanwezig`),";
			$sql .= " `haken_petroleumlampen_aantal` = VALUES(`haken_petroleumlampen_aantal`),";
			
			$sql .= " `houten_schapje_aanwezig` = VALUES(`houten_schapje_aanwezig`),";
			$sql .= " `zitbankje_aanwezig` = VALUES(`zitbankje_aanwezig`),";
			$sql .= " `metalen_schap_met_haken_aanwezig` = VALUES(`metalen_schap_met_haken_aanwezig`),";
			$sql .= " `metalen_schap_met_haken_aantal` = VALUES(`metalen_schap_met_haken_aantal`),";
			$sql .= " `opmerkingen` = VALUES(`opmerkingen`)";
			
			if ($stmt = $mysqli->prepare($sql)) {
				foreach ($schietgaten as $schietgat) {
					$stmt->bind_param('iississsssssssssisssis', 
						$bunkerbezoek_id, 
						$schietgat["schietgat_nummer"], 
						$schietgat["bekeken"],

						$schietgat["toestand"],
						$schietgat["schootsveld"],
						$schietgat["afsluitluik_buitenzijde_aanwezig"],
						$schietgat["afsluitluik_bedieningsketting_aanwezig"],
						//$schietgat["afsluitluik_binnenzijde_aanwezig"],

						$schietgat["affuit_aanwezig"],
						$schietgat["affuit_type"],
						$schietgat["affuit_toestand"],
						$schietgat["affuit_verankeringspunten_aanwezig"],
						$schietgat["affuit_nummer_inscripties_aanwezig"],

						$schietgat["witte_lijn_aanwezig"],
						$schietgat["observatiesleuf_toestand"],
						$schietgat["observatiesleuf_luikje_aanwezig"],
						$schietgat["haken_petroleumlampen_aanwezig"],
						$schietgat["haken_petroleumlampen_aantal"],

						$schietgat["houten_schapje_aanwezig"],
						$schietgat["zitbankje_aanwezig"],
						$schietgat["metalen_schap_met_haken_aanwezig"],
						$schietgat["metalen_schap_met_haken_aantal"],
						$schietgat["opmerkingen"]
					);
					if (!$stmt->execute()) {
						throw new Exception($mysqli->error);
					}
				}
				$stmt->close();
			} else {
				throw new Exception($mysqli->error);
			}
			
		}
	}

}