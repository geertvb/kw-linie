<?php

include_once '../../../mysqliUtils.php';

class BunkerTypeService {
	
    function findAll() {
		return findSQL("select * from `kwl_bunker_type` order by `type` asc");
	}

}

?>