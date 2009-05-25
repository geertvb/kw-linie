<?php

//header('Location:upload.html');

include 'mysqliUtils.php';

$mysqli = newMysqli();

$stmt = $mysqli->prepare("INSERT INTO kwl_document (`bunker_id`, `omschrijving`, `filename`, `mimetype`, `size`, `content`) VALUES (?, ?, ?, ?, ?, ?)");

$bunker_id = 0; //$_POST["bunker_id"];
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

// Get document_id

if ($result = $mysqli->query("SELECT LAST_INSERT_ID()")) {
	list($document_id) = $result->fetch_row();
	$result->close();
}

$mysqli->close();

$xml = new XMLWriter();
$xml->openMemory();
$xml->setIndent(true);
$xml->setIndentString(' ');
$xml->startDocument('1.0', 'UTF-8');
$xml->startElement("document");

	$xml->writeElement('document_id', $document_id);
	$xml->writeElement('omschrijving', $omschrijving);
	$xml->writeElement('filename', $filename);
	$xml->writeElement('mimetype', $mimetype);
	$xml->writeElement('size', $size);
	
$xml->endElement();
$xml->endDocument();

echo $xml->outputMemory();

?>