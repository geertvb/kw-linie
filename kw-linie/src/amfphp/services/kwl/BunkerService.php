<?php

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . "/../../.."));

include_once 'mysqliUtils.php';

restore_include_path();

class BunkerService {
	
	function findAllTypes() {
		return findSQL("select `bunker_type_id`, `label` from `kwl_bunker_type`");
	}

    function findAll() {
    	return findSQL("SELECT `bunker_id`, `type`, `nummer`, `gemeente`, `x`, `y` FROM `kwl_bunker`");
    }

}

?>