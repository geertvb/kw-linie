<?php

define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER["DOCUMENT_ROOT"]);
define('DS', DIRECTORY_SEPARATOR);

require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');
require_once 'mysqliUtils.php';

$mainframe = JFactory::getApplication('site');
$mainframe->initialise();

$user = JFactory::getUser();

if (!$user->guest) {
	$user->access = "public";
	if ($mysqli = newMysqli()) {
		$sql = "select `toegang` from `kwl_contact` where `gebruikersnaam`=? limit 1";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $user->username);
			if ($stmt->execute()) {
				$row = getSingleResult($stmt);
				$user->access = $row->toegang;
			}
			$stmt->close();
		}
		$mysqli->close();
	}
}

$xml = new XMLWriter();
$xml->openMemory();
$xml->setIndent(true);
$xml->setIndentString(' ');
$xml->startDocument('1.0', 'UTF-8');
$xml->startElement("user");
$xml->writeElement('guest', $user->guest ? "true" : "false");
if (!$user->guest) {
	$xml->writeElement('id', $user->id);
	$xml->writeElement('name', $user->name);
	$xml->writeElement('username', $user->username);
	$xml->writeElement('usertype', $user->usertype);
	$xml->writeElement('access', $user->access);
}
$xml->endElement();
$xml->endDocument();

echo $xml->outputMemory();

?>