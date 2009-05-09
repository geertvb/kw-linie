<?php

//header('Location:upload.html');

include 'mysqliUtils.php';
include 'resizeimage.php';

$bunker_id = $_POST["bunker_id"];
$omschrijving = $_POST["omschrijving"];
$filename = $_FILES["file"]["name"];
$mimetype = $_FILES["file"]["type"];
$size = $_FILES["file"]["size"];
$image_file = $_FILES["file"]["tmp_name"];
$content = NULL;
$thumb_content = NULL;

$thumb_file = tempnam(sys_get_temp_dir(), 'thumb');

echo $thumb_file . "<br>";

list($thumb_width, $thumb_height, $width, $height) = createthumb($image_file, $thumb_file);
$thumb_mimetype = "image/jpeg";
$thumb_size = filesize($thumb_file);

echo "$thumb_width, $thumb_height, $width, $height <br>";

$mysqli = newMysqli();

$sql = "INSERT INTO kwl_foto (";
$sql .= "`bunker_id`,";
$sql .= "`omschrijving`,";
$sql .= "`filename`,";
$sql .= "`mimetype`,";
$sql .= "`size`,";
$sql .= "`width`,";
$sql .= "`height`,";
$sql .= "`content`,";
$sql .= "`thumb_mimetype`,";
$sql .= "`thumb_size`,";
$sql .= "`thumb_width`,";
$sql .= "`thumb_height`,";
$sql .= "`thumb_content`";
$sql .= ") VALUES ";
$sql .= "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);

echo "$bunker_id, $omschrijving, $filename, $mimetype, $size, $width, $height, content, $thumb_mimetype, $thumb_size, $thumb_width, $thumb_height, thumb_content <br>";

$stmt->bind_param('isssiiibsiiib', $bunker_id, $omschrijving, $filename, $mimetype, $size, $width, $height, $content, $thumb_mimetype, $thumb_size, $thumb_width, $thumb_height, $thumb_content);

$fp = fopen($image_file, "r");
while (!feof($fp)) {
	$stmt->send_long_data(7, fread($fp, 4096));
}
fclose($fp);

$fp = fopen($thumb_file, "r");
while (!feof($fp)) {
	$stmt->send_long_data(12, fread($fp, 4096));
}
fclose($fp);

$stmt->execute();

$stmt->close();

$mysqli->close();

unlink($thumb_file);

echo "OK"
?>