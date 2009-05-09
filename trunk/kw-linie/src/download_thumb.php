<?php

include 'mysqliUtils.php';

$foto_id = $_GET["foto_id"];

if ($mysqli = newMysqli()) {

	$sql = "SELECT `filename`, `thumb_mimetype`, `thumb_size` FROM `kwl_foto` WHERE `foto_id` = ?";
	if ($stmt = $mysqli->prepare($sql)) {
		$stmt->bind_param('i', $foto_id);
		if ($stmt->execute()) {
			$stmt->bind_result($filename, $thumb_mimetype, $thumb_size);
			$result = $stmt->fetch();
		}
		$stmt->close();
	}
	
	$sql = "SELECT `thumb_content` FROM `kwl_foto` WHERE `foto_id` = ?";
	if ($stmt = $mysqli->prepare($sql)) {
		$stmt->bind_param('i', $foto_id);
		if ($stmt->execute()) {
			$stmt->store_result();
			$thumb_content = null;
			$stmt->bind_result($thumb_content);
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
	header("Content-length: $thumb_size");
	header("Content-Disposition: inline; filename=$filename");
	header("Content-Description: PHP Generated Data");
	
    /*
	echo "Content-type: $ctype";
	echo "Content-length: $thumb_size";
	echo "Content-Disposition: inline; filename=$filename";
	echo "Content-Description: PHP Generated Data";
	*/
	
	print $thumb_content;
	
}

?>