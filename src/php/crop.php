<?php

require_once('core/PictureCut.php');

try {

	$pictureCut = PictureCut::createSingleton();
	
	if($pictureCut->crop()){
		print $pictureCut->toJson();
	} else {
     print $pictureCut->exceptionsToJson(); //print exceptions if the upload fails
  	}

} catch (Exception $e) {
	print $e->getMessage();
}


