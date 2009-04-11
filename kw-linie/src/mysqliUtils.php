<?php

function newMysqli() {
	$mysqli = new mysqli("localhost", "j_devit_be", "K3DyYQcn", "j_devit_be");
	
	return $mysqli;
}

function findSQL($sql) {
	if ($mysqli = newMysqli()) {
		if ($stmt = $mysqli->prepare($sql)) {
			if ($stmt->execute()) {
				$result = getresult($stmt);
			}
			$stmt->close();
		}
		$mysqli->close();
	}
	
	return $result;
}

function getResult($stmt) {
	$result = array();
	 
	$metadata = $stmt->result_metadata();
	$fields = $metadata->fetch_fields();

	for (;;) {
		$pointers = array();
		$row = new stdClass();
		 
		$pointers[] = $stmt;
		foreach ($fields as $field) {
			$fieldname = $field->name;
			$pointers[] = &$row->$fieldname;
		}
		 
		call_user_func_array(mysqli_stmt_bind_result, $pointers);
		 
		if (!$stmt->fetch()) {
			break;
		}
		 
		$result[] = $row;
	}
	 
	$metadata->free();
	 
	return $result;
}

function getSingleResult($stmt) {
	$metadata = $stmt->result_metadata();
	$fields = $metadata->fetch_fields();

	$pointers = array();
	$result = new stdClass();
	 
	$pointers[] = $stmt;
	foreach ($fields as $field) {
		$fieldname = $field->name;
		$pointers[] = &$result->$fieldname;
	}
	 
	call_user_func_array(mysqli_stmt_bind_result, $pointers);
	 
	if (!$stmt->fetch()) {
		$result = null;
	}

	$metadata->free();
	 
	return $result;
}

?>