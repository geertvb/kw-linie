<?php

define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER["DOCUMENT_ROOT"]);
define('DS', DIRECTORY_SEPARATOR);

require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');

$mainframe = JFactory::getApplication('site');
$mainframe->initialise();

$user = JFactory::getUser();

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
}
$xml->endElement();
$xml->endDocument();

echo $xml->outputMemory();

?>