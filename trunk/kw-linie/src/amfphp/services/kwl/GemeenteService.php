<?php

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . "/../../.."));

include_once 'mysqliUtils.php';

restore_include_path();

class GemeenteService {
	
    function findAll() {
    	return findSQL("SELECT `gemeente_id`, `naam`, `postcode` FROM `kwl_gemeente`");
    }

}

?>