<? 
	
	//$_REQUEST['gallery_image_id'] = ;	

	$galleryImageService->serviceget($_connection);

	$_cropfile = $_REQUEST['image3_uri'];
	$size = getimagesize($_REQUEST['image3_schema']);
	
	//$_layout = NULL;
	$_layout = "layout/layout_crop.html";
		
	
?>