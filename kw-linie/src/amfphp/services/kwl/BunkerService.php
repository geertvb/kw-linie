<?php

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . "/../../.."));

include_once 'mysqliUtils.php';

restore_include_path();

class BunkerService {
	
	function findAllTypes() {
		return findSQL("select `bunker_type_id`, `label` from `kwl_bunker_type`");
	}

    function findAll() {
    	$sql = "SELECT";
    	$sql .= "  `bunker_id`,";
    	$sql .= "  `kwl_bunker_type`.`label` as `type`,";
    	$sql .= "  `code`,";
    	$sql .= "  `nr`,";
    	$sql .= "  `nummer`,";
    	$sql .= "  `gemeente`,";
    	$sql .= "  `x`,";
    	$sql .= "  `y` ";
    	$sql .= "FROM";
    	$sql .= "  `kwl_bunker`";
    	$sql .= "LEFT OUTER JOIN";
    	$sql .= "  `kwl_bunker_type`";
    	$sql .= "ON";
    	$sql .= "  `kwl_bunker`.`type` = `kwl_bunker_type`.`bunker_type_id` ";
    	$sql .= "ORDER BY";
    	$sql .= "  `bunker_id` ASC";
    	return findSQL($sql);
    }
    
    function save($bunker) {
    	ob_start();
    	var_dump($bunker);
    	$result = ob_get_contents();
    	ob_end_clean();
    	return $result;
    }

}

?>