<?php

include_once '../../../mysqliUtils.php';

class GebruikerService {

	function findAll() {
		$sql[] = "SELECT ";
		$sql[] = "  CONCAT(`name`, ' (', `username`, ')') as `label`,";
		$sql[] = "  `username` as `gebruikersnaam` ";
		$sql[] = "FROM ";
		$sql[] = "  `jos_users` ";
		$sql[] = "ORDER BY ";
		$sql[] = "  `name` ASC ";
		
    	return findSQL(implode(" ", $sql));
    }

}