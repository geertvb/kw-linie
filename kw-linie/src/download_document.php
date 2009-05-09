<?php

include 'mysqliUtils.php';

$document_id = $_GET["document_id"];

if ($mysqli = newMysqli()) {

	$sql = "SELECT `filename`, `mimetype`, `size` FROM `kwl_document` WHERE `document_id` = ?";
	if ($stmt = $mysqli->prepare($sql)) {
		$stmt->bind_param('i', $document_id);
		if ($stmt->execute()) {
			$result = getSingleResult($stmt);
		}
		$stmt->close();
	}
	
	$sql = "SELECT `content` FROM `kwl_document` WHERE `document_id` = ?";
	if ($stmt = $mysqli->prepare($sql)) {
		$stmt->bind_param('i', $document_id);
		if ($stmt->execute()) {
			$stmt->store_result();
			$content = null;
			$stmt->bind_result($content);
			$result = $stmt->fetch();
		}
		$stmt->close();
	}
	
	$mysqli->close();
}

header("Content-type: $result->mimetype");
header("Content-length: $result->size");
header("Content-Disposition: attachment; filename=$result->filename");
header("Content-Description: PHP Generated Data");

print $content;

?>