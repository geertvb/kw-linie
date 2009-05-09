<?php

//header('Location:upload.html');

include 'mysqliUtils.php';

$mysqli = newMysqli();

$stmt = $mysqli->prepare("INSERT INTO kwl_document (`bunker_id`, `omschrijving`, `filename`, `mimetype`, `size`, `content`) VALUES (?, ?, ?, ?, ?, ?)");

$bunker_id = $_POST["bunker_id"];
$omschrijving = $_POST["omschrijving"];
$filename = $_FILES["file"]["name"];
$mimetype = $_FILES["file"]["type"];
$size = $_FILES["file"]["size"];
$content = NULL;

$stmt->bind_param('isssib', $bunker_id, $omschrijving, $filename, $mimetype, $size, $content);

$fp = fopen($_FILES["file"]["tmp_name"], "r");
while (!feof($fp)) {
	$stmt->send_long_data(5, fread($fp, 4096));
}
fclose($fp);

$stmt->execute();

$stmt->close();

$mysqli->close();

echo "OK"
?>