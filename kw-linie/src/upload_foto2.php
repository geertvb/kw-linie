<?php

include 'mysqliUtils.php';

$omschrijving = $_POST["omschrijving"];

$filename = $_FILES["file"]["name"];

$image_file = $_FILES["file"]["tmp_name"];
$mimetype = $_FILES["file"]["type"];
$size = $_FILES["file"]["size"];
$width = $_POST["width"];
$height = $_POST["height"];
$uid = $_POST["uid"];
$content = NULL;

$thumb_mimetype = $_POST["thumb_mimetype"];
$thumb_size = $_POST["thumb_size"]; // strlen($thumb_content);
$thumb_width = $_POST["thumb_width"];
$thumb_height = $_POST["thumb_height"];
$thumb_content = NULL;

$mysqli = newMysqli();

$sql = <<<SQL
INSERT INTO kwl_foto (
  `uid`,
  `omschrijving`,
  `filename`,
  `mimetype`,
  `size`,
  `width`,
  `height`,
  `content`,
  `thumb_mimetype`,
  `thumb_size`,
  `thumb_width`,
  `thumb_height`,
  `thumb_content`
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
SQL;

$stmt = $mysqli->prepare($sql);

$stmt->bind_param('ssssiiibsiiib', 
    &$uid,
	&$omschrijving, 
	&$filename, 
	&$mimetype, 
	&$size, 
	&$width, 
	&$height, 
	&$content, 
	&$thumb_mimetype, 
	&$thumb_size, 
	&$thumb_width, 
	&$thumb_height, 
	&$thumb_content);

$fp = fopen($image_file, "r");
while (!feof($fp)) {
	$stmt->send_long_data(7, fread($fp, 4096));
}
fclose($fp);

$tc = base64_decode($_POST['thumb_content']);
$chunks = str_split($tc, 4096);
foreach ($chunks as $chunk) {
	$stmt->send_long_data(12, $chunk);
}

$stmt->execute();

$stmt->close();

$mysqli->close();

echo 'OK - ' . uid;

?>