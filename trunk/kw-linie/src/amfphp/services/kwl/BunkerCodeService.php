<?php

include_once '../../../mysqliUtils.php';

class BunkerCodeService {
	
    function findAll() {
		return findSQL("select * from `kwl_bunker_code` order by `code` asc");
	}

}

?>