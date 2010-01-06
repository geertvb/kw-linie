<?php

include_once '../../../mysqliUtils.php';

class BunkerService {
	
	private function asdf($vo) {
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

	function findByExample($bunker) {
		list($where, $types, $values) = $this->asdf($bunker);
		$sql = "SELECT";
    	$sql .= " * ";
    	$sql .= "FROM";
    	$sql .= "  `kwl_bunker`";
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

	function create() {
    	$sql = "";
    	$sql .= " INSERT INTO `kwl_bunker`";
    	$sql .= " ()";
    	$sql .= " VALUES";
    	$sql .= " ()";
    	
    	if ($mysqli = newMysqli()) {
			$mysqli->query($sql);
			$result = $mysqli->query("SELECT LAST_INSERT_ID()");
			if ($result) {
				list($bunker_id) = $result->fetch_row();
				$result->close();
			}
			$mysqli->close();
    	}
    	
    	return $bunker_id;
    }
    
    function remove($id) {
    	$sql = "DELETE FROM `kwl_bunker` WHERE `bunker_id` = ?";
		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $id);
				$stmt->execute();
			}
		}
    }
    
    function findTypes() {
		return findSQL("select distinct `type` from `kwl_bunker` where `type` is not null order by `type` asc");
	}
	
	function findCodes() {
		return findSQL("select distinct `code` from `kwl_bunker` where `code` is not null order by `code` asc");
	}
	
	/*
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
	*/
	
	function findGemeentes() {
		$sql = <<<SQL1
SELECT DISTINCT
  `gemeente`
FROM
  `kwl_deelgemeente` 
WHERE
  `kwlinie` = 1
ORDER BY
  `gemeente` ASC
SQL1;
		return findSQL($sql);
	}
	
	function findDeelgemeentes() {
		$sql = <<<SQL2
SELECT DISTINCT
  `deelgemeente`,
  `gemeente`
FROM
  `kwl_deelgemeente`
WHERE
  `kwlinie` = 1
ORDER BY
  `deelgemeente` ASC
SQL2;
		return findSQL($sql);
	}

	function findVerbindingen() {
		$sql = <<<SQL3
SELECT
  `van`,
  `tot`
FROM
  `kwl_verbinding`
ORDER BY
  `kwl_verbinding_id` ASC
SQL3;
		return findSQL($sql);
	}

	function findByID($id) {
		
		if ($mysqli = newMysqli()) {
			$sql = "select * from `kwl_bunker` where `bunker_id` = ?";
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
		$sql .= "   `kwl_bunker_document`,";
		$sql .= "   `kwl_document`";
		$sql .= " WHERE";
		$sql .= "   `kwl_bunker_document`.`bunker_id` = ? AND";
		$sql .= "   `kwl_bunker_document`.`document_id` = `kwl_document`.`document_id`";
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
		$sql = "select `url`, `omschrijving` from `kwl_link` where `bunker_id` = ?";
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
		$sql .= "   `kwl_bunker_contact`.`relatie`,";
		$sql .= "   `kwl_contact`.*";
		$sql .= " from";
		$sql .= "   `kwl_bunker_contact`,";
		$sql .= "   `kwl_contact`";
		$sql .= " where";
		$sql .= "   `kwl_bunker_contact`.`bunker_id` = ? AND";
		$sql .= "   `kwl_bunker_contact`.`contact_id` = `kwl_contact`.`contact_id`";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) {
				$contacts = getResult($stmt);

				$bunkercontacts = array();					
				foreach ($contacts as $contact) {
				 	$relatie = $contact->relatie;
				 	$contact_id = $contact->contact_id;
				 	unset($contact->relatie);
				 	$bunkercontacts[] = array(
				 		relatie => $relatie, 
				 		contact_id => $contact_id, 
				 		contact => $contact);
				}

			}
			$stmt->close();
		}
		return $bunkercontacts;
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
		$sql .= "   `kwl_bunker_foto`,";
		$sql .= "   `kwl_foto`";
		$sql .= " WHERE";
		$sql .= "   `kwl_bunker_foto`.`bunker_id` = ? AND";
		$sql .= "   `kwl_bunker_foto`.`foto_id` = `kwl_foto`.`foto_id`";
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
	
	function save($bunker) {
		if ($mysqli = newMysqli()) {

			$this->saveType($bunker, $mysqli);
			$this->saveLocatie($bunker, $mysqli);
			$this->saveDocumenten($bunker, $mysqli);
			$this->saveBescherming($bunker, $mysqli);
			$this->saveOpmerkingen($bunker, $mysqli);
			$this->updateLinks($mysqli, $bunker["bunker_id"], $bunker["links"]);
			$this->updateContacts($mysqli, $bunker["bunker_id"], $bunker["contacts"]);
			$this->updateFotos($bunker, $mysqli);
			$this->updateDocumenten($bunker, $mysqli);
			
			$sql = <<<SQL
SELECT
  *
FROM
  `kwl_bunker`
WHERE
  `bunker_id` = ?
SQL;
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('i', $bunker["bunker_id"]);
				if ($stmt->execute()) {
					$result = getSingleResult($stmt);
				}
				$stmt->close();
			}
			
			$mysqli->close();
		}
		
		return $result;
	}
	
	private function updateDocumenten($vo, $mysqli) {
		$bunker_id = $vo["bunker_id"];
		$new_ids = array();
		foreach ($vo["documenten"] as $document) {
			$new_ids[] = $document["document_id"];
		}
		
    	$sql = "";
    	$sql .= " SELECT";
    	$sql .= "   `document_id`";
    	$sql .= " FROM";
    	$sql .= "   `kwl_bunker_document`";
    	$sql .= " WHERE";
    	$sql .= "   `bunker_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $bunker_id);
			if ($stmt->execute()) {
				$old_ids = getValues($stmt);
			}
			$stmt->close();
		}
		
    	// Remove document ids
		$remove_ids = array_diff($old_ids, $new_ids);
		if (count($remove_ids) > 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_bunker_document`";
	    	$sql .= " WHERE";
	    	$sql .= "   `bunker_id` = " . $bunker_id . " AND ";
	    	$sql .= "   `document_id` in (" . implode(", ", $remove_ids) . ")";
			$mysqli->query($sql);
    	}
		
		// Add document ids
		$add_ids = array_diff($new_ids, $old_ids);
    	if (count($add_ids) > 0) {
			$sql = "";
			$sql .= " INSERT INTO `kwl_bunker_document`";
			$sql .= "   (`bunker_id`, `document_id`)";
			$sql .= " VALUES";
			$values = array();
			foreach ($add_ids as $add_id) {
				$values[] = "(" . $bunker_id . ", " . $add_id . ")";
			}
			$sql .= " " . implode(", ", $values);
			$mysqli->query($sql);
		}
    }	

	private function updateFotos($vo, $mysqli) {
		$defaultfoto_id = null;
		foreach ($vo["fotos"] as $foto) {
			if ($foto["foto_id"] == $vo["defaultfoto_id"]) {
				$defaultfoto_id = $vo["defaultfoto_id"];
				break;
			}
		}
		
		$sql = "update `kwl_bunker` set `defaultfoto_id` = ? where `bunker_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ii', 
				$defaultfoto_id, 
				$vo["bunker_id"]);
			if (!$stmt->execute()) {
				throw new Exception($mysqli->error);
			}
			$stmt->close();
		} else {
			throw new Exception($mysqli->error);
		}

		$bunker_id = $vo["bunker_id"];
		$new_ids = array();
		foreach ($vo["fotos"] as $foto) {
			$new_ids[] = $foto["foto_id"];
		}
		
    	$sql = "";
    	$sql .= " SELECT";
    	$sql .= "   `foto_id`";
    	$sql .= " FROM";
    	$sql .= "   `kwl_bunker_foto`";
    	$sql .= " WHERE";
    	$sql .= "   `bunker_id` = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $bunker_id);
			if ($stmt->execute()) {
				$old_ids = getValues($stmt);
			}
			$stmt->close();
		}
		
    	// Remove foto ids
		$remove_ids = array_diff($old_ids, $new_ids);
		if (count($remove_ids) > 0) {
			$sql = "";
	    	$sql .= " DELETE FROM `kwl_bunker_foto`";
	    	$sql .= " WHERE";
	    	$sql .= "   `bunker_id` = " . $bunker_id . " AND ";
	    	$sql .= "   `foto_id` in (" . implode(", ", $remove_ids) . ")";
			$mysqli->query($sql);
    	}
		
		// Add foto ids
		$add_ids = array_diff($new_ids, $old_ids);
    	if (count($add_ids) > 0) {
			$sql = "";
			$sql .= " INSERT INTO `kwl_bunker_foto`";
			$sql .= "   (`bunker_id`, `foto_id`)";
			$sql .= " VALUES";
			$values = array();
			foreach ($add_ids as $add_id) {
				$values[] = "(" . $bunker_id . ", " . $add_id . ")";
			}
			$sql .= " " . implode(", ", $values);
			$mysqli->query($sql);
		}
    }	

	private function saveLocatie($bunker, $mysqli) {
		$sql = "update `kwl_bunker` ";
		$sql .= "set ";
		$sql .= "`x` = ?,"; 
		$sql .= "`y` = ?,"; 
		$sql .= "`lat` = ?,"; 
		$sql .= "`lng` = ?,"; 
		$sql .= "`gemeente` = ?,"; 
		$sql .= "`deelgemeente` = ?,"; 
		$sql .= "`toponiem` = ?,"; 
		$sql .= "`straat` = ?,"; 
		$sql .= "`stafkaart` = ?,"; 
		$sql .= "`kadaster` = ? "; 
		$sql .= "where `bunker_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iiddssssssi', 
				$bunker["x"], 
				$bunker["y"], 
				$bunker["lat"], 
				$bunker["lng"], 
				$bunker["gemeente"], 
				$bunker["deelgemeente"], 
				$bunker["toponiem"], 
				$bunker["straat"], 
				$bunker["stafkaart"], 
				$bunker["kadaster"], 
				$bunker["bunker_id"]);
			$stmt->execute();
			$stmt->close();
		}
	}

	private function saveType($bunker, $mysqli) {
		$sql = "update `kwl_bunker` ";
		$sql .= "set ";
		$sql .= "`type` = ?,"; 
		$sql .= "`code` = ?,"; 
		$sql .= "`nr` = ?,"; 
		$sql .= "`ext` = ?,"; 
		$sql .= "`nummer` = ?,"; 
		$sql .= "`schietgaten` = ?,"; 
		$sql .= "`aanwezig` = ? "; 
		$sql .= "where `bunker_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ssisssii', 
				$bunker["type"], 
				$bunker["code"], 
				$bunker["nr"], 
				$bunker["ext"], 
				$bunker["nummer"], 
				$bunker["schietgaten"], 
				$bunker["aanwezig"], 
				$bunker["bunker_id"]);
			$stmt->execute();
			$stmt->close();
		}
	}
	
	private function updateLinks($mysqli, $bunker_id, $links) {

		$sql = "";
    	$sql .= " DELETE FROM `kwl_link`";
    	$sql .= " WHERE";
    	$sql .= "   `bunker_id` = " . $bunker_id;
		$mysqli->query($sql);
		
		$sql = "";
		$sql .= " INSERT INTO `kwl_link`";
		$sql .= "   (`bunker_id`, `url`, `omschrijving`)";
		$sql .= " VALUES";
		$values = array();
		foreach ($links as $link) {
			$v = "(";
			$v .= $bunker_id;
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

	private function updateContacts($mysqli, $bunker_id, $contacts) {

		$sql = "";
    	$sql .= " DELETE FROM `kwl_bunker_contact`";
    	$sql .= " WHERE";
    	$sql .= "   `bunker_id` = " . $bunker_id;
		$mysqli->query($sql);
		
		$sql = "";
		$sql .= " INSERT INTO `kwl_bunker_contact`";
		$sql .= "   (`bunker_id`, `contact_id`, `relatie`)";
		$sql .= " VALUES";
		$values = array();
		foreach ($contacts as $contact) {
			$v = "(";
			$v .= $bunker_id;
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

    private function saveDocumenten($bunker, $mysqli) {
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

	private function saveBescherming($bunker, $mysqli) {
		$sql = "update `kwl_bunker` ";
		$sql .= "set ";
		$sql .= "`bescherming_gewestplan` = ?,"; 
		$sql .= "`bescherming_landschapsatlas` = ?,"; 
		$sql .= "`bescherming_ven_ivon` = ?,"; 
		$sql .= "`bescherming_sbz` = ?,"; 
		$sql .= "`bescherming_beschermd` = ?,"; 
		$sql .= "`bescherming_rup` = ?,"; 
		$sql .= "`bescherming_andere` = ?,"; 
		$sql .= "`bescherming_andere_tekst` = ? "; 
		$sql .= "where `bunker_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('siiiiiisi', 
				$bunker["bescherming_gewestplan"], 
				$bunker["bescherming_landschapsatlas"], 
				$bunker["bescherming_ven_ivon"], 
				$bunker["bescherming_sbz"], 
				$bunker["bescherming_beschermd"], 
				$bunker["bescherming_rup"], 
				$bunker["bescherming_andere"], 
				$bunker["bescherming_andere_tekst"], 
				$bunker["bunker_id"]);
			$stmt->execute();
			$stmt->close();
		}
		return $sql;
	}

	private function saveOpmerkingen($bunker, $mysqli) {
		$sql = "update `kwl_bunker` set `opmerkingen` = ? where `bunker_id` = ?";
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('si', 
				$bunker["opmerkingen"], 
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
    	$sql .= "  `type` ASC,";
    	$sql .= "  `nummer` ASC";
    	return findSQL($sql);
    }
    
}

?>