<?php 
$host = $_SERVER['HTTP_HOST'];
$ini_array = parse_ini_file("google-map-keys.ini");
foreach ($ini_array as $key => $value) {
	$pos = strrpos($host, $key);
	if (isset($pos) && $pos + strlen($key) == strlen($host)) {
		echo $value;
		exit;
	}
}
?>