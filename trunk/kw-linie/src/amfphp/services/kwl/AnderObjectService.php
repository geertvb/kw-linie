<?php

include_once '../../../mysqliUtils.php';

class AnderObjectService {

	function findAll() {
    	$sql = "SELECT";
    	$sql .= "  *";
    	$sql .= "FROM";
    	$sql .= "  `kwl_anderobject`";
    	$sql .= "ORDER BY";
    	$sql .= "  `anderobject_id` ASC";
    	return findSQL($sql);
    }
    
    function remove($id) {
    	$sql = "DELETE FROM `kwl_anderobject` WHERE `anderobject_id` = ?";
		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				$stmt->execute();
			}
		}
    }
    
    function create() {
    	$sql = "";
    	$sql .= " INSERT INTO `kwl_anderobject`";
    	$sql .= " ()";
    	$sql .= " VALUES";
    	$sql .= " ()";
    	
    	if ($mysqli = newMysqli()) {
			$mysqli->query($sql);
			$result = $mysqli->query("SELECT LAST_INSERT_ID()");
			if ($result) {
				list($anderobject_id) = $result->fetch_row();
				$result->close();
			}
			$mysqli->close();
    	}
    	
    	return $anderobject_id;
    }
    
    function findByID($id) {
		if ($mysqli = newMysqli()) {
		
			$sql = "select * from `kwl_anderobject` where `anderobject_id` = ?";
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				if ($stmt->execute()) {
					$result = getSingleResult($stmt);
				}
				$stmt->close();
			}
			
			$result->fotos = $this->findFotos($mysqli, $id);
			$result->contacts = $this->findContacts($mysqli, $id);
			$result->links = $this->findLinks($mysqli, $id);
			$result->documenten = $this->findDocumenten($mysqli, $id);
						
			$mysqli->close();
		}
		
		return $result;	
    }
    
	private function findDocumenten($mysqli, $id) {
		$sql = "";
		$sql .= " SELECT";
		$sql .= "   `kwl_document`.`document_id`,";
		$sql .= "   `kwl_document`.`omschrijving`,";
		$sql .= "   `kwl_document`.`filename`,";
		$sql .= "   `kwl_document`.`mimetype`,";
		$sql .= "   `kwl_document`.`size`";
		$sql .= " FROM";
		$sql .= "   `kwl_anderobject_document`,";
		$sql .= "   `kwl_document`";
		$sql .= " WHERE";
		$sql .= "   `kwl_anderobject_document`.`anderobject_id` = ? AND";
		$sql .= "   `kwl_anderobject_document`.`document_id` = `kwl_document`.`document_id`";
		$sql .= " ORDER BY";
		$sql .= "   `kwl_document`.`document_id` ASC";

		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) {
				$documenten = getresult($stmt);
			}
			$stmt->close();
		}
		
		return $documenten;		
	}	
	
	private function findLinks($mysqli, $id) {
		$sql = "select `url`, `omschrijving` from `kwl_anderobject_link` where `anderobject_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) {
				$links = getResult($stmt);
			}
			$stmt->close();
		}
		return $links;
	}
	
	private function findContacts($mysqli, $id) {
		$sql = "";
		$sql .= " select";
		$sql .= "   `kwl_anderobject_contact`.`relatie`,";
		$sql .= "   `kwl_contact`.*";
		$sql .= " from";
		$sql .= "   `kwl_anderobject_contact`,";
		$sql .= "   `kwl_contact`";
		$sql .= " where";
		$sql .= "   `kwl_anderobject_contact`.`anderobject_id` = ? AND";
		$sql .= "   `kwl_anderobject_contact`.`contact_id` = `kwl_contact`.`contact_id`";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) {
				$contacts = getResult($stmt);

				$anderobjectcontacts = array();					
				foreach ($contacts as $contact) {
				 	$relatie = $contact->relatie;
				 	$contact_id = $contact->contact_id;
				 	unset($contact->relatie);
				 	$anderobjectcontacts[] = array(
				 		relatie => $relatie, 
				 		contact_id => $contact_id, 
				 		contact => $contact);
				}

			}
			$stmt->close();
		}
		return $anderobjectcontacts;
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
		$sql .= "   `kwl_anderobject_foto`,";
		$sql .= "   `kwl_foto`";
		$sql .= " WHERE";
		$sql .= "   `kwl_anderobject_foto`.`anderobject_id` = ? AND";
		$sql .= "   `kwl_anderobject_foto`.`foto_id` = `kwl_foto`.`foto_id`";
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

	function save($vo) {
		if ($mysqli = newMysqli()) {

			$this->saveType($vo, $mysqli);
			$this->saveLocatie($vo, $mysqli);
			$this->saveDocumenten($vo, $mysqli);
			$this->saveBescherming($vo, $mysqli);
			$this->saveOpmerkingen($vo, $mysqli);
			
			$this->updateLinks($vo, $mysqli);
			$this->updateContacts($vo, $mysqli);
			$this->updateFotos($vo, $mysqli);
			$this->updateDocumenten($vo, $mysqli);
			
			$mysqli->close();
		}
		return $vo;
	}
	
	private function updateDocumenten($vo, $mysqli) {
		$anderobject_id = $vo["anderobject_id"];
		$new_ids = array();
		foreach ($vo["documenten"] as $document) {
			$new_ids[] = $document["document_id"];
		}
		
    	$sql = "";
    	$sql .= " SELECT";
    	$sql .= "   `document_id`";
    	$sql .= " FROM";
    	$sql .= "   `kwl_anderobject_document`";
    	$sql .= " WHERE";
    	$sql .= "   `anderobject_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $anderobject_id);
			if ($stmt->execute()) {
				$old_ids = getValues($stmt);
			}
			$stmt->close();
		}
		
    	// Remove document ids
		$remove_ids = array_diff($old_ids, $new_ids);
		if (count($remove_ids) > 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_anderobject_document`";
	    	$sql .= " WHERE";
	    	$sql .= "   `anderobject_id` = " . $anderobject_id . " AND ";
	    	$sql .= "   `document_id` in (" . implode(", ", $remove_ids) . ")";
			$mysqli->query($sql);
    	}
		
		// Add document ids
		$add_ids = array_diff($new_ids, $old_ids);
    	if (count($add_ids) > 0) {
			$sql = "";
			$sql .= " INSERT INTO `kwl_anderobject_document`";
			$sql .= "   (`anderobject_id`, `document_id`)";
			$sql .= " VALUES";
			$values = array();
			foreach ($add_ids as $add_id) {
				$values[] = "(" . $anderobject_id . ", " . $add_id . ")";
			}
			$sql .= " " . implode(", ", $values);
			$mysqli->query($sql);
		}
    }	

	private function updateFotos($vo, $mysqli) {
		$anderobject_id = $vo["anderobject_id"];
		$new_ids = array();
		foreach ($vo["fotos"] as $foto) {
			$new_ids[] = $foto["foto_id"];
		}
		
    	$sql = "";
    	$sql .= " SELECT";
    	$sql .= "   `foto_id`";
    	$sql .= " FROM";
    	$sql .= "   `kwl_anderobject_foto`";
    	$sql .= " WHERE";
    	$sql .= "   `anderobject_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $anderobject_id);
			if ($stmt->execute()) {
				$old_ids = getValues($stmt);
			}
			$stmt->close();
		}
		
    	// Remove foto ids
		$remove_ids = array_diff($old_ids, $new_ids);
		if (count($remove_ids) > 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_anderobject_foto`";
	    	$sql .= " WHERE";
	    	$sql .= "   `anderobject_id` = " . $anderobject_id . " AND ";
	    	$sql .= "   `foto_id` in (" . implode(", ", $remove_ids) . ")";
			$mysqli->query($sql);
    	}
		
		// Add foto ids
		$add_ids = array_diff($new_ids, $old_ids);
    	if (count($add_ids) > 0) {
			$sql = "";
			$sql .= " INSERT INTO `kwl_anderobject_foto`";
			$sql .= "   (`anderobject_id`, `foto_id`)";
			$sql .= " VALUES";
			$values = array();
			foreach ($add_ids as $add_id) {
				$values[] = "(" . $anderobject_id . ", " . $add_id . ")";
			}
			$sql .= " " . implode(", ", $values);
			$mysqli->query($sql);
		}
    }	

	private function saveLocatie($vo, $mysqli) {
		$sql = "update `kwl_anderobject` ";
		$sql .= "set ";
		$sql .= "`x` = ?,"; 
		$sql .= "`y` = ?,"; 
		$sql .= "`lat` = ?,"; 
		$sql .= "`lng` = ?,"; 
		$sql .= "`gemeente` = ?,"; 
		$sql .= "`deelgemeente` = ?,"; 
		$sql .= "`toponiem` = ?,"; 
		$sql .= "`straat` = ? "; 
		$sql .= "where `anderobject_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiddssssi', 
				$vo["x"], 
				$vo["y"], 
				$vo["lat"], 
				$vo["lng"], 
				$vo["gemeente"], 
				$vo["deelgemeente"], 
				$vo["toponiem"], 
				$vo["straat"], 
				$vo["anderobject_id"]);
			$stmt->execute();
			$stmt->close();
		} else {
			exit($mysqli->error);
		}
	}

	private function saveType($vo, $mysqli) {
		$sql = "";
		$sql .= " update `kwl_anderobject`";
		$sql .= " set";
		$sql .= "   `type` = ?,"; 
		$sql .= "   `aanwezig` = ? "; 
		$sql .= " where `anderobject_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('sii', 
				$vo["type"], 
				$vo["aanwezig"], 
				$vo["anderobject_id"]);
			$stmt->execute();
			$stmt->close();
		} else {
			exit($mysqli->error);
		}
	}
	
	private function updateLinks($vo, $mysqli) {
		$anderobject_id = $vo["anderobject_id"];
		$links = $vo["links"];

		$sql = "";
    	$sql .= " DELETE FROM `kwl_anderobject_link`";
    	$sql .= " WHERE";
    	$sql .= "   `anderobject_id` = " . $anderobject_id;
		$mysqli->query($sql);
		
		$sql = "";
		$sql .= " INSERT INTO `kwl_anderobject_link`";
		$sql .= "   (`anderobject_id`, `url`, `omschrijving`)";
		$sql .= " VALUES";
		$values = array();
		foreach ($links as $link) {
			$v = "(";
			$v .= $anderobject_id;
			$v .= ", ";
			$v .= "'" . $mysqli->real_escape_string($link["url"]) . "'";
			$v .= ", ";
			$v .= "'" . $mysqli->real_escape_string($link["omschrijving"]) . "'";
			$v .= ")";
			$values[] = $v;
		}
		$sql .= " " . implode(", ", $values);
		$mysqli->query($sql);
		return $sql;
    }	

	private function updateContacts($vo, $mysqli) {
		$anderobject_id = $vo["anderobject_id"];
		$contacts = $vo["contacts"];

		$sql = "";
    	$sql .= " DELETE FROM `kwl_anderobject_contact`";
    	$sql .= " WHERE";
    	$sql .= "   `anderobject_id` = " . $anderobject_id;
		$mysqli->query($sql);
		
		$sql = "";
		$sql .= " INSERT INTO `kwl_anderobject_contact`";
		$sql .= "   (`anderobject_id`, `contact_id`, `relatie`)";
		$sql .= " VALUES";
		$values = array();
		foreach ($contacts as $contact) {
			$v = "(";
			$v .= $anderobject_id;
			$v .= ", ";
			$v .= $contact["contact"]["contact_id"];
			$v .= ", ";
			$v .= "'" . $mysqli->real_escape_string($contact["relatie"]) . "'";
			$v .= ")";
			$values[] = $v;
		}
		$sql .= " " . implode(", ", $values);
		$mysqli->query($sql);
		return $sql;
    }	

    private function saveDocumenten($vo, $mysqli) {
		$sql = "update `kwl_anderobject` ";
		$sql .= "set ";
		$sql .= "`vh_plattegrond` = ?,"; 
		$sql .= "`vh_coupes` = ?,"; 
		$sql .= "`vh_oude_fotos` = ?,"; 
		$sql .= "`vh_andere` = ?,"; 
		$sql .= "`vh_andere_tekst` = ? "; 
		$sql .= "where `anderobject_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiiisi', 
				$vo["vh_plattegrond"], 
				$vo["vh_coupes"], 
				$vo["vh_oude_fotos"], 
				$vo["vh_andere"], 
				$vo["vh_andere_tekst"], 
				$vo["anderobject_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveBescherming($vo, $mysqli) {
		$sql = "update `kwl_anderobject` ";
		$sql .= "set ";
		$sql .= "`bescherming_gewestplan` = ?,"; 
		$sql .= "`bescherming_landschapsatlas` = ?,"; 
		$sql .= "`bescherming_ven_ivon` = ?,"; 
		$sql .= "`bescherming_sbz` = ?,"; 
		$sql .= "`bescherming_beschermd` = ?,"; 
		$sql .= "`bescherming_rup` = ?,"; 
		$sql .= "`bescherming_andere` = ?,"; 
		$sql .= "`bescherming_andere_tekst` = ? "; 
		$sql .= "where `anderobject_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiiiiiisi', 
				$vo["bescherming_gewestplan"], 
				$vo["bescherming_landschapsatlas"], 
				$vo["bescherming_ven_ivon"], 
				$vo["bescherming_sbz"], 
				$vo["bescherming_beschermd"], 
				$vo["bescherming_rup"], 
				$vo["bescherming_andere"], 
				$vo["bescherming_andere_tekst"], 
				$vo["anderobject_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveOpmerkingen($vo, $mysqli) {
		$sql = "update `kwl_anderobject` set `opmerkingen` = ? where `anderobject_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('si', 
				$vo["opmerkingen"], 
				$vo["anderobject_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

}