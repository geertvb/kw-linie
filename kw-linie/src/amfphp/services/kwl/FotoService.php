<?php

include_once '../../../mysqliUtils.php';

class FotoService {

	function findByUid($uid) {
		
		$sql = <<<SQL
SELECT
  `foto_id`,
  `omschrijving`,
  `filename`,
  `mimetype`,
  `width`,
  `height`,
  `size`
FROM
  `kwl_foto` 
WHERE
  `uid` = ?
SQL;

		if ($mysqli = newMysqli()) {
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->bind_param('s', $uid);
				if ($stmt->execute()) {
					$result = getSingleResult($stmt);
				}
				$stmt->close();
			}
			$mysqli->close();
		}
		
		return $result;	
	}
	
}
	