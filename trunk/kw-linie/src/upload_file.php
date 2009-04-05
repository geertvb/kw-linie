<?php

header('Location:upload.html');

include 'mysql_connect.php';

$stmt = $mysqli->prepare("INSERT INTO kwl_blob (`filename`, `mimetype`, `size`, `content`) VALUES (?, ?, ?, ?)");

$filename = $_FILES["file"]["name"];
$mimetype = $_FILES["file"]["type"];
$size = $_FILES["file"]["size"];
$content = NULL;

$stmt->bind_param('ssib', $filename, $mimetype, $size, $content);

$fp = fopen($_FILES["file"]["tmp_name"], "r");
while (!feof($fp)) {
	$stmt->send_long_data(3, fread($fp, 4096));
}
fclose($fp);

$stmt->execute();

$stmt->close();

$mysqli->close();

?>