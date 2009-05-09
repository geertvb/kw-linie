<?php

include 'mysqliUtils.php';

$document_id = $_GET["document_id"];

if ($mysqli = newMysqli()) {

	$sql = "SELECT `filename`, `mimetype`, `size` FROM `kwl_document` WHERE `document_id` = ?";
	if ($stmt = $mysqli->prepare($sql)) {
		$stmt->bind_param('i', $document_id);
		if ($stmt->execute()) {
			$stmt->bind_result($filename, $mimetype, $size);
			$result = $stmt->fetch();
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
	
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch ($file_extension) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpe": case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        default: $ctype="application/force-download";
    }

	header("Content-type: $ctype");
	header("Content-length: $size");
	header("Content-Disposition: inline; filename=$filename");
	header("Content-Description: PHP Generated Data");
	
	print $content;
	
}

?>