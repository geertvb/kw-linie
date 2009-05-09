<?php

function createthumb($image_file, $thumb_file, $thumb_width = 128, $thumb_height = 96) {

	list($width, $height, $type) = getimagesize($image_file);
	
	switch ($type) {
	    case IMAGETYPE_GIF:
	        $image = imagecreatefromgif($image_file);
	        break;
	    case IMAGETYPE_JPEG:
	        $image = imagecreatefromjpeg($image_file);
	        break;
	    case IMAGETYPE_PNG:
	        $image = imagecreatefrompng($image_file);
	        break;
	    case IMAGETYPE_WBMP:
	        $image = imagecreatefromwbmp($image_file);
	        break;
	     default:
	     	// Use default thumbnail image
	        break;
	}
	
	if ($width * $thumb_height > $thumb_width * $height) {
		$new_width = $thumb_width;
		$new_height = $thumb_width * $height / $width;
	} else {
		$new_width = $thumb_height * $width / $height;
		$new_height = $thumb_height;
	}
	
	$thumb = imagecreatetruecolor($new_width, $new_height);
	
	imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	
	ImageJpeg($thumb, $thumb_file);
	
	ImageDestroy ($thumb);
	ImageDestroy ($image);
	
	return array($new_width, $new_height, $width, $height);
}

?>