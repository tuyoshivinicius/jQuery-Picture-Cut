<?php

require_once('core/PictureCut.php');

try {

	$pictureCut = PictureCut::createSingleton();
	
	if($pictureCut->upload()){
		print $pictureCut->toJson();
	} else {
		print $pictureCut->exceptionsToJson();
	}

} catch (Exception $e) {
	print $e->getMessage();
}


