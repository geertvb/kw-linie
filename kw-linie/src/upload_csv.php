<?php

$INDEX_NUMMER = 0;
$INDEX_X = 1;
$INDEX_Y = 2;

include 'mysql_connect.php';
$stmt = $mysqli->prepare("INSERT INTO kwl_bunker (`nummer`, `x`, `y`) VALUES (?, ?, ?)");
$stmt->bind_param('sii', $bnr, $bcx, $bcy);



$row = 1;
$handle = fopen($_FILES["file"]["tmp_name"], "r");
echo "<table border='1'>";
while (($data = fgetcsv($handle)) !== FALSE) {
	if (isBunker($data)) {
		echo "<tr>";
	    $num = count($data);
	    $row++;
	    for ($c=0; $c < $num; $c++) {
			echo "<td>" . $data[$c] . "</td>\n";
    	}
		echo "</tr>";
		
		$bnr = $data[$INDEX_NUMMER];
		$bcx = $data[$INDEX_X];
		$bcy = $data[$INDEX_Y];
		$stmt->execute();
		
	}
}
echo "</table>";
fclose($handle);

$stmt->close();
$mysqli->close();

function isBunker($data) {
	global $INDEX_NUMMER;
	global $INDEX_X;
	global $INDEX_Y;
	
	return isBunkerNummer($data[$INDEX_NUMMER]);
}

function isBunkerNummer($value) {
	return preg_match("/(ML|L|H|A|F|LW|BL|P|C|Bb|Do|GH|Ha|He|Ib|KO|KR|MS|Pe|Ps|Ro|Ry|Sb|Te|Th|We|TPM)\s*\d+/", $value) > 0;
}

?>
